// ========== Typing Effect for Main Text ==========
/**
 * Initializes the typewriter effect for the hero section (Name and Slogan).
 * It types the name first, then the slogan.
 */
function initTypingEffect() {
    const nameElement = document.getElementById("name");
    const sloganElement = document.getElementById("slogan");
    
    // Exit if elements don't exist on the current page
    if (!nameElement || !sloganElement) return;
    
    const nameText = "رَحّــال"; // "Rahal"
    const sloganText = "اكتشف جمال السعودية"; // "Discover the beauty of Saudi Arabia"

    let nameIndex = 0;
    let sloganIndex = 0;

    // Recursive function to type the Name character by character
    function typeName() {
        if (nameIndex < nameText.length) {
            nameElement.textContent += nameText[nameIndex];
            nameIndex++;
            setTimeout(typeName, 150); // Delay between chars
        } else {
            // Once name is finished, wait 400ms then start typing slogan
            setTimeout(typeSlogan, 400);
        }
    }

    // Recursive function to type the Slogan
    function typeSlogan() {
        if (sloganIndex < sloganText.length) {
            sloganElement.textContent += sloganText[sloganIndex];
            sloganIndex++;
            setTimeout(typeSlogan, 120); // Slightly faster typing for slogan
        }
    }
    
    // Start the process
    typeName();
}

// ========== Navbar Scroll Effect ==========
/**
 * Changes the navbar style (transparency/color) and logo when scrolling down.
 */
function initNavbarScrollEffect() {
    
    const nav = document.getElementById("navbar");
    const logo = document.getElementById("logo");
    
    if (!nav || !logo) return;
    
    window.addEventListener("scroll", function () {
        // Check if we are on specific pages where the scroll effect should be disabled
        // (attractions list or html wrapper pages usually have fixed headers)
        if (window.location.pathname.includes('attractions_list.php') ||
            window.location.pathname.includes('html_wrapper.php')) {
            return; // Do nothing
        }
        
        // Main scroll logic
        if (window.scrollY > 1) {
            // User has scrolled down: Add white background class and switch to colored logo
            nav.classList.add("navbar-scrolled");
            logo.src = "./assets/images/logo/second-logo.png";
        } else {
            // User is at the top: Remove background class and switch to white/original logo
            nav.classList.remove("navbar-scrolled");
            logo.src = "./assets/images/logo/first-logo.png";
        }
    });
}

// ========== Image Carousel (Infinite Scroll) ==========
/**
 * Handles the logic for the infinite scrolling image slider.
 * Includes auto-scroll, drag-to-scroll, and infinite loop calculations.
 */
function initCarousel() {
    const carousel = document.getElementById('carousel');
    if (!carousel) return;

    let isDragging = false;
    let startX = 0;
    let startScrollLeft = 0;
    
    // Speed configurations
    const originalSpeed = 1.2; // Normal auto-scroll speed
    const slowSpeed = 0.5;   // Slower speed when hovering
    let autoSpeed = originalSpeed;

    // Helper to calculate half width for the infinite loop logic
    const halfWidth = () => carousel.scrollWidth / 2;

    // Animation loop for auto-scrolling
    function tick() {
        if (!isDragging) {
            carousel.scrollLeft += autoSpeed;
        }
        // Logic for infinite loop effect:
        // If scrolled past the halfway point (duplicated content), reset to 0
        if (carousel.scrollLeft >= halfWidth()) {
            carousel.scrollLeft -= halfWidth();
        }
        // If scrolled backwards past 0, reset to the halfway point
        if (carousel.scrollLeft <= 0) {
            carousel.scrollLeft += halfWidth();
        }
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);

    // --- Drag Functionality ---
    
    carousel.addEventListener('pointerdown', (e) => {
        isDragging = true;
        carousel.setPointerCapture(e.pointerId); // Capture pointer events
        startX = e.clientX;
        startScrollLeft = carousel.scrollLeft;
        carousel.style.cursor = 'grabbing'; // Visual cue
        
        // Prevent default behavior to stop text selection/native scrolling
        e.preventDefault();
    });

    carousel.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        carousel.scrollLeft = startScrollLeft - dx; // Move carousel based on drag distance
        
        e.preventDefault();
    });

    function endDrag() {
        if (!isDragging) return;
        isDragging = false;
        carousel.style.cursor = 'grab'; // Reset cursor
    }
    carousel.addEventListener('pointerup', endDrag);
    carousel.addEventListener('pointercancel', endDrag);

    // --- Speed Control on Hover ---
    
    carousel.addEventListener('mouseenter', () => {
        autoSpeed = slowSpeed; // Slow down when user hovers
    });
    
    carousel.addEventListener('mouseleave', () => {
        autoSpeed = originalSpeed; // Resume normal speed
    });

    // Handle window resize to ensure infinite loop math stays correct
    window.addEventListener('resize', () => {
        const hw = halfWidth();
        if (carousel.scrollLeft > hw) carousel.scrollLeft = carousel.scrollLeft % hw;
    });
}

