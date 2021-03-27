<?php
// External PHP File Linking 
include 'php/config.php';
include 'mailer.php';
//function domain_exists($email){ // DNS Check for non valid 
  //$record = 'MX';
	//list($user, $domain) = split('@', $email);
	//return checkdnsrr($domain, $record);
//}

$jump_prevent_send_back="login.html";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){ // Make sure that user comes only through a valid POST request.
  if($connected_successfully == true && isset($_POST['submit'])) // If we have connected to the database and the varaiable submit is set in the SESSIONS Array
  {
    //  Get the POST variables
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $institute = $_POST['institute'];
    $inst_id = $_POST['inst_id'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    //Error Flags
    $Empty_First_Name_error = false;
    $Empty_Last_Name_error = false;
    $Empty_Institute_error = false;
    $Empty_inst_id = false;
    $Empty_Address_error = false;
    $Empty_Email_error = false;
    $Empty_Phone_Number_error = false;
    $Empty_Pass_error = false;
    $success = false;
    $mail_success = false;

    if(empty($email) || empty($pass) || empty($first_name) || empty($last_name) || empty($phone_number) || empty($institute) || empty($inst_id) || empty($address))
    {
      echo '<span class="form-error">Please Fill in all Fields</span>';
      if(empty($first_name))
      {
          $Empty_First_Name_error = true;
      }
      if(empty($last_name))
      {
          $Empty_Last_Name_error = true;
      }
      if(empty($phone_number))
      {
          $Empty_Phone_Number_error = true;
      }
      if(empty($institute))
      {
        $Empty_Institute_error = true;
      }
      if(empty($inst_id))
      {
        $Empty_inst_id = true;
      }
      if(empty($address))
      {
        $Empty_Address_error = true;
      }
      if(empty($email))
      {
          $Empty_Email_error = true;
      }
      if(empty($pass))
      {
          $Empty_Pass_error = true;
      }
    }
    else if(!preg_replace('/[^0-9]/', '', $phone_number))
    {
      echo '<span class="form-error" >Please Enter a valid Phone Number</span>';
    }
    else if(!preg_match("/^[a-zA-Z ]*$/",$first_name))
    {
      echo '<span class="form-error" >Please Enter a valid First Name</span>';
    }
    else if(!preg_match("/^[a-zA-Z ]*$/",$last_name))
    {
      echo '<span class="form-error" >Please Enter a valid Last Name</span>';
    }
    else if(!filter_var($email, $filter = FILTER_VALIDATE_EMAIL))// If the email is not a valid email
    {
      echo '<span class="form-error" > Please Enter a valid Email Address</span>';
    }
    //else if(!domain_exists($email))
    //{
      //echo '<span class="form-error" > Please Enter a valid Email Address//</span>';
    //}
    else if(strlen($pass) <7)// If the password length is less than 7.
    {
      echo '<span class="form-error" > Password length must be greater than 6</span>';
    }
    else if(strcmp($pass,$cpass)!=0)// If the two passwords missmatch
    {
      echo '<span class="form-error" > Password Missmatch</span>';
    }
    else if(strlen($phone_number)!=10)// Phone number must be 10 digits long
    {
      echo '<span class="form-error" >Enter a Valid 10 digit Phone Number</span>';
    }
    else // All preliminary checks complete go to filter phase
    {
      // Filter the user inputs
      $hpwd = password_hash($pass,PASSWORD_BCRYPT);
      $hash = md5(rand(0,1000));
      // Registration DateTime 
      $reg_date = date('Y/m/d', time());
      // Last active timestamp
      $timestamp = date("Y-m-d H:i:s");
      // Getting IP-Address of the client
      $ip = $_SERVER['REMOTE_ADDR'];
      $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      $ip = (string)$ip;
      $proxy_ip = (string)$proxy_ip;
      $type="user";
      

      //Check the user is already registered.
      $check=$mysqli->query("SELECT id FROM users WHERE email = '".$email."'"); // SQL Query.

      if($check->num_rows>0)// If a row is found the user is already registered.
      {
        echo '<span class="form-error">This Email Address is Already Registered  </span>';
        echo $email;
      }
      else
      {
        // Insert the new user details into the database.
        $q1=$mysqli->prepare("INSERT INTO users (fname,lname,institute,iid,address,email,pwd,phno,reg_date,last_active,last_ip,last_ip_proxy,type,hash) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $q1->bind_param('ssssssssssssss',$first_name, $last_name, $institute,$inst_id,$address,$email,$hpwd,$phone_number,$reg_date,$timestamp,$ip,$proxy_ip,$type,$hash);
        if($q1->execute())
        {
          $success = true; // SQL Entry Successfull
          $body = '
          <h4><font color="red">DO NOT REPLY! This is a system generated mail, kindly do not reply. In case of any queries please refer the Contact Us section for the instrument admin.</font></h4><br> 
          Thanks for signing up!<br>Your Dept. of Chem. account has been created successfully and is pending Admin approval.<br><br> Please  
<a href="http://scif.srmonline.net/verify-email.php?email='.$email.'&hash='.$hash.'">Click Here</a> to verify your email address.<br>
We will notify you once the admin approves the registration request. This may take upto 48hrs.<br><br><br>
Regards<br>Dept. of Chem. Team';
$subject = 'Registration Verification Link';
          $mm=maill($email, $first_name, $subject, $body); // Mail the user.
          if($mm == true)
            {
              $mail_success = true;
            }

          echo '<span class="form-success">We have sent a verification link to your email. Please Verify</span>';
        }
        else
        {
          echo '<span class="form-error">Oh no! We encountered a fatal error. Please try again later</span>';
        }
      }
    }
  }

  else // Database connection Error or Submit button press wasn't registered properly.
  {
    echo '<span class="form-error">The Server Failed to Respond</span>';
  }
}
// End of valid POST request processing by server.

else // Prevent a user to simply jump to this page.
{
  header('location: '.$jump_prevent_send_back);
}
?>

<!-- Response Ajax Script -->
<script>
  function custom_wait()
  {
    setTimeout(function(){window.location = 'login.html';}, 3000);
  }
  document.getElementById("form-submit").disabled = false;
  $("#from-email, #form-password, #form-first-name, #form-last-name, #form-phone-number, #form-gender, #form-dob").removeClass("input-error");

  var errorfirstname="<?php echo $Empty_First_Name_error; ?>";
  var errorlastname="<?php echo $Empty_Last_Name_error; ?>";
  var errorphonenumber="<?php echo $Empty_Phone_Number_error; ?>";
  var errorgender="<?php echo $Empty_Gender_error;?>";
  var errordob="<?php echo $Empty_DOB;?>";
  var errorEmail="<?php echo $Empty_Email_error; ?>";
  var errorPass="<?php echo $Empty_Pass_error; ?>";
  var success ="<?php echo $success ?>";
  var mail_success ="<?php echo $mail_success ?>";

  if(errorEmail == true)
  {
    $("#form-email").addClass("input-error");
  }
  if(errorPass == true)
  {
    $("#form-password").addClass("input-error");
    $("#form-password-confirm").addClass("input-error");
  }
  if(errorfirstname == true)
  {
    $("#form-first-name").addClass("input-error");
  }
  if(errorlastname == true)
  {
    $("#form-last-name").addClass("input-error");
  }
  if(errorphonenumber == true)
  {
    $("#form-phone-number").addClass("input-error");
  }
  if(errorEmail == false  && errorPass == false )
  {
    $("#from-email, #form-password").val("");
    $("#from-email, #form-password-confirm").val("");
  }
  if(errorgender == true)
  {
    $("#form-gender").addClass("input-error");
  }
  if(errordob == true)
  {
    $("#form-dob").addClass("input-error");
  }
  if(success == true && mail_success == true)
  {
    custom_wait();
  }
</script>
<noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>