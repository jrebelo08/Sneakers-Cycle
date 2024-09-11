<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../session/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/item.class.php');

?>

<?php function drawItems(array $items) { ?>
 <main> 
        <section id="products">
        <?php foreach($items as $item) { ?> 
        <article>
                    <a href="/pages/product.php?id=<?=$item->id?>"> <?=$item->itemName ?></a>
                    <a href="/pages/product.php?id=<?=$item->id?>"><img id="product-grid-img" src="<?php echo $item->ItemImage; ?>" alt="<?php echo $item->itemName; ?>"> </a>                
                    <a href="/pages/product.php?id=<?=$item->id?>"><h3> <?=$item->itemBrand ?></h3> </a>
                    <a href="/pages/product.php?id=<?=$item->id?>"><h4>Price: <?=$item->itemPrice ?>$</h4> </a>
        </article>
        <?php } ?>
    </section>
</main>
<?php } ?>

<?php 
function drawItem(Item $item, Session $session, int $itemOwnerId) { 
    $loggedInUserId = $session->getId();
    $isOwner = ($loggedInUserId === $itemOwnerId);


    ?>
    <head>
        <link rel="stylesheet" href="../style/product.css">
    </head>
    <main>
        <h1><?php echo $item->itemName; ?></h1>
        <section id="product" class="product-grid">
            <div class="product-image">
                <img id="product-img" src="<?php echo $item->ItemImage; ?>" alt="<?php echo $item->itemName; ?>">
            </div>
            <div class="product-info">
                <div class="info-item" id="price"><strong>Price:</strong> <?php echo $item->itemPrice; ?>$</div>
                <div class="info-item" id="brand"><strong>Brand:</strong> <?php echo $item->itemBrand; ?></div>
                <div class="info-item" id="category"><strong>Category:</strong> <?php echo $item->itemCategory; ?></div>
                <div class="info-item" id="size"><strong>Size:</strong> <?php echo $item->itemSize; ?></div>
                <div class="info-item" id="condition"><strong>Condition:</strong> <?php echo $item->itemCondition; ?></div>
                <div class="info-item" id="sold-by"><strong>Sold by:</strong> <?php echo $item->itemOwner?></div>
                <div class="description-box">
                    <p><strong>Description:</strong> <?php echo $item->itemDescription; ?></p>
                </div>
                <?php if($session->isLoggedIn() && !$isOwner): ?>
                    <form action="../pages/chat.php" method="GET">
                        <input type="hidden" name="sender_id" value="<?php echo $loggedInUserId; ?>">
                        <input type="hidden" name="receiver_id" value="<?php echo $itemOwnerId ?>">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                        <button class="start-chat-button" type="submit">Start Chat</button>
                    </form>
                <?php endif; ?>
                <?php if($session->isLoggedIn() && !$isOwner): ?>
                    <form id="add-to-cart-form" action="../actions/action_cart.php" method="POST">
                        <input type="hidden" name="item_json" value='<?php echo json_encode($item); ?>'>
                        <button id="add-to-cart-Button" type="submit"> <i class="fa-solid fa-cart-plus"></i> Add to Cart  </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

<?php } ?>