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
    case "view":
      viewrec($recid);
      break;
	case "export":
	  exporttocsv();
	  break;
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
	}
	$fromdate = date("d-m-Y",strtotime($fromdate));
     //$qry1 = "  WHERE date_format(rec_date,'%e%m%Y') between '$fromdate' and '$todate' ";
	   $qry1 = " and date_format( rec_date, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
	   $qry2 = " and date_format( odate, '%e/%m/%Y' )  = date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) ";
	   $qry3 = " and date_format( routeday, '%e/%m/%Y' )  = date_format(str_to_date('$fromdate', '%e-%m-%Y'), '%e/%m/%Y') ";
  	
  	
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
	
	?>
    
  
 	<form name="frmFilter" action="" method="post">
 	   <table border="1" cellspacing="0" cellpadding="4" width="100%" bgcolor="#EAEAEA">
       	 <tr>
          	<td align="left" width="15%"><b>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td align="left"><input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/WU_Daily_Sales_Report.php?fromdate='+this.value);">
             		<a href="javascript:show_calendar('document.frmFilter.fromdate', document.frmFilter.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
                   
            </td>
         </tr>
         
      
        <tr>
        	<td colspan="2" align="left"> 
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/WU_Daily_Sales_Report.php?a=filter')">
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/WU_Daily_Sales_Report.php?a=reset')">
            </td>
        </tr>
      </table>
      <br>
      
    </form>
    
    
   
