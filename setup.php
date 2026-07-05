
<?php
// 1. تضمين ملف الاتصال الجديد المربوط بـ Render
include 'db.php'; 

echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; direction: rtl;'>";
echo "<h2 style='color: #2c3e50; text-align: center;'>🛠️ معالج تهيئة متجر غرانج والمنتجات</h2>";

try {
    // 2. أمر إنشاء الجدول بالهيكلية الدقيقة والمطابقة لمتجرك
    $createTableQuery = "
    CREATE TABLE IF NOT EXISTS products (
        id SERIAL PRIMARY KEY,
        created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
        client_id TEXT NULL,
        title TEXT NULL,
        content TEXT NULL,
        price TEXT NULL,
        image_url TEXT NULL
    );";
    
    $pdo->exec($createTableQuery);
    echo "<p style='color: green; font-weight: bold;'>✅ تم إنشاء جدول المنتجات (products) في قاعدة بيانات Render بنجاح!</p>";

    // 3. فحص لو كان الجدول فارغاً لضخ منتج تجريبي فوراً لكي لا يظهر المتجر فارغاً
    $checkQuery = "SELECT COUNT(*) FROM products";
    $count = $pdo->query($checkQuery)->fetchColumn();

    if ($count == 0) {
        $insertQuery = "
        INSERT INTO products (title, content, price, image_url) 
        VALUES (
            'مسك الغزال الأصلي', 
            'عطر مسك طبيعي فاخر يدوم طويلاً، مناسب لجميع الاستخدامات الشخصية والمناسبات.', 
            '5000 ليرة', 
            'https://unsplash.com'
        );";
        
        $pdo->exec($insertQuery);
        echo "<p style='color: blue; font-weight: bold;'>🎁 تم إضافة أول منتج تجريبي (مسك الغزال الأصلي) إلى المتجر بنجاح!</p>";
    } else {
        echo "<p style='color: orange;'>ℹ️ الجدول يحتوي بالفعل على منتجات سابقة، لم يتم إضافة منتج تجريبي جديد.</p>";
    }

    echo "<hr><p style='text-align: center; margin-top: 20px;'>👉 <a href='index.php' style='background: #27ae60; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>اضغط هنا للذهاب للمتجر ورؤية النتيجة</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ فشل أثناء تهيئة الجدول: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p style='color: #7f8c8d; font-size: 13px;'>نصيحة: تأكد من أنك قمت بنسخ الباسورد الحقيقي المكون من نقاط في ملف db.php بشكل صحيح.</p>";
}

echo "</div>";
?>
