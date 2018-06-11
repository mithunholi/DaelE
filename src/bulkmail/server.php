<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	$query = mysql_query("select host_name,port_no,user_name,user_password,from_mail,from_name from tbl_mail_setting limit 1");
	if(mysql_num_rows($query)>0){
		$row = mysql_fetch_assoc($query);
		$hostname=$row['host_name'];
		$portno = $row['port_no'];
		$username = $row['user_name'];
		$userpassword = $row['user_password'];
		$frommail = $row['from_mail'];
		$fromname = $row['from_name'];
		define("HOST", "$hostname");      // sets GMAIL as the SMTP server
		define("PORT","$portno");                   // set the SMTP port
		define("USERNAME","$username");  // GMAIL username
		define("PASSWORD", "$userpassword");            // GMAIL password
		define("FROMMAIL", "$frommail");
		define("FROMNAME", "$fromname");
	}
}else{
	print "<script>window.location.href='../index.html';</script>";
}
?>