<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>.::AnandasoftBD::.</title> 
<meta charset="UTF-8">
<style type="text/css">
body {font-size:14px;font-family:Arial;}
table{border-collapse:collapse;}
td {padding: 2px;}
.header {
	width:100%;
}
.logo {
	float:left;overflow:auto;padding:10px;
}
.description {
	padding:10px;
}
@page { size: auto;
		margin: 6mm 6mm 6mm 6mm;  
}
@media print {
		body,table {
			font-size:80%;
		}
		.devtag {
			font-size:7pt;
		}
	}
</style>
</head>
<body>
<?php
error_reporting(0);
include "home/config.php";
include "home/function_cur.php";

?>

<table align="center" border="0" style="border-collapse:collapse;" width="100%"  >
	<tr>
		<td align="left" > 
			<span style=" font:18px sans-serif;"><strong>বড় ডাক্তার</strong></span><br>
			জিলা স্কুলের সামনে, ময়মনসিংহ।<br>
			মোবাইল-  ০১৭১৫১০৪৫২৮      
		</td>
		<td align="left" width="15%"><img src="home/img/logo.jpg" height="65px"></td>
		<td align="Right">
			<span style=" font:20px sans-serif;"><strong>Boro Dakter</Strong></span><br>
			In front of Zilla School, Mymensingh<br>
			Mobile: 01715104528
		</td>
	</tr>
</table>
<hr>
<br>

<div align="center"><span style="padding:5px;border:1px solid gray;border-radius:6px;"><strong>Money Receipt</strong></span></div>
<br>
<table align="center" border="0" style="border-collapse:collapse; padding:1px;" width="100%"  >
	<?php 
	$sale_no=$_GET['sale_no'];
	$result1 = mysqli_query($con,"SELECT * FROM sale_details WHERE sale_no='$sale_no' group by sale_no");
			while($row1 = mysqli_fetch_array($result1)){
				$p_id=$row1['p_id'];
	$re = mysqli_query($con,"SELECT * FROM `patient_info` WHERE `p_id`='$p_id'");
			while($row2 = mysqli_fetch_array($re)){
				
			
		?>
		<tr>
			<td align="left"  >Order No.</td>
			<td >:</td>
			<td  align="left"><?php echo $row1['sale_no']; ?></td>
			<td align="right" >Client ID</td>
			<td >:</td>
			<td ><?php echo $row1['p_id'];?></td>
		</tr>
		<tr>
			<td align="left">Order Date &amp; Time</td>
			<td >:</td>
			<td  align="left"><?php echo $row1['order_date']; ?> &nbsp;&nbsp;<?php echo $row1['order_time']; ?> </td>
			<td align="right" >Date</td> 
			<td >:</td>
			<td  align="left"><?php date_default_timezone_set('Asia/Dacca'); 
					$date = date('d F Y', time()); 
					echo $date;
					?>
			</td>
		</tr>
		<tr>
			<td >Name</td>
			<td >:</td>
			<td  align="left"><strong><?php echo strtoupper($row2['p_name']);?></strong></td>
			<td align="right">Phone</td>
			<td >:</td>
			<td  align="left"><?php echo $row2['phone'];?></td>
		</tr>
		<tr>
			<td >Address</td>
			<td >:</td>
			<td  align="left"><?php echo strtoupper($row2['address']);?></td>
			<td align="right" >Sex</td>
			<td >:</td>
			<td align="left" ><?php echo strtoupper($row2['sex']);?></td>
		</tr>
		<tr>
			
			<td align="right" ></td>
			<td ></td>
			<td ></td>
		</tr>
		<tr>
			<td align="left">Pharmacy</td>
			<td >:</td>
			<td  align="left" colspan="4"><?php 
						
							echo strtoupper($row1['pharmacy']);
					?></td>
			
		</tr>



</table>
<?php
			}
			}
?>
<br>
<table align="center"  width="100%" border="1" >
			
	<tr align="center">
		<td><strong>Sn</strong></td>
		<td align="center"><strong>Medicine</strong></td>
		<td><strong>Qauntity</strong></td>
	</tr>
	<?php
	$i=1;
		$result3 = mysqli_query($con,"SELECT * FROM sale_details 
								WHERE sale_no='$sale_no' 
								ORDER BY medicine_name;");
			while($row = mysqli_fetch_array($result3))
					{
			?>
	<tr>
		<td align="center"><?php  echo $i++;?></td>
		<td ><?php echo $row['medicine_name'];?></td>
		<td align="center"><?php echo $row['quantity']; ?></td> 
	</tr>  
	<?php
	$g_total=$row['grand_total_quantity'];
	$check=$row['confirm'];
	$service_charge=$row['service_charge'];
	
			}
?>
		
	<tr align="right" >
		<td rowspan="4" align="center" valign="middle">
			<strong>
					
				  
					<?php 
					
						if('Yes'==$check) {
							?><span style="font-size:12pt;">Order Confrimed.<br />Helpline: 01700000000.<?php
							
							} 
							else {
								?><span style="font-size:12pt;">Order Not Confrimed. <br /> Please wait for verify your order. <br />Helpline: 01700000000.<?php 
							
							} 
					?> 
				</span>
			</strong>
		</td>
		
		</tr><tr>
		<td align="right"><strong>Grand Total Quantity:</strong></td>
		<td align="center">
			<strong><?php 
				echo $g_total;
			?></strong>
		</td>
	</tr><tr>
		<td align="right" ><strong>Service Charge:</strong></td>
		<td align="center"><strong><?php echo number_format($service_charge,2); ?></strong></td>
	</tr><tr>
	<td  align="right"><strong>Delivery Method:</strong></td>
	<td align="center"><span style="font-size:12pt;"><strong>Cash On Delivery Only.</strong> </span></td>
		
	</tr>
	
</table>
<br>
<table align="center" border="0" style="border-collapse:collapse;" width="100%"  >
	  <tr>  
		<td >Taka in word: <strong><?php echo convert_number_to_words($service_charge); ?> BDT Only ( Without Medicine Price).</strong></td>
		
	  </tr>
	 <tr>
		<td ><strong>N.B: Minimum 300/- or up above medicine price were allowed for confirm order. Otherwise cancel this order.</strong></td>
	 </tr>
</table>
  	<div class="devtag" align="right"><strong>AnandasoftBD</strong><br>Authorized Signature</div>
	<div style="clear:both;"></div>
	<div align="center"><span style="font-size:8pt;">Printing Time: <?php echo date('d-m-Y h:i A', time()); ?><br>Working Hour: From 8.00 AM to 11.30 PM <br> কার্যসময়ঃ সকাল ৮.০০  টা থেকে রাত ১১.৩০ টা পর্যন্ত</span></div>
  
  <?php
	mysqli_close($con);		 
	?>

</body>
</html>