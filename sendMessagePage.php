<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/User.php';
include_once 'src/Message.php';


if (!isset($_SESSION['loggedUserId'])) {
    header('location: loginPage.php');
}
$loggedUser = intval($_SESSION['loggedUserId']);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(empty($_GET['receiverId'])) {
        //header('Location: userTweetsPage.php');
    } else {
        $messageReceiverId = intval($_GET['receiverId']);
        $receiver = User::loadUserById($conn, $messageReceiverId);
        $receiverName = $receiver->getUsername();
        if ($loggedUser == $messageReceiverId) {
            echo "It is not possible to send message to yourself! Sorry.";
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['message'])) {
        echo "It is not possible to send empty message! Sorry.";
    } else {
        $message = $_POST['message'];
        $creationDate = date('Y/m/d');
        $senderId = $loggedUser;
        $receiverId = $_GET['receiverId'];

        
        $newMessage = new Message();
        $newMessage->setReceiverId($receiverId);
        $newMessage->setSenderId($senderId);
        $newMessage->setMessage($message);
        $newMessage->setCreationDate($creationDate);
        $newMessage->setReaded(1);
        
        if ($newMessage->saveToDB($conn)) {
            header("userMessagePage.php");
            echo "Message sent <a href='mainPage.php'>Main Page</a>";
            return false;
        } else {
            echo "Error: $conn->error ." . "Message cannot be send";
            var_dump($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Send Message</title>
    </head>
    <body>
        <form action="#" method="POST">
            <label><b>Send a message to : 
            <select name="user">
                <?php $result = User::loadAllUsers($conn);
                foreach($result as $user) {
                    $userId = $user->getId();
                    echo "<option value=" . $userId . ">" . $user->getUsername() . "</option>";
                } 
                ?>
                </select>
                </b></label>
            <br><br>
            <textarea name="message" placeholder="Your message:" cols="50" rows="10"></textarea>
            <br><br>
            <input type="submit" value="Send message">
        </form>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>