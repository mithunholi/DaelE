<?php
session_start();
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']==$_SESSION['User_Name']){
$user_name=$_SESSION['User_ID'];
?>
<link href="main.css" rel="stylesheet" type="text/css">
<html>
<body topmargin=0 leftmargin=0 rightmargin=0 >
<table border=0 width=100% cellspacing=1 cellpadding=1> 
	<tr >
	  <td width=136 valign=top><div align="center"><img src="" width="53" height="30" alt="logo"></div></td>
		<td width=414 valign=top height=45><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial">User: <strong><?php echo $_SESSION['User_ID'] ?></strong><br>
		</font></td>
	  <td width="390"align=center valign="middle"><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial"><b><?php echo "Company_name";?></b></td>
	  <td  align="right" width="403" valign="middle"><FONT style="FONT-SIZE:9pt" color="#000000" face="Arial"><a href="index2.php" target="_top">Log Out </a></td>
	  <td  align="right" width="52">&nbsp;</td>
  </tr>
</table>
</body>
</html> 

<?php
}
else {
		print "<script>window.location.href='index2.php';</script>";
}
?>
