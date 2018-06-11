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
	$_SESSION["userId"]="";
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
  if(isset($_GET["userId"])) $userId = $_GET["userId"];  
 
  
  //echo "Initial Values =".$distId."<br>"; 
  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
  if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
  if (!isset($todate) && isset($_SESSION["todate"])) $todate = $_SESSION["todate"];
  if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
  if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
  if (!isset($userId) && isset($_SESSION["userId"])) $userId = $_SESSION["userId"];
  
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
  if (isset($userId)) $_SESSION["userId"] = $userId;
 
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
	global $userId;
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
		$userId="";
	
		$_SESSION["fromdate"]="";
		$_SESSION["todate"]="";
		$_SESSION["distId"]="";
		$_SESSION["userId"]="";
		
  	}

  	$checkstr = "";
  	
	//echo "From Date :".$fromdate. " = To Date :".$todate." = User Id:".$userId."<br>";
	if($fromdate == "") {	
		$fromdate = date("d-m-Y");
	}

	if($todate == "") {	
		$todate = date("d-m-Y");
	}
	$fromdate = date("d-m-Y",strtotime($fromdate));
	$todate = date("d-m-Y",strtotime($todate));
  	$userList = buildUserAssignTP($userId,$fromdate,$todate);
	
	
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
         	<td align="left" width="15%"><b>Username :</b></td>
            <td align="left">
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimaryClose.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/monthlyPrimaryClose.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimaryClose.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 
	 //$qry2 = " and date_format( odate, '%e/%m/%Y' )  between date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) and date_format(str_to_date( '$todate', '%e-%m-%Y' ), '%e/%m/%Y' )";
	 
	 
    if($userId <> "" and $userId <> "0"){
	 	$grantSSqty = array();
	 	/**************************** Print Heading -- Category with Product List****************************************************************************/
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr class='hr'><td align='left' width='15%' rowspan='2'>Distributor</td><td align='left' rowspan='2'>Day</td><td rowspan='2'>Status</td>";
		/******************************* Collect categories with respect to Product List ****************************************/
		$cat_query = "select * from tbl_categories order by cat_code";
		$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
		if(mysql_num_rows($cat_result)>0){
			$catDatas = array();
			$prodDatas = array();
			$cntDatas = array();
			$prodCode = array();
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
						$cntDatas[$i] = $j;
						$j++;
					}		
				}
				$i++;
				$catcnt++;
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
				while($j<($cntDatas[$i]+1)){
					echo "<td align='left'>".$prodDatas[$j][$i]."</td>";
					$j++;	
					$columnNos++;
				}
			}//for
			echo "</tr>";
		}//if
		
		/***************************** Print Secondary Sales Qties based on date ************************************/
		$fromdate = date("Y-m-d",strtotime($fromdate));
		$todate = date("Y-m-d",strtotime($todate));
	 	if($distId <> "0" and $distId <> ""){
			$dist_query ="select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name,a.route_id,
						a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d
						WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id and a.org_dist_id='$distId'
						and a.user_id = '$userId' and date_format(a.routeday,'%Y-%m-%d') >= '$fromdate' 
						and date_format(a.routeday,'%Y-%m-%d') <= '$todate' group by a.org_dist_id) subq order by org_dist_id";
		
		}else{
			$dist_query ="select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name,a.route_id,
						a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d
						WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id 
						and a.user_id = '$userId' and date_format(a.routeday,'%Y-%m-%d') >= '$fromdate' 
						and date_format(a.routeday,'%Y-%m-%d') <= '$todate' group by a.org_dist_id) subq order by org_dist_id";
		}
		
		
		$dist_result = mysql_query($dist_query,$conn);
		$prev = "";
		if(mysql_num_rows($dist_result)>0){
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				
				/**************************** To find last closing stock date *********************************************/
				$dspdate1 = date("Y-m-d",strtotime($fromdate));
				$dspdate2 = date("Y-m-d",strtotime($todate));
				//if($dspdate1 == $dspdate2){
				$prevqry = "select * from (select b.rec_date from tbl_cstock a,tbl_cstock_child b where a.cstock_id=b.cstock_id and b.user_id='$userId' and 
								b.dist_id = '$distid' 
								and date_format(b.rec_date,'%Y-%m-%d') >= '$dspdate1' and date_format(b.rec_date,'%Y-%m-%d') <= '$dspdate2'
								group by b.rec_date) subq order by rec_date desc";
				/*}else{
				$prevqry = "select * from (select rec_date from tbl_cstock where user_id='$userId' and dist_id = '$distid' 
								and date_format(rec_date,'%Y-%m-%d') >= '$dspdate1' and date_format(rec_date,'%Y-%m-%d') < '$dspdate2'
								group by rec_date) subq order by rec_date desc";
				}*/
				//echo "SQL :".$prevqry."<br>";
				$prevresult = mysql_query($prevqry) or die("Error in Stockist Sales ".mysql_error());
				if(mysql_num_rows($prevresult) > 0){
					$prevrow = mysql_fetch_assoc($prevresult);
					$lastvisitdate = $prevrow["rec_date"];
					$lastvisitdate = date("Y-m-d",strtotime($lastvisitdate));
					//echo "STEP1: ".$lastvisitdate."<br>";
					$dateparts = explode("-",$lastvisitdate);
					//print_r($dateparts);
					
					$d = mktime(0,0,0,$dateparts[1],$dateparts[2],$dateparts[0]);
					$end_date = date("d-m-Y",strtotime("+1 days",$d));
					//echo "<br>STEP 2:".$end_date."<br>";
							
					$nextdate = $end_date;
				}else{
					$lastvisitdate = $todate;
					$nextdate ='0';
					//$rdate = $todate;
					//$ffdate = $todate;
				}
				
				
				
				
				echo "<tr class='<?php echo $style ?>'>";
				if($prev != $distname){
						$prev = $distname;
						echo "<td rowspan='5'>".$distname."</td>";
				}else{
						echo "<td rowspan='5'>&nbsp;</td>";
				}
				$priCSDataAmt=0;
				$transitDataAmt=0;
				$primaryInvoiceAmt =0;
				$totStockAmt=0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					$totSSamt = 0;
					while($j<($cntDatas[$i]+1)){
						$totSSqty[$i][$j] = 0; 
						$availstock[$i][$j] = 0; 
						$totalQty[$i][$j] = 0; 
						$primaryInvoicey[$i][$j] = 0; 
						$grantSSqty[$i][$j]=0;
						$pid = $prodCode[$j][$i];
						if($lastvisitdate != ""){
								$PrimaryOSData = primaryOpeningStock($pid,$userId,$distid,$lastvisitdate );
								$priCSData[$i][$j][0] = $PrimaryOSData[0];
								$priCSData[$i][$j][1] = $PrimaryOSData[1];
								$TransitData = primaryTransit($pid,$userId,$distid,$lastvisitdate );
								$transitData[$i][$j][0] = $TransitData[0];
								$transitData[$i][$j][1] = $TransitData[1];
						}else{
								$priCSData[$i][$j][0] =0;
								$priCSData[$i][$j][1] ="-";
								$transitData[$i][$j][0]=0;
								$transitData[$i][$j][1]=0;
						}
						$priCSDataAmt = $priCSDataAmt + $priCSData[$i][$j][1]; 
						$transitDataAmt=$transitDataAmt+$transitData[$i][$j][1];
						//$transitData[$i][$j] = $transitData[$i][$j] - $deliverData[$i][$j];
						$stockinhand[$i][$j][0] = $priCSData[$i][$j][0] + $transitData[$i][$j][0];
						//$availstock[$i][$j] = $stockinhand[$i][$j] - $secSalesData[$i][$j];
						if($nextdate != '0'){
							$primaryInvoiceData = primaryInvoiceData($pid,$userId,$distid,$nextdate,$todate);
							$primaryInvoice[$i][$j][0] = $primaryInvoiceData[0];
							$primaryInvoice[$i][$j][1] = $primaryInvoiceData[1];
						}else{
							$primaryInvoice[$i][$j][0] = 0;
							$primaryInvoice[$i][$j][1] = 0;
						}
							
						//$totalQty[$i][$j] = $availstock[$i][$j] + $primaryInvoice[$i][$j];
						$primaryInvoiceAmt = $primaryInvoiceAmt + $primaryInvoice[$i][$j][1];
						$totalQty[$i][$j] = $primaryInvoice[$i][$j][0] + $stockinhand[$i][$j][0]; 
						$grantSSqty[$i][$j] = $grantSSqty[$i][$j] + $totalQty[$i][$j]; 
						//echo "<td>".$totalQty."</td>";
						$j++;
					}//while
				}//for
				
				echo "<td rowspan='4'>Last Visit-".date("d-m-Y",strtotime($lastvisitdate))."</td>";
				echo "<td>Mfg.Date</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td>". $priCSData[$i][$j][1] ."</td>";
						
						$j++;
					}
				}
				echo "<td>&nbsp;</td>";
				echo "</tr>";
			
				
				echo "<tr><td>Physical Stock</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($priCSData[$i][$j][0] == "0"){
							echo "<td>-</td>";
						}else{
							echo "<td>". $priCSData[$i][$j][0] ."</td>";
						}
						$j++;
					}
				}
				echo "<td>&nbsp;</td>";
				echo "</tr>";
				/******************** Primary Sales ***********************/
				echo "<tr>";
				echo "<td>Transit</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($transitData[$i][$j][0]=="0"){
							echo "<td>-</td>";
						}else{
							echo "<td>".$transitData[$i][$j][0]."</td>";
						}
						$j++;
					}
				}
				echo "<td>".number_format($transitDataAmt,2,'.',',')."</td>";
				echo "</tr>";
				/******************** Total Stock ******************/
				echo "<tr class='srhighlight2'>";
				
				echo "<td>Total Stock</td>";

				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td>".$stockinhand[$i][$j][0]."</td>";
						$j++;
					}
				}
				//$grantSSamt = $grantSSamt + $totSSamt; 
				$totStockAmt = $priCSDataAmt + $transitDataAmt;
				echo "<td>".number_format($totStockAmt,2,'.',',')."</td>";
				echo "</tr>";
				
				/******************** Remaining Stock = Transit ******************/
				echo "<tr>";
			
				echo "<td>Remaining Days</td>";
				echo "<td>Transit</td>";

				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($primaryInvoice[$i][$j][0] == 0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$primaryInvoice[$i][$j][0]."</td>";
						}
						$j++;
					}
				}
				//$grantSSamt = $grantSSamt + $totSSamt; 
				echo "<td>".number_format($primaryInvoiceAmt,2,'.',',')."</td>";
				echo "</tr>";
				
				
				/*******************Closing Stock ********************************/
				echo "<tr><td>&nbsp;</td>";
				echo "<td class='srhighlight1'>Closing Stock</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td class='srhighlight1'>".$grantSSqty[$i][$j]."</td>";
						$j++;
					}
				}
				$grantAmt = $priCSDataAmt + $transitDataAmt+$primaryInvoiceAmt;
				echo "<td class='srhighlight1'>".number_format($grantAmt,2,'.',',')."</td></tr>";
			}//while
			
			echo "</table>";	
		}else{
			 
		}
		
	}//if
}//select function	

