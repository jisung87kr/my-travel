/**
 * Accessibility Utilities
 *
 * Provides utility functions for improved accessibility:
 * - Focus management
 * - Screen reader announcements
 * - Keyboard navigation
 * - Motion preferences
 */

/**
 * Focus Trap - Keeps focus within a container (useful for modals)
 *
 * @param {HTMLElement} element - The container element to trap focus within
 * @returns {Function} Cleanup function to remove the trap
 *
 * @example
 * const modal = document.querySelector('#my-modal');
 * const cleanup = focusTrap(modal);
 * // When done: cleanup();
 */
export function focusTrap(element) {
    if (!element) {
        console.warn('focusTrap: No element provided');
        return () => {};
    }

    const focusableSelectors = [
        'a[href]',
        'button:not([disabled])',
        'textarea:not([disabled])',
        'input:not([disabled])',
        'select:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
    ].join(', ');

    const focusableElements = element.querySelectorAll(focusableSelectors);
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    function handleTabKey(e) {
        if (e.key !== 'Tab') return;

        // Shift + Tab (backward)
        if (e.shiftKey) {
            if (document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable.focus();
            }
        } else {
            // Tab (forward)
            if (document.activeElement === lastFocusable) {
                e.preventDefault();
                firstFocusable.focus();
            }
        }
    }

    element.addEventListener('keydown', handleTabKey);

    // Focus the first element
    if (firstFocusable) {
        firstFocusable.focus();
    }

    // Return cleanup function
    return () => {
        element.removeEventListener('keydown', handleTabKey);
    };
}

/**
 * Announce to Screen Reader
 *
 * Creates a live region announcement for screen readers
 *
 * @param {string} message - The message to announce
 * @param {string} priority - 'polite' (default) or 'assertive'
 *
 * @example
 * announceToScreenReader('Item added to cart', 'polite');
 * announceToScreenReader('Error: Form submission failed', 'assertive');
 */
export function announceToScreenReader(message, priority = 'polite') {
    if (!message) return;

    // Check if announcer already exists
    let announcer = document.getElementById('sr-announcer');

    // Create if it doesn't exist
    if (!announcer) {
        announcer = document.createElement('div');
        announcer.id = 'sr-announcer';
        announcer.setAttribute('role', 'status');
        announcer.setAttribute('aria-live', priority);
        announcer.setAttribute('aria-atomic', 'true');
        announcer.className = 'sr-only';
        announcer.style.cssText = `
            position: absolute;
            left: -10000px;
            width: 1px;
            height: 1px;
            overflow: hidden;
        `;
        document.body.appendChild(announcer);
    } else {
        // Update priority if different
        announcer.setAttribute('aria-live', priority);
    }

    // Clear and set new message
    announcer.textContent = '';
    setTimeout(() => {
        announcer.textContent = message;
    }, 100);
}

/**
 * Handle Escape Key
 *
 * Adds event listener for Escape key press
 *
 * @param {Function} callback - Function to call when Escape is pressed
 * @returns {Function} Cleanup function to remove the listener
 *
 * @example
 * const cleanup = handleEscapeKey(() => closeModal());
 * // When done: cleanup();
 */
export function handleEscapeKey(callback) {
    if (typeof callback !== 'function') {
        console.warn('handleEscapeKey: Callback must be a function');
        return () => {};
    }

    function handleKeyDown(e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            callback(e);
        }
    }

    document.addEventListener('keydown', handleKeyDown);

    // Return cleanup function
    return () => {
        document.removeEventListener('keydown', handleKeyDown);
    };
}

/**
 * Skip to Content
 *
 * Scrolls to main content and focuses it
 *
 * @param {string} targetId - ID of the main content element (default: 'main-content')
 *
 * @example
 * <button onclick="skipToContent()">Skip to main content</button>
 * <main id="main-content" tabindex="-1">...</main>
 */
export function skipToContent(targetId = 'main-content') {
    const target = document.getElementById(targetId);

    if (!target) {
        console.warn(`skipToContent: Element with id "${targetId}" not found`);
        return;
    }

    // Scroll to target
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Focus the target
    target.setAttribute('tabindex', '-1');
    target.focus();

    // Remove tabindex after focus
    target.addEventListener('blur', () => {
        target.removeAttribute('tabindex');
    }, { once: true });
}

/**
 * Check if user prefers reduced motion
 *
 * @returns {boolean} True if user prefers reduced motion
 *
 * @example
 * if (prefersReducedMotion()) {
 *     // Skip animations
 * } else {
 *     // Show animations
 * }
 */
export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Watch for reduced motion preference changes
 *
 * @param {Function} callback - Function to call when preference changes
 * @returns {Function} Cleanup function to remove the listener
 *
 * @example
 * const cleanup = watchReducedMotion((prefersReduced) => {
 *     console.log('Reduced motion:', prefersReduced);
 * });
 * // When done: cleanup();
 */
export function watchReducedMotion(callback) {
    if (typeof callback !== 'function') {
        console.warn('watchReducedMotion: Callback must be a function');
        return () => {};
    }

    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    function handleChange(e) {
        callback(e.matches);
    }

    // Call immediately with current value
    callback(mediaQuery.matches);

    // Listen for changes
    mediaQuery.addEventListener('change', handleChange);

    // Return cleanup function
    return () => {
        mediaQuery.removeEventListener('change', handleChange);
    };
}

