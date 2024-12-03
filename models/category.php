<?php

function getAllCategories($conn) {
    $sql = "SELECT * FROM categories";
    $stm = $conn->prepare($sql);
    $stm->execute();
    return $stm->fetchAll();
}
?>
