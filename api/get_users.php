<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$users = User::getAllUsersFromDatabase($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && is_array($users)) {
    header('Content-Type: application/json');
    echo json_encode(['items' => $users]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