// ========== Server Status Check ==========
/**
 * Simple health check to see if the backend API is reachable.
 */
function checkServerStatus() {
    fetch('/api/health')
        .then(response => response.json())
        .then(data => {
            console.log('Server status:', data);
        })
        .catch(error => {
            console.log('Server is not running');
        });
}

// ========== SIMPLE WORKING COMMENTS CAROUSEL ==========
/**
 * Class to manage the Testimonials/Comments section.
 * Handles fetching, rendering, navigating, and submitting comments.
 */
class SimpleCommentsCarousel {
    constructor() {
        // Select DOM elements
        this.carousel = document.getElementById('commentsCarousel');
        this.prevBtn = document.getElementById('prevComment');
        this.nextBtn = document.getElementById('nextComment');
        this.commentForm = document.getElementById('commentForm');
        this.commentMessage = document.getElementById('commentMessage');
        
        // State variables
        this.comments = [];
        this.currentIndex = 0;
        this.interval = null; // For auto-rotation timer
        
        if (this.carousel) {
            console.log('Comments carousel found, initializing...');
            this.init();
        } else {
            console.log('Comments carousel not found!');
        }
    }
    
    // Main initialization method
    async init() {
        console.log('Loading comments...');
        await this.loadComments();
        this.setupEventListeners();
        this.startAutoRotation();
    }
    
    // Fetch comments from the PHP backend
    async loadComments() {
        try {
            console.log('Fetching comments from get_comments.php...');
            this.showLoading();
            
            // Fetch request
            const response = await fetch('./assets/php/get_comments.php');
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Received comments data:', data);
            
            // Ensure data is an array
            this.comments = Array.isArray(data) ? data : [];
            console.log('Processed comments:', this.comments);
            
            if (this.comments.length > 0) {
                this.renderComments();
            } else {
                this.showNoComments();
            }
            
        } catch (error) {
            console.error('Error loading comments:', error);
            this.showError('حدث خطأ في تحميل التعليقات: ' + error.message);
        }
    }
    
    // Render loading state
    showLoading() {
        this.carousel.innerHTML = `
            <div class="comment-card active">
                <div class="loading-comments">
                    جاري تحميل التعليقات...
                </div>
            </div>
        `;
    }
    
    // Render empty state
    showNoComments() {
        this.carousel.innerHTML = `
            <div class="comment-card active">
                <div class="no-comments">
                    لا توجد تعليقات حتى الآن. كن أول من يعلق!
                </div>
            </div>
        `;
        this.updateNavigation();
    }
    
    // Render error state
    showError(message) {
        this.carousel.innerHTML = `
            <div class="comment-card active">
                <div class="no-comments" style="color: #dc3545;">
                    ${message}
                </div>
            </div>
        `;
    }
    
    // Render the list of comments into the DOM
    renderComments() {
        console.log('Rendering', this.comments.length, 'comments');
        
        this.carousel.innerHTML = '';
        
        // Create all comment cards
        this.comments.forEach((comment, index) => {
            const card = document.createElement('div');
            // Make the first comment active initially
            card.className = `comment-card ${index === 0 ? 'active' : ''}`;
            card.innerHTML = this.createCommentHTML(comment);
            this.carousel.appendChild(card);
        });
        
        // Add progress indicators (dots) if we have multiple comments
        if (this.comments.length > 1) {
            this.addProgressIndicators();
        }
        
        this.updateNavigation();
        console.log('Comments rendered successfully');
    }
    
