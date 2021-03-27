<?php
include ('php/session.php');
include ('php/config.php');
?>
<?php
if(isset($_POST['submit']))
 {
   $secretkey="6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
   $responsekey=$_POST['g-recaptcha-response'];
   $user_ip=$_SERVER['REMOTE_ADDR'];
   $url="https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$responsekey&remoteip=$user_ip";
   $response=file_get_contents($url);
   $response=json_decode($response);
}
?>
<html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <!-- Bootstrap CSS Style Sheet-->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <!-- Custom Font -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      
      <!-- Latest compiled JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

      <!-- Custom CSS Style Sheet-->
      <link rel="stylesheet" type="text/css" href="./css/signup.css">

      <!-- Custom Scripts -->
      <script type="text/JavaScript">
      $(document).ready(function(){
      $("form").submit(function(event){
        event.preventDefault();
        document.getElementById("form-submit").disabled = true;
        var first_name = $("#form-first-name").val();
        var last_name = $("#form-last-name").val();
        var institute = $("#form-institute").val();
        var inst_id = $("#form-inst-id").val();
        var address = $("#form-address").val();
        var email = $("#form-email").val();
        var phone = $("#form-phone").val();
        var pass = $("#form-password").val();
        var cpass = $("#form-password-confirm").val();
        var submit = $("#form-submit").val();

        $(".form-message").load("signup_insert.php", {
          first_name: first_name,
          last_name: last_name,
          institute: institute,
          inst_id: inst_id,
          address: address,
          email: email,
          phone: phone,
          pass: pass,
          cpass: cpass,
          submit: submit
        });
      });
    });
    </script>
    <script type="text/javascript">
        function enableBtn(){
        document.getElementById("form-submit").disabled = false;    }
    </script>

    <script src="https://www.google.com/recaptcha/api.js"></script>
    
    <style>
        #recaptcha
        {
            width: 304px;
            margin: 0 auto;
        }
    </style>
 </head>


<body class="body-css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark nav-pills" style="font-size: 12px;">  
  <a  href="index.php"><img src="images/s.png" alt="SRM" height="35" width="60"></a>
  <a class="navbar-brand" href="#" style="font-size: 12px;"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
   <div class="navbar-collapse collapse w-100 order-3 dual-collapse2" id="navbarNav">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
                  <a class="nav-link" style="color: white" href="facilities.php">Facilities</a>
          </li>
          <li class="nav-item">
                    <a class="nav-link" style="color: white" href="services.php">Service Charges and Guidelines</a>
          </li>
          <li class="nav-item">
                  <a class="nav-link" style="color: white" href="products.php">Book Instruments</a>
          </li>
        <?php
        if(isset($_SESSION['username'])){
         echo' <li class="nav-item">
                  <a class="nav-link" style="color: white" href="orders.php">My Bookings</a>
              </li>';}
        ?>
          <li class="nav-item">
                  <a class="nav-link" style="color: white" href="contact.php">Contact</a>
          </li>
        <?php
              if(isset($_SESSION['username']))
              {
          echo '<li class="nav-item">
                  <a class="nav-link" style="color: white" href="account.php">My Account</a>
              </li>
          <li class="nav-item">
                  <a class="nav-link" style="color: white" href="logout.php">Log Out</a>
              </li>';
              }
              else
              {
          echo '<li class="nav-item">
                  <a class="nav-link" style="color: white" href="login.php">Login</a>
          </li>
          <li class="nav-item">
                  <a class="nav-link" style="color: white" href="register.php">Register</a>
          </li>';
              }
         ?>
          <li class="nav-item">
                  <a class="nav-link" style="color: white" href="images/User Manual.pdf" download>User Manual</a>
          </li>
        </ul>
    </div>
</nav>
<div class="container">
    <P><B>Please Note:</B> <br>All the users are requested to use their official/institute/company email id for registration.<br>
All internal users (Only Faculty/Research supervisors in SRM group of Institutions) must book through SRM EMAIL ID ONLY. Otherwise external charges will be applied. Please re-register immediately with your srmist email id if not done. </P>
  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <p style="text-align: center;" class="form-message" id="form-message"> </p>
      <form class="form-container"action="signup_insert.php" method="POST">
          <h1 style="text-align: center">Register</h1>
          <div class="form-group">
              <input type="text" class="form-control" name="first-name" id="form-first-name" placeholder="First Name(Faculty/Research Supervisor)">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" name="last-name" id="form-last-name" placeholder="Last Name(Faculty/Research Supervisor)">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" name="institute" id="form-institute" placeholder="Institute">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" name="inst_id" id="form-inst-id"  aria-describedby="emailHelp" placeholder="Enter Your Employee ID Number">
          </div>
          <div class="form-group">
              <input type="text" class="form-control" name="address" id="form-address"  aria-describedby="emailHelp" placeholder="Give Your Full Address">
          </div>
          <div class="form-group">
              <input type="email" class="form-control" name="email" id="form-email" placeholder="Email  Address (Official/Institute)">
          </div>
          <div class="form-group">
              <input type="tel" class="form-control" name="phone" id="form-phone" placeholder="Phone Number">
          </div>
          <div class="form-group">
              <input type="password" class="form-control" name="password" id="form-password" placeholder="Password">
          </div>
          <div class="form-group">
              <input type="password" class="form-control" name="password_confirm" id="form-password-confirm" placeholder="Confirm Password">
          </div>
          <div class="form-group">
              <div id="recaptcha">
               <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-callback="enableBtn" style="text-align:center;"></div>
               </div>
            </div>
              <button id="form-submit" type="submit" name="submit" disabled="true" class="btn btn-success btn-block">Register</button>
              
      </form>
    </div>
    <div class="col-sm-3"></div>
  </div>
</div>


</body>
</html>