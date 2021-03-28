<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');
if($_SESSION["type"]=="user" || $_SESSION["type"]=="") {
header("location:index.php");
}
?>
<?php
if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['comment']))
{
  $tcom=$_POST['tcomment'];
  $comm=$_POST['comment'];

  $upd=$mysqli->query("UPDATE orders SET ucomment = '$tcom' WHERE order_id = '$comm'");
  header("Location: bookingdata.php");
}

?>

<?php
$filter_flag=0;
$count=0;
$admin_id=$_SESSION['id'];


if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['submitb']))
{
  $filter_flag=1;
  $ssd=$_POST['datestart'];
  $eed=$_POST['dateend'];
  $inst=$_POST['inst'];
  $sd=date('Y-m-d', strtotime($ssd));
  $ed=date('Y-m-d', strtotime($eed));

}

//Pagination -- Start--
 
  // Query to find the total no of rows in the orders table.
	if($admin_id==1)
    {
        $sql=$mysqli->query("SELECT COUNT(*) as count FROM orders WHERE status=1 AND order_id>1000");// Show all bookings...
    }
	else if($admin_id==2)
    {
       $sql=$mysqli->query("SELECT COUNT(*) as count FROM orders WHERE status=1 AND order_id>1000 AND product_name='Autosorb iQ-C-AG/MP/XR'"); // Show bookings only for the halls under that admin...
    }
  else if($admin_id==16)
  {
      $sql=$mysqli->query("SELECT COUNT(*) as count FROM orders WHERE status=1 AND order_id>1000 AND product_name='Fuel Cell Analyser and RRDE Instruments'"); // Show bookings only for the halls under that admin...
  }

  $sql_res=$sql->fetch_object();
  
  // Total no of rows in the orders table.
  $row=$sql_res->count;
  // No of results to be displayed per page.
  
if($filter_flag==1)
{
    $page_rows=100000;
}
else
{
  $page_rows=8;
}
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


// The Main Query for results 
if($filter_flag==1) // This executes when the user chooses to filter the result.
        {
          $perPage=1000; 
          if($inst=='Autosorb iQ-C-AG/MP/XR')
          {
           $a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * from orders,users WHERE user_id=id AND WHERE product_name='Autosorb iQ-C-AG/MP/XR' AND status=1 AND date_of_order BETWEEN '$sd' AND '$ed' ORDER BY order_id DESC  ");
          }
          if($inst=='Fuel Cell Analyser and RRDE Instruments')
          {
           $a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * from orders,users WHERE user_id=id AND WHERE product_name='Fuel Cell Analyser and RRDE Instruments' AND status=1 AND date_of_order BETWEEN '$sd' AND '$ed' ORDER BY order_id DESC  ");
          }
        }

else if($filter_flag==0) // This executes when no filtering is applied ie by default.
        {
          if($_SERVER['REQUEST_METHOD']!="POST")
          {
            if($_SESSION["type"]=="autosorbiqadmin")
            {
              $a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * from orders,users WHERE user_id=id AND product_name='Autosorb iQ-C-AG/MP/XR' AND status=1 ORDER BY order_id DESC $limit");
            }
            if($_SESSION["type"]=="fuelcelladmin")
            {
              $a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * from orders,users WHERE user_id=id AND product_name='Fuel Cell Analyser and RRDE Instruments' AND status=1 ORDER BY order_id DESC $limit");
            }
            if($_SESSION["type"]=="superuser")
            {
              $a = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS * from orders,users WHERE user_id=id AND status=1 ORDER BY order_id DESC $limit");
            }

          }

        }

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

// Pagination

