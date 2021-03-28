<?php
include ('php/session.php');
include ('php/login_check.php');
include ('php/config.php');
include ('php/calhead.php');

if(!isset($_SESSION["username"])) { header("location:login.php");}
?>
<noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>
<html>
  <head>
    <title>Pick a date</title>
    <link rel="stylesheet" href="css/tooltip.css">
    <style>
      .ui-datepicker .ui-state-default
       {
        color: green;
        background: ghostwhite;
       } 
     .ui-datepicker td.ui-state-disabled>span
       {
    color: red;
       }  
       input[type=checkbox] {
  transform: scale(1.5);
}
        
</style>
</head>
<body>
<script>
alert("Any publications/patents out of Department of Chemistrty facilities, please email the details to respective admin");
</script>
    <div class="row" style="margin-top:10px;">
      <div class="large-12">

        <?php
        if(!empty($_POST)) // Used to stop a user from jumping to this page without properly filling the last one
        {
        $inst_id=$_POST['inst_id'];
            if($inst_id)
            {
              $q = $mysqli->query("SELECT * FROM announcement");
              $result=$q->fetch_object();
              
              echo '<marquee style="color:blue">'.$result->announce.'</marquee>';
            }

            {
              echo '<p><h3>Book Instrument</h3></p>';
              echo '<table>';
              echo '<tr>';
              echo '<th>Instrument Name</th>';
              echo '<th>Instrument Issue Date</th>';
              echo '<th>Number of Slots</th>';
              echo '</tr>';
              $q = $mysqli->query("SELECT product_name, price FROM products WHERE id=$inst_id");
              $q_result=$q->fetch_object();
              echo '<tr>';
              echo '<td colspan="1" align="left">';
              echo $q_result->product_name;
              echo'</td>';
              echo '<form action="book_1.php" method="POST"">';
              $dates=array();
              $disabledates=array();
            
            
            if($inst_id==1)
            {
              $q = $mysqli->query("SELECT slot_date from slot_autosorbiq WHERE (slot_9 > 1000 OR slot_9 = 0 OR slot_9 = 2)");
              while($row=mysqli_fetch_assoc($q)){
                $dates[]=$row;
              }
              foreach($dates as $data)
              {
                $disabledates[]=$data["slot_date"];
              }
              // var_dump($disabledates); stanity check to see if all disabled dates are correctly captured. 
              echo '<script>
                $( function() {
                  var myarray =';?><?php echo json_encode($disabledates)?>;
                  <?php
                  echo'
                  function custom (date)
                  {
                    var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
                    var day = date.getDay();
                    return [ (myarray.indexOf(string) == -1) && (day !=0), "Available","Available" ];
                  };
                $("#datepicker" ).datepicker({
                  beforeShowDay: custom,
                  minDate: 4,
                  maxDate: 21
                });
              });
                </script>';

            }

            if($inst_id==1){
              echo '<td colspan="1" align="left"><input type="text" name="date" id="datepicker" readonly="true" required></td>';
              echo '<td>';
              echo '<select id="no_of_slots" required name="no_of_slots">
              <option value="1" selected>1</option>
              </select>';
            }

            // added id 15 instrument
            if($inst_id==15)
            {
              $q = $mysqli->query("SELECT slot_date from slot_fuelcell WHERE (slot_9 > 1000 OR slot_9 = 0 OR slot_9 = 2) AND (slot_10 > 1000 OR slot_10 = 0 OR slot_10 = 2) AND (slot_11 > 1000 OR slot_11 = 0 OR slot_11 = 2) AND (slot_12 > 1000 OR slot_12 = 0 OR slot_12 = 2) AND (slot_13 > 1000 OR slot_13 = 0 OR slot_13 = 2) AND (slot_14 > 1000 OR slot_14 = 0 OR slot_14 = 2) AND (slot_15 > 1000 OR slot_15 = 0 OR slot_15 = 2) AND (slot_16 > 1000 OR slot_16 = 0 OR slot_16 = 2)");
              while($row=mysqli_fetch_assoc($q)){
                $dates[]=$row;
              }
              foreach($dates as $data)
              {
                $disabledates[]=$data["slot_date"];
              }
              // var_dump($disabledates); stanity check to see if all disabled dates are correctly captured. 
              echo '<script>
                $( function() {
                  var myarray =';?><?php echo json_encode($disabledates)?>;
                  <?php
                  echo'
                  function custom (date)
                  {
                    var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
                    var day = date.getDay();
                    return [ (myarray.indexOf(string) == -1) && (day !=0), "Available","Available" ];
                  };
                $("#datepicker" ).datepicker({
                  beforeShowDay: custom,
                  minDate: 4,
                  maxDate: 21
                });
              });
                </script>';

            }
            if($inst_id==15){
              echo '<td colspan="1" align="left"><input type="text" name="date" id="datepicker" readonly="true" required></td>';
              echo '<td>';
              echo '<select id="no_of_slots" required name="no_of_slots">
              <option value="1" selected>1</option>
              <option value="2">2</option>
              </select>';
            }
            echo '</td>';
            echo'</tr>';
            echo'<tr>';
            echo'<td>';
            echo '<td style="text-align:center">';
            echo'<a style="color: green; text-align:center;" id="error-message" href="#" disabled title="">Select a Date</a>';
            echo ' <div class="tool-tip">
            <i class="tool-tip__icon">i</i>
            <p class="tool-tip__info">
              <span class="info"><span class="info__title">Free to Book:</span>Open for booking.</span>
              <span class="info"><span class="info__title">Booking in Progress:</span>An Active booking under process.</span>
              <span class="info"><span class="info__title">Select a Date:</span>Select a Date to know more.</span>
            </p>
          </div>';
            echo "<td>";
            echo '<button id="sb" type="submit" value='.$inst_id.' name="instrument" style="float:right;">Proceed</button>';
            echo '</form>';
          echo '</tr>';
          echo '</table>';
          echo '</div>';
          echo '</div>';
        }
        }
        else
        {
          echo '<p align="center"><img src="images/hack.jpg"></img></p>';
        }
          ?>
</body>
<footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>

<script>
$(function() {
    $("#datepicker").datepicker();
    $("#datepicker").on("change",function(){
        var date = $(this).val();
        var inst_id = <?php echo $inst_id; ?>;
        $("#error-message").load("bussy.php", {
            inst: inst_id,
            date: date
          });
    });
});
</script>
<script>
</script>