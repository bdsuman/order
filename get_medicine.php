<?php
require_once "home/config.php";

$q = strtolower($_GET["q"]);
if (!$q) return;
 
$sql = "SELECT * FROM `medicine` WHERE m_name LIKE '%$q%'";
$rsd = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($rsd)) {
    $cname = strtoupper($rs['m_name']);
    echo "$cname\n";
}

?>