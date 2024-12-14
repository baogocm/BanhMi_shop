document.addEventListener('DOMContentLoaded', function () {
    const buttonsAddCart = document.querySelectorAll('.btn-add-cart');

    buttonsAddCart.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'add', productId, quantity: 1 }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        // Cập nhật giao diện giỏ hàng
                        updateCartUI(data.cart, data.total);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                    }
                })
                .catch(err => console.error('Lỗi:', err));
        });
    });

    // Hàm cập nhật giao diện giỏ hàng
    function updateCartUI(cartItems, total) {
        const cartContainer = document.querySelector('#cart-container');
        let cartHTML = '';

        cartItems.forEach(item => {
            cartHTML += `
                <div class="cart-item">
                    <p>${item.name} x ${item.quantity} - ${item.price}đ</p>
                </div>
            `;
        });

        cartHTML += `<p><strong>Tổng cộng: ${total}đ</strong></p>`;
        cartContainer.innerHTML = cartHTML;
    }
});
