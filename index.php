<?php
// 1. تضمين ملف الاتصال بقاعدة بيانات Supabase
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر مسك الإلكتروني</title>
    <!-- استدعاء ملف الـ CSS بالاسم الموحد للمتجر -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- هيدر المتجر الأصلي -->
    <header class="shop-header">
        <div class="shop-logo">متجر مسك الإلكتروني</div>
        <nav>
            <p style="color: #f1c40f; font-weight: bold; font-size: 14px;">🛍️ تصفح أحدث السلع المعروضة</p>
        </nav>
    </header>

    <!-- حاوية المنتجات الشبكية الأصلية -->
    <main class="products-grid">
        <?php
        try {
            // 2. الاستعلام المطابق تماماً لأسماء الأعمدة في جدولك بـ Supabase
            // تم استخدام اسم الجدول الافتراضي "products"، تأكد من استبداله إذا سميت الجدول اسماً آخر
            $query = "SELECT title, content, price, image_url FROM products";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll();

            if (!empty($products)) {
                foreach ($products as $prod) {
                    
                    // صناعة رابط الواتساب التلقائي والذكي لكل منتج
                    $whatsapp_number = "963938562320"; 
                    $message_text = "مرحباً، أود الاستفسار وشراء منتج: (" . $prod['title'] . ") المعروض في المتجر بسعر (" . $prod['price'] . ")";
                    
                    // رابط الواتساب الجاهز
                    $whatsapp_url = "https://wa.me" . $whatsapp_number . "?text=" . urlencode($message_text);
                    
                    ?>
                    <!-- كارت السلعة الأصلي بكامل كلاساته -->
                    <div class="product-card">
                        <div>
                            <div class="product-img-wrapper">
                                <!-- تم تحديث العمود إلى image_url -->
                                <img src="<?php echo htmlspecialchars($prod['image_url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($prod['title'] ?? ''); ?>" class="product-img">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($prod['title'] ?? ''); ?></h3>
                                <!-- تم تحديث العمود إلى content ليطابق قاعدة البيانات -->
                                <p class="product-desc"><?php echo nl2br(htmlspecialchars($prod['content'] ?? '')); ?></p>
                            </div>
                        </div>
                        
                        <div class="product-meta">
                            <!-- عرض السعر المرن النصي كما هو مخزن (مثال: 5000 ليرة) -->
                            <span class="product-price"><?php echo htmlspecialchars($prod['price'] ?? ''); ?></span>
                            <!-- زر الطلب عبر الواتساب الأصلي للمتجر -->
                            <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" class="buy-whatsapp-btn">اطلب الآن 💬</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 40px;">🛒 لا توجد منتجات معروضة في المتجر حالياً.</p>';
            }
        } catch (PDOException $e) {
            // عرض رسالة خطأ واضحة في حال واجهت مشكلة في أسماء الجداول
            echo '<p style="grid-column: 1/-1; text-align: center; color: #e74c3c; padding: 40px;">⚠️ خطأ في جلب البيانات: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </main>

    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: #fff; font-size: 13px;">
        جميع الحقوق محفوظة © متجر مسك الإلكتروني ٢٠٢٦
    </footer>

</body>
</html>




