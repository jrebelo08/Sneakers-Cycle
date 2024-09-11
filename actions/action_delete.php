<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../session/session.php');



$session = new Session();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['item_id'])) {
    if ($session->getCSRF() !== $_POST['csrf']) {
        $session->addMessage('Error:', 'Request does not appear to be legitimate');
        sleep(10);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } 
    $itemId = (int)htmlentities($_POST['item_id']);
    $imageURL = htmlentities($_POST['item_image']);

    $db = getDatabaseConnection();
    $success = Item::deleteItem($db, $itemId);

    if (!file_exists($imageURL)) {
        echo "File does not exist.";
        sleep(5);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    if ($success) {
        if (unlink($imageURL)) {
            header("Location: /../pages/profile.php");
            exit();
        } else {
            echo "Failed to delete the file.";
            sleep(5);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
 else {
    echo "Invalid request.";
    sleep(5);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
}
?>
