<?php
// db_connect.php

// 1. إعدادات الاتصال (لخادم XAMPP الافتراضي)
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "r7al"; // تأكد من استخدام اسم قاعدة البيانات الصحيح

// 2. إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8mb4");

// 3. التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// التحقق من عدم تعريف الدوال مسبقاً
if (!function_exists('get_regions')) {
    function get_regions($conn) {
        $sql = "SELECT id, name FROM regions ORDER BY name";
        $result = $conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}

if (!function_exists('get_cities')) {
    function get_cities($conn, $region_id = null) {
        $sql = "SELECT id, name FROM cities";
        
        if ($region_id !== null && is_numeric($region_id)) {
            $safe_region_id = intval($region_id);
            $sql .= " WHERE region_id = {$safe_region_id}";
        }
        
        $sql .= " ORDER BY name";
        $result = $conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}

if (!function_exists('get_categories')) {
    function get_categories() {
        return ['تراثية', 'دينية', 'سياحية','ترفيهية','ثقافية','طبيعية']; 
    }
}

if (!function_exists('add_comment')) {
    // دالة جديدة لإضافة تعليق
    function add_comment($conn, $name, $text) {
        $stmt = $conn->prepare("INSERT INTO comments (name, text) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $text);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

if (!function_exists('get_comments')) {
    // دالة جديدة لجلب التعليقات
    function get_comments($conn) {
        $sql = "SELECT * FROM comments ORDER BY date DESC";
        $result = $conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}

if (!function_exists('get_attraction_location')) {
    function get_attraction_location($conn, $attraction_id) {
        $stmt = $conn->prepare("SELECT location FROM tourist_attractions WHERE id = ?");
        $stmt->bind_param("i", $attraction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $location = $result->fetch_assoc();
        $stmt->close();
        return $location;
    }
}
?>