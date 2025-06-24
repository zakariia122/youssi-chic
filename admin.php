<?php
session_start();
$admin_password = "youssi123";
$login_error = "";
$success = "";
$error = "";

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        if ($_POST['admin_password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $login_error = "Wrong password!";
        }
    }
}

// عملية إضافة منتج
if (isset($_SESSION['admin_logged_in']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $colors = trim($_POST['colors']);
    $price = trim($_POST['price']);
    $link = trim($_POST['link']);

    try {
        $conn = new PDO("mysql:host=localhost;dbname=youssi_chic;charset=utf8mb4", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO products (title, description, colors, price, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $colors, $price, $link]);
        $product_id = $conn->lastInsertId();

        // رفع الصور
        $uploads_dir = "media";
        foreach ($_FILES['img']['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $filename = uniqid()."_".$_FILES['img']['name'][$key];
                move_uploaded_file($tmp_name, "$uploads_dir/$filename");
                $stmt2 = $conn->prepare("INSERT INTO product_media (product_id, file_path, media_type) VALUES (?, ?, 'image')");
                $stmt2->execute([$product_id, $filename]);
            }
        }
        $success = "Produit ajouté avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel — Add Product</title>
    <style>
        body {background: #ffeef8;}
        .admin-card {
            max-width: 480px;
            margin: 48px auto 0 auto;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 6px 32px #ce77b62c;
            padding: 48px 32px 40px 32px;
            position: relative;
        }
        .logout-container {
            position: absolute;
            top: 24px;
            right: 28px;
            z-index: 9;
        }
        .logout-btn {
            background: #c41c6e;
            color: #fff;
            border: none;
            border-radius: 18px;
            padding: 10px 28px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 6px #ff51ac17;
        }
        .logout-btn:hover {
            background: #a01453;
        }
        .admin-title {
            text-align: center;
            margin-bottom: 26px;
            font-size: 2.2rem;
            color: #c41c6e;
            font-weight: bold;
            margin-top: 12px;
        }
        .admin-form label {
            display: block;
            font-size: 1.05rem;
            margin-bottom: 7px;
            color: #a60050;
            font-weight: 600;
        }
        .admin-form input[type="text"],
        .admin-form input[type="number"],
        .admin-form textarea {
            width: 100%;
            padding: 13px 15px;
            margin-bottom: 18px;
            border: 1px solid #f2b2d4;
            border-radius: 9px;
            background: #fff7fa;
            font-size: 1rem;
        }
        .admin-form textarea {
            min-height: 70px;
            resize: vertical;
        }
        .admin-form input[type="file"] {
            margin-bottom: 16px;
        }
        .admin-form .submit-btn {
            width: 100%;
            background: #c41c6e;
            color: #fff;
            border: none;
            padding: 15px 0;
            font-size: 1.15rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .admin-form .submit-btn:hover {
            background: #a01453;
        }
        .login-form {
            max-width: 400px;
            margin: 90px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 3px 24px #ce77b62c;
            padding: 36px 30px 30px 30px;
            position: relative;
        }
        .login-form h2 {
            text-align: center;
            color: #c41c6e;
            margin-bottom: 25px;
        }
        .login-form input[type="password"] {
            width: 100%;
            padding: 13px 15px;
            margin-bottom: 18px;
            border: 1px solid #f2b2d4;
            border-radius: 9px;
            background: #fff7fa;
            font-size: 1rem;
        }
        .login-form button {
            width: 100%;
            background: #c41c6e;
            color: #fff;
            border: none;
            padding: 14px 0;
            font-size: 1.1rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
        }
        .login-form .error {
            color: #d9003a;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 12px;
        }
        .msg-success {color: #0b8e15; background: #e1ffe8; border-radius: 8px; padding: 8px; margin-bottom: 12px;}
        .msg-error {color: #b20b0b; background: #ffe6e6; border-radius: 8px; padding: 8px; margin-bottom: 12px;}
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged_in'])): ?>
    <form class="login-form" method="POST">
        <h2>Admin Login</h2>
        <?php if ($login_error): ?>
            <div class="error"><?= $login_error ?></div>
        <?php endif; ?>
        <input type="password" name="admin_password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
<?php else: ?>
    <div class="admin-card">
        <div class="logout-container">
            <form method="POST">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
        <h2 class="admin-title">Admin Panel — Add Product</h2>
        <?php if ($success): ?><div class="msg-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="msg-error"><?= $error ?></div><?php endif; ?>
        <form class="admin-form" method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Colors:</label>
            <input type="text" name="colors">
            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>
            <label>Link:</label>
            <input type="text" name="link">
            <label>Image 1:</label>
            <input type="file" name="img[]" accept="image/*" required>
            <label>Image 2:</label>
            <input type="file" name="img[]" accept="image/*">
            <label>Image 3:</label>
            <input type="file" name="img[]" accept="image/*">
            <label>Image 4:</label>
            <input type="file" name="img[]" accept="image/*">
            <button class="submit-btn" type="submit">Add Product</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
