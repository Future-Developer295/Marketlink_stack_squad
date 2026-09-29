<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('report')->insert([
            [
                'admin_id' => 1,
                'report_type' => 'Sales Report',
                'date_from' => '2026-10-01',
                'date_to' => '2026-10-07',
                'file_path' => 'reports/sales_report.pdf',
                'generated_at' => now(),
                'created_at' => now(),
            ],
            [
                'admin_id' => 1,
                'report_type' => 'Farmer Report',
                'date_from' => '2026-10-01',
                'date_to' => '2026-10-31',
                'file_path' => 'reports/farmer_report.pdf',
                'generated_at' => now(),
                'created_at' => now(),
            ],
            [
                'admin_id' => 1,
                'report_type' => 'Product Report',
                'date_from' => '2026-10-01',
                'date_to' => '2026-10-31',
                'file_path' => 'reports/product_report.pdf',
                'generated_at' => now(),
                'created_at' => now(),
            ],
        ]);
    }
}
