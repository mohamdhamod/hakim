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
     * Resolve modal argument to DOM element (accepts string ID or element)
     */
    _resolve(modal) {
        if (typeof modal === 'string') return document.getElementById(modal) || document.querySelector(modal);
        return modal;
    },

    /**
     * Show modal with backdrop and keyboard handling
     */
    show(modal) {
        modal = this._resolve(modal);
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
        modal = this._resolve(modal);
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
    // ── Private Helpers ──────────────────────────────────

    /** Build standard JSON request headers */
    _headers(extra = {}) {
        return Object.assign({
            'X-CSRF-TOKEN': Utils.getCSRFToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }, extra);
    },

    /** Attach reCAPTCHA v3 token to headers; returns { token, action } or null */
    async _attachRecaptcha(headers, url, method = 'post', explicitAction = null) {
        if (!RecaptchaV3 || !RecaptchaV3.siteKey) return null;
        try {
            const action = explicitAction || RecaptchaV3.deriveActionFromUrl(url, method);
            const token = await RecaptchaV3.execute(action);
            if (token) { headers['X-Recaptcha-Token'] = token; return { token, action }; }
        } catch (_) {}
        return null;
    },

    /** Parse JSON response safely */
    async _parseResponse(response) {
        let result = null;
        const ct = (response.headers.get('content-type') || '').toLowerCase();
        if (ct.includes('application/json')) { try { result = await response.json(); } catch (_) {} }
        return { result, wasRedirected: response.redirected === true, finalUrl: response.url || null };
    },

    /** Raw fetch returning parsed JSON (for backward-compat ApiClient shim) */
    async _rawRequest(method, url, data = {}) {
        const isFormData = data instanceof FormData;
        const headers = this._headers();
        if (isFormData) delete headers['Content-Type'];
        const response = await fetch(url, {
            method, credentials: 'same-origin', headers,
            body: isFormData ? data : JSON.stringify(data)
        });
        return response.json();
    },

    // ── Public API ───────────────────────────────────────

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
        return this.requestJson(resourceUrl, Object.assign({
            successMessage: window.i18n?.toasts?.delete_success || '',
            errorMessage: window.i18n?.toasts?.delete_failed || '',
        }, options, { method: 'DELETE' }));
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
     * Generalized activation/status toggle handler - delegates to requestJson
     * @param {string} resourceUrl - Full URL to post to
     * @param {Object} options - { action, onSuccess, onError, successMessage, errorMessage, headers, ... }
     */
    async activationStatus(resourceUrl, options = {}) {
        const { action, ...rest } = options;
        if (!action) { console.error('activationStatus: action is required (activate|deactivate)'); return false; }
        return this.requestJson(resourceUrl, Object.assign({
            successMessage: window.i18n?.toasts?.activate_success || '',
            errorMessage: window.i18n?.toasts?.activate_failed || '',
        }, rest, { method: 'POST', data: { action } }));
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
            method = 'POST', data = null, headers = {},
            onSuccess, onError,
            successMessage = (window.i18n?.messages?.operation_completed_successfully || ''),
            errorMessage = (window.i18n?.messages?.operation_failed || ''),
            redirectOnSuccess = false, redirectUrl = null,
            showSuccessToast = true, showErrorToast = true,
        } = options;

        if (!resourceUrl) { console.error('requestJson: resourceUrl is required'); return false; }

        try {
            const reqMethod = String(method || 'POST').toUpperCase();
            const requestHeaders = this._headers(headers);
            let bodyData = data ?? null;

            // Attach reCAPTCHA v3 token for non-idempotent requests
            if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(reqMethod)) {
                const rc = await this._attachRecaptcha(requestHeaders, resourceUrl, reqMethod.toLowerCase());
                if (rc && bodyData && typeof bodyData === 'object') {
                    bodyData = { ...bodyData, 'g-recaptcha-response': rc.token, 'recaptcha_action': rc.action };
                }
            }

            const response = await fetch(resourceUrl, {
                method: reqMethod, credentials: 'same-origin',
                headers: requestHeaders,
                body: bodyData === null ? null : JSON.stringify(bodyData),
            });

            const { result, wasRedirected, finalUrl } = await this._parseResponse(response);
            const serverFailed = result?.success === false;

            if (response.ok && !serverFailed) {
                if (wasRedirected && finalUrl) { window.location.href = finalUrl; return true; }
                if (result?.redirect) { window.location.href = result.redirect; return true; }
                if (redirectOnSuccess && redirectUrl) { window.location.href = redirectUrl; return true; }
                if (showSuccessToast) ToastManager.show(result?.message || successMessage, 'success');
                if (typeof onSuccess === 'function') { try { onSuccess(result, response); } catch (e) { console.warn('onSuccess error:', e); } }
                if (window.currentDataTable?.ajax?.reload) window.currentDataTable.ajax.reload();
                return true;
            }

            const err = new Error(result?.message || result?.error || errorMessage);
            if (typeof onError === 'function') { try { onError(err, response, result); } catch (e) { console.warn('onError error:', e); } }
            if (showErrorToast) ToastManager.show(err.message, (response.status >= 500 ? 'danger' : 'warning'));
            return false;
        } catch (error) {
            console.error('requestJson error:', error);
            if (typeof onError === 'function') { try { onError(error); } catch (e) { console.warn('onError error:', e); } }
            if (showErrorToast) ToastManager.show(error.message || errorMessage, 'danger');
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
    /**
     * Confirm activation/deactivation with modal.
     * Supports: (options) or legacy (data, resourceUrl, i18n)
     */
    confirmActivate(arg1 = {}, arg2 = null, arg3 = null) {
        let opts;
        if (arg1 && (arg1.itemLabel || arg1.resourceUrl || arg1.action || arg1.message || arg1.confirmText)) {
            opts = { ...arg1 };
        } else {
            const data = arg1 || {};
            const i18n = arg3 || window.i18n || {};
            opts = {
                itemLabel: data.title || data.key || data.full_name || data.name || '',
                resourceUrl: arg2 || data.url || '',
                action: (typeof data.active !== 'undefined') ? (data.active ? 'deactivate' : 'activate') : 'activate',
                message: i18n.confirm?.message || null,
                successMessage: i18n.toasts?.activate_success || null,
                errorMessage: i18n.toasts?.activate_failed || null,
            };
        }

        if (!opts.resourceUrl) { console.error('confirmActivate: resourceUrl is required'); return; }

        const modal = document.getElementById('confirmActivateModal');
        const messageEl = document.getElementById('confirmActivateMessage');
        const confirmBtn = document.getElementById('confirmActivateBtn');

        const callActivation = () => {
            this.activationStatus(opts.resourceUrl, {
                action: opts.action,
                successMessage: opts.successMessage,
                errorMessage: opts.errorMessage,
                onSuccess: opts.onSuccess,
                onError: opts.onError,
                redirectOnSuccess: opts.redirectOnSuccess,
                redirectUrl: opts.redirectUrl,
            });
        };

        if (modal && messageEl && confirmBtn) {
            const base = opts.message || window.i18n?.confirm?.activate_message || window.i18n?.confirm?.message || '';
            messageEl.textContent = base.replace(':item', opts.itemLabel || '');
            const newBtn = confirmBtn.cloneNode(true);
            if (opts.confirmText) newBtn.textContent = opts.confirmText;
            if (opts.confirmClass) newBtn.className = opts.confirmClass;
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            newBtn.addEventListener('click', () => {
                try { hideModal(modal); } catch (_) { try { window.bootstrap?.Modal?.getOrCreateInstance(modal)?.hide(); } catch (_) {} }
                callActivation();
            });
            try { showModal(modal); } catch (_) { try { window.bootstrap?.Modal?.getOrCreateInstance(modal)?.show(); } catch (_) {} }
        } else {
            callActivation();
        }
    },

    // ========================================================================
    // HTTP GET Utilities (merged from ApiClient)
    // ========================================================================

    /**
     * GET JSON request with optional query params
     * @param {string} url - API endpoint
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} Parsed JSON response
     */
    async getJson(url, params = {}) {
        if (!url) { console.error('getJson: url is required'); return null; }
        const qs = new URLSearchParams(params).toString();
        const response = await fetch(qs ? `${url}?${qs}` : url, {
            method: 'GET', credentials: 'same-origin', headers: this._headers()
        });
        return response.json();
    },

    /**
     * GET HTML request (for AJAX page loads)
     * @param {string} url - Endpoint URL
     * @returns {Promise<string>} HTML text
     */
    async getHtml(url) {
        if (!url) { console.error('getHtml: url is required'); return ''; }
        const response = await fetch(url, {
            method: 'GET', credentials: 'same-origin', headers: this._headers({ 'Accept': 'text/html' })
        });
        return response.text();
    },

    // ========================================================================
    // Form Utilities (merged from FormHelper)
    // ========================================================================

    /**
     * Bind AJAX submit to a form with callbacks
     * @param {string|HTMLElement} selector - Form selector or element
     * @param {Object} options - { onSuccess, onError, onBefore, loadingText, resetOnSuccess }
     */
    bindForm: function(selector, options = {}) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form || form.__crudFormBound) return;
        form.__crudFormBound = true;
        form.__handleSubmitBound = true; // Prevent global submit listener from also handling this form

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.submitForm(form, options);
        });
    },

    /**
     * Submit form via AJAX with loading state, modal auto-close, and validation error display
     * @param {string|HTMLElement} selector - Form selector or element
     * @param {Object} options - Submission options
     */
    submitForm: async function(selector, options = {}) {
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

        const restoreBtn = () => { if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalBtnHtml; } };

        try {
            const formData = new FormData(form);
            const url = form.action || window.location.href;
            const requestHeaders = this._headers();
            delete requestHeaders['Content-Type']; // FormData sets its own boundary

            const rc = await this._attachRecaptcha(requestHeaders, url, 'post');
            if (rc) formData.append('g-recaptcha-response', rc.token);

            const response = await fetch(url, {
                method: 'POST', credentials: 'same-origin',
                headers: requestHeaders, body: formData
            });

            const data = await response.json();
            restoreBtn();

            if (data.success) {
                if (resetOnSuccess) form.reset();
                if (closeModalOnSuccess && modal) {
                    try { bootstrap.Modal.getInstance(modal)?.hide(); } catch (_) { hideModal(modal); }
                }
                if (data.redirect) { window.location.href = data.redirect; return data; }
                if (onSuccess) { onSuccess(data); } else if (data.message) { ToastManager.show(data.message, 'success'); }
                if (window.currentDataTable?.ajax?.reload) { try { window.currentDataTable.ajax.reload(null, false); } catch (_) {} }
            } else {
                if (onError) { onError(data); }
                else if (data.errors) { this.showFormErrors(form, data.errors); }
                else if (data.message) { ToastManager.show(data.message, 'danger'); }
            }

            if (onComplete) onComplete(data);
            return data;
        } catch (error) {
            restoreBtn();
            if (onError) { onError({ success: false, message: error.message }); }
            else { ToastManager.show(error.message || window.i18n?.messages?.network_error || 'Network Error', 'danger'); }
            if (onComplete) onComplete({ success: false, error });
            return null;
        }
    },

    /**
     * Show validation errors on form fields
     * @param {HTMLFormElement} form
     * @param {Object} errors - { fieldName: [messages] }
     */
    showFormErrors: function(form, errors) {
        this.clearFormErrors(form);
        if (!form || !errors || typeof errors !== 'object') return;

        form.classList.add('was-validated');
        let firstError = null;

        Object.entries(errors).forEach(([field, messages]) => {
            const input = form.querySelector(`[name="${field}"], [name="${field}[]"]`) || form.querySelector(`#${field}`);
            if (!input) return;
            input.classList.add('is-invalid');

            // Remove existing feedback
            const existing = input.parentNode.querySelector('.invalid-feedback');
            if (existing) existing.remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block';
            const icon = document.createElement('i');
            icon.className = 'bi bi-exclamation-circle me-1';
            errorDiv.appendChild(icon);
            errorDiv.appendChild(document.createTextNode(Array.isArray(messages) ? messages[0] : String(messages)));
            input.parentNode.appendChild(errorDiv);

            if (!firstError) firstError = input;
        });

        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    },

    /**
     * Clear all validation errors from form
     * @param {HTMLFormElement|string} formOrSelector
     */
    clearFormErrors: function(formOrSelector) {
        const form = typeof formOrSelector === 'string' ? document.querySelector(formOrSelector) : formOrSelector;
        if (!form) return;
        form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
        form.querySelectorAll('.invalid-feedback, .valid-feedback, .text-danger.error-message').forEach(el => el.remove());
        form.classList.remove('was-validated');
    },

    /**
     * Get form data as plain object
     * @param {string|HTMLElement} selector
     * @returns {Object}
     */
    getFormData: function(selector) {
        const form = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!form) return {};
        return Object.fromEntries(new FormData(form));
    },

    /**
     * Set form values from object
     * @param {string|HTMLElement} selector
     * @param {Object} data - { fieldName: value }
     */
    setFormData: function(selector, data) {
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
     * @param {HTMLElement} button - Button element
     * @param {string} url - Toggle URL
     * @param {Object} options - { onSuccess, removeCard, successMessage, errorMessage }
     */
    async toggleFavorite(button, url, options = {}) {
        const { removeCard = true, onSuccess, successMessage, errorMessage } = options;
        const card = button.closest('.col-lg-6, .col-md-6, .card');
        const icon = button.querySelector('i');
        const originalClass = icon?.className;

        if (icon) icon.className = 'bi bi-hourglass-split fs-5';
        button.disabled = true;

        try {
            const requestHeaders = this._headers();
            await this._attachRecaptcha(requestHeaders, url, 'post', 'toggle_favorite');

            const response = await fetch(url, {
                method: 'POST', credentials: 'same-origin',
                headers: requestHeaders, body: JSON.stringify({})
            });
            const data = await response.json();

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

                ToastManager.show(successMessage || data.message || window.i18n?.messages?.updated || 'Updated!', 'success');
                if (onSuccess) onSuccess(data);
            } else {
                if (icon) icon.className = originalClass;
                button.disabled = false;
                ToastManager.show(errorMessage || data.message || window.i18n?.messages?.error || 'Error', 'danger');
            }
        } catch (error) {
            if (icon) icon.className = originalClass;
            button.disabled = false;
            ToastManager.show(window.i18n?.messages?.network_error || 'Network Error', 'danger');
        }
    },

    /**
     * Confirm and delete with SweetAlert (simple API)
     * @param {string} url - Delete URL
     * @param {Object} options - { confirmTitle, confirmText, confirmButton, onSuccess }
     */
    async confirmDeleteSwal(url, options = {}) {
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

        // Use the existing deleteItem which has reCAPTCHA support
        return this.deleteItem(url, { onSuccess: onSuccess || (() => location.reload()) });
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
    CRUDManager,
    deleteItem: CRUDManager.deleteItem.bind(CRUDManager),
    confirmDelete: CRUDManager.confirmDelete ? CRUDManager.confirmDelete.bind(CRUDManager) : undefined,
    confirmDeleteSwal: CRUDManager.confirmDeleteSwal.bind(CRUDManager),
    confirmActivate: CRUDManager.confirmActivate.bind(CRUDManager),
    activationStatus: CRUDManager.activationStatus.bind(CRUDManager),
    requestJson: CRUDManager.requestJson.bind(CRUDManager),
    bindSelectJsonUpdate: CRUDManager.bindSelectJsonUpdate.bind(CRUDManager),
    bindStatusSelect: CRUDManager.bindStatusSelect.bind(CRUDManager),
    // Form utilities (from CRUDManager)
    bindForm: CRUDManager.bindForm.bind(CRUDManager),
    submitFormAjax: CRUDManager.submitForm.bind(CRUDManager),
    showFormErrors: CRUDManager.showFormErrors.bind(CRUDManager),
    clearFormValidation: CRUDManager.clearFormErrors.bind(CRUDManager),
    getFormData: CRUDManager.getFormData.bind(CRUDManager),
    setFormData: CRUDManager.setFormData.bind(CRUDManager),
    toggleFavorite: CRUDManager.toggleFavorite.bind(CRUDManager)
});

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
window.copyToClipboard = copyToClipboard;

