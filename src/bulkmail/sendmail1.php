 <?php
 // Setting a timezone, mail() uses this.
 date_default_timezone_set('America/New_York');
  // recipients
 $to  = "selva@eoxys.com" . ", " ; // note the comma 
 $to .= "selva_gee@yahoo.com"; 

  // subject 
 $subject = "Test for Embedded Image & Attachement"; 

 // Create a boundary string.  It needs to be unique 
 $sep = sha1(date('r', time()));

 // Add in our content boundary, and mime type specification:  
 $headers .=
    "\r\nContent-Type: multipart/mixed; 
     boundary=\"PHP-mixed-{$sep}\"";

 // Read in our file attachment
 $attachment = file_get_contents('attachment.zip');
 $encoded = base64_encode($attachment);
 $attached = chunk_split($encoded);

 // additional headers
 $headers .= "To: You <selva@eoxys.com>, 
             We <selva_gee@yahoo.com>\r\n"; 
 $headers .= "From: Me <info@eoxys.com>\r\n"; 
 $headers .= "Cc: selvagee@gmail.com\r\n"; 
 $headers .= "Bcc: selva75gee@gmail.com\r\n";

 $inline = chunk_split(base64_encode(
           file_get_contents($targetfile)));

 // Your message here:
 $body =<<<EOBODY
 --PHP-mixed-{$sep}
 Content-Type: multipart/alternative; 
               boundary="PHP-alt-{$sep}"

 --PHP-alt-{$sep}
 Content-Type: text/plain

 Hai, Its me!


 --PHP-alt-{$sep}
 Content-Type: multipart/related; boundary="PHP-related-{$sep}"

 --PHP-alt-{$sep}
 Content-Type: text/html

 <html>
 <head>
 <title>Test HTML Mail</title>
 </head>
 <body>
 <font color='red'>Hai, it is me!</font>
 Here is my picture: 
  <img src="cid:PHP-CID-{$sep}" />
 </body>
 </html>
 
 --PHP-related-{$sep}
 Content-Type: image/gif
 Content-Transfer-Encoding: base64
 Content-ID: <PHP-CID-{$sep}> 
 
 {$inline}
 --PHP-related-{$sep}--
 
 --PHP-alt-{$sep}--

 --PHP-mixed-{$sep}
 Content-Type: application/zip; name="attachment.zip"
 Content-Transfer-Encoding: base64
 Content-Disposition: attachment

 {$attached}

 --PHP-mixed-{$sep}--
 EOBODY;
 
 // Finally, send the email
 mail($to, $subject, $body, $headers);
 ?>
