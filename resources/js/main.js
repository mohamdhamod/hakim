

// Debug flag to control logging
const DEBUG = false; // set to true while debugging

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeSidebar();
});


// Initialize sidebar state
function initializeSidebar() {
    const html = document.documentElement;

    // Set initial sidebar state based on screen size
    if (window.innerWidth < 992) {
        html.setAttribute('data-sidenav-size', 'offcanvas');
    } else {
        html.setAttribute('data-sidenav-size', 'default');
    }

    updateCollapseButton();
}


// Toggle sidebar collapse - CORRECTED VERSION
function toggleSidebar() {
    if (DEBUG) console.log('toggleSidebar called');

    const html = document.documentElement;
    const currentSize = html.getAttribute('data-sidenav-size');

    if (DEBUG) console.log('Current size:', currentSize);

    if (currentSize === 'collapse') {
        html.setAttribute('data-sidenav-size', 'default');
    } else {
        html.setAttribute('data-sidenav-size', 'collapse');
    }

    if (DEBUG) console.log('New size:', html.getAttribute('data-sidenav-size'));
    updateCollapseButton();
    // Force a small RTL repaint to ensure layout updates immediately in RTL builds
    if (typeof forceRtlRepaint === 'function') forceRtlRepaint();
}

// Update collapse button text and icon
function updateCollapseButton() {
    const html = document.documentElement;
    const collapseBtn = document.querySelector('.menu-collapse-box .button-collapse-toggle');

    if (!collapseBtn) {
        if (DEBUG) console.log('Collapse button not found');
        return;
    }

    const icon = collapseBtn.querySelector('i');
    const text = collapseBtn.querySelector('span');
    const isCollapsed = html.getAttribute('data-sidenav-size') === 'collapse';

    // Resolve labels from Blade-provided translations (preferred).
    // Fallbacks MUST NOT be hardcoded strings.
    const collapseLabel =
        collapseBtn.getAttribute('data-text-collapse')
        || (text && text.textContent ? text.textContent : '');
    const expandLabel =
        collapseBtn.getAttribute('data-text-expand')
        || collapseLabel;

    // Only update DOM when values actually change to avoid noisy repeated logs
    if (icon) {
        const desiredIcon = isCollapsed ? 'bi bi-chevron-double-right' : 'bi bi-chevron-double-left';
        if (icon.className !== desiredIcon) {
            icon.className = desiredIcon;
            if (DEBUG) console.log('Icon changed to', desiredIcon);
        }
    }

    if (text) {
        const desiredText = isCollapsed ? expandLabel : collapseLabel;
        if (text.textContent !== desiredText) {
            text.textContent = desiredText;
            if (DEBUG) console.log('Text changed to', desiredText);
        }
    }

    // Also toggle a force-collapse class on the sidenav-menu so the collapse applies
    // even if :hover rules would otherwise expand the sidebar.
    const sidenav = document.querySelector('.sidenav-menu');
    if (sidenav) {
        if (isCollapsed) sidenav.classList.add('force-collapse');
        else sidenav.classList.remove('force-collapse');
    }
}

// Toggle mobile sidebar
function toggleMobileSidebar() {
    const html = document.documentElement;
    html.classList.toggle('sidebar-enable');
    if (DEBUG) console.log('toggleMobileSidebar called');
    if (DEBUG) console.log('sidebar-enable class toggled:', html.classList.contains('sidebar-enable'));
    // RTL repaint helper
    if (typeof forceRtlRepaint === 'function') forceRtlRepaint();
}

// Expose functions used by inline onclick attributes
window.toggleSidebar = toggleSidebar;
window.toggleMobileSidebar = toggleMobileSidebar;


// Close mobile sidebar when clicking outside
document.addEventListener('click', function(event) {
    const sidenav = document.getElementById('sidenavMenu');
    const toggleBtn = document.querySelector('.button-collapse-toggle.d-xl-none');
    const html = document.documentElement;

    if (window.innerWidth < 992 &&
        sidenav &&
        toggleBtn &&
        !sidenav.contains(event.target) &&
        !toggleBtn.contains(event.target) &&
        html.classList.contains('sidebar-enable')) {
        html.classList.remove('sidebar-enable');
    }
});

// Handle responsive behavior (debounced)
function handleResize() {
    const html = document.documentElement;

    if (window.innerWidth >= 992) {
        // Close mobile sidebar on larger screens
        html.classList.remove('sidebar-enable');

        // Ensure proper sidebar mode for desktop
        if (html.getAttribute('data-sidenav-size') === 'offcanvas') {
            html.setAttribute('data-sidenav-size', 'default');
        }
    } else {
        // Switch to offcanvas mode for mobile
        if (html.getAttribute('data-sidenav-size') !== 'offcanvas') {
            html.setAttribute('data-sidenav-size', 'offcanvas');
        }
    }

    updateCollapseButton();
}

