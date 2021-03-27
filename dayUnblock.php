<!-- as the title suggest, it unblocks the day blocked by the admin/ super user -->


<?php
include ('php/config.php');


/*------POST ARRAY VARIABLES HERE-----*/
$slot=$_POST['slot'];
$date=$_POST['date'];
$id=$_POST['id'];
$admin_id=$_POST['admin_id'];

/*------POST ARRAY VARIABLES HERE-----*/
$day_unblock_possible=1;

$q1=$mysqli->query("SELECT table_name from link WHERE pid=$id");
$q1_result=$q1->fetch_object();
$table_name=$q1_result->table_name;

$q2=$mysqli->query("SELECT * FROM $table_name WHERE slot_date='$date'");
$q2_result=$q2->fetch_object();
if($q2_result->slot_9==1 && $q2_result->slot_10==1 && $q2_result->slot_11==1 && $q2_result->slot_12==1 && $q2_result->slot_13==1 && $q2_result->slot_14==1 && $q2_result->slot_15==1 && $q2_result->slot_16==1 && $q2_result->slot_17==1 && $q2_result->slot_18==1)
{
    echo "All the slots are already unblocked!";
    $day_unblock_possible=0;
}

if($day_unblock_possible==1)
{
	$slot_unblock=$mysqli->query("UPDATE $table_name SET slot_9=1, slot_10=1, slot_11=1, slot_12=1, slot_13=1, slot_14=1,slot_15=1, slot_16=1, slot_17=1, slot_18=1 WHERE slot_date='$date'");
	if($slot_unblock)
	{
		echo '<br><br><br><br>';	
		echo '<p align="center" style="color:red;">Day unblocked successfully!</p>';
	}
	else
	{
		echo '<br><br><br><br>';	
	echo '<p align="center" style="color:red;">Database Error! Contact the Database Administrator.</p>';
	}
}
// else
// {
// 	echo '<br><br><br><br>';	
// 	echo '<p align="center" style="color:red;">Failed! The day has prior user bookings or prior slots that are blocked by other device Administrators </p>';
// 	echo '<p align="center" style="color:red;">You can only block individual slots for the instrument.</p>';

// }

?>