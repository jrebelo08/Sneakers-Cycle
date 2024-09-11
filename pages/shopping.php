<?php
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/shopping.tpl.php');
$session = new Session();

drawHeader(false);
drawCart($session);
drawFooter();

?>