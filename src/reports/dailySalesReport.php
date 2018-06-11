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
		$distributorList = buildDistributorAssignTP($distId,$userId,$fromdate);
	}
	?>
    
  
 	<form name="frmFilter" action="" method="post">
 	   <table border="1" cellspacing="0" cellpadding="4" width="100%" bgcolor="#EAEAEA">
       	  <tr>
          	<td align="left" width="15%"><b>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
            <td align="left"><input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dailySalesReport.php?fromdate='+this.value)>
             		<a href="javascript:show_calendar('document.frmFilter.fromdate', document.frmFilter.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
                   
            </td>
         </tr>
         
         <tr>
          <td align="left" width="15%"><b>Username :</b></td> 
          <td align="left">
                 <select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dailySalesReport.php?userId='+this.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
          </td>
        </tr>
        <tr>
          <td align="left" width="15%"><b>Distributor :</b></td> 
          <td align="left">
                 <select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dailySalesReport.php?distId='+this.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
   				</select>
                
 		  </td>
          
     	</tr>
        <tr>
        	<td colspan="2" align="left"> 
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/dailySalesReport.php?a=filter')">
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dailySalesReport.php?a=reset')">
            </td>
        </tr>
      </table>
      <br>
      
    </form>
    
    
   
<?php
     if($userId <> "" and $userId <> "0"){
		
		
		if($distId <> "0" and $distId <> ""){
			$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) dist_name,route_id,concat(route_code,'-',route_name) route_name,
			 			  region_code,area_code FROM tbl_assigntp 
						  WHERE user_id = '$userId' and org_dist_id = '$distId' 
						  and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
		}else{
			
			$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) dist_name,route_id,concat(route_code,'-',route_name) route_name, 		
						   region_code,area_code FROM tbl_assigntp 
							WHERE user_id = '$userId' and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
		}
		
		$dist_result = mysql_query($dist_query,$conn);
		while($dist_row = mysql_fetch_assoc($dist_result)){
			$distid = $dist_row["org_dist_id"];
			$distname = $dist_row["dist_name"];
			
			//************************************ Print Top Headings *****************************************************************
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
			echo "<tr><td width='15%' align='left'>SALESMAN :</td><td align='left' width='25%'>".getSalesmanNames($userId)."</td>";
			echo "<td width='10%' align='left'>REGION :</td><td align='left' width='20%'>".getRegionNames($dist_row["region_code"])."</td>";
			echo "<td width='10%' align='left'>DATE :</td><td align='left' width='20%'>".$fromdate."</td></tr>";
			//echo "<tr><td width='20%'>AREA :</td><td>".getAreaName($sid)."</td></tr>";
			echo "<tr><td widht='15%' align='left'>DISTRIBUTOR :</td><td width='25%' align='left'>".$dist_row["dist_name"] ."</td>";
			echo "<td width='10%' align='left'>AREA :</td><td width='20%' align='left' colspan='3'>".getAreaNames($dist_row["area_code"])."</td>";
			
			echo "</tr></table>";
			
			
			
			/**************************** Print Heading -- Category with Product List****************************************************************************/
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
			echo "<tr class='hr'><td align='left' rowspan='2' width='15%'>Particulars</td>";
			
			/******************************* Collect categories with respect to Product List ****************************************/
			$cat_query = "select * from tbl_categories";
			$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
			if(mysql_num_rows($cat_result)>0){
				$catDatas = array();
				$prodDatas = array();
				$cntDatas = array();
				$i=0;
				$catcnt = 1;
				while($cat_row = mysql_fetch_assoc($cat_result)){
					$catDatas[$i][0] = $cat_row["cat_name"];
					$catDatas[$i][1] = $cat_row["cat_code"];
					
					$prod_query = "select * from tbl_product where cat_code='".$catDatas[$i][1]."'";
					//echo "Prod Quyery :".$prod_query."<br>";
					$prod_result = mysql_query($prod_query);
					if(mysql_num_rows($prod_result)>0){
						$j=0;
						while($prod_row = mysql_fetch_assoc($prod_result)){
							$prodDatas[$j][$i] = $prod_row["prod_sname"];
							
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
			
			/**************************** To find Final Closing Stock *******************************************************************/
			$dspdate = date("Y-m-d",strtotime($fromdate));
			$prevqry = "select * from (select rec_date from tbl_cstock where user_id='$userId' and dist_id = '$distid' 
						and rec_date < '$dspdate' 
						group by rec_date) subq order by rec_date desc";
			
			$prevresult = mysql_query($prevqry) or die("Error in Stockist Sales ".mysql_error());
			if(mysql_num_rows($prevresult) > 0){
				
				$prevrow = mysql_fetch_assoc($prevresult);
				$rdate = $prevrow["rec_date"];
			}else{
				$rdate = "";
			}
			
			/***************************** To collect all information from closing stock table, order book table ************************/			
			for($i=0;$i<sizeof($catDatas);$i++){
				$j=0;
				while($j<($cntDatas[$i]+1)){
					$totPSqty[$i][$j] = 0; 
					$totOBqty[$i][$j] = 0;
					$totOBDqty[$i][$j] = 0;
					$totSSqty[$i][$j] = 0;
					$totSSDqty[$i][$j] = 0;
					$totSSCqty[$i][$j] = 0;
					$totTSTqty[$i][$j] = 0;
					$totASqty[$i][$j] = 0;
					$totCSqty[$i][$j] = 0;
					
					$pname = $prodDatas[$j][$i];
					
					//Closing Stock;
					$sql = "select * from (select prod_id,sum(prod_qty) pqty from tbl_cstock 
								where dist_id = '$distid' and user_id = '$userId' 
								and dist_id='$distid' and prod_name = '$pname' ".$qry1." group by prod_id) subq order by prod_id";
					
					$result = mysql_query($sql,$conn) or die("Error in CStock Table :".mysql_error());
					if(mysql_num_rows($result)>0){
						$row = mysql_fetch_assoc($result);
						$totPSqty[$i][$j] = $row["pqty"];
					}
					
					if($rdate != ""){
						$prvsql = "select * from (select prod_id,sum(prod_qty) pqty from tbl_cstock 
								where dist_id = '$distid' and user_id = '$userId' and prod_name = '$pname' 
								and date_format( rec_date, '%e/%m/%Y' ) = date_format($rdate,'%e/%m/%Y') group by prod_id) subq order by prod_id";
						$prvres = mysql_query($sql) or die("Error in Stockist Sales ".mysql_error());
						if(mysql_num_rows($prvres)>0){
							$prvrow = mysql_fetch_assoc($prvres);
							$totPCSqty[$i][$j] = $prvrow["pqty"];
						} 
						
					}else{
						$totPCSqty[$i][$j] = 0;
					}
					
					//Order Book (Transit, Delivery)
					$tsql = "select * from (select prod_id, sum(qty) tqty,sum(amount) tamt,tstatus,ordertype from tbl_orderbook 
							where userid = '$userId' and distid = '$distid' and prodname = '$pname' 
							and date_format( invdate, '%d-%m-%Y' )= '$fromdate' 
							group by ordertype,tstatus,prod_id) subq order by prod_id";
							
					//echo "TSQL :".$tsql."<br>";
					$tresult = mysql_query($tsql,$conn) or die(mysql_error());
					if(mysql_num_rows($tresult)>0){
						while($trow = mysql_fetch_assoc($tresult)){
							if($trow["ordertype"] == "Order Booking"){
								if(strtoupper($trow["tstatus"])=="TRANSIT"){
									$totOBqty[$i][$j] = $trow["tqty"];
								}
								
								if(strtoupper($trow["tstatus"]) == "DONE"){
									$totOBDqty[$i][$j] = $trow["tqty"];
								}
							}
							if(trim($trow["ordertype"]) == "Sales"){
								//echo "Sales Here :"."<br>";
								if(strtoupper($trow["tstatus"])=="TRANSIT"){
									$totSSqty[$i][$j] = $trow["tqty"];
									//echo "SS :".$totSSqty[$i][$j]."<br>";
								}
								if(strtoupper($trow["tstatus"]) == "DONE"){
									$totSSDqty[$i][$j] = $trow["tqty"];
								}
								if(strtoupper($trow["tstatus"]) == "CANCEL"){
									$totSSCqty[$i][$j]= $trow["tqty"];
								}
							}
							
						}//while
						$totTSTqty[$i][$j] = $totPSqty[$i][$j] + $totOBqty[$i][$j];//Total Stock
						$totASqty[$i][$j] = $totTSTqty[$i][$j] - $totSSqty[$i][$j]; //Available Stock
						$totCSqty[$i][$j] = $totASqty[$i][$j] + $totOBDqty[$i][$j]; //to calculate closing stock 
						$totStkSqty[$i][$j] =  $totPCSqty[$i][$j] - $totPSqty[$i][$j]; //prev-current
					}else{
						$totTSTqty[$i][$j] = $totPSqty[$i][$j] + $totOBqty[$i][$j];//Total Stock
						$totASqty[$i][$j] = $totTSTqty[$i][$j] - $totSSqty[$i][$j]; //Available Stock
						$totCSqty[$i][$j] = $totASqty[$i][$j] + $totOBDqty[$i][$j]; //to calculate closing stock 
						$totStkSqty[$i][$j] =  $totPCSqty[$i][$j] - $totPSqty[$i][$j]; //prev-current
					}
					$j++;
				}//while
			}//for
			
			
			
			echo "<tr><td colspan='$catcnt' align='left'><b>Salesman Primary Sales Performance</b></td></tr>";
			
			/*************************************** to calculate physical stock by productname **********************************/
			echo "<tr class='<?php echo $style ?>'>";
			echo "<td>Physical Stock</td>";
				printData($totPSqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			/*************************************** to calculate transit stock by productname **********************************/
			echo "<tr class='<?php echo $style ?>'>";
			echo "<td>Transit Stock</td>";
				printData($totOBqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/**********************************************to calculate Total Stock by product name****************************************/
			echo "<tr class='srhighlight1'>";
			echo "<td>Total Stock</td>";
				printData($totTSTqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/***********************************************to accumulate secondary sales by product name**********************************/
			echo "<tr>";
			echo "<td>Acc Sec Sales</td>";
				printData($totSSqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			
			
			/**************************************************to calculate available stock by product name**********************************/
			echo "<tr class='srhighlight1'>";
			echo "<td>Available Stock</td>";
				printData($totASqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/******************************************** to calculate accumulate Delivered Primary Sales by productname ****************************/
			echo "<tr>";
			echo "<td>Acc Pri Sales</td>";
				printData($totOBDqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			
			/**************************************************to calculate closing stock by product name**********************************/
			echo "<tr class='srhighlight1'>";
			echo "<td>Closing Stock</td>";
				printData($totCSqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
					
			
			echo "<tr><td colspan='$catcnt' align='left'><b>Distributor Primary Sales Performance</b></td></tr>";
			/************************************************to calculate Previous Closing Stock*********************************/
			echo "<tr>";
			echo "<td>Prev Closing Stock</td>";
			
			printData($totPCSqty,$catDatas,$cntDatas);
			
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/***************************************** Current Closing Stock) ****************************************/	
			echo "<tr>";
			echo "<td>Cur Closing Stock</td>";
			printData($totPSqty,$catDatas,$cntDatas);
			
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/***************************************** Stockist Sales by product name(Current - Previous Closing Stock) ****************************************/	
			echo "<tr class='srhighlight1'>";
			echo "<td>Distributor Sales</td>";
			printData ($totStkSqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			/************************************************to calculate Secondary  Cancel Report*********************************/	
			echo "<tr><td colspan='$catcnt' align='left'><b>Distributor Secondary Delievery Performance</b></td></tr>";
			echo "<tr><td align='left'>Cancelled Sec Sales</td>";
			printData($totSSCqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			/************************************************to calculate Secondary  Delivered Report*********************************/	
			echo "<tr><td align='left'>Delievered Sec Sales</td>";
			printData($totSSDqty,$catDatas,$cntDatas);
			echo "<td align='right'>&nbsp;</td></tr>";
			
			$colcount = $catcnt+1;
			echo "<tr><td colspan='$colcount' align='left' class='dr'>&nbsp;</td></tr>";
			
		}//end of while statement
		echo "</table>";
	}//if
}



//**************************** USER DEFINED FUNCTIONS ******************************

function printData($arrayData,$catData,$cntData){
	for($i=0;$i<sizeof($catData);$i++){
		$j=0;
		while($j<($cntData[$i]+1)){
			echo "<td>".$arrayData[$i][$j]."</td>";
			$j++;
		}//while
	}//for
}
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
	$usql = "select concat(a.emp_fname,'',a.emp_lname) empname from tbl_employee a,tbl_user b where b.Title = a.emp_id and b.user_id='$uid'";
	$ures = mysql_query($usql,$conn) or die(mysql_error());
	$urow = mysql_fetch_assoc($ures);
	return $urow["empname"];
}
function getAreaNames($acode){
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
}
?>
			
	