<?php
include_once('head.php');
include "home/config.php";
			
			
			date_default_timezone_set('Asia/Dacca');
			$date = date('Y-m-d', time());
			
			
if(!class_exists('PHPMailer')) {
    require('home/phpmailer/class.phpmailer.php');
    require('home/phpmailer/class.smtp.php');
}
	error_reporting(E_STRICT | E_ALL);
?>
<?php
if(isset($_POST['send'])){
//Contact ID Genarate
		$result2 = mysqli_query($con,"SELECT max(contact_id) as inv FROM contact where contact_date='$date'");
			if($rows = mysqli_fetch_array($result2)){ 
				if($rows['inv']>0){
					$c_id=$rows['inv']+1;
				} else {
					$c_id=date("y"). date('m').date('d')."001";
				}
			} else { 
				$c_id=date("y"). date('m').date('d')."001";
			}


//Data Insert

$str="INSERT INTO `contact`(`id`, `contact_date`, `contact_id`, `name`, `mobile`, `email`, `subject`, `message`) VALUES (NULL,'$date','$c_id','$_POST[name]','$_POST[mobile]','$_POST[email]','$_POST[subject]','$_POST[message]');";				
				//echo $str;
					
				 if(!mysqli_query($con,$str)){
							die('Error: INSERT INTO `contact` Fail.'.mysqli_error($con));
						}

// Email Send Start
					$name=$_POST['name'];
					$email='anandasoftbd@gmail.com';
					$subject=$_POST['subject'];
					$mobile=$_POST['mobile'];
					$message=$_POST['message'];
					
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
							$mail->Subject = $subject;
							$body="Dear Boro Dakter,<br>".
								"Your new Contact No: ".$c_id."<br>".
								"Contact Person Name: ".$name."<br>".
								"Mobile: ".$mobile."<br>".
								"Message: ".$message."<br><br>".
								"Kind Regards,<br>AnandasoftBD,Mymensingh<br>".
								"Please don't reply to this email.";
							$mail->msgHTML($body);
							$mail->AltBody = 'Message from BORO DAKTER';
							$mail->addAddress($email);
							$mail->send();
			// Email Send End			

echo '<script type="text/javascript"> alert("Thank You Contact With Us. Contact ID='.$c_id.'");</script>';
}


?>

<!-- mail -->
		<div class="col-md-12">
			<h3>Contact With Us</h3>
			
				<div class="col-md-4 " align="left">
					<h4 ><span>BORO DAKTER</span></h4>
					<h5>
						Address:- <span>In front of Zilla School, Mymensingh</span><br /><br />
						Email:- <span><a href="#">anandasoftbd@gmail.com</a></span><br /><br />
						Mobile:- <span>01715104528</span>
					</h5>
				</div>
				<div class="col-md-8">
					<form action="" method="post">
						<div class="col-md-4 form-group " align="right">
							<input type="text" name="name" class="form-control" placeholder="Name*" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Name*';}" required="true">
							<input type="email" name="email" class="form-control"  placeholder="Email*" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email*';}" required="true">
						</div>
						<div class="col-md-4 form-group " align="right" >
							<input type="text" name="mobile" class="form-control"  placeholder="Mobile*" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Mobile*';}" required="true">
							<input type="text" name="subject"  class="form-control"  placeholder="Subject*" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Subject*';}" required="true">
						</div>
						<div class="col-md-8 form-group " align="right" >
						<textarea  name="message" class="form-control"  placeholder="Message...*" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Message...*';}" required="true"></textarea>
						</div>
						<div class="col-md-8 form-group " align="center" >
						<input type="submit" class="btn btn-primary" name="send" value="SEND">
						<input type="reset" class="btn btn-default" value="CLEAR">
						</div>
					</form>
				</div>
		</div>		
<!-- //mail -->

<?php  
include_once('footer.php');
?>