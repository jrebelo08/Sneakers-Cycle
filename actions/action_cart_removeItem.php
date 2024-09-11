<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../session/session.php');

$session = new Session();

if ($session->getCSRF() !== htmlentities($_POST['csrf'])) {
    $session->addMessage('Error:', 'Request does not appear to be legitimate');
    sleep(10);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} 

if(isset($_POST['item_json'])) {
    $item_data = json_decode($_POST['item_json'], true);
    $session->removeFromCartById($item_data['id']);
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']); 
    exit();
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
