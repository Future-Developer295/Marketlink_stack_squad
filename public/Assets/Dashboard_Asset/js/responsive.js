/* MarketLink dashboard — responsive helpers
   1) makes every .dtable responsive: ID, Name and Actions columns always stay.
      The other columns get hc-1/hc-2/hc-3 classes (least important = hidden first)
      and responsive.css hides them with display:none via container queries on the
      table wrapper. Their values move to a small line under the name. A measuring
      safety net hides further columns if the table still would not fit.
   2) small drawer / dropdown niceties. */
(function () {
  'use strict';

  var ID_RE = /^\s*(#\s*id|#|id|no\.?|sr\.?)\s*$/i;
  var ICON_RE = /^\s*(icon|image|photo|avatar|logo)\s*$/i;
  var ACTION_RE = /^\s*(actions?|options?|file|download)\s*$/i;
  var IMPORTANT_RE = /status|total|price|amount|stock|qty|quantity|approval/i;

  function headText(th) { return (th.textContent || '').replace(/\s+/g, ' ').trim(); }

  function prepareTable(table) {
    if (table._rt) return table._rt;
    var ths = table.querySelectorAll('thead th');
    if (!ths.length) return null;

    var labels = [], roles = [], i;
    for (i = 0; i < ths.length; i++) labels.push(headText(ths[i]));

    var idIdx = -1, nameIdx = -1;
    for (i = 0; i < labels.length; i++) { if (ID_RE.test(labels[i])) { idIdx = i; break; } }

    for (i = 0; i < labels.length; i++) {
      if (i === idIdx) roles[i] = 'id';
      else if (labels[i] === '' || ACTION_RE.test(labels[i])) roles[i] = 'actions';
      else if (ICON_RE.test(labels[i])) roles[i] = 'keep';
      else if (nameIdx === -1) { nameIdx = i; roles[i] = 'name'; }
      else roles[i] = 'hide';
    }

    // hide order: least important first (right -> left), important columns (status, price...) last
    var low = [], high = [];
    for (i = labels.length - 1; i >= 0; i--) {
      if (roles[i] !== 'hide') continue;
      (IMPORTANT_RE.test(labels[i]) ? high : low).push(i);
    }

    var order = low.concat(high), n = order.length, tiers = {};
    var wide = ths.length >= 7;
    // tier 1 hides first (widest containers), tier 3 hides last (narrowest)
    order.forEach(function (col, k) {
      if (n <= 2 || !wide) tiers[col] = (k < Math.ceil(n / 2)) ? 2 : 3;
      else tiers[col] = k < Math.floor(n / 3) ? 1 : (k < Math.floor(n / 3) + Math.ceil((n - Math.floor(n / 3)) / 2) ? 2 : 3);
    });

    for (i = 0; i < ths.length; i++) {
      if (roles[i] === 'id') ths[i].classList.add('rt-id');
      if (roles[i] === 'actions') ths[i].classList.add('rt-actions');
      if (roles[i] === 'name') ths[i].classList.add('rt-name');
      if (tiers[i]) ths[i].classList.add('hc-' + tiers[i]);
    }
    var wrapEl = table.closest('.tbl-wrap, .table-responsive');
    if (wrapEl) wrapEl.classList.add(wide ? 'rt-wide' : 'rt-narrow');

    table._rt = { labels: labels, roles: roles, order: order, tiers: tiers, cols: ths.length, nameIdx: nameIdx, ths: ths };
    return table._rt;
  }

  function cloneValue(cell) {
    var c = cell.cloneNode(true);
    var junk = c.querySelectorAll('form, details, script, button, input, textarea, select, [id]');
    for (var j = 0; j < junk.length; j++) junk[j].remove();
    return c;
  }

  function prepareRows(table, cfg) {
    var rows = table.querySelectorAll('tbody tr');
    for (var r = 0; r < rows.length; r++) {
      var tr = rows[r];
      if (tr._rtDone) continue;
      var cells = tr.children;
      if (cells.length !== cfg.cols) continue; // e.g. "No records" colspan row
      tr._rtDone = true;

      var extra = null;
      for (var i = 0; i < cells.length; i++) {
        var role = cfg.roles[i], td = cells[i];
        if (role === 'id') td.classList.add('rt-id');
        if (role === 'actions') td.classList.add('rt-actions');
        if (role === 'name') td.classList.add('rt-name');
        if (role !== 'hide') continue;
        var tier = cfg.tiers[i];
        td.classList.add('hc-' + tier);
        var txt = (td.textContent || '').replace(/\s+/g, ' ').trim();
        if (txt && txt !== '—' && txt !== '-') {
          if (!extra) { extra = document.createElement('div'); extra.className = 'cell-extra'; }
          var span = document.createElement('span');
          span.className = 'rt-x x' + tier;
          extra.classList.add('h' + tier);
          span.setAttribute('data-col', i);
          var b = document.createElement('b');
          b.textContent = cfg.labels[i] + ': ';
          span.appendChild(b);
          var val = cloneValue(td);
          while (val.firstChild) span.appendChild(val.firstChild);
          extra.appendChild(span);
        }
      }
      if (extra && cfg.nameIdx > -1 && cells[cfg.nameIdx]) cells[cfg.nameIdx].appendChild(extra);
    }
  }

  function setColumn(table, cfg, col, off) {
    cfg.ths[col].classList.toggle('rt-off', off);
    var rows = table.querySelectorAll('tbody tr');
    for (var r = 0; r < rows.length; r++) {
      var tr = rows[r];
      if (tr.children.length !== cfg.cols) continue;
      tr.children[col].classList.toggle('rt-off', off);
      var x = tr.querySelector('.rt-x[data-col="' + col + '"]');
      if (x) x.classList.toggle('rt-on', off);
    }
  }

  function refreshExtras(table) {
    var boxes = table.querySelectorAll('.cell-extra');
    for (var i = 0; i < boxes.length; i++) {
      boxes[i].classList.toggle('rt-has', !!boxes[i].querySelector('.rt-x.rt-on'));
    }
  }

  // Show every column, then hide the least important ones until the table fits.
  function fitTable(table) {
    var cfg = table._rt;
    if (!cfg || !cfg.order.length) return;
    var wrap = table.closest('.tbl-wrap, .table-responsive') || table.parentElement;
    var i;
    for (i = 0; i < cfg.cols; i++) if (cfg.roles[i] === 'hide') setColumn(table, cfg, i, false);

    // measure natural (one-line, capped) width of each column
    table.classList.add('rt-measure');
    var widths = [], total = 0;
    for (i = 0; i < cfg.cols; i++) {
      var w = cfg.ths[i].getBoundingClientRect().width;
      widths.push(w);
      total += w;
    }
    table.classList.remove('rt-measure');

    var avail = wrap.clientWidth - 2;
    var k = 0;
    while (total > avail && k < cfg.order.length) {
      var col = cfg.order[k++];
      if (!widths[col]) continue; // already hidden by its breakpoint
      total -= widths[col];
      setColumn(table, cfg, col, true);
    }
    refreshExtras(table);
  }

  function processAll() {
    var tables = document.querySelectorAll('table.dtable');
    for (var t = 0; t < tables.length; t++) {
      var cfg = prepareTable(tables[t]);
      if (!cfg) continue;
      var before = tables[t]._rtRows;
      prepareRows(tables[t], cfg);
      var count = tables[t].querySelectorAll('tbody tr').length;
      var width = (tables[t].closest('.tbl-wrap, .table-responsive') || tables[t].parentElement).clientWidth;
      if (before !== count || tables[t]._rtWidth !== width) {
        tables[t]._rtRows = count;
        tables[t]._rtWidth = width;
        fitTable(tables[t]);
      }
    }
  }

  var timer = null;
  function schedule() { clearTimeout(timer); timer = setTimeout(processAll, 60); }

  function initTables() {
    processAll();
    // AJAX search / pagination replaces the table markup — re-apply automatically
    if ('MutationObserver' in window) {
      new MutationObserver(schedule).observe(document.body, { childList: true, subtree: true });
    }
    window.addEventListener('resize', schedule);
    window.addEventListener('orientationchange', schedule);
    window.addEventListener('load', schedule);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () {
      var ts = document.querySelectorAll('table.dtable');
      for (var i = 0; i < ts.length; i++) ts[i]._rtWidth = null;
      schedule();
    });
  }

  function initShell() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.querySelector('.sidebar-overlay');
    if (sidebar && 'MutationObserver' in window) {
      new MutationObserver(function () {
        document.body.classList.toggle('nav-open', sidebar.classList.contains('show'));
      }).observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
    function closeNav() {
      if (sidebar) sidebar.classList.remove('show');
      if (overlay) overlay.classList.remove('show');
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeNav();
        var m = document.getElementById('userMenu');
        if (m) m.classList.remove('show');
      }
    });
    window.addEventListener('resize', function () { if (window.innerWidth > 992) closeNav(); });
    document.addEventListener('click', function (e) {
      var m = document.getElementById('userMenu');
      if (m && m.classList.contains('show') && !e.target.closest('.user-dropdown')) m.classList.remove('show');
    });
  }

  function init() { initShell(); initTables(); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
