<?php
class Cart {
    private $db;

    public function __construct($db) {
        $this->db = $db;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    public function getCartItemCount($userId) {
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $items = [];
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->getProductById($productId); // Lấy chi tiết sản phẩm từ DB
                if ($product) {
                    $items[] = [
                        'product_id' => $productId,
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity
                    ];
                }
            }
            return $items;
        }
        return []; // Nếu không có sản phẩm nào trong giỏ hàng
    }
    
    function getLatestProducts($conn, $limit) {
        $sql = "SELECT id, name, description, price, image_url FROM products ORDER BY created_at DESC LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function addToCart($productId, $quantity) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        return true;
    }

    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }
    

    public function increaseQuantity($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
            return true;
        }
        return false;
    }
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
    

    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->getProductById($productId);
            if ($product) {
                $total += $product['price'] * $quantity;
            }
        }
        return $total;
    }

    public function getProductById($productId) {
        $sql = "SELECT * FROM products WHERE id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
