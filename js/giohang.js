document.addEventListener('DOMContentLoaded', function() {

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            console.log('Adding product:', productId);
            addToCart(productId);
        });
    });

    
    const cartTable = document.querySelector('.cart-table');
    if (cartTable) {
        cartTable.addEventListener('click', function(e) {
            const target = e.target;
            const row = target.closest('tr');
            if (!row) return;

            const productId = row.dataset.productId;
            
            if (target.classList.contains('btn-increase-quantity')) {
                updateCart(productId, 1, row);
            } else if (target.classList.contains('btn-decrease-quantity')) {
                const currentQuantity = parseInt(row.querySelector('.quantity').textContent);
                if (currentQuantity > 1) {
                    updateCart(productId, -1, row);
                }
            } else if (target.classList.contains('btn-remove-from-cart')) {
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: 'Bạn có chắc muốn xóa sản phẩm này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeFromCart(productId, row);
                    }
                });
            }
        });
    }

    
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
                
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                }
                toastr.success('Đã thêm sản phẩm vào giỏ hàng!');
            } else {
                toastr.error(data.message || 'Không thể thêm sản phẩm vào giỏ hàng!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng!');
        });
    }

    
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
                
                const quantityElement = row.querySelector('.quantity');
                const newQuantity = parseInt(quantityElement.textContent) + change;
                quantityElement.textContent = newQuantity;

                
                const price = parseInt(row.querySelector('.product-price').textContent.replace(/[^\d]/g, ''));
                const totalElement = row.querySelector('.product-total');
                totalElement.textContent = formatCurrency(price * newQuantity);

                
                document.getElementById('cart-total').textContent = formatCurrency(data.total);
                
                
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                }
                
                toastr.success('Đã cập nhật giỏ hàng!');
            } else {
                toastr.error('Không thể cập nhật giỏ hàng!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi cập nhật giỏ hàng!');
        });
    }

    
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
                
                
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                }
                
                if (data.cartCount === 0) {
                    location.reload();
                }
                toastr.success('Đã xóa sản phẩm khỏi giỏ hàng!');
            } else {
                toastr.error('Không thể xóa sản phẩm!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Có lỗi xảy ra khi xóa sản phẩm!');
        });
    }

    
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }
});
