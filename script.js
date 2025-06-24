// ✅ تحميل المنتجات تلقائياً من index.php باستخدام fetch
document.addEventListener("DOMContentLoaded", () => {
  const productGrid = document.getElementById("products");

  if (productGrid) {
    fetch("products.php")
      .then(res => res.text())
      .then(html => {
        productGrid.innerHTML = html;
      })
      .catch(err => {
        productGrid.innerHTML = "<p style='color:red;'>فشل تحميل المنتجات.</p>";
        console.error("خطأ أثناء تحميل المنتجات:", err);
      });
  }
});

// ✅ دالة السلايدر (تبديل الصور داخل البطاقة)
function showNext(button) {
  const slider = button.parentElement.querySelector('.product-image-slider');
  slider.scrollBy({ left: 160, behavior: 'smooth' });
}

function showPrev(button) {
  const slider = button.parentElement.querySelector('.product-image-slider');
  slider.scrollBy({ left: -160, behavior: 'smooth' });
}