/**
 * Make element accessible with proper ARIA attributes
 *
 * @param {HTMLElement} element - The element to enhance
 * @param {Object} options - Options for accessibility
 * @param {string} options.role - ARIA role
 * @param {string} options.label - ARIA label
 * @param {string} options.describedBy - ID of describing element
 * @param {boolean} options.expanded - ARIA expanded state
 * @param {boolean} options.hidden - ARIA hidden state
 *
 * @example
 * makeAccessible(button, {
 *     role: 'button',
 *     label: 'Close dialog',
 *     expanded: false
 * });
 */
export function makeAccessible(element, options = {}) {
    if (!element) {
        console.warn('makeAccessible: No element provided');
        return;
    }

    const { role, label, describedBy, expanded, hidden } = options;

    if (role) element.setAttribute('role', role);
    if (label) element.setAttribute('aria-label', label);
    if (describedBy) element.setAttribute('aria-describedby', describedBy);
    if (typeof expanded === 'boolean') {
        element.setAttribute('aria-expanded', String(expanded));
    }
    if (typeof hidden === 'boolean') {
        element.setAttribute('aria-hidden', String(hidden));
    }
}

/**
 * Focus first invalid form field
 *
 * Useful after form validation fails
 *
 * @param {HTMLFormElement} form - The form element
 * @returns {boolean} True if an invalid field was found and focused
 *
 * @example
 * form.addEventListener('submit', (e) => {
 *     if (!form.checkValidity()) {
 *         e.preventDefault();
 *         focusFirstInvalidField(form);
 *     }
 * });
 */
export function focusFirstInvalidField(form) {
    if (!form) {
        console.warn('focusFirstInvalidField: No form provided');
        return false;
    }

    const invalidField = form.querySelector(':invalid');

    if (invalidField) {
        invalidField.focus();
        invalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return true;
    }

    return false;
}

/**
 * Create accessible tooltip
 *
 * @param {HTMLElement} trigger - The element that triggers the tooltip
 * @param {string} text - Tooltip text
 * @returns {Object} Object with show, hide, and destroy methods
 *
 * @example
 * const tooltip = createTooltip(button, 'Click to save');
 * // Tooltip will show on hover/focus automatically
 * // Call tooltip.destroy() when done
 */
export function createTooltip(trigger, text) {
    if (!trigger || !text) {
        console.warn('createTooltip: trigger and text are required');
        return { show: () => {}, hide: () => {}, destroy: () => {} };
    }

    const tooltipId = `tooltip-${Math.random().toString(36).substr(2, 9)}`;
    const tooltip = document.createElement('div');

    tooltip.id = tooltipId;
    tooltip.textContent = text;
    tooltip.setAttribute('role', 'tooltip');
    tooltip.style.cssText = `
        position: absolute;
        background: #222;
        color: #fff;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
    `;

    document.body.appendChild(tooltip);
    trigger.setAttribute('aria-describedby', tooltipId);

    function show() {
        const rect = trigger.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
        tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
        tooltip.style.opacity = '1';
    }

    function hide() {
        tooltip.style.opacity = '0';
    }

    function destroy() {
        trigger.removeEventListener('mouseenter', show);
        trigger.removeEventListener('mouseleave', hide);
        trigger.removeEventListener('focus', show);
        trigger.removeEventListener('blur', hide);
        trigger.removeAttribute('aria-describedby');
        tooltip.remove();
    }

    trigger.addEventListener('mouseenter', show);
    trigger.addEventListener('mouseleave', hide);
    trigger.addEventListener('focus', show);
    trigger.addEventListener('blur', hide);

    return { show, hide, destroy };
}

/**
 * Keyboard navigation helper for lists
 *
 * Adds arrow key navigation to a list of elements
 *
 * @param {HTMLElement} container - The container element
 * @param {string} itemSelector - Selector for navigable items
 * @returns {Function} Cleanup function
 *
 * @example
 * const cleanup = enableKeyboardNav(menu, '[role="menuitem"]');
 */
export function enableKeyboardNav(container, itemSelector) {
    if (!container || !itemSelector) {
        console.warn('enableKeyboardNav: container and itemSelector are required');
        return () => {};
    }

    function handleKeyDown(e) {
        const items = Array.from(container.querySelectorAll(itemSelector));
        const currentIndex = items.indexOf(document.activeElement);

        if (currentIndex === -1) return;

        let nextIndex = currentIndex;

        switch (e.key) {
            case 'ArrowDown':
            case 'Down':
                e.preventDefault();
                nextIndex = Math.min(currentIndex + 1, items.length - 1);
                break;
            case 'ArrowUp':
            case 'Up':
                e.preventDefault();
                nextIndex = Math.max(currentIndex - 1, 0);
                break;
            case 'Home':
                e.preventDefault();
                nextIndex = 0;
                break;
            case 'End':
                e.preventDefault();
                nextIndex = items.length - 1;
                break;
            default:
                return;
        }

        if (items[nextIndex]) {
            items[nextIndex].focus();
        }
    }

    container.addEventListener('keydown', handleKeyDown);

    return () => {
        container.removeEventListener('keydown', handleKeyDown);
    };
}

// Export all functions as default object as well
export default {
    focusTrap,
    announceToScreenReader,
    handleEscapeKey,
    skipToContent,
    prefersReducedMotion,
    watchReducedMotion,
    makeAccessible,
    focusFirstInvalidField,
    createTooltip,
    enableKeyboardNav,
};
