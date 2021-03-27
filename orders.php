<?php
//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])){
  header("location:index.php");
}
include 'php/config.php';
?>

<?php
echo '<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Bookings</title>
  <link rel="stylesheet" href="css/foundation.css" />
<style type="text/css">
  .boxed {
  border: 1px solid green ;
}
</style>
  <style type="text/css">
  footer {
    padding: 1em;
    color: gray;
    background-color: white;
    clear: left;
    text-align: center;
    font-size: 10px;
}
.scrollit {
    overflow-x:scroll;
}
  table {
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
<style>
* {box-sizing: border-box}
body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none}
img {vertical-align: middle;}
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
}
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}
.active, .dot:hover {
  background-color: #717171;
}
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}
@-webkit-keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}
@keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}
@media only screen and (max-width: 300px) {
  .prev, .next,.text {font-size: 11px}
}
</style>
</head>
<body style="background-color:#f4f6f9;">
  <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a href="index.php"><img src="images/s.png" alt="SRM" height="95" width="95"></a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>
      <section class="top-bar-section">
      <ul class="right">
        <li><a href="facilities.php">Facilities</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="products.php">Instruments</a></li>';
        if(isset($_SESSION['username'])){
        echo'<li class="active"><a href="orders.php">My Bookings</a></li>';}
        echo'<li><a href="contact.php">Contact</a></li>'; // Echo Dump edit wisely
          if(isset($_SESSION['username'])){
            echo '<li><a href="account.php">My Account</a></li>';
            echo '<li><a href="logout.php">Log Out</a></li>';
          }
          else{
            echo '<li><a href="login.php">Log In</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
          }
			echo '<li><a href="images/User Manual.pdf" download>User Manual</a></li>';

        echo '</ul>';
      echo '</section>';
    echo'</nav>';
?>


    <div class="row" style="margin-top:10px;">
      <div class="large-12">
        <h3>My Bookings</h3>
        <input type="button" value="Print this page" onClick="window.print()">
        <hr>
        <?php
          $user = $_SESSION["username"];
          $q1=$mysqli->query("SELECT id from users WHERE email='".$user."'");
          $result_q1=$q1->fetch_object();
          $uid=$result_q1->id;


//Pagination -- Start--
 
  // Query to find the total no of rows in the orders table.
	
  $sql=$mysqli->query("SELECT COUNT(*) as count FROM orders WHERE user_id=$uid");// Show all bookings...
  

$sql_res=$sql->fetch_object();

// Total no of rows in the orders table.
$row=$sql_res->count;

// No of results to be displayed per page.
$page_rows=5;

//Page number of the last page
$last=ceil($row/$page_rows);
//Making sure than last can't be less than 1
if($last<1)
{
  $last=1;
}
//Defining the pagenum variable (tells us which page we are on).
$pagenum=1; // By default
// Get the pagenum variable from the url. If its not found set pagenum to 1 by default
if(isset($_GET['pn']))
{
  $pagenum=preg_replace('#[^0-9]#','', $_GET['pn']);
}
//Make sure the page number isn't below 1 or greater than $last variable. Else take action to correct.
if($pagenum<1)
{
  $pagenum=1;
}
else if($pagenum>$last)
{
  $pagenum=$last;
}
// Set the range of rows to query for each page based on th page number.
$limit='LIMIT '.($pagenum-1)*$page_rows.','.$page_rows.'';



//Main Query
$a = $mysqli->query("SELECT * from orders where user_id=$uid ORDER BY order_id DESC $limit");



// Initialize the Pagination control variable
$paginationCtrls='';
//If there is more than 1 page worth of results
if($last!=1)
{
  if($pagenum>1)
  {
    $previous=$pagenum-1;
    $paginationCtrls.='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">&laquo;</a> &nbsp;';
    // Render the clickable number links that appear on the left of the current page number.
    for($i=$pagenum-5; $i<$pagenum; $i++)
    {
      if($i>0)
      {
        $paginationCtrls.='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp;';
      } 
    }
  } 
  // Display the current page number that is inactive 

  $paginationCtrls.='<a class="active" href="#">'.$pagenum.'</a> &nbsp;';

  // Display the clicakble number that appear on the right of the current page number.
   for($i=$pagenum+1; $i<=$last; $i++)
    {
        $paginationCtrls.='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp;';
        if($i>= $pagenum+5)break;
    }

    if($pagenum!=$last)
    {
      $next=$pagenum+1;
      $paginationCtrls.='<a href= "'.$_SERVER['PHP_SELF'].'?pn='.$next.'">&raquo</a> &nbsp;';
    }
}   

