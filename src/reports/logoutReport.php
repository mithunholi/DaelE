<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["pageId"]="";
	$_SESSION["distId"]="";
	$_SESSION["routeId"]="";
	$_SESSION["outletId"]="";
	$_SESSION["userId"]="";
	$status = false;
} 
	if(!isset($_SESSION['yiketil']) and $_SESSION['yiketil']!="awoo"){
		print "<script>window.location.href='../index.html';</script>";	
	}
	
	require_once("../config.php");
    if(isset($_GET["userId"])) $userId = $_GET["userId"];  
	if(isset($_GET["fromdate"])) $fromdate = $_GET["fromdate"];
	if(isset($_GET["distId"])) $distId = $_GET["distId"];	
	
	if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
	if (!isset($userId) && isset($_SESSION["userId"])) $userId = $_SESSION["userId"];
	if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
	
	if($userId <> '' and $userId <> '0'){
   		$queryString = " and a.userid = '$userId' ";
   	}else {
   		$queryString = "";
   	}
?>
<html>
<head>
<meta name="generator" http-equiv="content-type" content="text/html; charset=utf-8">

<title>mCRM -- Order Details</title>
	<link href="main.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="CRM.js"></script>
	<script type="text/javascript" src="calendar.js"></script>
	
</head>
<body>
<?php
  require_once("../config.php");
  require_once('../library/functions.php');
  
  //echo "Record Status :".$recordstatus."<br>";
  if($pageId == "" or ((int) $pageId <= 0)){
  	$showrecs = REC_PER_PAGE;
  }else{
  	$showrecs = $pageId;
  }
  $pagerange = PAGE_RANGE;

  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  switch ($a) {
    
	case "filter":
	  select();
	  break;
	case "del":
	  sql_delete();
	  break;
    default:
      select();
      break;
  }

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($fromdate)) $_SESSION["fromdate"] = $fromdate;
  if (isset($distId)) $_SESSION["distId"] = $distId;
  if (isset($pageId)) $_SESSION["pageId"] = $pageId;
  if (isset($userId)) $_SESSION["userId"] = $userId;
  
  mysql_close($conn);
?>

</body>
</html>

