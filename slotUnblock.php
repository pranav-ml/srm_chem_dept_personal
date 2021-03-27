<!-- as the title suggest, it unblocks the slot blocked by the admin/ super user -->


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
             $slot_c=$hrtem_map[$slot];
        }
        if($id==2)
        {
             $slot_c=$mrs_map[$slot]; 
        }
        if($id==3)
        {
             $slot_c=$xrd_map[$slot];
        }
        if($id==5)
        {
             $slot_c=$hrsem_map[$slot];
        }
        if($id==6)
        {
             $slot_c=$teecan_map[$slot];
        }
        if($id==7)
        {
             $slot_c=$biorad_map[$slot];
        }
        if($id==8)
        {
             $slot_c=$syngene_map[$slot];
        }
        if($id==9)
        {
             $slot_c=$toc_map[$slot];
        }
        if($id==10)
        {
             $slot_c=$hrmspectro_map[$slot];
        }
        if($id==19)
        {
             $slot_c=$pcr_map[$slot];
        }
 
/*Code to generate the slot time string. DO NOT EDIT!*/



// Slot unblock query 0 is inserted into the selected. Setting the slot to 1 results in its unblocking 
$slot_block=$mysqli->query("UPDATE $table_name SET $slot_c=1 WHERE slot_date='$date'");
if($slot_block)
{
	echo '<br><br><br><br>';	
	echo '<p align="center" style="color:red;">Slot unblocked successfully!</p>';
	echo '<pre align="cnter"> The Details of the unblocked slot are:
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