<?php
 //array of file names
 $files = array('screen1.gif');
 //email set up
 $to = "selva@eoxys.com";
 $subject = "email with attachment";
 $message="";
 $messageTxt = "This is my first email with attachments";
 $headers = "From: info@eoxys.com\r\n";
 //setting the boundary
 $rand_seed = md5(time());
 $mime_boundary = "==Multipart_Boundary_x{$rand_seed}x";
 //attachment header
 $headers .= "MIME-Version: 1.0\r\n"
   ."Content-Type: multipart/mixed;\r\n"
   ." boundary=\"{$mime_boundary}\"\r\n";
 $message .= "This is a multi-part message in MIME format.\n\n"
   ."--{$mime_boundary}\n\n"
   ."Content-Type:text/plain; charset=\"iso-8859-1\"\n\n"
   ."Content-Transfer-Encoding: 7bit\n\n" . $messageTxt . "\n\n";
 $message .= "--{$mime_boundary}\n";
 //attachments
   $file = fopen($files[0],"rb");
   $data = fread($file,filesize($files[0]));
   fclose($file);
   $data = chunk_split(base64_encode($data));
   $message .= "Content-Type: {\"application/octet-stream\"};\n"
    ." name=\"$files[$x]\"\n"
    ."Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n"
    ."Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
   $message .= "--{$mime_boundary}\n";

 $mail_sent = @mail( $to, $subject, $message, $headers );
 echo $mail_sent ? "Mail sent" : "Mail failed";
?>