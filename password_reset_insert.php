<?php
include 'php/config.php';

if(isset($_POST['pwd1']) && !empty($_POST['pwd1']) AND isset($_POST['pwd2']) && !empty($_POST['pwd2']) AND isset($_POST['email']) && !empty($_POST['email']))
{
	$pwd=mysqli_real_escape_string($mysqli,$_POST['pwd1']);
	$email=mysqli_real_escape_string($mysqli,$_POST['email']);
	$hpwd=password_hash($pwd,PASSWORD_BCRYPT);

	$q=$mysqli->prepare("UPDATE users SET pwd=? WHERE email=?");
	$q->bind_param('ss',$hpwd,$email);
	if(!$q->execute())
	{
  	echo 'SQL ERROR!';
	}
	echo 'PASSWORD UPDATED SUCCESSFULLY! Please Login Again.';
	header("Refresh:3; url=login.php");
}
else
{
	echo 'Unauthorized Access';
}