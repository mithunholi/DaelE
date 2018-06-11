<?php

// example on using PHPMailer with GMAIL
include("server.php");
include("class.phpmailer.php");
include("class.smtp.php"); // note, this is optional - gets called from main class if not already loaded

$mail = new PHPMailer();



$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = HOST;      // sets GMAIL as the SMTP server
$mail->Port       = PORT;                   // set the SMTP port

$mail->Username   = USERNAME;  // GMAIL username
$mail->Password   = PASSWORD;            // GMAIL password

$mail->From       = FROMMAIL;
$mail->FromName   = FROMNAME;

$mail->AddReplyTo($mail->From,$mail->FromName);
$names = $_POST["email_to"]; // names
$to = $_POST["hidden_email_to"]; //email address

$subject = $_POST["subject"];
$content = $_POST["about"];

if($to != "" and $names != "" and $subject != ""){
	$mail->Subject    = $subject;
	$mail->WordWrap   = 50; // set word wrap
	$toaddress = split(";",$to);
	$fnames = split(";",$names);
	$target = "images/"; 
	$target = $target . basename( $_FILES['imgfile']['name']);
	if(move_uploaded_file($_FILES['imgfile']['tmp_name'], $target)) 
	 { 
		$targetfile = $target;
	 }
	 $targetfile = $target;
	
	for($i=0;$i<sizeof($toaddress);$i++){
		$to_add = $toaddress[$i];
		$stringData = "<html><body>Dear ".$fnames[$i].",<br /><br /><p>".$content."</p><p><img src="."\"".$targetfile."\""." /></p><br /><br />"."Thanks and Regards.<br />".$mail->FromName."</body></html>";
	 	
		$mail->MsgHTML($stringData);
		$mail->AddAttachment($targetfile);
		$mail->AddAddress($to_add);
		$mail->IsHTML(true); // send as HTML
	
		if(!$mail->Send()) {
			$result = 0;
		} else {
			$result = 1;
		}
		$mail->ClearAddresses();
	}
}
print "<script language='javascript' type='text/javascript'>window.top.window.stopUpload($result);</script>";
?>

   