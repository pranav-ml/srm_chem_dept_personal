<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load composer's autoloader
require 'vendor/autoload.php';

if(($_SESSION["type"]!="superuser") && ($_SESSION["type"]!="fuelcelladmin") ) {
  header("location:index.php");
}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['approve']))
{
  $booya=$_POST['approve'];
  $result = $mysqli->query("UPDATE orders SET status=1 WHERE order_id=$booya");
  $q=$mysqli->query("SELECT * FROM orders WHERE order_id=$booya");
  $slot_no1=$q->fetch_object();

  $pr=$slot_no1->product_name;
  $sl=$slot_no1->slot_time;
  $dt=$slot_no1->date_of_order;
 
  $ussid=$slot_no1->user_id;
  $add=$mysqli->query("SELECT * FROM users WHERE id=$ussid");
  $add_res=$add->fetch_object();
  $eemail=$add_res->email;
 
  $ussfname=$add_res->fname;
  $usslname=$add_res->lname;
 
  if($result)
  {
    echo'Request Accepted';
    // Send Confirmation mail to user.
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username ='smtpemail';                 // SMTP username
    $mail->Password = 'smtppassword';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@srmchemdept.ac.in', 'Dept. of Chem. Admin');
     $mail->addAddress($eemail);     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Booking Confirmation - Dept. of Chem. Admin';
    $mail->Body    = '
    <html>
    <body>
    <div style="background-color: #dee1e5;">
    <h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br>
    <h2>Booking Request Approved</h2>
<h1>Hi '.$eemail.'!</h1><br><h3>Your booking with booking id:'.$booya.' has been confimed by the Administrator.</h3><br> 
<p>Please make payment if applicable.
 For more information about the payment terms please visit the portal, service section.</p><br>
<h4><font color="red">Booking Details</font><h4>
<table>
<tr>
    <th>Name</th>
    <th>Booking ID</th>
    <th>Instrument Booked</th>
    <th>Details</th>
  </tr>
  <tr>
    <td>'.$ussfname.' '.$usslname.'</td>
    <td>'.$booya.'</td>
    <td>'.$pr.'</td>
    <td>'.$sl.' on '.$dt.'</td>
  </tr>
</table>
<br>
<p><i>Please refer to the "My Bookings" sections on the Dept. of Chem. website for more details.
    </div>
    <footer style="background-color: #545556; color: white; text-align:center;">
    <p>SRM Department of Chemistry</p>
    </footer>
    </body>
    </html>'; // Our message above including the link
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
  }
} 

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['reject']))
{
  $booya=$_POST['reject'];
  $q=$mysqli->query("SELECT slot_no,user_id FROM orders WHERE order_id=$booya");
  $slot_no1=$q->fetch_object();
  $slot_no = $slot_no1->slot_no;
  $ussid=$slot_no1->user_id;
  $result1 = $mysqli->query("UPDATE slot_fuelcell SET $slot_no =1 WHERE $slot_no=$booya");
  $result = $mysqli->query("DELETE FROM orders WHERE order_id=$booya");
  $del=$mysqli->query("DELETE FROM fuelcell_order_details WHERE order_id=$booya");
  $add=$mysqli->query("SELECT email FROM users WHERE id=$ussid");
  $add_res=$add->fetch_object();
  $eemail=$add_res->email;

  $comment = $_POST['rejectc'];

if($result && $del && $result1)
{
  echo 'Request Rejected';

  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username ='smtpemail';                 // SMTP username
    $mail->Password = 'smtppassword';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@srmchemdept.ac.in', 'Dept. of Chem. Admin');
     $mail->addAddress($eemail);     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Booking Rejected - Dept. of Chem. Admin';
    $mail->Body    = '
<h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br> 
Your booking with booking id:'.$booya.' has been rejected by the Administrator.<br><br>
Following is the remark by the administrator about your rejection of slot: "'.$comment.'" <br><br>
You can try another booking using the "Instrument" tab on the Dept. of Chem. website<br>
We regret any inconvenience caused to you. ';
 // Our message above including the link
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo ' and email has been sent to user';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
}
else echo'SQL Error!';
}



// Handel pageination
// Page and Per Page Query Limit
$page;
if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['page']))
{
  $page = (int)$_POST['page'];
}
else
{
  $page= isset($_GET['page']) ? (int)$_GET['page'] : 1;
}
$perPage=5; // Hard code specification for nof results per page


//Positioning
$start=($page>1) ? ($page*$perPage)-$perPage : 0;


// Query 
$name="Fuel Cell Analyser and RRDE Instruments";
$a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * FROM users cross join orders WHERE user_id=id AND status=0 AND product_name='$name' LIMIT {$start},{$perPage}");


// Total Rows
$total_query=$mysqli->query("SELECT FOUND_ROWS() as total");
$total_query_result=$total_query->fetch_object();
$total=(int)$total_query_result->total;