// Query that are to be retained

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
</head>
  <body>
    <!--Navigation Bar-->
    <nav class="top-bar" data-topbar role="navigation">
      <ul class="title-area">
        <li class="name">
          <h1><a>Dept.  of Chem. Administrator</a></h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
      </ul>
      <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">';?>
       <?php
       if($_SESSION['type']=="autosorbiqadmin"){echo'<li><a href="block.php">Slot Blocking</a></li>
          <li><a href="autosorbiqrequests.php">Autosorb iQ Requests</a></li>
        <li><a href="autosorbiq_cancellation.php">Autosorb iQ Cancellation Requests</a></li>
        <li><a href="bookingdata.php">Autosorb iQ Booking Data</a></li>';}
        
        
        if($_SESSION['type']=="fuelcelladmin"){echo'<li><a href="block.php">Slot Blocking</a></li>
          <li><a href="fuelcellrequests.php">FCA and RRDE Instruments Requests</a></li>
        <li><a href="fuelcell_cancellation.php">FCA and RRDE Instruments Cancellation Requests</a></li>
        <li><a href="bookingdata.php">Multiwave Pro Booking Data</a></li>';}
        
        if($_SESSION['type']=="superuser"){
            echo '<li><a href="block.php">Slot Blocking</a></li>
            <li><a href="registration.php">Registration Requests</a></li>
            <li><a href="analysis.php">Analysis</a></li>
            <li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
              <option value="#">Requests</option>
              <option value="autosorbiqrequests.php">Autosorb iQ Requests</option>
              <option value="fuelcellrequests.php">FCA and RRDE Instruments Requests</option>
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
      echo '  <li><a href="logout.php">Log Out</a></li>';
       echo'</ul>
      </section>
    </nav>
    <!-- End of the Navigation Bar-->
    <!-- The Filter Section-->
    <div class="row">
      <table>
      <form action="bookingdata.php" method=POST>
        <tr>
      <td><input type="button" value="Print this page" onClick="window.print()"></td>
      <td>Select Start Date <input type="text"  name="datestart" id="datepicker" autocomplete="off"> </td>
      <td>Select End Date <input type="text"  name="dateend" id="datepicker2" autocomplete="off"> </td>
      <td>
          <select name="inst">';?>
          <?php
          if($_SESSION['type']=="autosorbiqadmin")
          {
            echo'<option value="Autosorb iQ-C-AG/MP/XR">Autosorb iQ-C-AG/MP/XR</option>';
          }

          if($_SESSION['type']=="fuelcell")
          {
            echo'<option value="FCA and RRDE Instruments">FCA and RRDE Instruments</option>';
          }

          if($_SESSION['type']=="superuser")
          {
            echo'<option value="Autosorb iQ-C-AG/MP/XR">Autosorb iQ-C-AG/MP/XR</option>';
            echo'<option value="FCA and RRDE Instruments">FCA and RRDE Instruments</option>';
          }
          ?>
          <?php
         echo'</select>
      </td>
      <td><button type=submit  name="submitb" style="float:right;">Submit</button></td>
      </tr>
      </form>
      </table>
    </div>
    <!--End of the Filter Section-->
    <div class="row" style="margin-top:10px;">';


        if($a->num_rows)
           {
            while($obj = $a->fetch_object())
            {
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
              echo '<center><font color="green"><i>Download the payment information of this booking by <a href='.$link.' download> clicking here</a>.<br></i>  Bank Name: <b>'.$obj->tbank.'</b> Transaction ID: <b>'.$obj->tid.'</b> Transaction Date: <b>'.$obj->tdate.'</b> Amount: <b>'.$obj->tamm.' </b></font></center>';
            }
              $count++;

            if($obj->product_name=='Autosorb iQ-C-AG/MP/XR')
            {
              $oi=$obj->order_id;
              $q1=$mysqli->query("SELECT * FROM autosorbiq_order_details WHERE order_id=$oi");
              $q1_result=$q1->fetch_object();

            echo '<br>Order Details for Booking Id: '.$obj->order_id.'';
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Time of Booking</th>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '<th>Date of Usage</th>';
            echo '<th>Slot</th>';
            echo '<th>Instrument Name</th>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>'.$obj->timestamp.'</td>';
            echo '<td>'.$obj->fname.' '.$obj->lname.'</td>';
            echo '<td style="word-break:break-all;">'.$obj->institute.'</td>';
            echo '<td>'.$obj->iid.'</td>';
            echo '<td>'.$obj->phno.'</td>';
            echo '<td>'.$obj->email.'</td>';
            echo '<td>'.$obj->date_of_order.'</td>';
            echo '<td>'.$obj->slot_time.'</td>';
            echo '<td>'.$obj->product_name.'</td>';
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

            echo '<tr>';
            echo '<td>'.$q1_result->nature_of_sample.'</td>';
            echo '<td>'.$q1_result->porous_nature.'</td>';
            echo '<td style="word-break:break-all;">'.$q1_result->analysis_type.'</td>';
            echo '<td>'.$q1_result->degassing_temp.'</td>';
            echo '<td style="word-break:break-all;">'.$q1_result->degassing_condition.'</td>';
            echo '<td style="word-break:break-all;">'.$q1_result->additional_details.'</td>';
            echo '</tr>';
            echo '</table>';

            //third table
            echo '<table class="mytable">';
            echo '<tr>';
            echo '<td>Booking Comment Made:<br><font color="blue"><i>'.$obj->ucomment.'</font>';
            echo '</i></td>';
            echo '</tr>';
            echo '</table>';
            echo '<table class="mytable">';
            echo '<tr>';
            echo '<td>';
            echo '<form action ="bookingdata.php" method=POST>
            <textarea name="tcomment" placeholder="Your comment will be displayed above."></textarea>';
            //echo '<textarea name="comment" placeholder="Please Enter your comment about the booking here."></textarea>';
            echo '<td>';
            echo '
            <button type=submit name="comment" value='.$obj->order_id.' style="padding: 8px 8px; border-radius:7px;">Comment/Update Comment</button><br>
            </form>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
            } // IF END FOR Autosorb IQ

            if($obj->product_name=='Fuel Cell Analyser and RRDE Instruments')
            {
              $oi=$obj->order_id;
              $q1=$mysqli->query("SELECT * FROM fuelcell_order_details WHERE order_id=$oi");
              $q1_result=$q1->fetch_object();

            echo '<br>Order Details for Booking Id: '.$obj->order_id.'';
            echo '<table class="absorbing-column">';
            echo '<tr>';
            echo '<th>Time of Booking</th>';
            echo '<th>Name</th>';
            echo '<th>Institute</th>';
            echo '<th>Institute ID</th>';
            echo '<th>Phone No</th>';
            echo '<th>Email</th>';
            echo '<th>Date of Usage</th>';
            echo '<th>Slot</th>';
            echo '<th>Instrument Name</th>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>'.$obj->timestamp.'</td>';
            echo '<td>'.$obj->fname.' '.$obj->lname.'</td>';
            echo '<td style="word-break:break-all;">'.$obj->institute.'</td>';
            echo '<td>'.$obj->iid.'</td>';
            echo '<td>'.$obj->phno.'</td>';
            echo '<td>'.$obj->email.'</td>';
            echo '<td>'.$obj->date_of_order.'</td>';
            echo '<td>'.$obj->slot_time.'</td>';
            echo '<td>'.$obj->product_name.'</td>';
            echo '</tr>';
            echo '</table>';

            //third table
            echo '<table class="mytable">';
            echo '<tr>';
            echo '<td>Booking Comment Made:<br><font color="blue"><i>'.$obj->ucomment.'</font>';
            echo '</i></td>';
            echo '</tr>';
            echo '</table>';
            echo '<table class="mytable">';
            echo '<tr>';
            echo '<td>';
            echo '<form action ="bookingdata.php" method=POST>
            <textarea name="tcomment" placeholder="Your comment will be displayed above."></textarea>';
            //echo '<textarea name="comment" placeholder="Please Enter your comment about the booking here."></textarea>';
            echo '<td>';
            echo '
            <button type=submit name="comment" value='.$obj->order_id.' style="padding: 8px 8px; border-radius:7px;">Comment/Update Comment</button><br>
            </form>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
            } // IF END FOR Fuel Cell Analyser and RRDE Instruments


          } // end of while
          echo 'Total Bookings : ';echo $count;
        }// end of if
          else
          {
            echo 'No Bookings Found in the searched range.';
          }

          echo '<div class="centeritman">';
       echo ' <div class="pagination">'; 
       echo'<br>';
       echo $paginationCtrls;

          echo '</div>';
          echo '</div>';

          
        echo '</div>';
        echo '</div>';




        ?>
        <script src="js/foundation.min.js"></script>
        <script>
          $(document).foundation();
        </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>