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
	$_SESSION["stockdate"]="";
	$fromdate = date("d-m-Y");
	$todate = date("d-m-Y");
	$status = false;
  } 
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
  
  $user_name=$_SESSION['User_ID'];
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_GET["filter"])) $filter = @$_GET["filter"];
  if (isset($_GET["filter_field"])) $filterfield = @$_GET["filter_field"];
  
  if(isset($_GET["fromdate"])) $fromdate = $_GET["fromdate"];
  if(isset($_GET["todate"])) $todate = $_GET["todate"];
  if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];  
  if(isset($_GET["distId"])) $distId = $_GET["distId"];  
  if(isset($_GET["stockdate"])) $stockdate = $_GET["stockdate"];  
 
  
  //echo "Initial Values =".$distId."<br>"; 
  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
  if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
  if (!isset($todate) && isset($_SESSION["todate"])) $todate = $_SESSION["todate"];
  if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
  if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
  if (!isset($stockdate) && isset($_SESSION["stockdate"])) $stockdate = $_SESSION["stockdate"];
  
 //echo "From Date :".$fromdate. " = To Date :".$todate." = User Id:".$userId."<br>";
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
	
    default:
      select();
      break;
  }

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($fromdate)) $_SESSION["fromdate"] = $fromdate;
  if (isset($todate)) $_SESSION["todate"] = $todate;
  if (isset($pageId)) $_SESSION["pageId"] = $pageId;
  if (isset($distId)) $_SESSION["distId"] = $distId;
  if (isset($stockdate)) $_SESSION["stockdate"] = $stockdate;
 
  mysql_close($conn);
?>

</body>
</html>
<?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}
?>



<?php 
/****************************** User Defined Functions Start *******************************************/
function select()
{
  	global $a;
  	global $showrecs;
  	global $page;
 	global $fromdate;
	global $todate;
	global $distId;
	global $stockdate;
	global $conn;
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$fromdate ="";
		$todate="";
		$distId="";
		$stockdate="";
	
		$_SESSION["fromdate"]="";
		$_SESSION["todate"]="";
		$_SESSION["distId"]="";
		$_SESSION["stockdate"]="";
		
  	}

  	$checkstr = "";
  	
	//echo "From Date :".$fromdate. " = To Date :".$todate." = User Id:".$stockdate."<br>";
	if($fromdate == "") {	
		$fromdate = date("d-m-Y");
	}

	if($todate == "") {	
		$todate = date("d-m-Y");
	}
	
  	//$userList = buildUserAssignTP($userId,$fromdate,$todate);
	
	//if($userId <> "0" and $userId <> ""){
		//$distributorList = buildDistributorAssignTP($distId,$userId,$fromdate);
		$distributorList = buildAssignRouteForStockRotation($distId);
	//}
	
	
?>
	
    <form name="frmFilter" action="" method="post">
 	   <table border="1" cellspacing="0" cellpadding="4" width="100%" bgcolor="#EAEAEA">
       	  <tr>
          	<td align="left" colspan="2" width="98%"><b>From Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
            		<input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>">
             		<a href="javascript:show_calendar('document.frmFilter.fromdate', document.frmFilter.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
               	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> 
             		<input type="text" name="todate" id="todate" value="<?php echo $todate; ?>">
                  	 <a href="javascript:show_calendar('document.frmFilter.todate', document.frmFilter.todate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
               
            </td>
         </tr>
         
         <tr>
         	<td align="left" width="15%"><b>Distributor :</b></td>
            <td align="left">
				<select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stockRotation.php?distId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
   				</select>
			</td>
           
         </tr>
        
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/stockRotation.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stockRotation.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 
	
	 
     if($distId <> "" and $distId <> "0"){
	 	$grantSSqty = array();
	 	/**************************** Print Heading -- Category with Product List****************************************************************************/
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr><td>&nbsp;</td><td>Salesman</td><td>Visit Date</td></tr>";
		/******************************* Collect categories with respect to Product List ****************************************/
		
		//Find UserId
		$userqry = "select * from tbl_assignroute where dist_id='$distId'";
		$userresult = mysql_query($userqry,$conn) or die ("Error in Assign Route ".mysql_error());
		if(mysql_num_rows($userresult)>0){
			$userrow = mysql_fetch_assoc($userresult);
			$userId = $userrow["user_id"];
		}
		/***************************** Print Secondary Sales Qties based on date ************************************/
		
	 	$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name, region_code,area_code FROM tbl_assigntp 
						  		WHERE user_id = '$userId' and org_dist_id = '$distId' 
								and date_format(routeday,'%d-%m-%Y') between '$fromdate' and '$todate' 
								group by org_dist_id) subq order by org_dist_id";
		
		//echo "Query :".$dist_query."<br>";
		
		$dist_result = mysql_query($dist_query,$conn);
		$prev = "";
		if(mysql_num_rows($dist_result)>0){
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				//$todate = $stockdate;
				/**************************** To find last closing stock date *********************************************/
				$dspdate = date("Y-m-d",strtotime($todate));
				$prevqry = "select * from (select rec_date,user_id from tbl_cstock where user_id='$userId' and dist_id = '$distid' 
								and rec_date < '$dspdate' 
								group by rec_date) subq order by rec_date desc";
				//echo "Prev Qry :".$prevqry."<br>";
				$prevresult = mysql_query($prevqry) or die("Error in Stockist Sales ".mysql_error());
				if(mysql_num_rows($prevresult) > 0){
					$prevval='';
					while($prevrow = mysql_fetch_assoc($prevresult)){
						$rdate = $prevrow["rec_date"];
						if(date("d-m-Y",strtotime($rdate)) != $prevval){
							$prevval =date("d-m-Y",strtotime($rdate));
							$username = getUserName($prevrow["user_id"]);
							echo "<tr><td>";
							?>
                        	<input type="radio" name="radiondate" value="<?php echo $rdate ?>" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'reports/stockRotationReport.php?fromdate='+this.value+'&todate=<?php echo $todate ?>&uid=<?php echo $userId; ?>&did=<?php echo $distid; ?>')">
						<?php
							echo "</td><td>".$username."</td>";
							echo "<td>".date("d-m-Y",strtotime($rdate))."</td>";
							echo "</tr>";
						}//if
            		}//while	
				}//if
			}//while
			
			echo "</table>";
		}else{
			 
		}
		
	}//if
}//select function	

