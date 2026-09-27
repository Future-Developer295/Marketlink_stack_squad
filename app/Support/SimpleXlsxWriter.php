<?php

namespace App\Support;

use ZipArchive;

/**
 * SimpleXlsxWriter
 *
 * A minimal, dependency-free multi-sheet .xlsx writer.
 *
 * This project does not have Maatwebsite/Excel or PhpOffice/PhpSpreadsheet
 * installed, so this class builds a valid Office Open XML (.xlsx) file
 * by hand using PHP's built-in ZipArchive extension. Each "sheet" added
 * becomes its own tab in the workbook, which is what lets the Reports
 * export put Sales / Orders / Farmers / Products into separate tables
 * instead of mixing everything into one.
 *
 * Visual style is matched to the MarketLink PDF reports:
 *   - a green "MarketLink" title banner across the top of each sheet
 *   - an optional subtitle line (date range / generated at)
 *   - a bold white-on-green header row
 *   - bordered cells with soft alternating (zebra) row shading
 *
 * Usage:
 *   $xlsx = new SimpleXlsxWriter();
 *   $xlsx->addSheet('Sales', ['ID', 'Customer', 'Amount'], [
 *       [1, 'Ali', 500],
 *       [2, 'Sara', 750],
 *   ], 'Sales Summary', 'Date Range: 01 Sep 2026 - 24 Sep 2026  |  Generated: 24 Sep 2026 10:00');
 *   $binary = $xlsx->output(); // raw xlsx bytes
 */
class SimpleXlsxWriter
{
    /** @var array<int, array{name:string, headers:array, rows:array, title:?string, subtitle:?string}> */
    protected array $sheets = [];

    public function addSheet(string $name, array $headers, array $rows, ?string $title = null, ?string $subtitle = null): static
    {
        // Excel sheet names can't exceed 31 chars or contain : \ / ? * [ ]
        $safeName = substr(preg_replace('/[:\\\\\/\?\*\[\]]/', '-', $name), 0, 31);

        $this->sheets[] = [
            'name' => $safeName ?: ('Sheet'.(count($this->sheets) + 1)),
            'headers' => array_values($headers),
            'rows' => $rows,
            'title' => $title,
            'subtitle' => $subtitle,
        ];

        return $this;
    }

    public function output(): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');

