<?php
require_once "home/config.php";

$q = strtolower($_GET["q"]);
if (!$q) return;
 
$sql = "SELECT * FROM pharmacy WHERE name LIKE '%$q%'";
$rsd = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($rsd)) {
    $cname= strtoupper($rs['name'])."-".$rs['pharmacy_id'].", ".strtoupper($rs['proprietor']).", ".strtoupper($rs['address']).", ".$rs['mobile'].",".$rs['email'];
    echo "$cname\n";
}

?>