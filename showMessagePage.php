<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/Message.php';
include_once 'src/User.php';

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messageId = $_GET['messageId'];
    $loadedMessage = Message::loadMessageById($conn, $messageId);
    $messageText = $loadedMessage->getMessage();
    $messageReceiver = $loadedMessage->username;

    $messageSenderId = $loadedMessage->getSenderId();
    $messageAuthor = User::loadUserById($conn, $messageSenderId);
    $messageAuthorName = $messageAuthor->getUsername();

    $userId = $_SESSION['loggedUserId'];
    $user = User::loadUserById($conn, $userId);
    $authorName = $user->getUsername();
    $authorId = $loadedMessage->getSenderId();

    if ($userId != $authorId) {
        $loadedMessage->changeStatus($conn, $messageId);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Messages</title>
    </head>
    <body>
        <a href='userMessagesPage.php'><= Your Messages</a>
        <h2>Message info:</h2>
        <p><b>Message form: </b><?php echo $messageAuthorName ?></p>
        <p><b>Message to: </b><?php echo $messageReceiver ?></p>
        <p><?php echo $messageText ?></p>
        
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>