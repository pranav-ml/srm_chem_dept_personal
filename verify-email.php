<?php
include 'php/config.php';

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = $mysqli->real_escape_string($_GET['email']); // Set email variable
    $hash = $mysqli->real_escape_string($_GET['hash']); // Set hash variable
      echo $email;           
    $search = $mysqli->query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active=0");
    $match  = $search->num_rows;
       //    echo $match;      
    if($match > 0){
        // We have a match, activate the account
        $mysqli->query("UPDATE users SET active=1 WHERE email='".$email."' AND hash='".$hash."' AND active=0") ;
        echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
    }else{
        // No match -> invalid url or account has already been activated.
        echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
    }
                 
}else{
    // Invalid approach
    echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
}

?>
