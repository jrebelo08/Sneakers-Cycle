<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/message.class.php');

$db = getDatabaseConnection();

$chatId = htmlentities($_GET['chat_id']);
$item_id = htmlentities($_GET['item_id']);

if (!isset($chatId) || !isset($item_id)) {
    echo json_encode([]);
    exit();
}

$messages = Message::getMessagesForChat($db, $chatId, $item_id);

$formattedMessages = [];
foreach ($messages as $message) {
    $senderUsername = User::getUsernameById($db, $message->getSenderId()); 
    
    $formattedMessages[] = [
        'messageId' => $message->getMessageId(), 
        'chatId' => $message->getChatId(), 
        'senderId' => $message->getSenderId(), 
        'receiverId' => $message->getReceiverId(), 
        'senderUsername' => $senderUsername, 
        'content' => $message->getContent(), 
        'timestamp' => $message->getTimestamp() 
    ];
}

echo json_encode($formattedMessages);

?>
