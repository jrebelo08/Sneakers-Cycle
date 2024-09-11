<?php
require_once('../database/connection.db.php');
require_once('../session/session.php');
require_once('../database/user.class.php');

$session = new Session();
$db = getDatabaseConnection();

if ($session->getCSRF()  !== $_POST['csrf']) {
    sleep(10);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $targetDirectory = "../uploads/"; 
    $targetFile = $targetDirectory . basename($_FILES["hiddenInput"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["hiddenInput"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["hiddenInput"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["hiddenInput"]["tmp_name"], $targetFile)) {

            $itemName = htmlentities($_POST['ItemName']);
            $itemBrand = htmlentities($_POST['ItemBrand']);
            $itemDescription = htmlentities($_POST['ItemDescription']);
            $itemCategory = htmlentities($_POST['ItemCategory']);
            $itemPrice = htmlentities($_POST['ItemPrice']);
            $itemOwner = htmlentities($_POST['ItemOwner']);
            $itemSize = htmlentities($_POST['ItemSize']);
            $itemCondition = htmlentities($_POST['ItemCondition']);

            $itemImage = $targetFile;

            if (!isset($itemName) || !isset($itemDescription) || !isset($itemCategory) || !isset($itemPrice) || !isset($itemSize) || !isset($itemCondition)) {
                echo "Invalid form data";
                exit;
            }

            $query = "INSERT INTO Item (ItemName, ItemBrand, ItemDescription, ItemCategory, ItemPrice, ItemOwner, ItemSize, ItemCondition, ItemImage) VALUES (:itemName, :itemBrand, :itemDescription, :itemCategory, :itemPrice, :itemOwner, :itemSize, :itemCondition, :itemImage)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':itemName', $itemName);
            $stmt->bindParam(':itemBrand', $itemBrand);
            $stmt->bindParam(':itemDescription', $itemDescription);
            $stmt->bindParam(':itemCategory', $itemCategory);
            $stmt->bindParam(':itemPrice', $itemPrice);
            $stmt->bindParam(':itemOwner', $itemOwner);
            $stmt->bindParam(':itemSize', $itemSize);
            $stmt->bindParam(':itemCondition', $itemCondition);
            $stmt->bindParam(':itemImage', $itemImage);
            $stmt->execute();

            if(!User::isUserAdmin($db, $session->getId())){
                $user_id = $session->getId();
                $query = "UPDATE User SET ItemsListed = ItemsListed + 1, UserType = CASE WHEN ItemsListed >= 0 THEN 'buyer/seller' ELSE 'buyer' END WHERE UserId = :user_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
            }

            $db = null;

            header('Location: ../index.php');
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
