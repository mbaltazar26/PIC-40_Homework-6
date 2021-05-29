#!/usr/local/bin/php
<?php
    session_save_path(dirname(realpath(__FILE__)) . '/sessions/');
    session_name('Demo'); // name the session
    session_start(); // start a session
    if (isset($_POST['LogOut']))
    {
        $_SESSION['loggedin'] = false; // have not logged in
        session_unset();
        session_destroy();
    }
    echo "<p> ou have logged out. </p>";
?>