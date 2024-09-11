<?php

class Order {
    private $orderId;
    private $userId;
    private $orderDate;
    private $total;

    public function __construct($orderId, $userId, $orderDate, $total) {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->total = $total;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getTotal() {
        return $this->total;
    }

    public static function createOrder(PDO $db, $userId, $orderDate, $total) {
        $stmt = $db->prepare('INSERT INTO Orders (UserId, OrderDate, Total) VALUES (?, ?, ?)');
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $orderDate);
        $stmt->bindParam(3, $total);
        $stmt->execute();
        $orderId = $db->lastInsertId();
        return $orderId;
    }

    public function getOrderItems(PDO $db) {
        $stmt = $db->prepare('SELECT * FROM OrderItem WHERE OrdersId = ?');
        $stmt->bindParam(1, $this->orderId);
        $stmt->execute();
        $orderItems = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderItem = new OrderItem($row['OrderItemId'], $row['OrdersId'], $row['ItemId'], $row['Quantity']);
            $orderItems[] = $orderItem;
        }
        return $orderItems;
    }

    public function getAllOrders(PDO $db) {
        //TODO
    }
    
    
}