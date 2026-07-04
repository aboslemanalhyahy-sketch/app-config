<?php
// 1. سحب بيانات المتصيد الحقيقية فوراً عبر السيرفر
$visitor_ip   = $_SERVER['REMOTE_ADDR'];
$visitor_agent = $_SERVER['HTTP_USER_AGENT'];
$log_time     = date('Y-m-d H:i:s');
$target_file  = "database.json";

// 2. تنسيق سطر السجل السري الذي سيحفظ لك للاطلاع عليه لاحقاً
$log_entry = "----------------------------------------\n" .
             "التاريخ والوقت: " . $log_time . "\n" .
             "عنوان الـ IP: " . $visitor_ip . "\n" .
             "بيانات الجهاز والمتصفح: " . $visitor_agent . "\n" .
             "الملف المستهدف: " . $target_file . "\n";

// 3. كتابة وحفظ البيانات الحقيقية داخل ملف نصي سري على السيرفر باسم hacker_logs.txt
// الخيار FILE_APPEND يضمن عدم مسح البيانات القديمة بل الكتابة أسفلها
file_put_contents('hacker_logs.txt', $log_entry, FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚨 رصد تهديد سيبراني حقيقي - محاولة اختراق 🚨</title>
    <style>
        body { background-color: #050505; color: #00ff00; font-family: 'Courier New', Courier, monospace, sans-serif; text-align: center; padding: 50px 20px; margin: 0; }
        .warning-box { max-width: 650px; margin: 30px auto; background: #111; padding: 40px; border: 2px solid #ff0000; border-radius: 8px; box-shadow: 0 0 30px rgba(255, 0, 0, 0.4); }
        h1 { color: #ff0000; font-size: 50px; margin-bottom: 5px; animation: blink 1.2s infinite; }
        h2 { color: #ffcc00; font-size: 20px; margin-bottom: 25px; }
        p { font-size: 15px; line-height: 1.8; color: #cccccc; text-align: right; border-right: 3px solid #ff0000; padding-right: 15px; margin-bottom: 20px; }
        .tech-logs { background-color: #000; padding: 15px; border-radius: 5px; text-align: left; direction: ltr; font-size: 13px; color: #ff0000; border: 1px solid #333; margin-top: 25px; overflow-x: auto; line-height: 1.5; }
        .back-btn { display: inline-block; margin-top: 30px; background-color: #ff0000; color: white; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: bold; font-size: 14px; box-shadow: 0 0 15px rgba(255, 0, 0, 0.3); transition: all 0.3s; }
        .back-btn:hover { background-color: #cc0000; box-shadow: 0 0 25px rgba(255, 0, 0, 0.6); }
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.1; } 100% { opacity: 1; } }
    </style>
</head>
<body>

    <div class="warning-box">
        <h1>[ ACCESS DENIED ]</h1>
        <h2>🚨 نظام الحماية النشط: تم تسجيل محاولة الاختراق الحية 🚨</h2>
        
        <p><strong>تنبيــه أمنــي صـارم:</strong> تم حظر دخولك المباشر إلى قاعدة البيانات. تم تفعيل بروتوكول تتبع المتسللين ونقل بصمتك الرقمية الحقيقية بنجاح إلى قاعدة البيانات الأمنية للموقع.</p>
        
        <p><strong>بيانات الرصد الحالية:</strong> نصوص النظام في الأسفل تعرض الآن معلومات جهازك وموقعك الإلكتروني الافتراضي الحقيقي والتي يتم تسجيلها وتوثيقها في هذه اللحظة:</p>
        
        <!-- هنا تكمن الصدمة الحقيقية؛ المتصيد سيرى الـ IP وجهازه معروضين بلون أحمر مخيف -->
        <div class="tech-logs">
            [SYS_LOG] INDIVIDUAL THREAT LOCATED...<br>
            [TIME] <?php echo $log_time; ?><br>
            [VIOLATION] TARGET => <?php echo $target_file; ?><br>
            [TARGET_IP] => <?php echo $visitor_ip; ?> (RECORDED)<br>
            [USER_AGENT] => <?php echo htmlspecialchars($visitor_agent); ?><br>
            [STATUS] LOG FILE 'hacker_logs.txt' GENERATED & SAVED... DONE.
        </div>

        <a href="index.php" class="back-btn"><< التراجع الفوري والهروب الآمن</a>
    </div>

</body>
</html>
