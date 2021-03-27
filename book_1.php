<!--
   THIS PAGE IS SENSITIVE IN NATURE.
   DO NOT MESS AROUND IF YOU DO NOT NOW WHAT YOU ARE DOING.
   READ THE COMMENTS ON THE PAGE TO UNDERSTAND THE CODE.
-->
<html>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/jquery.are-you-sure.js"></script>
  <script src="js/ays-beforeunload-shim.js"></script>
  <style>
  .mytable
  {
    table-layout: fixed;
  }
  .mytable td
  {
    width: 500px;
  }
  .mytable th
  {
    width: 500px;
  }
  #magnetic_details_1_extra{
    height: 120px;
  }

  </style>
</head>
<?php
if(!empty($_POST))
{
include_once ('php/data.php');
include_once ('php/session.php');
include_once ('php/login_check.php');
include_once ('php/config.php');
if(!isset($_SESSION["username"])) {header("location:index.php");}
?>
    
        
        <?php
        
        // Force to load 1 slot if only 1 slot is available.
        $FORCE_ONE_SLOT = false;
        $display_five_min_warning = false;
        // GET ALL THE POST VARIABLED FROM THE LAST PAGE
          $pid =$_POST["instrument"]; // Instrument id form the last page.
          $no_of_slots = $_POST['no_of_slots']; // No of slots to be booked.
          $wdate= $_POST["date"]; // Get the date from the post array.
          $date = date('Y-m-d', strtotime($wdate)); //date format do not change! This will break SQL if chnaged. Took me hours to get this right. please i beg you do not change it if you do not know what you are doing.
          //Check if user selected 2 slots but ony 1 slot is actually free, then set FORCE_ONE_SLOT to true.
          
          $tq = $mysqli->query("SET SESSION time_zone = '+5:30'"); // Unsure that the sql server is set for Indian time zone. PHP and SQL server time zone mismatch is bad.
          $tuq = $mysqli->query("INSERT INTO order_under_process (inst_id, date) VALUES ($pid,'$date')"); // Entry for the 5 min timer in the sql table.
          if($tuq)
          {
            $display_five_min_warning = true;
          }
          else
          {
            echo 'fail';
          }

          $counter = 0;
          if($pid == 1)
          {
            $slot_9_query=$mysqli->query("SELECT slot_id FROM slot_autosorbiq WHERE slot_9 = 1 AND slot_date = '$date'");

            if($rows=mysqli_num_rows($slot_9_query) >=1){$counter++;}
            if($counter == 1){$FORCE_ONE_SLOT = true;}
          }

          if($date<"2000-01-01"){
            echo '<h1 align="center" style="color:red;">Please Select a Valid Date</h1>';
            header("Refresh:2; url=products.php");
          }
          else
          {
            include ('php/head.php');
            include ('php/config.php');
            echo '<div class="row" style="margin-top:10px;">
            <div class="large-12"><p id="warning" style="color:red; text-align:center;">Booking in progress. Don\'t press back button or refresh page.<br> You have 5 minutes to fill the details<br><br><b>All fields are mandatory; if not filled appropriately slot may be denied.</b></p>';
            echo '<span id="warning_time" style="color:red; text-align:center;"></span>';
            
            if($no_of_slots == 2 && $FORCE_ONE_SLOT == false)
            {
              echo '<p><h3>Enter details for First Slot</h3></p>';
            }
            else
            {
              echo '<p><h3>Enter Details for the Slot</h3></p>';
            }
            
          $instrument_id=$_POST['instrument'];

          $result = $mysqli->query("SELECT * FROM products WHERE id=$pid");
          $row=$result->fetch_object();
            $total = 0;
            echo '<form id="main_form" action="book_2.php" method="POST" onsubmit="return vali()">';
            echo '<table table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Instrument Selected</th>';
            echo '<th>Cost</th>';
            echo '<th>Slots Available on '.$date.'</th>';
            echo '</tr>';
            echo '<tr>';
                  echo '<td colspan="1" align="left">';
                  echo $row->product_name;
                  echo'</td>';
                  echo '<td colspan="1" align="left">';
                  echo "Check Service Section";
                  echo'</td>';

                  echo '<td colspan="1" align="left">';

                  /*REFRENCE FOR HUMANS
                   q1: Query to find the table_name to use based on the pid of the product selected on the last page.
                   q2: Query to find if the slected date has a valid entry in the slot table of the selected product.
                   q3: If the above query fails then make a vaild entry for that date explicitly in the slot table of the slected product.*/


                  // Finding table_name using pid from the link table.
                 $q1=$mysqli->query("SELECT table_name from link WHERE pid=$pid");
                 $result_q1=$q1->fetch_object();
                 $table_name=$result_q1->table_name;


                 // If a slot date is not present in the database we will create an entry for that slot_date explicitly.
                 $q2=$mysqli->query("SELECT slot_date FROM $table_name WHERE slot_date='$date'");
                 if(!$q2->fetch_object())
                 {
                 $q3=$mysqli->query("INSERT INTO $table_name (slot_date) VALUES ('$date')");
                 }


                 // Query for slot availability on that given date.
                 $q4=$mysqli->query("SELECT * FROM $table_name WHERE slot_date='$date'");

                 // Loop to print only thoese slots that are available on that day.
                 if($q4)
                 {
                  $slots=$q4->fetch_object();
                  echo '<select name="slot_1" id="slot_1" required>';
                  if($instrument_id==1)
                  {
                    if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && ($instrument_id==1 || $instrument_id==5) ) {echo' <option value="'.$autosorbiq_slot_9.'">'.$autosorbiq_slot_9.'</option>';}
                  }

                  // UNCOMMENT THESE SLOTS IF NEEDED.
                  // if ($slots->slot_16!=0 && $slots->slot_16<1001) {echo' <option value="'.'10'.'">'."4PM - 5PM".'</option>';}
                  // if ($slots->slot_17!=0 && $slots->slot_17<1001) {echo' <option value="'.'11'.'">'."5PM - 6PM".'</option>';}
                  // if ($slots->slot_18!=0 && $slots->slot_18<1001) {echo' <option value="'.'12'.'">'."6PM - 7PM".'</option>';}
                  // if ($slots->slot_19!=0 && $slots->slot_19<1001) {echo' <option value="'.'13'.'">'."7PM - 8PM".'</option>';}
                  // if ($slots->slot_20!=0 && $slots->slot_20<1001) {echo' <option value="'.'14'.'">'."8PM - 9PM".'</option>';}
                  // if ($slots->slot_21!=0 && $slots->slot_21<1001) {echo' <option value="'.'15'.'">'."9PM - 10PM".'</option>';}
                  // if ($slots->slot_22!=0 && $slots->slot_22<1001) {echo' <option value="'.'16'.'">'."10PM - 11PM".'</option>';}
                  // END OF SLOT DECLERATION
                  echo '</select>';
                 }
                 else
                 {
                  echo "Error in SQL";
                 }
                  echo'</td>';
                  echo "</tr>";
                  echo '</table>';
                  //End of the first table

                  if($pid==1)
                  {
                    echo '<table class="mytable">';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">Nature of the Sample:</td>';
                    echo '<td colspan="1" align="left">';
                    echo '<select required name="sample_nature">';
                    echo '<option value="" disabled selected>Select your option</option>';
                    echo' <option value="'.'Powder'.'">'."Powder".'</option>';
                    echo' <option value="'.'Granules'.'">'."Granules".'</option>';
                    echo' <option value="'.'Polymer'.'">'."Polymer".'</option>';
                    echo '</select>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">Porous Nature:</td>';
                    echo '<td colspan="1" align="left">';
                    echo '<select required name="porous_nature">';
                    echo '<option value="" disabled selected>Select your option</option>';
                    echo' <option value="'.'Micro'.'">'."Micro".'</option>';
                    echo' <option value="'.'Meso'.'">'."Meso".'</option>';
                    echo' <option value="'.'Macro'.'">'."Macro".'</option>';
                    echo '</select>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">Type of Analysis:</td>';
                    echo '<td colspan="1" align="left">';
                    echo '<select required name="analysis_type">';
                    echo '<option value="" disabled selected>Select your option</option>';
                    echo' <option value="'.'Isotherm'.'">'."Isotherm".'</option>';
                    echo' <option value="'.'BET Analysis'.'">'."BET Analysis".'</option>';
                    echo' <option value="'.'PSD'.'">'."PSD".'</option>';
                    echo '</select>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">Degassing Temperature <font color="red">[50-300 degrees]</font>:</td>';
                    echo '<td>';
                    echo '<textarea name="degassing_temp" placeholder="Please Answer here." required></textarea>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">Degassing Time/Condition:
                    <br><font color="red">0-12hr / Under Vacuum (more time requires special permission)</font></td>';
                    echo '<td>';
                    echo '<textarea name="degassing_condition" placeholder="Please Enter your Answer here." required></textarea>';
                    echo '</tr>';
                    echo '<td colspan="1" align="left">Any other details:</td>';
                    echo '<td>';
                    echo '<textarea name="additional_details" placeholder="Please provide any other details."></textarea>';
                    echo '</tr>';
                    if($no_of_slots == 1 || $FORCE_ONE_SLOT == true)
                    {
                    echo '<tr>';
                    echo '<td style="text-align:center">';
                    echo'<td>';
                    echo '<button type=submit style="float:right;">Book</button>';
                    echo '</tr>';
                    echo '</table>';
                    }
                    echo '</form>';
                  }

                  // CODE ONLY IF TWO SLOTS ARE SELECTED AT A TIME FOR BOOKING......... 

                  if($no_of_slots == 2 && $FORCE_ONE_SLOT == false)
                  {
                    echo '<form action="book_2.php" method="POST">';
                    echo '<table table class="absorbing-column">';
                    echo '<tr>';
                    echo '<th>Instrument Selected</th>';
                    echo '<th>Cost</th>';
                    echo '<th>Slots Available on '.$date.'</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="1" align="left">';
                    echo $row->product_name;
                    echo'</td>';
                    echo '<td colspan="1" align="left">';
                    echo "Check Service Section";
                    echo'</td>';
                    echo '<td colspan="1" align="left">';
        
                          /*REFRENCE FOR HUMANS
                           q1: Query to find the table_name to use based on the pid of the product selected on the last page.
                           q2: Query to find if the slected date has a valid entry in the slot table of the selected product.
                           q3: If the above query fails then make a vaild enrty for that date explicitly in the slot table of the slected product.*/
        
        
                          // Finding table_name using pid from the link table.
                         $q1=$mysqli->query("SELECT table_name from link WHERE pid=$pid");
                         $result_q1=$q1->fetch_object();
                         $table_name=$result_q1->table_name;
        
        
                         // If a slot date is not present in the database we will create an entry for that slot_date explicitly.
                         $q2=$mysqli->query("SELECT slot_date FROM $table_name WHERE slot_date='$date'");
                         if(!$q2->fetch_object())
                         {
                         $q3=$mysqli->query("INSERT INTO $table_name (slot_date) VALUES ('$date')");
                         }
        
        
                         // Query for slot availability on that given date.
                         $q4=$mysqli->query("SELECT * FROM $table_name WHERE slot_date='$date'");
                         
                         // Loop to print only thoese slots that are available on that day.
                         if($q4)
                         {
                          $slots=$q4->fetch_object();
                          echo '<select name="slot_2" id="slot_2" required>';
                          if($instrument_id<5)
                          {
                          
                            if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id==1) {echo' <option value="'.$hrtem_slot_9.'">'.$hrtem_slot_9.'</option>';}
                            else if($slots->slot_9!=0 && $slots->slot_9!=2 && $slots->slot_9!=3 && $slots->slot_9!=4 && $slots->slot_9<1001 && $instrument_id!=1){echo' <option value="'.$mrs_slot_9.'">'.$mrs_slot_9.'</option>';}
                          }

                          // UNCOMMENT THESE SLOTS IF NEEDED.
                          // if ($slots->slot_16!=0 && $slots->slot_16<1001) {echo' <option value="'.'10'.'">'."4PM - 5PM".'</option>';}
                          // if ($slots->slot_17!=0 && $slots->slot_17<1001) {echo' <option value="'.'11'.'">'."5PM - 6PM".'</option>';}
                          // if ($slots->slot_18!=0 && $slots->slot_18<1001) {echo' <option value="'.'12'.'">'."6PM - 7PM".'</option>';}
                          // if ($slots->slot_19!=0 && $slots->slot_19<1001) {echo' <option value="'.'13'.'">'."7PM - 8PM".'</option>';}
                          // if ($slots->slot_20!=0 && $slots->slot_20<1001) {echo' <option value="'.'14'.'">'."8PM - 9PM".'</option>';}
                          // if ($slots->slot_21!=0 && $slots->slot_21<1001) {echo' <option value="'.'15'.'">'."9PM - 10PM".'</option>';}
                          // if ($slots->slot_22!=0 && $slots->slot_22<1001) {echo' <option value="'.'16'.'">'."10PM - 11PM".'</option>';}
                          // END OF SLOT DECLERATION
                          echo '</select>';
                         }
                         else
                         {
                          echo "Error in SQL";
                         }
                          echo'</td>';
                          echo "</tr>";
                          echo '</table>';
                          //End of the first table
        
                          if($pid==1)
                          {
                            echo '<table class="mytable">';
                            echo '<tr>';
                            echo '<td colspan="2" align="center">Only 1 sample per day!</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<button type=submit style="float:right;">Book</button>';
                            echo '</tr>';
                            echo '</table>';        
                          }
                          echo "</form>";
                  }

                  // Passing varibales needed in the next page explitly
                   $_SESSION['date']=$date; // Passing the order_date.
                   $_SESSION['instrument']=$pid; // Passing the instrument id.
                   $_SESSION['no_of_slots'] = $no_of_slots; // Passing the no-of slots.
                   $_SESSION['force_one_slot'] = $FORCE_ONE_SLOT; 

                   if($no_of_slots == 2 && $FORCE_ONE_SLOT == true)
                    {
                      echo '<script> 
                      alert("You wanted to book 2 slots but only 1 is Available!!");
                      </script>';
                    }
          }
          
        }
          else
          {
            echo '<p align="center"><img src="images/hack.jpg"></img></p>';

          }
          ?>


   </div>
    </div>


