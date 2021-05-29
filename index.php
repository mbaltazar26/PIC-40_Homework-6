#!/usr/local/bin/php
<?php

    session_save_path(dirname(realpath(__FILE__)) . '/sessions/');
    session_name('Demo'); // name the session
    session_start(); // start a session
    $_SESSION['loggedin'] = false; // have not logged in

    /*
    This function validates an email and sets the $_SESSION token to true
    if it does not exist, registering them in and sending them a validation email.
    Otherwise it flags $email_r_error as true.
    @param string $email the email the user entered
    @param boolean $email_r_error the error flag to possibly change
    */

    function validateREmail($email, &$email_r_error)
    {
        $fin = fopen('accounts.txt', 'r'); // open file to read
        $true_r_email = fgets($fin); // get the line
        while(!feof($fin)) // while still more to read 
        { 
            $line = fgets($fin);
            $startOfLine = explode("\t", $line);
            if ($startOfline[0] === $email)       // Exists in the accounts file
            {
                $email_r_error = true;  // FBI, we got hium
            }
        }
        fclose($fin); // close the file
    }

    /*
    This function validates an email and sets the $_SESSION token to true
    if it exists, logging them in and sending them to the welcome page.
    Otherwise it flags $email_error as true.
    @param string $email the email the user entered
    @param boolean $email_error the error flag to possibly change
    */

    function validateEmail($email, &$email_error)
    {
        $fin = fopen('accounts.txt', 'r'); // open file to read
        $found = false;
        while(!feof($fin)) // while still more to read 
        { 
            $line = fgets($fin);
            $startOfLine = explode("\t", $line);
            if ($startOfline[0] === $email)       // Exists in the accounts file
            {
                $found = true;  // FBI, we got him
            }
        }
        if($found === false)
        {
            $email_error = true;
        }
    }

    /*
    This function validates a password and sets the $_SESSION token to true
    if it is correct, logging them in and sending them to the welcome page.
    Otherwise it flags $password_)error as true.
    @param string $password the password the user entered
    @param boolean $password_error the error flag to possibly change
    */

    function validatePassword($password, $email, &$password_error)
    {
        $fin = fopen('accounts.txt', 'r'); // open file to read
        $found = false;
        while(!feof($fin)) // while still more to read 
        { 
            $line = fgets($fin);
            $startOfLine = explode("\t", $line);
            if ($startOfLine[0] === $email)       // Exists in the accounts file
            {
                if ($startOfLine[1] === $password)
                {
                    $found = true;
                }
            }
        }
        if($found === false)
        {
            $password_error = true;
        }
    }

    $email_error = false;
    $password_error = false;
    $email_r_error = false;
    if (isset($_POST['LogIn']))     // For log in only 
    {
        if ($email_error === false)     // if email is valid
        {
            if ($password_error===false)    // if password is right
            {
                $_SESSION['loggedin'] = true;
                header('Location: welcome.php');
            }
        }
        else if(isset($_POST['email']))     // if the email is wrong 
        { // if something was posted
            validateEmail($_POST['email'], $email_error); // check it 
        }
        else if(isset($_POST['password']))      // if the password is wrong 
        { // if something was posted
            validatePassword($_POST['password'], $_POST['email'], $password_error); // check it 
        }
    }
    else if (isset($_POST['Register'])) // For register only 
    {
        if($email_r_error === false)
        {
            $fin = fopen('unverified.txt', 'r'); // open file to read
            $secret = hash('md2', $_POST['password']);
            fwrite($fin, $_POST['email']);
            fwrite($fin, "\t");
			fwrite($fin, $_POST['password']);
			fwrite($fin, "\t");
			fwrite($fin, $secret);
			fwrite($fin, "\n");
			fclose($fin);
            // The message
            $message = "Validate by clicking here: https://www.pic.ucla.edu/~mbaltazar26/HW6/validate.php?v=" + $secret;
            // Send
            mail($_POST['email'], 'validation', $message);
            echo "<p>Please check your email.</p>";
        }
        else if(isset($_POST['email']))
        { // if something was posted
            validateEmail($_POST['email'], $email_r_error); // check it 
        }
    }
?>

<!DOCTYPE html> 
<html lang="en"> 
    <head>
        <title>Login Page</title> 
    </head>
    <body> 
        <main>
            <form method = "post" action ="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for= "email">Email Address:</label> 
                <input type="email" id="email" name="email" required> <br>
                <label for="password">Password (must be at least 6 letters or digits):</label>
                <input type="password" id="password" name="password" pattern = "[A-za-z0-9]{6,}" required> <br>
                <input type = "submit" value = "Register" name = "Register">
                <input type = "submit" value = "LogIn" name = "Log In"> 
                <?php 
                    if ($email_r_error)
                    {   // email already exits
                        echo "<p>Already registered. Please log in.</p>";
                    }
                    if($email_error) 
                    { // wrong email 
                        echo "<p>No such email adress exists. Please register or validate.</p>";
                    } 
                    if($password_error) 
                    { // wrong password 
                        echo "<p>Your password is invalid.</p>";
                    } 
                ?>
            </form> 
        </main>
    </body> 
</html>