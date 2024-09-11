<?php

class Shipment {
    private $shipmentId;
    private $orderId;
    private $shipmentDate;
    private $shipmentStatus;

    public function __construct($shipmentId, $orderId, $shipmentDate, $shipmentStatus) {
        $this->shipmentId = $shipmentId;
        $this->orderId = $orderId;
        $this->shipmentDate = $shipmentDate;
        $this->shipmentStatus = $shipmentStatus;
    }

    public function getShipmentId() {
        return $this->shipmentId;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getShipmentDate() {
        return $this->shipmentDate;
    }

    public function getShipmentStatus() {
        return $this->shipmentStatus;
    }

    public static function createShipment(PDO $db, $orderId, $shipmentDate, $shipmentStatus) {
        $stmt = $db->prepare('INSERT INTO Shipment (OrdersId, ShipmentDate, ShipmentStatus) VALUES (?, ?, ?)');
        $stmt->bindParam(1, $orderId);
        $stmt->bindParam(2, $shipmentDate);
        $stmt->bindParam(3, $shipmentStatus);
        $stmt->execute();
        $shipmentId = $db->lastInsertId();
        return $shipmentId;
    }
}