// بيانات المناطق مع الألوان والمعلومات
const regionsData = {
    'arriyad': {
        name: 'الرياض',
        color: '#f6cc7e',
        description: 'الرياض هي عاصمة المملكة العربية السعودية وأكبر مدنها، وتقع في وسط شبه الجزيرة العربية. تعتبر الرياض مركزًا اقتصاديًا وسياسيًا مهمًا في المملكة، وتضم العديد من المعالم البارزة مثل برج المملكة ومركز الملك عبدالله المالي.',
        image: './assets/images/Photos/Riyad.png'
    },
    'makkah': {
        name: 'مكة المكرمة',
        color: '#b4b4b4',
        description: 'مكة المكرمة هي أقدس مدينة في الإسلام، وتقع في غرب المملكة. تضم المسجد الحرام والكعبة المشرفة، وهي قبلة المسلمين في صلاتهم. يقصدها ملايين المسلمين سنويًا لأداء فريضتي الحج والعمرة.',
        image: './assets/images/Photos/Makkah.png'
    },
    'almadinah': {
        name: 'المدينة المنورة',
        color: '#b4b4b4',
        description: 'المدينة المنورة هي أول عاصمة في تاريخ الإسلام، وثاني أقدس الأماكن لدى المسلمين بعد مكة المكرمة. تضم المسجد النبوي وقبر النبي محمد ﷺ',
        image: './assets/images/Photos/El-Maddena.png'
    },
    'ashsharqiyah': {
        name: 'الشرقية',
        color: '#8fd3f4',
        description: 'المنطقة الشرقية هي أكبر مناطق السعودية مساحة، وتطل على الخليج العربي. تشتهر باحتياطياتها النفطية الهائلة، وتضم مدنًا مهمة مثل الدمام والخبر والظهران. تعتبر مركزًا صناعيًا واقتصاديًا رئيسيًا في المملكة.',
        image: './assets/images/Photos/El-Sharqia.png'
    },
    'alquassim': {
        name: 'القصيم',
        color: '#f6cc7e',
        description: 'منطقة القصيم تقع في وسط السعودية وتشتهر بالزراعة وخاصة النخيل. عاصمتها الإدارية هي بريدة، وتضم العديد من المحافظات والمراكز الحضرية. تشتهر المنطقة بتراثها الثقافي الغني وأسواقها التقليدية.',
        image: './assets/images/Photos/El_Qaseem.png'
    },
    'hail': {
        name: 'حائل',
        color: '#f6cc7e',
        description: 'منطقة حائل تقع في شمال السعودية وتشتهر بجبل أجا وسلمى. تعتبر بوابة شمال الجزيرة العربية وتضم مواقع تراثية مهمة مثل قصر القشلة وقلعة عيرف.',
        image: './assets/images/Photos/Haal.png'
    },
    'tabuk': {
        name: 'تبوك',
        color: '#8fd3f4',
        description: 'منطقة تبوك تقع في شمال غرب السعودية وتطل على خليج العقبة. تشتهر بالزراعة والمناخ المعتدل، وتضم مواقع تاريخية مثل قلعة تبوك ومحطة سكة حديد الحجاز.',
        image: './assets/images/Photos/Tabok.png'
    },
    'alhududashshamaliyah': {
        name: 'الحدود الشمالية',
        color: '#f6cc7e',
        description: 'منطقة الحدود الشمالية تقع في أقصى شمال السعودية وتحد العراق. عاصمتها الإدارية هي عرعر، وتشتهر بالمراعي والثروة الحيوانية.',
        image: './assets/images/Photos/Al-hodod-Al-Shmalia.png'
    },
    'jizan': {
        name: 'جازان',
        color: '#92b34d',
        description: 'منطقة جازان تقع في جنوب غرب السعودية وتطل على البحر الأحمر. تشتهر بالزراعة والثروة السمكية، وتضم جزر فرسان ذات الطبيعة الخلابة.',
        image: './assets/images/Photos/Jazan.png'
    },
    'najran': {
        name: 'نجران',
        color: '#f6cc7e',
        description: 'منطقة نجران تقع في جنوب السعودية وتحد اليمن. تشتهر بالتراث التاريخي والزراعة، وتضم مواقع أثرية مهمة مثل قرية الأخدود الأثرية.',
        image: './assets/images/Photos/Najran.png'
    },
    'albahah': {
        name: 'الباحة',
        color: '#92b34d',
        description: 'منطقة الباحة تقع في جنوب غرب السعودية وتشتهر بالطبيعة الخلابة والمناخ المعتدل. تضم العديد من الغابات والمنتزهات الطبيعية والموروث الثقافي الغني.',
        image: './assets/images/Photos/Al-Baha.png'
    },
    'aljawf': {
        name: 'الجوف',
        color: '#f6cc7e',
        description: 'منطقة الجوف تقع في شمال السعودية وتشتهر بالزراعة وخاصة الزيتون. تضم مواقع أثرية مهمة مثل موقع دومة الجندل وقلعة مارد.',
        image: './assets/images/Photos/Al-Jouf.png'
    },
    'asir': {
        name: 'عسير',
        color: '#92b34d',
        description: 'عسير هي منطقة جبلية تقع في جنوب غرب السعودية، وتشتهر بمناخها المعتدل وجمالها الطبيعي. عاصمتها الإدارية هي أبها، وتضم مواقع سياحية مثل السودة والقرعاء. تشتهر المنطقة بتراثها الثقافي الغني وطبيعتها الخلابة.',
        image: './assets/images/Photos/Asser.png'
    }
};

