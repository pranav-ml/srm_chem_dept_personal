<?php

if(session_id() == '' || !isset($_SESSION)){session_start();}
include ('php/session.php');
include ('php/config.php');

if($_SESSION["type"]!="superuser") {
  header("location:index.php");
}

if($_SERVER['REQUEST_METHOD']=="POST" and isset($_POST['announcement']))
{$booya=$_POST['announcement'];

$result = $mysqli->query("UPDATE announcement SET announce = '.$booya.'");
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

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Analysis</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="https://cdn.plot.ly/plotly-1.2.0.min.js"></script>

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
table td {
    border: 1px solid #ccc;
}
table .absorbing-column {
    width: 100%;
}

    </style>
  </head>
  <body>
    <nav class="top-bar expanded" data-topbar role="navigation">
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

        <?php
        if($_SESSION['type']=="superuser"){
            echo '
            <li><a href="analysis.php">Analysis</a></li>
            <li><select name="Requests" onchange="location = this.value;" style="padding-bottom: 4px;padding-top: 4px;margin-bottom: 8px;">
              <option value="#">Requests</option>
              <option value="autosorbiqrequests.php">Autosorb IQ Requests</option>
              <option value="fuelcellrequests.php">FCA And RRDE Instruments Requests</option>
            </select></li>
        <li><a href="bookingdata.php">View Booking Data</a></li>
        <li><a href="users.php">View Users Data</a></li>';          }
          ?>
        <li><a href="logout.php">Log Out</a></li>
        </ul>
      </section>
    </nav>
    <div class="row" style="margin-top:10px;">
      <div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
  <?php
  // SQL Queries for Plot generation
      $q1=$mysqli->query("SELECT DISTINCT(date_of_order) FROM orders");
      $q2=$mysqli->query("SELECT * FROM orders WHERE product_name='Autosorb iQ-C-AG/MP/XR'");
      $q3=$mysqli->query("SELECT * FROM orders WHERE product_name='XRD'");
      $q4=$mysqli->query("SELECT * FROM orders WHERE product_name='MRS'");
      $q5=$mysqli->query("SELECT * FROM orders WHERE product_name='HRSEM'");

// Code for the Total Bar in Plotly

  $date1=array(); // X-Axis Array for total
  $count1=array(); // Y-Axis Array for total
  $i;
  $i1=0;
  while($result1=$q1->fetch_object())
  {
  $date1[$i1]=$result1->date_of_order;
  $i1++;
  }

  for($i=0;$i<count($date1);$i++)
  {
    $q=$mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE date_of_order='$date1[$i]'");
    $q_result=$q->fetch_object();
    $count1[$i]=$q_result->total;
  }

// END OF CODE FOR Total

// Code for the HRTEM Bar in Plotly

  $date2=array();
  $count2=array();

$i2=0;
while($result2=$q2->fetch_object())
{
  $date2[$i2]=$result2->date_of_order;
  $i2++;
}

for($i=0;$i<count($date2);$i++)
{
  $q=$mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE product_name='Autosorb iQ-C-AG/MP/XR' AND date_of_order='$date2[$i]'");
  $q_result=$q->fetch_object();
  $count2[$i]=$q_result->total;
}

// END OF CODE FOR HRTEM

// Code for the instrument 2 Bar in Plotly

$date3=array();
$count3=array();
$i3=0;
while($result3=$q3->fetch_object())
{
$date3[$i3]=$result3->date_of_order;
$i3++;
}

for($i=0;$i<count($date3);$i++)
{
  $q=$mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE product_name='instrument 2' AND date_of_order='$date3[$i]'");
  $q_result=$q->fetch_object();
  $count3[$i]=$q_result->total;
}

  // END OF CODE FOR instrument 2
// Code for the instrument 3 Bar in Plotly

$date4=array();
$count4=array();
$i4=0;
while($result4=$q4->fetch_object()){
$date4[$i4]=$result4->date_of_order;
$count4[$i4]=1;
$i4++;}

for($i=0;$i<count($date4);$i++)
{
  $q=$mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE product_name='instrument 3' AND date_of_order='$date4[$i]'");
  $q_result=$q->fetch_object();
  $count4[$i]=$q_result->total;
}


$date5=array();
$count5=array();
$i5=0;
while($result5=$q5->fetch_object()){
$date5[$i5]=$result5->date_of_order;
$count5[$i5]=1;
$i5++;}

for($i=0;$i<count($date5);$i++)
{
  $q=$mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE product_name='instrument 4' AND date_of_order='$date5[$i]'");
  $q_result=$q->fetch_object();
  $count5[$i]=$q_result->total;
}
// END OF CODE FOR instrument 4

?>


<script type="text/javascript" language="javascript">
    var x1 = <?php echo json_encode($date1); ?>;
    var y1 = <?php echo json_encode($count1); ?>;

    var x2 = <?php echo json_encode($date2); ?>;
    var y2 = <?php echo json_encode($count2); ?>;

    var x3 = <?php echo json_encode($date3); ?>;
    var y3 = <?php echo json_encode($count3); ?>;

    var x4 = <?php echo json_encode($date4); ?>;
    var y4 = <?php echo json_encode($count4); ?>;

    var x5 = <?php echo json_encode($date5); ?>;
    var y5 = <?php echo json_encode($count5); ?>;
</script>

<script>
  var trace1 = {
  x: x3,
  y: y3,
  mode: 'lines',
  type: 'bar',
  name: 'instrument 2',
  line: {
    dash: 'instrument 2',
    width: 4
  }
};

var trace2 = {
  x: x4,
  y: y4,
  mode: 'lines',
  type: 'bar',
  name: 'instrument 3',
  line: {
    dash: 'instrument 3',
    width: 4
  }
};

var trace3 = {
  x: x2,
  y: y2,
  mode: 'lines',
  type: 'bar',
  name: 'Autosorb iQ',
  line: {
    dash: 'Autosorb iQ',
    width: 4
  }
};

var trace5 = {
  x: x5,
  y: y5,
  mode: 'lines',
  type: 'bar',
  name: 'instrument 4',
  line: {
    dash: 'instrument 4',
    width: 4
  }
};

var trace4 = {
  x: x1,
  y: y1,
  mode: 'lines',
  type: 'bar',
  name: 'Total',
  line: {
    dash: 'Total',
    width: 4
  }
};

var data = [trace1, trace2, trace3, trace4,trace5];

var layout = {
  title: 'Analysis',
   xaxis: {
    title: 'Dates',
    titlefont: {
      family: 'Courier New, monospace',
      size: 18,
      color: '#7f7f7f'
    }
    },
    yaxis: {
    title: 'No of Bookings',
    titlefont: {
      family: 'Courier New, monospace',
      size: 18,
      color: '#7f7f7f'
    }
    },
  legend: {
    y: 1,
    traceorder: 'reversed',
    font: {
      size: 16
    },
    yref: 'paper'
  }
};

Plotly.newPlot('myDiv', data, layout);
  </script>
    </div>
    <div class="row">
      <form action="analysis.php" id="form1" method=POST>
      <input type="text" name="announcement" autocomplete="off"><br>
      </form>

<button type="submit" form="form1" value="Submit">Make Announcement</button>
    </div>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>