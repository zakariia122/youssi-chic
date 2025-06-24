<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  die("Your cart is empty.");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $address = $_POST['address'] ?? '';
  $phone = $_POST['phone'] ?? '';

  if ($name && $address && $phone) {
    $product_ids = implode(',', $_SESSION['cart']);
    $stmt = $conn->prepare("INSERT INTO orders (name, address, phone, products) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $address, $phone, $product_ids]);

    $_SESSION['cart'] = [];
    $message = "Your order has been placed successfully!";
  } else {
    $error = "Please fill in all fields.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Place Order - Youssi Chic</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .order-form {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .order-form input,
    .order-form textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
    .order-form button {
      background-color: #d66b28;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }
    .order-form button:hover {
      background-color: #bb571f;
    }
    .message {
      text-align: center;
      margin-top: 20px;
      font-weight: bold;
      color: green;
    }
    .error {
      text-align: center;
      margin-top: 20px;
      color: red;
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
    </ul>
  </div>
</nav>

<div class="order-form">
  <h2>Place Your Order</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <textarea name="address" placeholder="Delivery Address" required></textarea>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <button type="submit">Submit Order</button>
  </form>

  <?php if (isset($message)): ?>
    <div class="message"><?= $message ?></div>
  <?php elseif (isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>
</div>

</body>
</html>







