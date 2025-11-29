<?php
include __DIR__ . '/db_connect.php';

// 1. استقبال المدخلات من الـ URL (للفلاتر) - FIXED
$selected_region = $_GET['region'] ?? '';
$selected_city = $_GET['city'] ?? ''; // Fixed variable name
$selected_category = $_GET['category'] ?? '';

// Keep original for display
$selected_city_name = $selected_city;

// 2. استخلاص البيانات الديناميكية للفلاتر
$regions = get_regions($conn);
$cities = get_cities($conn, $selected_region);
$categories = get_categories();

// 3. تمرير المتغيرات إلى ملف العرض
$filtered_attractions = [];
if (!empty($selected_region) || !empty($selected_city) || !empty($selected_category)) {
    // بناء جملة WHERE بناءً على الفلاتر المختارة
    $where_clauses = [];
    
    if (!empty($selected_region) && is_numeric($selected_region)) {
        $where_clauses[] = "t.region_id = " . intval($selected_region);
    }
    
    // تعديل شرط المدينة - FIXED
    if (!empty($selected_city)) {
        $safe_city_name = $conn->real_escape_string($selected_city);
        $where_clauses[] = "c.name = '{$safe_city_name}'";
    }
    
    if (!empty($selected_category)) {
        $safe_category = $conn->real_escape_string($selected_category);
        $where_clauses[] = "t.category = '{$safe_category}'";
    }
    
    $where = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";
    
    // بناء الاستعلام الكامل
    $sql = "SELECT 
                t.*,
                r.name AS region_name, 
                c.name AS city_name 
            FROM tourist_attractions t
            LEFT JOIN regions r ON t.region_id = r.id
            LEFT JOIN cities c ON t.city_id = c.id
            " . $where . " 
            ORDER BY t.name";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $filtered_attractions = $result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    // جلب جميع المعالم إذا لم يتم تطبيق أي فلتر
    $sql = "SELECT 
                t.*,
                r.name AS region_name, 
                c.name AS city_name 
            FROM tourist_attractions t
            LEFT JOIN regions r ON t.region_id = r.id
            LEFT JOIN cities c ON t.city_id = c.id
            ORDER BY t.name";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $filtered_attractions = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع المعالم السياحية - رحال</title>
    <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/article.css">

    <link rel="icon" href="../images/Favicon/favicon-32x32.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
                  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .attractions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            padding: 30px 20px;
            max-width: 1400px;
            margin: 80px auto 0;
        }
        .page-header {
            text-align: center;
            padding: 100px 20px 40px;
            background: #142836;
            color: white;
            margin-top: 80px;
        }
        .back-button {
            display: inline-block;
            margin: 20px 0;
            background: #be9d6a;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            color: #f2f2f2;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-family: 'Tajawal', sans-serif;
        }
        .back-button:hover {
            background: #a9854f;
            transition: background 0.3s ease;
        }
        
        .filters-section {
            background: #f8f9fa;
            padding: 30px 20px;
            margin: 40px auto;
            border-radius: 15px;
            max-width: 1400px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .filter-group {
            margin-bottom: 20px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #142836;
            font-family: 'Tajawal', sans-serif;
        }
        .filter-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .filter-group select:focus {
            border-color: #be9d6a;
            outline: none;
        }
         .page-header {
        background: linear-gradient(135deg, #142836 0%, #1e3a52 100%);
        color: white;
        padding: 120px 20px 80px;
        margin-top: 80px;
        position: relative;
        overflow: hidden;
        text-align: center;
        font-family: 'Tajawal', sans-serif;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(190, 157, 106, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(190, 157, 106, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        animation: float 6s ease-in-out infinite;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #be9d6a, #d4b78c, #be9d6a);
        background-size: 200% 100%;
        animation: shimmer 3s ease-in-out infinite;
    }

    .header-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }

    .page-header h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 2px 4px 12px rgba(0,0,0,0.3);
        animation: fadeInUp 0.8s ease;
    }

    .page-header .subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
        margin-bottom: 2.5rem;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease 0.2s both;
    }

    .header-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-bottom: 3rem;
        animation: fadeInUp 0.8s ease 0.4s both;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        color: #be9d6a;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: white;
        padding: 15px 30px;
        text-decoration: none;
        border-radius: 50px;
        font-family: 'Tajawal', sans-serif;
        font-weight: 600;
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease 0.6s both;
    }

    .back-button:hover {
        background: rgba(190, 157, 106, 0.3);
        border-color: #be9d6a;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(190, 157, 106, 0.3);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    @keyframes shimmer {
        0%, 100% { background-position: -200% 0; }
        50% { background-position: 200% 0; }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
.navbar {
    padding: 0 20px 0 20px !important; /* هامش من الجانبين */
}
    </style>
</head>
<body>
    <section >
        <div class="navbar" id="navbar" style="background-color: #f2f2f2;">
            <!-- Search bar على أقصى اليسار -->
            <form method="GET" action="search_results.php" class="search-form">
                <input dir="rtl" class="search-bar" type="text" name="query" placeholder="ابحث عن معلم سياحي...">
            </form>

            <!-- مجموعة اليمين: الأزرار + اللوجو -->
            <div class="navbar-right-group">
                <!-- الأزرار على يسار اللوجو -->
                <ul class="nav-links" dir="rtl">
                    <li><a href="../../index.php">الرئيسية</a></li>
                    <li><a href="attractions_list.php">معالم المملكة</a></li>
                    <li><a href="../html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                    <li><a href="../../index.php#map-section">الخريطة</a></li>
                </ul>
                
                <!-- اللوجو على أقصى اليمين -->
                <a href="../../index.php"><img src="../images/logo/second-logo.png" alt="RahalLogo" id="logo"></a>
            </div>
        </div>
    </section>
<!-- عنوان الصفحة -->
<div class="page-header" dir="rtl">
    <div class="header-content">
        <h1>جميع المعالم السياحية</h1>
        <p class="subtitle">اكتشف روعة المملكة العربية السعودية من خلال معالمها السياحية المتنوعة والخلابة</p>
        
        <div class="header-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($filtered_attractions) ?></span>
                <span class="stat-label">معلم سياحي</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count($regions) ?></span>
                <span class="stat-label">منطقة</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count($categories) ?></span>
                <span class="stat-label">فئة</span>
            </div>
        </div>
        
        <a href="../../index.php" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            العودة للرئيسية
        </a>
    </div>
</div>

<!-- قسم الفلاتر -->
<div class="filters-section" dir="rtl">
    <form method="GET" action="attractions_list.php">
        <h2 style="text-align: center; margin-bottom: 30px; color: #142836; font-family: 'Tajawal', sans-serif;">تصفيات المعالم</h2>
        
        
        <div class="row">
            <div class="col-md-4">
                <div class="filter-group">
                    <label for="region">اختر المنطقة:</label>
                    <select name="region" id="region" onchange="this.form.submit()">
                        <option value="">كل المناطق</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= ($selected_region == $r['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
    <div class="filter-group">
        <label for="city">اختر المدينة:</label>
        <select name="city" id="city" onchange="this.form.submit()">
            <option value="">كل المدن</option>
            <?php foreach ($cities as $c): ?>
                <option value="<?= $c['name'] ?>" <?= ($selected_city == $c['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

            <div class="col-md-4">
                <div class="filter-group">
                    <label for="category">اختر الفئة:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="">كل الفئات</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($selected_category == $cat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <noscript>
            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" value="تصفية" class="btn btn-primary" style="background: #142836; border: none; padding: 10px 30px;">
            </div>
        </noscript>
    </form>
</div>

<!-- عرض المعالم -->
<div class="attractions-grid" >
    <?php if (!empty($filtered_attractions)): ?>
        <?php foreach ($filtered_attractions as $attraction): ?>
    <div class="attraction-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: transform 0.3s ease; border: 1px solid #eee; text-align: right; direction: rtl; display: flex; flex-direction: column; height: 100%;">
        
        <div style="height: 250px; overflow: hidden; flex-shrink: 0;">
            <img src="<?= htmlspecialchars($attraction['image_url'] ?? '/images/placeholder.jpg') ?>" 
                 alt="<?= htmlspecialchars($attraction['name']) ?>" 
                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
        </div>
        
        <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
            <h4 style="color: #142836; margin-bottom: 10px; font-family: 'Tajawal', sans-serif; font-size: 22px; text-align: right;">
                <?= htmlspecialchars($attraction['name']) ?>
            </h4>
            
            <p style="color: #666; line-height: 1.6; margin-bottom: 15px; font-family: 'Tajawal', sans-serif; text-align: right; font-size: 16px;">
                <?= nl2br(htmlspecialchars(substr($attraction['description'] ?? 'لا يوجد وصف', 0, 120))) ?>...
            </p>
            
            <div style="margin-bottom: 15px;">
                <p style="margin-bottom: 5px;"><strong>المنطقة:</strong> <?= htmlspecialchars($attraction['region_name'] ?? 'غير محدد') ?></p>
                <p style="margin-bottom: 5px;"><strong>المدينة:</strong> <?= htmlspecialchars($attraction['city_name'] ?? 'غير محدد') ?></p>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <span style="background: #be9d6a; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-family: 'Tajawal', sans-serif; font-weight: 500;">
                    <?= htmlspecialchars($attraction['category'] ?? 'غير مصنف') ?>
                </span>
            </div>
            
            <?php if (!empty($attraction['html_file'])): ?>
                <a id="Details-Button" href="html_wrapper.php?file=<?= htmlspecialchars($attraction['html_file']) ?>" 
                    style="margin-top: auto; display: block; text-align: center; background: linear-gradient(135deg, #142836 0%, #1e3a52 100%); color: #f2f2f2; padding: 12px; text-decoration: none; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-weight: 500; transition: background 0.3s ease; font-size: 18px; border: 2px solid transparent">
                    اقرأ أكثر / تفاصيل
                </a>
            <?php else: ?>
                <button style="margin-top: auto; width: 100%; background: #ccc; color: #666; padding: 12px; border: none; border-radius: 8px; font-family: 'Tajawal', sans-serif; cursor: not-allowed; font-size: 16px;">
                    التفاصيل غير متاحة
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p style="font-size: 18px; color: #666; font-family: 'Tajawal', sans-serif;">
                لا توجد معالم سياحية متاحة حالياً.
            </p>
        </div>
    <?php endif; ?>
</div>

<!--
=======================================================
Footer
=======================================================
-->
      <section dir="rtl">
<!-- الفوتر المذهل -->
<footer class="article-footer">
    <!-- الشريط النمطي المتكرر -->
    <div class="footer-top-pattern"></div>
    
    <div class="footer-wave"></div>
    <div class="footer-content">
        <div class="footer-section">
            <div class="footer-brand">
                <a href="../../index.php"><img src="../images/logo/first-logo.png" alt="RahalLogo" class="footer-logo"></a>
            </div>
            <p class="footer-description">
                منصة رحال - دليلك الشامل لاكتشاف كنوز المملكة العربية السعودية. 
                نقدم لك تجربة سياحية استثنائية تجمع بين الأصالة والحداثة.
            </p>
            <div class="footer-social">
                <a href="#" class="social-link" title="تويتر">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-link" title="انستغرام">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link" title="فيسبوك">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link" title="يوتيوب">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
        
        <div class="footer-section">
            <h3>روابط سريعة</h3>
            <ul class="footer-links">
                <li><a href="../../index.php">الرئيسية</a></li>
                <li><a href="attractions_list.php">معالم المملكة</a></li>
                <li><a href="../../index.php#map-section">الخريطة التفاعلية</a></li>
                <li><a href="../html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                <li><a href="../../index.php#cities-section">أبرز المدن</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>المعالم</h3>
            <ul class="footer-links">
                <li><a href="attractions_list.php?category=تراثية">معالم تراثية</a></li>
                <li><a href="attractions_list.php?category=دينية">معالم دينية</a></li>
                <li><a href="attractions_list.php?category=طبيعية">معالم طبيعية</a></li>
                <li><a href="attractions_list.php?category=ثقافية">معالم ثقافية</a></li>
                <li><a href="attractions_list.php?category=ترفيهية">معالم ترفيهية</a></li>            </ul>
        </div>
        
        <div class="footer-section">
            <h3>اتصل بنا</h3>
            <div class="footer-contact">
                <p><i class="fas fa-envelope"></i> info@rahal.com</p>
                <p><i class="fas fa-phone"></i>  6789 345 12 966+</p>
                <p><i class="fas fa-map-marker-alt"></i> المملكة العربية السعودية</p>
                <p><i class="fas fa-clock"></i> ٢٤/٧ دعم على مدار الساعة</p>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>© 2025 رحال. جميع الحقوق محفوظة. | صمم بـ ❤️ لحب المملكة</p>
    </div>
</footer>
  </section>
  <!--
=======================================================
Chatbot Widget
=======================================================
-->
<script>
(function () {
    if (!window.chatbase || window.chatbase("getState") !== "initialized") {
        window.chatbase = (...args) => {
        if (!window.chatbase.q) {
            window.chatbase.q = [];
        }
        window.chatbase.q.push(args);
        };

        window.chatbase = new Proxy(window.chatbase, {
        get(target, prop) {
            if (prop === "q") {
            return target.q;
            }
            return (...args) => target(prop, ...args);
        }
        });
    }

    const onLoad = function () {
        const script = document.createElement("script");
        script.src = "https://www.chatbase.co/embed.min.js";
        script.id = "BREzytATbRZVEccbXiUID";
        script.domain = "www.chatbase.co";
        document.body.appendChild(script);
    };

    if (document.readyState === "complete") {
        onLoad();
    } else {
        window.addEventListener("load", onLoad);
    }
})();
</script>

    <script src="../js/script.js"></script>
    <script src="../js/search.js"></script>
    </body>
