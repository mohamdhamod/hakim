/**
 * ============================================================================
 * General JavaScript Utilities
 * ============================================================================
 * 
 * Centralized utility functions for the entire application
 * 
 * @version 4.0 (Phase 4: Advanced Features)
 * @date January 31, 2026
 * @author Senior Laravel Architect +Senior Product Designer +  Senior Doctor
 * 

 * 
 * ============================================================================
 */

'use strict';

// ================================
// CONSTANTS AND CONFIGURATION
// ================================

const CONFIG = {
    TOAST_DELAY: 5000,
    ANIMATION_DURATION: 220,
    DEBOUNCE_DELAY: 300,
    PRINT_DELAY: 250
};

// ================================
// SWEETALERT UNIFIED HELPER
// ================================

/**
 * Unified SweetAlert helper to reduce duplicate code patterns
 */
const SwalHelper = {
    /**
     * Check if SweetAlert is available
     */
    isAvailable() {
        return typeof Swal !== 'undefined' && typeof Swal.fire === 'function';
    },

    /**
     * Show loading dialog
     */
    showLoading(title, text = '') {
        if (!this.isAvailable()) return null;
        return Swal.fire({
            title: title || window.i18n?.messages?.processing || 'Processing...',
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    },

    /**
     * Show success message
     */
    success(title, text = '', options = {}) {
        if (!this.isAvailable()) {
            alert(title);
            return Promise.resolve({ isConfirmed: true });
        }
        return Swal.fire({
            icon: 'success',
            title,
            text,
            timer: options.timer || 2500,
            timerProgressBar: true,
            ...options
        });
    },

    /**
     * Show error message
     */
    error(title, text = '', options = {}) {
        if (!this.isAvailable()) {
            alert(title + (text ? '\n' + text : ''));
            return Promise.resolve({ isConfirmed: true });
        }
        return Swal.fire({
            icon: 'error',
            title,
            text,
            confirmButtonText: window.i18n?.messages?.ok || 'OK',
            ...options
        });
    },

    /**
     * Show warning/confirmation dialog
     */
    confirm(title, text = '', options = {}) {
        if (!this.isAvailable()) {
            return Promise.resolve({ isConfirmed: confirm(title + (text ? '\n' + text : '')) });
        }
        return Swal.fire({
            icon: options.icon || 'warning',
            title,
            text,
            showCancelButton: true,
            confirmButtonColor: options.confirmButtonColor || '#3085d6',
            cancelButtonColor: options.cancelButtonColor || '#d33',
            confirmButtonText: options.confirmButtonText || window.i18n?.messages?.confirm || 'Confirm',
            cancelButtonText: options.cancelButtonText || window.i18n?.messages?.cancel || 'Cancel',
            ...options
        });
    },

    /**
     * Show toast notification
     */
    toast(title, icon = 'success', options = {}) {
        if (!this.isAvailable()) {
            console.log(`[${icon}] ${title}`);
            return Promise.resolve();
        }
        return Swal.fire({
            icon,
            title,
            toast: true,
            position: options.position || 'top-end',
            showConfirmButton: false,
            timer: options.timer || 2500,
            timerProgressBar: true,
            ...options
        });
    },

    /**
     * Close any open Swal
     */
    close() {
        if (this.isAvailable()) Swal.close();
    }
};

// ================================
// RECAPTCHA v3 HELPERS
// ================================

const RecaptchaV3 = (function () {
    const siteKey = document.querySelector('meta[name="recaptcha-site-key"]')?.getAttribute('content') || '';
    let _loadPromise = null;
    let _cooldownUntil = 0;

    function isAvailable() {
        return Boolean(siteKey) && typeof window.grecaptcha !== 'undefined' && typeof window.grecaptcha.execute === 'function';
    }

    function ensureLoaded(timeoutMs = 2500) {
        if (!siteKey) return Promise.resolve(false);
        if (isAvailable()) return Promise.resolve(true);

        // Avoid repeated polling if reCAPTCHA is blocked (ad-block, CSP, offline, etc.)
        if (_cooldownUntil && Date.now() < _cooldownUntil) return Promise.resolve(false);

        if (_loadPromise) return _loadPromise;

        _loadPromise = new Promise((resolve) => {
            const start = Date.now();

            // If script tag is missing (e.g., blocked partial render), attempt to inject it.
            try {
                const existing = document.querySelector('script[src*="www.google.com/recaptcha/api.js"]');
                if (!existing) {
                    const s = document.createElement('script');
                    s.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(siteKey)}`;
                    s.async = true;
                    s.defer = true;
                    document.head.appendChild(s);
                }
            } catch (_) {
                // ignore
            }

            const tick = () => {
                if (isAvailable()) return resolve(true);
                if (Date.now() - start >= timeoutMs) return resolve(false);
                setTimeout(tick, 80);
            };
            tick();
        }).then((ok) => {
            // If load failed, wait before retrying to prevent request storms.
            if (!ok) _cooldownUntil = Date.now() + 30000;
            return ok;
        }).finally(() => {
            // If it loaded, keep the resolved promise cached.
            // If it failed, allow retry after cooldown.
            if (!isAvailable()) {
                _loadPromise = null;
            }
        });

        return _loadPromise;
    }

    function normalizeAction(action) {
        // Google reCAPTCHA v3 action name must only contain A-Za-z/_ (no digits, no hyphens).
        const a = String(action || 'submit')
            .trim()
            .toLowerCase()
            .replace(/-/g, '_')
            .replace(/[^a-z_\/]/g, '_')
            .replace(/_+/g, '_')
            .replace(/\/+?/g, '/')
            .replace(/^_+|_+$/g, '')
            .replace(/\/_+/g, '/')
            .replace(/_+\//g, '/')
            .slice(0, 80);
        return a || 'submit';
    }

    function actionFromPathname(pathname) {
        const raw = String(pathname || '').split('?')[0].split('#')[0];
        const segments = raw.split('/').filter(Boolean);
        const kept = [];

        for (let i = 0; i < segments.length; i++) {
            const segRaw = String(segments[i] || '').trim();
            if (!segRaw) continue;

            // Drop locale prefixes like /ar or /en
            if (i === 0 && /^[a-z]{2}$/i.test(segRaw)) continue;

            // Drop numeric IDs and common opaque identifiers (uuid/hex-like)
            if (/^\d+$/.test(segRaw)) continue;
            if (/^[0-9a-f]{8,}$/i.test(segRaw)) continue;

            let seg = segRaw
                .toLowerCase()
                .replace(/-/g, '_')
                .replace(/[^a-z_]/g, '_')
                .replace(/_+/g, '_')
                .replace(/^_+|_+$/g, '');

            if (!seg) continue;
            kept.push(seg);
        }

        return kept.join('/');
    }

    function deriveAction(form) {
        if (!form) return 'submit';
        const explicit = form.getAttribute('data-recaptcha-action');
        if (explicit) return normalizeAction(explicit);
        if (form.id) return normalizeAction('form_' + form.id);
        const url = form.action || window.location.pathname;
        try {
            const path = new URL(url, window.location.origin).pathname;
            return normalizeAction(actionFromPathname(path));
        } catch (_) {
            return 'submit';
        }
    }

    function deriveActionFromUrl(url, method = 'submit') {
        const m = String(method || 'submit').trim().toLowerCase();
        try {
            const path = new URL(String(url || ''), window.location.origin).pathname;
            const cleaned = actionFromPathname(path);
            return normalizeAction(`${m}_${cleaned || 'submit'}`);
        } catch (_) {
            return normalizeAction(`${m}_submit`);
        }
    }

    async function execute(action) {
        if (!isAvailable()) {
            const ok = await ensureLoaded();
            if (!ok || !isAvailable()) return null;
        }
        const act = normalizeAction(action);
        return await new Promise((resolve) => {
            try {
                window.grecaptcha.ready(() => {
                    window.grecaptcha.execute(siteKey, { action: act })
                        .then(token => resolve(token))
                        .catch(() => resolve(null));
                });
            } catch (_) {
                resolve(null);
            }
        });
    }

    function ensureHiddenInputs(form) {
        if (!form) return;

        if (!form.querySelector('input[name="g-recaptcha-response"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            form.appendChild(input);
        }
        if (!form.querySelector('input[name="recaptcha_action"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recaptcha_action';
            form.appendChild(input);
        }
    }

    function setOnForm(form, token, action) {
        ensureHiddenInputs(form);
        const tokenEl = form.querySelector('input[name="g-recaptcha-response"]');
        const actionEl = form.querySelector('input[name="recaptcha_action"]');
        if (tokenEl) tokenEl.value = token || '';
        if (actionEl) actionEl.value = normalizeAction(action);
    }

    return {
        isAvailable,
        ensureLoaded,
        deriveAction,
        deriveActionFromUrl,
        execute,
        setOnForm,
        siteKey
    };
})();

// ================================
// GLOBAL SESSION-EXPIRE HANDLERS
// ================================

// Read login URL from meta or fallback
const LOGIN_URL = (document.querySelector('meta[name="login-url"]')?.getAttribute('content')) || '/login';

// Wrap window.fetch to auto-redirect on 401/419
if (typeof window.fetch === 'function' && !window.__fetchSessionWrapped) {
    const __origFetch = window.fetch.bind(window);
    window.fetch = async (input, init = undefined) => {
        // Add reCAPTCHA v3 token for same-origin non-idempotent requests
        try {
            const method = String((init && init.method) || (input instanceof Request ? input.method : 'GET') || 'GET').toUpperCase();
            if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method) && RecaptchaV3 && RecaptchaV3.siteKey) {
                const url = (input instanceof Request) ? input.url : String(input || '');
                const parsed = new URL(url, window.location.href);

                // Only attach token to same-origin requests
                if (parsed.origin === window.location.origin) {
                    const baseHeaders = (init && init.headers)
                        ? init.headers
                        : (input instanceof Request ? input.headers : undefined);

                    const headers = new Headers(baseHeaders || {});

                    if (!headers.has('X-Recaptcha-Token')) {
                        const action = RecaptchaV3.deriveActionFromUrl(parsed.href, method.toLowerCase());
                        const token = await RecaptchaV3.execute(action);
                        if (token) {
                            headers.set('X-Recaptcha-Token', token);
                            init = Object.assign({}, init || {}, { headers });
                        }
                    }
                }
            }
        } catch (_) {
            // If token generation fails, proceed without it; server middleware will decide.
        }

        const resp = await __origFetch(input, init);
        if (resp && (resp.status === 401 || resp.status === 419)) {
            try {
                ToastManager.show((window.i18n?.messages?.session_expired_redirecting || window.i18n?.messages?.session_expired || ''), 'warning');
            } catch (_) { /* no-op */ }
            // Small delay to let the toast render
            setTimeout(() => { window.location.href = LOGIN_URL; }, 500);
        }
        return resp;
    };
    window.__fetchSessionWrapped = true;
}

// ================================
// TOAST NOTIFICATION SYSTEM
// ================================

const ToastManager = (function() {
    let container = null;
    const DEFAULT_DELAY = CONFIG.TOAST_DELAY;

    function ensureStyles() {
        if (document.getElementById('ins-toast-styles')) return;

        const css = `
        .ins-toast-container{position:fixed;right:1rem;bottom:1rem;z-index:10850;display:flex;flex-direction:column;gap:0.5rem;max-width:360px;pointer-events:none}
        @media (max-width: 576px){.ins-toast-container{left:50%;right:auto;bottom:1rem;transform:translateX(-50%);max-width:92vw}}
        .ins-toast{pointer-events:auto;display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0.75rem;border-radius:0.5rem;color:#fff;box-shadow:0 6px 18px rgba(0,0,0,0.12);overflow:hidden;transform-origin:right center;transition:opacity .18s ease,transform .18s ease}
        .ins-toast .ins-body{flex:1;font-size:0.95rem}
        .ins-toast .ins-close{background:transparent;border:0;color:inherit;opacity:0.85;cursor:pointer}
        .ins-toast.info{background:linear-gradient(90deg,#0d6efd,#6f42c1)}
        .ins-toast.success{background:linear-gradient(90deg,#198754,#20c997)}
        .ins-toast.danger{background:linear-gradient(90deg,#dc3545,#e55353)}
        .ins-toast.warning{background:linear-gradient(90deg,#fd7e14,#ffc107)}
        .ins-toast .ins-progress{height:3px;background:rgba(255,255,255,0.85);width:100%;transform-origin:left center}
        `;

        const style = document.createElement('style');
        style.id = 'ins-toast-styles';
        style.textContent = css;
        document.head.appendChild(style);
    }

    function ensureContainer() {
        if (container) return container;
        container = document.createElement('div');
        container.className = 'ins-toast-container';
        document.body.appendChild(container);
        return container;
    }

    function hideToast(el) {
        if (!el || !el.parentNode) return;

        el.style.opacity = '0';
        el.style.transform = 'translateY(8px)';

        if (el._timer) {
            clearTimeout(el._timer);
            delete el._timer;
        }

        setTimeout(() => {
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }, CONFIG.ANIMATION_DURATION);
    }

    function show(message, type = 'info', options = {}) {
        if (!message) return null;

        ensureStyles();
        const c = ensureContainer();
        const delay = options.delay || DEFAULT_DELAY;
        const autoHide = options.autoHide !== false;

        const el = document.createElement('div');
        el.className = `ins-toast ${type}`;
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        el.innerHTML = `
            <div class="ins-body"></div>
            <button type="button" class="ins-close" aria-label="Close">&times;</button>
            <div class="ins-progress"></div>
        `;

        const closeBtn = el.querySelector('.ins-close');
        const body = el.querySelector('.ins-body');
        const prog = el.querySelector('.ins-progress');

        // Safer text injection to avoid XSS from untrusted message strings
        if (body) body.textContent = String(message);

        closeBtn.addEventListener('click', () => hideToast(el));
        c.appendChild(el);

        // Entrance animation
        requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });

        // Progress animation
        if (prog && autoHide) {
            prog.style.width = '100%';
            prog.style.transition = `width ${delay}ms linear`;
            requestAnimationFrame(() => { prog.style.width = '0%'; });
        }

        if (autoHide) {
            el._timer = setTimeout(() => hideToast(el), delay);
        } else if (prog) {
            prog.style.opacity = '0.48';
        }

        return el;
    }

    return {
        show,
        hide: hideToast
    };
})();

// Expose Toast globally for backward compatibility
if (!window.Toast) {
    window.Toast = ToastManager;
}

// Expose SwalHelper as SwalUtil for backward compatibility
window.SwalUtil = SwalHelper;

// ================================
// UTILITY FUNCTIONS
// ================================

const Utils = {
    /**
     * Debounce function execution
     */
    debounce(func, delay = CONFIG.DEBOUNCE_DELAY) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    },

    /**
     * Get CSRF token
     */
    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    },

    /**
     * Strip HTML tags from string
     */
    stripHtml(html) {
        if (!html) return '';
        const temp = document.createElement('div');
        temp.innerHTML = html;
        return temp.textContent || temp.innerText || '';
    },

    /**
     * Remove HTML tags using regex (alternative method)
     */
    removeHtmlTags(str) {
        if (!str) return '';
        return str.replace(/<[^>]*>/g, '');
    },

    /**
     * Format date for display - supports multiple formats
     */
    formatDate(date, options = {}) {
        if (!date) return '';
        const d = new Date(date);
        const locale = options.locale || 'en-US';
        const formatOptions = options.format || {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return d.toLocaleString(locale, formatOptions);
    },

    /**
     * Safe JSON parse
     */
    safeJsonParse(str, fallback = null) {
        try {
            return JSON.parse(str);
        } catch (e) {
            return fallback;
        }
    },

    /**
     * Escape HTML special characters to prevent XSS when building HTML strings.
     */
    escapeHtml(value) {
        const str = value === null || value === undefined ? '' : String(value);
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },

    /**
     * Truncate text to specified number of words
     */
    truncateWords(value, maxWords = 10) {
        const str = value === null || value === undefined ? '' : String(value);
        const words = str.trim().split(/\s+/).filter(Boolean);
        if (words.length <= maxWords) return str.trim();
        return `${words.slice(0, maxWords).join(' ')}...`;
    },

    /**
     * Get CSS variable value from :root
     * @param {string} varName - Variable name (with or without --)
     * @returns {string} The CSS variable value
     */
    getCssVar(varName) {
        const name = varName.startsWith('--') ? varName : `--${varName}`;
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    },

    /**
     * Common theme colors from CSS variables
     */
    colors: {
        get primary() { return Utils.getCssVar('--ins-primary') || '#428177'; },
        get secondary() { return Utils.getCssVar('--ins-secondary') || '#054239'; },
        get success() { return Utils.getCssVar('--ins-success') || '#22c55e'; },
        get danger() { return Utils.getCssVar('--ins-danger') || '#ef4444'; },
        get warning() { return Utils.getCssVar('--ins-warning') || '#eab308'; },
        get info() { return Utils.getCssVar('--ins-info') || '#06b6d4'; },
        get indigo() { return Utils.getCssVar('--ins-indigo') || '#6366f1'; },
        get blue() { return Utils.getCssVar('--ins-blue') || '#3b82f6'; },
        get teal() { return Utils.getCssVar('--ins-teal') || '#14b8a6'; },
        get bodyColor() { return Utils.getCssVar('--ins-body-color') || '#374151'; },
        get bodyBg() { return Utils.getCssVar('--ins-body-bg') || '#f9f9f9'; },
        get white() { return Utils.getCssVar('--ins-white') || '#fff'; },
        get black() { return Utils.getCssVar('--ins-black') || '#030712'; },
        // Social Media Colors
        social: {
            get facebook() { return Utils.getCssVar('--ins-social-facebook') || '#1877F2'; },
            get facebookGreen() { return Utils.getCssVar('--ins-social-facebook-green') || '#42B72A'; },
            get facebookText() { return Utils.getCssVar('--ins-social-facebook-text') || '#050505'; },
            get facebookSecondary() { return Utils.getCssVar('--ins-social-facebook-secondary') || '#65676B'; },
            get facebookReaction() { return Utils.getCssVar('--ins-social-facebook-reaction') || '#F0284A'; },
            get twitter() { return Utils.getCssVar('--ins-social-twitter') || '#1DA1F2'; },
            get twitterBlue() { return Utils.getCssVar('--ins-social-twitter-blue') || '#1D9BF0'; },
            get twitterBg() { return Utils.getCssVar('--ins-social-twitter-bg') || '#000000'; },
            get twitterBorder() { return Utils.getCssVar('--ins-social-twitter-border') || '#2F3336'; },
            get twitterText() { return Utils.getCssVar('--ins-social-twitter-text') || '#E7E9EA'; },
            get twitterSecondary() { return Utils.getCssVar('--ins-social-twitter-secondary') || '#71767B'; },
            get linkedin() { return Utils.getCssVar('--ins-social-linkedin') || '#0A66C2'; },
            get linkedinDark() { return Utils.getCssVar('--ins-social-linkedin-dark') || '#004182'; },
            get linkedinBg() { return Utils.getCssVar('--ins-social-linkedin-bg') || '#F4F2EE'; },
            get linkedinReaction() { return Utils.getCssVar('--ins-social-linkedin-reaction') || '#E16745'; },
            get linkedinCelebrate() { return Utils.getCssVar('--ins-social-linkedin-celebrate') || '#6DAE4F'; },
            get instagram() { return Utils.getCssVar('--ins-social-instagram') || '#E4405F'; },
            get instagramBlue() { return Utils.getCssVar('--ins-social-instagram-blue') || '#0095F6'; },
            get instagramSecondary() { return Utils.getCssVar('--ins-social-instagram-secondary') || '#A8A8A8'; },
            get instagramBorder() { return Utils.getCssVar('--ins-social-instagram-border') || '#262626'; },
            get instagramLink() { return Utils.getCssVar('--ins-social-instagram-link') || '#E0F1FF'; },
            get tiktokCyan() { return Utils.getCssVar('--ins-social-tiktok-cyan') || '#25F4EE'; },
            get tiktokPink() { return Utils.getCssVar('--ins-social-tiktok-pink') || '#FE2C55'; },
            get tiktokBlue() { return Utils.getCssVar('--ins-social-tiktok-blue') || '#20D5EC'; }
        }
    }
};

// ================================
// FORM MANAGEMENT
// ================================

const FormManager = {
    /**
     * Fill form fields with data
     */
    fillForm(container, data) {
        if (!container || !data || typeof data !== 'object') return;

        const cont = typeof container === 'string' ? document.querySelector(container) : container;
        if (!cont) return;

        // Fill input fields by name attribute
        Object.keys(data).forEach(key => {
            const input = cont.querySelector(`[name="${key}"]`);
            if (!input) return;

            switch (input.type) {
                case 'checkbox':
                    input.checked = Boolean(data[key]);
                    break;
                case 'radio':
                    const radioInput = cont.querySelector(`[name="${key}"][value="${data[key]}"]`);
                    if (radioInput) radioInput.checked = true;
                    break;
                default:
                    input.value = data[key] || '';
            }
        });

        // Fill fields by ID with special handling for dates
        const fieldMappings = {
            'id': 'editConfigTitleId',
            'key': 'key',
            'page': 'page',
            'title': 'title',
            'description': 'description',
            'created_at': 'created_at',
            'updated_at': 'updated_at'
        };

        Object.entries(fieldMappings).forEach(([dataKey, fieldId]) => {
            const element = cont.querySelector(`#${fieldId}`);
            if (element && data[dataKey] !== undefined) {
                if (dataKey.endsWith('_at')) {
                    element.value = Utils.formatDate(data[dataKey]);
                } else {
                    element.value = data[dataKey] || '';
                }
            }
        });
    },

    /**
     * Clear form validation errors
     */
    clearFormErrors(container) {
        if (!container) return;

        const cont = typeof container === 'string' ? document.querySelector(container) : container;
        if (!cont) return;

        // Remove error classes and messages
        const errorSelectors = ['.is-invalid', '.is-valid', '.invalid-feedback', '.valid-feedback'];
        errorSelectors.forEach(selector => {
            cont.querySelectorAll(selector).forEach(el => {
                if (selector.startsWith('.is-')) {
                    el.classList.remove(selector.substring(1));
                } else {
                    el.remove();
                }
            });
        });

        // Remove error messages with specific class
        cont.querySelectorAll('.text-danger.error-message').forEach(el => el.remove());
        cont.classList.remove('was-validated');
    },

    /**
     * Reset form data and clear errors
     */
    resetForm(form) {
        if (!form) return;
        form.reset();
        this.clearFormErrors(form);
    },

    /**
     * Display form validation errors
     */
    displayFormErrors(form, errors) {
        if (!form || !errors || typeof errors !== 'object') return;

        form.classList.add('was-validated');
        let firstErrorField = null;

        Object.entries(errors).forEach(([fieldName, fieldErrors]) => {
            const field = form.querySelector(`[name="${fieldName}"]`) || form.querySelector(`#${fieldName}`);
            if (!field) return;

            field.classList.add('is-invalid');
            field.classList.remove('is-valid');

            // Remove existing error message
            const existingError = field.parentNode.querySelector('.invalid-feedback');
            if (existingError) {
                existingError.remove();
            }

            // Add new error message (build nodes safely to avoid HTML injection)
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            const icon = document.createElement('i');
            icon.className = 'bi bi-exclamation-circle me-1';
            const text = document.createTextNode(Array.isArray(fieldErrors) ? fieldErrors[0] : String(fieldErrors));
            errorDiv.appendChild(icon);
            errorDiv.appendChild(text);
            field.parentNode.appendChild(errorDiv);

            // Track first error field for focus
            if (!firstErrorField) {
                firstErrorField = field;
            }
        });

        // Focus and scroll to first error field
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    },

    /**
     * Show duplicate confirmation dialog for patient creation
     */
    showDuplicateConfirmation(form, result) {
        const i18n = window.i18n || {};
        const messages = i18n.messages || {};
        const isRTL = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
        
        // Build duplicate patient info HTML
        let duplicatesHtml = '';
        if (result.duplicates && result.duplicates.length > 0) {
            duplicatesHtml = result.duplicates.map(dup => {
                const matchTypes = {
                    'name': messages.duplicate_match_name || 'Name match',
                    'phone': messages.duplicate_match_phone || 'Phone match', 
                    'email': messages.duplicate_match_email || 'Email match'
                };
                const matchType = matchTypes[dup.match_type] || dup.match_type;
                const confidence = Math.round((dup.confidence || 0) * 100);
                
                return `
                    <div class="card mb-2 border-warning">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${Utils.escapeHtml(dup.name)}</strong>
                                    <br><small class="text-muted">#${Utils.escapeHtml(dup.file_number)}</small>
                                    ${dup.phone ? `<br><small class="text-muted">${Utils.escapeHtml(dup.phone)}</small>` : ''}
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark">${matchType}</span>
                                    <br><small class="text-muted">${messages.duplicate_confidence || 'Confidence'}: ${confidence}%</small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="${window.location.origin}${window.location.pathname.replace('/create', '')}/${dup.id}" 
                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye me-1"></i>${messages.view_existing || 'View Existing'}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Create modal element
        const modalId = 'duplicateConfirmModal';
        let modal = document.getElementById(modalId);
        if (modal) {
            modal.remove();
        }

        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning-subtle">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                ${messages.duplicate_warning || 'Potential Duplicate Patient'}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">${Utils.escapeHtml(result.message)}</p>
                            <div class="duplicate-list">
                                ${duplicatesHtml}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>${messages.cancel || 'Cancel'}
                            </button>
                            <button type="button" class="btn btn-primary" id="confirmDuplicateBtn">
                                <i class="fas fa-plus me-1"></i>${messages.create_anyway || 'Create New Patient Anyway'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        modal = document.getElementById(modalId);

        // Show modal using Bootstrap
        let bsModal;
        if (window.bootstrap && window.bootstrap.Modal) {
            bsModal = new window.bootstrap.Modal(modal);
            bsModal.show();
        } else {
            // Fallback without Bootstrap JS
            modal.classList.add('show');
            modal.style.display = 'block';
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }

        // Handle confirm button click
        const confirmBtn = document.getElementById('confirmDuplicateBtn');
        confirmBtn.addEventListener('click', async () => {
            // Add confirm_duplicate field to form and resubmit
            let confirmField = form.querySelector('input[name="confirm_duplicate"]');
            if (!confirmField) {
                confirmField = document.createElement('input');
                confirmField.type = 'hidden';
                confirmField.name = 'confirm_duplicate';
                form.appendChild(confirmField);
            }
            confirmField.value = '1';

            // Hide modal
            if (bsModal) {
                bsModal.hide();
            } else {
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }

            // Resubmit form
            const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
            form.dispatchEvent(submitEvent);
        });

        // Cleanup modal on close
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
    },

    /**
     * Handle AJAX form submission
     */
    async handleFormSubmit(event, form) {
        if (!event || !form) return;
        event.preventDefault();

        this.clearFormErrors(form);

        // Show loading state - find submit button in modal or in form directly
        const modal = form.closest('.modal');

        // Look for submit button with multiple strategies
        let submitButton = form.querySelector('button[type="submit"]');

        if (!submitButton && modal) {
            // Try to find the primary button in modal footer (common pattern)
            submitButton = modal.querySelector('.modal-footer .btn-primary') ||
                modal.querySelector('.modal-footer button[type="button"]:not(.btn-secondary)') ||
                modal.querySelector('button[type="submit"]');
        }

        if (!submitButton) {
            // Fallback: any primary button in form
            submitButton = form.querySelector('.btn-primary');
        }

        const originalText = submitButton?.innerHTML || '';
        const originalDisabled = submitButton?.disabled || false;

        if (submitButton) {
            submitButton.disabled = true;
            const txt = (window.i18n && window.i18n.messages && window.i18n.messages.processing) ? window.i18n.messages.processing : '';
            submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> ' + txt;
        }

        // Function to restore button state
        const restoreButton = () => {
            if (submitButton) {
                submitButton.disabled = originalDisabled;
                submitButton.innerHTML = originalText;
            }
        };

        try {
            const formData = new FormData(form);
            const url = form.action || window.location.href;

            // Build request headers early so we can attach reCAPTCHA token header if available
            const requestHeaders = {
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };

            // Add reCAPTCHA v3 token for non-idempotent requests
            if (RecaptchaV3 && RecaptchaV3.siteKey) {
                // Avoid double-token generation if a previous hook already attached it
                const existingToken = formData.get('g-recaptcha-response');
                const existingAction = formData.get('recaptcha_action');
                if (!existingToken || !existingAction) {
                    const action = RecaptchaV3.deriveAction(form);
                    const token = await RecaptchaV3.execute(action);
                    if (token) {
                        formData.set('g-recaptcha-response', token);
                        formData.set('recaptcha_action', action);
                        requestHeaders['X-Recaptcha-Token'] = token;
                    }
                } else {
                    requestHeaders['X-Recaptcha-Token'] = String(existingToken);
                }
            }

            // Create timeout controller (30 seconds max)
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000);

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                signal: controller.signal,
                // Ensure cookies (session) are sent so authenticated requests succeed
                credentials: 'same-origin',
                headers: requestHeaders
            });
            clearTimeout(timeoutId);

            // Try to handle JSON and non-JSON responses gracefully
            const contentType = (response.headers.get('content-type') || '').toLowerCase();
            let result = null;

            if (contentType.includes('application/json')) {
                // Parse JSON safely
                try {
                    let responseText = await response.clone().text();
                    // Remove BOM if present (some PHP servers add it)
                    if (responseText.charCodeAt(0) === 0xFEFF) {
                        responseText = responseText.slice(1);
                    }
                    // Also handle UTF-8 BOM sequence
                    responseText = responseText.replace(/^\xEF\xBB\xBF/, '').trim();
                    result = JSON.parse(responseText);
                } catch (e) {
                    // Malformed JSON; treat as generic failure
                    throw new Error(window.i18n?.messages?.invalid_server_response || 'Invalid server response');
                }

                // Some servers return success=false with a 200/2xx or a 4xx status.
                // Accept both shapes: check HTTP status and result.success flag.
                const serverIndicatesFailure = (result && (result.success === false));

                if (response.ok && !serverIndicatesFailure) {
                    // Success path for JSON
                    const successMessage = result.message || (window.i18n?.messages?.operation_completed_successfully || '');

                    // Redirect if provided - store message in session to show after redirect
                    if (result.redirect) {
                        try {
                            sessionStorage.setItem('toastMessage', JSON.stringify({
                                message: successMessage,
                                type: 'success'
                            }));
                        } catch(e) {
                            // Session storage not available
                        }
                        // Use setTimeout to ensure the redirect happens after current execution
                        setTimeout(() => {
                            window.location.assign(result.redirect);
                        }, 10);
                        return;
                    }

                    // Only show toast and restore button if not redirecting
                    restoreButton();
                    ToastManager.show(successMessage, 'success');

                    if (modal) {
                        ModalManager.hide(modal);
                    }

                    if (window.currentDataTable?.ajax?.reload) {
                        window.currentDataTable.ajax.reload();
                    }
                } else {
                    // Error path for JSON or explicit success=false

                    // Special handling for duplicate confirmation flow
                    if (result && result.requires_confirmation && result.duplicates) {
                        restoreButton();
                        this.showDuplicateConfirmation(form, result);
                        return;
                    }

                    // If the server returned validation-style errors, show them on the form
                    if (result && result.errors) {
                        restoreButton();
                        this.displayFormErrors(form, result.errors);
                    }

                    // If the server included a redirect, follow it (useful for auth/company flows)
                    if (result && result.redirect) {
                        // Store message in session to show after redirect
                        if (result.message) {
                            sessionStorage.setItem('toastMessage', JSON.stringify({
                                message: result.message,
                                type: (response.status >= 500 ? 'danger' : 'warning')
                            }));
                        }
                        window.location.href = result.redirect;
                        return;
                    }

                    // If server provided a message and no redirect, show it now
                    if (result && result.message) {
                        restoreButton();
                        ToastManager.show(result.message, (response.status >= 500 ? 'danger' : 'warning'));
                    }

                    // Specific handling for authorization failures
                    if (response.status === 403) {
                        ToastManager.show((window.i18n?.messages?.not_authorized || ''), 'warning');
                        setTimeout(() => { window.location.href = LOGIN_URL; }, 600);
                        return;
                    }

                    // If we already displayed field errors or message, stop here.
                    if ((result && (result.errors || result.message || result.redirect))) {
                        return;
                    }


                    // Fallback: unknown error
                    restoreButton();
                    const msg = (result && (result.message || result.error)) || (window.i18n?.messages?.operation_failed || '');
                    throw new Error(msg);
                }
            } else {
                // Non-JSON (likely HTML redirect or validation page). Handle redirects and full-page updates.

                if (response.redirected) {
                    // Server redirected; follow it explicitly to update document
                    window.location.href = response.url;
                    return;
                }

                // Read text to detect HTML document
                const text = await response.text();

                if (response.ok) {
                    // If we received an HTML document, replace current document to show server-rendered page/state
                    if (text && (text.includes('<!DOCTYPE html') || text.includes('<html'))) {
                        document.open();
                        document.write(text);
                        document.close();
                        return;
                    }

                    // Fallback: reload current page
                    window.location.reload();
                    return;
                }

                // Non-ok and non-JSON: show generic error
                restoreButton();
                throw new Error(window.i18n?.messages?.unexpected_response || '');
            }
        } catch (error) {
            restoreButton();

            // Handle timeout specifically
            if (error.name === 'AbortError') {
                ToastManager.show(window.i18n?.messages?.request_timeout || '', 'danger');
            } else {
                ToastManager.show(error.message || (window.i18n?.messages?.an_error_occurred || ''), 'danger');
            }
        }
    }
};

// Expose FormManager globally
window.FormManager = FormManager;

// ================================
// MODAL MANAGEMENT
// ================================

const ModalManager = {
    /**
     * Show modal with backdrop and keyboard handling
     */
    show(modal) {
        if (!modal) return;

        modal.style.display = 'block';
        modal.classList.add('show', 'in');
        modal.classList.remove('out');
        document.body.classList.add('modal-open');

        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = `modal-backdrop-${Date.now()}`;
        document.body.appendChild(backdrop);

        // Handle ESC key
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                this.hide(modal);
                document.removeEventListener('keydown', handleEsc);
            }
        };
        document.addEventListener('keydown', handleEsc);

        // Handle backdrop click
        backdrop.addEventListener('click', () => this.hide(modal));

        // Store cleanup function on modal
        modal._cleanup = () => {
            document.removeEventListener('keydown', handleEsc);
        };
    },

    /**
     * Hide modal and clean up
     */
    hide(modal) {
        if (!modal) return;

        modal.style.display = 'none';
        modal.classList.remove('show', 'in');
        modal.classList.add('out');
        document.body.classList.remove('modal-open');

        // Remove backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

        // Clean up event listeners
        if (modal._cleanup) {
            modal._cleanup();
            delete modal._cleanup;
        }
    }
};

// ================================
// DATA TABLE UTILITIES
// ================================

const DataTableManager = {
    currentDataTable: null,

    /**
     * Set current DataTable reference
     */
    setCurrentDataTable(dataTable) {
        this.currentDataTable = dataTable;
        window.currentDataTable = dataTable; // Backward compatibility
    },

    /**
     * Get current DataTable reference
     */
    getCurrentDataTable() {
        return this.currentDataTable;
    },

    /**
     * Initialize column visibility controls
     */
    initializeColumnVisibility(tableSelector = '.dataTable', checkboxSelector = '.column-toggle') {
        const tableElement = document.querySelector(tableSelector);
        if (!tableElement) return;

        // Initialize column toggle checkboxes
        document.querySelectorAll(checkboxSelector).forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (this.currentDataTable) {
                    const column = this.currentDataTable.column(parseInt(checkbox.dataset.column));
                    column.visible(checkbox.checked);
                }
                this.updateColumnCount(checkboxSelector);
            });
        });

        this.updateColumnCount(checkboxSelector);
    },

    /**
     * Toggle all columns visibility
     */
    toggleAllColumns(visible, checkboxSelector = '.column-toggle') {
        document.querySelectorAll(checkboxSelector).forEach(checkbox => {
            checkbox.checked = visible;
            if (this.currentDataTable) {
                const column = this.currentDataTable.column(parseInt(checkbox.dataset.column));
                column.visible(visible);
            }
        });
        this.updateColumnCount(checkboxSelector);
    },

    /**
     * Update visible column count display
     */
    updateColumnCount(checkboxSelector = '.column-toggle', countElementId = 'column-count') {
        const checkboxes = document.querySelectorAll(checkboxSelector);
        const visibleCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const totalCount = checkboxes.length;

        const countElement = document.getElementById(countElementId);
        if (countElement) {
            countElement.textContent = `${visibleCount} of ${totalCount} columns visible`;
        }
    },

    /**
     * Export table data to CSV
     */
    exportTableToCSV(tableSelector = '.dataTable', filename = 'export.csv', respectVisibility = true) {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error('Table not found');
            return;
        }

        const tableData = this.extractTableData(table, respectVisibility);
        const csvContent = tableData.map(row =>
            row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(',')
        ).join('\n');

        this.downloadFile(csvContent, filename, 'text/csv;charset=utf-8;');
        ToastManager.show(window.i18n?.messages?.table_export_success || '', 'success');
    },

    /**
     * Copy table data to clipboard
     */
    copyTableToClipboard(tableSelector = '.dataTable', respectVisibility = true) {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error('Table not found');
            return;
        }

        const tableData = this.extractTableData(table, respectVisibility);
        const tsvData = tableData.map(row => row.join('\t')).join('\n');

        navigator.clipboard.writeText(tsvData).then(() => {
            ToastManager.show(window.i18n?.messages?.table_copy_success || '', 'success');
        }).catch(err => {
            console.error('Failed to copy to clipboard:', err);
            ToastManager.show(window.i18n?.messages?.table_copy_failed || '', 'danger');
        });
    },

    /**
     * Print table data
     */
    printTable(tableSelector = '.dataTable', title = null, respectVisibility = true) {
        const table = document.querySelector(tableSelector);
        if (!table) {
            console.error('Table not found');
            return;
        }

        const resolvedTitle = title || window.i18n?.messages?.data_export || '';
        const generatedOnLabel = window.i18n?.messages?.generated_on || '';

        const tableData = this.extractTableData(table, respectVisibility);
        const tableHTML = this.generatePrintableTable(tableData);

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${resolvedTitle}</title>
                <meta charset="utf-8">
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    tr:hover { background-color: #f5f5f5; }
                    @media print {
                        .no-print { display: none; }
                        body { margin: 0; }
                        table { page-break-inside: auto; }
                        tr { page-break-inside: avoid; page-break-after: auto; }
                        th { page-break-after: avoid; }
                    }
                    @page { margin: 1cm; }
                </style>
            </head>
            <body>
                <h2>${resolvedTitle}</h2>
                <p style="color: #666; font-size: 12px; margin-bottom: 20px;">
                    ${generatedOnLabel} ${new Date().toLocaleString()}
                </p>
                ${tableHTML}
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
        }, CONFIG.PRINT_DELAY);

        ToastManager.show(window.i18n?.messages?.print_dialog_opened || '', 'info');
    },

    /**
     * Extract table data for export
     */
    extractTableData(table, respectVisibility) {
        const data = [];
        const headers = [];

        // Extract headers
        const headerCells = table.querySelectorAll('thead th');
        headerCells.forEach((cell, index) => {
            if (!respectVisibility || !this.currentDataTable || this.currentDataTable.column(index).visible()) {
                headers.push(Utils.stripHtml(cell.textContent.trim()));
            }
        });
        data.push(headers);

        // Extract data rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (!respectVisibility || !this.currentDataTable || this.currentDataTable.column(index).visible()) {
                    rowData.push(Utils.stripHtml(cell.textContent.trim()));
                }
            });
            if (rowData.length > 0) {
                data.push(rowData);
            }
        });

        return data;
    },

    /**
     * Generate printable HTML table
     */
    generatePrintableTable(tableData) {
        if (!tableData.length) return `<p>${(window.i18n?.messages?.no_data_to_display || '')}</p>`;

        const [headers, ...rows] = tableData;
        const headerHTML = headers.map(header => `<th>${header}</th>`).join('');
        const rowsHTML = rows.map(row =>
            `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`
        ).join('');

        return `
            <table>
                <thead><tr>${headerHTML}</tr></thead>
                <tbody>${rowsHTML}</tbody>
            </table>
        `;
    },

    /**
     * Download file helper
     */
    downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
};

// ================================
// UI UTILITIES
// ================================

const UIManager = {
    /**
     * Initialize dropdown behavior
     */
    initializeDropdownBehavior(dropdownSelector = '.dropdown-menu') {
        document.querySelectorAll(dropdownSelector).forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                if (dropdown.querySelector('.column-toggle')) {
                    e.stopPropagation();
                }
            });
        });
    },

    /**
     * Initialize password toggle functionality
     */
    bindPasswordToggle(toggleSelector = null, inputSelector = null) {
        // Explicit selectors take precedence
        if (toggleSelector && inputSelector) {
            const toggle = document.querySelector(toggleSelector);
            const input = document.querySelector(inputSelector);
            if (toggle && input) {
                this.setupPasswordToggle(toggle, input);
            }
            return;
        }

        // Preferred robust binding: pair within each input-group
        const groups = document.querySelectorAll('.input-group');
        let bound = false;
        groups.forEach(group => {
            const input = group.querySelector("input[type='password']");
            // Only select button-like toggles; avoid matching inputs with the same class
            const toggle = group.querySelector("button.togglePassword, .togglePassword.btn, .togglePassword[type='button']");
            if (input && toggle) {
                this.setupPasswordToggle(toggle, input);
                bound = true;
            }
        });

        if (bound) return;

        // Fallback: bind by index, but only consider button-like toggles (not inputs)
        const passwordInputs = document.querySelectorAll("input[type='password']");
        const toggleButtons = document.querySelectorAll("button.togglePassword, .togglePassword.btn, .togglePassword[type='button']");

        if (!toggleButtons.length || !passwordInputs.length) return;

        if (passwordInputs.length === 1 && toggleButtons.length === 1) {
            this.setupPasswordToggle(toggleButtons[0], passwordInputs[0]);
        } else {
            passwordInputs.forEach((input, index) => {
                const toggle = toggleButtons[index];
                if (toggle) {
                    this.setupPasswordToggle(toggle, input);
                }
            });
        }
    },

    /**
     * Setup individual password toggle
     */
    setupPasswordToggle(toggle, input) {
        // Prevent double-binding
        if (toggle.__passwordToggleBound) return;
        toggle.__passwordToggleBound = true;

        toggle.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            // Update icon only for elements that can contain HTML (e.g., buttons)
            if (typeof toggle.innerHTML !== 'undefined') {
                toggle.innerHTML = isPassword ?
                    '<i class="bi bi-eye-slash"></i>' :
                    '<i class="bi bi-eye"></i>';
            }
        });
    }
};

// ================================
// CRUD UTILITIES
// ================================

const CRUDManager = {
    /**
     * Generalized DELETE request handler
     * @param {string} resourceUrl - Full URL to delete
     * @param {Object} options
     * @param {Function} [options.onSuccess] - Callback on success with (result)
     * @param {Function} [options.onError] - Callback on error with (error, response)
     * @param {string} [options.successMessage] - Toast message to show on success
     * @param {string} [options.errorMessage] - Toast message to show on error
     * @param {Object} [options.headers] - Extra headers to include
     */
    async deleteItem(resourceUrl, options = {}) {
        const {
            onSuccess,
            onError,
            successMessage = (window.i18n?.toasts?.delete_success || ''),
            errorMessage = (window.i18n?.toasts?.delete_failed || ''),
            headers = {},
            // Optional: force navigation after success
            redirectOnSuccess = false,
            redirectUrl = null
        } = options;

        if (!resourceUrl) {
            console.error('deleteItem: resourceUrl is required');
            return false;
        }

        try {
            const requestHeaders = Object.assign({
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }, headers);

            // Attach reCAPTCHA v3 token for protected endpoints (middleware checks header)
            if (RecaptchaV3 && RecaptchaV3.siteKey) {
                const action = RecaptchaV3.deriveActionFromUrl(resourceUrl, 'delete');
                const token = await RecaptchaV3.execute(action);
                if (token) {
                    requestHeaders['X-Recaptcha-Token'] = token;
                }
            }

            const response = await fetch(resourceUrl, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: requestHeaders
            });

            let result = null;
            const contentType = (response.headers.get('content-type') || '').toLowerCase();
            const wasRedirected = response.redirected === true;
            const finalUrl = response.url || null;
            if (contentType.includes('application/json')) {
                try { result = await response.json(); } catch (_) { /* ignore parse error */ }
            }

            if (response.ok) {
                // If server issued a redirect (e.g., non-AJAX flow), follow it in the browser
                if (wasRedirected && finalUrl) {
                    window.location.href = finalUrl;
                    return true;
                }

                // If server returned a redirect URL inside JSON result, follow it
                if (result && result.redirect) {
                    window.location.href = result.redirect;
                    return true;
                }

                // If caller requested a redirect on success, do it
                if (redirectOnSuccess && redirectUrl) {
                    window.location.href = redirectUrl;
                    return true;
                }

                // Otherwise, show toast and refresh table if available
                ToastManager.show((result && result.message) || successMessage, 'success');
                if (typeof onSuccess === 'function') {
                    try { onSuccess(result); } catch (e) { console.warn('onSuccess error:', e); }
                }
                if (window.currentDataTable?.ajax?.reload) {
                    window.currentDataTable.ajax.reload();
                }
                return true;
            } else {
                const err = new Error((result && result.message) || errorMessage);
                if (typeof onError === 'function') {
                    try { onError(err, response); } catch (e) { console.warn('onError error:', e); }
                }
                ToastManager.show(err.message, 'danger');
                return false;
            }
        } catch (error) {
            console.error('deleteItem error:', error);
            ToastManager.show(error.message || errorMessage, 'danger');
            if (typeof onError === 'function') {
                try { onError(error); } catch (e) { console.warn('onError error:', e); }
            }
            return false;
        }
    },

    async confirmDelete (data , resourceUrl = null, i18n = null , itemTitle = null ) {
        const modal = document.getElementById('confirmModal');
        const message = document.getElementById('confirmMessage');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        if (modal && message && confirmBtn) {
            // Set the confirmation message
            message.textContent = (i18n.confirm.message || '').replace(':item', (itemTitle));

            // Remove any existing event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            // Add new event listener
            newConfirmBtn.addEventListener('click', function () {
                hideModal(modal);
                window.deleteItem(resourceUrl, {
                    successMessage: i18n.toasts.delete_success,
                    errorMessage: i18n.toasts.delete_failed
                });
            });

            // Show the modal
            showModal(modal);
        } else {
            // Fallback to alert if modal elements not found
            if (window.confirm && window.confirm((i18n.confirm.message || '').replace(':item', (data.title || data.key)))) {

                window.deleteItem(resourceUrl, {
                    successMessage: i18n.toasts.delete_success,
                    errorMessage: i18n.toasts.delete_failed
                });
            } else {
                console.error('Confirmation modal not found and confirm() not available');
            }
        }
    },

    /**
     * Generalized activation/status toggle handler
     * @param {string} resourceUrl - Full URL to post to
     * @param {Object} options
     * @param {Function} [options.onSuccess]
     * @param {Function} [options.onError]
     * @param {string} [options.successMessage]
     * @param {string} [options.errorMessage]
     * @param {Object} [options.headers]
     * @param {string} [options.action] - 'activate' or 'deactivate'
     */
    async activationStatus(resourceUrl, options = {}) {
        const {
            onSuccess,
            onError,
            successMessage = (window.i18n?.toasts?.activate_success || ''),
            errorMessage = (window.i18n?.toasts?.activate_failed || ''),
            headers = {},
            action = null,
            // Optional redirect behavior
            redirectOnSuccess = false,
            redirectUrl = null
        } = options;

        if (!resourceUrl) {
            console.error('activationStatus: resourceUrl is required');
            return false;
        }
        if (!action) {
            console.error('activationStatus: action is required (activate|deactivate)');
            return false;
        }

        try {
            const requestHeaders = Object.assign({
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }, headers);

            const payload = { action };

            // Attach reCAPTCHA v3 token for protected endpoints
            if (RecaptchaV3 && RecaptchaV3.siteKey) {
                const recaptchaAction = RecaptchaV3.deriveActionFromUrl(resourceUrl, 'post');
                const token = await RecaptchaV3.execute(recaptchaAction);
                if (token) {
                    requestHeaders['X-Recaptcha-Token'] = token;
                    // Also include in body for compatibility with servers reading input()
                    payload['g-recaptcha-response'] = token;
                    payload['recaptcha_action'] = recaptchaAction;
                }
            }

            const response = await fetch(resourceUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: requestHeaders,
                body: JSON.stringify(payload)
            });

            let result = null;
            const contentType = (response.headers.get('content-type') || '').toLowerCase();
            const wasRedirected = response.redirected === true;
            const finalUrl = response.url || null;
            if (contentType.includes('application/json')) {
                try { result = await response.json(); } catch (_) { /* ignore parse error */ }
            }

            if (response.ok) {
                if (wasRedirected && finalUrl) {
                    window.location.href = finalUrl;
                    return true;
                }

                // If server returned a redirect URL inside JSON result, follow it
                if (result && result.redirect) {
                    window.location.href = result.redirect;
                    return true;
                }

                // If caller requested a redirect on success, do it
                if (redirectOnSuccess && redirectUrl) {
                    window.location.href = redirectUrl;
                    return true;
                }

                ToastManager.show((result && result.message) || successMessage, 'success');
                if (typeof onSuccess === 'function') {
                    try { onSuccess(result); } catch (e) { console.warn('onSuccess error:', e); }
                }
                if (window.currentDataTable?.ajax?.reload) {
                    window.currentDataTable.ajax.reload();
                }
                return true;
            } else {
                const err = new Error((result && result.message) || errorMessage);
                if (typeof onError === 'function') {
                    try { onError(err, response); } catch (e) { console.warn('onError error:', e); }
                }
                ToastManager.show(err.message, 'danger');
                return false;
            }
        } catch (error) {
            console.error('activationStatus error:', error);
            ToastManager.show(error.message || errorMessage, 'danger');
            if (typeof onError === 'function') {
                try { onError(error); } catch (e) { console.warn('onError error:', e); }
            }
            return false;
        }
    },

    /**
     * Generic JSON request helper (POST/PUT/PATCH/DELETE) with consistent toast + redirect handling.
     * @param {string} resourceUrl
     * @param {Object} options
     * @param {string} [options.method] - HTTP method
     * @param {Object|null} [options.data] - JSON body
     * @param {Object} [options.headers]
     * @param {Function} [options.onSuccess]
     * @param {Function} [options.onError]
     * @param {string} [options.successMessage]
     * @param {string} [options.errorMessage]
     * @param {boolean} [options.redirectOnSuccess]
     * @param {string|null} [options.redirectUrl]
     * @param {boolean} [options.showSuccessToast]
     * @param {boolean} [options.showErrorToast]
     */
    async requestJson(resourceUrl, options = {}) {
        const {
            method = 'POST',
            data = null,
            headers = {},
            onSuccess,
            onError,
            successMessage = (window.i18n?.messages?.operation_completed_successfully || ''),
            errorMessage = (window.i18n?.messages?.operation_failed || ''),
            redirectOnSuccess = false,
            redirectUrl = null,
            showSuccessToast = true,
            showErrorToast = true,
        } = options;

        if (!resourceUrl) {
            console.error('requestJson: resourceUrl is required');
            return false;
        }

        try {
            const reqMethod = String(method || 'POST').toUpperCase();
            const requestHeaders = Object.assign({
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }, headers);

            let bodyData = (data === null || typeof data === 'undefined') ? null : data;

            // Attach reCAPTCHA v3 token for protected endpoints
            if (RecaptchaV3 && RecaptchaV3.siteKey && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(reqMethod)) {
                const recaptchaAction = RecaptchaV3.deriveActionFromUrl(resourceUrl, reqMethod.toLowerCase());
                const token = await RecaptchaV3.execute(recaptchaAction);
                if (token) {
                    requestHeaders['X-Recaptcha-Token'] = token;
                    // Only mutate body if the caller already sends JSON (avoid adding bodies unexpectedly)
                    if (bodyData && typeof bodyData === 'object') {
                        bodyData = Object.assign({}, bodyData, {
                            'g-recaptcha-response': token,
                            'recaptcha_action': recaptchaAction,
                        });
                    }
                }
            }

            const response = await fetch(resourceUrl, {
                method: reqMethod,
                credentials: 'same-origin',
                headers: requestHeaders,
                body: bodyData === null ? null : JSON.stringify(bodyData),
            });

            let result = null;
            const contentType = (response.headers.get('content-type') || '').toLowerCase();
            const wasRedirected = response.redirected === true;
            const finalUrl = response.url || null;
            if (contentType.includes('application/json')) {
                try { result = await response.json(); } catch (_) { /* ignore parse error */ }
            }

            const serverIndicatesFailure = (result && result.success === false);

            if (response.ok && !serverIndicatesFailure) {
                if (wasRedirected && finalUrl) {
                    window.location.href = finalUrl;
                    return true;
                }
                if (result && result.redirect) {
                    window.location.href = result.redirect;
                    return true;
                }
                if (redirectOnSuccess && redirectUrl) {
                    window.location.href = redirectUrl;
                    return true;
                }

                if (showSuccessToast) {
                    ToastManager.show((result && result.message) || successMessage, 'success');
                }
                if (typeof onSuccess === 'function') {
                    try { onSuccess(result, response); } catch (e) { console.warn('onSuccess error:', e); }
                }
                if (window.currentDataTable?.ajax?.reload) {
                    window.currentDataTable.ajax.reload();
                }
                return true;
            }

            const err = new Error((result && (result.message || result.error)) || errorMessage);
            if (typeof onError === 'function') {
                try { onError(err, response, result); } catch (e) { console.warn('onError error:', e); }
            }
            if (showErrorToast) {
                ToastManager.show(err.message, (response.status >= 500 ? 'danger' : 'warning'));
            }
            return false;
        } catch (error) {
            console.error('requestJson error:', error);
            if (typeof onError === 'function') {
                try { onError(error); } catch (e) { console.warn('onError error:', e); }
            }
            if (showErrorToast) {
                ToastManager.show(error.message || errorMessage, 'danger');
            }
            return false;
        }
    },

    /**
     * Bind <select> change events to send an immediate JSON update request.
     * Reverts the select to its previous value on failure.
     *
     * Example usage:
     * bindStatusSelect({
     *   selector: '[data-status-id]',
     *   urlAttr: 'data-update-url',
     *   idAttr: 'data-status-id',
     *   badgeAttr: 'data-status-badge',
     *   valueKey: 'status',
     *   method: 'PUT',
     *   renderBadge: (value) => value,
     * })
     */
    bindSelectJsonUpdate(options = {}) {
        const {
            selector = '[data-update-url]',
            urlAttr = 'data-update-url',
            method = 'PUT',
            valueKey = 'status',
            prevValueDatasetKey = 'prevValue',
            disableDuringRequest = true,
            // optional badge auto-update
            idAttr = null,
            badgeAttr = null,
            renderBadge = null,
            // request messages
            successMessage = (window.i18n?.toasts?.status_updated || window.i18n?.messages?.operation_completed_successfully || ''),
            errorMessage = (window.i18n?.toasts?.status_update_failed || window.i18n?.messages?.operation_failed || ''),
            showSuccessToast = true,
            showErrorToast = true,
            // hooks
            buildData = null,
            onSuccess = null,
            onError = null,
        } = options;

        document.querySelectorAll(selector).forEach((select) => {
            if (!select || select.tagName !== 'SELECT') return;
            if (select.__crudSelectJsonUpdateBound) return;
            select.__crudSelectJsonUpdateBound = true;

            // store initial value
            select.dataset[prevValueDatasetKey] = String(select.value || '');

            select.addEventListener('change', async function () {
                const url = this.getAttribute(urlAttr);
                const status = String(this.value || '');
                const prev = String(this.dataset[prevValueDatasetKey] || '');

                // no-op if unchanged
                if (status === prev) return;

                if (!url) {
                    this.value = prev;
                    return;
                }

                const payload = (typeof buildData === 'function')
                    ? buildData(this)
                    : { [valueKey]: status };

                const prevDisabled = this.disabled;
                if (disableDuringRequest) this.disabled = true;

                // Badge lookup (optional)
                let badge = null;
                if (idAttr && badgeAttr) {
                    const id = this.getAttribute(idAttr);
                    if (id) {
                        badge = document.querySelector(`[${badgeAttr}="${id}"]`);
                    }
                }

                const ok = await CRUDManager.requestJson(url, {
                    method,
                    data: payload,
                    successMessage,
                    errorMessage,
                    showSuccessToast,
                    showErrorToast,
                    onSuccess: (result, response) => {
                        this.dataset[prevValueDatasetKey] = status;
                        if (badge && typeof renderBadge === 'function') {
                            try { badge.textContent = String(renderBadge(status, this, result, response) || ''); } catch (_) {}
                        }
                        if (typeof onSuccess === 'function') {
                            try { onSuccess(this, result, response); } catch (e) { console.warn('onSuccess hook failed:', e); }
                        }
                    },
                    onError: (err, response, result) => {
                        if (typeof onError === 'function') {
                            try { onError(this, err, response, result); } catch (e) { console.warn('onError hook failed:', e); }
                        }
                    }
                });

                if (!ok) {
                    this.value = prev;
                }

                if (disableDuringRequest) this.disabled = prevDisabled;
            });
        });
    },

    // Friendly alias
    bindStatusSelect(arg1 = {}, arg2 = null, arg3 = null) {
        // Supports two styles:
        // 1) Object style: bindStatusSelect({ selector, ... })
        // 2) Simple style: bindStatusSelect(selector, renderBadgeFn, i18n)
        if (typeof arg1 === 'string') {
            const selector = arg1;
            const renderBadge = (typeof arg2 === 'function') ? arg2 : null;
            const i18n = arg3 || window.i18n || {};
            return this.bindSelectJsonUpdate({
                selector,
                urlAttr: 'data-update-url',
                method: 'PUT',
                valueKey: 'status',
                idAttr: 'data-status-id',
                badgeAttr: 'data-status-badge',
                renderBadge,
                successMessage: i18n?.toasts?.status_updated || i18n?.messages?.operation_completed_successfully,
                errorMessage: i18n?.toasts?.status_update_failed || i18n?.messages?.operation_failed,
                showSuccessToast: true,
                showErrorToast: true,
            });
        }

        return this.bindSelectJsonUpdate(arg1 || {});
    },
    async confirmActivate (data , resourceUrl = null, i18n = null) {
        const modal = document.getElementById('confirmActivateModal');
        const message = document.getElementById('confirmActivateMessage');
        const confirmBtn = document.getElementById('confirmActivateBtn');

        if (modal && message && confirmBtn) {
            // Set the confirmation message
            message.textContent = (i18n.confirm.message || '').replace(':item', (data.title || data.key));

            // Remove any existing event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            // Add new event listener
            newConfirmBtn.addEventListener('click', function () {
                hideModal(modal);
                window.activationStatus(resourceUrl, {
                    successMessage: i18n.toasts.delete_success,
                    errorMessage: i18n.toasts.delete_failed
                });
            });

            // Show the modal
            showModal(modal);
        } else {
            // Fallback to alert if modal elements not found
            if (window.confirm && window.confirm((i18n.confirm.message || '').replace(':item', (data.title || data.key)))) {

                window.activationStatus(resourceUrl, {
                    successMessage: i18n.toasts.delete_success,
                    errorMessage: i18n.toasts.delete_failed
                });
            } else {
                console.error('Confirmation modal not found and confirm() not available');
            }
        }
    }

};

// ================================
// GLOBAL EXPORTS
// ================================

// Export main managers to window for backward compatibility
Object.assign(window, {
    // Toast system
    Toast: ToastManager,
    showToast: (message, type, options) => ToastManager.show(message, type, options),

    // Form management
    fillForm: FormManager.fillForm.bind(FormManager),
    clearFormErrors: FormManager.clearFormErrors.bind(FormManager),
    resetForm: FormManager.resetForm.bind(FormManager),
    displayFormErrors: FormManager.displayFormErrors.bind(FormManager),
    handleFormSubmit: FormManager.handleFormSubmit.bind(FormManager),

    // Modal management
    showModal: ModalManager.show.bind(ModalManager),
    hideModal: ModalManager.hide.bind(ModalManager),

    // DataTable utilities
    initializeColumnVisibility: DataTableManager.initializeColumnVisibility.bind(DataTableManager),
    toggleAllColumns: DataTableManager.toggleAllColumns.bind(DataTableManager),
    updateColumnCount: DataTableManager.updateColumnCount.bind(DataTableManager),
    exportTableToCSV: DataTableManager.exportTableToCSV.bind(DataTableManager),
    copyTableToClipboard: DataTableManager.copyTableToClipboard.bind(DataTableManager),
    printTable: DataTableManager.printTable.bind(DataTableManager),
    setCurrentDataTable: DataTableManager.setCurrentDataTable.bind(DataTableManager),
    getCurrentDataTable: DataTableManager.getCurrentDataTable.bind(DataTableManager),

    // UI utilities
    initializeDropdownBehavior: UIManager.initializeDropdownBehavior.bind(UIManager),
    bindPasswordToggle: UIManager.bindPasswordToggle.bind(UIManager),

    // Utility functions
    Utils,
    SwalHelper,
    stripHtml: Utils.stripHtml,
    removeHtmlTags: Utils.removeHtmlTags,
    escapeHtml: Utils.escapeHtml,
    truncateWords: Utils.truncateWords,
    safeJsonParse: Utils.safeJsonParse,
    getCSRFToken: Utils.getCSRFToken,

    // CRUD utilities
    deleteItem: CRUDManager.deleteItem.bind(CRUDManager),
    // Expose confirmDelete so views that call it directly (old-style) work
    confirmDelete: CRUDManager.confirmDelete ? CRUDManager.confirmDelete.bind(CRUDManager) : undefined,
    activationStatus: CRUDManager.activationStatus.bind(CRUDManager),
    requestJson: CRUDManager.requestJson.bind(CRUDManager),
    bindSelectJsonUpdate: CRUDManager.bindSelectJsonUpdate.bind(CRUDManager),
    bindStatusSelect: CRUDManager.bindStatusSelect.bind(CRUDManager)
});
window.confirmActivate = function (arg1 = {}, arg2 = null, arg3 = null) {
    
    let opts = {};
    if (arg1 && (arg1.itemLabel || arg1.resourceUrl || arg1.action || arg1.message || arg1.confirmText)) {
        // Already an options object
        opts = Object.assign({}, arg1);
    } else {
        // Legacy signature: (data, resourceUrl, i18n)
        const data = arg1 || {};
        const i18n = arg3 || window.i18n || {};
        opts = {
            itemLabel: data.title || data.key || data.full_name || data.name || '',
            resourceUrl: arg2 || data.url || '',
            action: (typeof data.active !== 'undefined') ? (data.active ? 'deactivate' : 'activate') : 'activate',
            confirmText: null,
            confirmClass: null,
            message: (i18n.confirm && i18n.confirm.message) ? i18n.confirm.message : null,
            successMessage: i18n.toasts?.activate_success || null,
            errorMessage: i18n.toasts?.activate_failed || null,
            onSuccess: null,
            onError: null,
            redirectOnSuccess: false,
            redirectUrl: null
        };
    }

    const modal = document.getElementById('confirmActivateModal');
    const messageEl = document.getElementById('confirmActivateMessage');
    const confirmBtn = document.getElementById('confirmActivateBtn');

    if (!opts.resourceUrl) {
        console.error('confirmActivate: resourceUrl is required');
        return;
    }

    if (modal && messageEl && confirmBtn) {
        const base = opts.message || (window.i18n?.confirm?.activate_message) || (window.i18n?.confirm?.message) || '';
        messageEl.textContent = base.replace(':item', opts.itemLabel || '');

        // Clone button to remove old listeners
        const newBtn = confirmBtn.cloneNode(true);
        // Set text/class if provided
        if (opts.confirmText) newBtn.textContent = opts.confirmText;
        if (opts.confirmClass) newBtn.className = opts.confirmClass;

        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);

        newBtn.addEventListener('click', function () {
            try { hideModal(modal); } catch (_) {
                try { window.bootstrap?.Modal?.getOrCreateInstance(modal)?.hide(); } catch (e) {}
            }

            // Call activationStatus helper
            window.activationStatus(opts.resourceUrl, {
                action: opts.action,
                successMessage: opts.successMessage,
                errorMessage: opts.errorMessage,
                onSuccess: opts.onSuccess,
                onError: opts.onError,
                redirectOnSuccess: opts.redirectOnSuccess,
                redirectUrl: opts.redirectUrl
            });
        });

        try { showModal(modal); } catch (_) {
            try { window.bootstrap?.Modal?.getOrCreateInstance(modal)?.show(); } catch (e) {}
        }
    } else {
        // Fallback: directly call activationStatus
        window.activationStatus(opts.resourceUrl, {
            action: opts.action,
            successMessage: opts.successMessage,
            errorMessage: opts.errorMessage,
            onSuccess: opts.onSuccess,
            onError: opts.onError,
            redirectOnSuccess: opts.redirectOnSuccess,
            redirectUrl: opts.redirectUrl
        });
    }
};

// ================================
// LEGACY COMPATIBILITY LAYER
// ================================


if (!window.clearForm) {
    window.clearForm = function(content) {
        const root = (typeof content === 'string') ? document.querySelector(content) : content;
        if (!root) return;

        root.querySelectorAll('input.form-control, textarea.form-control').forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });

        root.querySelectorAll('select').forEach(select => {
            Array.from(select.options).forEach(opt => { opt.selected = false; });
            // Refresh Choices if present
            if (select._choices) {
                select._choices.removeActiveItems();
            }
            select.dispatchEvent(new Event('change', { bubbles: true }));
        });
    };
}

if (!window.clearErrors) {
    window.clearErrors = function(content) {
        const root = (typeof content === 'string') ? document.querySelector(content) : content;
        if (!root) return;
        root.querySelectorAll('.error').forEach(el => el.remove());
    };
}

// Provide a simple global binder similar to legacy handleSubmit(selector, options)
if (!window.handleSubmit) {
    window.handleSubmit = function(selector, options = {}) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form) return;
        if (form.__handleSubmitBound) return;
        form.__handleSubmitBound = true;
        form.addEventListener('submit', async function(event) {
            // Prevent global handler from also processing this
            event.stopImmediatePropagation();
            event.preventDefault();
            try {
                await window.FormManager.handleFormSubmit(event, form);
            } catch (e) {
                console.error('handleSubmit error:', e);
                ToastManager.show(e?.message || window.i18n?.messages?.an_error_occurred || '', 'danger');
            }
        }, true);
    };
}

if (!window.__recaptchaGlobalSubmitListenerAdded) {
    window.__recaptchaGlobalSubmitListenerAdded = true;
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        if (form.__handleSubmitBound) return;
        if (form.dataset && form.dataset.recaptchaSkip === '1') return;
        if (form.dataset && form.dataset.submitNative === '1') return;
        if (form.__recaptchaSubmitInProgress) return;

        const method = String(form.getAttribute('method') || 'GET').toUpperCase();
        if (!['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) return;

        // Default behavior for non-AJAX forms that still return JSON:
        // submit via AJAX so we can handle {redirect: ...} and validation errors.
        e.preventDefault();
        form.__recaptchaSubmitInProgress = true;
        try {
            await window.FormManager.handleFormSubmit(e, form);
        } catch (err) {
            console.error('Global submit handler error:', err);
        } finally {
            form.__recaptchaSubmitInProgress = false;
        }
    }, true);
}

// ================================
// AUTO-INITIALIZATION
// ================================

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    try {
        UIManager.bindPasswordToggle();
        UIManager.initializeDropdownBehavior();

        // Check for stored toast message from previous page redirect
        const storedToast = sessionStorage.getItem('toastMessage');
        if (storedToast) {
            try {
                const { message, type } = JSON.parse(storedToast);
                if (message) {
                    ToastManager.show(message, type || 'info');
                }
            } catch (e) {
                console.warn('Failed to parse stored toast:', e);
            }
            // Clear the stored message after displaying
            sessionStorage.removeItem('toastMessage');
        }
    } catch (e) {
        console.warn('Auto-initialization warning:', e);
    }
});
// Handle modal close buttons
document.addEventListener('click', function(e) {
    if (e.target.matches('[data-bs-dismiss="modal"]') || e.target.closest('[data-bs-dismiss="modal"]')) {
        const modal = e.target.closest('.modal');
        if (modal) {
            hideModal(modal);
        }
    }
});

// Auto-initialize Choices.js on selects marked with `.select2` (legacy class) or `[data-choices]`.
(function initChoices() {
    async function setup() {
        if (!window.loadChoices) return;
        const Choices = await window.loadChoices();

        const nodes = document.querySelectorAll('select.select2, select[data-choices]');
        nodes.forEach((select) => {
            if (select._choices) return;

            const i18nPlaceholder = (window.i18n && window.i18n.messages && window.i18n.messages.select_an_option) || null;
            const attrPlaceholder = select.getAttribute('data-placeholder') || select.getAttribute('placeholder') || null;
            const placeholder = i18nPlaceholder || attrPlaceholder || (document.documentElement.lang === 'ar' ? 'اختر خيارًا' : (window.i18n?.messages?.select_an_option || 'Select an option'));

            try {
                const instance = new Choices(select, {
                    removeItemButton: select.multiple,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: placeholder,
                    searchEnabled: true,
                });
                select._choices = instance;
            } catch (e) {
                console.warn('Choices initialization skipped or failed:', e);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        setup().catch(() => null);
        // Retry once after i18n loads (if it is injected after DOMContentLoaded)
        setTimeout(() => { setup().catch(() => null); }, 400);
    });
})();

// ================================
// COPY TO CLIPBOARD
// ================================

/**
 * Copy text to clipboard with visual feedback
 * @param {string} text - Text to copy
 * @param {HTMLElement} btn - Button element for visual feedback
 * @param {Object} options - Additional options
 */
async function copyToClipboard(text, btn = null, options = {}) {
    const {
        successText = '<i class="bi bi-check me-1"></i>Copied!',
        successClass = 'btn-success',
        originalClass = 'btn-outline-secondary',
        timeout = 2000
    } = options;

    try {
        if (navigator.clipboard) {
            await navigator.clipboard.writeText(text);
        } else {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }

        if (btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = successText;
            btn.classList.add(successClass);
            btn.classList.remove(originalClass);
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove(successClass);
                btn.classList.add(originalClass);
            }, timeout);
        }

        return true;
    } catch (error) {
        console.error('Copy failed:', error);
        return false;
    }
}

// Export to window
Object.assign(window, {
    copyToClipboard
});


// ============================================================================
// AI REFINEMENT UTILITIES
// ============================================================================

window.RefinementManager = {
    applyRefinement: function(action, url, callback) {
        SwalHelper.showLoading(
            window.i18n?.messages?.processing || 'Processing...',
            `Applying ${action} refinement...`
        );

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                SwalHelper.success(
                    window.i18n?.messages?.refinement_applied || 'Refinement Applied!',
                    data.message || window.i18n?.messages?.content_refined_successfully || 'Content has been refined successfully.',
                    { timer: 2000 }
                ).then(() => {
                    if (callback) callback(data);
                    else window.location.reload();
                });
            } else {
                throw new Error(data.message || window.i18n?.messages?.failed_to_apply_refinement || 'Failed to apply refinement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error(
                window.i18n?.messages?.refinement_failed || 'Refinement Failed',
                error.message || window.i18n?.messages?.failed_to_apply_refinement_text || 'Failed to apply refinement. Please try again.'
            );
        });
    },

    adjustTone: function(tone, url, callback) {
        SwalHelper.showLoading(
            window.i18n?.messages?.adjusting_tone || 'Adjusting Tone...',
            `Changing tone to ${tone}...`
        );

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tone: tone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                SwalHelper.success(
                    window.i18n?.messages?.tone_adjusted || 'Tone Adjusted!',
                    data.message || window.i18n?.messages?.content_tone_adjusted || `Content tone has been changed to ${tone}.`,
                    { timer: 2000 }
                ).then(() => {
                    if (callback) callback(data);
                    else window.location.reload();
                });
            } else {
                throw new Error(data.message || window.i18n?.messages?.failed_to_adjust_tone || 'Failed to adjust tone');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            SwalHelper.error(
                window.i18n?.messages?.tone_adjustment_failed || 'Tone Adjustment Failed',
                error.message || window.i18n?.messages?.failed_to_adjust_tone_text || 'Failed to adjust tone. Please try again.'
            );
        });
    }
};


// ============================================================================
// API CLIENT - Centralized HTTP Request Handler
// ============================================================================
// Unified API client for all HTTP requests with CSRF, error handling, and loading states
// ============================================================================

window.ApiClient = {
    /**
     * Get CSRF token from meta tag
     */
    getToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    /**
     * Build fetch options with defaults
     */
    buildOptions: function(method, data, customHeaders = {}) {
        const isFormData = data instanceof FormData;
        const headers = {
            'X-CSRF-TOKEN': this.getToken(),
            'Accept': 'application/json',
            ...customHeaders
        };

        if (!isFormData && data) {
            headers['Content-Type'] = 'application/json';
        }

        const options = { method, headers };
        
        if (data) {
            options.body = isFormData ? data : JSON.stringify(data);
        }

        return options;
    },

    /**
     * GET request
     * @param {string} url - API endpoint
     * @param {Object} params - Query parameters
     */
    get: async function(url, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;
        
        const response = await fetch(fullUrl, this.buildOptions('GET', null));
        return response.json();
    },

    /**
     * GET HTML request (for AJAX page loads)
     * @param {string} url - API endpoint
     */
    getHtml: async function(url) {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': this.getToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        });
        return response.text();
    },

    /**
     * POST request
     * @param {string} url - API endpoint
     * @param {Object|FormData} data - Request body
     */
    post: async function(url, data = {}) {
        const response = await fetch(url, this.buildOptions('POST', data));
        return response.json();
    },

    /**
     * PUT request
     */
    put: async function(url, data = {}) {
        const response = await fetch(url, this.buildOptions('PUT', data));
        return response.json();
    },

    /**
     * DELETE request
     */
    delete: async function(url, data = {}) {
        const response = await fetch(url, this.buildOptions('DELETE', data));
        return response.json();
    },

    /**
     * Request with loading indicator and callbacks
     * @param {string} url - API endpoint
     * @param {Object} options - { method, data, loadingText, onSuccess, onError, onFinally }
     */
    request: async function(url, options = {}) {
        const {
            method = 'POST',
            data = {},
            loadingText = 'Processing...',
            showLoading = true,
            onSuccess,
            onError,
            onFinally
        } = options;

        // Show loading with SweetAlert if available
        if (showLoading && typeof Swal !== 'undefined') {
            Swal.fire({
                title: loadingText,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        try {
            const response = await fetch(url, this.buildOptions(method, data));
            const result = await response.json();

            if (showLoading && typeof Swal !== 'undefined') {
                Swal.close();
            }

            if (result.success) {
                if (onSuccess) onSuccess(result);
            } else {
                if (onError) {
                    onError(result);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: window.i18n?.messages?.error || 'Error', text: result.message || window.i18n?.messages?.operation_failed || 'Operation failed' });
                }
            }

            return result;
        } catch (error) {
            console.error('ApiClient Error:', error);
            
            if (showLoading && typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: window.i18n?.messages?.network_error || 'Network Error', text: window.i18n?.messages?.connection_failed || 'Connection failed. Please try again.' });
            }

            if (onError) onError({ success: false, message: error.message });
            throw error;
        } finally {
            if (onFinally) onFinally();
        }
    },

    /**
     * Submit form via AJAX
     * @param {HTMLFormElement|string} form - Form element or selector
     * @param {Object} callbacks - { onSuccess, onError }
     */
    submitForm: async function(form, callbacks = {}) {
        const formEl = typeof form === 'string' ? document.querySelector(form) : form;
        if (!formEl) return;

        const formData = new FormData(formEl);
        const url = formEl.action || window.location.href;
        const method = formEl.method?.toUpperCase() || 'POST';

        return this.request(url, {
            method,
            data: formData,
            ...callbacks
        });
    }
};

// ============================================================================
// FORM HELPER - Simplified Form Handling Utilities
// ============================================================================
// Provides easy-to-use form utilities for Blade templates
// ============================================================================

window.FormHelper = {
    /**
     * Bind AJAX submit to a form with callbacks
     * @param {string|HTMLElement} selector - Form selector or element
     * @param {Object} options - { onSuccess, onError, onBefore, loadingText, resetOnSuccess }
     */
    bind: function(selector, options = {}) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form || form.__formHelperBound) return;
        form.__formHelperBound = true;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.submit(form, options);
        });
    },

    /**
     * Submit form via AJAX
     * @param {string|HTMLElement} selector - Form selector or element
     * @param {Object} options - Submission options
     */
    submit: async function(selector, options = {}) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form) return null;

        const {
            onSuccess,
            onError,
            onBefore,
            onComplete,
            loadingText = 'Processing...',
            resetOnSuccess = false,
            closeModalOnSuccess = true
        } = options;

        // Find submit button
        const submitBtn = form.querySelector('button[type="submit"], .btn-primary');
        const originalBtnHtml = submitBtn?.innerHTML;
        const modal = form.closest('.modal');

        // Before callback
        if (onBefore && onBefore(form) === false) return null;

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="bi bi-hourglass-split me-1"></i>${loadingText}`;
        }

        try {
            const formData = new FormData(form);
            const url = form.action || window.location.href;

            const data = await ApiClient.post(url, formData);

            // Restore button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }

            if (data.success) {
                // Success handling
                if (resetOnSuccess) form.reset();
                
                if (closeModalOnSuccess && modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                }

                if (data.redirect) {
                    window.location.href = data.redirect;
                    return data;
                }

                if (onSuccess) {
                    onSuccess(data);
                } else if (data.message && typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: data.message, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                }
            } else {
                // Error handling
                if (onError) {
                    onError(data);
                } else if (data.errors) {
                    this.showErrors(form, data.errors);
                } else if (data.message && typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: window.i18n?.messages?.error || 'Error', text: data.message });
                }
            }

            if (onComplete) onComplete(data);
            return data;

        } catch (error) {
            // Restore button on error
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }

            if (onError) {
                onError({ success: false, message: error.message });
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: window.i18n?.messages?.network_error || 'Network Error', text: window.i18n?.messages?.check_connection || 'Please check your connection' });
            }

            if (onComplete) onComplete({ success: false, error });
            return null;
        }
    },

    /**
     * Show validation errors on form fields
     */
    showErrors: function(form, errors) {
        this.clearErrors(form);
        
        Object.entries(errors).forEach(([field, messages]) => {
            const input = form.querySelector(`[name="${field}"], [name="${field}[]"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = Array.isArray(messages) ? messages[0] : messages;
                input.parentNode.appendChild(errorDiv);
            }
        });

        // Focus first error
        const firstError = form.querySelector('.is-invalid');
        if (firstError) firstError.focus();
    },

    /**
     * Clear all validation errors from form
     */
    clearErrors: function(form) {
        if (!form) return;
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    },

    /**
     * Get form data as object
     */
    getData: function(selector) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form) return {};
        return Object.fromEntries(new FormData(form));
    },

    /**
     * Set form values from object
     */
    setData: function(selector, data) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form || !data) return;
        
        Object.entries(data).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = !!value;
                } else if (input.type === 'radio') {
                    const radioInput = form.querySelector(`[name="${key}"][value="${value}"]`);
                    if (radioInput) radioInput.checked = true;
                } else {
                    input.value = value;
                }
            }
        });
    },

    /**
     * Toggle favorite with animation (for lists)
     * @param {HTMLElement} button - Button element with data-content-id
     * @param {string} url - Toggle URL
     * @param {Object} options - { onSuccess, removeCard }
     */
    toggleFavorite: async function(button, url, options = {}) {
        const { removeCard = true, onSuccess, successMessage, errorMessage } = options;
        const card = button.closest('.col-lg-6, .col-md-6, .card');
        const icon = button.querySelector('i');
        const originalClass = icon?.className;

        // Loading state
        if (icon) icon.className = 'bi bi-hourglass-split fs-5';
        button.disabled = true;

        try {
            const data = await ApiClient.post(url);

            if (data.success) {
                if (removeCard && card) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.col-lg-6, .col-md-6').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: successMessage || data.message || window.i18n?.messages?.updated || 'Updated!', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                }

                if (onSuccess) onSuccess(data);
            } else {
                if (icon) icon.className = originalClass;
                button.disabled = false;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: errorMessage || data.message || window.i18n?.messages?.error || 'Error', toast: true, position: 'top-end', timer: 3000 });
                }
            }
        } catch (error) {
            if (icon) icon.className = originalClass;
            button.disabled = false;
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: window.i18n?.messages?.network_error || 'Network Error', toast: true, position: 'top-end', timer: 3000 });
            }
        }
    },

    /**
     * Confirm and delete with AJAX
     * @param {string} url - Delete URL
     * @param {Object} options - { confirmTitle, confirmText, onSuccess }
     */
    confirmDelete: async function(url, options = {}) {
        const {
            confirmTitle = window.i18n?.messages?.are_you_sure || 'Are you sure?',
            confirmText = window.i18n?.messages?.action_cannot_be_undone || 'This action cannot be undone.',
            confirmButton = window.i18n?.messages?.yes_delete || 'Yes, Delete',
            onSuccess
        } = options;

        if (typeof Swal === 'undefined') {
            if (!confirm(confirmText)) return;
        } else {
            const result = await Swal.fire({
                icon: 'warning',
                title: confirmTitle,
                text: confirmText,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: confirmButton
            });
            if (!result.isConfirmed) return;
        }

        try {
            const data = await ApiClient.delete(url);
            
            if (data.success) {
                if (onSuccess) {
                    onSuccess(data);
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    location.reload();
                }
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: window.i18n?.messages?.error || 'Error', text: data.message });
            }
        } catch (error) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: window.i18n?.messages?.network_error || 'Network Error', text: window.i18n?.messages?.please_try_again || 'Please try again' });
            }
        }
    }
};

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

// Use Utils.formatDate and Utils.debounce - avoid duplicates
window.formatDate = Utils.formatDate;
window.debounce = Utils.debounce;

// ============================================================================
// AUTO-INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Hakim Clinics - JavaScript Utilities Loaded');
    console.log('📦 Available Managers:', {
        RefinementManager: typeof window.RefinementManager !== 'undefined',
        ApiClient: typeof window.ApiClient !== 'undefined',
        FormHelper: typeof window.FormHelper !== 'undefined'
    });
});
