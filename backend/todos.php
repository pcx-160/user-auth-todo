<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_GET['user_id'] ?? $data['user_id'] ?? null;

if (!$user_id) {
    http_response_code(400);
    echo json_encode(["message" => "User ID required."]);
    exit;
}

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ?");
        $stmt->execute([$user_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $task = $data['task'] ?? '';
        if (!$task) {
            http_response_code(400);
            echo json_encode(["message" => "Task is required."]);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, task) VALUES (?, ?)");
        $stmt->execute([$user_id, $task]);
        echo json_encode(["message" => "Task added"]);
        break;

    case 'DELETE':
        $todo_id = $_GET['id'] ?? null;
        if (!$todo_id) {
            http_response_code(400);
            echo json_encode(["message" => "Todo ID required."]);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
        $stmt->execute([$todo_id, $user_id]);
        echo json_encode(["message" => "Task deleted"]);
        break;

    case 'PUT':
        $todo_id = $data['id'] ?? null;
        $task = $data['task'] ?? '';

        if (!$todo_id || !$task) {
            http_response_code(400);
            echo json_encode(["message" => "Todo ID and new task content are required."]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE todos SET task = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$task, $todo_id, $user_id]);
        echo json_encode(["message" => "Task updated"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
}
?>
