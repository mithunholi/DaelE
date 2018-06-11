<?php
 $to = 'selva@eoxys.com';
 $subject = 'HTML email';
 $message = <<<EOF
<html> 
  <body bgcolor="#EFEFEF"> 
    <center> 
        <b>Hello world</b> <br> 
        <font color="red">This is my first HTML Email</font> <br> 
		<img src="screen1.gif" width="100" height="100" />
        <a href="http://www.webnapps.co.uk/">Thanks. Hasan</a> 
    </center>      
  </body> 
</html> 
EOF;
// <<< sign is the php heredoc sign, almost same as double quote

$headers="From:info@eoxys.com\r\n"
         ."Reply-To:info@eoxys.com\r\n"
		 ."Content-type: text/html;charset=iso-8859-1\r\n"; 

 //options to send to cc+bcc 
 //$headers .= "Cc: [email]name1@domain.com[/email]"; 
 //$headers .= "Bcc: [email]name2@domain.com[/email]";
 $mail_sent = @mail( $to, $subject, $message, $headers );
 echo $mail_sent ? "Mail sent" : "Mail failed";
?>