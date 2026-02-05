(function (window, document) {
    'use strict';

    // initTypeahead: options { input, hiddenInput, suggestionsBox, searchUrl, createUrl, minLength=3, debounce=250 }
    window.initTypeahead = function (options) {
        const input = document.querySelector(options.input);
        const hidden = document.querySelector(options.hiddenInput);
        const suggestionsBox = document.querySelector(options.suggestionsBox);
        const searchUrl = options.searchUrl;
        const createUrl = options.createUrl;
        const minLength = options.minLength || 3;
        const debounceMs = options.debounce || 250;
        if (!input || !suggestionsBox || !searchUrl) return;

        let debounceTimer = null;
        let selectedIndex = -1;

        const clear = function () {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
            selectedIndex = -1;
        };

        const select = function (id, name, item) {
            input.value = name;
            if (hidden) hidden.value = id;
            clear();
            // Call onSelect callback if provided
            if (options.onSelect && typeof options.onSelect === 'function') {
                options.onSelect(item || { id: id, name: name });
            }
        };

        const escapeRegex = function (s) { return s.replace(/[.*+?^${}()|[\\]\\]/g, '\\$&'); };

        const updateHighlight = function () {
            const children = Array.from(suggestionsBox.children);
            children.forEach((ch, idx) => ch.classList.toggle('active', idx === selectedIndex));
        };

        const render = function (items, q) {
            suggestionsBox.innerHTML = '';
            if (!items || items.length === 0) { clear(); return; }
            const re = q ? new RegExp('(' + escapeRegex(q) + ')', 'ig') : null;

            items.forEach((it, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-start';
                btn.style.whiteSpace = 'normal';

                const left = document.createElement('div');
                left.innerHTML = re ? it.name.replace(re, '<strong>$1</strong>') : (it.name || '');
                const right = document.createElement('small');
                right.className = 'text-muted ms-2';
                right.textContent = it.phone || '';

                btn.appendChild(left);
                btn.appendChild(right);

                btn.addEventListener('click', function () { select(it.id, it.name, it); });
                btn.addEventListener('mouseenter', function () { selectedIndex = idx; updateHighlight(); });
                suggestionsBox.appendChild(btn);
            });

            // create new option
            const createQuery = (q || '').trim();
            if (createQuery.length >= minLength && createUrl) {
                const createBtn = document.createElement('button');
                createBtn.type = 'button';
                createBtn.className = 'list-group-item list-group-item-action text-primary';
                createBtn.innerHTML = 'Create new: <strong>' + createQuery + '</strong>';
                createBtn.addEventListener('click', function () { create(createQuery); });
                createBtn.addEventListener('mouseenter', function () { selectedIndex = suggestionsBox.children.length - 1; updateHighlight(); });
                suggestionsBox.appendChild(createBtn);
            }

            suggestionsBox.style.display = 'block';
            selectedIndex = -1;
        };

        const create = function (name) {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
            fetch(createUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name })
            }).then(r => r.json())
                .then(data => {
                    if (data && data.id) select(data.id, data.name, data);
                }).catch(() => {
                    // noop
                });
        };

        input.addEventListener('input', function () {
            const q = this.value || '';
            if (hidden) hidden.value = '';
            
            // Call onChange callback if provided
            if (options.onChange && typeof options.onChange === 'function') {
                options.onChange(q);
            }
            
            if (debounceTimer) clearTimeout(debounceTimer);
            if (q.length < minLength) { clear(); return; }
            debounceTimer = setTimeout(() => {
                fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(response => {
                        // Extract data array from response (API returns {success: true, data: [...]})
                        const items = response.data || response || [];
                        render(items, q);
                    })
                    .catch(() => clear());
            }, debounceMs);
        });

        input.addEventListener('keydown', function (ev) {
            const children = Array.from(suggestionsBox.children);
            if (ev.key === 'ArrowDown') {
                ev.preventDefault();
                if (children.length === 0) return;
                selectedIndex = Math.min(children.length - 1, selectedIndex + 1);
                updateHighlight();
            } else if (ev.key === 'ArrowUp') {
                ev.preventDefault();
                if (children.length === 0) return;
                selectedIndex = Math.max(0, selectedIndex - 1);
                updateHighlight();
            } else if (ev.key === 'Enter') {
                if (selectedIndex >= 0 && children[selectedIndex]) {
                    ev.preventDefault();
                    children[selectedIndex].click();
                }
            } else if (ev.key === 'Escape') {
                clear();
            }
        });

        input.addEventListener('blur', function () { setTimeout(clear, 150); });
    };

    // initCurrencyCalculator: opts { formSelector, foreignSelector, baseSelector, rateSelector }
    window.initCurrencyCalculator = function (opts) {
        const form = document.querySelector(opts.formSelector);
        if (!form) return;
        const foreignEl = form.querySelector(opts.foreignSelector);
        const baseEl = form.querySelector(opts.baseSelector);
        const rateEl = form.querySelector(opts.rateSelector);

        window.purchaseLastEdited = window.purchaseLastEdited || 'foreign';

        window.onForeignAmountInput = function () {
            window.purchaseLastEdited = 'foreign';
            computeSyp();
        };

        window.onBaseAmountInput = function () {
            window.purchaseLastEdited = 'base';
            computeFromBase();
        };

        window.onRateInput = function () {
            if (window.purchaseLastEdited === 'base') computeFromBase(); else computeSyp();
        };

        const computeFromBase = window.computePurchaseFromBase = function () {
            if (!form) return;
            const base = parseFloat((baseEl && baseEl.value) || 0);
            const rate = parseFloat((rateEl && rateEl.value) || 0);
            const foreign = (base && rate) ? (base / rate) : 0;
            if (foreignEl) foreignEl.value = foreign ? foreign.toFixed(4) : '';
        };

        const computeSyp = window.computePurchaseSyp = function () {
            if (!form) return;
            const foreign = parseFloat((foreignEl && foreignEl.value) || 0);
            const rate = parseFloat((rateEl && rateEl.value) || 0);
            const base = (foreign && rate) ? (foreign * rate) : 0;
            if (baseEl) baseEl.value = base ? base.toFixed(2) : '';
        };

        // attach listeners if elements exist (forms may use oninput attributes already)
        if (foreignEl) foreignEl.addEventListener('input', window.onForeignAmountInput);
        if (baseEl) baseEl.addEventListener('input', window.onBaseAmountInput);
        if (rateEl) rateEl.addEventListener('input', window.onRateInput);
    };

    // Backwards-compatible aliases used across some pages
    window.computeSaleSyp = function () { if (typeof window.computePurchaseSyp === 'function') return window.computePurchaseSyp(); };
    window.computeSaleFromBase = function () { if (typeof window.computePurchaseFromBase === 'function') return window.computePurchaseFromBase(); };

})(window, document);
