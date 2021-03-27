<?php
//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])){
  header("location:index.php");
}
include 'php/config.php';
include 'php/head.php';



if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['reason']))
{
	$reason=mysqli_real_escape_string($mysqli,$_POST['reason']);
	$on=$_POST['submit'];
	$booya=$mysqli->query("UPDATE orders SET cancel_reason='$reason' WHERE order_id=$on");
	if($booya)
	{
		echo 'Request Sent';
	}
	else{
		echo 'SQL Error';
	}
}
else
{
	$on=$_POST["order_no"];
}

$q1=$mysqli->query("SELECT product_code, product_name,date_of_order,slot_time FROM orders WHERE order_id=$on");
if($q1)
{
	$q1_res=$q1->fetch_object();
	 echo '<div class="row" style="margin-top:10px;">';
	 echo '<div class="large-12">';
	 echo '<div class="large-6">';
	 echo '<p style="color:red;font-size:150%;text-align:center;">You are Cancelling the following order</p>';
	 echo '<p style="text-align:center;font-size:120%;">Order ID ->'.$on.'</p>';
	 echo '<table>';
	 echo '<tr>';
	 echo '<th>Product Code</th>';
	 echo '<th>Product Name</th>';
	 echo '<th>Date of Order</th>';
	 echo '<th>Slot Time</th>';
	 echo '</tr>';
	 echo '<tr>';
	 echo '<td>'.$q1_res->product_code.'';
	 echo '<td>'.$q1_res->product_name.'';
	 echo '<td>'.$q1_res->date_of_order.'';
	 echo '<td>'.$q1_res->slot_time.'';
	 echo '</table>';
	 echo '</div>';

	 echo '<div class="large-6">';
	 echo '<form action="cancelit.php" method=POST>';
	 echo '<pre>			Please Note
A Request will be generated to the Admin to approve the
cancellation. Please Give a valid Reason to cancel the booking</pre>';
echo '<br>';
	 echo '<input type="text" autocomplete="off" name="reason" placeholder="Enter your reason here" required>';

	 echo '<button type="submit" name="submit" value='.$on.' id="button1">Click to Confirm</button>';
	 echo '</form>';

	 echo '</div>';



}
else
{
	echo 'SQL Error';
}

 

?>