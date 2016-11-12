<?php
include_once 'src/Connection.php';
include_once 'src/Tweet.php';
include_once 'src/User.php';

session_start();
if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['tweet'])) {
        echo 'Create Tweet';
    } else {
        $text = $conn->real_escape_string(trim($_POST['tweet']));
        $userId = $_SESSION['loggedUserId'];
        $creationDate = date('Y-m-d');
        if (strlen($text) < 140) {
            $tweet = new Tweet();
            $tweet->setUserId($userId);
            $tweet->setText($text);
            $tweet->setCreationDate($creationDate);
            $tweet->saveToDB($conn);
        } else {
            echo "Tweet is too long! Maximum 140 characters.";
        }
    }
}
?>
<html>
    <head>
        <title>Main Page</title>
    </head>
    <body>
        <h1>Welcome on Twitter!</h1>
        <a href="logoutPage.php">Logout</a> ||
        <a href="editUserPage.php">My Profile</a> ||
        <a href='userTweetsPage.php'>Tweets</a> ||
        <a href="userMessagePage.php">Messages</a>
        <hr>
        <form action="#" method="POST">
            <label><b>Say something:</b></label>
            <br>
            <textarea name="tweet" class="tweetInput" placeholder="What is on your mind?" rows="10" cols="40"></textarea>
            <br>
            <input type="submit" value="Send Tweet">
            <br>
            <p><b>Old Tweets:</b></p>
                <?php
                $result = Tweet::loadAllTweets($conn);
                foreach ($result as $key => $tweet) {
                    $userId = $tweet->getUserId();
                    $user = User::loadUserById($conn, $userId);
                    echo $user->getUsername() . " post: "; 
                    echo $tweet->getCreationDate() . " ";
                    echo $tweet->getText();
                    print "<hr>";
                }
                ?>
        </form>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>