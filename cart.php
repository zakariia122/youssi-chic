<?php
session_start();

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
  $id = intval($_GET['add']);
  if (!in_array($id, $_SESSION['cart'])) {
    $_SESSION['cart'][] = $id;
  }
  header("Location: cart.php");
  exit();
}

if (isset($_GET['remove'])) {
  $id = intval($_GET['remove']);
  $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($pid) => $pid != $id);
  header("Location: cart.php");
  exit();
}

$dsn = "mysql:host=localhost;dbname=youssi_chic;charset=utf8mb4";
$user = "root";
$pass = "";

try {
  $conn = new PDO($dsn, $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$cartProducts = [];
if (!empty($_SESSION['cart'])) {
  $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
  $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
  $stmt->execute($_SESSION['cart']);
  $cartProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Youssi Chic</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .cart-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }
    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }
    .cart-item:last-child {
      border-bottom: none;
    }
    .cart-item h3 {
      margin: 0;
    }
    .cart-item a {
      color: red;
      text-decoration: none;
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="nav-container">
    <a href="index.php" class="logo"><img src="images/logo.png" alt="Logo"></a>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="cart.php" class="cart-icon"><img src="images/icons/cart.png" style="width: 24px;"><span id="cart-count"><?= count($_SESSION['cart']) ?></span></a></li>
    </ul>
  </div>
</nav>

<div class="cart-container">
  <h2>Your Shopping Cart</h2>
  <?php if ($cartProducts): ?>
    <?php foreach ($cartProducts as $prod): ?>
      <div class="cart-item">
        <h3><?= htmlspecialchars($prod['title']) ?> - <?= $prod['price'] ?> MAD</h3>
        <a href="cart.php?remove=<?= $prod['id'] ?>">Remove</a>
      </div>
    <?php endforeach; ?>
    <br>
    <a href="order.php" class="buy-button">Proceed to Order</a>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>
</div>

</body>
</html>

