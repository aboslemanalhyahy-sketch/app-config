<?php
session_start();
define('SHOP_PASSWORD', 'shop2026'); 

// تسجيل الخروج - التوجيه إلى اسم الملف الموحد admin.php
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['shop_logged_in']);
    header('Location: admin.php');
    exit;
}

// التحقق من كلمة المرور
if (isset($_POST['login'])) {
    if ($_POST['password'] === SHOP_PASSWORD) {
        $_SESSION['shop_logged_in'] = true;
    } else {
        $error = "كلمة المرور غير صحيحة!";
    }
}

// حجب اللوحة خلف جدار تسجيل الدخول
if (!isset($_SESSION['shop_logged_in']) || $_SESSION['shop_logged_in'] !== true): ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم </title>
    <!-- تم تعديل اسم ملف الـ CSS المستدعى ليطابق الاسم الموحد -->
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <!-- الكلاسات الأصلية للمتجر store-container و shop-btn -->
    <main class="store-container" style="max-width: 450px; padding:30px;">
        <h2 style="text-align:center; color:#2c3e50; margin-bottom:15px;"> لوحة التحكم</h2>
        <?php if(isset($error)): ?><p style="color:red; text-align:center; font-weight:bold;"><?php echo $error; ?></p><?php endif; ?>
        <form action="admin.php" method="POST">
            <div class="form-group">
                <label>كلمة المرور:</label>
                <input type="password" name="password" required placeholder="أدخل كلمة المرور">
            </div>
            <button type="submit" name="login" class="shop-btn">دخول</button>
        </form>
    </main>
</body>
</html>
<?php exit; endif; 

// تم تعديل اسم ملف قاعدة البيانات ليطابق الاسم الموحد
$db_file = 'database.json';
$message = "";

$products = [];
if (file_exists($db_file)) {
    $products = json_decode(file_get_contents($db_file), true);
    if (!is_array($products)) { $products = []; }
}

// معالجة حذف منتج
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    foreach ($products as $key => $prod) {
        if ($prod['id'] == $delete_id) {
            if (!empty($prod['image']) && file_exists($prod['image'])) {
                unlink($prod['image']); 
            }
            unset($products[$key]);
            $products = array_values($products); 
            file_put_contents($db_file, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $message = "<p style='color:green; font-weight:bold; text-align:center;'>🎉 تم حذف المنتج وصورته بنجاح!</p>";
            break;
        }
    }
}

