// resources/js/category-selector.js

document.addEventListener('DOMContentLoaded', function () {
    const cards = Array.from(document.querySelectorAll('.sell-category-card'));
    const categorySelect = document.getElementById('categorySelect');
    const selectedCategoryKeyInput = document.getElementById('selectedCategoryKey');

    if (!cards.length || !categorySelect) return;

    function activateCardByKey(key) {
        cards.forEach((card) => {
            const isActive = card.dataset.key === key;

            card.classList.toggle('is-active', isActive);
            card.setAttribute('aria-pressed', isActive ? 'true' : 'false');

            if (isActive) {
                categorySelect.value = key;
                if (selectedCategoryKeyInput) {
                    selectedCategoryKeyInput.value = key;
                }
            }
        });
    }

    // คลิกการ์ด -> active + sync select
    cards.forEach((card) => {
        card.addEventListener('click', function () {
            activateCardByKey(this.dataset.key);
        });
    });

    // เปลี่ยน select -> active card
    categorySelect.addEventListener('change', function () {
        activateCardByKey(this.value);
    });

    // init
    activateCardByKey(categorySelect.value || 'smartphone');
});