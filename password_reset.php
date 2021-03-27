<?php
include 'php/config.php';
include 'php/red_head.php';


if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
{
$email = $mysqli->real_escape_string($_GET['email']); // Set email variable
$hash = $mysqli->real_escape_string($_GET['hash']); // Set hash of the password
$search = $mysqli->query("SELECT email, pwd, active FROM users WHERE email='".$email."' AND pwd='".$hash."' AND active=1");
$match  = $search->num_rows;
if($match > 0){
        // We have a match, allow the user to chnage the password!

echo '<script type="text/javascript">
      function validateForm() {
    var x = document.forms["register"]["pwd1"].value;
    var y = document.forms["register"]["pwd2"].value;
    var s= x.length;
    if (x !=y) {
        alert("Password mismatch!");
        return false;
    }
    if(s<6)
    {
        alert("Please make password of atleast 6 characters");
        return false;
      }
  }
      </script>';


echo '<div class="row">
<div class= "small-8 columns">
<form name="register" action="password_reset_insert.php" method=POST onsubmit="return validateForm()"><br>
<p align="center" style="color:red;">Please Enter your new password</p>
<table>
<tr>
<td>New Password</td>
<td><input type="password" name="pwd1" required></td>
</tr>
<tr>
<td>Repeat Password</td>
<td><input type="password" name="pwd2" required></td>
</tr>
</table>
<button type="submit" name="email" value='.$email.'>Reset</button>
</form>
</div>
</div>';
    }
    else{
        // No match -> throw Error
        echo '<div class="statusmsg">The url has expired as you have already reset your password.</div>';
    }

}
else
{
	echo 'Error';
}

?>