<?php 
/****************************** User Defined Functions Start *******************************************/
function select()
{
  	global $a;
  	global $showrecs;
  	global $page;
  	global $filter;
  	global $filterfield;
  	global $order;
  	global $ordtype;
	global $userId;
	global $distId;
	global $fromdate;
	global $pagerange;
	global $recordstatus;
	global $conn;
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$fromdate ="";
		$userId="";
		
		$_SESSION["userId"]="";
		
  	}

  	$checkstr = "";
  	if($fromdate == "") {	
		$fromdate = date("d-m-Y");
		//$fromdate = "16-07-2011";
	}

	$fromdate = date("d-m-Y",strtotime($fromdate));
	if($fromdate <> ""){
       //$qry1 = "  WHERE date_format(rec_date,'%e%m%Y') between '$fromdate' and '$todate' ";
	   $qry1 = " and date_format( rec_date, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
	   $qry2 = " and date_format( odate, '%e/%m/%Y' )  = date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) ";
	   $qry3 = " and date_format( routeday, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
  	}
  	if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	if ($count % $showrecs != 0) {
    	$pagecount = intval($count / $showrecs) + 1;
  	}
  	else {
    	$pagecount = intval($count / $showrecs);
  	}
  	$startrec = $showrecs * ($page - 1);
  	if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	//echo "User ID :".$userId."<br>";
	if($userId <>"0" and $userId <> ""){
		$dist_query = "select b.* from tbl_assigntp a, tbl_distributor b where a.org_dist_id= b.dist_id and a.user_id = '$userId' " .$qry3;

	}else{
		$dist_query = "SELECT b.* from tbl_assigntp a, tbl_distributor b where a.org_dist_id= b.dist_id ". $qry3;
	}
	
	if($distId <> "0" and $distId <> ""){
		$dist_query .= " and a.org_dist_id='$distId' group by a.org_dist_id";
	}else{
		$dist_query .= " group by a.org_dist_id";
	}
	//echo "Dist Query :".$distId."<br>";
	$userList = buildUserAssignTP($userId,$fromdate,$fromdate);
	
	if($userId <> "0" and $userId <> ""){
		//echo "D :".$distId." U :".$userId." FD :".$fromdate."<br>";
		$distributorList = buildDistributorAssignTP($distId,$userId,$fromdate);
	}
	?>
   
  
 	<form name="frmFilter" action="" method="post">
 	   <table border="1" cellspacing="0" cellpadding="4" width="100%" bgcolor="#EAEAEA">
       	  <tr>
          	<td align="left" width="15%"><b>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td align="left"><input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>">
             		<a href="javascript:show_calendar('document.frmFilter.fromdate', document.frmFilter.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
                   
            </td>
         </tr>
         
         <tr>
          <td align="left" width="15%"><b>Username :</b></td> 
          <td align="left">
                <select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/logoutReport.php?userId='+this.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
          </td>
        </tr>
        <tr>
          <td align="left" width="15%"><b>Distributor :</b></td> 
          <td align="left">
                 <select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/logoutReport.php?distId='+this.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
   				</select>
                
 		  </td>
          
     	</tr>
        <tr>
        	<td colspan="2" align="left"> 
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/logoutReport.php?a=filter')">
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/logoutReport.php?a=reset')">
            </td>
        </tr>
      </table>
      <br>
    </form>
  
  <?php	
  	
  
  	if($userId <> "" and $userId <>"0"){
		echo "<table class='tbl' border='0' cellspacing='1' cellpadding='5' width='100%'>";
		echo "<tr><td width='15%' class='hr'>Tour Plan :</td>";
		$fdate = date("Y-m-d",strtotime($fromdate));
		$qry = " and date_format( routeday, '%Y-%m-%d')  = '$fdate' and user_id='$userId'";
  	 
  		if($distId <> "" and $distId <> "0"){
  			//$qry .= " and org_dist_id = '$distId' ";
  		}	 
	
  		$sql = "select * from (select * from tbl_assigntp where 1 and comment <>''".$qry." group by org_dist_id)subq order by org_dist_id";
		//echo "SQL :".$sql."<br>";
		$result = mysql_query($sql,$conn) or die("Error in Assign TP1 :".mysql_error());
		if(mysql_num_rows($result)>0){
			echo "<td class='hrhighlightgreen' colspan='3'>CHANGED</td>";
		}else{
			echo "<td class='sr' colspan='3'>NOT CHANGED</td>";
		}
		echo "</tr>";
	}
	
	
  	for ($i = $startrec; $i < $reccount; $i++)
  	{
		$row = mysql_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		$distId = $row["org_dist_id"];
    	
	 	
	 	$dqry = " and org_dist_id='$distId' ";
		$dqry1 = " and distid='$distId' ";
		$dqry2 = " and dist_id='$distId' ";
	 	
     if($userId <> "" and $userId <> "0"){
	 	echo "<table class='tbl' border='0' cellspacing='1' cellpadding='5' width='100%'>";
		
		//echo "Date :".date("d-m-Y",strtotime($row["logout"]))."<br>";
		/****************************** Distributor Name **********************************************************/
		echo "<tr><td width='15%' class='hr'>Distributor :</td><td class='sr' colspan='3'>".getDistributorNames($distId)."</td></tr>";
		
		/************************************* Login - Logout Time ************************************************/
		$log_sql = "select * from tbl_logoutrep where distid = '$distId' and userid='$userId' and date_format(login,'%d-%m-%Y') = '$fromdate' order by distid,login";
		//echo "Qry :".$log_sql."<br>";
		$log_result = mysql_query($log_sql,$conn) or die("Error in Log :".mysql_error());
		while($logrow = mysql_fetch_assoc($log_result)){
			//echo "Log Row :"."<br>";
			$login = $logrow["login"];
			if($login == "0000-00-00 00:00:00"){
				echo "<tr><td width='15%' class='hr'>Login :</td><td class='sr'>NOT DONE</td></tr>";
			}else{
				echo "<tr><td width='15%' class='hr'>Login :</td><td class='hrhighlightgreen'>".$login."</td></tr>";
			}
			$location="";
			$logincid = $logrow["login_cellid"];
			if($logincid <> "" and $logincid <> "null" and strlen($logincid) > 1){
				$location = getLocation($logincid);
				echo "<tr class='sr'>";
				echo "<td width='15%' class='hr'>Login CellID :</td><td width='30%'>".$logincid."</td>";
				echo "<td width='15%' class='hr'>Login Location :</td><td width='30%'>".$location."</td>";
				echo "</tr>";
			}
			
			if($logrow["logout"] == "0000-00-00 00:00:00"){
				echo "<tr><td width='15%' class='hr'>Logout :</td><td class='sr'>NOT DONE</td></tr>";
			}else{
				echo "<tr><td width='15%' class='hr'>Logout :</td><td class='hrhighlightgreen'>".$logrow["logout"]."</td></tr>";
			}
			$location="";
			$logoutcid = $logrow["logout_cellid"];
			if($logoutcid <> "" and $logoutcid <> "null" and strlen($logoutcid) > 1){
				$location = getLocation($logoutcid);
				echo "<tr class='sr'>";
				echo "<td width='15%' class='hr'>Login CellID :</td><td width='30%'>".$logoutcid."</td>";
				echo "<td width='15%' class='hr'>Login Location :</td><td width='30%'>".$location."</td>";
				echo "</tr>";
			}
					
			/*************************************** Primary Tag **************************************************/
			//echo "PR TAG :".$logrow["pr_tag"]."<br>";
			echo "<tr class='sr'><td width='15%'>Primary Tag:</td>";
			if($logrow["pr_tag"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Secondary Stock **************************************************/
			echo "<tr class='sr'><td width='15%'>Secondary Stock:</td>";
			if($logrow["sec_stock"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Secondary Sales **************************************************/
			echo "<tr class='sr'><td width='15%'>Secondary Sales:</td>";
			if($logrow["sec_sales"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Outlet **************************************************/
			echo "<tr class='sr'><td width='15%'>New Outlets:</td>";
			if($logrow["outlet"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Secondary TAG **************************************************/
			echo "<tr class='sr'><td width='15%'>Secondary Tag:</td>";
			if($logrow["sec_tag"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Primary Stock **************************************************/
			echo "<tr class='sr'><td width='15%'>Primary Stock:</td>";
			if($logrow["pr_stock"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Primary Sales **************************************************/
			echo "<tr class='sr'><td width='15%'>Primary Sales:</td>";
			if($logrow["pr_sales"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Primary Return **************************************************/
			echo "<tr class='sr'><td width='15%'>Primary Returns</td>";
			if($logrow["pr_return"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
			/*************************************** Secondary Return **************************************************/
			echo "<tr class='sr'><td width='15%'>Secondary Returns</td>";
			if($logrow["sec_return"] == '1'){
				echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
			}else{
				echo "<td class='sr' colspan='3'>NOT TAKEN</td>";
			}
			echo "</tr>";
		}//while
	}//user if
	echo "</table>";
	$distId="";
  }//for loop
  mysql_free_result($res);
 
} 
?>


<?php

//**************************** USER DEFINED FUNCTIONS ******************************

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $userId;
  global $distId;
  global $fromdate;
  
  global $queryString;
  global $repstatus;
  
  
  $qry = " WHERE date_format( routeday, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
  
  if($userId <> "" and $userId <>"0"){
  	$qry .= " and user_id = '$userId' ";
  }
  
  if($distId <> "" and $distId <> "0"){
  	$qry .= " and org_dist_id = '$distId' ";
  } 
  $sql = "select * from (select * from tbl_assigntp ".$qry." group by org_dist_id)subq order by org_dist_id";
  //$sql = "select * from tbl_log order by logout";
 //echo "SQL :".$sql."<br>";

  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $userId;
  global $distId;
  global $fromdate;
  global $todate;
  global $queryString;
  global $repstatus;
  
 
  $qry = " WHERE date_format( routeday, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
  
  if($userId <> "" and $userId <>"0"){
  	$qry .= " and user_id = '$userId' ";
  }
  
  if($distId <> "" and $distId <> "0"){
  	$qry .= " and org_dist_id = '$distId' ";
  } 
  $sql = "select count(*) from (select * from tbl_assigntp ".$qry." group by org_dist_id)subq order by org_dist_id";
  //$sql = "select count(*) from tbl_log  order by logout";

  
 //echo "<br>"."SQL1 :".$sql;
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} 

function printTourPlan($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$toursql = "select * FROM tbl_assigntp where user_id = '$uid' and routeday >= '$lin' and routeday <='$lout' and comment<>'' and org_dist_id='$did'"; 
	//echo "Tour SQL :".$toursql."<br>";
	$tourresult = mysql_query($toursql,$conn) or die("Error in Tour Plan :".mysql_error());
	if(mysql_num_rows($tourresult)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>CHANGED</td>";
	}else{
		echo "<td class='sr' colspan='3'>NOT CHANGED</td>";
	}
}

function printPrimaryTag($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.deliver_status='DONE' and b.ordertype='ORDER BOOKING'
				and b.deliver_date >= '$lin' and b.deliver_date <='$lout' group by b.orderid";
	//echo "Tag SQL :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Primary Tag :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}

function printSecondaryStock($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$tagSQL = "select a.* from tbl_openstock a,tbl_openstock_child b where a.orderid=b.orderid and 
				b.userid='$uid' and b.rec_date >= '$lin' and b.rec_date <= '$lout' and b.distid='$did'";
	//echo "Sec Stock :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Secondary Stock :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}

function printPrimaryStock($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$tagSQL = "select * from tbl_cstock a,tbl_cstock_child b where a.cstock_id=b.cstock_id and b.user_id='$uid' and b.rec_date >= '$lin' 
				and b.rec_date <= '$lout' and b.dist_id='$did'";
	//echo "Sec Stock :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Secondary Stock :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}


function printSecondarySales($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	/*$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.transit_status = 'TRANSIT' and b.ordertype='SALES' 
				and b.transit_cancel_status='FALSE' and b.deliver_status='FALSE' and b.transit_date >= '$lin' and b.transit_date <= '$lout' 
				group by b.orderid";*/
	$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.ordertype='SALES' 
				and b.transit_date >= '$lin' and b.transit_date <= '$lout' 
				group by b.orderid";
	//echo "Sec Sales SQL :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Secondary Sales :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}
}

function printOutlet($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$outletSQL = "select * from tbl_outlet where ucase(comment)='ADDED' and user_id='$uid' 
					and cdate >= '$lin' and cdate <= '$lout' and dist_id='$did'";
					//echo "SQL :".$outletSQL;
	//echo "Outlet SQL :".$outletSQL."<br>";					
	$outletRes = mysql_query($outletSQL,$conn) or die("Error in Outlet :".mysql_error());
	if(mysql_num_rows($outletRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>ADDED</td>";
	}else{
		echo "<td colspan='3'>NOT ADDED</td>";
	}

}

function printSecondaryTag($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.ordertype='SALES' 
				and b.deliver_status='DONE' and b.deliver_date >= '$lin' and b.deliver_date <= '$lout' group by b.orderid";
	//echo "Sec Tag SQL :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Secondary Tag :".mysql_error());
	
	$tagSQL1 = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.ordertype='SALES' 
				and b.transit_cancel_status='CANCEL' and b.transit_cancel_date >= '$lin' and b.transit_cancel_date <= '$lout' group by b.orderid";
	$tagRes1 = mysql_query($tagSQL1,$conn) or die("Error in Secondary Tag :".mysql_error());
					
	if(mysql_num_rows($tagRes)>0 or mysql_num_rows($tagRes1)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}

function printPrimarySales($uid,$did,$lin,$lout){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	/*$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.order_status='PENDING' and b.ordertype='ORDER BOOKING'
				and b.acc_rej_status ='FALSE' and b.order_date >= '$lin' and b.order_date <= '$lout' group by b.orderid";*/
	$tagSQL = "select b.* from tbl_orderbook a,tbl_orderbook_child b 
				where a.orderid=b.orderid and b.userid='$uid' and b.distid='$did' and b.ordertype='ORDER BOOKING'
				and b.order_date >= '$lin' and b.order_date <= '$lout' group by b.orderid";
	//echo "Pri Sales SQL :".$tagSQL."<br>";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Primary Sales :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}

function printPrimaryReturn($uid,$did,$lin,$lout,$status){
	global $conn;
	$lin = date("Y-m-d H:i:s",strtotime($lin));
	$lout = date("Y-m-d H:i:s",strtotime($lout));
	$tagSQL = "select a.* from tbl_pr_master a,tbl_pr_child b where a.returnid=b.returnid and ucase(b.ordertype)='$status' 
					and b.userid='$uid' and b.rdate >= '$lin' and b.rdate <= '$lout' and b.distid='$did'";
	$tagRes = mysql_query($tagSQL,$conn) or die("Error in Primary Return :".mysql_error());
	if(mysql_num_rows($tagRes)>0){
		echo "<td class='hrhighlightgreen' colspan='3'>TAKEN</td>";
	}else{
		echo "<td colspan='3'>NOT TAKEN</td>";
	}

}
?>
			
	