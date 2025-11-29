<?php
// html_wrapper.php - The Amazing Version (Original comment in Arabic)

// Include the database connection file. This is crucial for connecting to the MySQL database.
include __DIR__ . '/db_connect.php';

// --- Input Validation and Database Query ---

// Receive the target HTML file name from the GET request, defaulting to an empty string if not set.
$target_html_file = $_GET['file'] ?? '';

// Basic validation: Check if the file name is empty or does not contain '.html'.
if (empty($target_html_file) || strpos($target_html_file, '.html') === false) {
    die("ملف غير صالح."); // Invalid file message (in Arabic)
}

// Prepare the file name for safe use in the SQL query by escaping special characters.
$safe_file_name = $conn->real_escape_string($target_html_file);

// SQL query to fetch tourist attraction data, including region and city names via LEFT JOINs,
// based on the HTML file name.
$sql = "SELECT t.*, r.name as region_name, c.name as city_name 
        FROM tourist_attractions t
        LEFT JOIN regions r ON t.region_id = r.id
        LEFT JOIN cities c ON t.city_id = c.id
        WHERE t.html_file = '{$safe_file_name}'";

$result = $conn->query($sql);
// Fetch the result as an associative array.
$attraction = $result->fetch_assoc();

// Check if an attraction was found in the database.
if (!$attraction) {
    die("المعلم غير موجود."); // Attraction not found message (in Arabic)
}

// Assuming get_attraction_location is defined elsewhere (e.g., in db_connect.php)
// Get the location data for the attraction.
$location_data = get_attraction_location($conn, $attraction['id']);

// --- Content Loading and Cleaning ---

// Start output buffering (OB). This captures all subsequent output instead of sending it directly to the browser.
ob_start();

// Define the full path to the article HTML file.
$file_path = __DIR__ . '/../articles/' . $target_html_file;

// Check if the actual detail page file exists on the server.
if (!file_exists($file_path)) {
    die("صفحة التفاصيل غير موجودة على الخادم."); // Detail page not found message (in Arabic)
}

// Include (load and execute) the content HTML file. Its output is captured by ob_start().
include $file_path;

// Get the buffered content and stop buffering. The included file's HTML is now in $content.
$content = ob_get_clean();

// Add this code to clean the content from embedded styles (Original comment in Arabic)

