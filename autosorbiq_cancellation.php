<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');

if(($_SESSION["type"]!="superuser")&&($_SESSION["type"]!="autosorbiqadmin")) {
  header("location:index.php");
}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['approve']))
{
  $booya=$_POST['approve'];
   $q=$mysqli->query("SELECT slot_no FROM orders WHERE order_id=$booya");
  $slot_no1=$q->fetch_object();
  $slot_no = $slot_no1->slot_no;
  $result1 = $mysqli->query("UPDATE slot_autosorbiq SET $slot_no =1 WHERE $slot_no=$booya");
  $result = $mysqli->query("DELETE FROM orders WHERE order_id=$booya");
  $del=$mysqli->query("DELETE FROM autosorbiq_order_details WHERE order_id=$booya");

if($result && $del && $result1)
{
  echo 'Cancellation Accepted ';
}

}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['reject']))
{
  $booya=$_POST['reject'];
  $result = $mysqli->query("UPDATE orders SET cancel_reason='NULL' WHERE order_id=$booya" );

}

?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Autosorb iQ Cancellation</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <style type="text/css">
    footer {
      padding: 1em;
      color: gray;
      background-color: white;
      clear: left;
      text-align: center;
      font-size: 10px;
  }
     table {
      margin-bottom: 0px;
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
}
table td {
    border: 1px solid #ccc;
}
table .absorbing-column {
    width: 100%;
}

    </style>
  </head>
  <body>
    <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a>Dept. of Chem. Administrator</a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>

      <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">
        <li><a href="block.php">Slot Blocking</a></li>
      <?php
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="registration.php">Registration Requests</a></li>
            <li><a href="analysis.php">Analysis</a></li>';
          }
          ?>
        <li><a href="autosorbiqrequests.php">Autosorb iQ Requests</a></li>
        <li><a href="autosorbiq_cancellation.php">Autosorb iQ Cancellation Requests</a></li>
        <li><a href="bookingdata.php">Autosorb iQ Booking Data</a></li>
        <?php
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="bookingdata.php">View Booking Data</a></li>';
          }
          ?>
        <li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>
    <div class="row" style="margin-top:10px;">
      <div>
        <?php
        $name="Autosorb iQ-C-AG/MP/XR";
          $result = $mysqli->query("SELECT * FROM users cross join orders WHERE user_id=id AND status=1 AND product_name='$name' AND cancel_reason!='NULL'");
          echo '<h1>Cancellation Requests</h1>';
          if($result->num_rows) {
            
            while($obj = $result->fetch_object()) {
              $uid=$obj->order_id;
              $lq=$mysqli->query("SELECT * FROM autosorbiq_order_details WHERE order_id=$uid");
              $lq_result=$lq->fetch_object();
            // Start of first table
              echo 'Order Details:';
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Booking ID</th>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '<th>Date of Usage</th>';
            echo '<th>Slot</th>';
echo '<th>Reason</th>';
              echo '<th>Approval</th>';

            echo '</tr>';
            echo '<tr>';
            echo '<td>'.$obj->order_id.'</td>';
           echo '<td>'.$obj->fname.' '.$obj->lname.'</td>';
           echo '<td>'.$obj->institute.'</td>';
           echo '<td>'.$obj->iid.'</td>';
           echo '<td>'.$obj->phno.'</td>';
           echo '<td>'.$obj->email.'</td>';
            echo '<td>'.$obj->date_of_order.'</td>';
            echo '<td>'.$obj->slot_time.'</td>';
              echo '<td>'.$obj->cancel_reason.'</td>';
            echo '<td>';
            echo '<form action ="autosorbiq_cancellation.php" method=POST>
            <button type=submit name="approve" value='.$uid.' style="padding: 8px 8px; border-radius:7px;">Approve</button><br>
            <button type=submit name="reject" value='.$uid.' style="padding: 8px 16px; border-radius:7px;">Reject</button>
            </form>';
            echo '</td>';

            echo '</tr>';
            echo '</table>';

            echo '<table>';
            echo '<tr>';
            echo '<th>Nature of Sample</th>';
            echo '<th>Porous Nature</th>';
            echo '<th>Analysis Type</th>';
            echo '<th>Degassing Temperature</th>';
            echo '<th>Degassing Conditions</th>';
            echo '<th>Additional Details</th>';
            echo '</tr>';
            echo '<td>'.$lq_result->nature_of_sample.'</td>';
            echo '<td>'.$lq_result->porous_nature.'</td>';
            echo '<td>'.$lq_result->analysis_type.'</td>';
            echo '<td>'.$lq_result->degassing_temp.'</td>';
            echo '<td>'.$lq_result->degassing_condition.'</td>';
            echo '<td>'.$lq_result->additional_details.'</td>';
            echo '</tr>';
            echo '</table>';
            }
            
          }
          else
          {
             echo '<p>No New Cancellations to approve as of now!</p>';
          }

          echo '</div>';
            echo '</div>';
        ?>
      </div>
    </div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>
