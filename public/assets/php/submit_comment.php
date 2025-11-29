<?php
// submit_comment.php
include __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['text'] ?? '');
    
    // التحقق من البيانات
    if (empty($name) || empty($text)) {
        echo json_encode(['success' => false, 'message' => 'الاسم والنص مطلوبان']);
        exit;
    }
    
    if (strlen($name) > 100) {
        echo json_encode(['success' => false, 'message' => 'الاسم يجب أن لا يتجاوز 100 حرف']);
        exit;
    }
    
    // إضافة التعليق
    if (add_comment($conn, $name, $text)) {
        echo json_encode(['success' => true, 'message' => 'تم إضافة التعليق بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في إضافة التعليق']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة غير مسموحة']);
}
?>