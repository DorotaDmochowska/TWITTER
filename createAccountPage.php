
<?php
include_once 'src/Connection.php';
include_once 'src/User.php';

if (($_SERVER['REQUEST_METHOD']) == 'POST') {
    if (isset($_POST['email']) && trim($_POST['email']) != " " &&
        isset($_POST['password']) && trim($_POST['password']) != " " &&
        isset($_POST['userName']) && trim($_POST['userName']) != " ") {
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['userName'];

        $user = User::getUserByEmail($conn, $email);
        if ($user) {
            echo "This e-mail is already taken. Try different on.";
        } else if ($password == $password2) {
            $user = new User();
            $user->setEmail($email);
            $user->setHashedPassword($password);
            $user->setUserName($username);

            if ($user->saveToDB($conn)) {
                header("Location: loginPage.php");
            }
        }
    } else {
        echo "Enter all required data";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Create Your Account</title>
    </head>
    <body>
        <form action="" method="POST">
            <lable><h3><b>Your E-mail Address: </h3></b></lable>
            <input type="text" name ="email" placeholder="E-mail address: ">
            <lable><h3><b>Your Username: </h3></b></lable>
            <input type="text" name ="username" placeholder="Username: ">
            <lable><h3><b>Your Password: </h3></b></lable>
            <input type="password" name ="password" placeholder="Password: ">
            <br>
            <br>
            <input type="submit" value="Create account">
            <br>
        </form>
        <hr>
        <p><a href="loginPage.php">Log in</a></p>
    </body>
</html

<?php
$conn->close();
$conn = null;
?>