function primaryOpeningStock($prodname,$uId,$did,$rddate){
	global $conn;
	$rddate = date("Y-m-d",strtotime($rddate));
	$pcssql = "select * from (select a.prod_id, sum(a.prod_qty) pcsqty,rec_date,prod_mfd from tbl_cstock a,tbl_cstock_child b
											where a.cstock_id=b.cstock_id and b.user_id='$uId' and b.dist_id='$did' and a.prod_id = '$prodname' 
											and date_format(b.rec_date,'%Y-%m-%d') ='$rddate' 
											group by a.prod_id,b.rec_date) subq order by rec_date desc ";
	//echo "OS :".$pcssql."<br>";
	$pcsresult = mysql_query($pcssql,$conn) or die(mysql_error());
	if(mysql_num_rows($pcsresult)>0){
		$pcsrow = mysql_fetch_assoc($pcsresult);
		$data[0] = $pcsrow["pcsqty"];
		$data[1] = $pcsrow["prod_mfd"];
	}else{
		$data[0] = 0;
		$data[1] = "-";
	}
	return $data;
}

function primaryTransit($pname,$userId,$distid,$rdate){
	global $conn;
	$rdate = date("Y-m-d",strtotime($rdate));
	$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' 
											and a.prod_id = '$pname' and b.transit_status ='TRANSIT'  
											and b.ordertype = 'ORDER BOOKING' and date_format(b.transit_date,'%Y-%m-%d') <= '$rdate' 
											and (b.deliver_status ='FALSE' or (b.deliver_status='DONE' and date_format( b.deliver_date, '%Y-%m-%d' ) > '$rdate')) 
											group by a.prod_id) subq order by prod_id ";
	//echo "Transit :".$accsql."<br>";
	$accresult = mysql_query($accsql,$conn) or die(mysql_error());
	if(mysql_num_rows($accresult)>0){
		$accrow = mysql_fetch_assoc($accresult);
		$data[0] = $accrow["accqty"];
		$data[1] = $accrow["accamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;
}

function primaryDeliver($pname,$userId,$distid,$rdate){
	global $conn;
	$rdate = date("Y-m-d",strtotime($rdate));
	$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' 
											and a.prod_id = '$pname' and b.deliver_status ='DONE'  
											and b.ordertype = 'ORDER BOOKING' and date_format(b.deliver_date,'%Y-%m-%d') <= '$rdate' 
											group by a.prod_id) subq order by prod_id ";
	//echo "Transit :".$accsql."<br>";
	$accresult = mysql_query($accsql,$conn) or die(mysql_error());
	if(mysql_num_rows($accresult)>0){
		$accrow = mysql_fetch_assoc($accresult);
		$data[0] = $accrow["accqty"];
		$data[1] = $accrow["accamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;
}

function primaryInvoiceData($pname,$userId,$distid,$fdate,$tdate){
	global $conn;
	
	$fdate = date("Y-m-d",strtotime($fdate));
	$tdate = date("Y-m-d",strtotime($tdate));
	$invsql = "select * from (select a.prod_id, sum(a.qty) invqty,sum(a.amount) invamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id='$pname' 
											and b.transit_status='TRANSIT' and b.deliver_status ='FALSE'
											and b.ordertype = 'ORDER BOOKING' 
											and date_format(b.transit_date,'%Y-%m-%d') >= '$fdate' and date_format(b.transit_date,'%Y-%m-%d') <='$tdate' 
											group by a.prod_id) subq order by prod_id ";
	//echo "SQL 1:".$invsql."<br>";
	$invresult = mysql_query($invsql,$conn) or die(mysql_error());
	if(mysql_num_rows($invresult)>0){
		$invrow = mysql_fetch_assoc($invresult);
		$data[0] = $invrow["invqty"];
		$data[1] = $invrow["invamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;	
}

function secondarySales($pname,$userId,$distid,$rdate){
	global $conn;
	$rddate = date("d-m-Y",strtotime($rddate));
	$secsql = "select * from (select a.prod_id, sum(a.qty) secqty,sum(a.amount) secamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid = b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pname' 
											and b.transit_status='TRANSIT' and b.deliver_status = 'FALSE' and b.transit_cancel_status ='FALSE' 
											and b.ordertype = 'SALES' and date_format(b.transit_date,'%d-%m-%Y') = '$rddate' 
											group by a.prod_id) subq order by prod_id ";
	$secresult = mysql_query($secsql,$conn) or die(mysql_error());
	if(mysql_num_rows($secresult)>0){
		$secrow = mysql_fetch_assoc($secresult);
		$data[0] = $secrow["secqty"];
		$data[1] = $secrow["secamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;
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
?>