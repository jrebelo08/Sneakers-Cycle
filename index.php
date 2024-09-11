<?php 
  require_once('templates/common.tpl.php'); 
  require_once('database/common.tpl.db.php'); 
  require_once('database/connection.db.php');
  require_once('templates/items.tpl.php');
  require_once('templates/pagination.tpl.php');
  require_once('class/pagination.class.php');
    

  $session = new Session();

  $db = getDatabaseConnection();
  $numberItems = getNumberOfItems($db);

  if($numberItems == -1){
    exit();
  }
  $pagination = new Pagination($numberItems);
  if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagination->setCurrentPage(intval($_GET['page']));
  }else{
    $pagination->setCurrentPage(1);
  }

  if(isset($_GET['category']) && $_GET['category'] != 'All'){

    $category = $_GET['category'];
    $items = Item::getItemsByCategory($db, $category, $pagination->getOffset(), $pagination->getLimit());
    
    drawHeader($session,true);
    drawItems($items);
    drawFooter();
    exit();

  }else {

    $items = Item::getItemsStartingOn($db, $pagination->getOffset(), $pagination->getLimit());
    
    drawHeader($session,true);
    drawItems($items);
    drawPagination($pagination);
    drawFooter();

  }
?>
