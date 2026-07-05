<?php
// 🎯 الاتصال المباشر والقاطع بقاعدة بيانات Supabase (بدون الـ Pooler المشاكس)
$host     = 'db.usgpsmfnvkfzcuteunvu.supabase.co'; // الهوست المباشر والمخصص لمشروعك
$db       = 'postgres'; 
$port     = '5432'; // المنفذ القياسي للاتصال المباشر بـ PostgreSQL
$user     = 'postgres'; // اسم المستخدم الافتراضي والنظيف (بدون نقط أو زيادات)
$password = 'GIqXgqRbuPDb8vQg'; // كلمة المرور الخاصة بك

// نص الاتصال (DSN)
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    // إنشاء الاتصال
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    ]);
    
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
