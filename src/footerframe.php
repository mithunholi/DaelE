<?php
session_start();
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']==$_SESSION['User_Name']){

?>

<html>
<head>
<title></title>
<link href="style_forsms.css" rel="stylesheet" type="text/css">

</head>

<body topmargin=0 leftmargin=0 rightmargin=0>
<table border=0 width=100% cellspacing=1 cellpadding=1 class="footer">
  <tr >
    <td width=309 valign=top height=21><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial">Powered by eoxys Systems India pvt Ltd</font></td>
    <td width="287"align=center><div align="left"></div>
    </font></td>
    <td  align="right" width="235" valign="bottom"><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial"></td>
    <td  align="right" width="333"><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial"></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>


</html> 
<?php
}
else {

		print "<script>window.location.href='index2.php';</script>";	
 

}
?>
