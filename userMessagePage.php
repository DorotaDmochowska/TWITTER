<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/Message.php';
include_once 'src/User.php';

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
}

$userId = $_SESSION['loggedUserId'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Your Messages</title>
    </head>
    <body>
        <a href="mainPage.php"><= Main Page</a>
        <hr>
        <lable><b><h2>Send Messages:</h2></lable></b>
        <div>
                <?php
                $sentMessages = Message::loadMessagesBySenderId($conn, $userId);
                foreach ($sentMessages as $message) {
                    echo "Message: " . $message->getMessage() . "<a href='showMessagePage.php?messageId=" . $message->getId() . "<br>";
                    echo "Date: " . $message->getCreationDate() . "<br>";
                    echo "Sent to: " . $message->username . "<br>";  
                }
                ?>
        </div>
        <br><br><hr>
        <lable><b><h2>Received Messages:</h2></lable></b>
        <div>
                <?php
                $receivedMessages = Message::loadMessagesByReceiverId($conn, $userId);
                foreach ($receivedMessages as $message) {
                    echo "Message: " . $message->getMessage() . "<a href='showMessagePage.php?messageId=" . $message->getId() . "<br>";
                    echo "Date:  " . $message->getCreationDate() . "<br>";
                    echo "From: " . $message->username . "<br>";
                    $status = $message->getReaded();
                    if ($status == 1) {
                        echo "<b>New Message!</b>";
                    }
                }
                ?>
            </table>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>