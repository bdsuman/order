<?php
require_once "home/config.php";

$q = strtolower($_GET["q"]);
if (!$q) return;
 
$sql = "SELECT DISTINCT(sale_no) FROM `sale_details` WHERE m_name LIKE '%$q%'";
$rsd = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($rsd)) {
    $cname = $rs['sale_no'];
    echo "$cname\n";
}

?>