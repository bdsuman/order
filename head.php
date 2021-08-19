<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<head>
<title>.::AnandasoftBD::.</title> 
	<link rel="stylesheet" type="text/css" href="home/css/tcal.css" />
	<script type="text/javascript" src="home/js/tcal.js"></script> 

	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="home/css/bootstrap.min.css">

  <link rel="stylesheet" href="home/css/font-awesome.css"/>
  <link rel="stylesheet" href="home/css/font-awesome.min.css"/>


<link rel="stylesheet" type="text/css" href="home/css/body.css" />
<link rel="stylesheet" href="home/css/buttonstyles.css">

<script type="text/javascript" src="home/jquery.js"></script>
<script type='text/javascript' src='home/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="home/jquery.autocomplete.css" />
<script type="text/javascript">
$().ready(function() {
    $("#pharmacy").autocomplete("get_pharmacy.php", {
        width: 260,
        matchContains: true,
        selectFirst: false
    });
});
</script>
<script type="text/javascript">
$().ready(function() {
    $("#medicine_name").autocomplete("get_medicine.php", {
        width: 260,
        matchContains: true,
        selectFirst: false
    });
});
</script>
<script type="text/javascript">
$().ready(function() {
    $("#p_id").autocomplete("get_pid.php", {
        width: 260,
        matchContains: true,
        selectFirst: false
    });
});
</script>
<script type="text/javascript">
$().ready(function() {
    $("#ReceiptNo").autocomplete("get_reciept_no.php", {
        width: 260,
        matchContains: true,
        selectFirst: false
    });
});
</script>

</head>
<div class="header"  >
<div class="col-md-12" align="center">
<div class="col-md-4" ><img src="home/img/logo.jpg" class="img-thumbnail" alt="Company Logo" width="100" height="70" style="margin-top: 5px; margin-bottom: 5px;" align="center" />    </div>
    <div class="col-md-4" align="center" style="margin-top: 20px; margin-bottom: 5px;"><span style="font-size:20pt;">BORO DAKTER</span></div>
    <div class="col-md-4" style="margin-top: 30px; margin-bottom: 5px;">
			<?php date_default_timezone_set('Asia/Dhaka');
				$date = date('l jS F Y', time()); 
				echo "Today is :<strong>&nbsp;".$date."</strong>"; ?>		
	</div>
</div>
</div>
<div class="col-md-12" align="center">
<div id="nav">
	<ul>
		<li><a href="index.php"><span>Home</span></a></li>
		<li><a href="order.php">Order Medicine</a></li>
		<li><a href="contact.php">Contact Us</a></li>
	</ul>
</div>
</div>




