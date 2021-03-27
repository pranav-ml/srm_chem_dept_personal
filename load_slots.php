<?php
include ('php/config.php');
include ('php/data.php');
	$admin_id=$_POST['admin_id'];
	$instrument_id=(int)$_POST['id'];
	$date=$_POST['date'];
	$sql1=$mysqli->query("SELECT table_name FROM link WHERE pid=$instrument_id");
	$sql1_result=$sql1->fetch_object();
	$table_name=$sql1_result->table_name;

	$sql3=$mysqli->query("SELECT slot_date FROM $table_name WHERE slot_date='$date'");
    if(!$sql3->fetch_object())
    {
        $sql3=$mysqli->query("INSERT INTO $table_name (slot_date) VALUES ('$date')");
	}
	
	//block day
    echo '<script> $(document).ready(function(){
            $("#button2").click(function(){
            var slot = $("#slot_selector").find(":selected").val();
            var div = document.getElementById("dom-target");
    		var date = div.textContent;
    		var div2 = document.getElementById("dom-target2");
    		var id = div2.textContent;
    		var div3 = document.getElementById("dom-target3");
    		var admin_id = div3.textContent;
			$("#data2").load("dayblocker.php",{slot: slot, date: date, id: id, admin_id: admin_id});
            });
		});</script>';
	
	//unblock day
	echo '<script> $(document).ready(function(){
			$("#button4").click(function(){
			var slot = $("#slot_selector").find(":selected").val();
			var div = document.getElementById("dom-target");
			var date = div.textContent;
			var div2 = document.getElementById("dom-target2");
			var id = div2.textContent;
			var div3 = document.getElementById("dom-target3");
			var admin_id = div3.textContent;
			$("#data2").load("dayUnblock.php",{slot: slot, date: date, id: id, admin_id: admin_id});
			});
		});</script>';
	
	//block slot
	echo '<script> $(document).ready(function(){
			$("#button1").click(function(){
			var slot = $("#slot_selector").find(":selected").val();
			var div = document.getElementById("dom-target");
			var date = div.textContent;
			var div2 = document.getElementById("dom-target2");
			var id = div2.textContent;
			var div3 = document.getElementById("dom-target3");
			var admin_id = div3.textContent;
			$("#data2").load("blocker.php",{slot: slot, date: date, id: id, admin_id: admin_id});
			});
		});</script>';

	//unblock slot
	echo '<script> $(document).ready(function(){
			$("#button3").click(function(){
			var slot = $("#slot_selector").find(":selected").val();
			var div = document.getElementById("dom-target");
			var date = div.textContent;
			var div2 = document.getElementById("dom-target2");
			var id = div2.textContent;
			var div3 = document.getElementById("dom-target3");
			var admin_id = div3.textContent;
			$("#data2").load("slotUnblock.php",{slot: slot, date: date, id: id, admin_id: admin_id});
			});
		});</script>';

	// Display the slots available for booking by the admin!
	$sql2=$mysqli->query("SELECT * FROM $table_name WHERE slot_date='$date'");
	if($sql2)
	{
	
		if($sql2->num_rows)
		{
			$slots=$sql2->fetch_object();
		
			echo '<div class="row" style="margin-top:10px;">';
    		echo '<div class="small-16 columns">';
			echo '<table>';
			echo '<tr>';
			echo '<th>Available Slots</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><select id="slot_selector">';

			// if($instrument_id==5)
			// {
			// 	if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_9.'">'.$hrsem_slot_9.'</option>';}
            //       else if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_9.'">'.$mrs_slot_9.'</option>';}

            //       if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_10.'">'.$hrsem_slot_10.'</option>';}
            //       else if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_10.'">'.$mrs_slot_10.'</option>';}

            //       if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_11.'">'.$hrsem_slot_11.'</option>';}
            //       else if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_11.'">'.$mrs_slot_11.'</option>';}

            //       if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_12.'">'.$hrsem_slot_12.'</option>';}
            //       else if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_12.'">'.$mrs_slot_12.'</option>';}

            //       if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_13.'">'.$hrsem_slot_13.'</option>';}
            //       else if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_13.'">'.$mrs_slot_13.'</option>';}

            //       if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_14.'">'.$hrsem_slot_14.'</option>';}
            //       else if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_14.'">'.$mrs_slot_14.'</option>';}

            //       if($slots->slot_15!=0 && $slots->slot_15!=2 && $slots->slot_15!=3 && $slots->slot_15!=4 && $slots->slot_15<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_15.'">'.$hrsem_slot_15.'</option>';}
            //       else if($slots->slot_15!=0 && $slots->slot_15!=2 && $slots->slot_15!=3 && $slots->slot_15!=4 && $slots->slot_15<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_15.'">'.$mrs_slot_15.'</option>';}

            //       if($slots->slot_16!=0 && $slots->slot_16!=2 && $slots->slot_16!=3 && $slots->slot_16!=4 && $slots->slot_16<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_16.'">'.$hrsem_slot_16.'</option>';}

            //       if($slots->slot_17!=0 && $slots->slot_17!=2 && $slots->slot_17!=3 && $slots->slot_17!=4 && $slots->slot_17<1001 && $instrument_id==5) {echo' <option value="'.$hrsem_slot_17.'">'.$hrsem_slot_17.'</option>';}
			// }

			if($instrument_id==5)
			{
					if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=838 && ($instrument_id==5) ) {echo' <option value="'.$hrsem_slot_9.'">'.$hrsem_slot_9.'</option>';}
					if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=838 && ($instrument_id==5)) {echo' <option value="'.$hrsem_slot_10.'">'.$hrsem_slot_10.'</option>';}
					if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=838 && ($instrument_id==5)) {echo' <option value="'.$hrsem_slot_11.'">'.$hrsem_slot_11.'</option>';}
					if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=838 && ($instrument_id==5)) {echo' <option value="'.$hrsem_slot_12.'">'.$hrsem_slot_12.'</option>';}
					if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=838 && ($instrument_id==5)) {echo' <option value="'.$hrsem_slot_13.'">'.$hrsem_slot_13.'</option>';}          
					if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=838 && ($instrument_id==5)) {echo' <option value="'.$hrsem_slot_14.'">'.$hrsem_slot_14.'</option>';}
			}
			// Ref for the for loop: 0-booked by the super-admin 1001> means booked by user. 1 otherwise
			else if($instrument_id==6)
                {
                	if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=8 && ($instrument_id==6) ) {echo' <option value="'.$teecan_slot_9.'">'.$teecan_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=8 && ($instrument_id==6)) {echo' <option value="'.$teecan_slot_10.'">'.$teecan_slot_10.'</option>';}
                    if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=8 && ($instrument_id==6)) {echo' <option value="'.$teecan_slot_11.'">'.$teecan_slot_11.'</option>';}
                    if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=8 && ($instrument_id==6)) {echo' <option value="'.$teecan_slot_12.'">'.$teecan_slot_12.'</option>';}
                    if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=8 && ($instrument_id==6)) {echo' <option value="'.$teecan_slot_13.'">'.$teecan_slot_13.'</option>';}          
                    if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=8 && ($instrument_id==6)) {echo' <option value="'.$teecan_slot_14.'">'.$teecan_slot_14.'</option>';}
				}
			else if($instrument_id==7)
                  {
                    if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=9 && ($instrument_id==7) ) {echo' <option value="'.$biorad_slot_9.'">'.$biorad_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=9 && ($instrument_id==7)) {echo' <option value="'.$biorad_slot_10.'">'.$biorad_slot_10.'</option>';}
                  }

            else if($instrument_id==8)
                  {
                    if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=10 && ($instrument_id==8) ) {echo' <option value="'.$syngene_slot_9.'">'.$syngene_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=10 && ($instrument_id==8)) {echo' <option value="'.$syngene_slot_10.'">'.$syngene_slot_10.'</option>';}
                    if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=10 && ($instrument_id==8)) {echo' <option value="'.$syngene_slot_11.'">'.$syngene_slot_11.'</option>';}
                    if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=10 && ($instrument_id==8)) {echo' <option value="'.$syngene_slot_12.'">'.$syngene_slot_12.'</option>';}
                    if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=10 && ($instrument_id==8)) {echo' <option value="'.$syngene_slot_13.'">'.$syngene_slot_13.'</option>';}          
                    if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=10 && ($instrument_id==8)) {echo' <option value="'.$syngene_slot_14.'">'.$syngene_slot_14.'</option>';}
                  }

             else if($instrument_id==9)
                  {
                    if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=11 && ($instrument_id==9) ) {echo' <option value="'.$toc_slot_9.'">'.$toc_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=11 && ($instrument_id==9)) {echo' <option value="'.$toc_slot_10.'">'.$toc_slot_10.'</option>';}
                    if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=11 && ($instrument_id==9)) {echo' <option value="'.$toc_slot_11.'">'.$toc_slot_11.'</option>';}
				  }
			else if($instrument_id==10)
			{
				if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=12 && ($instrument_id==10) ) {echo' <option value="'.$hrmspectro_slot_9.'">'.$hrmspectro_slot_9.'</option>';}
				if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_10.'">'.$hrmspectro_slot_10.'</option>';}
				if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_11.'">'.$hrmspectro_slot_11.'</option>';}
				if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_12.'">'.$hrmspectro_slot_12.'</option>';}
				if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_13.'">'.$hrmspectro_slot_13.'</option>';}
				if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_14.'">'.$hrmspectro_slot_14.'</option>';}
				if($slots->slot_15!=0 && $slots->slot_15!=2 && $slots->slot_15!=3 && $slots->slot_15!=4 && $slots->slot_15<1001 && $slots->slot_15!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_15.'">'.$hrmspectro_slot_15.'</option>';}
				if($slots->slot_16!=0 && $slots->slot_16!=2 && $slots->slot_16!=3 && $slots->slot_16!=4 && $slots->slot_16<1001 && $slots->slot_16!=12 && ($instrument_id==10)) {echo' <option value="'.$hrmspectro_slot_16.'">'.$hrmspectro_slot_16.'</option>';}
			}
			else if($instrument_id==19)
                  {
                    if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=13 && ($instrument_id==19) ) {echo' <option value="'.$pcr_slot_9.'">'.$pcr_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=13 && ($instrument_id==19)) {echo' <option value="'.$pcr_slot_10.'">'.$pcr_slot_10.'</option>';}
				  }
			else if($instrument_id==20)
			{
				if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=14 && ($instrument_id==20) ) {echo' <option value="'.$trace_slot_9.'">'.$trace_slot_9.'</option>';}
				if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=14 && ($instrument_id==20)) {echo' <option value="'.$trace_slot_10.'">'.$trace_slot_10.'</option>';}
				if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=14 && ($instrument_id==20)) {echo' <option value="'.$trace_slot_11.'">'.$trace_slot_11.'</option>';}
				if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=14 && ($instrument_id==20)) {echo' <option value="'.$trace_slot_12.'">'.$trace_slot_12.'</option>';}
				if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=14 && ($instrument_id==20)) {echo' <option value="'.$trace_slot_13.'">'.$trace_slot_13.'</option>';}          
				if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=14 && ($instrument_id==20)) {echo' <option value="'.$trace_slot_14.'">'.$trace_slot_14.'</option>';}
			}
			else if($instrument_id==21)
			{
				if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $slots->slot_9!=15 && ($instrument_id==21) ) {echo' <option value="'.$hplc_slot_9.'">'.$hplc_slot_9.'</option>';}
                    if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $slots->slot_10!=15 && ($instrument_id==21)) {echo' <option value="'.$hplc_slot_10.'">'.$hplc_slot_10.'</option>';}
                    if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $slots->slot_11!=15 && ($instrument_id==21)) {echo' <option value="'.$hplc_slot_11.'">'.$hplc_slot_11.'</option>';}
                    if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $slots->slot_12!=15 && ($instrument_id==21)) {echo' <option value="'.$hplc_slot_12.'">'.$hplc_slot_12.'</option>';}
                    if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $slots->slot_13!=15 && ($instrument_id==21)) {echo' <option value="'.$hplc_slot_13.'">'.$hplc_slot_13.'</option>';}          
                    if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $slots->slot_14!=15 && ($instrument_id==21)) {echo' <option value="'.$hplc_slot_14.'">'.$hplc_slot_14.'</option>';}
			}
			else 
			{
				if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_9.'">'.$hrtem_slot_9.'</option>';}
                  else if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_9.'">'.$mrs_slot_9.'</option>';}

                  if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_10.'">'.$hrtem_slot_10.'</option>';}
                  else if($slots->slot_10!=0 && $slots->slot_10!=2 && $slots->slot_10!=3 && $slots->slot_10!=4 && $slots->slot_10<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_10.'">'.$mrs_slot_10.'</option>';}

                  if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_11.'">'.$hrtem_slot_11.'</option>';}
                  else if($slots->slot_11!=0 && $slots->slot_11!=2 && $slots->slot_11!=3 && $slots->slot_11!=4 && $slots->slot_11<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_11.'">'.$mrs_slot_11.'</option>';}

                  if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_12.'">'.$hrtem_slot_12.'</option>';}
                  else if($slots->slot_12!=0 && $slots->slot_12!=2 && $slots->slot_12!=3 && $slots->slot_12!=4 && $slots->slot_12<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_12.'">'.$mrs_slot_12.'</option>';}

                  if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_13.'">'.$hrtem_slot_13.'</option>';}
                  else if($slots->slot_13!=0 && $slots->slot_13!=2 && $slots->slot_13!=3 && $slots->slot_13!=4 && $slots->slot_13<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_13.'">'.$mrs_slot_13.'</option>';}

                  if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_14.'">'.$hrtem_slot_14.'</option>';}
                  else if($slots->slot_14!=0 && $slots->slot_14!=2 && $slots->slot_14!=3 && $slots->slot_14!=4 && $slots->slot_14<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_14.'">'.$mrs_slot_14.'</option>';}

                  if($slots->slot_15!=0 && $slots->slot_15!=2 && $slots->slot_15!=3 && $slots->slot_15!=4 && $slots->slot_15<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_15.'">'.$hrtem_slot_15.'</option>';}
                  else if($slots->slot_15!=0 && $slots->slot_15!=2 && $slots->slot_15!=3 && $slots->slot_15!=4 && $slots->slot_15<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_15.'">'.$mrs_slot_15.'</option>';}

                  if($slots->slot_16!=0 && $slots->slot_16!=2 && $slots->slot_16!=3 && $slots->slot_16!=4 && $slots->slot_16<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_16.'">'.$hrtem_slot_16.'</option>';}

				  if($slots->slot_17!=0 && $slots->slot_17!=2 && $slots->slot_17!=3 && $slots->slot_17!=4 && $slots->slot_17<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_17.'">'.$hrtem_slot_17.'</option>';}
			}

			echo '</select>';
			echo '</tr>';
			echo '</table>';
			echo '<button id="button1" style="float:right;">Block Slot</button>';
			echo '<button id="button2" style="float:left;">Block Day</button>';
			// echo '<button id="button3" style="float:right;">Unblock Slot</button>'; disabled the slot unblocking option for admin
			// echo '<button id="button4" style="float:left;">Unblock Day</button>'; disabled the day unblock option for admin

		}
		else
		{
			echo 'Something is wrong';
		}
	}
	else echo 'SQL Fail';


echo '<div id="data2">';
echo '</div>';// The echo into which the AJAX function prints!
 	
/*------Hidden Divs to pass PHP variables to the AJAX function-----*/
/*------Do Not Edit this. If you don't know exactly what you are doing*/
echo '<div id="dom-target" style="display: none;">'; 
echo htmlspecialchars($date); // passing date
echo '</div>';
echo '<div id="dom-target2" style="display: none;">'; 
echo htmlspecialchars($instrument_id); // passing the instrument id
echo '</div>';   
echo '<div id="dom-target3" style="display: none;">'; 
echo htmlspecialchars($admin_id); // passing the admin id
echo '</div>';  

?>
