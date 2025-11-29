// إضافة لإرسال الرسائل للصفحة الرئيسية
function sendMessageToParent(type, data) {
    if (window.parent && window.parent !== window) {
        window.parent.postMessage({
            type: type,
            regionId: data
        }, '*');
    }
}

// تعديل دوال الماوس الموجودة
function handleMouseOver(event) {
    const target = event.target;
    
    if (target.tagName === 'path' || target.tagName === 'polygon') {
        const regionId = target.id;
        const regionName = regionsData[regionId];
        
        if (regionName) {
            tooltip.textContent = regionName;
            tooltip.style.opacity = '1';
            
            // إرسال رسالة للصفحة الرئيسية
            sendMessageToParent('REGION_HOVER', regionId);
        }
    }
}

function handleMouseOut(event) {
    if (event.target.tagName === 'path' || event.target.tagName === 'polygon') {
        tooltip.style.opacity = '0';
        
        // إرسال رسالة للصفحة الرئيسية
        sendMessageToParent('REGION_LEAVE', event.target.id);
    }
}

// إضافة حدث النقر على المناطق
function handleClick(event) {
    const target = event.target;
    
    if (target.tagName === 'path' || target.tagName === 'polygon') {
        const regionId = target.id;
        const regionName = regionsData[regionId];
        
        if (regionName) {
            // إرسال رسالة النقر للصفحة الرئيسية
            sendMessageToParent('REGION_CLICK', regionId);
        }
    }
}

// إضافة تتبع للخريطة ككل
document.addEventListener('DOMContentLoaded', () => {
    const saudiMap = document.getElementById('saudi-map');
    
    if (saudiMap) {
        // إضافة حدث النقر
        saudiMap.addEventListener('click', handleClick);
        
        saudiMap.addEventListener('mouseleave', function() {
            sendMessageToParent('MAP_LEAVE', null);
        });
    }
});