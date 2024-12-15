<?php
class Cart {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    // Thêm sản phẩm vào giỏ hàng
    public function addItem($productId, $quantity = 1) {
        // Kiểm tra sản phẩm có tồn tại
        $sql = "SELECT id FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId]);
        
        if ($stmt->fetch()) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
            return true;
        }
        return false;
    }

    // Lấy sản phẩm trong giỏ hàng
    public function getCartItems() {
        $items = [];
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $sql = "SELECT id as product_id, name, price FROM products WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $product['quantity'] = $quantity;
                $items[] = $product;
            }
        }
        return $items;
    }

    // Cập nhật số lượng
    public function updateQuantity($productId, $change) {
        if (isset($_SESSION['cart'][$productId])) {
            $newQuantity = $_SESSION['cart'][$productId] + $change;
            if ($newQuantity > 0) {
                $_SESSION['cart'][$productId] = $newQuantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
            return true;
        }
        return false;
    }

    // Xóa sản phẩm
    public function removeItem($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }

    // Tính tổng tiền
    public function calculateTotal() {
        $total = 0;
        foreach ($this->getCartItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Đếm số lượng sản phẩm
    public function getCartItemCount() {
        return count($_SESSION['cart']);
    }

    // Thêm phương thức clearCart
    public function clearCart() {
        $_SESSION['cart'] = [];
        return true;
    }
}