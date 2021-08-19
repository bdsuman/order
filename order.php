<?php
include "home/config.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<script type="text/javascript" src="home/jquery.js"></script>

	
<script type="text/javascript">
				
		function count(){ 
			tTable="PTest";
			  
			
			var table = document.getElementById(tTable);
			var tbody = table.tBodies[0];
			row_count = tbody.rows.length - 1;
			row_count=(row_count==-1)? 0: row_count;
			var j=1,col,subToal=0;
			for (var i = 0, row; row = tbody.rows[i]; i++) {
				col = row.cells[0];
				col.innerHTML = j++;
				subToal=subToal + Number(row.cells[2].innerText);
			}
			document.getElementById("sub_total").value = subToal;
			var charge=50;
			document.getElementById("service_charge").value = charge;
			document.getElementById("medicine_name").value='';
			document.getElementById("quantity").value='';
		}
		
	function AddExtra(){ 
	
        
        var x = document.getElementById("medicine_name").value;
        var y = document.getElementById("quantity").value;
        
        if(x==''){
            alert("Wrong Medicine Name Entered. Please enter Medicine Name.");
			document.getElementById("medicine_name").value='';
			document.getElementById("medicine_name").focus();
			return;
        }
		
        if(y=='' || isNaN(y)){
            alert("Wrong Quantity Value Entered. Please enter number.");
			document.getElementById("quantity").value='';
			document.getElementById("quantity").focus();
			return;
        }
		
        
        
		var NumOfRow = $('#PTest tr').length-1;
		
		NumOfRow=(NumOfRow)? NumOfRow+1:1;
		
		markup = "<tr><td>" + NumOfRow + "</td><td ><input type='hidden' name='product_no[]' value='9999'><input type='hidden' name='medicine_name[]' value='"+x+"'>" + x + "</td><td ><input type='hidden' name='quantity[]' value='"+y+"'>" + y + "<input type='hidden' name='cost[]' value='"+ y +"'></td><td style=' padding: 2px;'><img src='home/img/delete-img.png' width='20px' hspace='20' onclick='removeRow(this)'> </td></tr>";
				
		$("#PTest").append(markup);
         count(); 
    }
	
	function removeRow(src) {
		var row = src.parentNode.parentNode;
		row.parentNode.removeChild(row);
		count(); //make table serial number ascending
	}
</script>
		

</head>

<body>


    <div id="wrapper">
       <?php  
			include "head.php";	
			include "home/config.php";
	if(!class_exists('PHPMailer')) {
    require('home/phpmailer/class.phpmailer.php');
    require('home/phpmailer/class.smtp.php');
}
	error_reporting(E_STRICT | E_ALL);
			
			date_default_timezone_set('Asia/Dacca');
			$date = date('Y-m-d', time());
			$time=date("Y-m-d H:i:s",time());
			$year=date('Y',time());
			$order_time=date('h:i A', time());
			
			
			
			
			
			