$pages=(int)ceil($total/$perPage);


echo '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dept. of Chem.</title>
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

.centeritman
{
  text-align:center;
} 

.pagination {
    display: inline-block;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}



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
        <li><a href="block.php">Slot Blocking</a></li>';
        
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="registration.php">Registration Requests</a></li>
            <li><a href="analysis.php">Analysis</a></li>';
          }
          

        
      
        if($_SESSION['type']=="fuelcelladmin")
          {
            echo '<li><a href="fuelcellrequests.php">FCA And RRDE Instruments Requests</a></li>
            <li><a href="fuelcell_cancellation.php">FCA And RRDE Instruments Cancellation Requests</a></li>
            <li><a href="bookingdata.php">FCA And RRDE Instruments Booking Data</a></li>';
          }
        
        if($_SESSION['type']=="superuser"){
            echo '<li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
            <option value="#">Requests</option>
            <option value="autosorbiqrequests.php">Autosorb iQ Requests</option>
            <option value="fuelcellrequests.php">FCA And RRDE Instruments Requests</option>
          </select></li>
        <li><a href="users.php">View Users Data</a></li>
        <li><a href="bookingdata.php">View Booking Data</a></li>';
          }
          
        echo '<li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>
    <div class="row" style="margin-top:10px;">
      <div>';
        
        //$name="Autosorb iQ-C-AG/MP/XR";
         //$result = $mysqli->query("SELECT * FROM users cross join orders WHERE user_id=id AND status=0 AND product_name='$name'");
           echo '<h1>FCA And RRDE Instruments Booking Requests</h1>';
          if($a->num_rows) {
            
            while($obj = $a->fetch_object()) {
              $uid=$obj->order_id;
              $lq=$mysqli->query("SELECT * FROM fuelcell_order_details WHERE order_id=$uid");
              $lq_result=$lq->fetch_object();
            // Start of first table
            echo '<hr style="border-color: red;"><hr style="border-color: red;">';
            if($obj->payinfo==NULL)
            {
              echo '<table class="absorbing-column" style="background-color:#ffc4c4"';
              echo'<center><font color="red"><i>Payment information for this booking is not yet provided by the user.</i></font></center>';
            }
            else
            {
              $link="uploads/".$obj->payinfo;
              echo '<table class="absorbing-column" style="background-color:#c5ffc4"';
               echo '<center><font color="green"><i>Download the payment information of this booking by <a href='.$link.' download> clicking here</a>.<br></i>  Bank Name: <b>'.$obj->tbank.'</b> Transaction ID: <b>'.$obj->tid.'</b> Transaction Date: <b>'.$obj->tdate.'</b> Ammount: <b>'.$obj->tamm.' </b></font></center>';
            }
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Booking ID</th>';
            echo '<th>Time of Booking</th>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '<th>Date of Usage</th>';
            echo '<th>Slot</th>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>'.$obj->order_id.'</td>';
            echo '<td>'.$obj->timestamp.'</td>';
            echo '<td style="word-break:break-all;">'.$obj->fname.' '.$obj->lname.'</td>';
            echo '<td>'.$obj->institute.'</td>';
            echo '<td>'.$obj->iid.'</td>';
            echo '<td>'.$obj->phno.'</td>';
            echo '<td>'.$obj->email.'</td>';
            echo '<td>'.$obj->date_of_order.'</td>';
            echo '<td>'.$obj->slot_time.'</td>';
            echo '</tr>';
            echo '</table>';
            // End of first table
            
            
            //second table
            echo '<table class="mytable">';
            echo '<tr>';
            echo '<td>';
            echo '<form action ="fuelcellrequests.php" method=POST>
            <textarea name="rejectc" placeholder="Please Enter your rejection justification comment here."></textarea>';
            //echo '<textarea name="comment" placeholder="Please Enter your comment about the booking here."></textarea>';
            echo '</td>';
            echo '<td>';
            echo '
            <button type=submit name="approve" value='.$uid.' style="padding: 8px 8px; border-radius:7px;">Approve</button><br>
            <button type=submit name="reject" value='.$uid.' style="padding: 8px 16px; border-radius:7px;">Reject</button>
            <input type="hidden" id="page" name="page" value="'.$page.'">
            </form>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';

            }
            
          }
          else
          {
            echo '<p>No New Fuel Cell Analyser and RRDE Instruments Booking as of now!</p>';
          }
          
          echo '<div class="centeritman">';
       echo ' <div class="pagination">'; 
       echo '<a href="#">&laquo;</a>';
          for($x=1; $x<=$pages; $x++)  
          {
           echo '<a href="?page='.$x.'" ';if($page==$x){echo 'class="active"';} echo '>'; echo''.$x.'</a>';
          }
          echo '<a href="#">&raquo;</a>';

          echo '</div>';
          echo '</div>';
        echo '</div>';
        echo '</div>';


    echo '<script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>';
?>