    // Helper to generate HTML string for a single comment
    createCommentHTML(comment) {
        const userInitial = comment.name ? comment.name.charAt(0).toUpperCase() : '?';
        // Format date to Arabic locale
        const date = new Date(comment.date).toLocaleDateString('ar-EG', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Use escapeHtml to prevent XSS attacks from user input
        return `
            <div class="comment-header">
                <div class="user-avatar">${userInitial}</div>
                <div class="user-info">
                    <div class="user-name">${this.escapeHtml(comment.name)}</div>
                    <div class="comment-date">${date}</div>
                </div>
            </div>
            <div class="comment-text">${this.escapeHtml(comment.text).replace(/\n/g, '<br>')}</div>
        `;
    }
    
    // Create clickable dots at the bottom
    addProgressIndicators() {
        const progress = document.createElement('div');
        progress.className = 'carousel-progress';
        
        this.comments.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `progress-dot ${index === this.currentIndex ? 'active' : ''}`;
            dot.onclick = () => this.goToComment(index); // Click to jump to comment
            progress.appendChild(dot);
        });
        
        this.carousel.appendChild(progress);
    }
    
    // Logic to switch to a specific comment index
    goToComment(index) {
        if (index === this.currentIndex || index < 0 || index >= this.comments.length) return;
        
        // Hide current comment
        const currentCard = this.carousel.querySelector('.comment-card.active');
        if (currentCard) {
            currentCard.classList.remove('active');
        }
        
        // Show new comment
        const cards = this.carousel.querySelectorAll('.comment-card');
        if (cards[index]) {
            cards[index].classList.add('active');
        }
        
        this.currentIndex = index;
        this.updateProgressIndicators();
        this.updateNavigation();
        this.resetAutoRotation(); // Reset timer so it doesn't switch immediately after user click
    }
    
    // Switch to next comment
    nextComment() {
        if (this.comments.length <= 1) return;
        
        // Calculate next index with wrap-around
        const nextIndex = (this.currentIndex + 1) % this.comments.length;
        this.goToComment(nextIndex);
    }
    
    // Switch to previous comment
    prevComment() {
        if (this.comments.length <= 1) return;
        
        // Calculate prev index with wrap-around
        const prevIndex = (this.currentIndex - 1 + this.comments.length) % this.comments.length;
        this.goToComment(prevIndex);
    }
    
    // Update the active state of the dots
    updateProgressIndicators() {
        const dots = this.carousel.querySelectorAll('.progress-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }
    
    // Enable/Disable arrow buttons based on comment count
    updateNavigation() {
        if (this.prevBtn && this.nextBtn) {
            const hasMultiple = this.comments.length > 1;
            this.prevBtn.disabled = !hasMultiple;
            this.nextBtn.disabled = !hasMultiple;
        }
    }
    
    // Setup automatic sliding interval
    startAutoRotation() {
        if (this.interval) {
            clearInterval(this.interval);
        }
        
        if (this.comments.length > 1) {
            this.interval = setInterval(() => {
                this.nextComment();
            }, 4000); // Switch every 4 seconds
        }
    }
    
    // Reset timer when user interacts manually
    resetAutoRotation() {
        this.startAutoRotation();
    }
    
    // Attach event listeners to buttons and form
    setupEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevComment());
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextComment());
        }
        if (this.commentForm) {
            this.commentForm.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }
    
    // Handle new comment submission
    async handleSubmit(e) {
        e.preventDefault(); // Prevent page reload
        
        const formData = new FormData(this.commentForm);
        const submitBtn = this.commentForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Disable submit button to prevent double-click
        submitBtn.disabled = true;
        submitBtn.textContent = 'جاري الإرسال...'; // "Sending..."
        
        try {
            // Post data to PHP backend
            const response = await fetch('./assets/php/submit_comment.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showMessage('تم إضافة تعليقك بنجاح!', 'success'); // "Comment added successfully"
                this.commentForm.reset(); // Clear form
                
                // Reload comments to show the new one
                await this.loadComments();
                
            } else {
                this.showMessage(result.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showMessage('حدث خطأ في الإرسال', 'error'); // "Error sending"
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
    
    // Display feedback messages (success/error)
    showMessage(text, type) {
        if (this.commentMessage) {
            this.commentMessage.innerHTML = `<div class="message ${type}">${text}</div>`;
            // Hide message after 5 seconds
            setTimeout(() => {
                this.commentMessage.innerHTML = '';
            }, 5000);
        }
    }
    
    // Sanitize input to prevent XSS (Cross-Site Scripting)
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}


// ========== Initialize All Components When Page Loads ==========
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing components...');
    
    // Initialize typing effect
    initTypingEffect();
    
    // Initialize navbar scroll effect
    initNavbarScrollEffect();
    
    // Initialize image carousel
    initCarousel();
    
    // Check server status
    checkServerStatus();
    
    // Initialize comments carousel class
    new SimpleCommentsCarousel();
    
    console.log('All components initialized');
});