if(isset($_POST['save'])){

		mysqli_query($con,"LOCK TABLES sale_details WRITE,patient_info WRITE,pharmacy WRITE");
	
		if(!empty($_POST['ReceiptNo'])){ //If Re admit the receipt no
			
			$sn=$_POST['ReceiptNo'];
			$p_sn=$_POST['p_id'];
			
			//check if make more than one trans for this sales id
			$re = mysqli_query($con,"SELECT * FROM sale_details where confirm='No' and sale_no='$_POST[ReceiptNo]'");
			$TransTag=0;
			if(mysqli_num_rows($re)>0){
				
				mysqli_query($con,"DELETE FROM sale_details where sale_no='$_POST[ReceiptNo]'");			
				$TransTag=0;
			}else{
				$TransTag=1;
				unset($_POST);
				echo '<script type="text/javascript"> alert("Warning!!! You can not change data after confrimed order.");</script>';
				
			}
			
		}
		else{
			
			$result2 = mysqli_query($con,"SELECT max(sale_no) as inv FROM sale_details where order_date='$date'");
			if($rows = mysqli_fetch_array($result2)){ 
				if($rows['inv']>0){
					$sn=$rows['inv']+1;
				} else {
					$sn=date("y"). date('m').date('d')."001";
				}
			} else { 
				$sn=date("y"). date('m').date('d')."001";
			}
			
		}
		
		if($TransTag==0){
			$qry ="SELECT * FROM patient_info where p_id='$_POST[p_id]'";
						//echo $qry;
						 if(!$q=mysqli_query($con,$qry)){
							die('Error: SELECT * FROM patient_info Fail.'.mysqli_error($con));
						}
						 $data_check = mysqli_num_rows($q);
												 //Explode for Pharmacy ID
						$pieces = explode("-", $_POST["pharmacy"]);//split the ID text
						$pharmacy=$pieces[1];
						$p = explode(",", $pharmacy);
						$pharmacy_id=$p[0];
						 
						 $q ="SELECT * FROM `pharmacy` where `pharmacy_id`='$pharmacy_id'";
						//echo $q;
						 if(!$q=mysqli_query($con,$q)){
							die('Error: SELECT * FROM `pharmacy` Fail.'.mysqli_error($con));
						}
						 $data = mysqli_num_rows($q);
						if($data_check==0){
							
							echo "<div class=\"alert alert-info alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
								<h3 align='center'>Sorry,  Your Client ID <b>Not Found!!!</b> in our database. </h3>
                            </div>";
							exit;
						}else if(empty($data)){
							echo "<div class=\"alert alert-info alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
								<h3 align='center'>Sorry,  Your Nearest Pharmacy <b>Not Found!!!</b> in our database. </h3>
                            </div>";
							exit;
						}else{	
			$product_no=$_POST['product_no'];
			$medicine_name=$_POST['medicine_name'];
			$quantity=$_POST['quantity'];
			$p_sn=$_POST['p_id'];
			$str='';
			for($i=0;$i<count($product_no);$i++){
		

				$str="INSERT INTO sale_details ( p_id, sale_no, medicine_name, quantity, pharmacy, order_date,order_time,confirm, grand_total_quantity,service_charge,remarks) VALUES('$p_sn','$sn','".$medicine_name[$i]."','".$quantity[$i]."','$_POST[pharmacy]','$date','$order_time','No','$_POST[sub_total]','$_POST[service_charge]','$_POST[remarks]');";
				//echo $str;
					
				 if(!mysqli_query($con,$str)){
							die('Error: INSERT INTO sale_details Fail.'.mysqli_error($con));
						}
			}
			$re = mysqli_query($con,"SELECT * FROM `patient_info` WHERE `p_id`='$p_sn'");
			while($row2 = mysqli_fetch_array($re)){
				$name=strtoupper($row2['p_name']);
				$phone=$row2['phone'];
				$email=$row2['email'];
			}
			
			// send sms start
							$SMS = "Boro Dakter: Dear ".$name.", thanks for order. Your Order ID:".$sn.". THANK YOU";
								
							try {
									$soapClient = new SoapClient("http://api.onnorokomsms.com/sendsms.asmx?wsdl");
									$paramArray = array(
									 'userName'=>"01715840339",
									 'userPassword'=>"9226",
									 'mobileNumber'=> $phone,
									 'smsText'=>$SMS,
									 'type'=>"1",
									 'maskName'=> "",
									 'campaignName'=>'',
									 );
									 $value = $soapClient->__call("OneToOne", array($paramArray));
								 } catch (dmException $e) {
								 // echo $e;
							}
				
			//end sms
			
			// Email Send Start
							$mail = new PHPMailer();
							$mail->IsSMTP(); 
							$mail->CharSet="UTF-8";
							$mail->Host = "mail.borodakter.info";
							$mail->SMTPDebug = 0; 
							$mail->Port = 465 ; //465 or 587
							$mail->SMTPSecure = 'ssl';  
							$mail->SMTPAuth = true; 
							//Authentication
							$mail->Username = "noreply@borodakter.info";
							$mail->Password = "suman2020";
							$mail->setFrom('noreply@borodakter.info', 'BORO DAKTER');
							$mail->Subject = "Medicine :: Order";
							$body="Dear " .$name. ",<br><br>".
								"Your Order No:".$sn."<br>".
								"See Invoice Click here <a href= 'http://www.borodakter.info/order/invoice_process.php?sale_no=".$sn."' target='_blank'> Invoice </a><br><br>".
								"Kind Regards,<br>AnandasoftBD,Mymensingh<br>".
								"Please don't reply to this email.";
							$mail->msgHTML($body);
							$mail->AltBody = 'Message from BORO DAKTER';
							$mail->addAddress($email);
							$mail->send();
			// Email Send End	
			
			
			mysqli_query($con,"UNLOCK TABLES");
				//exit;
			unset($_POST);
			unset($email);
			unset($phone);
			echo '<script type="text/javascript"> window.open("invoice_process.php?sale_no='.$sn.'","_blank");</script>';
			//echo '<script type="text/javascript"> alert("OK");</script>';
		}	
}	
}	


	   ?>
