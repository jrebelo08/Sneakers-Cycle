<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/common.tpl.db.php');

$db = getDatabaseConnection();
$session = new Session();

try{
    if(isset($_POST['term'])){
        $searchTerm = htmlentities($_POST['term']);
        
        $results = getSearchedItems($db, $searchTerm);
        
        if (!empty($results)) {
            foreach ($results as $result) {
                echo '<p class="result-item">' . $result['ItemName'] . '</p>';
            }
        } else {
            echo '<p class="no-result">No results found.</p>';
        }
    }
}catch(PDOException $e){
    die("ERROR: Could not able to execute" . $e->getMessage());
}
?>
