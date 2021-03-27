<?php
include ('php/session.php');
include ('php/config.php');
include ('php/head.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$flag=-1;
if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['user-email']))
{
  $booya=mysqli_real_escape_string($mysqli,$_POST['user-email']);

  $result= $mysqli->query("SELECT * FROM users WHERE email='$booya' AND type='user'");
  $q=$result->fetch_object();
  if($q){
  $pass = $q->pwd;
  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
  try {
      //Server settings
      $mail->SMTPDebug = false;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'smtpemail';                 // SMTP username
      $mail->Password = 'smtppassword';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('noreply@srmchemdept.ac.in', 'Dept. of Chem. Admin');
      $mail->addAddress($booya, $q->fname);     // Add a recipient

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Password Reset';
      $mail->Body    = '
  <h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br> 
  Hello '.$q->fname.'!<br>
  It seems like you have forgotton your password.<br>
  Click this link to reset the password: <a href="http://scif.srmonline.net/password_reset.php?email='.$booya.'&hash='.$pass.'">Click Here</a><br>
  Note: Please ensure that you have verified your email id, the same can be done by clicking on the link provided in verification email sent by us. 
  In case you haven\'t received the verification email please check your spam folder.
  Ignore if you have not requested for a password change<br>';
      $mail->send();
      $flag=1;
  } catch (Exception $e) {
      echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
  }
//  header("location:login.php");
  }
  else
  {
    $flag=0;
  }


}


echo '<div class="row">
<div class= "small-8 columns">
<form name="frmForgot" id="frmForgot" method="post" onSubmit="return validate_forgot();">
<h1>Forgot Password?</h1>

	<div class="field-group">
	<p> Steps to recover the password:
            <br>1: Please enter the email address that you used to make the orginal account on the portal.<br>
            2: We will send you a reset link on this email addresses.<br>
            3: Use the link to enter a new password for your account.<br>
            4: Use the new password to log back into the portal.<br><br>
            If you face any issues during the recovery process please contact: <a href="scif.cse@ktr.srmuniv.ac.in?Subject=Hello">scif.cse@ktr.srmuniv.ac.in</a>
    </p>
		<div><label for="email">Enter Email Address below:</label></div>
    <form action "forgot.php" method=POST>
		<div><input type="text" name="user-email" id="user-email" class="input-field"></div>
	</div>
	<div class="field-group">
    <button type=submit style="float:right;">Submit</button>
    </form>
    </div>
    
</div>
</div>
</form>';

if($flag==1)
{
  echo '<div class="row"><div class= "small-8 columns"><p align="center" style="color:green;">Password reset link has been sent to your email address. Use it to reset the password and log back in.</p></div></div>';
}
else if($flag==0)
{
  echo '<div class="row"><div class= "small-8 columns"><p align="center" style="color:red;">This email address has no user associated with it. Are you a registered user.</p></div></div>';

}

echo '</body>
</html>';

?>
