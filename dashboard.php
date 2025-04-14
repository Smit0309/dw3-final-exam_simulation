<?php
require 'session.php';
require 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ?");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <a href="add_product.php">➕ Add Product</a> | 
    <a href="logout.php">🚪 Logout</a>
    <hr>

    <?php if ($products): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Action</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><img src="<?php echo $p['image_path']; ?>" width="80"></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo htmlspecialchars($p['description']); ?></td>
                    <td>$<?php echo number_format($p['price'], 2); ?></td>
                    <td><a href="delete_product.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Delete this product?');">🗑️ Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No products added yet.</p>
    <?php endif; ?>
</body>
</html>
