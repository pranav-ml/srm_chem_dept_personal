<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load composer's autoloader
require 'vendor/autoload.php';
?>

<!-- 
    The Mailing Script is below:
-->
<?php
include ('php/session.php');
include ('php/login_check.php');
include ('php/config.php');
include ('php/head.php');
include ('php/data.php');
if(!isset($_SESSION["username"])) {header("location:index.php");}
$email=$_SESSION['username'];
$fname=$_SESSION['fname'];
?>
<?php
function maill($body){
$email=$_SESSION['username'];
$fname=$_SESSION['fname'];
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
     $mail->addAddress($email, $fname);     // Add a recipient
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Booking Status';
    $mail->Body    = $body;
 // Our message above including the link
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
} catch (Exception $e) {

}
}
?>
<!-- 
    The Mailing Script Ends:
-->


<!--DO NOT EDIT THE CODE BELOW IF YOU DON'T KNOW WHAT YOU ARE DOING!-->
<!--DO NOT EDIT THE CODE BELOW IF YOU DON'T KNOW WHAT YOU ARE DOING!-->
<!--DO NOT EDIT THE CODE BELOW IF YOU DON'T KNOW WHAT YOU ARE DOING!-->

    <?php
    //var_dump($_POST); // Sanity Check for POST array.
    // GLOBAL FLAGS
    $order_limit_reached=0; // Limit at max of 1 order per day per user.

