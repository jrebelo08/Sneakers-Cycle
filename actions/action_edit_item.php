<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/item.class.php');

$session = new Session();
$db = getDatabaseConnection();

if ($session->getCSRF() !== $_POST['csrf']) {
    $session->addMessage('Error:', 'Request does not appear to be legitimate');
    sleep(10);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$itemId = isset($_POST['itemId']) ? (int)htmlentities($_POST['itemId']) : 0;
$itemName = htmlentities($_POST['ItemName'] ?? '');
$itemBrand = htmlentities($_POST['ItemBrand'] ?? '');
$itemOwner = Item::getOwnerUsernameId($db, $itemId);
$itemDescription = htmlentities($_POST['ItemDescription'] ?? '');
$itemCategory = htmlentities($_POST['ItemCategory'] ?? '');
$itemPrice = (int)($_POST['ItemPrice'] ?? 0);
$itemCondition = htmlentities($_POST['ItemCondition'] ?? '');
$itemSize = htmlentities($_POST['ItemSize'] ?? '');

$itemImage = '';
if (isset($_FILES['ItemImage']) && $_FILES['ItemImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['ItemImage']['tmp_name'];
    $fileName = $_FILES['ItemImage']['name'];
    $fileSize = $_FILES['ItemImage']['size'];
    $fileType = $_FILES['ItemImage']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    $uploadFileDir = '../uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }

    $dest_path = $uploadFileDir . $newFileName;

    if(move_uploaded_file($fileTmpPath, $dest_path)) {
        $itemImage = $dest_path; 
    } else {
        $session->addMessage('Error:', 'There was an error moving the uploaded file.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    $itemImage = $_POST['currentItemImage'] ?? '';
}

$success = Item::updateItem($db, $itemId, $itemName, $itemBrand, $itemOwner, $itemDescription, $itemCategory, $itemPrice, $itemCondition, $itemSize, $itemImage);

if ($success) {
    header("Location: ../index.php");
    exit();
} else {
    $session->addMessage('Error:', 'Failed to update the item.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
