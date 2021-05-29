#!/usr/local/bin/php
<?php
    $v = $_GET['v'];
    function validate()
    {
        $fin = fopen('unverified.txt', 'r'); // open file to read
        while(!feof($fin)) // while still more to read 
        { 
            $line = fgets($fin);
            $startOfLine = explode("\t", $line);
            if ($startOfLine[2] === $v)       // Found in the unverified file
            {
                $fin2 = fopen('accounts.txt', 'r');
                fwrite($fin2, $startOfLine[0]);     // add email to accounts.txt
                fwrite($fin2, "\t");
			    fwrite($fin2, $startOfLine[1]);     // add password to accounts.txt
			    fwrite($fin2, "\n");
			    fclose($fin2);
            }
        }
        fclose($fin);
        echo "<p> You have validated your account! </p>"
    }

?>