// Quin Tech — admin CRUD interactions (Services, Projects, …)
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
    document.querySelectorAll('.flash-seed').forEach((el) => toast(el.dataset.msg, el.dataset.type === 'error'));

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

    /* ---------------- AJAX toggles ---------------- */
    document.querySelectorAll('input[data-toggle-url]').forEach((input) => {
        input.addEventListener('change', async () => {
            try {
                await post(input.dataset.toggleUrl);
                const item = input.closest('.sec-item');
                if (item) item.classList.toggle('off', !input.checked);
                toast(input.checked ? 'Turned on.' : 'Turned off.');
            } catch (e) {
                input.checked = !input.checked;
                toast('Could not save — try again.', true);
            }
        });
    });

    /* ---------------- sortable reorder ---------------- */
    document.querySelectorAll('[data-sortable]').forEach((list) => {
        if (!window.Sortable) return;
        Sortable.create(list, {
            handle: '.drag',
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: async () => {
                const order = [...list.querySelectorAll('[data-id]')].map((el) => parseInt(el.dataset.id, 10));
                try {
                    await post(list.dataset.reorderUrl, { order });
                    toast('Order saved.');
                } catch (e) {
                    toast('Could not save order.', true);
                }
            },
        });
    });

    /* ---------------- slug auto-fill ---------------- */
    const slugSource = document.querySelector('[data-slug-source]');
    const slugTarget = document.querySelector('[data-slug-target]');
    if (slugSource && slugTarget) {
        let touched = slugTarget.value.trim().length > 0;
        slugTarget.addEventListener('input', () => { touched = true; });
        slugSource.addEventListener('input', () => {
            if (touched) return;
            slugTarget.value = slugSource.value
                .toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        });
    }

    /* ---------------- Quill rich text ---------------- */
    const quillEl = document.querySelector('[data-quill]');
    if (quillEl && window.Quill) {
        const quill = new Quill(quillEl, {
            theme: 'snow',
            placeholder: 'Write the full description…',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    ['blockquote'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean'],
                ],
            },
        });
        const form = quillEl.closest('[data-quill-form]');
        const target = form?.querySelector('[data-quill-target]');
        if (form && target) {
            form.addEventListener('submit', () => {
                const html = quill.getText().trim().length ? quill.root.innerHTML : '';
                target.value = html;
            });
        }
    }
})();
