// Quin Tech — admin section editor interactions
(function () {
    'use strict';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    /* ---------------- toast ---------------- */
    const toastEl = document.getElementById('toast');
    let toastTimer;
    function toast(msg, isError) {
        if (!toastEl) return;
        toastEl.textContent = msg;
        toastEl.classList.toggle('error', !!isError);
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2600);
    }

    // Flash messages seeded from the server
    document.querySelectorAll('.flash-seed').forEach((el) => {
        toast(el.dataset.msg, el.dataset.type === 'error');
    });

    async function post(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: body ? JSON.stringify(body) : null,
        });
        if (!res.ok) throw new Error('Request failed (' + res.status + ')');
        return res.json();
    }

    /* ---------------- AJAX toggles (pages + sections) ---------------- */
    document.querySelectorAll('input[data-toggle-url]').forEach((input) => {
        input.addEventListener('change', async () => {
            const url = input.dataset.toggleUrl;
            try {
                const data = await post(url);
                const item = input.closest('.sec-item');
                if (item) item.classList.toggle('off', !input.checked);
                const state = ('is_active' in data) ? data.is_active : data.show_in_nav;
                toast(state ? 'Turned on.' : 'Turned off.');
            } catch (e) {
                input.checked = !input.checked; // revert
                toast('Could not save — try again.', true);
            }
        });
    });

    /* ---------------- accordion panels (page settings) ---------------- */
    document.querySelectorAll('[data-accordion]').forEach((head) => {
        head.addEventListener('click', () => head.closest('.panel').classList.toggle('open'));
    });

    /* ---------------- section expand/collapse ---------------- */
    document.querySelectorAll('[data-expand]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            btn.closest('.sec-item').classList.toggle('open');
        });
    });
    // Also expand when clicking the header name (but not on controls)
    document.querySelectorAll('.sec-head').forEach((head) => {
        head.addEventListener('click', (e) => {
            if (e.target.closest('.toggle, .icon-btn, form, .drag')) return;
            head.closest('.sec-item').classList.toggle('open');
        });
    });

    /* ---------------- drag-and-drop reorder ---------------- */
    const list = document.getElementById('sections');
    if (list && window.Sortable) {
        Sortable.create(list, {
            handle: '.drag',
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: async () => {
                const order = [...list.querySelectorAll('.sec-item')].map((el) => parseInt(el.dataset.id, 10));
                try {
                    await post(list.dataset.reorderUrl, { order });
                    toast('Order saved.');
                } catch (e) {
                    toast('Could not save order.', true);
                }
            },
        });
    }

    /* ---------------- repeatable rows ---------------- */
    document.querySelectorAll('[data-repeat-add]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const wrap = btn.closest('.repeat-wrap');
            const container = wrap.querySelector('[data-repeat]');
            const tpl = wrap.querySelector('[data-repeat-template]');
            let next = parseInt(container.dataset.next || '0', 10);
            const html = tpl.innerHTML.replace(/__i__/g, next);
            const frag = document.createElement('div');
            frag.innerHTML = html.trim();
            const row = frag.firstElementChild;
            container.appendChild(row);
            container.dataset.next = next + 1;
            row.querySelector('input, select')?.focus();
        });
    });

    // Delegate remove clicks (works for rows added later too)
    document.addEventListener('click', (e) => {
        const rm = e.target.closest('[data-repeat-remove]');
        if (!rm) return;
        const row = rm.closest('[data-repeat-row]');
        if (!row) return;
        row.classList.add('removing');
        setTimeout(() => row.remove(), 190);
    });
})();
