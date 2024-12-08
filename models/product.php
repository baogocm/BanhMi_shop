<?php

// Lấy sản phẩm theo danh mục
function getProductsByCategory($conn, $category_id) {
    $sql = "SELECT * FROM products WHERE category_id = ?";
    $stm = $conn->prepare($sql);
    $stm->execute([$category_id]);
    return $stm->fetchAll();
}

// Lấy tất cả sản phẩm
function getAllProducts($conn) {
    $sql = "SELECT * FROM products";
    $stm = $conn->prepare($sql);
    $stm->execute();
    return $stm->fetchAll();
}

// Lấy sản phẩm mới nhất
function getLatestProducts($conn, $limit) {
    $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $limit, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

// Lấy sản phẩm theo phân trang
function getProductsPaginated($conn, $limit, $offset) {
    $sql = "SELECT * FROM products LIMIT ? OFFSET ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $limit, PDO::PARAM_INT);
    $stm->bindParam(2, $offset, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

// Lấy sản phẩm theo danh mục có phân trang
function getProductsByCategoryPaginated($conn, $category_id, $limit, $offset) {
    $sql = "SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $category_id, PDO::PARAM_INT);
    $stm->bindParam(2, $limit, PDO::PARAM_INT);
    $stm->bindParam(3, $offset, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

// Lấy tổng số sản phẩm
function getTotalProducts($conn) {
    $sql = "SELECT COUNT(*) as total FROM products";
    $stm = $conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetch();
    return $result['total'];
}

// Lấy tổng số sản phẩm theo danh mục
function getTotalProductsByCategory($conn, $category_id) {
    $sql = "SELECT COUNT(*) as total FROM products WHERE category_id = ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $category_id, PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch();
    return $result['total'];
}

// Lấy sản phẩm theo ID
function getProductById($conn, $id) {
    try {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Error getting product: " . $e->getMessage());
        return false;
    }
}

// Thêm sản phẩm mới
function addProduct($conn, $data) {
    try {
        $sql = "INSERT INTO products (name, description, price, image_url, category_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image_url'],
            $data['category_id']
        ]);
        return true;
    } catch(PDOException $e) {
        error_log("Error adding product: " . $e->getMessage());
        return false;
    }
}

// Cập nhật thông tin sản phẩm
function updateProduct($conn, $id, $data) {
    try {
        if (!empty($data['image_url'])) {
            $sql = "UPDATE products 
                   SET name = ?, description = ?, price = ?, 
                       image_url = ?, category_id = ? 
                   WHERE id = ?";
            $params = [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['image_url'],
                $data['category_id'],
                $id
            ];
        } else {
            $sql = "UPDATE products 
                   SET name = ?, description = ?, price = ?, 
                       category_id = ? 
                   WHERE id = ?";
            $params = [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category_id'],
                $id
            ];
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch(PDOException $e) {
        error_log("Error updating product: " . $e->getMessage());
        return false;
    }
}

// Xóa sản phẩm
function deleteProduct($conn, $id) {
    try {
        $sql = "SELECT image_url FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        if ($product && $product['image_url']) {
            $image_path = "../images/" . $product['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        return true;
    } catch(PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}

// Tải lên hình ảnh sản phẩm
function uploadProductImage($file) {
    $target_dir = "../images/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }

    if ($file["size"] > 5000000) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }

    return false;
}
?>
