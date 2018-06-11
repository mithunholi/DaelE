<?php

$from = 'info@eoxys.com';
$fromname ="eoxys";
$replyto = 'info@eoxys.com';

$names = $_POST["email_to"]; // names
$to = $_POST["hidden_email_to"]; //email address
$subject = $_POST["subject"];
$content = $_POST["about"];

if($to != "" and $names != "" and $subject != ""){
	$toaddress = explode(";",$to);
	$fnames = explode(";",$names);
	//$mailto = substr($to,0,strlen($to)-1);
	$mailto = $to;
	$toaddress = explode(";",$mailto);

	//echo "SERVER DIR :".$_SERVER['DOCUMENT_ROOT']."<br>";
	 $target = "http://".$_SERVER['SERVER_NAME'].getcwd()."/images/"; 
	//$target =$_SERVER['DOCUMENT_ROOT']."elead/bulkmail/images/";
	//$target = "http://www.eoxys.com/ecrm/bulkmail/images/";
	$target = $target . basename( $_FILES['imgfile']['name']);
	if(move_uploaded_file($_FILES['imgfile']['tmp_name'], $target)){ 
 		$targetfile = $target;
 	}
 	$targetfile = $target;
 
 	$headers = "From: " . $from . "\r\n";
 	$headers .= "Reply-To: ". $replyto . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
 	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Content-Disposition: inline; filename = \"".$targetfile."\"\n\n";
 	for($i=0;$i<sizeof($toaddress);$i++){
		$to_add = $toaddress[$i];
 		/*$message = "<html><body>Dear ".$to_add.",<br /><p>".$content."</p><p><img src="."\"".$targetfile."\""." /></p><br />"."Image is shown above here.<br /></body></html>";*/
		$message = "<html><body>Dear ".$fnames[$i].",<br /><br /><p>".$content."</p><p><img src="."\"".$targetfile."\""." /></p><br /><br />"."Thanks and Regards.<br />".$fromname."</body></html>";
		$eLog="/tmp/mailError.log";

		//Get the size of the error log
		//ensure it exists, create it if it doesn't
		$fh= fopen($eLog, "a+");
		fclose($fh);
		$originalsize = filesize($eLog);
		//echo "OriginalSize :".$originalsize."<br>";
		//mail($email,$subject,$msg);
		mail($to_add,$subject,$message,$headers);

		/*
		* NOTE: PHP caches file status so we need to clear
		* that cache so we can get the current file size
		*/

		clearstatcache();
		$finalsize = filesize($eLog);

		//Check if the error log was just updated
		if ($originalsize != $finalsize) {
			//print "Problem sending mail. (size was $originalsize, now $finalsize) See $eLog";
			$result = 0;
		} else {
			//print "Mail sent to $to_add";
			$result = 1;
		}
	}//for loop
}
print "<script language='javascript' type='text/javascript'>window.top.window.stopUpload($result);</script>";
?>