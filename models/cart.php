<?php
// ...

class Cart {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy số lượng sản phẩm trong giỏ hàng của user
    public function getCartItemCount($userId) {
        $sql = "SELECT order_items.*, products.name, products.price FROM order_items 
                JOIN orders ON orders.id = order_items.order_id
                JOIN products ON products.id = order_items.product_id
                WHERE orders.user_id = :user_id AND orders.status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách sản phẩm trong giỏ hàng
    }

    // Tăng số lượng sản phẩm trong giỏ hàng
    public function increaseQuantity($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
            return true;
        }
        return false;
    }

    // Giảm số lượng sản phẩm trong giỏ hàng
    public function decreaseQuantity($productId) {
        if (isset($_SESSION['cart'][$productId]) && $_SESSION['cart'][$productId] > 1) {
            $_SESSION['cart'][$productId]--;
            return true;
        } elseif (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu theo ID
    public function getProductById($productId) {
        $sql = "SELECT * FROM products WHERE id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về thông tin sản phẩm
    }

    // Tính tổng tiền giỏ hàng
    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->getProductById($productId); // Tìm chi tiết sản phẩm từ cơ sở dữ liệu
            if ($product) {
                $total += $product['price'] * $quantity;
            }
        }
        return $total;
    }
}
?>
