<?php
// بيانات الاتصال الدقيقة لـ Supabase المحدثة والمتوافقة مع السيرفرات الخارجية
$host     = '://supabase.com'; 
$db       = 'postgres'; 
$port     = '6543'; // 🎯 هذا البورت يحل مشكلة tenant not found السابقة تماماً
$user     = 'postgres.usgpsmfnvkfzcuteunvu'; 
$password = 'GIqXgqRbuPDb8vQg'; 

// نص الاتصال القياسي لـ PostgreSQL في PHP
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    ]);
    
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>


