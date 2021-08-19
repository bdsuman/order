<?php
include "home/config.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title> .:: Sign Up for Client ::.</title>
<script>

function maxLengthCheck(object) {
  if (object.value.length > object.maxLength)
  object.value = object.value.slice(0, object.maxLength)
}

</script>
</head>

<body>


   
       <?php  
			include "head.php";	
			include "home/config.php";
			
			
			date_default_timezone_set('Asia/Dacca');
			$date = date('Y-m-d', time());
			$time=date("Y-m-d H:i:s",time());
			$year=date('Y',time());
			$order_time=date('h:i A', time());
			
			
			
			
			
			
if(isset($_POST['save'])){
	$qry = mysqli_query($con,"SELECT * FROM patient_info where phone='$_POST[phone]';");
						$data_check = mysqli_num_rows($qry);
						if($data_check>0){
							
							echo "<div class=\"alert alert-info alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
                                <br />
								<h3 align='center'>Sorry, Your mobile number already registered in our database. </h3>
                            </div>";
							exit;
						}else{
			$sql = mysqli_query($con,"SELECT max(p_id) as pp FROM patient_info where e_date='$date'");
			if($row = mysqli_fetch_array($sql)){ 
			if($row['pp']>0){ 
				$p_sn=$row['pp']+1;
			} else {
				$p_sn=date("y"). date('m').date('d')."001";
					}
			}else { 
				$p_sn=date("y"). date('m').date('d')."001";
			}
			//****************Isert patient Information***************//
			
			$sql="INSERT INTO patient_info(e_date,p_id,p_name,sex,address,phone,email) VALUES ('$date','$p_sn','$_POST[p_name]','$_POST[sex]','$_POST[address]','$_POST[phone]','$_POST[email]')";
			//echo $sql;	
			if (!mysqli_query($con,$sql)){
				die('Error: INSERT INTO patient_info' . mysqli_error($con));
			}
				//exit;
			
			// send sms start
							$SMS = "Boro Dakter: Dear " . $_POST['p_name'] . ", thanks for registration. Your Client ID:".$p_sn.". Must remember the Client ID for all activities including order. THANK YOU";
								
							try {
									$soapClient = new SoapClient("http://api.onnorokomsms.com/sendsms.asmx?wsdl");
									$paramArray = array(
									 'userName'=>"01715840339",
									 'userPassword'=>"9226",
									 'mobileNumber'=> $_POST['phone'],
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
			
			unset($_POST);
			echo '<script type="text/javascript"> alert("Your Client ID: '.$p_sn.'");</script>';
		}
}		
if(isset($_POST['update'])){
	
			
			//****************Update patient Information***************//
			$sql="UPDATE `patient_info` SET `p_name`='$_POST[p_name]',`sex`='$_POST[sex]',`address`='$_POST[address]',`phone`='$_POST[phone]',`email`='$_POST[email]' WHERE `p_id`='$_POST[p_id]';";
			//echo $sql;	
			if (!mysqli_query($con,$sql)){
				die('Error: UPDATE `patient_info`' . mysqli_error($con));
			}
			$sql="UPDATE `patient_info` SET `p_name`='$_POST[p_name]',`sex`='$_POST[sex]',`address`='$_POST[address]',`phone`='$_POST[phone]',`email`='$_POST[email]' WHERE `p_id`='$_POST[p_id]';";
			//echo $sql;	
			if (!mysqli_query($con,$sql)){
				die('Error: UPDATE `patient_info`' . mysqli_error($con));
			}
				//exit;
			unset($_POST);
			
			echo '<script type="text/javascript"> alert("Updated Succesfully");</script>';
		}	
		


	   ?>


<?php
if(isset($_POST['old_client_id'])){	
				$qry ="SELECT * FROM patient_info where p_id='$_POST[client_id]'";
						//echo $qry;
						 if(!$q=mysqli_query($con,$qry)){
							die('Error: SELECT * FROM patient_info Fail.'.mysqli_error($con));
						}
						  $data_check = mysqli_num_rows($q);
						if($data_check==0){
							echo "<div class=\"alert alert-info alert-dismissable\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
								<h3 align='center'>Sorry,  Your Client ID <b>Not Found!!!</b> in our database. </h3>
                            </div>";
							exit;
						}else{
		$s="SELECT * FROM `patient_info` where `p_id`='$_POST[client_id]'";
		if (!$r=mysqli_query($con,$s)){
				die('Error: SELECT * FROM `patient_info`' . mysqli_error($con));
			}
			//echo $s;
		while($row = mysqli_fetch_array($r)){
			 $p_name=$row['p_name'];
			 $address=$row['address'];
			 $sex=$row['sex'];
			 $phone=$row['phone'];
			 $email=$row['email'];
				
		}	
	}
}
?>
<div class="col-md-12" style="margin-top: 5px; margin-bottom: 5px;">   
<form action='' method='POST'>
		<div class="col-md-4 col-md-offset-4" align="left">
		<div class="panel panel-primary">
							<div class="panel-heading" align="center">
									<h3 class="panel-title">For Change Information(তথ্য পরিবর্তন করা)</h3>
							</div>
		<div class="panel-body">
			<div class="form-group" align="center">
                <input type="text" class="form-control"  name="client_id" placeholder="200404001" required >
				<button style="margin-top: 5px; margin-bottom: 5px;" type="submit" name="old_client_id" class="btn btn-primary"  value="">Refresh(রিফ্রেশ)</button>
            </div>
        </div>	
        </div>	
        </div>	
</form>
<div class="col-md-8 col-md-offset-2" align="left">
	<div class="panel panel-primary">
							<div class="panel-heading" align="center">
									<h3 class="panel-title">Sign Up Client(ক্রেতার জন্য রেজিস্ট্রশন)</h3>
							</div>
		<div class="panel-body">
			<form action="" method="POST" autocomplete="on">
				
				<div class="col-md-6">
				<div class=" form-group">
					<label>Name(নাম)<span style="color:red">*</span></label>
					<input type="text" name="p_name" class="form-control" value="<?php echo ($_POST['client_id'])? $p_name:'';?>" required>
				</div>
				</div>
				<div class="col-md-6">
				
			<div class=" form-group">
					<label>Mobile(মোবাইল)<span style="color:red">*</span></label>
					<input type="text" name="phone" class="form-control" maxlength="11" minlength="11" onkeyup="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" oninput="maxLengthCheck(this)" placeholder="Without +880 Only allow 01700000000" value="<?php echo ($_POST['client_id'])? $phone:'';?>" required>
			</div>
			</div>
			<div class="col-md-6">
			<div class="form-group">
				<label>Email(ই-মেইল)</label>
				<input type="email" name="email" value="<?php echo ($_POST['client_id'])? $email:'';?>" class="form-control" >
			</div>
			</div>
			<div class="col-md-6">
			 <div class="form-group">
                <label>Gender(লিঙ্গ)</label>       
                <select name="sex" class="form-control" >
				<?php
					if(!empty($_POST['client_id'])){
						?>
						<option value="<?php echo $sex; ?>"><?php echo $sex; ?></option>
					<?php
					}else{
					?>
                    <option value="">Select Gender</option>
					<?php
					}
				?>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            </div>
		<div class="col-md-12">
			<div class="form-group">
				<label>Present Address(বর্তমান ঠিকানা)</label>
			<div>
				<textarea class="form-control" name="address" value="<?php echo ($_POST['client_id'])? $address:'';?>"><?php echo ($_POST['client_id'])? $address:'';?></textarea>
			</div>
			</div>
		</div>
			<div class="form-group">
			<div align="center">
			<?php
			if(!empty($_POST['client_id'])){
				?>
				<input type="hidden" name="p_id" value="<?php echo $_POST['client_id']; ?>">
				<button class="btn btn-primary" name="update" type="submit" >Update(পরিবর্তন)</button>
				<?php
			}else{
				?>
				<button class="btn btn-primary" name="save" type="submit" >Save(সংরক্ষন)</button>
				<button class="btn btn-default" type="reset">Reset(মুছুন)</button>
			<?php
			}
			?>
				
			</div>
			</div>
			
			
			</form>
		</div> 
	</div>
</div>
</div>
<hr style="margin-top: 5px; margin-bottom: 5px;">	  
        <?php
		mysqli_close($con);
	include "footer.php";
	?>
   
</body>
</html>