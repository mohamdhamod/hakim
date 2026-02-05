/**
 * Simple phone input initialization
 * Just basic text input for phone numbers
 */
export function initPhoneInputs() {
    // Find all phone input fields
    const phoneInputs = document.querySelectorAll('input[type="tel"], input#phone, input[name="phone"]');
    
    phoneInputs.forEach(input => {
        // Skip if already initialized
        if (input.classList.contains('phone-initialized')) {
            return;
        }
        
        // Mark as initialized
        input.classList.add('phone-initialized');
        
        // Set placeholder if not set
        if (!input.placeholder) {
            input.placeholder = '09xxxxxxxx';
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPhoneInputs);
} else {
    initPhoneInputs();
}

// Re-initialize if new content is added dynamically
export function reinitPhoneInputs() {
    initPhoneInputs();
}

// Export for global access
window.initPhoneInputs = initPhoneInputs;
window.reinitPhoneInputs = reinitPhoneInputs;