if(!empty($_POST)) // Used to stop a user from jumping to this page without properly filling the last one
{

    // Get the details of the user from the database.
    $un=$_SESSION["username"]; // $un has the user's email address.
    $q1=$mysqli->query("SELECT id, fname, lname FROM users WHERE email='$un'");
    $q1_result=$q1->fetch_object();
    $id=$q1_result->id; // store id from query.
    $first_name=$q1_result->fname; //store first name from query.
    $last_name=$q1_result->lname; // store ladt name from query.
    $order_date=$_SESSION["date"]; // store the order_date from SESSIONS
    $pidi=$_SESSION['instrument'];
    $no_of_slots = $_SESSION['no_of_slots'];
    $force_one_slot = $_SESSION['force_one_slot'];
    if($pidi==1)
    {
        $name="Autosorb iQ-C-AG/MP/XR";
    }

    if($pidi == 1)
    {
    // RUN QUERY ON DATABASE TO SEE IF USER HAS PLACED AN ORDER.
    $result=$mysqli->query("SELECT * FROM orders WHERE product_name='Autosorb IQ' AND user_id=$id AND date_of_order BETWEEN(NOW()-INTERVAL 7 DAY) AND (NOW()+INTERVAL 15 DAY)");
   
    if($result->num_rows >=2) // IF YES, STOP HIM From making another order
    {
      echo "<br><br>";
      echo "<h3 align='center'>Sorry!</h3>";
      echo "<br>";
      echo "<p align='center'>You have already made two bookings. Booking Limit is reached as only 2 bookings per person per 14 days is allowed per instrument.";
      $order_limit_reached=1;
    }
    }
    if($pidi == 3)
    {
    // RUN QUERY ON DATABASE TO SEE IF USER HAS PLACED AN ORDER.
    $result=$mysqli->query("SELECT * FROM orders WHERE product_name='XRD' AND user_id=$id AND date_of_order BETWEEN(NOW()-INTERVAL 7 DAY) AND (NOW()+INTERVAL 15 DAY)");
   
    if($result->num_rows >=2) // IF YES, STOP HIM From making another order
    {
      echo "<br><br>";
      echo "<h3 align='center'>Sorry!</h3>";
      echo "<br>";
      echo "<p align='center'>You have already made two bookings. Booking Limit is reached as only 2 bookings per person per 14 days is allowed per instrument.";
      $order_limit_reached=1;
    }
    }

    $go_for_print=0;

    if($order_limit_reached==0) // Print the Order Details only if user has not made more than 1 order.
    {

        echo ' <div class="myown" style="margin-top:10px;" width: 400px> ';
        echo  ' <div class="myown" width: 400px> ';
        echo ' <p><h3>Booking Details</h3></p> ';
        echo'<input type="button" value="Print this page" onClick="window.print()">';


        //Code to display the slot section of the table.
        if($no_of_slots == 2 && $force_one_slot == false) // If we are booking 2 slots.
        {
            $slot_time_1 = $_POST['slot_1'];
            $slot_time_2 = $_POST['slot_2'];
        }
        else if($no_of_slots == 1 || $force_one_slot == true) // Else if we are only booking a single slot.
        {
             $slot_time_1 = $_POST['slot_1'];
        }
        $pid=$_SESSION['instrument'];
        if($pid==1)
        {
             $slot_c_1 = $autosorbiq_map[$slot_time_1];
        }

        //Code to display the instrument name and price section in the table

        $q=$mysqli->query("SELECT product_code, product_name, price FROM products WHERE id=$pid");
        $result=$q->fetch_object();
        $price="Check Service Section";
        $product_name=$result->product_name; // Product Name
        $product_code=$result->product_code; // Product Code
        // End of code to display the instrument name and price section in the table


        // Timestamp for when the order is placed
        $ts = date("Y-m-d h:i:sa", time());
        // Order id manual creation.
        $oi;// Order_id

        $q3=$mysqli->query("SELECT * FROM orders");
        if(!$q3->fetch_object())
        {
            $oi=1001;
        }
        else
        {
            $q4=$mysqli->query("SELECT MAX(order_id) AS prev_order_id FROM orders");
            $result_q4=$q4->fetch_object();
            $oi=$result_q4->prev_order_id+1;
        }

        $in_id=(int)$id;

        //check
        if($no_of_slots == 1 || $force_one_slot == true)
        {
            $push=$mysqli->query("SELECT * FROM orders WHERE date_of_order = '$order_date' AND slot_time = '$slot_time_1' AND product_code = '$product_code'");
            if($push->num_rows>0)
            {
                header('Location: index.php');
                exit();
            }
        }
        else if($no_of_slots == 2 && $force_one_slot == false)
        {
            $push1=$mysqli->query("SELECT * FROM orders WHERE date_of_order = '$order_date' AND slot_time = '$slot_time_1' AND product_code = '$product_code'");

            $push2=$mysqli->query("SELECT * FROM orders WHERE date_of_order = '$order_date' AND slot_time = '$slot_time_2' AND product_code = '$product_code'");
            if($push1->num_rows>0 || $push2->num_rows>0)
            {
                header('Location: index.php');
                exit();
            }
        }
        //checkend

        if($no_of_slots == 1 || $force_one_slot == true)
        {
        $push=$mysqli->query("INSERT INTO orders (user_id, date_of_order, slot_time, product_code, product_name, order_id, slot_no,timestamp) VALUES ($in_id,'$order_date','$slot_time_1','$product_code','$product_name',$oi, '$slot_c_1','$ts')");
        }
        else if($no_of_slots == 2 && $force_one_slot == false)
        {
            $push_1=$mysqli->query("INSERT INTO orders (user_id, date_of_order, slot_time, product_code, product_name, order_id, slot_no, timestamp) VALUES ($in_id,'$order_date','$slot_time_1','$product_code','$product_name',$oi, '$slot_c_1','$ts')");
            $oi = $oi + 1; // Increment the order id as the 2 slot booking are treated as 2 seperate bookings.
            $push_2=$mysqli->query("INSERT INTO orders (user_id, date_of_order, slot_time, product_code, product_name, order_id, slot_no, timestamp) VALUES ($in_id,'$order_date','$slot_time_2','$product_code','$product_name',$oi, '$slot_c_2', '$ts')");
        }

     // End of Push

        // Blocking the Slot
        $slot_blocked=0; // By Default te slot is not booked.


        if($no_of_slots == 1 || $force_one_slot == true) // IF only 1 slot is booked.
        {
            //Getting the order_id which is pushed into the slot
            $booya5=(string)$oi; // Converted the order id that is generated above into string.

             // Getting the table name form the link table using the pid.
             $q5=$mysqli->query("SELECT table_name from link WHERE pid=$pid");
             $result_q5=$q5->fetch_object();
             $table_name=$result_q5->table_name;



            $slot_block=$mysqli->query("UPDATE $table_name SET $slot_c_1='$booya5' WHERE slot_date='$order_date'");
            if($slot_block)
            {
                $slot_blocked=1;
            }
            else
            {
                $slot_blocked=0;
            }
            $go_for_print=1;
        }
        else if($no_of_slots == 2 && $force_one_slot == false) // If 2 slots are booked.
        {
            
            //Getting the order_id which is pushed into the slot
            $oi = $oi - 1;; // Decrement the order id beacuse we want to insert the last order first.
            $booya5=(string)$oi; // Converted the order id that is generated above into string.

             // Getting the table name form the link table using the pid.
             $q5=$mysqli->query("SELECT table_name from link WHERE pid=$pid");
             $result_q5=$q5->fetch_object();
             $table_name=$result_q5->table_name; 
            $slot_block_1=$mysqli->query("UPDATE $table_name SET $slot_c_1='$booya5' WHERE slot_date='$order_date'");

            $oi = $oi + 1;
            $booya6 =(string)$oi;
            $slot_block_2=$mysqli->query("UPDATE $table_name SET $slot_c_2='$booya6' WHERE slot_date='$order_date'");
            if($slot_block_1 && $slot_block_2)
            {
                $slot_blocked=1;
            }
            else
            {
                $slot_blocked=0;
            }
            $go_for_print=1;
        }

        
    }


    if($go_for_print==1)
    {

    // End of Slot Blocking

    //Pushing the order prefrences and details into the respective table.

    if($pid==1)
    {
        if($no_of_slots == 1 || $force_one_slot == true)
        {
            $sample_nature=$_POST['sample_nature'];
            $porous_nature=$_POST['porous_nature'];
            $analysis_type=$_POST['analysis_type'];
            $degassing_temp=mysqli_real_escape_string($mysqli,$_POST['degassing_temp']);
            $degassing_condition=mysqli_real_escape_string($mysqli,$_POST['degassing_condition']);
            $additional_details=mysqli_real_escape_string($mysqli,$_POST['additional_details']);
            $od_1=$mysqli->query("INSERT INTO autosorbiq_order_details(order_id, nature_of_sample, porous_nature, analysis_type, degassing_temp, degassing_condition, additional_details)VALUES($oi, '$sample_nature','$porous_nature', '$analysis_type', '$degassing_temp','$degassing_condition','$additional_details')");
        }
    }

    // End of push of prefrences and details into respective tables

        // Drawing the order confirmation table.
        echo "<br>";
        echo "<br>";
        echo '<div class="scrollit">';
        echo '<table cellpadding="2" cellspacing="2" width: 400px>';
        echo "<tr>";
        echo "<th>Name</th>";
        echo "<th>Email</th>";
        echo "<th>Instrument Name</th>";
        echo "<th>Price</th>";
        echo "<th>Date</th>";
        if($no_of_slots == 1 || $force_one_slot == true)
        {
            echo "<th>Slot</th>";
        }
        else if($no_of_slots == 2 && $force_one_slot == false)
        {
            echo "<th>Slot - 1</th>";
            echo "<th>Slot - 2</th>";
        }
        if($no_of_slots == 1 || $force_one_slot == true)
        {
            echo "<th>Booking Status</th>";
        }
        else if($no_of_slots ==2 && $force_one_slot == false)
        {
            echo "<th>Booking Status - 1</th>";
            echo "<th>Booking Status - 2</th>";
        }
        echo "<tr>";
        echo '<td colspan="1" align="left">';
        echo $first_name.' '.$last_name;
        echo '<td colspan="1" align="left">';
        echo $un;
        echo '<td colspan="1" align="left">';
        echo $product_name;
        echo '<td colspan="1" align="left">';
        echo $price;
        echo '<td colspan="1" align="left">';
        echo $order_date;
        if($no_of_slots == 1 || $force_one_slot == true)
        {
            echo "<td colspan='1' align='left'>";
            echo $slot_time_1;
            echo '<td colspan="1" align="left">';
            if($slot_blocked==1 || $force_one_slot == true)
            {
                echo 'Pending Admin Approval';
            }
            else
            {
                echo 'Denied';
            }
            echo '</tr>';
        }
        else if($no_of_slots == 2 && $force_one_slot == false)
        {
            echo "<td colspan='1' align='left'>";
            echo $slot_time_1;
            echo "<td colspan='1' align='left'>";
            echo $slot_time_2;
            echo '<td colspan="1" align="left">';
            if($slot_blocked==1)
            {
                echo 'Pending Admin Approval';
            }
            else
            {
                echo 'Denied';
            }
            echo '<td colspan="1" align="left">';
            if($slot_blocked==1)
            {
                echo 'Pending Admin Approval';
            }
            else
            {
                echo 'Denied';
            }
            
            echo '</tr>';
        }
        
        echo '</table>';
        echo '<br><br><br><br>';
        // END OF THE TOP TABLE

        //Start of the "Payment Info" (Second) table
        echo '<table>';
        echo '<tr>';
        echo '<th>';
        echo 'Payement Info';
        echo '</th>';
        echo '<td>';
        echo '<form action="payupload.php" method="post" enctype="multipart/form-data">
        Fill the Booking Information:<br>
        Bank Name: <input type="text" name="tbank" required><br>
        Transaction ID: <input type="text" name="tid" required><br>
        Date of Payment: <input type="text" name="tdate" required><br>
        Amount: <input type="text" name="tamm" required><br>
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
        <input type="hidden" name="bookid" value="'.$oi.'"/>
        </form>';
        echo '</td>';
        echo '</table>';


        // START OF THE BOTTOM (Third) TABLE
        if($pid==1)
        {
            if($no_of_slots == 1 || $force_one_slot == true)
            {
                echo '<h3>Details of Order</h3>';
                // QUERIES NEEDED TO CONSTRUCT THE BOTTOM TABLE
                $sq=$mysqli->query("SELECT * FROM autosorbiq_order_details WHERE order_id=$oi");
                $sq_result=$sq->fetch_object();
                // END OF QUERY
                echo '<table>';
                echo '<tr>';
                echo '<th>Instrument Attributes</th>';
                echo '<th>Options Selected</th>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="1" align="left">Nature of Sample:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->nature_of_sample.'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="1" align="left">Porous Nature:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->porous_nature.'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="1" align="left">Analysis Type:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->analysis_type.'</td>';
                echo '</tr>';


                echo '<tr>';
                echo '<td colspan="1" align="left">Degassing Temperature:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->degassing_temp.'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="1" align="left">Degassing Conditions:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->degassing_condition.'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="1" align="left">Additional Details:</td>';
                echo '<td colspan="1" align="left">'.$sq_result->additional_details.'</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        echo"</div>";

    // END OF THE BOTTOM TABLE
    maill('
    <html>
<head>
</head>
<body>
<div style="background-color: #dee1e5;">
<h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br>
<h2>Booking Request Pending</h2>
          <p>    
          Thank you for submitting your slot request. Based on the information given by you, the admin will approve your request and the same will be updated to you by email within few days.<br><br>
If the information entered is inappropriate or if the sample does not meet the conditions or in case of any other known unavoidable reasons related to the instrument/grid availability- the request may be rejected.<br><br>
If not able to use the slot, please cancel it in the portal immediately. Payment if made in case of slot rejection or cancellation, can be used for the upcoming similar service.<br><br>
 
          For more details about payment, please visit service section in the portal</p>
</div>
<footer style="background-color: #545556; color: white;">
<p>SRM Central Instrumentation Facility</p>
</footer>
</body>
</html>
    ');

    echo '</div></div></div><footer><p align="center">This is a machine generated recipt. You will recive a confirmation email from the Admin in 48hrs.</p></footer>';
    
    //displaying the last message
    if($pid==1)
    {
        echo '<p align="center" style="color:red">Thank you for submitting your slot request. Please pay the fee and upload the proof as per the guidelines. Please include the additional charges towards consumables such as grids as required. Regarding the consumable, please check with the facility/lab at least one day in advance. In case of external samples sending through courier/contacts, you MUST discuss the details with TEM lab in advance and pay the additional charges as applicable.</p>';
        echo '<p align="center" style="color:red">Based on the information given by you, the admin will approve your request and the same will be updated to you by email within few days.</p>';
        echo '<p align="center" style="color:red"> If the information entered is inappropriate or if the sample does not meet the conditions or in case of any other known unavoidable reasons related to the instrument/grid availability - the request maybe rejected.</p>';
        echo '<p align="center" style="color:red">If not able to use the slot, please cancel it in the portal immediately. Payment if made in case of slot rejection or cancellation, can be used for the upcoming similar service.</p>';
        echo '<br>';
    }


    }
    else // If the user has booked today then show error and exit
    {
      echo "<br><br>";
      echo "<br>";
      echo '<img src="images/uhno.png" alt="Eror 404!">';
      maill('
    <html>
    <head>
    </head>
    <body>
    <div style="background-color: #dee1e5;">
    <h2>Already Booked</h2>
              <p>    
              You already booked today. Please try again later.</p>
    </div>
    <footer style="background-color: #545556; color: white;">
    <p>SRM Central Instrumentation Facility</p>
    </footer>
    </body>
    </html>    
      ');
    }

    // Custom messges given by the admin for their booking.


}// Security Feature this does not allows users to simply jump to the page, even if they are loged in
else
{
    echo '<p align="center"><img src="images/hack.jpg"></img></p>';
    echo '<footer><p align="center"> /Developed by Department of CSE, SRMIST, KTR/</p> </footer>';
}

// A booking was successful. Delete the hold created for this user.

$d= $mysqli->query("DELETE FROM order_under_process WHERE inst_id = $pidi AND date = '$order_date'");

    ?>
  </body>
  </html>

  <noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>