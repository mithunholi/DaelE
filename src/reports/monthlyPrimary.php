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
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimary.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/monthlyPrimary.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimary.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 
	 $qry2 = " and date_format( odate, '%e/%m/%Y' )  between date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) and date_format(str_to_date( '$todate', '%e-%m-%Y' ), '%e/%m/%Y' )";
	 
	 //echo "Query :".$qry2;
     if($userId <> "" and $userId <> "0"){
	 	$grantSSqty = array();
	 	
		/**************************** Print Heading -- Category with Product List****************************************************************************/
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr class='hr'><td align='left' width='15%' rowspan='2'>Distributor</td><td align='left' width='15%' rowspan='2'>STATUS</td>";
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
						
				$prod_query = "select * from tbl_product where cat_code='".$catDatas[$i][1]."' order by cat_code";
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
				$catcode = $catDatas[$i][1];
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
		}//if
		
		
		/***************************** Print Secondary Sales Qties based on date ************************************/
		//echo "Start From Date :".$fromdate."<br>";
		$fromdate = date("Y-m-d",strtotime($fromdate));
		$todate = date("Y-m-d",strtotime($todate));
	 	if($distId <> "0" and $distId <> ""){
			$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name, region_code,area_code FROM tbl_assigntp 
						  		WHERE user_id = '$userId' and org_dist_id = '$distId' 
								and date_format(routeday,'%Y-%m-%d') >= '$fromdate' and date_format(routeday,'%Y-%m-%d') <='$todate' 
								group by org_dist_id) subq order by org_dist_id";
		}else{
			$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name,region_code,area_code FROM tbl_assigntp 
								WHERE user_id = '$userId' 
								and date_format(routeday,'%Y-%m-%d') >= '$fromdate' and date_format(routeday,'%Y-%m-%d') <='$todate'
								 group by org_dist_id) subq order by org_dist_id";
		}
		//echo "Dist Query :".$dist_query."<br>";
		$dist_result = mysql_query($dist_query,$conn);
		
		
		if(mysql_num_rows($dist_result)>0){
			$prev = "";
			$totSALESPENDqty= array();
			$totSALESREJECTqty=array();
			$totSALESINVENTqty=array();
			$totWEBPENDqty=array();
			$totWEBREJECTqty=array();
			$totWEBINVENTqty=array();
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				$grantSSamt=0;
				$grantSSDamt=0;
				$grantPENDamt = 0;
				$grantINVamt = 0;
				/*************************************** to calculate Primary Sales(OrderBook PENDING) by productname **********************************/
				$totPENDamt = 0;
				
				$totSALESPENDqty = callSalesmanPending($distid,$userId,$fromdate,$todate,"SALESMAN","PENDING",$catDatas,$cntDatas,$prodCode);
				$totSALESPENDEDITqty = callSalesmanPendingEdit($distid,$userId,$fromdate,$todate,"SALESMAN","PENDING",$catDatas,$cntDatas,$prodCode);
				$totSALESREJECTqty = callSalesmanReject($distid,$userId,$fromdate,$todate,"SALESMAN","REJECTED",$catDatas,$cntDatas,$prodCode);
				$totSALESINVENTqty = callSalesmanInventory($distid,$userId,$fromdate,$todate,"SALESMAN","INVENTORY",$catDatas,$cntDatas,$prodCode);
				$totWEBPENDqty = callSalesmanPending($distid,$userId,$fromdate,$todate,"WEB","PENDING",$catDatas,$cntDatas,$prodCode);
				$totWEBPENDEDITqty=callSalesmanPendingEdit($distid,$userId,$fromdate,$todate,"WEB","PENDING",$catDatas,$cntDatas,$prodCode);
				$totWEBREJECTqty = callSalesmanReject($distid,$userId,$fromdate,$todate,"WEB","REJECTED",$catDatas,$cntDatas,$prodCode);
				$totWEBINVENTqty = callSalesmanInventory($distid,$userId,$fromdate,$todate,"WEB","INVENTORY",$catDatas,$cntDatas,$prodCode);
				/*************************************** Print Collected Information (Pending,Accepted,Rejected, Acc.Primary Sales)***************/
					
				echo "<tr>";
				if($prev != $distname){
					$prev = $distname;
					echo "<td rowspan='14'>".$distname."</td>";
				}else{
					echo "<td rowspan='14'>&nbsp;</td>";
				}
				/************************************Accumulated Primary Sales FROM SALESMAN***********************************/
				
				echo "<td>AccPriSale By SM</td>";
				$totAccPS_SM = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						$accSALESPS[$i][$j][0] = $totSALESPENDqty[$i][$j][0] + $totSALESREJECTqty[$i][$j][0] + $totSALESINVENTqty[$i][$j][0];
						if($accSALESPS[$i][$j][0]==0 or $accSALESPS[$i][$j][0]=="0"){
							echo "<td>-</td>";
						}else{
							echo "<td>".$accSALESPS[$i][$j][0]."</td>";
						}
						$totAccPS_SM = $totAccPS_SM + $totSALESPENDqty[$i][$j][1] + $totSALESREJECTqty[$i][$j][1] + $totSALESINVENTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totAccPS_SM,2,'.',',')."</td>";
				echo "</tr>";
				
				//Edited Qty
				$totSPEamt = 0;
				echo "<tr class='srhighlight2'>";
				echo "<td>Edited</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totSALESPENDEDITqty[$i][$j][0]==0 or $totSALESPENDEDITqty[$i][$j][0]=="0"){
							echo "<td>-</td>";
						}else if($totSALESPENDEDITqty[$i][$j][0]>0){
							echo "<td>+".$totSALESPENDEDITqty[$i][$j][0]."</td>";
						}else{
							echo "<td>".$totSALESPENDEDITqty[$i][$j][0]."</td>";
						}
						$totSPEamt = $totSPEamt + $totSALESPENDEDITqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totSPEamt,2,'.',',')."</td>";
				echo "</tr>";
							
				//Pending
				echo "<tr class='srhighlight2'>";
				echo "<td>PENDING</td>";
				$totSPamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totSALESPENDqty[$i][$j][0]=="0" or $totSALESPENDqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totSALESPENDqty[$i][$j][0]."</td>";
						}
						$totSPamt = $totSPamt + $totSALESPENDqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totSPamt,2,'.',',')."</td>";
				echo "</tr>";
					
				//Accepted
				echo "<tr class='srhighlight2'>";
				echo "<td>ACCEPTED</td>";
				$totSIamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totSALESINVENTqty[$i][$j][0]=="0" or $totSALESINVENTqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totSALESINVENTqty[$i][$j][0]."</td>";
						}
						$totSIamt = $totSIamt + $totSALESINVENTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totSIamt,2,'.',',')."</td>";
				echo "</tr>";	
					
				//Reject
				echo "<tr class='srhighlight2'>";
				echo "<td>REJECTED</td>";
				$totSRamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totSALESREJECTqty[$i][$j][0]=="0" or $totSALESREJECTqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totSALESREJECTqty[$i][$j][0]."</td>";
						}
						$totSRamt = $totSRamt + $totSALESREJECTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totSRamt,2,'.',',')."</td>";	
				echo "</tr>";
					
				/************************************Accumulated Primary Sales FROM WEB USER***********************************/
				echo "<tr>";
				echo "<td>AccPriSale By WU</td>";
				$totWPS = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						$accWEBPS[$i][$j][0] = $totWEBPENDqty[$i][$j][0] + $totWEBREJECTqty[$i][$j][0] + $totWEBINVENTqty[$i][$j][0];
						
						if($accWEBPS[$i][$j][0]=="0" or $accWEBPS[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$accWEBPS[$i][$j][0]."</td>";
						}
						$totWPS = $totWPS + $totWEBPENDqty[$i][$j][1] + $totWEBREJECTqty[$i][$j][1] + $totWEBINVENTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totWPS,2,'.',',')."</td>";
				echo "</tr>";
					
				//Edited
				echo "<tr class='srhighlight2'>";
				echo "<td>Edited</td>";
				$totWPEamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totWEBPENDEDITqty[$i][$j][0]==0 or $totWEBPENDEDITqty[$i][$j][0]=="0"){
							echo "<td>-</td>";
						}else if($totWEBPENDEDITqty[$i][$j][0]>0){
							echo "<td>+".$totWEBPENDEDITqty[$i][$j][0]."</td>";
						}else{
							echo "<td>".$totWEBPENDEDITqty[$i][$j][0]."</td>";
						}
						$totWPEamt = $totWPEamt + $totWEBPENDEDITqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totWPEamt,2,'.',',')."</td>";
				echo "</tr>";
					
				//Pending
				echo "<tr class='srhighlight2'>";
				echo "<td>PENDING</td>";
				$totWPamt=0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totWEBPENDqty[$i][$j][0]=="0" or $totWEBPENDqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totWEBPENDqty[$i][$j][0]."</td>";
						}
						$totWPamt = $totWPamt + $totWEBPENDqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totWPamt,2,'.',',')."</td>";	
				echo "</tr>";
					
				//Accepted
				echo "<tr class='srhighlight2'>";
				echo "<td>ACCEPTED</td>";
				$totWIamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totWEBINVENTqty[$i][$j][0]=="0" or $totWEBINVENTqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totWEBINVENTqty[$i][$j][0]."</td>";
						}
						$totWIamt = $totWIamt + $totWEBINVENTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totWIamt,2,'.',',')."</td>";	
				echo "</tr>";
				
				//Reject
				echo "<tr class='srhighlight2'>";
				echo "<td>REJECTED</td>";
				$totWRamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totWEBREJECTqty[$i][$j][0]=="0" or $totWEBREJECTqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totWEBREJECTqty[$i][$j][0]."</td>";
						}
						$totWRamt = $totWRamt + $totWEBREJECTqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totWRamt,2,'.',',')."</td>";		
				echo "</tr>";
					
				/*************************************** to calculate Primary Sales(OrderBook SANDBOX) by productname **********************************/
				$totINVqty = callSalesmanSandbox($distid,$userId,$fromdate,$todate,"INVENTORY",$catDatas,$cntDatas,$prodCode);
				echo "<tr class='<?php echo $style ?>'>";
				$totINVamt = 0;
					
				echo "<td>SANDBOX</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totINVqty[$i][$j][0]=="0" or $totINVqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totINVqty[$i][$j][0]."</td>";
						}
						$totINVamt = $totINVamt + $totINVqty[$i][$j][1];
						$j++;
					}//while
				}//for
				echo "<td>".number_format($totINVamt,2,'.',',')."</td>";		
				echo "</tr>";
						
				/*************************************** to calculate Primary Sales(TRANSIT, DELIVERED) by productname **********************************/
				$totTRANSITqty=array();
				$totDONEqty=array();
				$totTRANSITqty = callPrimaryOrderTransit($distid,$userId,$fromdate,$todate,"TRANSIT",$catDatas,$cntDatas,$prodCode);
				$totDONEqty = callPrimaryOrderDeliver($distid,$userId,$fromdate,$todate,"DONE",$catDatas,$cntDatas,$prodCode);
					
				/*********************** to print Primary Sales(TRANSIT) by productname **********************************/
				echo "<tr class='<?php echo $style ?>'>";
				echo "<td>TRANSIT</td>";
				$totTRANSITamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totTRANSITqty[$i][$j][0]=="0" or $totTRANSITqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totTRANSITqty[$i][$j][0]."</td>";
						}
						$totTRANSITamt = $totTRANSITamt + $totTRANSITqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totTRANSITamt,2,'.',',')."</td>";			
				echo "</tr>";
					
				/********************************to calculate Primary Sales(DELIVERED) by productname **********************************/
					
				echo "<tr><td>DELIVERED</td>";
				$totDONEamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($totDONEqty[$i][$j][0]=="0" or $totDONEqty[$i][$j][0]==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totDONEqty[$i][$j][0]."</td>";
						}
						$totDONEamt = $totDONEamt + $totDONEqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totDONEamt,2,'.',',')."</td>";				
				echo "</tr>";
					
				/****************************** to calculate Total Qty (Sandbox + Transit + Delivered) *********************************/
				echo "<tr class='srhighlight1'><td>TOTAL</td>";
				$totamt = 0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						$totqty = $totDONEqty[$i][$j][0] + $totTRANSITqty[$i][$j][0] + $totINVqty[$i][$j][0];
						if($totqty=="0" or $totqty==0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totqty."</td>";
						}
						$totamt = $totamt + $totDONEqty[$i][$j][1] + $totTRANSITqty[$i][$j][1] + $totINVqty[$i][$j][1];
						$j++;
					}
				}
				echo "<td>".number_format($totamt,2,'.',',')."</td>";					
				echo "</tr>";
			}//while
				
		}else{
			 
		}
		/********************************************* Total Qty ************************************************/
		//Pending OB
		/*echo "<tr><td rowspan='5'>Grant Total - All Distributors</td>";
		echo "<td>PENDING</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				if($grantPENDqty[$i][$j]=="0"){
					echo "<td>-</td>";
				}else{
					echo "<td>".$grantPENDqty[$i][$j]."</td>";
				}
				$j++;
			}
		}
		if($grantPENDamt == "0"){
			echo "<td>-</td>";
		}else{
			echo "<td>".$grantPENDamt."</td>";
		}
		echo "</tr>";
		
		//Sandbox
		echo "<tr><td>SANDBOX</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				if($grantINVqty[$i][$j]=="0"){
					echo "<td>-</td>";
				}else{
					echo "<td>".$grantINVqty[$i][$j]."</td>";
				}
				$j++;
			}
		}
		if($grantINVamt == "0"){
			echo "<td>-</td>";
		}else{
			echo "<td>".$grantINVamt."</td>";
		}
		echo "</tr>";
		
		//Transit
		echo "<tr><td>TRANSIT</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				if($grantSSqty[$i][$j]=="0"){
					echo "<td>-</td>";
				}else{
					echo "<td>".$grantSSqty[$i][$j]."</td>";
				}
				$j++;
			}
		}
		if($grantSSamt=="0"){
			echo "<td>-</td>";
		}else{
			echo "<td>".$grantSSamt."</td>";
		}
		echo "</tr>";
		
		//Deliver
		echo "<tr><td>DELIVERED</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				if($grantSSDqty[$i][$j]=="0"){
					echo "<td>-</td>";
				}else{
					echo "<td>".$grantSSDqty[$i][$j]."</td>";
				}
				$j++;
			}
		}
		if($grantSSDamt == "0"){
			echo "<td>-</td>";
		}else{
			echo "<td>".$grantSSDamt."</td>";
		}
		echo "</tr>";
		//Total
		echo "<tr class='srhighlight1'><td>TOTAL</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				if($grantqty[$i][$j]=="0"){
					echo "<td>-</td>";
				}else{
					echo "<td>".$grantqty[$i][$j]."</td>";
				}
				$j++;
			}
		}
		if($grantamt == "0"){
			echo "<td>-</td>";
		}else{
			//echo "<td>".$grantamt."</td>";
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";*/
		echo "</table>";
	}//if
}//select function	

