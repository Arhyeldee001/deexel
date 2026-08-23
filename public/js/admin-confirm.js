// Quin Tech — themed confirmation modal (replaces native confirm() across admin)
(function () {
    'use strict';

    var overlay, titleEl, msgEl, cancelBtn, confirmBtn, resolver;

    function build() {
        overlay = document.createElement('div');
        overlay.className = 'qt-modal-overlay';
        overlay.innerHTML =
            '<div class="qt-modal" role="dialog" aria-modal="true" aria-labelledby="qtModalTitle">' +
                '<h3 class="qt-modal-title" id="qtModalTitle"></h3>' +
                '<p class="qt-modal-msg"></p>' +
                '<div class="qt-modal-actions">' +
                    '<button type="button" class="qt-btn qt-btn-cancel">Cancel</button>' +
                    '<button type="button" class="qt-btn qt-btn-confirm">Confirm</button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(overlay);

        titleEl = overlay.querySelector('.qt-modal-title');
        msgEl = overlay.querySelector('.qt-modal-msg');
        cancelBtn = overlay.querySelector('.qt-btn-cancel');
        confirmBtn = overlay.querySelector('.qt-btn-confirm');

        cancelBtn.addEventListener('click', function () { close(false); });
        confirmBtn.addEventListener('click', function () { close(true); });
        overlay.addEventListener('click', function (e) { if (e.target === overlay) close(false); });
        document.addEventListener('keydown', function (e) {
            if (!overlay.classList.contains('open')) return;
            if (e.key === 'Escape') close(false);
            if (e.key === 'Enter') close(true);
        });
    }

    function close(result) {
        overlay.classList.remove('open');
        var r = resolver;
        resolver = null;
        if (r) r(result);
    }

    // Public API — returns a Promise<boolean>
    window.qtConfirm = function (message) {
        if (!overlay) build();
        var danger = /delete|remove|deactivat|sign out|log ?out/i.test(message || '');
        titleEl.textContent = danger ? 'Please confirm' : 'Are you sure?';
        msgEl.textContent = message || 'Please confirm this action.';
        confirmBtn.textContent = /delete/i.test(message || '') ? 'Delete' : (danger ? 'Confirm' : 'Confirm');
        confirmBtn.classList.toggle('danger', danger);
        overlay.classList.add('open');
        setTimeout(function () { confirmBtn.focus(); }, 30);
        return new Promise(function (resolve) { resolver = resolve; });
    };

    function wire(form) {
        if (form.__qtWired) return;
        form.__qtWired = true;
        form.addEventListener('submit', function (e) {
            if (form.__qtConfirmed) return;
            e.preventDefault();
            window.qtConfirm(form.getAttribute('data-confirm')).then(function (ok) {
                if (ok) { form.__qtConfirmed = true; form.submit(); }
            });
        });
    }

    function init() {
        // Upgrade any legacy onsubmit="return confirm('…')" forms to the themed modal
        document.querySelectorAll('form[onsubmit]').forEach(function (form) {
            var attr = form.getAttribute('onsubmit') || '';
            var m = attr.match(/confirm\(\s*['"]([^'"]*)['"]/);
            if (m) {
                form.setAttribute('data-confirm', m[1]);
                form.removeAttribute('onsubmit');
                form.onsubmit = null;
            }
        });
        // Wire every data-confirm form
        document.querySelectorAll('form[data-confirm]').forEach(wire);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
