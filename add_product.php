<?php
require 'session.php';
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Image handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $message = "Only JPG, JPEG, PNG allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $message = "File too large. Max 2MB.";
        } else {
            $upload_path = "uploads/" . uniqid() . ".$ext";
            move_uploaded_file($file_tmp, $upload_path);

            // Save to DB
            $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $desc, $price, $upload_path]);

            header("Location: dashboard.php");
            exit();
        }
    } else {
        $message = "Image is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Add New Product</h2>
    <?php if ($message): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
        <input type="file" name="image" accept=".jpg,.jpeg,.png" required><br><br>
        <button type="submit">Add Product</button>
    </form>
    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</body>
</html>