        $zip = new ZipArchive();
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addEmptyDir('_rels');
        $zip->addEmptyDir('docProps');
        $zip->addEmptyDir('xl');
        $zip->addEmptyDir('xl/_rels');
        $zip->addEmptyDir('xl/worksheets');

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->rootRelsXml());
        $zip->addFromString('docProps/core.xml', $this->coreXml());
        $zip->addFromString('docProps/app.xml', $this->appXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());

        foreach ($this->sheets as $index => $sheet) {
            $zip->addFromString(
                'xl/worksheets/sheet'.($index + 1).'.xml',
                $this->sheetXml($sheet)
            );
        }

        $zip->close();

        $contents = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $contents;
    }

    protected function contentTypesXml(): string
    {
        $overrides = '';
        foreach ($this->sheets as $index => $sheet) {
            $n = $index + 1;
            $overrides .= "<Override PartName=\"/xl/worksheets/sheet{$n}.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>";
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
            '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'.
            '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'.
            $overrides.
            '</Types>';
    }

    protected function rootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'.
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'.
            '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'.
            '</Relationships>';
    }

    protected function coreXml(): string
    {
        $now = date('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" '.
            'xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.
            '<dc:creator>MarketLink</dc:creator>'.
            '<dcterms:created xsi:type="dcterms:W3CDTF">'.$now.'</dcterms:created>'.
            '<dcterms:modified xsi:type="dcterms:W3CDTF">'.$now.'</dcterms:modified>'.
            '</cp:coreProperties>';
    }

    protected function appXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties">'.
            '<Application>MarketLink</Application>'.
            '</Properties>';
    }

    protected function workbookXml(): string
    {
        $sheetsXml = '';
        foreach ($this->sheets as $index => $sheet) {
            $n = $index + 1;
            $sheetsXml .= '<sheet name="'.$this->escape($sheet['name']).'" sheetId="'.$n.'" r:id="rId'.$n.'"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '.
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<sheets>'.$sheetsXml.'</sheets>'.
            '</workbook>';
    }

    protected function workbookRelsXml(): string
    {
        $rels = '';
        foreach ($this->sheets as $index => $sheet) {
            $n = $index + 1;
            $rels .= '<Relationship Id="rId'.$n.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet'.$n.'.xml"/>';
        }

        $stylesRid = count($this->sheets) + 1;
        $rels .= '<Relationship Id="rId'.$stylesRid.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            $rels.
            '</Relationships>';
    }

    /**
     * Style map (cellXfs indices), all built on top of MarketLink's brand green (#2F855A):
     *   0 = default (no border)
     *   1 = title banner   - big bold white text on green fill
     *   2 = subtitle       - small italic grey text, no fill
     *   3 = table header   - bold white text on green fill, thin border
     *   4 = data cell (odd)  - normal text, white fill, thin border
     *   5 = data cell (even) - normal text, light green fill, thin border (zebra stripe)
     */
    protected function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.

            '<fonts count="4">'.
            '<font><sz val="11"/><name val="Calibri"/></font>'.                                          // 0 normal
            '<font><sz val="16"/><name val="Calibri"/><b/><color rgb="FFFFFFFF"/></font>'.                // 1 title
            '<font><sz val="9"/><name val="Calibri"/><i/><color rgb="FF6B7280"/></font>'.                 // 2 subtitle
            '<font><sz val="11"/><name val="Calibri"/><b/><color rgb="FFFFFFFF"/></font>'.                // 3 header
            '</fonts>'.

            '<fills count="4">'.
            '<fill><patternFill patternType="none"/></fill>'.                                                                    // 0 none
            '<fill><patternFill patternType="solid"><fgColor rgb="FF2F855A"/><bgColor indexed="64"/></patternFill></fill>'.       // 1 brand green
            '<fill><patternFill patternType="solid"><fgColor rgb="FFF7FAF8"/><bgColor indexed="64"/></patternFill></fill>'.       // 2 zebra light green
            '<fill><patternFill patternType="solid"><fgColor rgb="FFFFFFFF"/><bgColor indexed="64"/></patternFill></fill>'.       // 3 white
            '</fills>'.

            '<borders count="2">'.
            '<border><left/><right/><top/><bottom/><diagonal/></border>'.                                                         // 0 none
            '<border>'.
            '<left style="thin"><color rgb="FFDFE4E1"/></left>'.
            '<right style="thin"><color rgb="FFDFE4E1"/></right>'.
            '<top style="thin"><color rgb="FFDFE4E1"/></top>'.
            '<bottom style="thin"><color rgb="FFDFE4E1"/></bottom>'.
            '<diagonal/>'.
            '</border>'.                                                                                                          // 1 thin light border
            '</borders>'.

            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'.

            '<cellXfs count="6">'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'.                                                                                     // 0 default
            '<xf numFmtId="0" fontId="1" fillId="1" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center"/></xf>'.    // 1 title
            '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"/>'.                                                                       // 2 subtitle
            '<xf numFmtId="0" fontId="3" fillId="1" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>'.                                         // 3 header
            '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0" applyFill="1" applyBorder="1"/>'.                                                       // 4 data odd
            '<xf numFmtId="0" fontId="0" fillId="2" borderId="1" xfId="0" applyFill="1" applyBorder="1"/>'.                                                       // 5 data even
            '</cellXfs>'.

            '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'.
            '</styleSheet>';
    }

    protected function sheetXml(array $sheet): string
    {
        $headers = $sheet['headers'];
        $rows = $sheet['rows'];
        $title = $sheet['title'];
        $subtitle = $sheet['subtitle'];

        $colCount = max(count($headers), 1);
        $lastColLetter = $this->columnLetter($colCount - 1);

        $currentRow = 1;
        $merges = [];
        $xml = '';

        // Title banner row (merged across all columns, green background)
        if ($title) {
            $xml .= '<row r="'.$currentRow.'" ht="26" customHeight="1">';
            $xml .= '<c r="A'.$currentRow.'" t="inlineStr" s="1"><is><t xml:space="preserve">MarketLink — '.$this->escape($title).'</t></is></c>';
            for ($c = 1; $c < $colCount; $c++) {
                $xml .= '<c r="'.$this->columnLetter($c).$currentRow.'" s="1"/>';
            }
            $xml .= '</row>';
            $merges[] = 'A'.$currentRow.':'.$lastColLetter.$currentRow;
            $currentRow++;
        }

        // Subtitle row (merged, grey italic - e.g. date range / generated at)
        if ($subtitle) {
            $xml .= '<row r="'.$currentRow.'" ht="18" customHeight="1">';
            $xml .= '<c r="A'.$currentRow.'" t="inlineStr" s="2"><is><t xml:space="preserve">'.$this->escape($subtitle).'</t></is></c>';
            for ($c = 1; $c < $colCount; $c++) {
                $xml .= '<c r="'.$this->columnLetter($c).$currentRow.'" s="2"/>';
            }
            $xml .= '</row>';
            $merges[] = 'A'.$currentRow.':'.$lastColLetter.$currentRow;
            $currentRow++;
        }

        // Blank spacer row if we had a banner, purely visual breathing room
        if ($title || $subtitle) {
            $xml .= '<row r="'.$currentRow.'" ht="6" customHeight="1"/>';
            $currentRow++;
        }

        // Header row (bold white on green, bordered)
        $headerRowNum = $currentRow;
        $xml .= '<row r="'.$headerRowNum.'">';
        foreach ($headers as $colIndex => $header) {
            $cellRef = $this->columnLetter($colIndex).$headerRowNum;
            $xml .= '<c r="'.$cellRef.'" t="inlineStr" s="3"><is><t xml:space="preserve">'.$this->escape((string) $header).'</t></is></c>';
        }
        $xml .= '</row>';
        $currentRow++;

        // Data rows, zebra striped + bordered
        foreach ($rows as $i => $row) {
            $rowNum = $currentRow;
            $style = ($i % 2 === 0) ? 4 : 5;

            $xml .= '<row r="'.$rowNum.'">';
            foreach (array_values($row) as $colIndex => $value) {
                $cellRef = $this->columnLetter($colIndex).$rowNum;

                if (is_numeric($value) && $value !== '' && ! preg_match('/^0[0-9]/', (string) $value)) {
                    $xml .= '<c r="'.$cellRef.'" s="'.$style.'"><v>'.$this->escape((string) $value).'</v></c>';
                } else {
                    $xml .= '<c r="'.$cellRef.'" t="inlineStr" s="'.$style.'"><is><t xml:space="preserve">'.$this->escape((string) $value).'</t></is></c>';
                }
            }
            $xml .= '</row>';
            $currentRow++;
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';

        // Reasonable default column width so text isn't squashed
        $sheetXml .= '<cols>';
        for ($c = 0; $c < $colCount; $c++) {
            $sheetXml .= '<col min="'.($c + 1).'" max="'.($c + 1).'" width="20" customWidth="1"/>';
        }
        $sheetXml .= '</cols>';

        $sheetXml .= '<sheetData>'.$xml.'</sheetData>';

        if (! empty($merges)) {
            $sheetXml .= '<mergeCells count="'.count($merges).'">';
            foreach ($merges as $ref) {
                $sheetXml .= '<mergeCell ref="'.$ref.'"/>';
            }
            $sheetXml .= '</mergeCells>';
        }

        $sheetXml .= '</worksheet>';

        return $sheetXml;
    }

    protected function columnLetter(int $index): string
    {
        // $index is 0-based (0 => A, 1 => B, 25 => Z, 26 => AA, ...)
        $index++;
        $letter = '';

        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    protected function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
