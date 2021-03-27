<?php
include ('php/session.php');
include ('php/config.php');
//include ('php/head.php');
?>
<?php
if(isset($_POST['submit']))
{
  // $secretkey="6LcQwEIUAAAAAABDGoeFpbjSoXGFWW8zXQLchZaW";
  // $responsekey=$_POST['g-recaptcha-response'];
  // $user_ip=$_SERVER['REMOTE_ADDR'];
  // $url="https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$responsekey&remoteip=$user_ip";
  // $response=file_get_contents($url);
  // $response=json_decode($response);
}
?>
<?php
// echo '<script type="text/javascript">
//   function enableBtn(){
//     document.getElementById("button1").disabled = false;
//    }
// </script>
// <script src="https://www.google.com/recaptcha/api.js"></script>';
?>

<html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS Style Sheet-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- Custom Font -->
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- Custom CSS Style Sheet-->
  <link rel="stylesheet" type="text/css" href="./css/login.css">


  <!-- Ajax Script-->
  <script type="text/JavaScript">
        $(document).ready(function(){
        $("form").submit(function(event){
          event.preventDefault();
          var email = $("#form-email").val();
          var pass = $("#form-password").val();
          var submit = $("#form-submit").val();

          $(".form-message").load("verify.php", {
            email: email,
            pass: pass,
            submit: submit
          });
        });
      });
  </script>
  <!-- Ajax Script Ends -->

  <!-- Caps-Lock Script-->
  <script type="text/JavaScript">
      function capLock(e){
        kc = e.keyCode?e.keyCode:e.which;
        sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
        if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
        {
          document.getElementById("form-message").innerHTML="Warning CapsLock is on";
        }
        else
        {
          document.getElementById("form-message").innerHTML="";
        }
      };
  </script>
</head>

<!--Body Begins-->
<body class="body-css">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: 12px;">
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
                  <a class="nav-link" style="color: white" href="services.php">Service charges and guidelines</a>
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
  <!-- Login Form Begins-->
  <div class="container">
      <br>
      <B>Please Note:</B> <br>All the users are requested to use their official/institute/company email id for registration.<br>
All internal users (SRM group of Institutions) must book through SRM EMAIL ID ONLY. Otherwise external charges will be applied. Please re-register immediately with your srmist email id if not done. </P>
    <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
          <form class="form-container" action="verify.php" method="POST">
          <div class="form-group">
            <h1 style="text-align: center">Login</h1>
          </div>
          <div class="form-group">
            <input type="email" class="form-control" id="form-email" name="email" aria-describedby="emailHelp"
              placeholder="Enter Email" style="border-radius: 10px;">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" onkeypress="capLock(event)" id="form-password"
              placeholder="Password" style="border-radius: 10px;">
          </div>
          <br>
          <button id="form-submit" type="submit" name="submit" class="btn btn-success btn-block">Login</button>
          <br>
          <a href = "register.php"style="font-size: 10px; text-align:left;">Sign Up </a><a style="font-size: 12px; text-align:left;">|</a>
          <a href = "forgot.php" style="font-size: 10px; text-align:right;">Forgot Password</a>

        </form>
        <p style="text-align: center;" class="form-message" id="form-message"></p>
      </div>
      <div class="col-sm-3"></div>
    </div>
</div>
  <!-- Login Form Ends-->
</body>
<!--Body Ends-->
</html>
