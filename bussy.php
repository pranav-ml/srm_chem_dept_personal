<?php
include_once('php/config.php');

$tq = $mysqli->query("SET SESSION time_zone = '+5:30'");


$inst = $_POST['inst'];
$wdate = $_POST['date'];
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d', strtotime($wdate));
$cur_time = date("Y-m-d H:i:s",time());
$kill_button = false;

if(!empty($date) && !empty($inst))
{
    $q = $mysqli->query("SELECT timestamp from order_under_process WHERE inst_id = $inst AND date = '$date'");
    if(!$q)
    {
        echo 'SQL Fail';
    }
    if($q->num_rows == 0)
    {
        echo 'Free to Book';
    }
    else
    {
        $qr = $q->fetch_object();
        $fetched_date = $qr->timestamp;
        $fetched_timestamp = strtotime($fetched_date);
        $fuck = date("Y-m-d H:i:s", $fetched_timestamp);
        
        $start_date = new DateTime($fuck);
        $since_start = $start_date->diff(new DateTime($cur_time));
        if($since_start->i < 5)
        {
            echo '<span style="color:red">Sorry a booking is in progress</span>';
            echo '<br>';
            $x = (int)$since_start->i;
            echo '<span style="color:red">Expected Wait Time: </span>';
            echo 5-$x;
            echo ' min';
            $kill_button = true;
        }
        else
        {
            // The user failed to book in time and the book_2.php delete funcion is not triggered. So we trigger it now and delete the entry.
            $d= $mysqli->query("DELETE FROM order_under_process WHERE inst_id = $inst AND date = '$date'");
            if($d)
            {
                echo 'Free to Book';
            }
        }
        
    }

}
else{
    echo 'fail';
}
?>

<script>
  document.getElementById("sb").disabled = false;
  var kill_button_flag="<?php echo $kill_button; ?>";
  if(kill_button_flag == true)
  {
    document.getElementById("sb").disabled  = true;
  }
</script>