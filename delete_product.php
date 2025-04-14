<?php
require 'session.php';
require 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Get image path
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$product_id, $user_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Delete file
        if (file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }

        // Delete DB row
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
        $stmt->execute([$product_id, $user_id]);
    }
}

header("Location: dashboard.php");
exit();
