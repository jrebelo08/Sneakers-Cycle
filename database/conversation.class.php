<?php

class Conversation {
    public $chatId;
    public $senderId;
    public $receiverId;
    public $itemId;

    public function __construct($chatId, $senderId, $receiverId, $itemId) {
        $this->chatId = $chatId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->itemId = $itemId;
    }

    static public function getUserConversations(PDO $db, $userId) {
        $stmt = $db->prepare('SELECT DISTINCT ChatId, SenderId, ReceiverId, ItemId FROM Chat WHERE SenderId = :userId OR ReceiverId = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $conversations = array();
        while ($conversation = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conversations[] = new Conversation($conversation['ChatId'], $conversation['SenderId'], $conversation['ReceiverId'], $conversation['ItemId']);
        }

        return $conversations;
    }

    static public function deleteConversationByItemId(PDO $db, $itemId) {
        $stmt = $db->prepare('DELETE FROM Chat WHERE ItemId = :itemId');
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    }
    static public function deleteConversationByuserId(PDO $db, $userId) {
        $stmt = $db->prepare('DELETE FROM Chat WHERE SenderId = :userId OR ReceiverId = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    }
}
