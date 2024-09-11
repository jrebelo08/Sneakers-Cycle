<?php 
    declare(strict_types = 1);

    require_once('../session/session.php');
    require_once('../database/connection.db.php');
    require_once('../database/user.class.php');

    $session = new Session();

    $db = getDatabaseConnection();

    $emailOrUsername = htmlentities($_POST['username-email']);
    $password = htmlentities($_POST['password']);
    
    if ($session->getCSRF() !== $_POST['csrf']) {
        $session->addMessage('Error:', 'Request does not appear to be legitimate');
        sleep(10);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $user = User::getUserWithPassword($db, $emailOrUsername, $password);
    if ($user) {
        $session->setId($user->id);
        $session->setName($user->username);
        $session->addMessage('success', 'Login successful, welcome ' . $user->username . '!');
        header('Location: ../index.php');
        exit();
    } else {
        $session->addMessage('error', 'Login failed! Please check your credentials.');
    }
    sleep(10);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
?>