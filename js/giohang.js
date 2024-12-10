document.addEventListener('DOMContentLoaded', function () {
    const buttonsIncrease = document.querySelectorAll('.btn-increase-quantity');
    const buttonsDecrease = document.querySelectorAll('.btn-decrease-quantity');
    const buttonsRemove = document.querySelectorAll('.btn-remove-cart');

    buttonsIncrease.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'increase', productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Số lượng sản phẩm đã tăng!');
                    // Update UI or refresh the cart
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => console.error('Lỗi:', err));
        });
    });

    buttonsDecrease.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'decrease', productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Số lượng sản phẩm đã giảm!');
                    // Update UI or refresh the cart
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => console.error('Lỗi:', err));
        });
    });

    buttonsRemove.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'remove', productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được xóa khỏi giỏ hàng!');
                    // Update UI or refresh the cart
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(err => console.error('Lỗi:', err));
        });
    });
});
