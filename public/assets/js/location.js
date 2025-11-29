// location.js - ملف جديد للتعامل مع الموقع الجغرافي
document.addEventListener('DOMContentLoaded', function() {
    initLocationSection();
});

function initLocationSection() {
    const locationSection = document.querySelector('.article-location');
    if (!locationSection) return;

    // إضافة تفاعلية للأزرار
    const directionBtn = document.getElementById('getDirections');
    const shareLocationBtn = document.getElementById('shareLocation');

    if (directionBtn) {
        directionBtn.addEventListener('click', function() {
            const address = this.getAttribute('data-address');
            openDirections(address);
        });
    }

    if (shareLocationBtn) {
        shareLocationBtn.addEventListener('click', function() {
            const locationData = {
                title: document.querySelector('.article-title')?.textContent || 'معلم سياحي',
                address: this.getAttribute('data-address'),
                url: window.location.href
            };
            shareLocation(locationData);
        });
    }

    // تهيئة الخريطة إذا كانت متاحة
    initMapIfAvailable();
}

function openDirections(address) {
    if (!address) {
        alert('عنوان الموقع غير متوفر');
        return;
    }

    // فتح خرائط جوجل مع الاتجاهات
    const encodedAddress = encodeURIComponent(address);
    const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${encodedAddress}`;
    
    window.open(mapsUrl, '_blank');
}

function shareLocation(locationData) {
    if (navigator.share) {
        // استخدام Web Share API إذا كان متاحاً
        navigator.share({
            title: locationData.title,
            text: `موقع ${locationData.title}: ${locationData.address}`,
            url: locationData.url
        })
        .then(() => console.log('تم مشاركة الموقع بنجاح'))
        .catch((error) => console.log('خطأ في المشاركة:', error));
    } else {
        // نسخ الرابط إلى الحافظة
        const shareText = `موقع ${locationData.title}: ${locationData.address}\n${locationData.url}`;
        navigator.clipboard.writeText(shareText)
            .then(() => alert('تم نسخ معلومات الموقع إلى الحافظة'))
            .catch(() => {
                // طريقة بديلة للنسخ
                const textArea = document.createElement('textarea');
                textArea.value = shareText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('تم نسخ معلومات الموقع إلى الحافظة');
            });
    }
}

function initMapIfAvailable() {
    const mapContainer = document.querySelector('.location-map');
    if (!mapContainer) return;

    const latitude = mapContainer.getAttribute('data-lat');
    const longitude = mapContainer.getAttribute('data-lng');
    const locationName = mapContainer.getAttribute('data-name');

    if (latitude && longitude) {
        // هنا يمكنك إضافة تكامل مع خرائط جوجل أو أي خدمة خرائط
        loadMap(parseFloat(latitude), parseFloat(longitude), locationName, mapContainer);
    }
}

function loadMap(lat, lng, name, container) {
    // هذا مثال لاستخدام خرائط جوجل - ستحتاج إلى استبدال YOUR_API_KEY بمفتاح API الخاص بك
    const mapUrl = `https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=${lat},${lng}&zoom=15&language=ar&maptype=roadmap`;
    
    const iframe = document.createElement('iframe');
    iframe.src = mapUrl;
    iframe.width = '100%';
    iframe.height = '100%';
    iframe.style.border = '0';
    iframe.loading = 'lazy';
    iframe.referrerpolicy = 'no-referrer-when-downgrade';
    
    container.innerHTML = '';
    container.appendChild(iframe);
}