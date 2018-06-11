<?php
	include('class.phpmailer.php');
	include("class.smtp.php");
	
	$from = 'info@eoxys.com';
	$fromname ='eoxys';
	$replyto = 'info@eoxys.com';
	$imagePath = '../images/email/';
	
	$scriptname=end(explode('/',$_SERVER['PHP_SELF']));
	$scriptpath= str_replace($scriptname,'',$_SERVER['PHP_SELF']);
	//$imagePath = $_SERVER['HTTP_HOST'].$scriptpath.'images/';
	//echo "ImagePath :".$imagePath."<br>";
	
	//get Names, email, subject and content from User
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
		
		//$target = "http://".$_SERVER['SERVER_NAME'].getcwd()."/images/"; 
		//echo "Doc :".$imagePath."<br>";
		$pfilename = uploadImage('imgfile', $imagePath); //customer image1
		//$target = $target . basename( $_FILES['imgfile']['name']);
		//echo "Target :".$pfilename."<br>";
		$targetfile="";
		/*if(move_uploaded_file($_FILES['imgfile']['tmp_name'], $target)){ 
 			$targetfile = $target;
 		}//if*/
 		
		if($pfilename != ""){
			$targetfile = $imagePath . $pfilename;
			//echo "Target :".$targetfile."<br>";
			$mail = new PHPMailer(); // the true param means it will throw exceptions on     errors, which we need to catch
			$mail->SetFrom($from, $fromname);
			$mail->Subject  = $subject;
			$mail->IsHTML(true);
			$mail->AddEmbeddedImage($targetfile, 'logoimg', $targetfile);
			$message = "<table width='100%'><tr>";
			$message .= "<td valign='top'><table width='50%'><tr><td>".$content."</td></tr></table></td>";
			$message .= "<td valign='top'><table width='50%'><tr><td><img src=\"cid:logoimg\" /></td></tr></table></td>";
			$message .= "</tr></table>";
			$mail->Body  = $message;	
			$file = 'log.txt';
			for($i=0;$i<sizeof($toaddress);$i++){
				$to_name = $fnames[$i];
				$to_add = $toaddress[$i];
				$mail->AddAddress($to_add);
				if(!$mail->Send()) {
					$msg_text = 'Message was not sent.'.'<br>';
					$msg_text .= 'Mailer error: ' . $mail->ErrorInfo.' - '.date('d-m-Y H:i:s').'\r\n';
				} else {
					$msg_text = 'Message has been sent: '.$to_add.' - '.date('d-m-Y H:i:s').'\r\n';
				}
				
				file_put_contents($file, $msg_text, FILE_APPEND | LOCK_EX);
				$msg_text='';
				$mail->ClearAddresses();
			}//for
		}//if
  	}



//    Upload an image and return the uploaded image name 
function uploadImage($inputName, $uploadDir){
	$filePath = '';
   	$image     = $_FILES[$inputName];
	
	// if a file is given
    if (trim($image['tmp_name']) != '') {
    	// get the image extension
        $ext = substr(strrchr($image['name'], "."), 1); 

        // generate a random new file name to avoid name conflict
        $filePath = md5(rand() * time()) . ".$ext";
        
		//$size = getimagesize($image['tmp_name']);
		
		// move the image to Customer image directory
		// if fail set $filePath to empty string
		//echo 'Temp Name :'.$image['tmp_name'];
		if (!move_uploaded_file($image['tmp_name'], $uploadDir . $filePath)) {
			$filePath = '';
		}
		 
    }//if
	return $filePath;
}	
?>