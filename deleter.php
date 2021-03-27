<?php
include_once('php/config.php');
include('php/session.php');
$deleted = false;

if(!empty($_SESSION['date'] && !empty($_POST['inst'])))
    {
        echo'inside';
        $date = $_SESSION['date'];
        $inst = $_POST['inst'];
        $d= $mysqli->query("DELETE FROM order_under_process WHERE inst_id = $inst AND date = '$date'");
        if($d)
        {
            $deleted = true;
        }   
    }
?>
<script>
var deleted = <?php echo $deleted; ?>;
if(deleted == true)
{
 window.location = 'products.php';
}
else
{
    window.location = 'products.php';
}
</script>