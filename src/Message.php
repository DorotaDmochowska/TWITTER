<?php

/*
 * CREATE TABLE Message(
    id int NOT NULL AUTO_INCREMENT,
    sender_id int NOT NULL,
    receiver_id int NOT NULL,
    message text NOT NULL,
    readed text NOT NULL,
    creation_date date NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(sender_id) REFERENCES users(id),
    FOREIGN KEY(receiver_id) REFERENCES users(id)
    );
 */

class Message {
    private $id;
    private $senderId;
    private $receiverId;
    private $message;
    private $readed;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->senderId = "";
        $this->receiverId = "";
        $this->message = "";
        $this->readed = 0;
        $this->creationDate = "";
    }
    
    function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    function setReceiverId($receiverId) {
        $this->receiverId = $receiverId;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setReaded($readed) {
        $this->readed = $readed;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function getId() {
        return $this->id;
    }

    function getSenderId() {
        return $this->senderId;
    }

    function getReceiverId() {
        return $this->receiverId;
    }

    function getMessage() {
        return $this->message;
    }

    function getReaded() {
        return $this->readed;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    static public function loadMessagesByReceiverId (mysqli $conn, $receiverId) {
        $sql = "SELECT Message.id, Message.sender_id, Message.receiver_id, Message.message, Message.creation_date, Message.readed, users.username FROM Message LEFT JOIN users ON Message.sender_id = users.id WHERE Message.receiver_id = '$receiverId'";
        $result = $conn->query($sql);
        $messagesByReceiver = [];
        
        if ($result != false && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->message = $row['message'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->readed = $row['readed'];
                $loadedMessage->username = $row['username'];
                
                $messagesByReceiver[] = $loadedMessage;
            }
            return $messagesByReceiver;
        }
        return $messagesByReceiver;
    }

    static public function loadMessagesBySenderId(mysqli $conn, $senderId) {
        $sql = "SELECT Message.id, Message.sender_id, Message.receiver_id, Message.message, Messages.creation_date, Message.readed, users.username FROM Message LEFT JOIN users ON Message.receiver_id = users.id WHERE Message.sender_id = '$senderId'";
        $result = $conn->query($sql);
        $messagesBySender = [];

        if ($result != false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->message = $row['message'];
                $loadedMessage->creationDate = $row['creation_date'];
                $loadedMessage->readed = $row['readed'];
                $loadedMessage->username = $row['username'];

                $messagesBySender[] = $loadedMessage;
            }
            return $messagesBySender;
        }
        return $messagesBySender;
    }
    
    public function saveToDB (mysqli $conn) {
        $sql = "INSERT INTO Message (sender_id, receiver_id, message, creation_date, readed) VALUES ('$this->senderId', '$this->receiverId', '$this->message', '$this->creationDate', '$this->readed')";
        $result = $conn->query($sql);
        if ($result != false) {
            $this->id = $conn->insert_id;
            return true;
        } else {
            return false;
        }
    }
    
    public function changeReaded(mysqli $conn, $messageId) {
        $sql = "UPDATE Message SET Message.readed= '0' WHERE Message.id = '$messageId'";
        $result = $conn->query($sql);
    }
    
    static public function loadMessageById(mysqli $conn, $messageId) {
        $sql = "SELECT Message.id, Message.sender_id, Message.receiver_id, Message.message, Message.creation_date, Message.readed, users.username FROM Message LEFT JOIN users ON Message.receiver_id = users.id WHERE Message.id = '$messageId'";
        $result = $conn->query($sql);
        if ($result != false && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->receiverId = $row['receiver_id'];
            $loadedMessage->message = $row['message'];
            $loadedMessage->creationDate = $row['creation_date'];
            $loadedMessage->readed = $row['readed'];
            $loadedMessage->username = $row['username'];
            
            return $loadedMessage;
        }
    }

}
