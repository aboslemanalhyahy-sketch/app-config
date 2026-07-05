<?php
// 🎯 بيانات الاتصال المباشرة والآمنة بقاعدة بيانات Render الداخلية الجديدة
$host     = '://render.com'; // العنوان الخارجي المباشر
$db       = 'ghranij_db'; // اسم قاعدة البيانات الجديدة
$port     = '5432'; // المنفذ الافتراضي
$user     = 'ghranij_db_user'; // اسم المستخدم المولد تلقائياً
$password = '0HAQSJ9ciwr6ZuAi7QuujmtH8JO4AhXx'; // ⚠️ تنبيه: قم بنسخ الباسورد الحقيقي المخفي خلف النقاط في شاشتك وضعه هنا
$dsn      = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    // إنشاء الاتصال المستقر
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    ]);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات الداخلية: " . $e->getMessage());
}
?>

