<?php

require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/common.tpl.db.php');
require_once(__DIR__ . '/../database/item.class.php');

$db = getDatabaseConnection();
$session = new Session();

try {
    if ($session->getCSRF()  !== $_POST['csrf']) {
        sleep(10);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    if (!empty($_POST['name'])) { 
        $itemName = htmlentities($_POST['name']);

        $results = Item::searchItems($db, $itemName, 1); 
        if (!empty($results)) {
            header('Location: ../pages/product.php?id=' . $results[0]->id);
            exit();
        }
    }
} catch(PDOException $e) {
    die("ERROR: Could not execute: " . $e->getMessage());
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();

?>