<div class="row" style="margin-top:10px;">
      <div class="small-12">
        <footer style="margin-top:10px;">
           <p style="text-align:center; font-size:0.8em;clear:both;">&copy;</p>
        </footer>

      </div>
    </div>
<p id="runner"></p>

</body>
</html>

<script>
$("#suitable_1").on('change',function(){
   if($(this).find('option:selected').text()=="No"){
       $("#btnSubmit").attr('disabled',true);
       alert("Booking not allowed. Discuss with TEM Lab.");
       document.getElementById("blocked").innerHTML = "Sample Not Suitable for TEM Analysis";
       }
   else
       $("#btnSubmit").attr('disabled',false);
       document.getElementById("blocked").innerHTML = "";
});
</script>

<script>
  $(function() {
    $('#main_form').areYouSure(
      {
        message: 'Going Back is Forbiden '
               + 'If you leave, your data will be lost.'
      }
    );
  });
</script>
<script>
    $("#number_of_replicates_1").on('keyup',function(){
        var totalsamples= $("#number_of_samples_1").val() * $("#number_of_replicates_1").val() 
        $(".total_samples_1").html(totalsamples);
})
</script>
<script>
    $("#number_of_replicates_2").on('keyup',function(){
        var totalsamples= $("#number_of_samples_2").val() * $("#number_of_replicates_2").val() 
        $(".total_samples_2").html(totalsamples);
})
</script>
<script>
function warning()
{
  if (typeof warning.counter == 'undefined') {
        warning.counter = 300; //seconds
        var s = warning.counter.toString();
        document.getElementById("warning_time").innerHTML = s+" minutes remaining";
        warning.counter--;
    }
    else if(warning.counter == 0)
    {
      document.getElementById("warning_time").innerHTML ="";
      return;
    }
    else
    {
      var s = warning.counter.toString();
      document.getElementById("warning_time").innerHTML = s+" seconds remaining";
      warning.counter--;
    }
}
function timeup()
{
  var inst_id = <?php echo $pid; ?>;
  $("#runner").load("deleter.php", {
      inst: inst_id
    });
}
setInterval(warning,1000);
setTimeout(timeup,300000);
</script>

<script>
$(document).ready(function() {
    var so = $("#magnetic_1").children("option").filter(":selected").text();
    if(so == "No")
    {
      alert("Yes");
    }

}

</script>


<script>
function vali()
{
  var slot_1 = document.getElementById("slot_1");
  var strUser_1 = slot_1.options[slot_1.selectedIndex].value;
  var slot_2 = document.getElementById("slot_2");
  var strUser_2 = slot_2.options[slot_2.selectedIndex].value;
  if(strUser_1 == strUser_2)
  {
    alert("Slot 1 and Slot 2 can't be same");
    return false;
  }
  return true;
}
</script>

<noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>