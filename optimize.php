<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');
$pass=0;
$user=1;
if(!isset($_SESSION["type"])) // Not even a user tries to come to the page.
{
	$pass=0;
	$user=0;
}

if($user==1 && $_SESSION["type"]!="superuser") // A Normal user tries to come to the page.
{
  $pass=0;
}

if($user==1 && $_SESSION["type"]=="superuser") // An superadmin user is detected then allow him
{
	$pass=1;
}

if($pass===1) // if pass===1 only then
{
$res = $mysqli->query("SHOW TABLE STATUS WHERE Data_free > 10");
$count=0;
 while($row =$res->fetch_assoc())
 {
  $mysqli->query('OPTIMIZE TABLE ' . $row['Name']);
  $count++;
 }
echo $count;echo ' tables optimized successfully';
 header("Refresh: 5; url=index.php");
}

else // Else Security breach attempt.
{
  echo '<p align="center"><img src="images/hack.jpg"></img></p>';
  echo '<footer><p align="center"> /Developed by Department of CSE, SRMIST, KTR/</p></footer>';
  header("Refresh: 3; url=index.php");
}

?>