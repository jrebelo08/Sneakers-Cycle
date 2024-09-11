<?php
    declare(strict_types = 1);

    function getNumberOfItems(PDO $db): int {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM Item");
            $stmt->execute();
            $result = $stmt->fetchColumn(); 
            return (int) $result; 
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return -1;
    }
    function getSearchedItems(PDO $pdo, $searchTerm) {
        try {
            $query = "SELECT * FROM Item WHERE ItemBrand LIKE ? OR ItemName LIKE ? OR ItemDescription LIKE ?";
            $stmt = $pdo->prepare($query);
            $searchTerm = '%' . $searchTerm . '%';
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
            $results = $stmt->fetchAll();
            return $results;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false; 
        }
    }    


?>