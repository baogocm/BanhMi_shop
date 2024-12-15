document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút thêm vào giỏ hàng
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            console.log('Adding product:', productId);
            addToCart(productId);
        });
    });

    // Xử lý nút tăng/giảm số lượng
    document.querySelectorAll('.btn-increase-quantity, .btn-decrease-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const productId = row.dataset.productId;
            const change = this.classList.contains('btn-increase-quantity') ? 1 : -1;
            const currentQuantity = parseInt(row.querySelector('.quantity').textContent);
            
            if (currentQuantity > 1 || change > 0) {
                updateCart(productId, change, row);
            }
        });
    });

    // Xử lý nút xóa
    document.querySelectorAll('.btn-remove-from-cart').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                const row = this.closest('tr');
                const productId = row.dataset.productId;
                removeFromCart(productId, row);
            }
        });
    });

    // Hàm thêm vào giỏ hàng
    function addToCart(productId) {
        fetch('giohang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'add',
                productId: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng trong header
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                }
                alert('Đã thêm sản phẩm vào giỏ hàng!');
            } else {
                alert(data.message || 'Không thể thêm sản phẩm vào giỏ hàng!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
        });
    }

    // Hàm cập nhật giỏ hàng
    function updateCart(productId, change, row) {
        fetch('giohang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'update',
                productId: productId,
                change: change
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng
                const quantityElement = row.querySelector('.quantity');
                const newQuantity = parseInt(quantityElement.textContent) + change;
                quantityElement.textContent = newQuantity;

                // Cập nhật tổng tiền sản phẩm
                const price = parseInt(row.querySelector('.product-price').textContent.replace(/[^\d]/g, ''));
                const totalElement = row.querySelector('.product-total');
                totalElement.textContent = formatCurrency(price * newQuantity);

                // Cập nhật tổng giỏ hàng
                document.getElementById('cart-total').textContent = formatCurrency(data.total);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Hàm xóa sản phẩm
    function removeFromCart(productId, row) {
        fetch('giohang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'remove',
                productId: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                document.getElementById('cart-total').textContent = formatCurrency(data.total);
                if (data.cartCount === 0) {
                    location.reload();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Format tiền tệ
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }
});
