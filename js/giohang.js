document.addEventListener('DOMContentLoaded', function () {
    const buttonsAddCart = document.querySelectorAll('.btn-add-cart');

    buttonsAddCart.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'add', productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => console.error('Lỗi:', err));
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Nút tăng số lượng
    document.querySelectorAll('.btn-increase-quantity').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            updateCart('increase', productId);
        });
    });

    // Nút giảm số lượng
    document.querySelectorAll('.btn-decrease-quantity').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            updateCart('decrease', productId);
        });
    });

    // Nút xóa sản phẩm
    document.querySelectorAll('.btn-remove-from-cart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            updateCart('remove', productId);
        });
    });

    // Hàm cập nhật giỏ hàng
    function updateCart(action, productId) {
        fetch('cart_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ action: action, productId: productId }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Refresh trang sau khi cập nhật
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch((err) => console.error('Lỗi:', err));
    }
});

