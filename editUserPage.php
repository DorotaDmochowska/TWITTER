<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/User.php';

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
} else {
            
    $userId = $_SESSION['loggedUserId'];
    $loadedUser = User::loadUserById($conn, $userId);
    $loadedUserName = $loadedUser->getUsername();
    $loadedUserEmail = $loadedUser->getEmail();
    $loadedUserId = $loadedUser->getId();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        if (isset($_POST['username']) && trim($_POST['username'] != '')) {
            $username = $conn->real_escape_string($_POST['username']);
            $loadedUser->setUserName($username);
            echo "Username has been changed";
        }
        if (isset($_POST["newemail"]) && trim($_POST["newemail"] != "")) {
            $newemail = $conn->real_escape_string($_POST["newemail"]);
            $user = User::getUserByEmail($conn, $newemail);
            if ($user) {
                echo "This e-mail addres is already taken. Try different one.";
            } else {
                $loadedUser->setEmail($newemail);
                echo "Email has been changed";
            }
        }
        if (isset($_POST["newpassword"])  && trim($_POST["newpassword"] != '')) {
            $newpassword = $conn->real_escape_string($_POST["newpassword"]);
                $loadedUser->setHashedPassword($newpassword);
                echo "Password has been changed";
            }
        }        
        if ($loadedUser->saveToDB($conn)) {
        } else {
            echo 'Error. Update is not possible';
            header('Location: loginPage.php');
        }
    }    



?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit your profile</title>
    </head>
    <body>
        <a href='mainPage.php'><= Main Page</a>
        <hr>
        <h1>About you:</h1>
        <h3>Info:</h3>
        <p><b>Name:</b> <?php echo $loadedUserName?></p>
        <p><b>Email:</b> <?php echo $loadedUserEmail?></p>
        <hr>
        <h4><lable>Change some data:</lable></h4>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" placeholder="New username:">
            <br><br>
            <label>E-mail address:</label>
            <input type="email" name="newemail" placeholder="New e-mail address:">
            <br> <br>
            <label>New password:</label>
            <input type="password" name="newpassword" placeholder="New password:">
            <br><br>
            <input type="submit" name="submit" value="Save">
        </form>
    </body>
</html>