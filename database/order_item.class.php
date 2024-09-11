<?php

class OrderItem {
    private $orderItemId;
    private $orderId;
    private $itemId;

    public function __construct($orderItemId, $orderId, $itemId) {
        $this->orderItemId = $orderItemId;
        $this->orderId = $orderId;
        $this->itemId = $itemId;
    }

    public function getOrderItemId() {
        return $this->orderItemId;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getItemId() {
        return $this->itemId;
    }   
    
    static public function createOrderItem(PDO $db, $orderId, $itemId, $quantity) {
        $stmt = $db->prepare('INSERT INTO OrderItem (OrdersId, ItemId, Quantity) VALUES (?, ?, ?)');
        $stmt->bindParam(1, $orderId);
        $stmt->bindParam(2, $itemId);
        $stmt->bindParam(3, $quantity);
        $stmt->execute();
    }
    
}

?>
