<?php

session_start();

if (isset($_SESSION['loggedUserId'])) {
    unset($_SESSION['loggedUserId']);
}

print "<hr>";
print "Goodbye Friend. See you soon!";
print "Login again";

header("Location: loginPage.php");

    
$conn->close();
$conn = null;
?>
