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

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(["message" => "Username and password are required."]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
try {
    $stmt->execute([$username, $hashed_password]);
    echo json_encode(["message" => "User registered successfully."]);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["message" => "Username already taken."]);
}
?>