<div align="center" id="content"  style="width:100%;">
		<input type="hidden" name="sale_no" value="<?php echo $sn;?>">	
	<div class="col-md-12">
	<div class="panel panel-primary">
							<div class="panel-heading" align="center">
									<h3 class="panel-title">Order Medicine(ঔষধ অর্ডার)</h3>
							</div>
		<div class="panel-body">
		<div class="col-md-4 col-md-offset-4">
		<form action='' method='POST'>
			<div class="form-group" align="center">
               <label>For Change Order(অর্ডার পরিবর্তন করা): </label><input type="text" class="form-control" name="ReceiptNo" id="ReceiptNo"  required placeholder="Old Receipt No">
				<button style="margin-top: 5px; margin-bottom: 5px;" type="submit" name="OldMemo" class="btn btn-primary"  value="">Refresh(রিফ্রেশ)</button>
						
            </div>
		</form>
        </div>
<div class="col-md-12" align="center">
	<div class="col-md-3 col-md-offset-2" align="center" >
		<form>
			<div class="form-group" align="center">
			<strong>Medicine(ঔষধ)</strong> <input type="text" class="form-control" name="medicine_name" id="medicine_name" placeholder="Napa 500mg Tab">
			</div>
	</div>
	<div class="col-md-3 col-md-offset-2" align="center">
			<div class="form-group" align="center">
			<strong>Quantity(পরিমাণ/কতগুলি) </strong> <input type="text" name="quantity" class="form-control" id="quantity" placeholder="10">
			</div>
			
	</div>
	<div class="col-md-4 col-md-offset-4" >
			<input name = "AdditionalTest" id = "AdditionalTest" type = "button" class="btn btn-primary" value = "ADD(যুক্ত করুন)" onclick = "AddExtra()">
			
	</div>
		</form>
</div> 
<?php
	
	unset($product_no);
	unset($medicine_name);
	unset($quantity);
			
	if($_POST['ReceiptNo']){
	//echo "SELECT * FROM sale_details where sale_no='$_POST[ReceiptNo]'";
		
		$subTotal=0;
		$result2 = mysqli_query($con,"SELECT * FROM sale_details where sale_no='$_POST[ReceiptNo]'");
		while($row = mysqli_fetch_array($result2)){
			$p_id=$row['p_id'];
			$pharmacy=$row['pharmacy'];
			$remarks=$row['remarks'];
			$service_charge=$row['service_charge'];
			$grand_total_quantity=$row['grand_total_quantity'];
			
			$product_no[]=$row['medicine_no'];
			$medicine_name[]=$row['medicine_name'];
			$type[]=$row['type'];
			$power[]=$row['power'];
			$quantity[]=$row['quantity'];
			
		}
		
		
		
		
		
	}
