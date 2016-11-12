<?php
session_start();

include_once 'src/Connection.php';
include_once 'src/Tweet.php';
include_once 'src/Comment.php';
include_once 'src/User.php';

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: loginPage.php');
} else {
        if (!empty($_GET['tweet_id'])) {
            $tweetId = $_GET['tweet_id'];
            $loadedTweet = Tweet::loadTweetById($conn, $tweetId);
            $tweet = $loadedTweet->getText();
            $tweetAuthorId = $loadedTweet->getUserId();
            $tweetCreationDate = $loadedTweet->getCreationDate();
            $tweetAuthor = $loadedTweet->username;    
        }
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['comment'])) {
            $comment = $_POST['comment'];
            if (strlen($comment) > 60) {
                echo "Comment too long. Maximum 60 characters.";
            } else {
                $creationDate = date("Y/m/d");
                $userId = $_SESSION['loggedUserId'];
                $tweetId = $_GET['tweet_id'];

                $newComment = new Comment();
                $newComment->setUserId($userId);
                $newComment->setTweetId($tweetId);
                $newComment->setCreationDate($creationDate);
                $newComment->setComment($comment);

                if ($newComment->saveToDB($conn)) {
                    echo "Comment has been added";
                } else {
                    echo "Error: $conn->error." . "It is not possible to add comment. Sorry";
                }
            }
        }
    }
}
?>

<!DOCTYPE>
<html>
    <head>
        <title>More Tweets</title>
    </head>
    <body>
            <a href="main.php"><= Main Page</a>
            <hr>
        <h2>Info:</h2>
        <div class='tweetInfo'>
            <p>Tweet text: <?php echo @$tweet?></p>
            <p>Tweet author: <?php echo @$tweetAuthor ?></p>
            <p>Creation date: <?php echo @$tweetCreationDate ?></p>
        </div>
        <form action='#' method="POST">
            <h2>Comments added to this post:</h2>
            <div class='tweetComments'>
                <?php
                $result = Comment::loadAllCommentsByTweetId($conn, @$tweetId);
                echo "<div>";
                foreach ($result as $key => $comment) {
                    echo "<div>" . $comment->getCreationDate() . "</div>";
                    echo "<div>" . $comment->getComment() . "</div>";
                    $CommentAuthorId = $comment->getUserId();
                    $commentAuthor = User::loadUserById($conn, $CommentAuthorId);
                    echo "<div>Comment form:" . $commentAuthor->getUsername() . "</div><hr>";
                }
                echo "</div>";
                ?>
            </div>
            <br><br>
            <textarea name="comment" placeholder="Comment" cols="30" rows="6"></textarea>
            <br>
            <input type='submit' value="Send Comment">
        </form>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>


