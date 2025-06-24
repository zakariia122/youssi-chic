<?php
// products.php
$dsn = "mysql:host=localhost;dbname=youssi_chic;charset=utf8mb4";
$user = "root";
$pass = "";

try {
  $conn = new PDO($dsn, $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getAllImages($productId, $conn) {
  $stmt = $conn->prepare("SELECT file_path FROM product_media WHERE product_id = ? AND media_type = 'image'");
  $stmt->execute([$productId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products - Youssi Chic</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 28px;
      justify-content: center;
      margin: 36px 0;
    }
    .product-centered-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 30px #f2e5eb96;
      width: 270px;
      padding: 20px 18px 30px 18px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: 0.2s;
      min-height: 430px;
      position: relative;
    }
    .slider-container {
      width: 220px;
      height: 220px;
      position: relative;
      display: flex;
      align-items: center;
      margin-bottom: 16px;
      justify-content: center;
    }
    .slider-container button {
      background: #f4cee7;
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      font-size: 20px;
      cursor: pointer;
      transition: background 0.2s;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 2;
    }
    .slider-container button:first-child { left: 2px; }
    .slider-container button:last-child { right: 2px; }
    .product-image-slider {
      display: flex;
      overflow-x: auto;
      scroll-behavior: smooth;
      gap: 7px;
      width: 170px;
      height: 170px;
      border-radius: 14px;
      background: #faecf6;
      justify-content: flex-start;
      align-items: center;
    }
    .product-image-slider img {
      width: 170px;
      height: 170px;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
      background: #fff;
    }
    .product-info-centered {
      margin-top: 10px;
      text-align: center;
    }
    .product-info-centered h3 {
      font-size: 1.14rem;
      color: #af2e75;
      font-weight: bold;
      margin-bottom: 3px;
    }
    .product-info-centered .price {
      color: #c22677;
      font-weight: 600;
      margin-bottom: 8px;
    }
    .buy-button {
      padding: 8px 26px;
      background: linear-gradient(90deg, #d91e7c, #d96fba);
      color: #fff;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      margin-top: 10px;
      display: inline-block;
      transition: background 0.2s;
    }
    .buy-button:hover {
      background: linear-gradient(90deg, #c92670, #f985bb);
      color: #fff;
    }
  </style>
  <script>
    function showNext(button) {
      const slider = button.parentElement.querySelector('.product-image-slider');
      slider.scrollBy({ left: 180, behavior: 'smooth' });
    }
    function showPrev(button) {
      const slider = button.parentElement.querySelector('.product-image-slider');
      slider.scrollBy({ left: -180, behavior: 'smooth' });
    }
  </script>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.php" class="logo">
        <img src="images/logo.png" alt="Youssi Chic Logo">
      </a>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li>
          <a href="cart.php" class="cart-icon">
            <img src="images/icons/cart.png" alt="Cart" style="width: 24px;">
            <span id="cart-count">0</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <h2 class="section-title">All Products</h2>

  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <?php $imgs = getAllImages($product['id'], $conn); ?>
      <div class="product-centered-card">
        <div class="slider-container">
          <button onclick="showPrev(this)">&#10094;</button>
          <div class="product-image-slider">
            <?php if ($imgs): ?>
              <?php foreach ($imgs as $i): ?>
                <img src="media/<?= htmlspecialchars($i['file_path']) ?>" alt="Product Image">
              <?php endforeach; ?>
            <?php else: ?>
              <img src="images/icons/no-image.png" alt="No image">
            <?php endif; ?>
          </div>
          <button onclick="showNext(this)">&#10095;</button>
        </div>
        <div class="product-info-centered">
          <h3><?= htmlspecialchars($product['title']) ?></h3>
          <p class="price"><?= htmlspecialchars($product['price']) ?> MAD</p>
          <a href="shop.php?id=<?= $product['id'] ?>" class="buy-button">Shop Now</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <footer class="site-footer">
    <p>&copy; 2025 Youssi Chic. All rights reserved.</p>
    <div class="social-icons">
      <a href="https://wa.me/212712439464"><img src="images/icons/whatsapp.png" alt="WhatsApp"></a>
      <a href="https://www.instagram.com/la7yaa.1"><img src="images/icons/instagram.png" alt="Instagram"></a>
      <a href="https://www.facebook.com/share/18y1tQcjpB"><img src="images/icons/facebook.png" alt="Facebook"></a>
      <a href="mailto:yousszakaria681@gmail.com"><img src="images/icons/gmail.png" alt="Email"></a>
    </div>
  </footer>
</body>
</html>

