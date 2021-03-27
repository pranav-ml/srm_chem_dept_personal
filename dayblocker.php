<?php
include ('php/config.php');


/*------POST ARRAY VARIABLES HERE-----*/
$slot=$_POST['slot'];
$date=$_POST['date'];
$id=$_POST['id'];
$admin_id=$_POST['admin_id'];

/*------POST ARRAY VARIABLES HERE-----*/
$day_block_possible=0;

$q1=$mysqli->query("SELECT table_name from link WHERE pid=$id");
$q1_result=$q1->fetch_object();
$table_name=$q1_result->table_name;

$q2=$mysqli->query("SELECT * FROM $table_name WHERE slot_date='$date'");
$q2_result=$q2->fetch_object();
if($q2_result->slot_9==1 && $q2_result->slot_10==1 && $q2_result->slot_11==1 && $q2_result->slot_12==1 && $q2_result->slot_13==1 && $q2_result->slot_14==1 && $q2_result->slot_15==1 && $q2_result->slot_16==1 && $q2_result->slot_17==1 && $q2_result->slot_18==1)
{
	$day_block_possible=1;
}

if($day_block_possible==1)
{
	$slot_block=$mysqli->query("UPDATE $table_name SET slot_9='$admin_id',slot_10='$admin_id', slot_11='$admin_id', slot_12='$admin_id', slot_13='$admin_id', slot_14='$admin_id',slot_15='$admin_id', slot_16='$admin_id', slot_17='$admin_id', slot_18='$admin_id' WHERE slot_date='$date'");
	if($slot)			//changed from $slot to $slot_block
	{
		echo '<br><br><br><br>';	
		echo '<p align="center" style="color:red;">Day blocked Successfully!</p>';
	}
	else
	{
		echo '<br><br><br><br>';	
	echo '<p align="center" style="color:red;">Database Error! Conatct the Database Administrator.</p>';
	}
}
else
{
	echo '<br><br><br><br>';	
	echo '<p align="center" style="color:red;">Failed! The day has prior user bookings or prior slots that are blocked by other device Administrators </p>';
	echo '<p align="center" style="color:red;">You can only block individual slots for the instrument.</p>';

}