function primaryOpeningStock($prodname,$uId,$did,$rddate){
	echo "Product Name:".$prodname."=UserID :".$uId."=DistID :".$did."=Date :".$rddate."<br>";
	global $conn;
	$rddate = date("d-m-Y",strtotime($rddate));
	$pcssql = "select * from (select prod_id, sum(prod_qty) pcsqty,rec_date from tbl_cstock 
											where user_id='$uId' and dist_id='$did' and prod_name = '$prodname' 
											and date_format(rec_date,'%d-%m-%Y') ='$rddate' 
											group by prod_id,rec_date) subq order by rec_date desc ";
	
	$pcsresult = mysql_query($pcssql,$conn) or die(mysql_error());
	if(mysql_num_rows($pcsresult)>0){
		$pcsrow = mysql_fetch_assoc($pcsresult);
		return $pcsrow["pcsqty"];
	}else{
		return "0";
	}
	
}

function primaryTransit($pname,$userId,$distid,$rdate){
	global $conn;
	$rddate = date("d-m-Y",strtotime($rddate));
	$accsql = "select * from (select prod_id, sum(qty) accqty,sum(amount) accamt from tbl_orderbook 
											where userid='$userId' and distid='$distid' and prodname = '$pname' and ucase(tstatus)='TRANSIT' 
											and ordertype = 'Order Booking' and date_format(odate,'%d-%m-%Y') = '$rddate' 
											group by prod_id) subq order by prod_id ";
	$accresult = mysql_query($accsql,$conn) or die(mysql_error());
	if(mysql_num_rows($accresult)>0){
		$accrow = mysql_fetch_assoc($accresult);
		return $accrow["accqty"];
	}else{
		return "0";
	}
}

function primaryInvoiceData($pname,$userId,$distid,$fdate,$tdate){
	global $conn;
	$invsql = "select * from (select prod_id, sum(qty) invqty,sum(amount) invamt from tbl_orderbook 
											where userid='$userId' and distid='$distid' and prodname = '$pname' and ucase(tstatus)='TRANSIT' 
											and ordertype = 'Order Booking' and date_format(odate,'%d-%m-%Y') between '$fdate' and '$tdate' 
											group by prod_id) subq order by prod_id ";
	$invresult = mysql_query($invsql,$conn) or die(mysql_error());
	if(mysql_num_rows($invresult)>0){
		$invrow = mysql_fetch_assoc($invresult);
		return $invrow["invqty"];
	}else{
		return "0";
	}	
}

function secondarySales($pname,$userId,$distid,$rdate){
	global $conn;
	$rddate = date("d-m-Y",strtotime($rddate));
	$secsql = "select * from (select prod_id, sum(qty) secqty,sum(amount) secamt from tbl_orderbook 
											where userid='$userId' and distid='$distid' and prodname = '$pname' and ucase(tstatus)='TRANSIT' 
											and ordertype = 'Sales' and date_format(odate,'%d-%m-%Y') = '$rddate' 
											group by prod_id) subq order by prod_id ";
	$secresult = mysql_query($secsql,$conn) or die(mysql_error());
	if(mysql_num_rows($secresult)>0){
		$secrow = mysql_fetch_assoc($secresult);
		return $secrow["secqty"];
	}else{
		return "0";
	}

}

function dateDiff($dformat, $endDate, $beginDate)
{
	//echo "StoTime :".strtotime($beginDate). " EtoTime :".strtotime($endDate). " Diff :". strtotime($endDate)-strtotime($beginDate)."<br>";
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}

function getUserName($uid){
	$usersql = "select concat(emp_fname,'',emp_lname) empname from tbl_employee a,tbl_user b where a.emp_id=b.Title and b.user_id='$uid'";
	$userresult = mysql_query($usersql);
	$userrow = mysql_fetch_assoc($userresult);
	return $userrow["empname"];
}
?>