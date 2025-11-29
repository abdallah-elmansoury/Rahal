<?php
include './assets/php/db_connect.php'; // Database connection

// Bring some landmarks to display on the homepage
$featured_sql = "SELECT 
            t.*,
            r.name AS region_name, 
            c.name AS city_name 
        FROM tourist_attractions t
        LEFT JOIN regions r ON t.region_id = r.id
        LEFT JOIN cities c ON t.city_id = c.id 
        ORDER BY t.id
        LIMIT 6";
$featured_result = $conn->query($featured_sql);
$featured_attractions = [];
if ($featured_result) {
    $featured_attractions = $featured_result->fetch_all(MYSQLI_ASSOC);
}
// City and region data for carousel
$cities_data = [
    'الطائف' => ['region' => 1, 'city' => 'الطائف'], // مكة المكرمة (region 1)
    'العلا' => ['region' => 3, 'city' => 'العلا'], // المدينة المنورة (region 3)
    'المدينة المنورة' => ['region' => 3, 'city' => 'المدينة المنورة'], // المدينة المنورة (region 3)
    'عسير' => ['region' => 13, 'city' => 'أبها'], // عسير (region 13)
    'مكة المكرمة' => ['region' => 1, 'city' => 'مكة المكرمة'], // مكة المكرمة (region 1)
    'جدة' => ['region' => 1, 'city' => 'جدة'], // مكة المكرمة (region 1)
    'الرياض' => ['region' => 2, 'city' => 'الرياض'], // الرياض (region 2)
    'الدمام' => ['region' => 4, 'city' => 'الدمام'] // الشرقية (region 4)
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rahal</title> <!-- Page Title -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"><!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./assets/css/style.css"> <!-- Main CSS -->
  <link rel="stylesheet" href="./assets/css/article.css"> <!-- Article CSS -->
  <link rel="icon" href="./assets/images/Favicon/favicon-32x32.png" type="image/x-icon" /> <!-- Favicon -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> <!-- Swiper CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome CSS -->
</head>

<body>
  <!--
=======================================================
Nav bar
=======================================================
-->
<section dir="ltr">
    <div class="navbar" id="navbar">
        <!-- Search bar -->
        <form method="GET" action="assets/php/search_results.php" class="search-form">
            <input dir="rtl"class="search-bar" type="text" name="query" placeholder="ابحث عن معلم سياحي...">
        </form>

        <!-- Navigation links and logo -->
        <div class="navbar-right-group">
            <!-- Navigation links -->
            <ul class="nav-links" dir="rtl">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="./assets/php/attractions_list.php">معالم المملكة</a></li>
                <li><a href="./assets/html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                <li><a href="index.php#map-section">الخريطة</a></li>
            </ul>
            <!--Logo-->
            <a href="index.php"><img src="./assets/images/logo/first-logo.png" alt="RahalLogo" id="logo"></a>
        </div>
    </div>
</section>


  <!--
=======================================================
Background Video
=======================================================
-->
  <section class="video-section">
    <video autoplay loop muted>
      <source src="./assets/images/Video/intro-background.mp4" type="video/mp4">
    </video>
  </section>


  <!--
=======================================================
Name and Slogan
=======================================================
-->
  <section>
    <div class="overlay-text" id="name"></div>
    <div class="slogan" id="slogan"></div>
  </section>


<!--
=======================================================
Important Cities Carousel
=======================================================
-->
<section dir="ltr" id="cities-section" style="padding: 50px 0;">
  <div class="header first-header">
    <h3>أبرز المدن</h3> <!-- Section Title -->
  </div>

  <!-- Swiper Carousel -->
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=1&city=<?= urlencode('الطائف') ?>">
          <img src="./assets/images/Photos/Al-Taif.png" alt="الطائف"ئئ>
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=3&city=<?= urlencode('العلا') ?>">
          <img src="./assets/images/Photos/Al-ula.png" alt="العلا">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=3&city=<?= urlencode('المدينة المنورة') ?>">
          <img src="./assets/images/Photos/El-Maddena.png" alt="المدينة المنورة">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=13&city=<?= urlencode('أبها') ?>">
          <img src="./assets/images/Photos/Asser.png" alt="عسير">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=1&city=<?= urlencode('مكة المكرمة') ?>">
          <img src="./assets/images/Photos/Makkah.png" alt="مكة المكرمة">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=1&city=<?= urlencode('جدة') ?>">
          <img src="./assets/images/Photos/Jeddah.png" alt="جدة">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=2&city=<?= urlencode('الرياض') ?>">
          <img src="./assets/images/Photos/Riyad.png" alt="الرياض">
        </a>
      </div>
      <div class="swiper-slide">
        <a href="./assets/php/attractions_list.php?region=4&city=<?= urlencode('الدمام') ?>">
          <img src="./assets/images/Photos/Al-Dammam.png" alt="الدمام">
        </a>
      </div>
    </div>
    <!-- Swiper buttons -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>
</section>

<!--
=======================================================
Saudi Landmarks
=======================================================
-->
<section style="padding: 50px 20px; background: #f8f9fa;">
    <div class="header second-header">
      <h3>أكتشف الممكلة</h3> ,<!-- Section Title -->
    </div>

    <!-- Attractions Grid Container -->
    <div class="attractions-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <?php if (!empty($featured_attractions)): ?>
            <?php foreach ($featured_attractions as $attraction): ?>
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
                        
                        <!-- Attraction Name -->
                        <h4 style="color: #142836; margin-bottom: 10px; font-family: 'Tajawal', sans-serif; font-size: 22px; text-align: right;">
                            <?= htmlspecialchars($attraction['name']) ?>
                        </h4>
                        
                        <!-- Content wrapper for flexible growth -->
                        <div style="flex: 1; display: flex; flex-direction: column;">
                            
                            <!-- Attraction Description -->
                            <p style="color: #666; line-height: 1.6; margin-bottom: 15px; font-family: 'Tajawal', sans-serif; text-align: right; font-size: 16px; flex: 1;">
                                <?= nl2br(htmlspecialchars(substr($attraction['description'] ?? 'لا يوجد وصف', 0, 120))) ?>...
                            </p>
                            
                            <!-- Location Information -->
                            <p><strong>المنطقة:</strong> <?= htmlspecialchars($attraction['region_name'] ?? 'غير محدد') ?></p>
                            <p><strong>المدينة:</strong> <?= htmlspecialchars($attraction['city_name'] ?? 'غير محدد') ?></p>
                            
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
                                <a id="Details-Button" href="./assets/php/html_wrapper.php?file=<?= htmlspecialchars($attraction['html_file']) ?>" 
                                  style="display: block; text-align: center; background: linear-gradient(135deg, #142836 0%, #1e3a52 100%); color: white; padding: 12px; text-decoration: none; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-weight: 500; transition: background 0.3s ease; font-size: 16px; min-height: 50px; display: flex; align-items: center; justify-content: center;">
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
        <?php else: ?>
            <!-- No Attractions Message -->
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <p style="font-size: 18px; color: #666; font-family: 'Tajawal', sans-serif;">
                    لا توجد معالم سياحية متاحة حالياً.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- View All Attractions Button -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="./assets/php/attractions_list.php" class="custom-btn">
            عرض جميع المعالم
        </a>
    </div>
</section>


<!--
=======================================================
Interactive Map Section
=======================================================
-->
  <section class="map" id="map-section" dir="ltr">
    <div class="header third-header">
      <h3>سافر معنا</h3> <!-- Section Title -->
    </div>
    <div class="map-container">
      <!-- Retrieve the map from map0 file -->
      <div class="map-wrapper">
        <iframe src="./assets/html/map0.html" width="100%" height="670" id="map"></iframe>
      </div>

      <!-- Information panel -->
      <div class="info-panel" id="infoPanel">
        <div class="welcome-message">
          <h2>مرحباً بكم في المملكة العربية السعودية</h2>
          <p>اكتشف جمال وتراث مناطق المملكة</p>
          <p>مرر مؤشر الماوس فوق أي منطقة على الخريطة لمعرفة المزيد عنها</p>

          <!-- Saudi logo -->
          <div class="logo-container">
            <img src="./assets/images/Photos/white-saudi-logo.png" alt="Saudi logo" class="welcome-logo">
          </div>
        </div>

        <!--Area information will be added dynamically-->
        <div class="region-info" id="regionInfo"></div>
      </div>
    </div>
  </section>

<!--
=======================================================
Comments Section
=======================================================
-->
<section class="comments-section" dir="rtl">
    <div class="container">
        <!-- Section Title -->
        <h3 class="section-title">آراء زوارنا</h3>
        
        <!-- Comments Carousel Container -->
        <div class="comments-carousel-container">
            <div class="comments-carousel" id="commentsCarousel">
                <!-- Comments will be loaded here by JavaScript -->
            </div>
            
            <!-- Carousel Navigation Buttons -->
            <button class="carousel-btn prev-btn" id="prevComment">❮</button>
            <button class="carousel-btn next-btn" id="nextComment">❯</button>
        </div>
        
        <!-- Add Comment Form Section -->
        <div class="add-comment-form">
            <!-- Form Title -->
            <h3 style="text-align: center; margin-bottom: 30px; font-family: 'Tajawal', sans-serif; color: #142836;">
                أضف تعليقك
            </h3>
            
            <!-- Comment Submission Form -->
            <form id="commentForm" method="POST">
                <!-- Name Input Field -->
                <div class="form-group">
                    <input type="text" id="commentName" name="name" 
                          placeholder="اسمك" required 
                          style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 16px; margin-bottom: 15px;">
                </div>
                
                <!-- Comment Text Input Field -->
                <div class="form-group">
                    <textarea id="commentText" name="text" 
                              placeholder="تعليقك..." required 
                              rows="4"
                              style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 16px; margin-bottom: 15px; resize: vertical;"></textarea>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="custom-btn" 
                        style="display: block; margin: 0 auto; padding: 12px 40px;">
                    إرسال التعليق
                </button>
                
                <!-- Message Display Area for Form Status -->
                <div id="commentMessage" style="text-align: center; margin-top: 15px; font-family: 'Tajawal', sans-serif;"></div>
            </form>
        </div>
    </div>
</section>

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
                <a href="index.php"><img src="./assets/images/logo/first-logo.png" alt="RahalLogo" class="footer-logo"></a>
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
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="./assets/php/attractions_list.php">معالم المملكة</a></li>
                <li><a href="./index.php#map-section">الخريطة التفاعلية</a></li>
                <li><a href="./assets/html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                <li><a href="index.php#cities-section">أبرز المدن</a></li>
            </ul>
        </div>
        
        <!-- Attractions Categories Section -->
        <div class="footer-section">
            <h3>المعالم</h3>
            <ul class="footer-links">
                <li><a href="./assets/php/attractions_list.php?category=تراثية">معالم تراثية</a></li>
                <li><a href="./assets/php/attractions_list.php?category=دينية">معالم دينية</a></li>
                <li><a href="./assets/php/attractions_list.php?category=طبيعية">معالم طبيعية</a></li>
                <li><a href="./assets/php/attractions_list.php?category=ثقافية">معالم ثقافية</a></li>
                <li><a href="./assets/php/attractions_list.php?category=ترفيهية">معالم ترفيهية</a></li>
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

<!-- JS linking -->
<script src="./assets/js/script.js"></script>
<script src="./assets/js/map-interaction.js"></script>
<script src="./assets/js/search.js"></script>
<script src="./assets/js/location.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  // Initialize Swiper carousel with custom configuration
  var swiper = new Swiper(".mySwiper", {
    // Number of slides visible at once (1.5 shows part of adjacent slide)
    slidesPerView: 1.5,
    
    // Space between slides in pixels
    spaceBetween: 30,
    
    // Enable infinite loop
    loop: true,
    
    // Center the active slide
    centeredSlides: true,
    
    // Auto-play configuration
    autoplay: {
      delay: 3000, // 3 seconds between slides
      disableOnInteraction: false // Continue autoplay after user interaction
    },
    
    // Pagination dots configuration
    pagination: {
      el: ".swiper-pagination", // Pagination container element
      clickable: true, // Allow clicking on dots to navigate
    },
    
    // Navigation arrows configuration
    navigation: {
      nextEl: ".swiper-button-next", // Next button element
      prevEl: ".swiper-button-prev", // Previous button element
    },
    
    // Responsive breakpoints for different screen sizes
    breakpoints: {
      // Small screens (mobile)
      640: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      // Medium screens (tablets)
      768: {
        slidesPerView: 1.3,
        spaceBetween: 25,
      },
      // Large screens (desktops)
      1024: {
        slidesPerView: 1.5,
        spaceBetween: 30,
      },
      // Extra large screens
      1400: {
        slidesPerView: 1.7,
        spaceBetween: 40,
      }
    }
  });
</script>
</body>
</html>