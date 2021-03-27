<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');
if($_SESSION["type"]=="user") {
header("location:index.php");
}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['autosorbiqannouncement']))
{$booya=$_POST['autosorbiqannouncement'];

$result = $mysqli->query("UPDATE announcement SET autosorbiqann = '$booya'");
//"INSERT INTO announcement (announce) VALUES ('.$booya.')"
if($result)
{
  echo'Announcement Updated';
}
else
{
  echo 'SQL Error! Contact Administrator';
}
}

?>
<?php

echo '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <!-- Javascripts are here-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Block</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="css/foundation.css" />
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $( function() {
    $( "#datepicker" ).datepicker();
       });
    </script>
    <script>
      $( function() {
      $( "#datepicker2" ).datepicker();
         });
    </script>
<!-- CSS IS HERE-->
<style type="text/css">
footer {
  padding: 1em;
  color: gray;
  background-color: white;
  clear: left;
  text-align: center;
  font-size: 10px;
}
.boxed {
  border: 1px solid green ;
}
table
{
  margin-bottom: 0px;
  table-layout: auto;
  border-collapse: collapse;
  width: 100%;
}
table td
{
    border: 1px solid #ccc;
}
table .absorbing-column
{
    width: 100%;
}
</style>
</head>
  <body>
    <!--Navigation Bar-->
    <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a>Dept. of Chem. Administrator</a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>
      <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">';?>

       <?php
       if($_SESSION['type']=="autosorbiqadmin"){echo'<li><a href="block.php">Slot Blocking</a></li>
          <li><a href="autosorbiqrequests.php">Autosorb IQ Requests</a></li>
        <li><a href="autosorbiq_cancellation.php">Autosorb IQ Cancellation Requests</a></li>
        <li><a href="bookingdata.php">Autosorb IQ Booking Data</a></li>';}
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="block.php">Slot Blocking</a></li>
            <li><a href="registration.php">Registration Requests</a></li>
            <li><a href="analysis.php">Analysis</a></li>
            <li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
              <option value="#">Requests</option>
              <option value="autosorbiqrequests.php">Autosorb IQ Requests</option>
            </select></li>
            <li><a href="users.php">View Users Data</a></li>';
          }
          ?>

         <?php
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="bookingdata.php">View Booking Data</a></li>';
          }
          ?>
       <?php
      echo '  <li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>';
// Global Queries that need to be exexuted.
$q1=$mysqli->query("SELECT id,product_name FROM products");
// End of Global Queries

// Super-Admin=0 Free=1 autosorbiqadmin=2 User_Blocked>1001. Do not assign '1' to any admin.

if($_SESSION["type"]=="superuser")
{
          $admin_id=0;

        	echo '<script> $( function() {
           $( "#datepicker" ).datepicker({
              minDate: 0, maxDate: 60
            });
          });
          </script>';

          echo '<script> $(document).ready(function(){
            $("button").click(function(){
              var d =new Date($("#datepicker").datepicker("getDate"));
              var date=d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();
              var inst_id = $("#instrument_id").find(":selected").val();
              var div = document.getElementById("dom-target");
              var admin_id = div.textContent;
              $("#data1").load("load_slots.php",{date: date, id: inst_id, admin_id: admin_id});
            });
          });</script>';

          echo '<div class="row" style="margin-top:10px;">';
      	  echo '<div class="small-16 columns">';
      	  echo '<h3>Super Admin Block Page</h3>';
          echo '<table>';
          echo '<tr>';
          echo '<th>Pick the Date:</th>';
          echo '<th>Pick an Instrument:</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td><input type="text" name="date" id="datepicker" required autocomplete="off"></td>';
          echo '<td><select name="instrument_id" id="instrument_id" required>';
          echo '<option value="" disabled selected>Select your option</option>';
          while($q1_result=$q1->fetch_object())
          {
            echo '<option value ="'.$q1_result->id.'">'.$q1_result->product_name.'</option>';
          }
          echo '</tr>';
          echo '</table>';
          echo '<button style="float:right;">Proceed</button>';
          echo '</div>';

          echo '<div class="small-16 columns" id="data1">';


          echo '</div>';
          echo '</div>';
          echo '<div id="dom-target" style="display: none;">';
          echo htmlspecialchars($admin_id); // passing admin id
          echo '</div>';

}
else if($_SESSION["type"]=="autosorbiqadmin")
{        
        $admin_id=2;
        echo '<br><div class="row">Type your annoucement here-<br>
        <form action="block.php" id="form1" method=POST>
        <input type="text" name="autosorbiqannouncement" autocomplete="off"><br>
        </form>
        <button type="submit" form="form1" value="Submit">Make Announcement</button>
        </div>';
         echo '<script> $( function() {
           $( "#datepicker" ).datepicker({
              minDate: 0, maxDate: 60
            });
          });
          </script>';

          echo '<script> $( function() {
            $( "#datepicker" ).datepicker({
               minDate: 0, maxDate: 60
             });
           });
           </script>';
 
           echo '<script> $(document).ready(function(){
             $("button").click(function(){
               var d =new Date($("#datepicker").datepicker("getDate"));
               var date=d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();
               var inst_id = $("#instrument_id").find(":selected").val();
               var div = document.getElementById("dom-target");
               var admin_id = div.textContent;
               $("#data1").load("load_slots.php",{date: date, id: inst_id, admin_id: admin_id});
             });
           });</script>';
 
           echo '<div class="row" style="margin-top:10px;">';
           echo '<div class="small-16 columns">';
           echo '<h3>Autosorb IQ Admin Block Page</h3>';
           echo '<table>';
           echo '<tr>';
           echo '<th>Pick the Date:</th>';
           echo '<th>Pick an Instrument:</th>';
           echo '</tr>';
           echo '<tr>';
           echo '<td><input type="text" name="date" id="datepicker" required autocomplete="off"></td>';
           echo '<td><select name="instrument_id" id="instrument_id" required>';
           echo '<option value="" disabled selected>Select your option</option>';
           echo '<option value ="'.'1'.'">'.'Autosorb IQ'.'</option>';
           echo '</tr>';
           echo '</table>';
           echo '<button style="float:right;">Proceed</button>';
           echo '</div>';
 
           echo '<div class="small-16 columns" id="data1">';
 
 
           echo '</div>';
           echo '</div>';
           echo '<div id="dom-target" style="display: none;">';
           echo htmlspecialchars($admin_id); // passing admin id
           echo '</div>';
 }

else
{
  header("location:index.php");
}
echo '<script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>';

?>