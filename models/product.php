<?php

function getProductsByCategory($conn, $category_id) {
    $sql = "SELECT * FROM products WHERE category_id = ?";
    $stm = $conn->prepare($sql);
    $stm->execute([$category_id]);
    return $stm->fetchAll();
}

function getAllProducts($conn) {
    $sql = "SELECT * FROM products";
    $stm = $conn->prepare($sql);
    $stm->execute();
    return $stm->fetchAll();
}

function getLatestProducts($conn, $limit) {
    $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $limit, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

function getProductsPaginated($conn, $limit, $offset) {
    $sql = "SELECT * FROM products LIMIT ? OFFSET ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $limit, PDO::PARAM_INT);
    $stm->bindParam(2, $offset, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

function getProductsByCategoryPaginated($conn, $category_id, $limit, $offset) {
    $sql = "SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $category_id, PDO::PARAM_INT);
    $stm->bindParam(2, $limit, PDO::PARAM_INT);
    $stm->bindParam(3, $offset, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll();
}

function getTotalProducts($conn) {
    $sql = "SELECT COUNT(*) as total FROM products";
    $stm = $conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetch();
    return $result['total'];
}

function getTotalProductsByCategory($conn, $category_id) {
    $sql = "SELECT COUNT(*) as total FROM products WHERE category_id = ?";
    $stm = $conn->prepare($sql);
    $stm->bindParam(1, $category_id, PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetch();
    return $result['total'];
}
?>
