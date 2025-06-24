<?php
$conn = mysqli_connect("localhost", "root", "", "youssi_chic");
if (!$conn) { die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error()); }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) die("<div style='margin:80px auto;text-align:center;color:#d63384;font-size:22px'>المنتج غير موجود.</div>");

// جلب الصور المرتبطة بالمنتج من جدول product_media
$product_images = [];
$sql_imgs = "SELECT file_path FROM product_media WHERE product_id = $id";
$res_imgs = mysqli_query($conn, $sql_imgs);
while($row = mysqli_fetch_assoc($res_imgs)) {
    $product_images[] = $row['file_path'];
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($product['title']); ?> - Youssi Chic</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .product-detail-container {
      display: flex;
      gap: 50px;
      background: #fff7fa;
      border-radius: 25px;
      margin: 60px auto 70px auto;
      padding: 36px 35px 36px 35px;
      max-width: 1100px;
      box-shadow: 0 5px 32px #fae2ef;
      align-items: flex-start;
    }
    .product-images-side {
      display: flex;
      flex-direction: column;
      gap: 12px;
      min-width: 80px;
    }
    .thumbnail-list {
      width: 70px; height: 70px;
      object-fit: cover;
      border-radius: 11px;
      box-shadow: 0 1px 6px #eee;
      cursor: pointer;
      border: 2px solid #fff;
      transition: 0.14s;
    }
    .thumbnail-list:hover { border: 2px solid #b92c6b; }
    .main-product-image {
      width: 350px; height: 350px;
      object-fit: cover;
      border-radius: 20px;
      box-shadow: 0 2px 14px #f3d2e8;
      margin-bottom: 22px;
      background: #faf4f8;
      display: block;
    }
    .product-main-info h1 {
      color: #b92c6b;
      font-size: 2.1rem;
      margin-bottom: 8px;
      font-family: 'Playfair Display', serif;
    }
    .product-main-info .price {
      font-size: 22px;
      color: #d63384;
      font-weight: bold;
      margin-bottom: 12px;
    }
    .add-to-cart-btn {
      padding: 10px 35px;
      background: linear-gradient(90deg,#d63384,#aa2062);
      color: #fff;
      border-radius: 10px;
      border: none;
      font-weight: 600;
      font-size: 17px;
      cursor: pointer;
      margin-top: 16px;
      margin-bottom: 12px;
      text-decoration: none;
      transition: 0.16s;
      display: inline-block;
    }
    .add-to-cart-btn:hover {
      background: linear-gradient(90deg,#aa2062,#d63384);
      transform: scale(1.045);
    }
    /* popup styles */
    #orderForm {
      display: none;
      position: fixed; top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.13);
      z-index: 9999;
      align-items: center; justify-content: center;
    }
    #orderForm form {
      margin: auto; max-width: 400px;
      padding: 36px 28px;
      background: #fff; box-shadow: 0 2px 18px #fae2ef;
      border-radius: 13px;
      display: flex; flex-direction: column; gap: 16px;
      align-items: center;
      min-width: 300px;
    }
    #orderForm input[type="text"], #orderForm input[type="tel"] {
      padding: 10px 12px; border-radius: 8px; width: 100%;
      border: 1px solid #eee; font-size: 15px;
    }
    #orderForm .close-btn {
      background: none; color: #b92c6b; border: none;
      margin-top: 8px; cursor: pointer; font-size: 15px;
    }
    @media (max-width:900px) {
      .product-detail-container{flex-direction:column;align-items:center;padding:15px;}
      .main-product-image{width:92vw;height:270px;}
      .product-images-side{flex-direction:row;gap:6px;margin-bottom:12px;}
      .thumbnail-list{width:53px;height:53px;}
      #orderForm form{width:95vw;min-width:unset;}
    }
  </style>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
  <div class="nav-container">
    <a class="logo" href="index.php">
      <img src="images/logo.png" alt="Youssi Chic" style="height:38px;">
    </a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
      <a href="cart.php" class="cart-icon">
        <img src="images/icons/cart.png" alt="Cart" style="height:22px;margin-right:4px;vertical-align:middle;">
        Cart
      </a>
    </div>
  </div>
</div>

<!-- تفاصيل المنتج -->
<div class="product-detail-container">
  <div class="product-images-side">
    <?php foreach($product_images as $img): ?>
      <img src="media/<?php echo urlencode($img); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="thumbnail-list">
    <?php endforeach; ?>
  </div>
  <div class="product-main-info" style="flex:1;">
    <?php if(!empty($product_images)): ?>
      <img src="media/<?php echo urlencode($product_images[0]); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="main-product-image">
    <?php endif; ?>
    <h1><?php echo htmlspecialchars($product['title']); ?></h1>
    <div class="price"><?php echo number_format($product['price'],2); ?> MAD</div>
    <div style="margin:14px 0 25px 0;"><?php echo htmlspecialchars($product['description']); ?></div>
    <!-- الزر مكان Add to Cart -->
    <button type="button" class="add-to-cart-btn" onclick="openOrderForm()">Shop Now</button>
  </div>
</div>

<!-- popup form -->
<div id="orderForm">
  <form action="order.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <h2 style="color:#b92c6b;margin-bottom:8px;">إتمام الطلب</h2>
    <input type="text" name="fullname" required placeholder="الاسم الكامل">
    <input type="tel" name="phone" required placeholder="رقم الهاتف" pattern="^(05|06|07)[0-9]{8}$" title="أدخل رقم هاتف مغربي صحيح">
    <input type="text" name="address" required placeholder="العنوان">
    <button type="submit" class="add-to-cart-btn" style="width:100%;margin:0">تأكيد الطلب</button>
    <button type="button" class="close-btn" onclick="closeOrderForm()">إلغاء</button>
  </form>
</div>

<!-- Footer -->
<footer class="site-footer">
  <div>جميع الحقوق محفوظة © Youssi Chic 2025</div>
  <div class="social-icons">
    <a href="#"><img src="images/icons/facebook.png" alt="facebook"></a>
    <a href="#"><img src="images/icons/instagram.png" alt="instagram"></a>
    <a href="#"><img src="images/icons/whatsapp.png" alt="whatsapp"></a>
    <a href="#"><img src="images/icons/gmail.png" alt="gmail"></a>
  </div>
</footer>

<script>
window.onload = function(){
  // تبديل الصور مع الثامبنيل
  var thumbnails = document.querySelectorAll('.thumbnail-list');
  var mainImg = document.querySelector('.main-product-image');
  thumbnails.forEach(function(img){
    img.onclick = function(){
      mainImg.src = img.src;
    }
  });
};
// فتح وغلق popup
function openOrderForm(){
  document.getElementById('orderForm').style.display = 'flex';
}
function closeOrderForm(){
  document.getElementById('orderForm').style.display = 'none';
}
window.onclick = function(event){
  var of = document.getElementById('orderForm');
  if(event.target === of){ closeOrderForm(); }
}
</script>
</body>
</html>
