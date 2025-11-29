// ========== Helper Functions for Geometry ==========

/**
 * Converts degrees to radians.
 * Essential for trigonometric functions (Math.sin, Math.cos) which expect radians.
 */
function degToRad(d) {
    return d * Math.PI / 180;
}

/**
 * Converts radians to degrees.
 * Used to convert the result back to a readable compass heading.
 */
function radToDeg(r) {
    return r * 180 / Math.PI;
}

// ========== Kaaba Coordinates ==========
// The fixed location of the Kaaba in Mecca (21.4225° N, 39.8262° E)
// Converted to radians immediately for calculation efficiency.
const kaabaLat = 21.4225 * Math.PI / 180;
const kaabaLng = 39.8262 * Math.PI / 180;

// ========== DOM Elements Selection ==========
const statusEl = document.getElementById("status");
const messageEl = document.getElementById("message");
const toggleBtn = document.getElementById("toggle");

// Visual elements for the compass UI
const compassBackground = document.getElementById("compassBackground"); // The rotating dial
const qiblaMarker = document.getElementById("qiblaMarker"); // The icon pointing to Mecca
const needle = document.getElementById("needle"); // The static needle (usually pointing Up/North)

// ========== State Variables ==========
let qiblaBearing = null; // Stores the calculated target angle to Mecca
let running = false;     // Tracks if the compass is currently active


// ========== Qibla Calculation Logic ==========

/**
 * Calculates the Great Circle Bearing (Azimuth) from a given point to the Kaaba.
 * Uses spherical trigonometry formulas.
 * @param {number} latDeg - Current latitude in degrees.
 * @param {number} lngDeg - Current longitude in degrees.
 * @returns {number} The bearing angle (0-360) clockwise from North.
 */
function computeQiblaBearing(latDeg, lngDeg) {
    const lat = degToRad(latDeg);
    const lng = degToRad(lngDeg);
    const dLng = kaabaLng - lng; // Difference in longitude

    // Formula to calculate bearing
    const y = Math.sin(dLng) * Math.cos(kaabaLat);
    const x = Math.cos(lat) * Math.sin(kaabaLat) -
              Math.sin(lat) * Math.cos(kaabaLat) * Math.cos(dLng);

    // Calculate arc tangent (azimuth)
    let brng = radToDeg(Math.atan2(y, x));
    
    // Normalize result to 0-360 degrees
    return (brng + 360) % 360;
}


/**
 * Normalizes an angle to be within the range of -180 to 180.
 * Useful for calculating the shortest difference between two angles.
 */
function normalizeAngle(a) {
    if (a > 180) a -= 360;
    if (a < -180) a += 360;
    return a;
}


/**
 * Updates the UI message based on alignment accuracy.
 * @param {number} heading - The current device heading (0-360).
 */
function updateMessage(heading) {
    // Calculate difference between Qibla bearing and current heading
    const diff = Math.abs(normalizeAngle(qiblaBearing - heading));
    
    // If aligned within 5 degrees, show success message
    if (diff <= 5) {
        messageEl.textContent = "القبلة في هذا الاتجاه"; // "Qibla is in this direction"
    } else {
        messageEl.textContent = "اتجه نحو القبلة"; // "Turn towards Qibla"
    }
}


// ========== Device Orientation Handler ==========

/**
 * Main event listener for device movement.
 * Rotates the compass UI elements based on the phone's physical orientation.
 * @param {DeviceOrientationEvent} event
 */
function handleOrientation(event) {
    if (!running) return;

    let heading;

    // 1. Get the Heading (Compass direction)
    // specific check for iOS devices (WebKit)
    if (event.webkitCompassHeading != null) {
        heading = event.webkitCompassHeading;
    } else {
        // Android / Standard devices
        // alpha is the rotation around z-axis. 
        // 360 - alpha converts it to compass heading (clockwise)
        const alpha = event.alpha;
        if (alpha == null) return; // Sensor data not available
        heading = (360 - alpha);
    }

    // Ensure heading is within 0-360
    heading = (heading + 360) % 360;

    // 2. Rotate UI Elements
    
    // Rotate the compass dial (background) OPPOSITE to the heading.
    // If phone turns right (90deg), dial turns left (-90deg) to stay "North" fixed.
    compassBackground.style.transform = `rotate(${-heading}deg)`;
    
    // Needle usually stays fixed pointing up (0deg) relative to screen
    needle.style.transform = "rotate(0deg)";
    
    // Rotate the Qibla marker. 
    // It needs to point to the bearing relative to the current heading.
    qiblaMarker.style.transform =
        `translateX(-50%) rotate(${qiblaBearing - heading}deg)`;

    // 3. Update status text
    updateMessage(heading);
}


// ========== Geolocation Logic ==========

/**
 * Requests the user's GPS position to calculate the specific Qibla angle
 * for their location.
 */
function getLocation() {
    statusEl.textContent = "جاري تحديد موقعك..."; // "Locating..."

    navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;

        // Calculate Qibla angle once location is found
        qiblaBearing = computeQiblaBearing(lat, lng);
        statusEl.textContent = "تم تحديد اتجاه القبلة."; // "Qibla direction determined"

    }, err => {
        // Handle GPS errors (denied permission, timeout, etc.)
        statusEl.textContent = "تعذر تحديد الموقع."; // "Could not determine location"
    }, { enableHighAccuracy: true });
}


// ========== Compass Control (Start/Stop) ==========

/**
 * Starts the compass: updates UI state and adds event listeners.
 * Handles iOS 13+ permission requirements.
 */
function startCompass() {
    running = true;
    toggleBtn.textContent = "إيقاف"; // "Stop"
    toggleBtn.classList.remove("stopped");
    toggleBtn.classList.add("running");

    // Check if the browser requires permission for device orientation (iOS 13+)
    if (typeof DeviceOrientationEvent?.requestPermission === "function") {
        DeviceOrientationEvent.requestPermission().then(res => {
            if (res === "granted") {
                window.addEventListener("deviceorientation", handleOrientation, true);
            } else {
                statusEl.textContent = "لم يتم السماح بالحساسات."; // "Sensors not allowed"
            }
        });
    } else {
        // Standard devices (Android/Older iOS) don't need explicit permission request logic
        window.addEventListener("deviceorientation", handleOrientation, true);
    }
}


/**
 * Stops the compass: cleans up event listeners and resets UI.
 */
function stopCompass() {
    running = false;
    toggleBtn.textContent = "ابدأ"; // "Start"
    toggleBtn.classList.remove("running");
    toggleBtn.classList.add("stopped");

    // Remove the event listener to save battery
    window.removeEventListener("deviceorientation", handleOrientation);
    statusEl.textContent = "تم إيقاف البوصلة."; // "Compass stopped"
}


// ========== Main Toggle Button ==========

toggleBtn.onclick = () => {
    if (!running) {
        // On first click: Get GPS location -> Then start compass
        getLocation();
        startCompass();
    } else {
        // On second click: Stop
        stopCompass();
    }
};