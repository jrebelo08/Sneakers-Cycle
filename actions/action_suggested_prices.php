<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = getDatabaseConnection();

$chat_id = htmlentities($_GET['chat_id']);
$item_id = htmlentities($_GET['item_id']);

if (!isset($chat_id) || !isset($item_id)) {
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

try {
    $lastSuggestedPrice = Message::getLastSuggestedPrice($db, (int)$chat_id, (int)$item_id);
    echo json_encode(['lastSuggestedPrice' => $lastSuggestedPrice]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
