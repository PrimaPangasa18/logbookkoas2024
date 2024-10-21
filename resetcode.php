<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    
    // Execute query to reset `stase`
    mysqli_query($con, "UPDATE `admin` SET `code` = NULL WHERE `username`='$username'");
    
    echo "Stase reset for user: $username";
}
?>
