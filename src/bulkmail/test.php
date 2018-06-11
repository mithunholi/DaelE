<?php  
 
require("class.phpmailer.php"); // path to the PHPMailer class
 require("class.smtp.php"); // path to the PHPMailer class

$mail = new PHPMailer();  
 
//$mail->IsSMTP();  // telling the class to use SMTP
//$mail->Mailer = "smtp";
//$mail->Host = "ssl://smtp.mail.yahoo.com";
//$mail->Port = 465;
//$mail->SMTPAuth = true; // turn on SMTP authentication
/*$mail->Username = "selvagee@gmail.com"; // SMTP username
$mail->Password = "SelvA+75+Gee"; // SMTP password 
 
$mail->From     = "selvagee@gmail.com";
$mail->AddAddress("selva@eoxys.com");  */
// $mail->Username = "selva@eoxys.com"; // your SMTP username or your gmail username
//$mail->Password = "SelvA&75&+Gee"; // your SMTP password or your gmail password
  
  $mail->SetFrom('info@eoxys.com', 'First Last');
  $mail->AddAddress('selva_gee@yahoo.com', 'Selva');
  $mail->Subject  = "Test Mail";
  $mail->IsHTML(true);
  $mail->AddEmbeddedImage('screen1.gif', 'logoimg', 'screen1.gif');
//$mail->AddEmbeddedImage("screen1.gif","image");
	//$mail->Body  = "Hi! \n\n This is my first e-mail sent from info@eoxys.com <img src=\"cid:logoimg\" />.";
//	$mail->WordWrap = 50;  
   $message = "<table width='100%'><tr>";
   $message .= "<td valign='top'><table width='50%'><tr><td>Hi! \n\n This is my first email sent to selva@eoxys.com</td></tr></table></td>";
   $message .= "<td valign='top'><table width='50%'><tr><td><img src=\"cid:logoimg\" /></td></tr></table></td>";
   $message .= "</tr></table>";
   $mail->Body  = $message;	

 
if(!$mail->Send()) {
echo 'Message was not sent.';
echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
echo 'Message has been sent.';
}
?>