<?php
session_start();

require_once 'src/Connection.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['email']) && trim($_POST['email']) != "" &&
        isset($_POST['password']) && trim($_POST['password'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        if ($userId = User::logIn($conn, $email, $password)) {
            $_SESSION['loggedUserId'] = $userId;
            $_SESSION['loggedUserEmail'] = $email;
            
            header("Location: mainPage.php");
        } else {
            echo("Invalid data. Try again.");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>
    <form action="" method="POST">
        <lable><h3><b>Enter Your E-mail Address: </h3></b></lable>
        <input type="text" name ="email" placeholder="E-mail address: ">
        <lable><h3><b>Enter Your Password: </h3></b></lable>
        <input type="password" name ="password" placeholder="Password: "><br>
        <br>
        <input type="submit" value="Login">
    </form>
    <hr>
    <p>You don't have account yet? No problem!</p>
    <p><a href="createAccountPage.php">Create Account</a></p>
</body>
</html>