<?php
	$dist_query = "select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) dist_name,
			 			  a.route_id,a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d 
						  WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id and a.user_id = '$userId' 
						  and a.org_dist_id = '$distId' 
						  and date_format(a.routeday,'%d-%m-%Y') = '$fromdate' group by a.org_dist_id) subq order by org_dist_id";
	//echo $dist_query;
		/*$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) dist_name,route_id,concat(route_code,'-',route_name) route_name, 		
					region_code,area_code,user_id FROM tbl_assigntp 
					WHERE date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";*/
	$dist_result = mysql_query($dist_query,$conn);
	if(mysql_num_rows($dist_result)>0){
		while($dist_row = mysql_fetch_assoc($dist_result)){
			$distid = $dist_row["org_dist_id"];
			$distname = $dist_row["dist_name"];
			$distcode = getDistributorCode($distid);
			$userId = $dist_row["user_id"];
			$grantTSAmount=0;
			$grantSSAmount=0;
			$grantSS1Amount=0;
			$grantSS2Amount=0;
			$accAmount=0;
			//************************************ Print Top Headings *****************************************************************
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
			echo "<tr><td width='15%' align='left'>SALESMAN :</td><td align='left' width='25%'>".getSalesmanNames($userId)."</td>";
			echo "<td width='10%' align='left'>REGION :</td><td align='left' width='20%'>".getRegionName($dist_row["region_code"])."</td>";
			echo "<td width='10%' align='left'>DATE :</td><td align='left' width='20%'>".$fromdate."</td></tr>";
			//echo "<tr><td width='20%'>AREA :</td><td>".getAreaName($sid)."</td></tr>";
			echo "<tr><td widht='15%' align='left'>DISTRIBUTOR :</td><td width='25%' align='left'>".$distcode."-".$dist_row["dist_name"] ."</td>";
			echo "<td width='10%' align='left'>AREA :</td><td width='20%' align='left' colspan='3'>".getAreaName($dist_row["area_code"])."</td>";
			
			echo "</tr></table>";
			
			
			
			/**************************** Print Heading -- Category with Product List****************************************************************************/
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
			echo "<tr class='hr'><td align='left' rowspan='2' width='15%'>Particulars</td>";
			
			/******************************* Collect categories with respect to Product List ****************************************/
			$cat_query = "select * from tbl_categories order by cat_code";
			$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
			if(mysql_num_rows($cat_result)>0){
				$catDatas = array();
				$prodDatas = array();
				$cntDatas = array();
				$prodCode  = array();
				$i=0;
				$catcnt = 1;
				while($cat_row = mysql_fetch_assoc($cat_result)){
					$catDatas[$i][0] = $cat_row["cat_name"];
					$catDatas[$i][1] = $cat_row["cat_code"];
					
					$prod_query = "select * from tbl_product where cat_code='".$catDatas[$i][1]."' order by prod_code";
					//echo "Prod Quyery :".$prod_query."<br>";
					$prod_result = mysql_query($prod_query);
					if(mysql_num_rows($prod_result)>0){
						$j=0;
						while($prod_row = mysql_fetch_assoc($prod_result)){
							$prodDatas[$j][$i] = $prod_row["prod_sname"];
							$prodCode[$j][$i] = $prod_row["prod_code"];
							//echo "Cat Datas :".$catDatas[$i][0]." = Prod Datas :".$prodDatas[$j][$i]."<br>";
							$cntDatas[$i] = $j;
							$j++;
							$catcnt++;
						}		
						
					}
					$i++;
					
				}//while
			}//if
			
		
			/***************************** Printing Start Here ***********************************************/
			
			if(sizeof($catDatas) > 0){
				for($i=0;$i<sizeof($catDatas);$i++){
					$colsize = $cntDatas[$i] + 1;
					$colwidth = $colsize * 15;
					echo "<td colspan='$colsize' align='center' width='$colwidth%'>".$catDatas[$i][0]."</td>";
				}//for
				echo "<td align='left' rowspan='2' width='15%'>BILL AMOUNT</td>";
				echo "</tr>";
				echo "<tr>";
				
				$columnNos = 1;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					//echo "Cntr :".$i."===Size of Product :".($cntDatas[$i]+1)."<br>";
					while($j<($cntDatas[$i]+1)){
						echo "<td align='left'>".$prodDatas[$j][$i]."</td>";
						$j++;	
						$columnNos++;
					}
				}//for
				
				echo "</tr>";
				//echo "</table>";
			}//if
			
		
			/****************************************to calculate Transit stock by product name*************************************/
			echo "<tr>";
			echo "<td>Acc Pri WU Sales</td>";
			for($i=0;$i<sizeof($catDatas);$i++){
				$j=0;
				while($j<($cntDatas[$i]+1)){
					if(!isset($totTSqty[$i][$j]) && $totTSqty[$i][$j] == ""){ 
						$totTSqty[$i][$j] = 0; 
					}
					
					$pid = $prodCode[$j][$i];
					$fdate = date("Y-m-d",strtotime($fromdate));
					$tsql = "select * from (select a.prod_id, sum(a.qty) tqty,sum(a.amount) tamt from tbl_orderbook a,tbl_orderbook_child b 
							where a.orderid=b.orderid and b.userid = '$userId' and b.distid = '$distid' and a.prod_id = '$pid' and b.order_status='PENDING' 
							and date_format( b.order_date, '%Y-%m-%d' ) = '$fdate' and b.user_status = 'WEB'
							and b.ordertype = 'ORDER BOOKING' 
							group by a.prod_id) subq order by prod_id";
					
					//echo "TSQL :".$tsql."<br>";
					$tresult = mysql_query($tsql,$conn) or die(mysql_error());
					if(mysql_num_rows($tresult)>0){
						$trow = mysql_fetch_assoc($tresult);
						$totTSqty[$i][$j] = $trow["tqty"];
					}else{
						$totTSqty[$i][$j] = 0;
					}
					if($totTSqty[$i][$j]=="0"){
						echo "<td>-</td>";
					}else{
						echo "<td>".$totTSqty[$i][$j]."</td>";
					}
					$grantTSAmount = $grantTSAmount + $trow["tamt"];
					$j++;
				}//while
			}//for
			echo "<td align='right'>".number_format($grantTSAmount,2,'.',',')."</td></tr>";
		
			/********************** Acc Primary Sales Edited Qty *******************************/
			echo "<tr class='srhighlight2'>";
			echo "<td>Acc Pri WU Sales Edited </td>";
			
			for($i=0;$i<sizeof($catDatas);$i++){
				$catcode = $catDatas[$i][1];
				$j=0;
				while($j<($cntDatas[$i]+1)){
					$pcode = $prodCode[$j][$i];
					$accSALESEPS[$i][$j]=0;
					$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.eqty) eqty, sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
												where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pcode' 
												and a.categoryid='$catcode' 
												and b.ordertype = 'ORDER BOOKING' and b.user_status = 'WEB' and b.order_status='PENDING' 
												and date_format(b.order_date,'%d-%m-%Y') = '$fromdate'
												group by a.prod_id) subq order by prod_id ";
					//echo "SQL :".$obsql."<br>";				
					$obresult = mysql_query($obsql,$conn) or die(mysql_error());
					if(mysql_num_rows($obresult)>0){
						$obrow = mysql_fetch_assoc($obresult);
						$accSALESEPS[$i][$j] = $obrow["eqty"];
					}
					if($accSALESEPS[$i][$j]==0 or $accSALESEPS[$i][$j]=="0"){
						echo "<td>-</td>";
					}else if($accSALESEPS[$i][$j]>0){
						echo "<td>+".$accSALESEPS[$i][$j]."</td>";
					}else{
						echo "<td>".$accSALESEPS[$i][$j]."</td>";
					}
					$j++;
				}
			}
			echo "<td align='right'>&nbsp;</td></tr>";
		
			$colcount = $catcnt+1;
			echo "<tr><td colspan='$colcount' align='left' class='dr'>&nbsp;</td></tr>";
			
			
			/************************************************end of Secondary Deliever and Cancel Report*********************************/	
		}//end of while statement
		echo "</table>";
	}else{
		echo "<table class='tbl1' cellspacing='0' cellpadding='4' width='100%'>";
		echo "<tr><td><b>No Data Found</b></td></tr>";
		echo "</table>";
	}
}



