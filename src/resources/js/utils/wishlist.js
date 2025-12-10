/**
 * Wishlist UI Helper
 * 위시리스트 토글 공통 함수
 */

/**
 * Toggle wishlist for a product (card button style)
 * @param {number} productId
 * @param {HTMLElement} button
 */
export function toggleWishlist(productId, button) {
    const icon = button.querySelector('.wishlist-icon');
    button.classList.add('pointer-events-none');

    window.api.wishlist.toggle(productId)
        .then(data => {
            if (data.success) {
                const nowWishlisted = data.data?.added;
                button.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

                if (nowWishlisted) {
                    button.classList.remove('text-gray-500', 'hover:text-pink-500');
                    button.classList.add('text-pink-500');
                    if (icon) icon.setAttribute('fill', 'currentColor');
                } else {
                    button.classList.remove('text-pink-500');
                    button.classList.add('text-gray-500', 'hover:text-pink-500');
                    if (icon) icon.setAttribute('fill', 'none');
                }

                // Heart animation
                if (icon) {
                    icon.classList.add('scale-125');
                    setTimeout(() => icon.classList.remove('scale-125'), 200);
                }
            }
        })
        .catch(error => console.error('Wishlist error:', error))
        .finally(() => {
            button.classList.remove('pointer-events-none');
        });
}

/**
 * Toggle wishlist for product detail page (larger button style)
 * @param {number} productId
 * @param {HTMLElement} button
 */
export function toggleMainWishlist(productId, button) {
    const icon = document.getElementById('wishlist-icon');
    const text = document.getElementById('wishlist-text');

    button.disabled = true;
    button.classList.add('opacity-50');

    window.api.wishlist.toggle(productId)
        .then(data => {
            if (data.success) {
                const nowWishlisted = data.data?.added;
                button.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

                if (nowWishlisted) {
                    button.className = 'flex-1 flex items-center justify-center gap-2 px-4 py-3 border rounded-xl font-medium transition-all duration-200 cursor-pointer border-pink-300 bg-pink-50 text-pink-600';
                    if (icon) icon.setAttribute('fill', 'currentColor');
                    if (text) text.textContent = '위시리스트에 추가됨';
                } else {
                    button.className = 'flex-1 flex items-center justify-center gap-2 px-4 py-3 border rounded-xl font-medium transition-all duration-200 cursor-pointer border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300';
                    if (icon) icon.setAttribute('fill', 'none');
                    if (text) text.textContent = '위시리스트 추가';
                }

                if (icon) {
                    icon.style.transform = 'scale(1.25)';
                    setTimeout(() => { icon.style.transform = 'scale(1)'; }, 200);
                }
            }
        })
        .catch(error => console.error('Wishlist error:', error))
        .finally(() => {
            button.disabled = false;
            button.classList.remove('opacity-50');
        });
}

/**
 * Remove from wishlist (wishlist page)
 * @param {number} productId
 * @param {HTMLElement} button
 */
export function removeFromWishlist(productId, button) {
    window.api.wishlist.toggle(productId)
        .then(() => {
            const card = button.closest('article');
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.remove(), 300);
        })
        .catch(error => console.error('Wishlist error:', error));
}