// معالجة إضافة منتج جديد (المحدثة بحظر أحجام الصور وسد ثغرة السيرفر)
if (isset($_POST['add_product'])) {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $desc = trim($_POST['desc']);
    $image_name = "";

    // 🛑 1. فحص ذكي: إذا تم رصد ملف مرفوع ولكن السيرفر رفضه بسبب الحجم الزائد (Error Code 1 أو 2)
    if (isset($_FILES['image']) && ($_FILES['image']['error'] == 1 || $_FILES['image']['error'] == 2)) {
        $message = "<p style='color:red; font-weight:bold; text-align:center;'>❌ خطأ: حجم صورة المنتج ضخم جداً ويتجاوز قدرة السيرفر! يرجى اختيار صورة أصغر من 2 ميجابايت لنشر السلعة بنجاح.</p>";
    } 
    // 2. إذا لم يكن هناك خطأ سيرفر، نستمر في الفحص والرفع الطبيعي
    else if (!empty($title) && !empty($price) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        $can_upload = true;
        $max_size = 2 * 1024 * 1024; // 2 ميجابايت تماماً بالبايت
        $file_size = $_FILES['image']['size'];
        
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            
            // 🛑 3. شرط فحص الحجم البرمجي (2 ميجابايت كحد أقصى)
            if ($file_size > $max_size) {
                $message = "<p style='color:red; font-weight:bold; text-align:center;'>❌ خطأ: حجم صورة المنتج ضخم جداً! الحد الأقصى المسموح به هو 2 ميجابايت فقط لحفظ مساحة السيرفر وسرعة تصفح المتجر.</p>";
                $can_upload = false;
            }

            // تنفيذ الرفع والحفظ في ملف JSON فقط إذا كانت عملية الرفع سليمة
            if ($can_upload) {
                $image_name = 'shop_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_name)) {
                    
                    // تجهيز مصفوفة المنتج الجديد بنفس الهيكلية الأصلية لمتجرك
                    $new_prod = array(
                        'id' => time(), 
                        'title' => $title,
                        'price' => $price,
                        'desc' => $desc,
                        'image' => $image_name
                    );

                    $products[] = $new_prod; 
                    file_put_contents($db_file, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    $message = "<p style='color:green; font-weight:bold; text-align:center;'>🎉 تم إضافة السلعة بنجاح إلى كتالوج المتجر!</p>";
                } else {
                    $message = "<p style='color:red; font-weight:bold; text-align:center;'>خطأ: تعذر رفع الصورة إلى السيرفر!</p>";
                }
            }

        } else {
            $message = "<p style='color:red; font-weight:bold; text-align:center;'>خطأ: نوع ملف الصورة غير مدعوم!</p>";
        }
    } else {
        $message = "<p style='color:orange; font-weight:bold; text-align:center;'>⚠️ يرجى تعبئة الاسم والسعر واختيار صورة مناسبة للسلعة!</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم </title>
    <!-- تم تعديل اسم الملف المستدعى فقط -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="shop-header">
        <div class="shop-logo">لوحة التحكم </div>
        <nav>
            <a href="index.php" target="_blank">رؤية المتجر  🌐</a>
            <a href="admin.php?action=logout" style="color:#ff6b6b; font-weight:bold;">خروج 🚪</a>
        </nav>
    </header>

    <div class="admin-wrapper" style="max-width:900px; margin:40px auto; padding:0 20px;">
        <?php echo $message; ?>
        
        <div class="store-container" style="margin-bottom:40px; padding:30px;">
            <h3 style="color:#2c3e50; margin-bottom:20px;">إضافة سلعة جديدة للمتجر</h3>
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>اسم المنتج أو السلعة:</label>
                    <input type="text" name="title" required placeholder="مثال: ساعة يد، حقيبة سفر...">
                </div>
                <div class="form-group">
                    <label>السعر (اكتب العملة أيضاً):</label>
                    <input type="text" name="price" required placeholder="مثال: 50$ أو 150 ألف ل.س">
                </div>
                <div class="form-group">
                    <label>وصف مختصر وجذاب للسلعة:</label>
                    <textarea name="desc" rows="4" style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-family:inherit;" placeholder="اكتب تفاصيل وميزات السلعة هنا..."></textarea>
                </div>
                <div class="form-group">
                    <label>صورة السلعة الخارقة:</label>
                    <input type="file" name="image" accept="image/*" required style="border:none; padding:5px 0;">
                </div>
                <button type="submit" name="add_product" class="shop-btn">إضافة ونشر ✨</button>
            </form>
        </div>

        <div class="store-container" style="padding:30px;">
            <h3 style="color:#2c3e50; margin-bottom:20px;">السلع المعروضة حالياً (إجمالي: <?php echo count($products); ?>)</h3>
            <?php if(empty($products)): ?>
                <p style="text-align:center; color:#7f8c8d;">لا توجد سلع معروضة في المتجر حالياً.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; text-align:right;">
                        <thead>
                            <tr style="background:#f8f9fa; border-bottom:2px solid #ddd;">
                                <th style="padding:10px;">الصورة</th>
                                <th style="padding:10px;">الاسم</th>
                                <th style="padding:10px;">السعر</th>
                                <th style="padding:10px; text-align:center;">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $prod): ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:10px;"><img src="<?php echo htmlspecialchars($prod['image']); ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px;"></td>
                                <td style="padding:10px; font-weight:bold; color:#2c3e50;"><?php echo htmlspecialchars($prod['title']); ?></td>
                                <td style="padding:10px; color:#27ae60; font-weight:bold;"><?php echo htmlspecialchars($prod['price']); ?></td>
                                <td style="padding:10px; text-align:center;">
                                    <a href="admin.php?delete=<?php echo $prod['id']; ?>" onclick="return confirm('هل أنت متأكد من حذف هذه السلعة نهائياً؟')" style="color:#ff6b6b; text-decoration:none; font-weight:bold;">حذف 🗑️</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