// Debounce helper
function debounce(fn, wait) {
    let t;
    return function(...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), wait);
    };
}

// Small helper to trigger a repaint/compositing layer in RTL when sidebar toggles.
function forceRtlRepaint() {
    const html = document.documentElement;
    try {
        if (html && html.getAttribute && html.getAttribute('dir') === 'rtl') {
            html.classList.add('ins-rtl-repaint');
            // remove shortly after to avoid lingering styles
            setTimeout(() => html.classList.remove('ins-rtl-repaint'), 60);
        }
    } catch (e) {
        if (DEBUG) console.log('forceRtlRepaint failed', e);
    }
}

// Language change functionality
function changeLanguage(countryCode, languageFlagUrl) {
    const flagImg = document.getElementById('selected-language-image');
    const flagEl = document.getElementById('selected-language-flag');
    const languageCode = document.getElementById('selected-language-code');

    // If we have an <img>, switch its src using provided URL or precomputed map
    if (flagImg) {
        if (languageFlagUrl) {
            flagImg.src = languageFlagUrl;
        } else if (window.FLAG_URLS && window.FLAG_URLS[countryCode]) {
            flagImg.src = window.FLAG_URLS[countryCode];
        }
    }

    // Backward-compat: if using CSS flag element, swap its class
    if (flagEl) {
        const toRemove = [];
        flagEl.classList.forEach(cls => { if (cls.startsWith('fi-')) toRemove.push(cls); });
        toRemove.forEach(cls => flagEl.classList.remove(cls));
        flagEl.classList.add(`fi-${countryCode}`);
    }

    if (languageCode) languageCode.textContent = ` ${countryCode.toUpperCase()}`;
    if (typeof DEBUG !== 'undefined' && DEBUG) console.log(`Language changed to: ${countryCode}`);
}

// Expose changeLanguage for inline onclick handlers in Blade templates
window.changeLanguage = changeLanguage;


window.addEventListener('resize', debounce(handleResize, 150));

// Add event listener for modal hidden event to reset form
document.addEventListener('DOMContentLoaded', function() {

    // Test if buttons are working
    const collapseBtn = document.querySelector('.menu-collapse-box .button-collapse-toggle');
    if (collapseBtn) {
        if (DEBUG) console.log('Collapse button found:', collapseBtn);
        // Only attach an event listener if there is no inline onclick to avoid double-invocation
        if (!collapseBtn.getAttribute('onclick')) {
            collapseBtn.addEventListener('click', function(e) {
                if (DEBUG) console.log('Collapse button clicked directly');
                toggleSidebar();
            });
        } else if (DEBUG) {
            console.log('Collapse button has inline onclick; skipping extra listener');
        }
    } else {
        if (DEBUG) console.log('Collapse button NOT found');
    }

    const mobileToggleBtn = document.querySelector('.button-collapse-toggle.d-xl-none');
    if (mobileToggleBtn) {
        if (DEBUG) console.log('Mobile toggle button found:', mobileToggleBtn);
        // Avoid double toggling: only add listener when no inline onclick exists
        if (!mobileToggleBtn.getAttribute('onclick')) {
            mobileToggleBtn.addEventListener('click', function(e) {
                if (DEBUG) console.log('Mobile toggle button clicked directly');
                toggleMobileSidebar();
            });
        } else if (DEBUG) {
            console.log('Mobile toggle button has inline onclick; skipping extra listener');
        }
    } else {
        if (DEBUG) console.log('Mobile toggle button NOT found');
    }
});
// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof initializeCharts === 'function') {
        initializeCharts();
    }
    // Dark mode toggle logic
    var darkBtn = document.querySelector('.topbar-link .bi-moon, .topbar-link .bi-moon-stars');
    if (darkBtn) {
        darkBtn.parentElement.addEventListener('click', function() {
            var html = document.documentElement;
            var current = html.getAttribute('data-bs-theme');
            html.setAttribute('data-bs-theme', current === 'dark' ? 'light' : 'dark');
        });
    }
});

