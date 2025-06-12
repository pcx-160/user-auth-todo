<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit;
}

require 'config.php';

$data = json_decode(file_get_contents('php://input'));
$username = $data->username ?? '';
$password = $data->password ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(["message" => "Login successful", "user_id" => $user['id']]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid username or password"]);
}
?>
