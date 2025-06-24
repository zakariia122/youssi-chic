<?php // ✅ PAGE: index.php
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
  <!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>سِّكان نسائية فاخرة | حقائب يد عصرية للبيع في المغرب - Youssi Chic</title>
  <meta name="description" content="تسوقي أحدث تشكيلة من حقائب اليد النسائية (سِّكان) العصرية والفاخرة من Youssi Chic. شحن سريع لجميع المدن المغربية، أثمنة مناسبة وتصاميم تناسب جميع الأذواق! اكتشفي الجديد الآن.">
  <meta name="keywords" content="حقائب نسائية, سِّكان, شنطة نساء, حقائب يد, شنط بنات, Bags Morocco, Women Handbags, شراء سِّكان المغرب">
  <meta property="og:title" content="سِّكان نسائية فاخرة | Youssi Chic" />
  <meta property="og:description" content="حقائب يد عصرية وفاخرة للنساء في المغرب. جديد التصاميم مع شحن سريع!" />
  <meta property="og:image" content="https://yoursite.com/images/main-bag.jpg" />
  <meta property="og:url" content="https://yoursite.com/" />
  <meta property="og:type" content="website" />
  <!-- باقي الروابط وملفات css ... -->
</head>
<body>
  <!-- باقي الصفحة ديالك ... -->
</body>
</html>

  
  <link rel="stylesheet" href="style.css">
  <style>
    /* welcome animation styles */
    .big-welcome-container {
      display: none; /* حذف الرسالة تماما */
    }
    /* hero background */
    .hero-bg-fixed {
      width: 100vw;
      height: 340px;
      position: relative;
      background: url('media/bck.jpg') center center/cover no-repeat;
      border-radius: 0 0 28px 28px;
      box-shadow: 0 5px 30px #e5a5c424;
      overflow: hidden;
      margin-bottom: 38px;
    }
    .hero-bg-overlay {
      position: absolute;
      top: 0; left: 0; width: 100vw; height: 100%;
      background: linear-gradient(90deg,#c41c6e77 7%,#fff0 60%);
      z-index: 1;
    }
    .hero-bg-content {
      position: absolute;
      top: 50%;
      left: 60px;
      transform: translateY(-50%);
      color: #fff;
      z-index: 2;
      font-family: 'Playfair Display', serif;
      text-shadow: 0 3px 24px #c41c6e7c;
    }
    .hero-bg-content h1 {
      font-size: 2.7rem;
      margin-bottom: 12px;
      font-weight: bold;
      letter-spacing: 1px;
    }
    .hero-bg-content p {
      font-size: 1.12rem;
      font-weight: 500;
    }
    @media (max-width: 700px) {
      .hero-bg-fixed { height: 120px;}
      .hero-bg-content h1 { font-size: 1.05rem;}
      .hero-bg-content { left: 12px;}
    }
  </style>
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

  <!-- hero background (banner image bck.jpg) -->
  <div class="hero-bg-fixed">
    <div class="hero-bg-overlay"></div>
    <div class="hero-bg-content">
      <h1>Welcome in Youssi Chic!</h1>
      <p>Discover the Newest Arrivals</p>
    </div>
  </div>

  <!-- حذف رسالة الترحيب الثانية نهائياً -->
  <!-- <div class="big-welcome-container">
    <span id="big-welcome"></span>
  </div> -->

  <h2 class="section-title">New Arrivals</h2>
  <div class="single-product-wrapper">
    <?php foreach ($products as $product): ?>
      <?php
        $images = getAllImages($product['id'], $conn);
        $mainImage = $images[0]['file_path'] ?? 'images/icons/no-image.png';
      ?>
      <div class="single-product-card">
        <img src="media/<?= $mainImage ?>" alt="<?= htmlspecialchars($product['title']) ?>">
        <h3><?= htmlspecialchars($product['title']) ?></h3>
        <p class="price"><?= number_format($product['price'], 2) ?> MAD</p>
        <a href="shop.php?id=<?= $product['id'] ?>" class="buy-button">Shop Now</a>
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

