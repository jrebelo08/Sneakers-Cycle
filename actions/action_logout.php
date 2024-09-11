<?php
    declare(strict_types = 1);

    require_once('../session/session.php');

    $session = new Session();
    $session->logout();

    header('Location: ../index.php');
?>