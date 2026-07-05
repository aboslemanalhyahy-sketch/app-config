<?php
// 1. تضمين ملف الاتصال بقاعدة بيانات Render
include 'db.php'; 

// 🎯 كود سحري: ينشئ جدول المنتجات تلقائياً داخل قاعدة بيانات ريندر الجديدة فور فتح الموقع لأول مرة
if (isset($pdo)) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (id SERIAL PRIMARY KEY, created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(), client_id TEXT, title TEXT, content TEXT, price TEXT, image_url TEXT);");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر مسك الإلكتروني</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="shop-header">
        <div class="shop-logo">متجر مسك الإلكتروني</div>
        <nav>
            <p style="color: #f1c40f; font-weight: bold; font-size: 14px;">🛍️ تصفح أحدث السلع المعروضة</p>
        </nav>
    </header>

    <main class="products-grid">
        <?php
        if (isset($pdo)) {
            try {
                $query = "SELECT title, content, price, image_url FROM products ORDER BY id DESC";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $products = $stmt->fetchAll();

                if (!empty($products)) {
                    foreach ($products as $prod) {
                        $title     = htmlspecialchars($prod['title'] ?? 'منتج بدون عنوان');
                        $content   = nl2br(htmlspecialchars($prod['content'] ?? 'لا يوجد وصف متاح.'));
                        $price     = htmlspecialchars($prod['price'] ?? 'غير محدد');
                        $image_url = htmlspecialchars($prod['image_url'] ?? 'default-product.png');
                        
                        $whatsapp_number = "963938562320"; 
                        $message_text = "مرحباً، أود الاستفسار وشراء منتج: (" . $title . ") بسعر (" . $price . ")";
                        $whatsapp_url = "https://wa.me" . $whatsapp_number . "?text=" . urlencode($message_text);
                        
                        ?>
                        <div class="product-card">
                            <div>
                                <div class="product-img-wrapper">
                                    <img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" class="product-img">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo $title; ?></h3>
                                    <p class="product-desc"><?php echo $content; ?></p>
                                </div>
                            </div>
                            <div class="product-meta">
                                <span class="product-price"><?php echo $price; ?></span>
                                <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="buy-whatsapp-btn">اطلب الآن 💬</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 40px;">🛒 لا توجد منتجات معروضة في المتجر حالياً.</p>';
                }
            } catch (PDOException $e) {
                echo '<p style="grid-column: 1/-1; text-align: center; color: #e74c3c; padding: 40px;">⚠️ خطأ في جلب البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p style="grid-column: 1/-1; text-align: center; color: #e74c3c; padding: 40px;">⚠️ جاري تهيئة الاتصال بالسيرفر السحابي...</p>';
        }
        ?>
    </main>

    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: #fff; font-size: 13px;">
        جميع الحقوق محفوظة © متجر مسك الإلكتروني ٢٠٢٦
    </footer>

</body>
</html>
