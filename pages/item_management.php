<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../session/session.php');

$db = getDatabaseConnection();

$items = Item::getAllItemsFromDatabase($db);

?>
<head>
    <title>Item List</title>
    <link rel="stylesheet" type="text/css" href="../style/management.css">
</head>
<body class="item-mng-body">

    <h1>Item List</h1>

    <?php if (!empty($items)): ?>
        <ul class="item-list">
            <?php foreach ($items as $item): ?>
                <form action="edit_item.php" method="post">
                    <input type="hidden" name="data" value="<?= htmlspecialchars(json_encode($item)) ?>">
                    <li class="item-list"><button type="submit" class="item-link"><?= htmlspecialchars($item->itemName) ?></button></li>
                </form>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>
</body>
</html>
