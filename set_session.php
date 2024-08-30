<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id']) && isset($data['email'])) {
    $_SESSION['user_id'] = $data['user_id'];
    $_SESSION['email'] = $data['email'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>