// ============================================================================
// BACKWARD COMPATIBILITY ALIASES
// ============================================================================

// ApiClient shim (delegates to CRUDManager for consistency)
window.ApiClient = {
    getToken: Utils.getCSRFToken,
    get: (url, params) => CRUDManager.getJson(url, params),
    getHtml: (url) => CRUDManager.getHtml(url),
    post: (url, data = {}) => CRUDManager._rawRequest('POST', url, data),
    put: (url, data = {}) => CRUDManager._rawRequest('PUT', url, data),
    delete: (url, data = {}) => CRUDManager._rawRequest('DELETE', url, data),
    request: (url, opts = {}) => CRUDManager.requestJson(url, { method: opts.method, data: opts.data }),
    submitForm: (form, cb) => CRUDManager.submitForm(form, cb)
};

// FormHelper shim
window.FormHelper = {
    bind: CRUDManager.bindForm.bind(CRUDManager),
    submit: CRUDManager.submitForm.bind(CRUDManager),
    showErrors: CRUDManager.showFormErrors.bind(CRUDManager),
    clearErrors: CRUDManager.clearFormErrors.bind(CRUDManager),
    getData: CRUDManager.getFormData.bind(CRUDManager),
    setData: CRUDManager.setFormData.bind(CRUDManager),
    toggleFavorite: CRUDManager.toggleFavorite.bind(CRUDManager),
    confirmDelete: CRUDManager.confirmDeleteSwal.bind(CRUDManager)
};