// Pagination Ends

echo '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <!-- Javascripts are here-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book</title>
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
</head>';

          if($a->num_rows) {

            // echo '<script>alert("Welcome to Geeks for Geeks")</script>';

            echo '<script>alert("Please Note: Slots will be approved only after uploading the proof of the payment details. Proof of payment details must be uploaded within 48 hours of booking. Otherwise, the slots will be cancelled.")</script>';
            while($obj = $a->fetch_object()) {
              echo '<div>';

              
              $forpay = $mysqli->query("SELECT * FROM orders WHERE order_id=$obj->order_id");
              $forpayres = $forpay->fetch_object();
              $oi=$obj->order_id;
              echo '<p><h4>Booking ID ->'.$obj->order_id.'</h4></p>';
              if($forpayres->payinfo==NULL)
              {
              echo '
              <form action="payupload.php" method="post" enctype="multipart/form-data">
                <font color="red"> Select file to upload for payment information:<br><br><i>Booking won\'t be accepted unless proper payment information is provided.</i></font><br>
                Fill the Booking Information:<br>
                  <input type="file" style="text-align: center; margin: auto;" name="fileToUpload" id="fileToUpload">
                <br><br><button type="submit" name="submit" value="Upload File">Upload File</button>
                <input type="hidden" name="bookid" value="'.$oi.'"/>
                </form>';
              }
              else
              {
                echo'<center><font color="green"><i>Payment information for this booking has already been updated.</i></font></center>';
              }
              echo '<div class="scrollit">';
              echo '<table>';
              echo '<tr>';
              echo '<th>Time of Booking</th>';
              echo '<th>Product Name</th>';
              echo '<th>Date of the Order</th>';
              echo '<th>Slot Time</th>';
              // Table change as product name changes
              if($obj->product_name=='Autosorb iQ-C-AG/MP/XR')
              {
                $q1=$mysqli->query("SELECT * FROM autosorbiq_order_details WHERE order_id=$oi");
                $q1_result=$q1->fetch_object();
                echo '<th>Nature of Sample</th>';
                echo '<th>Porous Details</th>';
                echo '<th>Analysis Type</th>';
                echo '<th>Degassing Temperature</th>';
                echo '<th>Degassing Conditions</th>';
                echo '<th>Additional Details</th>';
                echo '<th>Status</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>';
                echo $obj->timestamp;
                echo '</td>';
                echo '<td>';
                echo $obj->product_name;
                echo '</td>';
                echo '<td>';
                echo $obj->date_of_order;
                echo '</td>';
                echo '<td>';
                echo $obj->slot_time;
                echo '</td>';
                echo '<td>';
                echo $q1_result->nature_of_sample;
                echo '</td>';
                echo '<td>';
                echo $q1_result->porous_nature;
                echo '</td>';
                echo '<td>';
                echo $q1_result->analysis_type;
                echo '</td>';
                echo '<td>';
                echo $q1_result->degassing_temp;
                echo '</td>';
                echo '<td style="word-break:break-all;">';
                echo $q1_result->degassing_condition;
                echo '</td>';
                echo '<td style="word-break:break-all;">';
                echo $q1_result->additional_details;
                echo '</td>';
                if($obj->status==0)
              {
              echo '<td>'.'Pending Admin Approval'.'</td>';
              }
              else if($obj->status==1)
              {
                // echo '<td>'.'Booking Approved'.'</td>';
                echo '<td>'.'<p align="center" style="color:blue">Booking Approved.</p>'.'</td>';
              }


              }

              echo '</tr>';
              echo '</table>';
              if($obj->date_of_order>=date("Y-m-d")){
              echo '</div>';
              echo '
              <form action="cancelit.php" method=POST>
              <button type="submit" name="order_no" value='.$oi.'> Cancel Order</button>
              </form>';

              echo '</div>';
              }
              echo'<hr><hr>';

            }
          }
          else
          {
            echo '<p><strong>Book something and it will appear here!</strong></p>';
          }
        
     echo'</div>';
    echo '</div>';


    echo '<div class="centeritman">';
    echo ' <div class="pagination">'; 
    echo'<br>';
    echo $paginationCtrls;

       echo '</div>';
       echo '</div>';
    ?>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>