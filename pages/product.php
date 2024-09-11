<?php 
require_once('../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once('../templates/items.tpl.php');

require_once(__DIR__ . '/../session/session.php');
$session = new Session();

$db = getDatabaseConnection();

$item = Item::getItem($db, intval($_GET['id']));

drawHeader($session, true); 
if (is_numeric($item->itemOwner)) {
    $itemOwnerId = $item->itemOwner;
    $item->itemOwner = User::getUsernameById($db, $item->itemOwner);
}else{
    $itemOwnerId = User::getIdfromUsername($db, $item->itemOwner);
}
drawItem($item, $session, $itemOwnerId); 
drawFooter(); 

?>

