<script>
/**
 * initTypeahead(options)
 * options: {
 *   input: HTMLElement or selector,
 *   hiddenInput: HTMLElement or selector,
 *   suggestionsBox: HTMLElement or selector,
 *   searchUrl: string,
 *   createUrl: string|null,
 *   minLength: number (default 3),
 *   debounce: number ms (default 250),
 *   onSelect: function(item) - optional
 * }
 */
window.initTypeahead = function (options) {
    const cfg = Object.assign({ minLength: 3, debounce: 250, createUrl: null }, options || {});

    const el = (typeof cfg.input === 'string') ? document.querySelector(cfg.input) : cfg.input;
    const hidden = (typeof cfg.hiddenInput === 'string') ? document.querySelector(cfg.hiddenInput) : cfg.hiddenInput;
    const box = (typeof cfg.suggestionsBox === 'string') ? document.querySelector(cfg.suggestionsBox) : cfg.suggestionsBox;
    if (!el || !box) return null;

    let debounceTimer = null;
    let selectedIndex = -1;

    const clear = () => { box.innerHTML = ''; box.style.display = 'none'; selectedIndex = -1; };

    const escapeRegex = (s) => s.replace(/[.*+?^${}()|[\\]\\]/g, '\\$&');

    const updateHighlight = () => {
        const children = Array.from(box.children || []);
        children.forEach((ch, idx) => ch.classList.toggle('active', idx === selectedIndex));
    };

    const selectItem = (item) => {
        if (el) el.value = item.name || '';
        if (hidden) hidden.value = item.id || '';
        clear();
        if (typeof cfg.onSelect === 'function') cfg.onSelect(item);
    };

    const render = (items, query) => {
        box.innerHTML = '';
        if (!items || items.length === 0) { clear(); return; }
        const q = (query || '').trim();
        const re = q ? new RegExp('(' + escapeRegex(q) + ')', 'ig') : null;
        items.forEach((it, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-start';
            btn.style.whiteSpace = 'normal';
            const nameHtml = re ? (it.name || '').replace(re, '<strong>$1</strong>') : (it.name || '');
            const left = document.createElement('div'); left.innerHTML = nameHtml;
            const right = document.createElement('small'); right.className = 'text-muted ms-2'; right.textContent = it.phone || '';
            btn.appendChild(left); btn.appendChild(right);
            btn.addEventListener('click', () => selectItem(it));
            btn.addEventListener('mouseenter', () => { selectedIndex = idx; updateHighlight(); });
            box.appendChild(btn);
        });

        // create new option
        if (cfg.createUrl && q.length >= cfg.minLength) {
            const createEl = document.createElement('button');
            createEl.type = 'button';
            createEl.className = 'list-group-item list-group-item-action text-primary';
            createEl.innerHTML = `Create new: <strong>${q}</strong>`;
            createEl.addEventListener('click', () => {
                // POST to create
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                fetch(cfg.createUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ name: q })
                }).then(r => r.json()).then(data => {
                    if (data && data.id) selectItem(data);
                }).catch(() => {});
            });
            box.appendChild(createEl);
        }

        box.style.display = 'block';
        selectedIndex = -1;
    };

    el.addEventListener('input', function () {
        const q = this.value || '';
        if (hidden) hidden.value = '';
        if (debounceTimer) clearTimeout(debounceTimer);
        if (q.length < cfg.minLength) { clear(); return; }
        debounceTimer = setTimeout(() => {
            fetch(cfg.searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => render(data, q))
                .catch(() => clear());
        }, cfg.debounce);
    });

    el.addEventListener('keydown', function (ev) {
        const children = Array.from(box.children || []);
        if (ev.key === 'ArrowDown') { ev.preventDefault(); if (children.length === 0) return; selectedIndex = Math.min(children.length - 1, selectedIndex + 1); updateHighlight(); }
        else if (ev.key === 'ArrowUp') { ev.preventDefault(); if (children.length === 0) return; selectedIndex = Math.max(0, selectedIndex - 1); updateHighlight(); }
        else if (ev.key === 'Enter') { if (selectedIndex >= 0 && children[selectedIndex]) { ev.preventDefault(); children[selectedIndex].click(); } }
        else if (ev.key === 'Escape') { clear(); }
    });

    el.addEventListener('blur', function () { setTimeout(clear, 150); });

    return { clear, render, selectItem };
};
</script>
