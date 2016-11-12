<?php

/*
CREATE TABLE users (
    id int AUTO_INCREMENT,
    username varchar(255),
    hashedPassword varchar(60) NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    PRIMARY KEY (id)
    )
*/ 

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->hashedPassword = "";
        $this->email = "";
    }

    public function getId() {
        return $this->id;
    }

    public function setUserName($username) {
        $this->username = $username;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function getUsername() {
        return $this->username;
    }

    function getHashedPassword() {
        return $this->hashedPassword;
    }

    function getEmail() {
        return $this->email;
    }

    function setHashedPassword($hashedPassword) {
        $newHashedPassword = password_hash($hashedPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
        return $this;
    }

    public function saveToDB(mysqli $conn) {
        if ($this->id == -1) {
            $statement = $conn->prepare("INSERT INTO users(username, hashedPassword, email)
                    VALUES (?, ?, ?)");
            if (!$statement) {               
                return false;
            }

            $statement->bind_param("sss", $this->username, $this->hashedPassword, $this->email);
            if ($statement->execute()) {
                                
                $this->id = $statement->insert_id;
                return true;
            } else {
                echo "Statement problem. Error: $statement->error.";
            }
            return false;
        } else {
            $sql = "UPDATE users SET username = '$this->username', hashedPassword = '$this->hashedPassword', "
                    . "email = '$this->email' WHERE id = '$this->id'";
            
            $result = $conn->query($sql);
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    static public function loadUserById (mysqli $conn, $id) {
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $conn->query($sql);
        
        if ($result != FALSE && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->email = $row['email'];
            $loadedUser->hashedPassword = $row['hashedPassword'];
            $loadedUser->username = $row['username'];
            
            return $loadedUser;
        }
        return null;
    }
    
    static public function loadAllUsers(mysqli $conn) {
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        $ret = [];
        
        if($result != FALSE && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->email = $row['email'];
                $loadedUser->hashedPassword = $row['hashedPassword'];
                $loadedUser->username = $row['username'];
                
                $ret[] = $loadedUser;
            }
            
        }
        return $ret;
    }
    
    static public function loadAllUsersByUsername(mysqli $conn, $username) {
        $sql = "SELECT * FROM users WHERE username LIKE '%$username%'";
        $result = $conn->query($sql);
        $ret = [];
        
        if ($result != false && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->email = $row['email'];
                $loadedUser->hashedPassword = $row['hashedPassword'];
                $loadedUser->username = $row['username'];
                
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }
    
    public function delete(mysqli $conn) {
        if ($this->id == -1) {
            return true;
        }
        
        $sql = "DELETE FROM users WHERE id = $this->id";
        $result = $conn->query($sql);
        if ($result) {
            $this->id = -1;
            return true;
        }
        return false;
    }

    static public function logIn(mysqli $conn, $email, $password) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['hashedPassword'])) {
                $userId = $row['id'];
                return $userId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    static public function loadUserByEmail(mysqli $conn, $email) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user = new User();
            $user->setEmail($row['email']);
            $user->setHashedPassword($row['hashedPassword']);
            $user->setUserName($row['username']);
            return $user;
        } else {
            return false;
        }
    }
}
?>