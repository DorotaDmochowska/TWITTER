<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/User.php';
include_once 'src/Tweet.php';
include_once 'src/Comment.php';

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userTweets = $_POST['user'];
    $tweetsAuthor = User::loadUserById($conn, $userTweets);   
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>All Tweets</title>
    </head>
    <body>
        <a href='mainPage.php'><= Main Page</a>
        <hr>
        <form action='#' method="POST">
            <label>Choose friend: </label>
            <select name="user">
                <?php 
                $result = User::loadAllUsers($conn);
                foreach($result as $user) {
                    $userId = $user->getId();
                    echo "<option value=" . $userId . ">" . $user->getUsername() . "</option>";
                }
                ?>
            </select>
            <input type='submit' value='Show Tweets'>
        </form>
            <?php
                @$result = Tweet::loadAllTweetByUserId($conn, $userTweets);
                echo "All tweets posted by: " . @$tweetsAuthor->getUsername();
                foreach ($result as $key => $tweet) {
                    $userId = $tweet->getUserId();
                    echo "Date: " . $tweet->getCreationDate() . "<br>";
                    echo "Tweet text: " . $tweet->getText() . "<br>";
                    $tweetId = $tweet->getId();
                    $commentsToTweet = Comment::loadAllCommentsByTweetId($conn, $tweetId);
                    $number = 0;
                    foreach($commentsToTweet as $comment) {
                        $number++;
                    }
                    echo "Number of comments: " . $number . "</br>";
                }
            ?>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>