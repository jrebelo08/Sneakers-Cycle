<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();
$db = getDatabaseConnection();
if ($session->getCSRF() !== $_POST['csrf']) {
    $session->addMessage('Error:', 'Request does not appear to be legitimate');
    sleep(10);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$userId = isset($_POST['id']) ? (int)htmlentities($_POST['id']) : 0;
$username = htmlentities($_POST['username']) ?? '';
$email = htmlentities($_POST['email']) ?? '';
$userType = htmlentities($_POST['type']) ?? '';


$success = User::updateUser($db, $userId, $username, $email, $userType);

if ($success) {
    header("Location: ../pages/profile.php");
    exit();
} else {
    header("Location: ../pages/edit_user.php");
    exit();
}
?>