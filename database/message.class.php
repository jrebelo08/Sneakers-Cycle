<?php

class Message {
    private $messageId;
    private $chatId; 
    private $senderId;
    private $receiverId;
    private $content;
    private $timestamp;

    public function __construct($messageId, $chatId, $senderId, $receiverId, $content, $timestamp) {
        $this->messageId = $messageId;
        $this->chatId = $chatId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->content = $content;
        $this->timestamp = $timestamp;
    }

    public function getMessageId() {
        return $this->messageId;
    }

    public function getChatId() {
        return $this->chatId;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getContent() {
        return $this->content;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }
    
    static public function sendMessage(PDO $db, $chatId, $senderId, $receiverId, $content) {
        $stmt = $db->prepare('INSERT INTO Message (ChatId, SenderId, ReceiverId, Content) VALUES (:chatId, :senderId, :receiverId, :content)');
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':senderId', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':receiverId', $receiverId, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    static public function getMessagesForChat(PDO $db, $chatId, $itemId) {
        $stmt = $db->prepare('SELECT m.MessageId, m.ChatId, m.SenderId, m.ReceiverId, m.Content, m.Timestamp 
                              FROM Message m
                              INNER JOIN Chat c ON m.ChatId = c.ChatId
                              WHERE m.ChatId = :chatId AND c.ItemId = :itemId
                              ORDER BY m.Timestamp ASC');
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
    
        $messages = array();
        while ($message = $stmt->fetchObject()) {
            $messages[] = new Message(
                $message->MessageId,
                $message->ChatId,
                $message->SenderId,
                $message->ReceiverId,
                $message->Content,
                $message->Timestamp
            );
        }
    
        return $messages;
    }

    
    static public function createChat(PDO $db, $itemId, $senderId, $receiverId) {
        $stmt = $db->prepare('INSERT INTO Chat (ItemId, SenderId, ReceiverId) VALUES (:itemId, :senderId, :receiverId)');
        $stmt->bindParam(':itemId', $itemId);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->bindParam(':receiverId', $receiverId);
        $stmt->execute();
    }

    static public function getChatIdSenderReceiver(PDO $db, $item_id, $senderId, $receiverId) {
        $stmt = $db->prepare('SELECT ChatId FROM Chat WHERE ItemId = :item_id AND ((SenderId = :senderId AND ReceiverId = :receiverId) OR (SenderId = :receiverId AND ReceiverId = :senderId))');
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->bindParam(':receiverId', $receiverId);
        $stmt->execute();
    
        $chat = $stmt->fetchObject();
        if (!$chat) {
            self::createChat($db, $item_id, $senderId, $receiverId);
            $chatId = self::getChatIdSenderReceiver($db, $item_id, $senderId, $receiverId);
            return $chatId;
        } else {
            $associatedItem = Message::getItemByChatId($db, $chat->ChatId);
            if ($associatedItem == $item_id) {
                return $chat->ChatId;
            } else {
                self::createChat($db, $item_id, $senderId, $receiverId);
                $chatId = self::getChatIdSenderReceiver($db, $item_id, $senderId, $receiverId);
                return $chatId;
            }
        }
    }
    

    static public function getUserConversations(PDO $db, $userId) {
        $stmt = $db->prepare('SELECT DISTINCT ChatId, SenderId, ReceiverId FROM Chat WHERE SenderId = :userId OR ReceiverId = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $conversations = array();
        while ($conversation = $stmt->fetchObject()) {
            $conversations[] = $conversation;
        }

        return $conversations;
    }
    
    static public function getItemOwnerId(PDO $db, $itemId) {
        $stmt = $db->prepare('SELECT SenderId, ReceiverId FROM Chat WHERE ItemId = :itemId');
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    
        $chat = $stmt->fetchObject();
        
        $itemOwnerId = Item::getOwnerId($db, $itemId);
    
        if ($chat->SenderId == $itemOwnerId) {
            return $chat->SenderId;
        } elseif ($chat->ReceiverId == $itemOwnerId) { 
            return $chat->ReceiverId;
        } else {
            return null;
        }
    }

    static public function getItemByChatId(PDO $db, $chatId) {
        $stmt = $db->prepare('SELECT ItemId FROM Chat WHERE ChatId = :chatId');
        $stmt->bindParam(':chatId', $chatId);
        $stmt->execute();
    
        $chat = $stmt->fetchObject();
        return $chat->ItemId;
    }
    
    static public function getLastSuggestedPrice(PDO $db, int $chatId, int $itemId): ?float {
        $stmt = $db->prepare('SELECT LastSuggestedPrice FROM Chat WHERE ChatId = :chatId AND ItemId = :itemId');
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['LastSuggestedPrice'] : null;
    }
    
    
}