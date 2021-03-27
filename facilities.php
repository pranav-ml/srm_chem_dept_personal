<?php
include ('php/session.php');
include ('php/config.php');
?>

<?php
echo '<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Facilities</title>

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
        <li class="active"><a href="facilities.php">Facilities</a></li>
        <li><a href="services.php">Service Charges and Guidelines</a></li>
        <li><a href="products.php">Book Instruments</a></li>';
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

<style type="text/css">

  .boxed {
  border: 1px solid green ;
}
</style>
  <style type="text/css">

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
    <div class="row" style="margin-top:30px;">
      <div class="small-12">
        <div class="scrollit">
           <p>State-of-the-art facilities available for users.<br>
        <table>
  <tr>
    <th>#</th>
    <th>Name of the Instrument</th>
    <th>Make/Model</th>
    <th>Other Details</th>
  </tr>  
  <tr>
      <td>1</td>
      <td>Autosorb iQ- Chemisorption (&Physisorption) Gas sorption Analyzer</td>
      <td></td>
      <td></td>
  </tr>
</table>
<br>
<b>Location -</b>
<br>
Sir C V Raman Research Park (Room No. 003)<br>
SRM institute of Science & Technology, Kattankulathur,<br>
Chennai-603203, Tamil Nadu <br>
(For location details of individual instruments, please check <a href="contact.php">contact</a>)
        </p>
      </div>
      </div>
    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
  <footer> /Developed by Department of CSE, SRMIST, KTR/ </footer>
</html>