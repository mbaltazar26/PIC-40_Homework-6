#!/usr/local/bin/php
<?php
    session_save_path(dirname(realpath(__FILE__)) . '/sessions/'); 
    session_name('Demo'); // resume Demo session
    session_start(); // start a session
?>

<!DOCTYPE html> 
<?php
// either no session or not logged in
if(!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) 
{ ?> 
    <html>
        <head> 
            <title>Unwelcome</title>
        </head> 
        <body>
            <p>Go back and log in.</p> 
        </body>
    </html> 
<?php 
}
else // then they are logged in for real
{ 
?> 
    <html>
        <head> 
            <title>Welcome</title>
        </head> 
        <body>
            <p>Welcome!</p> 
            <?php
                echo "<p>Your email is " + $_POST['email'] + "</p>";
            ?>
        <form> <input type = "submit" value = "LogOut" name = "Log Out"> </form>
        <?php
        if (isset($_POST['LogOut']))
        {
            header('Location: logout.php');
        }
        </body>
    </html> 
<?php 
}
?>