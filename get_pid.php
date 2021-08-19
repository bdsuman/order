<?php
require_once "home/config.php";

$q = strtolower($_GET["q"]);
if (!$q) return;
 
$sql = "SELECT * FROM `patient_info` WHERE `p_id` LIKE '%$q%'";
$rsd = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($rsd)) {
    $cname = $rs['p_id'];
    echo "$cname\n";
}

?>