// دالة لتوجيه المستخدم إلى صفحة المعالم مع فلتر المنطقة
function redirectToRegionAttractions(regionId) {
    const regionData = regionsData[regionId];
    if (regionData) {
        // الحصول على معرف المنطقة من الخريطة (قد تحتاج لتعديل هذا حسب قاعدة البيانات)
        const regionMap = {
            'arriyad': 2,
            'makkah': 1,
            'almadinah': 3,
            'ashsharqiyah': 4,
            'alquassim': 5,
            'hail': 7,
            'tabuk': 6,
            'alhududashshamaliyah': 8,
            'jizan': 10,
            'najran': 11,
            'albahah': 12,
            'aljawf': 9,
            'asir': 13
        };
        
        const regionDbId = regionMap[regionId];
        if (regionDbId) {
            // التوجيه إلى صفحة المعالم مع فلتر المنطقة
            window.location.href = `./assets/php/attractions_list.php?region=${regionDbId}`;
        }
    }
}

// متغيرات التحكم
let currentRegion = null;
let isMouseInMap = false;

// دالة لعرض معلومات المنطقة
function showRegionInfo(regionData, regionId) {
    const infoPanel = document.getElementById('infoPanel');
    const regionInfo = document.getElementById('regionInfo');
    const welcomeMessage = document.querySelector('.welcome-message');
    
    // تغيير لون لوحة المعلومات
    infoPanel.style.backgroundColor = regionData.color;
    
    // إخفاء رسالة الترحيب وإظهار معلومات المنطقة
    welcomeMessage.style.display = 'none';
    
    // تحديث محتوى معلومات المنطقة
    regionInfo.innerHTML = `
        <div class="region-header">
            <h3 class="region-name">${regionData.name}</h3>
        </div>
        <img src="${regionData.image}" alt="${regionData.name}" class="region-image" onerror="this.src='https://via.placeholder.com/400x200/be9d6a/ffffff?text=صورة+${encodeURIComponent(regionData.name)}'">
        <p class="region-description">${regionData.description}</p>
    `;
    regionInfo.classList.add('active');
}

// دالة للعودة للرسالة الترحيبية
function resetToWelcome() {
    const infoPanel = document.getElementById('infoPanel');
    const regionInfo = document.getElementById('regionInfo');
    const welcomeMessage = document.querySelector('.welcome-message');
    
    infoPanel.style.backgroundColor = '#be9d6a';
    welcomeMessage.style.display = 'block';
    regionInfo.classList.remove('active');
    currentRegion = null;
}

// تهيئة التفاعل
document.addEventListener('DOMContentLoaded', function() {
    const mapIframe = document.getElementById('map');
    
    // إضافة event listener للرسائل من الـ iframe
    window.addEventListener('message', function(event) {
        // التأكد من أن الرسالة من الخريطة
        if (event.data && event.data.type === 'REGION_HOVER') {
            const regionId = event.data.regionId;
            const regionData = regionsData[regionId];
            
            if (regionData) {
                currentRegion = regionId;
                showRegionInfo(regionData, regionId);
            }
        }
        else if (event.data && event.data.type === 'REGION_LEAVE') {
            // إذا خرج الماوس من كل المناطق، ارجع للرسالة الترحيبية
            setTimeout(() => {
                if (!currentRegion) {
                    resetToWelcome();
                }
            }, 100);
        }
        else if (event.data && event.data.type === 'MAP_LEAVE') {
            // إذا خرج الماوس من الخريطة ككل
            resetToWelcome();
            currentRegion = null;
        }
        // إضافة حدث النقر على المنطقة
        else if (event.data && event.data.type === 'REGION_CLICK') {
            const regionId = event.data.regionId;
            redirectToRegionAttractions(regionId);
        }
    });
    
    // بديل: إذا كانت الخريطة في نفس النطاق، استخدم الطريقة المباشرة
    setTimeout(() => {
        try {
            const mapDocument = mapIframe.contentDocument || mapIframe.contentWindow.document;
            setupMapInteractions(mapDocument);
        } catch (e) {
            console.log('Using postMessage method for cross-origin iframe');
        }
    }, 2000);
});

// الطريقة المباشرة (لنفس النطاق)
function setupMapInteractions(mapDocument) {
    const mapSvg = mapDocument.getElementById('saudi-map');
    const paths = mapDocument.querySelectorAll('#saudi-map path, #saudi-map polygon');
    
    // تتبع دخول وخروج الماوس من الخريطة ككل
    mapSvg.addEventListener('mouseenter', function() {
        isMouseInMap = true;
    });
    
    mapSvg.addEventListener('mouseleave', function() {
        isMouseInMap = false;
        // إذا خرج الماوس من الخريطة ككل، ارجع للرسالة الترحيبية
        if (!currentRegion) {
            resetToWelcome();
        }
    });
    
    // إضافة حدث لكل منطقة
    paths.forEach(path => {
        path.addEventListener('mouseenter', function(e) {
            const regionId = this.id;
            const regionData = regionsData[regionId];
            
            if (regionData) {
                currentRegion = regionId;
                showRegionInfo(regionData, regionId);
                e.stopPropagation();
            }
        });
        
        path.addEventListener('mouseleave', function(e) {
            currentRegion = null;
            setTimeout(() => {
                if (!isMouseInMap) {
                    resetToWelcome();
                }
            }, 100);
            e.stopPropagation();
        });

        // إضافة حدث النقر للمنطقة (لنفس النطاق)
        path.addEventListener('click', function(e) {
            const regionId = this.id;
            redirectToRegionAttractions(regionId);
            e.stopPropagation();
        });
    });
}