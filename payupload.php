<?php
include ('php/session.php');
include ('php/login_check.php');
include ('php/config.php');
$target_dir = "uploads/";
$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$oi=$_POST['bookid'];
$tbank=$_POST['tbank'];
$tid=$_POST['tid'];
$tdate=$_POST['tdate'];
$tamm=$_POST['tamm'];
$target_file = $target_dir . basename($newfilename);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
$uploadOk = 1;
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $push=$mysqli->query("UPDATE orders SET payinfo='$newfilename', tbank='$tbank', tid='$tid', tdate='$tdate', tamm='$tamm' WHERE order_id='$oi'");
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        header("Location: orders.php");
        die();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
