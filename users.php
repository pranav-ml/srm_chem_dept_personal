<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load composer's autoloader
require 'vendor/autoload.php';

if(($_SESSION["type"]!="superuser")) {
  header("location:index.php");
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
$perPage=10000; // Hard code specification for nof results per page


//Positioning
$start=($page>1) ? ($page*$perPage)-$perPage : 0;


// Query 
$a = $mysqli->query("SELECT * FROM users WHERE  ustatus=1 AND type='user' LIMIT {$start},{$perPage}");


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
    <title>SRM Chem Dept.</title>
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
        echo '<li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
        <option value="#">Requests</option>
        <option value="autosorbiqrequests.php">Autosorb IQ Requests</option>
      </select></li>
        <li><a href="bookingdata.php">View Booking Data</a></li>
        <li><a href="users.php">View Users Data</a></li>';
          }

        echo '<li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>
    <div class="row" style="margin-top:10px;">
      <div>';

           echo '<h1>Users Data</h1>';
          if($a->num_rows) {
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>REG ID</th>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '</tr>';
            while($obj = $a->fetch_object()) {
              $uid=$obj->id;
              $lq=$mysqli->query("SELECT * FROM users WHERE ustatus=1");
              $lq_result=$lq->fetch_object();
            // Start of first table
            echo '<tr>';
            echo '<td>'.$obj->id.'</td>';
            echo '<td>'.$obj->fname.' '.$obj->lname.'</td>';
            echo '<td>'.$obj->institute.'</td>';
            echo '<td>'.$obj->iid.'</td>';
            echo '<td>'.$obj->phno.'</td>';
            echo '<td>'.$obj->email.'</td>';
            }
            echo '</tr>';
            echo '</table>';
          }
          else
          {
            echo '<p>No user as of now!</p>';
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