<?php

/*
 CREATE TABLE Comments (
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    tweet_id int NOT NULL,
    creation_date date NOT NULL,
    text varchar(255) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(tweet_id) REFERENCES Tweet(id)
    );

 */

class Comment {
    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = '';
        $this->tweetId = '';
        $this->creationDate = '';
        $this->text = '';
    }
    
    function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
        return $this;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    function setText($text) {
        $this->text = $text;
        return $this;
    }
    
    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getTweetId() {
        return $this->tweetId;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getText() {
        return $this->text;
    }
    
    static public function loadCommentById(mysqli $conn, $id) {
        $sql = "SELECT * FROM Comments WHERE id = '$id'";
        $result = $conn->query($sql);
        
        if ($result != false && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->tweetId = $row['tweet_id'];
            $loadedComment->creationDate = $row['creation_date'];
            $loadedComment->text = $row['text'];
            
            return $loadedComment;
        }
        return null;
    }
    
    static public function loadAllCommentsByTweetId(mysqli $conn, $tweetId) {
        $sql = "SELECT * FROM Comments WHERE tweet_id = '$tweetId' ORDER BY creation_date DESC";
        $result = $conn->query($sql);
        $allComments = [];
        
        if ($result != false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->tweetId = $row['tweet_id'];
                $loadedComment->creationDate = $row['creation_date'];
                $loadedComment->text = $row['text'];
                
                $allComments[] = $loadedComment;
            }
            return $allComments;
        }
        return $allComments;
    }
    
    public function saveToDB(mysqli $conn) {
        if($this->id == -1) {
            $sql = "INSERT INTO Comments (user_id, tweet_id, creation_date, text) VALUES ('$this->userId', '$this->tweetId', '$this->creationDate', '$this->text')";
            
            $result = $conn->query($sql);
            if ($result) {
                $this->id = $conn->insert_id;
                return true;
            } else {
                return false;
            }
        } 
    }
    
    public function changeReaded (mysqli $conn, $messageId) {
        $sql = "UPDATE Message SET status = 1 WHERE Message.id = '$messageId'";
        $result = $conn->query($sql);
        if ($result != false) {
            return true;
        } else {
            return false;
        }
    }

}


?>