?>
		<form action="" method="POST" autocomplete="on">
			<div class="col-md-12">
				<div class="col-md-3 col-md-offset-2" align="center">
				<div class="form-group">
				
					<label>Client ID(ক্রেতার আইডি)</label>
					<input name="p_id" class ="form-control" id="p_id" type="text" placeholder="200404001" required value="<?php echo ($_POST['ReceiptNo'])? $p_id:'';?>" ></td>								
				</div>
				</div>
				<div class="col-md-3 col-md-offset-2" align="center">
				<div class="form-group">
					<label>	Nearest Pharmacy(নিকস্টস্থ ফার্মেসী) </label>
					<input name="pharmacy" type="text" class ="form-control" required id="pharmacy" value="<?php echo ($_POST['ReceiptNo'])? $pharmacy:'';?>" required></td> 
						
				</div>
				</div>
				
				
				
				<div class="col-md-12">
					<table class="table table-bordered" id="PTest" align="center">
						<thead>
							<tr>
								<td align="Center"><b>সিরিয়াল</b></td>
								<td align="Center"><b>ঔষধ</b></td>
								<td align="Center"><b>পরিমাণ/কতগুলি</b></td>
								<td align="Center"><b>মুছুন</b></td>				
							</tr>
						</thead>
						<tbody>
						<?php 
							for($i=0; $i<count($product_no); $i++){
								$j=$i+1;
								echo "<tr><td>" . $j . "</td><td ><input type='hidden' name='product_no[]' value='".$product_no[$i]."'><input type='hidden' name='medicine_name[]' value='".$medicine_name[$i]."'>" . $medicine_name[$i] . "</td><td ><input type='hidden' name='quantity[]' value='".$quantity[$i]."'>" . $quantity[$i] . "</td><td style=' padding: 2px; '><img src='home/img/delete-img.png' width='20px' hspace='20' onclick='removeRow(this)'> </td></tr>";
							}
						?>
					</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<div class="form-group" align="right">
						<label class="control-label col-sm-8" for="sub_total" >Grant Total Quantity(সর্বমোট পরিমাণ ):</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="sub_total" id="sub_total"  value="<?php echo ($_POST['ReceiptNo'])? $grand_total_quantity:'';?>"  readonly>	 
						</div>	
					</div>	
					<div class="form-group" align="right" >
						<label class="control-label col-sm-8" for="service_charge">Service Charge(সার্ভিস চার্জ ):</label>
						<div class="col-sm-4">
							<input type="text" name="service_charge" class="form-control" id="service_charge"  value="<?php echo ($_POST['ReceiptNo'])? $service_charge:'';?>" readonly>
						</div>
					</div>
					<div class="form-group" align="right" >
						<label class="control-label col-sm-8" for="service_charge">Remarks(মন্তব্য) :</label>
						<div class="col-sm-4">
						<input type="text" name="remarks" class="form-control" placeholder="Remarks" value="<?php echo ($_POST['ReceiptNo'])? $remarks:'';?>">
					</div>
					</div>
						
					
					<div class="col-md-4 col-md-offset-4" >
					
								<input name="sale_no" type="hidden" value="<?php echo $sale_no;?>">
								<input name="sale_date" type="hidden" value="<?php echo $sale_date;?>">
								<input name="ReceiptNo" type="hidden" value="<?php echo $_POST['ReceiptNo'];?>">
								<button type="submit" name="save" onclick="return confirm('     যদি অর্ডারটি নিশ্চিত করতে চান।\n\n  তাহলে আপনাকে অবশ্যই সর্বনিম্ন \n৩০০ টাকার উপরে ঔষধ কিনতে হবে।');" class="btn btn-primary">
								  <span>Order(অর্ডার)</span> 
								</button>
								
					</div>		
				</div>
			</div>
		</form>
        
	     </div>	
        </div>
	   </div>
	   </div>
        <?php
		mysqli_close($con);
	include "footer.php";
	?>
    </div>
</body>
</html>