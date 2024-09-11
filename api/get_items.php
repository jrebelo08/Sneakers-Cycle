<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');

header("Access-Control-Allow-Origin: *");

$db = getDatabaseConnection();

$items = Item::getAllItemsFromDatabase($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && is_array($items)) {
    header('Content-Type: application/json');
    echo json_encode(['items' => $items]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
