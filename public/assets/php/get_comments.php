<?php
// get_comments.php
include __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$comments = get_comments($conn);
echo json_encode($comments, JSON_UNESCAPED_UNICODE);
?>