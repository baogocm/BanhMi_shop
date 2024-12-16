document.addEventListener('DOMContentLoaded', function() {
    const productsGrid = document.querySelector('.products-grid');
    const productCards = document.querySelectorAll('.product-card');

    if (productCards.length === 1) {
        productsGrid.classList.add('one-product');
    }
});
