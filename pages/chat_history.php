<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/conversation.class.php');
require_once(__DIR__ . '/../session/session.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$current_user = User::getUserByUsername($db, $session->getName());
$currentUserId = User::getUserIdByUsername($db, $current_user->username);

$conversations = Conversation::getUserConversations($db, $currentUserId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat History</title>
    <link rel="stylesheet" href="../style/history.css">
</head>
<body>
    <h1>Chat History</h1>

    <ul>
        <?php foreach ($conversations as $conversation): ?>
            <?php
                $otherUserId = ($conversation->senderId == $currentUserId) ? $conversation->receiverId : $conversation->senderId;
                $otherUserUsername = User::getUsernameById($db, $otherUserId);
                $item = Item::getItem($db, (int)$conversation->itemId);
            ?>
            <li>
                <a href="../pages/chat.php?sender_id=<?= htmlspecialchars((string)$currentUserId) ?>&receiver_id=<?= htmlspecialchars((string)$otherUserId) ?>&item_id=<?= htmlspecialchars((string)$conversation->itemId) ?>">
                    Chat with <?= htmlspecialchars($otherUserUsername) ?> (Item Name: <?= htmlspecialchars($item->itemName) ?>)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
