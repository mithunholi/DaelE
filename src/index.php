<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){

	require_once("config.php");
	
	$user_id=$_SESSION['User_ID'];
	$user_name = $_SESSION['User_Name'];
	$user_design = $_SESSION['User_Design'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mobile CRM</title>
<link href="CRM.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="CRM.js"></script>

</head>

<body onload="LoadDiv('<?php echo CENTER_DIV ?>','menufolder/home.php');">
			
<div id="body_main">

	<div id="Header1">
    	<a id="Text_H1">User:<?php echo $_SESSION['User_Name'] ?></a>
		<a id="Text_H2"><?php echo $_SESSION['Company_Name'] ?></a>
		<a id="Text_H3" href="index2.php">Log Out</a>
	</div>
	<div id="Header2">
    	<img id="Header2_Img" src="images/LoginBanner_240.png" />
    </div>
	
    <!--Left Side-->
	<div id="Content_Page">
	<?php 
		require_once ("menu.php"); 
	?>	
    </div>
<!---Center Side-->
	<div id="Center">
		<div id="Center_H">
			<div id="status_bar">
				<img id="loading" src="images/Status_Icon_Ani3.gif" alt="loading"/> 
			</div>
			<div id="Center_Text"></div>
		</div>

		<div id="Center_Content">
		</div>
	</div>
<!--Right Side-->
<!--<div id="Right">
		<div id="Right_H">
		</div>
	</div>-->

<!--<div id="Empty">
</div>-->
<!---Footer-->
	<div id="Footer">
		<a id="Footer_Text">Powered by Eoxys Systems India Pvt Ltd</a>
	</div>
</div>
</body>
</html>
<?php
}else{
	print "<script>window.location.href='index2.php';</script>";	
}


function PrimaryReturn($otype){
	/*$query = "select count(*) totalRec1 from (select a.* from tbl_pr_master a, tbl_pr_child b,tbl_user c where a.returnid=b.returnid and b.userid = c.user_id
				 and b.dstatus='0' and b.vstatus='0' and b.ordertype='$otype' group by a.returnid)subq";
	//echo "Query :".$query."<br>";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	return $row["totalRec1"];*/
}

function PrimaryTransit($ptstatus){
	$transit_query = "SELECT count(*) transitRecord FROM (SELECT b.* FROM tbl_orderbook a,tbl_orderbook_child b,tbl_user c 
						WHERE a.orderid=b.orderid and b.userid = c.user_id and b.transit_status = 'TRANSIT' and b.deliver_status ='FALSE' 
						AND b.transit_cancel_status = 'FALSE' and b.ordertype = 'ORDER BOOKING' and b.transit_vstatus ='0' GROUP BY a.orderid) subq";
						
	//echo $transit_query;
	$transit_result = mysql_query($transit_query);
	$transit_row = mysql_fetch_assoc($transit_result);
	
	return $transit_row["transitRecord"];
}

function PrimaryDeliver($dtstatus){
	$transit_query = "SELECT count(*) deliverRecord FROM (SELECT b.* FROM tbl_orderbook a,tbl_orderbook_child b WHERE a.orderid=b.orderid 
						and b.deliver_vstatus='0' AND b.ordertype = 'ORDER BOOKING' AND b.deliver_status = '$dtstatus' 
						GROUP BY b.invoiceid) subq";
	$transit_result = mysql_query($transit_query);
	$transit_row = mysql_fetch_assoc($transit_result);
	
	return $transit_row["deliverRecord"];
}


function SecondaryTransit($ststatus){
	
	$transit_query = "SELECT count(*) transitRecord1 FROM (SELECT b.* FROM tbl_orderbook a,tbl_orderbook_child b WHERE a.orderid=b.orderid and 
						b.ordertype = 'SALES' AND b.transit_cancel_status = 'CANCEL' AND b.transit_cancel_vstatus='0' GROUP BY b.invoiceid) subq";
	$transit_result = mysql_query($transit_query);
	$transit_row = mysql_fetch_assoc($transit_result);
	
	return $transit_row["transitRecord1"];
}
?>