<?php
// بيانات الاتصال الدقيقة بقاعدة بيانات Supabase الخاصة بك
$host     = '://supabase.com'; // تم تحديث النطاق بناءً على منطقتك (eu-west-2)
$db       = 'postgres'; // قاعدة البيانات الافتراضية
$user     = 'postgres.usgpsmfnvkfzcuteunvu'; // تم دمج اسم المستخدم مع معرف مشروعك الدقيق
$password = 'GIqXgqRbuPDb8vQg'; // ⚠️ ضع هنا كلمة المرور الخاصة بقاعدة بيانات Supabase التي اخترتها عند الإنشاء
$port     = '5432';

// نص الاتصال (DSN) لبيئة PostgreSQL في PHP
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    // إنشاء الاتصال باستخدام PDO
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // إظهار الأخطاء بوضوح إن وجدت
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // جلب البيانات على شكل مصفوفة مرتبة
    ]);
    
    // تم الاتصال بنجاح!
    // echo "تم الاتصال بنجاح!"; 
    
} catch (PDOException $e) {
    // في حال فشل الاتصال يعرض رسالة الخطأ لتسهيل معرفة السبب
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>

