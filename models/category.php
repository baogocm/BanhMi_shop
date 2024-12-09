<?php

// Lấy tất cả danh mục
function getAllCategories($conn) {
    $sql = "SELECT * FROM categories";
    $stm = $conn->prepare($sql);
    $stm->execute();
    return $stm->fetchAll();
}

// Thêm danh mục mới
function addCategory($conn, $name) {
    try {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name]);
        return true;
    } catch(PDOException $e) {
        error_log("Error adding category: " . $e->getMessage());
        return false;
    }
}


// Cập nhật danh mục
function updateCategory($conn, $id, $name) {
    try {
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $id]);
        return true;
    } catch(PDOException $e) {
        error_log("Error updating category: " . $e->getMessage());
        return false;
    }
}

// Xóa danh mục
function deleteCategory($conn, $id) {
    try {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return true;
    } catch(PDOException $e) {
        error_log("Error deleting category: " . $e->getMessage());
        return false;
    }
}

function getCategoryById($conn, $id) {
    try {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Error getting category: " . $e->getMessage());
        return false;
    }
}
?>