window.formatDate = Utils.formatDate;

// ============================================================================
// CHOICES.JS AUTO-INITIALIZATION
// ============================================================================

/**
 * Initialize Choices.js on select elements
 * @param {string|NodeList|Element} selector - CSS selector, NodeList, or single element
 * @param {Object} customOptions - Custom options to override defaults
 * @returns {Promise<Array>} Array of Choices instances
 */
window.initChoicesSelect = async function(selector = 'select.select2, select[data-choices], .choices-select', customOptions = {}) {
    if (typeof window.loadChoices !== 'function') return [];

    const Choices = await window.loadChoices();
    const instances = [];

    let elements;
    if (typeof selector === 'string') {
        elements = document.querySelectorAll(selector);
    } else if (selector instanceof NodeList) {
        elements = selector;
    } else if (selector instanceof Element) {
        elements = [selector];
    } else {
        return [];
    }

    elements.forEach(element => {
        if (!element || element.tagName !== 'SELECT') return;
        if (element._choices || element.dataset.choicesInitialized) return;

        const i18nPlaceholder = window.i18n?.messages?.select_an_option;
        const attrPlaceholder = element.getAttribute('data-placeholder') || element.getAttribute('placeholder');
        const placeholder = i18nPlaceholder || attrPlaceholder || (document.documentElement.lang === 'ar' ? 'اختر خيارًا' : 'Select an option');

        const defaultOptions = {
            searchEnabled: element.options.length > 5,
            itemSelectText: '',
            shouldSort: false,
            allowHTML: true,
            searchPlaceholderValue: window.i18n?.common?.search || 'Search...',
            noResultsText: window.i18n?.common?.no_results || 'No results found',
            noChoicesText: window.i18n?.common?.no_results || 'No results found',
            removeItemButton: element.hasAttribute('multiple'),
            placeholder: true,
            placeholderValue: placeholder,
        };

        try {
            const instance = new Choices(element, { ...defaultOptions, ...customOptions });
            element._choices = instance;
            element.dataset.choicesInitialized = 'true';
            instances.push(instance);
        } catch (error) {
            console.warn('Choices init failed:', error);
        }
    });

    return instances;
};

// ============================================================================
// AUTO-INITIALIZATION (single DOMContentLoaded)
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    try {
        UIManager.bindPasswordToggle();
        UIManager.initializeDropdownBehavior();

        // Show stored toast from previous page redirect
        const storedToast = sessionStorage.getItem('toastMessage');
        if (storedToast) {
            try {
                const { message, type } = JSON.parse(storedToast);
                if (message) ToastManager.show(message, type || 'info');
            } catch (_) {}
            sessionStorage.removeItem('toastMessage');
        }

        // Auto-initialize Choices.js
        window.initChoicesSelect().catch(() => null);
        // Retry once after i18n loads
        setTimeout(() => { window.initChoicesSelect().catch(() => null); }, 400);

        console.log('✅ JavaScript Utilities Loaded');
    } catch (e) {
        console.warn('Auto-initialization warning:', e);
    }
});

// Handle modal close buttons via event delegation
document.addEventListener('click', function(e) {
    if (e.target.matches('[data-bs-dismiss="modal"]') || e.target.closest('[data-bs-dismiss="modal"]')) {
        const modal = e.target.closest('.modal');
        if (modal) hideModal(modal);
    }
});
