<?php
include __DIR__ . '/db_connect.php'; // Database connection

// Get search query from URL parameters
$search_query = $_GET['query'] ?? '';

// Initialize empty results array
$search_results = [];

if (!empty($search_query)) {
    // Sanitize search query to prevent SQL injection
    $safe_search_query = $conn->real_escape_string($search_query);
    
    // SQL query to search attractions with region and city joins
$sql = "SELECT 
            t.*,
            r.name AS region_name, 
            c.name AS city_name 
        FROM tourist_attractions t
        LEFT JOIN regions r ON t.region_id = r.id
        LEFT JOIN cities c ON t.city_id = c.id
        WHERE (t.name LIKE '%$safe_search_query%' 
           OR t.description LIKE '%$safe_search_query%'
           OR t.category LIKE '%$safe_search_query%'
           OR c.name LIKE '%$safe_search_query%')
           AND c.name LIKE '%$safe_search_query%'  -- Additional city matching condition
        ORDER BY t.name";
    
    // Execute query and get results
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج البحث - رحال</title>
    <!-- External CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../images/Favicon/favicon-32x32.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Font for Arabic support -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Main container styling */
        .search-results-container {
            margin-top: 120px;
            padding: 20px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        /* Search header section */
        .search-header {
            text-align: center;
            margin-bottom: 40px;
            background: linear-gradient(135deg, #142836 0%, #1e3a52 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
        }
        /* Results count text */
        .results-count {
            font-size: 18px;
            margin-top: 15px;
            opacity: 0.9;
        }
        /* Grid layout for attraction cards */
        .attractions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            padding: 30px 0;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        /* No results message styling */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        /* Page header with gradient background */
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

        /* Animated background effect */
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

        /* Animated bottom border */
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

        /* Header content container */
        .header-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Main page title */
        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 4px 12px rgba(0,0,0,0.3);
            animation: fadeInUp 0.8s ease;
        }

        /* Page subtitle */
        .page-header .subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        /* Statistics container */
        .header-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        /* Individual stat item */
        .stat-item {
            text-align: center;
        }

        /* Stat number styling */
        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            color: #be9d6a;
            margin-bottom: 5px;
        }

        /* Stat label styling */
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Back button styling */
        .back-button-search {
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

        /* Back button hover effect */
        .back-button-search:hover {
            background: rgba(190, 157, 106, 0.3);
            border-color: #be9d6a;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(190, 157, 106, 0.3);
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Shimmer animation for border */
        @keyframes shimmer {
            0%, 100% { background-position: -200% 0; }
            50% { background-position: 200% 0; }
        }

        /* Fade in up animation */
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
    </style>
</head>
<body>

    <!-- Navigation Section -->
    <section dir="ltr" >
        <div class="navbar" id="navbar" style="background: #f2f2f2;">
            <!-- Search form -->
            <form method="GET" action="search_results.php" class="search-form">
                <input dir="rtl" class="search-bar" type="text" name="query" placeholder="ابحث عن معلم سياحي...">
            </form>

            <!-- Right side navigation group -->
            <div dir="rtl" class="navbar-right-group" style="display: flex; flex-direction: row-reverse;">
                <!-- Navigation links -->
                <ul class="nav-links">
                    <li><a href="../../index.php">الرئيسية</a></li>
                    <li><a href="./attractions_list.php">معالم المملكة</a></li>
                    <li><a href="../html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                    <li><a href="../../index.php#map-section">الخريطة</a></li>
                </ul>
                
                <!-- Logo -->
                <a href="../../index.php"><img src="../images/logo/second-logo.png" alt="RahalLogo" id="logo"></a>
            </div>
        </div>
    </section>

<!-- Page Header Section -->
<div class="page-header" dir="rtl">
    <div class="header-content">
        <h1>نتائج البحث</h1>
        <p class="subtitle">اكتشف المعالم السياحية التي تبحث عنها في المملكة العربية السعودية</p>
        
        <!-- Search Statistics -->
        <div class="header-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($search_results) ?></span>
                <span class="stat-label">نتيجة بحث</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= !empty($search_query) ? '"' . htmlspecialchars($search_query) . '"' : '---' ?></span>
                <span class="stat-label">كلمة البحث</span>
            </div>
        </div>
        
        <!-- Back to Home Button -->
        <a href="../../index.php" class="back-button-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            العودة للرئيسية
        </a>
    </div>
</div>

<!-- Search Results Grid -->
<div class="attractions-grid" dir="rtl">
    <?php if (!empty($search_results)): ?>
        <?php foreach ($search_results as $attraction): ?>
            <!-- Individual Attraction Card -->
            <div class="attraction-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: transform 0.3s ease; border: 1px solid #eee; text-align: right; direction: rtl; display: flex; flex-direction: column; height: 100%;">
                <!-- Attraction Image -->
                <div style="height: 250px; overflow: hidden; flex-shrink: 0;">
                    <img src="<?= htmlspecialchars($attraction['image_url'] ?? '/images/placeholder.jpg') ?>" 
                        alt="<?= htmlspecialchars($attraction['name']) ?>" 
                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                
                <!-- Card Content -->
                <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                    <h4 style="color: #142836; margin-bottom: 10px; font-family: 'Tajawal', sans-serif; font-size: 22px; text-align: right;">
                        <?= htmlspecialchars($attraction['name']) ?>
                    </h4>
                    
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <!-- Truncated Description -->
                        <p style="color: #666; line-height: 1.6; margin-bottom: 15px; font-family: 'Tajawal', sans-serif; text-align: right; font-size: 16px; flex: 1;">
                            <?= nl2br(htmlspecialchars(substr($attraction['description'] ?? 'لا يوجد وصف', 0, 120))) ?>...
                        </p>
                        
                        <!-- Location Information -->
                        <div style="margin-bottom: 15px;">
                            <p style="margin-bottom: 5px;"><strong>المنطقة:</strong> <?= htmlspecialchars($attraction['region_name'] ?? 'غير محدد') ?></p>
                            <p style="margin-bottom: 5px;"><strong>المدينة:</strong> <?= htmlspecialchars($attraction['city_name'] ?? 'غير محدد') ?></p>
                        </div>
                        
                        <!-- Category Badge -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="background: #be9d6a; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px; font-family: 'Tajawal', sans-serif; font-weight: 500;">
                                <?= htmlspecialchars($attraction['category'] ?? 'غير مصنف') ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Details Button -->
                    <div style="margin-top: auto;">
                        <?php if (!empty($attraction['html_file'])): ?>
                            <a href="./html_wrapper.php?file=<?= htmlspecialchars($attraction['html_file']) ?>" 
                                style="display: block; text-align: center; background: linear-gradient(135deg, #142836 0%, #1e3a52 100%); color: #f2f2f2; padding: 12px; text-decoration: none; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-weight: 500; transition: background 0.3s ease; font-size: 18px; border: 2px solid transparent; min-height: 50px; display: flex; align-items: center; justify-content: center;">
                                اقرأ أكثر / تفاصيل
                            </a>
                        <?php else: ?>
                            <button style="width: 100%; background: #ccc; color: #666; padding: 12px; border: none; border-radius: 8px; font-family: 'Tajawal', sans-serif; cursor: not-allowed; font-size: 16px; min-height: 50px;">
                                التفاصيل غير متاحة
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif (!empty($search_query)): ?>
        <!-- No Results Message (existing code) -->
    <?php endif; ?>
</div>


<!--
=======================================================
Footer
=======================================================
-->
<section>
<!-- Footer Section -->
<footer class="article-footer">
    <!-- Repeating Pattern Strip -->
    <div class="footer-top-pattern"></div>
    
    <!-- Main Footer Content Container -->
    <div class="footer-content">
        
        <!-- Rahal Information Section -->
        <div class="footer-section">
            <div class="footer-brand">
                <!-- Logo -->
                    <a href="../../index.php"><img src="../images/logo/first-logo.png" alt="RahalLogo" class="footer-logo"></a>
                </div>
            <!-- Rahal Description -->
            <p class="footer-description">
                منصة رحال - دليلك الشامل لاكتشاف كنوز المملكة العربية السعودية. 
                نقدم لك تجربة سياحية استثنائية تجمع بين الأصالة والحداثة.
            </p>
            <!-- Social Media Links -->
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
        
        <!-- Quick Links Navigation Section -->
            <div class="footer-section">
                <h3>روابط سريعة</h3>
                <ul class="footer-links">
                    <li><a href="../../index.php">الرئيسية</a></li>
                    <li><a href="./attractions_list.php">معالم المملكة</a></li>
                    <li><a href="../../index.php#map-section">الخريطة التفاعلية</a></li>
                    <li><a href="../html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                    <li><a href="../../index.php#cities-section">أبرز المدن</a></li>
                </ul>
            </div>
            
            <!-- Attractions Categories Section -->
            <div class="footer-section">
                <h3>المعالم</h3>
                <ul class="footer-links">
                    <li><a href="./attractions_list.php?category=تراثية">معالم تراثية</a></li>
                    <li><a href="./attractions_list.php?category=دينية">معالم دينية</a></li>
                    <li><a href="./attractions_list.php?category=طبيعية">معالم طبيعية</a></li>
                    <li><a href="./attractions_list.php?category=ثقافية">معالم ثقافية</a></li>
                    <li><a href="./attractions_list.php?category=ترفيهية">معالم ترفيهية</a></li>
                </ul>
            </div>
            
            <!-- Contact Information Section -->
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
        
        <!-- Footer Copyright Section -->
        <div class="footer-bottom">
            <p>© 2025 رحال. جميع الحقوق محفوظة. | صمم بـ ❤️ لحب المملكة</p>
        </div>
    </footer>
  <section>
    <script>
  (function() {
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

    const onLoad = function() {
      const script = document.createElement("script");
      script.src = "https://www.chatbase.co/embed.min.js";
      script.id = "0vWDkJ2sdzkjCXpTyA4Dj"; // خليها زي ما هي في حسابك
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
  </section>
</body>
</html>