//**************************** USER DEFINED FUNCTIONS ******************************

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $user_name;
  global $fromdate;
  global $todate;
  global $queryString;
  global $repstatus;
  
   
  $sql = "select * from tbl_distributor  order by dist_id";
  


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
  global $user_name;
  global $fromdate;
  global $todate;
  global $queryString;
  global $repstatus;
  
 
  $sql = "SELECT COUNT(*) FROM tbl_distributor";
  
 // echo "<br>"."SQL1 :".$sql;
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} 


function getSalesmanNames($uid){
	global $conn;
	$usql = "select a.emp_dname empname from tbl_employee a,tbl_user b where b.Title = a.emp_id and b.user_id='$uid'";
	$ures = mysql_query($usql,$conn) or die(mysql_error());
	$urow = mysql_fetch_assoc($ures);
	return $urow["empname"];
}
/*function getAreaNames($acode){
	global $conn;
	$asql = "select a.area_desc1 areaname from tbl_area a where a.area_name='$acode'";
	$ares = mysql_query($asql,$conn) or die(mysql_error());
	$arow = mysql_fetch_assoc($ares);
	return $arow["areaname"];
}

function getRegionNames($rcode){
	global $conn;
	$rgsql = "select region_desc from tbl_region where region_name='$rcode'";
	$rgres = mysql_query($rgsql,$conn) or die(mysql_error());
	$rgrow = mysql_fetch_assoc($rgres);
	return $rgrow["region_desc"];
}*/

function callSalesmanPending($did,$uid,$fdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.user_status = '$ustatus' and b.order_status='PENDING' 
								and date_format(b.order_date,'%d-%m-%Y') = '$fdate'
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j] = $obrow["accqty"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callSalesmanReject($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	$fdate = date("d-m-Y",strtotime($fdate));
	$tdate = date("d-m-Y",strtotime($tdate));
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt,b.userstatus from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.userstatus = '$ustatus' and b.order_status<>'PENDING' and b.acc_rej_status='REJECTED' 
								and b.acc_rej_status <>'INVENTORY' and date_format(b.acc_rej_date,'%d-%m-%Y') >= '$fdate' 
								and date_format(b.acc_rej_date,'%d-%m-%Y') <= '$tdate' 
								group by a.prod_id) subq order by prod_id ";
			///echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j] = $obrow["accqty"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callSalesmanInventory($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	$fdate = date("d-m-Y",strtotime($fdate));
	$tdate = date("d-m-Y",strtotime($tdate));
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt,b.userstatus from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.userstatus = '$ustatus' and b.order_status<>'PENDING' and b.acc_rej_status<>'REJECTED' 
								and b.acc_rej_status ='INVENTORY' and date_format(b.acc_rej_date,'%d-%m-%Y') >= '$fdate' 
								and date_format(b.acc_rej_date,'%d-%m-%Y') <= '$tdate' 
								group by a.prod_id) subq order by prod_id ";
			///echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j] = $obrow["accqty"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

?>
			
	