// Current step for wizard form
        let currentStep = 1;
        const totalSteps = 5;

        // Card action (collapse/expand) handler: toggle .card-collapse on closest .card
        document.addEventListener('click', function(event) {
            try {
                var btn = event.target && event.target.closest ? event.target.closest('.card-action-item') : null;
                if (!btn) return;
                var card = btn.closest('.card');
                if (!card) return;

                // Toggle visual collapsed state with robust animation helper
                var willCollapse = !card.classList.contains('card-collapse');

                function onTransitionEndFactory(body) {
                    return function handler(e) {
                        // Only act on max-height transitions to avoid interfering with other transitions
                        if (e && e.propertyName && e.propertyName !== 'max-height') return;

                        // Determine card collapsed state after toggle
                        var cardEl = body.closest('.card');
                        var isCollapsed = cardEl && cardEl.classList.contains('card-collapse');

                        if (isCollapsed) {
                            // keep collapsed state: ensure body stays at 0 and hidden
                            body.style.overflow = 'hidden';
                            body.style.maxHeight = '0px';
                        } else {
                            // expanded: remove inline maxHeight so content can size naturally
                            body.style.overflow = '';
                            body.style.maxHeight = '';
                        }

                        body.__cardAnimating = false;
                        body.removeEventListener('transitionend', handler);
                    };
                }

                var bodies = card.querySelectorAll('.card-body');
                bodies.forEach(function(body) {
                    // Cancel existing animation marker
                    if (body.__cardAnimating) {
                        // let existing animation finish; clear listener first
                        // (we still proceed to set new target so it continues correctly)
                    }

                    var start = body.scrollHeight;
                    var handler = onTransitionEndFactory(body);
                    body.__cardAnimating = true;

                    // Ensure transition is on max-height for predictable behavior
                    body.style.transition = body.style.transition || 'max-height 0.28s ease';

                    if (willCollapse) {
                        // Collapse: animate from current height -> 0
                        // set explicit starting height in case it was 'auto'
                        body.style.maxHeight = start + 'px';
                        // force repaint then collapse
                        requestAnimationFrame(function() {
                            body.style.overflow = 'hidden';
                            body.style.maxHeight = '0px';
                        });
                        body.addEventListener('transitionend', handler);
                    } else {
                        // Expand: from 0 -> full height
                        // set to 0 first to allow transition
                        body.style.overflow = 'hidden';
                        body.style.maxHeight = '0px';
                        requestAnimationFrame(function() {
                            // expand to measured height
                            body.style.maxHeight = body.scrollHeight + 'px';
                        });
                        body.addEventListener('transitionend', handler);
                    }
                });

                // Toggle class for CSS changes (icon rotation etc.)
                card.classList.toggle('card-collapse');
            } catch (e) {
                if (typeof DEBUG !== 'undefined' && DEBUG) console.error('card-action toggle error', e);
            }
        });



        // (duplicate simple toggleSidebar removed - using the main toggleSidebar implementation above)

        // Wizard form functions
        function nextStep() {
            if (currentStep < totalSteps) {
                // Mark current step as done
                document.querySelector(`.wizard-tabs .nav-link[data-step="${currentStep}"]`).classList.add('wizard-item-done');

                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.remove('active');

                // Show next step
                currentStep++;
                document.getElementById(`step-${currentStep}`).classList.add('active');

                // Update active tab
                document.querySelectorAll('.wizard-tabs .nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                document.querySelector(`.wizard-tabs .nav-link[data-step="${currentStep}"]`).classList.add('active');
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                // Remove done class from current step
                document.querySelector(`.wizard-tabs .nav-link[data-step="${currentStep}"]`).classList.remove('wizard-item-done');

                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.remove('active');

                // Show previous step
                currentStep--;
                document.getElementById(`step-${currentStep}`).classList.add('active');

                // Update active tab
                document.querySelectorAll('.wizard-tabs .nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                document.querySelector(`.wizard-tabs .nav-link[data-step="${currentStep}"]`).classList.add('active');
            }
        }


        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize card bodies to respect any pre-existing collapsed state
            try {
                document.querySelectorAll('.card.card-collapse').forEach(function(card) {
                    card.querySelectorAll('.card-body').forEach(function(body) {
                        // make sure collapsed bodies are actually hidden
                        body.style.overflow = 'hidden';
                        body.style.maxHeight = '0px';
                    });
                });

                // Clean up any card-body leftover maxHeight for expanded cards
                document.querySelectorAll('.card:not(.card-collapse)').forEach(function(card) {
                    card.querySelectorAll('.card-body').forEach(function(body) {
                        body.style.maxHeight = '';
                        body.style.overflow = '';
                    });
                });
            } catch (e) {
                if (typeof DEBUG !== 'undefined' && DEBUG) console.error('card init error', e);
            }
        });
