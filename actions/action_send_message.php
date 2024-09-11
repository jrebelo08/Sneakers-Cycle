<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');

$db = getDatabaseConnection();

$chat_id = htmlentities($_POST['chat_id']);
$sender_id = htmlentities($_POST['sender_id']);
$receiver_id = htmlentities($_POST['receiver_id']);
$content = htmlentities($_POST['content']);

Message::sendMessage($db, $chat_id, $sender_id, $receiver_id, $content, $content);


echo 'Message sent successfully!';
?>