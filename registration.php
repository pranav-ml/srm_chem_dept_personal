<?php 
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load composer's autoloader
require 'vendor/autoload.php';


if($_SESSION["type"]!="superuser") {
  header("location:index.php");
}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['approve']))
{
  $booya=$_POST['approve'];
  $result = $mysqli->query("UPDATE users SET ustatus=1 WHERE id=$booya");
  echo'accepted';

  $add=$mysqli->query("SELECT email FROM users WHERE id=$booya");
  $add_res=$add->fetch_object();
  $eemail=$add_res->email;

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
    $mail->Subject = 'Registration Approval - Dept. of Chem. Admin';
    $mail->Body    = '
<h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br> 
You have been approved by the Administrator.<br>
If you have already verified your email, you can now login.<br><br><br>
Regards<br>Dept. of Chem. Team'; // Our message above including the link
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

}// end if


if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['reject']))
{
  $booya=$_POST['reject'];

  $add=$mysqli->query("SELECT email FROM users WHERE id=$booya");
  $add_res=$add->fetch_object();
  $eemail=$add_res->email;

  $result = $mysqli->query("DELETE FROM users WHERE id=$booya");
  echo 'rejected';

  

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
    $mail->Subject = 'Registration Rejected - Dept. of Chem. Admin';
    $mail->Body    = '
<h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br> 
You Registration request to use Dept. of Chem. portal has been rejected by the Administrator.<br>
<br><br><br>
Regards<br>Dept. of Chem. Team'; // Our message above including the link
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

}


// Handel pageination 
$page= isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage=5; // Hard code specification for nof results per page
//Positioning
$start=($page>1) ? ($page*$perPage)-$perPage : 0;

// Query 
$a=$mysqli->query("SELECT SQL_CALC_FOUND_ROWS id,fname,lname,institute,iid,email,phno from users WHERE ustatus=0 LIMIT {$start},{$perPage}");
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
    <title>Registration</title>
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
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
}
.centeritman
{
  text-align:center;
}
table td {
    border: 1px solid #ccc;
}
table .absorbing-column {
    width: 100%;
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

.pagination a:hover:not(.active) {background-color: #ddd;}   </style>
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
      <li><a href="registration.php">Registration Requests</a></li>
      <li><a href="analysis.php">Analysis</a></li>';


        if($_SESSION["type"]=="superuser"){

            echo '<li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
            <option value="#">Requests</option>
            <option value="autosorbiqequests.php">Autosorb IQ Requests</option>
          </select></li>
        <li><a href="bookingdata.php">View Booking Data</a></li>
        <li><a href="users.php">View Users Data</a></li>';
          }
    
        echo '<li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>
    <div class="row" style="margin-top:10px;">';

          //$result = $mysqli->query("SELECT * FROM users WHERE ustatus=0");
          echo '<h1>Registration Requests</h1>';
          if($a->num_rows) {
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '<th>Approval</th>';

            echo '</tr>';
            while($obj = $a->fetch_object()) {
              $uid=$obj->id;
            echo '<tr>';
           echo '<td>'.$obj->fname.' '.$obj->lname.'</td>';
           echo '<td>'.$obj->institute.'</td>';
           echo '<td>'.$obj->iid.'</td>';
           echo '<td>'.$obj->phno.'</td>';
           echo '<td>'.$obj->email.'</td>';
            echo '<td>';
            echo '<form action ="registration.php" method=POST>
            <button type=submit name="approve" value='.$uid.' style="padding: 8px 8px; border-radius:7px;">Approve</button><br>
            <button type=submit name="reject" value='.$uid.' style="padding: 8px 16px; border-radius:7px;">Reject</button>
            </form>';
            echo '</td>';
            echo '</tr>';
            }
            echo '</table>';


          }
          else
          {
            echo '<p>No New Requests as of now!</p>';
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
    </div>
    </div>
    </div>
  </body>

  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>';
?>
