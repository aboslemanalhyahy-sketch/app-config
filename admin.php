<?php
session_start();
define('SHOP_PASSWORD', 'shop2026'); 

// 1. تضمين ملف الاتصال بقاعدة بيانات Supabase
include 'db.php';

// تسجيل الخروج
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['shop_logged_in']);
    header('Location: admin.php');
    exit;
}

// التحقق من كلمة المرور لدخول اللوحة
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
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
    <main class="store-container" style="max-width: 450px; padding:30px;">
        <h2 style="text-align:center; color:#2c3e50; margin-bottom:15px;">لوحة التحكم</h2>
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

$message = "";

// 2. معالجة حذف منتج من جدول Supabase
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $delete_id]);
        $message = "<p style='color:green; font-weight:bold; text-align:center;'>🎉 تم حذف المنتج من قاعدة البيانات بنجاح!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color:red; font-weight:bold; text-align:center;'>❌ خطأ أثناء الحذف: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// 3. معالجة إضافة منتج جديد إلى جدول Supabase
if (isset($_POST['add_product'])) {
    $title     = trim($_POST['title']);
    $price     = trim($_POST['price']);
    $content   = trim($_POST['desc']); // يطابق عمود content في جدولك
    $image_url = trim($_POST['image_url']); // قراءة رابط الصورة المباشر

    if (!empty($title) && !empty($price) && !empty($image_url)) {
        try {
            $query = "INSERT INTO products (title, price, content, image_url) VALUES (:title, :price, :content, :image_url)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':title'     => $title,
                ':price'     => $price,
                ':content'   => $content,
                ':image_url' => $image_url
            ]);
            $message = "<p style='color:green; font-weight:bold; text-align:center;'>🎉 تم إضافة السلعة ونشرها في المتجر بنجاح!</p>";
        } catch (PDOException $e) {
            $message = "<p style='color:red; font-weight:bold; text-align:center;'>❌ خطأ أثناء الإضافة: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $message = "<p style='color:orange; font-weight:bold; text-align:center;'>⚠️ يرجى تعبئة جميع الحقول المطلوبة ورابط الصورة!</p>";
    }
}

// 4. جلب قائمة السلع الحالية لعرضها في اللوحة
$products = [];
try {
    $query = "SELECT id, title, price, image_url FROM products ORDER BY id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $message .= "<p style='color:red; text-align:center;'>⚠️ خطأ في جلب السلع: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="shop-header">
        <div class="shop-logo">لوحة التحكم</div>
        <nav>
            <a href="index.php" target="_blank">رؤية المتجر 🌐</a>
            <a href="admin.php?action=logout" style="color:#ff6b6b; font-weight:bold;">خروج 🚪</a>
        </nav>
    </header>

    <div class="admin-wrapper" style="max-width:900px; margin:40px auto; padding:0 20px;">
        <?php echo $message; ?>
        
        <div class="store-container" style="margin-bottom:40px; padding:30px;">
            <h3 style="color:#2c3e50; margin-bottom:20px;">إضافة سلعة جديدة للمتجر</h3>
            <form action="admin.php" method="POST">
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
                <!-- 🎯 تم استبدال الرفع التقليدي بخانة رابط الصورة السحابي المستقر للأبد -->
                <div class="form-group">
                    <label>رابط صورة المنتج المباشر (URL):</label>
                    <input type="url" name="image_url" required placeholder="https://example.com" style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;">
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
                                <td style="padding:10px;"><img src="<?php echo htmlspecialchars($prod['image_url'] ?? ''); ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px;"></td>
                                <td style="padding:10px; font-weight:bold; color:#2c3e50;"><?php echo htmlspecialchars($prod['title'] ?? ''); ?></td>
                                <td style="padding:10px; color:#27ae60; font-weight:bold;"><?php echo htmlspecialchars($prod['price'] ?? ''); ?></td>
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
