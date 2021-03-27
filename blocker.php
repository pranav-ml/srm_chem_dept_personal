<?php
include ('php/config.php');
include ('php/data.php');
if(!empty($_POST))
{




/*------POST ARRAY VARIABLES HERE-----*/
$slot=$_POST['slot'];
$date=$_POST['date'];
$id=$_POST['id'];
$admin_id=$_POST['admin_id'];
/*------POST ARRAY VARIABLES HERE-----*/

/*-----------*/


$q1=$mysqli->query("SELECT table_name from link WHERE pid=$id");
$q1_result=$q1->fetch_object();
$table_name=$q1_result->table_name;

$q2=$mysqli->query("SELECT * from products WHERE id=$id");
$q2_result=$q2->fetch_object();

     if($id==1)
     {
          $slot_c=$autosorbiq_map[$slot];
     }

 
/*Code to generate the slot time string. DO NOT EDIT!*/



// Slot Block query 0 is inserted into the selected. 0 is for the superuser.
$slot_block=$mysqli->query("UPDATE $table_name SET $slot_c='$admin_id' WHERE slot_date='$date'");
if($slot)
{
	echo '<br><br><br><br>';	
	echo '<p align="center" style="color:red;">Slot blocked Successfully!</p>';
	echo '<pre align="cnter"> The Details of the booked slot are:
	Date: '.$date.'			Instrument: '.$q2_result->product_name.'			Slot Time: '.$slot.'   </pre>';

}
else
{
	 echo "Fail";
}
}
 else
{
    echo '<p align="center"><img src="images/hack.jpg"></img></p>';

}

?>