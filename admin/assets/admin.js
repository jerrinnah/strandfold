/* Admin behaviour: repeater rows and image previews. Vanilla, no build step. */
(function () {
  'use strict';

  /* ---- repeaters ---- */
  document.querySelectorAll('[data-rep]').forEach(function (rep) {
    var rows = rep.querySelector('[data-rep-rows]');
    var tpl  = rep.querySelector('[data-rep-template]');
    var add  = rep.querySelector('[data-rep-add]');

    function reindex() {
      var key = rep.getAttribute('data-rep');
      rows.querySelectorAll('[data-rep-row]').forEach(function (row, i) {
        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(
            new RegExp('^rep\\[' + key + '\\]\\[[^\\]]*\\]'),
            'rep[' + key + '][' + i + ']'
          );
        });
      });
    }

    if (add && tpl) {
      add.addEventListener('click', function () {
        var html = tpl.innerHTML.replace(/__I__/g, String(Date.now()).slice(-6));
        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        var row = wrap.firstElementChild;
        rows.appendChild(row);
        reindex();
        var first = row.querySelector('input,textarea,select');
        if (first) first.focus();
      });
    }

    rows.addEventListener('click', function (e) {
      var del = e.target.closest('[data-rep-del]');
      if (!del) return;
      var row = del.closest('[data-rep-row]');
      var title = row.querySelector('.rep-row-title');
      var name = title ? title.textContent.trim() : 'this entry';
      if (!confirm('Remove ' + name + '? It disappears from the site when you save.')) return;
      row.remove();
      reindex();
    });

    /* Keep the collapsed row title in step with what is being typed. */
    rows.addEventListener('input', function (e) {
      var row = e.target.closest('[data-rep-row]');
      if (!row) return;
      var first = row.querySelector('.rep-row-body input[type=text]');
      var title = row.querySelector('.rep-row-title');
      if (first && title && e.target === first) {
        title.textContent = first.value.trim() || 'New entry';
      }
    });
  });

  /* ---- image picker preview ---- */
  document.querySelectorAll('[data-imgpick]').forEach(function (pick) {
    var sel = pick.querySelector('[data-imgpick-file]');
    var box = pick.querySelector('.imgpick-preview');
    if (!sel || !box) return;
    sel.addEventListener('change', function () {
      if (!sel.value) { box.innerHTML = '<span class="imgpick-empty">No image</span>'; return; }
      box.innerHTML = '<img alt="">';
      box.querySelector('img').src = '../' + sel.value;
    });
  });

  /* ---- warn before leaving an edited form ---- */
  var form = document.querySelector('form.editor');
  if (form) {
    var dirty = false;
    form.addEventListener('input', function () { dirty = true; });
    form.addEventListener('submit', function () { dirty = false; });
    window.addEventListener('beforeunload', function (e) {
      if (!dirty) return;
      e.preventDefault();
      e.returnValue = '';
    });
  }
})();
