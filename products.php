<?php
include ('php/session.php');
include ('php/config.php');
?>
<noscript>
      <div style="border: 1px solid purple; padding: 10px; text-align: center;">
      <span style="color:red; text-align: center;">Hey! Javascript is disabled. Your Browser is no longer supported! Please enable Javascript.</span>
      <span> To learn how to enable javascript please <a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome">click here</a></span>
      <p style="text-align:center"><img src="images/hack.jpg"></p>
      </div>
</noscript>
<?php
echo '<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Instruments</title>

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
       <li><a href="services.php">Service Charges and Guidelines</a></li>
        <li class="active"><a href="products.php">Book Instruments</a></li>';
        if(isset($_SESSION['username'])){
        echo'<li><a href="orders.php">My Bookings</a></li>';}
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
      <div class="small-12">
          <p><b>Please Note:</b><br><br>Slots will be approved only after uploading the proof of payment details.
Proof of payment details must be uploaded within 48 hrs of booking. Otherwise the slots will be cancelled.
<br><br></p>
    
        <?php
          $i=1;
          $product_id = array();
          $product_quantity = array();
          $result = $mysqli->query('SELECT * FROM products');
          if($result === FALSE){
            die(mysqli_error());      //changed mysql_error to mysqli_error, since mysql isn't supported in php 7+
          }
          if($result){
             echo '<form action="cart.php" method="POST">';
            while($obj = $result->fetch_object()) {
              echo '<div class="large-4 columns" style="float: left" >';
              echo '<div class="card">';
              echo '<p><h3><center>'.$obj->product_name.'</center></h3></p>';//product name ye wala he, upar print hoga
              echo '<center><img src="images/'.$obj->product_img_name.'"/></center>';
              echo '<p><strong>Description</strong>: '.$obj->product_desc.'</p>';
              echo '<p><strong>Booking Price</strong>:<a href="./services.php">Check Service Section</a></p>';
              echo '<p><center><button type="submit" value="'.$obj->id.'" name="inst_id" class="radius button">Book</button></center></p>';
              echo '</div>';
              echo '</div>';
              $i++;
              if($i==3)
              {
                //echo'<br>';
              }
            }
            echo '</form>';
          }
          $_SESSION['product_id'] = $product_id;
   
          echo '</div>';
          echo '</div>';
          ?>
           <style>
      .card {
    /* Add shadows to create the "card" effect */
    background-color: rgb(188, 196, 209);
    box-shadow: 0 4px 8px 0 rgba(55,105,105,1.7);
    transition: 0.3s;
    border-radius: 10px;
}
      .reveal-modal{background-color: rgb(188, 196, 209);}

/* On mouse-over, add a deeper shadow */
.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

/* Add some padding inside the card container */
.container {
    padding: 2px 16px;
}
img {
    border-radius: 5px 5px 0 0;
}
    </style>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>


