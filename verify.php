<?php
include_once('php/config.php');
include_once('php/session.php');


if($connected_successfully == true && isset($_POST['submit']))
{
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $Empty_Email_error = false;
  $Empty_Pass_error = false;
  $user="";
  $success = false;

  if(empty($email) || empty($pass))
  {
    echo '<span class="form-error">Please Fill in all Fields</span>';
    $Empty_Email_error = true;
    $Empty_Pass_error = true;
  }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    echo '<span class="form-error" > Please Enter a valid Email Address</span>';
  }
  else
  {
    // Now its time to connect query the database and find the user and check his/her password.
    $q1=$mysqli->query("SELECT id,fname,lname,email,pwd,type FROM users WHERE email='$email'");
    if($q1)
    {
      $q1_res=$q1->fetch_object();
      if(password_verify($pass,$q1_res->pwd) && $q1_res->email == $email)
      {
              $email_verified_flag = false;
              $admin_approved_flag = false;

              // SQL Query to check if the user has verified its email address.
              $email_verified = $mysqli->query("SELECT active FROM users WHERE email='$email'");
              if($email_verified)
              {
                $email_verified_result = $email_verified->fetch_object();
                if($email_verified_result->active == 0)
                {
                  echo '<span class="form-error">Please Verify your email address</span>';
                }
                else
                {
                  $email_verified_flag = true;
                }
              }

              //SQL Query to check if admin has approved the user provied email has been verified.
              if($email_verified_flag == true)
              {
                $admin_approved = $mysqli->query("SELECT ustatus FROM users WHERE email='$email'");
              }
              if($admin_approved)
              {
                $admin_approved_result = $admin_approved->fetch_object();
                if($admin_approved_result->ustatus == 0)
                {
                  echo '<span class="form-error">Please wait the Admin has not approved your registration yet.</span>';
                }
                else
                {
                  $admin_approved_flag = true;
                }
              }
              if($email_verified == true && $admin_approved_flag == true)
              {
                $_SESSION['user_id']=$q1_res->id;
                $_SESSION['first_name']=$q1_res->first_name;
                $_SESSION['last_name']=$q1_res->last_name;
                $_SESSION['username']=$q1_res->email;
                $_SESSION['type']=$q1_res->type;
                $_SESSION['id']=$q1_res->id;
                $timestamp = date("Y-m-d H:i:s");
                $ip = $_SERVER['REMOTE_ADDR'];
                $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $ip = (string)$ip;
                $proxy_ip = (string)$proxy_ip;
                $update_user_info = $mysqli->query("UPDATE users SET last_active='$timestamp', last_ip='$ip', last_ip_proxy='$proxy_ip' WHERE email='$email'");    
                if($_q1_res->type == "user")
                {
                  $user = true;
                }
                else
                {
                  $user = false;
                }          
                $success = true;
              }
      } 
      else
      {
        echo '<span class="form-error">Wrong Password or Email</span>';
      }
    }
    else
    {
      echo '<span class="form-error">Oh no! We encountered a fatal error. Please try again later</span>';
    }
}
}
else
{
  echo '<span class="form-error">The Server Failed to Respond</span>';
}
 ?>
<script>

  $("#from-email, #form-password").removeClass("input-error");

  var errorEmail="<?php echo $Empty_Email_error; ?>";
  var errorPass="<?php echo $Empty_Pass_error; ?>";
  var user ="<?php echo $user ?>";
  var success ="<?php echo $success ?>";

  if(errorEmail == true)
  {
    $("#form-email").addClass("input-error");
  }
  if(errorPass == true)
  {
    $("#form-password").addClass("input-error");
  }
  if(errorEmail == false  && errorPass == false)
  {
    $("#from-email, #form-password").val("");
  }
  if(user == true)
  {
    if(success == true)
    {
      window.location = 'index.php';
    }
  }
  if(user == false)
  {
    if(success == true)
    {
      window.location = 'bookingdata.php';
    } 
  }
  else
  {
    window.location = 'contact.php';
  }
</script>

<noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>