// Use regex to remove <style> blocks (inline styles within the content file).
$content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
// Use regex to remove <link rel="stylesheet"> tags (external stylesheets within the content file).
$content = preg_replace('/<link[^>]*rel=["\']stylesheet["\'][^>]*>/i', '', $content);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($attraction['name']) ?> - رحال</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/article.css">
    <link rel="icon" href="../images/Favicon/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="article-page">
    <section dir="ltr">
        <div class="navbar" id="navbar" style="background-color: white;" >
            <form method="GET" action="./search_results.php" class="search-form">
                <input dir="rtl" class="search-bar" type="text" name="query" placeholder="ابحث عن معلم سياحي...">
            </form>

            <div class="navbar-right-group">
                <ul class="nav-links" dir="rtl">
                    <li><a href="../../index.php">الرئيسية</a></li>
                    <li><a href="./attractions_list.php">معالم المملكة</a></li>
                    <li><a href="../html/Qibla.html">القبلة ومواعيد الصلاة</a></li>
                    <li><a href="../../index.php#map-section">الخريطة</a></li>
                </ul>
                
                <a href="../../index.php"><img src="../images/logo/second-logo.png" alt="RahalLogo" id="logo"></a>
            </div>
        </div>
    </section>

    <header class="article-hero">
        <div class="hero-pattern"></div>
        <div class="hero-content">
            <h1 class="article-title fade-in"><?= htmlspecialchars($attraction['name']) ?></h1>
            
            <?php if (!empty($attraction['description'])): ?>
                <p class="article-subtitle fade-in"><?= htmlspecialchars($attraction['description']) ?></p>
            <?php endif; ?>
            
            <div class="article-meta-grid fade-in">
                <?php if ($attraction['category']): ?>
                    <div class="meta-card">
                        <i class="fas fa-tag meta-icon"></i>
                        <div class="meta-label">التصنيف</div>
                        <div class="meta-value"><?= htmlspecialchars($attraction['category']) ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($attraction['region_name']): ?>
                    <div class="meta-card">
                        <i class="fas fa-map-marker-alt meta-icon"></i>
                        <div class="meta-label">المنطقة</div>
                        <div class="meta-value"><?= htmlspecialchars($attraction['region_name']) ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($attraction['city_name']): ?>
                    <div class="meta-card">
                        <i class="fas fa-city meta-icon"></i>
                        <div class="meta-label">المدينة</div>
                        <div class="meta-value"><?= htmlspecialchars($attraction['city_name']) ?></div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </header>

    <main class="article-main">
        <div class="article-container">
            <article class="article-card">
                <?php if (!empty($attraction['image_url'])): ?>
                    <div class="article-featured-image">
                        <img src="<?= htmlspecialchars($attraction['image_url']) ?>" 
                             alt="<?= htmlspecialchars($attraction['name']) ?>">
                        <div class="image-overlay">
                            <h2>اكتشف روعة <?= htmlspecialchars($attraction['name']) ?></h2>
                            <p>رحلة استثنائية إلى قلب التراث السعودي الأصيل</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="article-body">
                    <div class="article-content">
                        <?= $content ?>
                    </div>

                    <div class="article-navigation">
                        <a href="attractions_list.php" class="nav-btn back">
                            <i class="fas fa-arrow-right"></i>
                            العودة إلى قائمة المعالم
                        </a>
                        
                        <div class="article-share">
                            <div class="share-text">شارك هذا المقال</div>
                            <div class="share-buttons">
                                <a href="#" class="share-btn" title="Share on Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="share-btn" title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="share-btn" title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="share-btn" title="Copy link">
                                    <i class="fas fa-link"></i>
                                </a>
                            </div>
                        </div>
                        
                        <a href="../../index.php" class="nav-btn home">
                            <i class="fas fa-home"></i>
                            الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </article>

            <?php if ($location_data && !empty($location_data['location'])): ?>
            <section class="article-location">
                <div class="location-header">
                    <div class="location-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h2 class="location-title">موقع المعلم</h2>
                </div>
                
                <div class="location-content">
                    <div class="location-info">
                        <div class="location-address">
                            <i class="fas fa-map-pin" style="color: #be9d6a;"></i>
                            <?= htmlspecialchars($location_data['location']) ?>
                        </div>
                        
                        <div class="location-actions">
                            <a href="<?= htmlspecialchars($location_data['location']) ?>" target="_blank" class="location-btn" id="getDirections">
                                <i class="fas fa-directions"></i>
                                احصل على الاتجاهات
                            </a>
                            <button class="location-btn secondary" id="shareLocation" data-location="<?= htmlspecialchars($location_data['location']) ?>">
                                <i class="fas fa-share-alt"></i>
                                مشاركة الموقع
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
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
                    <a href="#" class="social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" title="Youtube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
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


    <section>
    <script>
  (function() {
    // Check if chatbase is initialized, if not, set up the stub function and proxy.
    if (!window.chatbase || window.chatbase("getState") !== "initialized") {
      window.chatbase = (...args) => {
        if (!window.chatbase.q) {
          window.chatbase.q = []; // Initialize command queue
        }
        window.chatbase.q.push(args); // Push commands to the queue
      };

      // Create a proxy to allow calling methods like window.chatbase.method() 
      // which will be pushed to the queue.
      window.chatbase = new Proxy(window.chatbase, {
        get(target, prop) {
          if (prop === "q") {
            return target.q;
          }
          return (...args) => target(prop, ...args);
        }
      });
    }

    // Function to load the external Chatbase script
    const onLoad = function() {
      const script = document.createElement("script");
      script.src = "https://www.chatbase.co/embed.min.js";
      script.id = "BREzytATbRZVEccbXiUID"; // Chatbot ID
      script.domain = "www.chatbase.co";
      document.body.appendChild(script);
    };

    // Load the script either immediately if the page is complete, or on 'load' event.
    if (document.readyState === "complete") {
      onLoad();
    } else {
      window.addEventListener("load", onLoad);
    }
  })();
</script>
  </section>
    <script>
        // JS for fade-in effects on scroll (Intersection Observer API)
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible'); // Add 'visible' class to trigger CSS transition
                }
                // Removed 'unobserve' to allow re-triggering, if needed (though usually kept for single-trigger)
            });
        }, { threshold: 0.1 }); // Trigger when 10% of the element is visible
        
        fadeElements.forEach(el => observer.observe(el)); // Start observing each element

        // JS for Copy Link functionality
        document.querySelector('.fa-link').closest('.share-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const url = window.location.href;
            // Use modern clipboard API
            navigator.clipboard.writeText(url).then(() => {
                alert('تم نسخ الرابط بنجاح!'); // Success message (in Arabic)
            });
        });

        // JS for Share Location functionality (using Web Share API or fallback to clipboard)
        const shareLocationBtn = document.getElementById('shareLocation');

        if (shareLocationBtn) {
            shareLocationBtn.addEventListener('click', function() {
                const locationUrl = this.getAttribute('data-location');
                const title = document.querySelector('.article-title')?.textContent || 'معلم سياحي';
                const url = window.location.href;
                
                const shareText = `موقع ${title}\nرابط الخريطة: ${locationUrl}\nرابط المقال: ${url}`;
                
                // Use the native Web Share API if available
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: `موقع ${title}`,
                        url: locationUrl
                    });
                } else {
                    // Fallback to clipboard if Web Share API is not available
                    navigator.clipboard.writeText(shareText)
                        .then(() => alert('تم نسخ معلومات الموقع إلى الحافظة'))
                        .catch(() => {
                            // Secondary fallback using deprecated document.execCommand('copy')
                            const textArea = document.createElement('textarea');
                            textArea.value = shareText;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                            alert('تم نسخ معلومات الموقع إلى الحافظة');
                        });
                }
            });
        }
    </script>
    <script src="../js/search.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
<?php
// Close the database connection to free up resources.
$conn->close();
?>