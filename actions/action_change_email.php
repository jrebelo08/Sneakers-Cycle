<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../session/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $session = new Session();
    $db = getDatabaseConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($session->getCSRF() !== $_POST['csrf']) {
            $session->addMessage('Error:', 'Request does not appear to be legitimate');
            sleep(10);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }   
        if ($_POST['new'] != $_POST['new2']) {
            $session->addMessage('Email error', 'Passwords do not match!');
        } else {
            require_once(__DIR__ . '/../database/connection.db.php');
            require_once(__DIR__ . '/../database/user.class.php');
            
            $email = User::getEmailByUsername($db, $session->getName());
            $user = User::getUserWithPassword($db, $email, htmlentities($_POST['old_password']));

            if($user->username !== $session->getName() || $user === null){
                $session->addMessage('Password error', 'Current password Wrong!');
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $db = getDatabaseConnection();
    
            $admin = (User::getUserTypeByUsername($db, $session->getName()) == 'admin');
    
            if ($admin) {
                User::changePassword($db, $session->getName(), htmlentities($_POST['new']));
            } elseif($_POST['new_email'] == $_POST['confirm_email']){
                User::changeEmailName($db, $session->getName(), htmlentities($_POST['new_email']));
            }
        }
    }
    
    header("Location: ../pages/profile.php");
    exit();
?>