function callSalesmanPending($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.user_status = '$ustatus' and b.order_status='PENDING' 
								and b.acc_rej_status = 'FALSE'  
								and date_format(b.order_date,'%Y-%m-%d') >= '$fdate' and date_format(b.order_date,'%Y-%m-%d') <='$tdate' 
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j][0] = $obrow["accqty"];
				$totalqty[$i][$j][1] = $obrow["accamt"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callSalesmanPendingEdit($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.eqty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.user_status = '$ustatus' and b.order_status='PENDING' 
								and a.eqty != 0  
								and date_format(b.order_date,'%Y-%m-%d') >= '$fdate' and date_format(b.order_date,'%Y-%m-%d') <='$tdate' 
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j][0] = $obrow["accqty"];
				$totalqty[$i][$j][1] = $obrow["accamt"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}



function callSalesmanReject($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0;
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.user_status = '$ustatus' and b.acc_rej_status='REJECTED' 
								and date_format(b.acc_rej_date,'%Y-%m-%d') >= '$fdate' and date_format(b.acc_rej_date,'%Y-%m-%d') <='$tdate' 
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j][0] = $obrow["accqty"];
				$totalqty[$i][$j][1] = $obrow["accamt"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callSalesmanInventory($did,$uid,$fdate,$tdate,$ustatus,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.user_status = '$ustatus' and b.acc_rej_status='INVENTORY'  
								and date_format(b.acc_rej_date,'%Y-%m-%d') >='$fdate' and date_format(b.acc_rej_date,'%Y-%m-%d') <='$tdate' 
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				$totalqty[$i][$j][0] = $obrow["accqty"];
				$totalqty[$i][$j][1] = $obrow["accamt"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callSalesmanSandbox($did,$uid,$fdate,$tdate,$dstatus,$catData,$cntData,$prodCodes){
	global $conn;
	$totalqty = array();
	
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0;
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$obsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
								where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
								and b.ordertype = 'ORDER BOOKING' and b.acc_rej_status='INVENTORY' and b.transit_status = 'FALSE'  
								and date_format(b.acc_rej_date,'%Y-%m-%d') >='$fdate' and date_format(b.acc_rej_date,'%Y-%m-%d')<='$tdate' 
								group by a.prod_id) subq order by prod_id ";
			//echo "SQL :".$obsql."<br>";				
			$obresult = mysql_query($obsql,$conn) or die(mysql_error());
			if(mysql_num_rows($obresult)>0){
				$obrow = mysql_fetch_assoc($obresult);
				//$totalqty[$i][$j] = $obrow["accqty"];
				$totalqty[$i][$j][0] = $obrow["accqty"]; 
				$totalqty[$i][$j][1] = $obrow["accamt"];
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}


function callPrimaryOrderTransit($did,$uid,$fdate,$tdate,$ttstatus,$catData,$cntData,$prodCodes)
{
	global $conn;
	$totalqty = array();
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0;
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
											where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
											and b.ordertype = 'ORDER BOOKING' and b.transit_status='TRANSIT' and b.deliver_status = 'FALSE'
											and date_format(b.transit_date,'%Y-%m-%d') >= '$fdate' and date_format(b.transit_date,'%Y-%m-%d') <='$tdate' 
											group by a.prod_id) subq order by prod_id ";
							
			
			$accresult = mysql_query($accsql,$conn) or die(mysql_error());
			if(mysql_num_rows($accresult)>0){
				$accrow = mysql_fetch_assoc($accresult);
				$totalqty[$i][$j][0] = $accrow["accqty"];
				$totalqty[$i][$j][1] = $accrow["accamt"];
				//echo "Product Code :".$pcode."=Category ID:".$catcode."=Qty :".$totalqty[$i][$j]."<br>";
			}
			$j++;
		}//while
	}//for
	return $totalqty;
}

function callPrimaryOrderDeliver($did,$uid,$fdate,$tdate,$ttstatus,$catData,$cntData,$prodCodes)
{
	global $conn;
	$totalqty = array();
	for($i=0;$i<sizeof($catData);$i++){
		$catcode = $catData[$i][1];
		$j=0;
		while($j<($cntData[$i]+1)){
			$totalqty[$i][$j][0] = 0; 
			$totalqty[$i][$j][1] = 0; 
			//$pname = $prodDatas[$j][$i];
			$pcode = $prodCodes[$j][$i];
			$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
											where a.orderid=b.orderid and a.userid='$uid' and a.distid='$did' and a.prod_id = '$pcode' and a.categoryid='$catcode' 
											and b.ordertype = 'ORDER BOOKING' and b.deliver_status = 'DONE'
											and date_format(b.deliver_date,'%Y-%m-%d') >= '$fdate' and date_format(b.deliver_date,'%Y-%m-%d') <='$tdate' 
											group by a.prod_id) subq order by prod_id ";
							
			
			$accresult = mysql_query($accsql,$conn) or die(mysql_error());
			if(mysql_num_rows($accresult)>0){
				$accrow = mysql_fetch_assoc($accresult);
				$totalqty[$i][$j][0] = $accrow["accqty"];
				$totalqty[$i][$j][1] = $accrow["accamt"];
				//echo "Product Code :".$pcode."=Category ID:".$catcode."=Qty :".$totalqty[$i][$j]."<br>";
			}
			$j++;
		}//while
	}//for
	return $totalqty;
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