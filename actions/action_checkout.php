<?php
require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/order.class.php');
require_once(__DIR__ . '/../database/shipment.class.php');
require_once(__DIR__ . '/../database/shipment_user.class.php');
require_once(__DIR__ . '/../database/order_item.class.php');
require_once(__DIR__ . '/../database/conversation.class.php');

try {
    $session = new Session();
    $db = getDatabaseConnection();
    
    if ($session->getCSRF() !== $_POST['csrf']) {
        $session->addMessage('Error:', 'Request does not appear to be legitimate');
        sleep(10);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    $shippingCost = isset($_POST['shippingCost']) ? floatval(htmlentities($_POST['shippingCost'])) : 0.0;
    $totalAmount = isset($_POST['totalAmount']) ? floatval(htmlentities($_POST['totalAmount'])) : 0.0;
    
    $userId = $session->getId();

    $cart = $session->getCart();

    $orderDate = date('Y-m-d');
    $totalWithShipping = $totalAmount + $shippingCost;
    $orderId = Order::createOrder($db, $userId, $orderDate, $totalWithShipping);

    if ($orderId) {
        foreach ($cart as $item) {
            OrderItem::createOrderItem($db, $orderId, $item->id, 1);

            $imageURL = "../uploads/" . $item->ItemImage;
            unlink($imageURL);
            $result = Conversation::deleteConversationByItemId($db,$item->id);
            Item::deleteItem($db, $item->id);
        }

        $shipmentDate = date('Y-m-d');
        $shipmentStatus = 'Pending';
        $shipmentId = Shipment::createShipment($db, $orderId, $shipmentDate, $shipmentStatus);

        $session->clearCart();
        echo "<link rel='stylesheet' type='text/css' href='../style/style.css'>";
        echo "<div class='order-summary'>";
        echo "<h1>We got your order!! </h1>";
        echo "<h2>Summary:</h2>";
        echo "<p>Total Amount: $" . $totalAmount . "</p>";
        echo "<p>Shipping Cost: $" . $shippingCost . "</p>";
        echo "<p>Shipping in: ". $shipmentDate .  "</p>";
        echo "<p>Shipping Status:". $shipmentStatus . "</p>";
        echo "<p>Total: $" . $totalWithShipping . "</p>";

        echo "<button class='print-hide' onclick='window.location.href=\"../index.php\"'>Return To the Shop</button>";
        echo "<button class='print-hide' onclick='window.print()'>Print Receipt</button>";
        echo "</div>";

        exit();
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
