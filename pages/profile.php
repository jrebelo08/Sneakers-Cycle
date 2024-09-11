<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../templates/profile.tpl.php'); 
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();

$db = getDatabaseConnection();

drawHeader($session, false);

if (!($session->isLoggedIn())) {
    header('Location: ../index.php');
    exit();
}

$user = User::getUserByUsername($db, $session->getName());

if ($user === null) {
    header('Location: ../index.php');
    exit();
}

drawHeaderForSell($session,true);
drawProfile($user,$session);

drawFooter();

?>