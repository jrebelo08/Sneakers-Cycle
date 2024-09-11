<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../session/session.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$sender_id = $_GET['sender_id'];
$receiver_id = $_GET['receiver_id'];
$item_id = $_GET['item_id'];

$sender_username = User::getUsernameById($db, (int)$sender_id);
$receiver_username = User::getUsernameById($db, (int)$receiver_id);

$chat_id = Message::getChatIdSenderReceiver($db, $item_id, $receiver_id, $sender_id);

$item = Message::getItemByChatId($db, $chat_id);

$item_object = Item::getItem($db, (int)$item);

$messages = Message::getMessagesForChat($db, $chat_id,$item_id);

$item = Item::getItem($db, (int)$item_id);

if(!is_numeric($item->itemOwner)){
    $item_owner_id = User::getUserIdByUsername($db,$item->itemOwner);
}else{
    $item_owner_id = $item->itemOwner;
}

$is_item_owner = ($sender_id === $item_owner_id);

$lastSuggestedPrice = Message::getLastSuggestedPrice($db, $chat_id,(int)$item_id); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link rel="stylesheet" href="../style/chat.css">
</head>
<body>
    <h1>Chat between <?php echo $sender_username; ?> and <?php echo $receiver_username; ?></h1>
    
    <div id="chat-messages"></div>

    <form id="message-form">
        <input type="hidden" id="chat_id" value="<?php echo $chat_id; ?>">
        <input type="hidden" id="sender_id" value="<?php echo $sender_id; ?>">
        <input type="hidden" id="receiver_id" value="<?php echo $receiver_id; ?>">
        <input type="hidden" id="item_id" value="<?php echo $item_id; ?>">
        <textarea id="content" placeholder="Type your message here..."></textarea>
        <button type="submit">Send</button>
    </form>

    <?php if ($is_item_owner): ?>
        <form id="sell-price-suggestion-form">
            <input type="hidden" id="chat_id" value="<?php echo $chat_id; ?>"> 
            <input type="number" id="sell_price" placeholder="Enter sell price">
            <button type="button" onclick="suggestSellPrice()">Suggest Sell Price</button>
        </form>
    <?php else: ?>
        <?php if ($lastSuggestedPrice != null): ?>
            <form id="add-to-cart-form" action="../actions/action_cart.php" method="POST">
                <input type="hidden" name="item_json" value='<?php echo json_encode($item_object); ?>'>
                <input type="hidden" name="last_suggested_price" id="last_suggested_price" value="<?php echo $lastSuggestedPrice; ?>">
                <button id="add-to-cart-submit" type="submit">Add to Cart $<?php echo $lastSuggestedPrice; ?></button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <script src="../javascript/chat.js">
    
    </script>
</body>
</html>