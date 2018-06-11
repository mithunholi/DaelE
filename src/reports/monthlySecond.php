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
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlySecond.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/monthlySecond.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlySecond.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 
	 $qry2 = " and date_format( odate, '%e/%m/%Y' )  between date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) and date_format(str_to_date( '$todate', '%e-%m-%Y' ), '%e/%m/%Y' )";
	 
	 
     if($userId <> "" and $userId <> "0"){
	 	$grantSSqty = array();
	 			/**************************** Print Heading -- Category with Product List****************************************************************************/
				echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
				echo "<tr class='hr'><td align='left' width='15%' rowspan='2'>DISTRIBUTOR</td><td align='left' width='15%' rowspan='2'>STATUS</td>";
				/******************************* Collect categories with respect to Product List ****************************************/
				$cat_query = "select * from tbl_categories order by cat_code";
				$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
				if(mysql_num_rows($cat_result)>0){
					$catDatas = array();
					$prodDatas = array();
					$prodCode = array();
					$cntDatas = array();
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
		
		$totSSqty = array();
		$totSSCqty = array();
		$totSSDqty = array();
		$grantSSqty = array();
		$grantqty= array();
		$grandSSamt = 0;
		$grantSSDamt = 0;
		$grantSSCamt = 0;
		$grantamt=0;
			
		if(mysql_num_rows($dist_result)>0){
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
					
				
				/*************************************** to calculate secondary sales by productname **********************************/
					echo "<tr>"; 
					if($prev != $distname){
						$prev = $distname;
						echo "<td rowspan='4'>".$distname."</td>";
					}else{
						echo "<td rowspan='4'>&nbsp;</td>";
					}
					
					/********************************************************** TRANSIT***********************************************/
					echo "<td>PENDING</td>";
					$totSSamt = 0;
					//$grantSSamt=0;
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						while($j<($cntDatas[$i]+1)){
							if(!isset($totSSqty[$i][$j]) && $totSSqty[$i][$j] == ""){ 
								$totSSqty[$i][$j] = 0;								 
							}
							if(!isset($grantSSqty[$i][$j]) && $grantSSqty[$i][$j] == ""){ 
								$grantSSqty[$i][$j] = 0;								 
							}
							$pid = $prodCode[$j][$i];
							
							$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pid' 
											and b.transit_status='TRANSIT' and b.transit_cancel_status ='FALSE' and b.deliver_status = 'FALSE'
											and b.ordertype = 'SALES' and date_format(b.transit_date,'%Y-%m-%d') >= '$fromdate' 
											and date_format(b.transit_date,'%Y-%m-%d') <='$todate' 
											group by a.prod_id) subq order by prod_id ";
							
							//echo "Secondary Sales :".$accsql."<br>";
							$accresult = mysql_query($accsql,$conn) or die(mysql_error());
							if(mysql_num_rows($accresult)>0){
								$accrow = mysql_fetch_assoc($accresult);
								$totSSqty[$i][$j] = $accrow["accqty"];
								$totSSamt = $totSSamt+$accrow["accamt"];
							}else{
								$totSSqty[$i][$j] = 0;
								
							}
							$grantSSqty[$i][$j] = $grantSSqty[$i][$j] + $totSSqty[$i][$j]; 
							if($totSSqty[$i][$j]=="0"){
								echo "<td>-</td>";
							}else{
								echo "<td>".$totSSqty[$i][$j]."</td>";
							}
							$j++;
						}//while
					}//for
					$grantSSamt = $grantSSamt + $totSSamt; 
					if($totSSamt == "0"){
						echo "<td>-</td>";
					}else{
						echo "<td>".number_format($totSSamt,2,'.',',')."</td>";
					}
					echo "</tr>";
					
					/********************************************************** DELIVERED ***********************************************/
					echo "<tr><td>DELIVERED</td>";
					$totSSDamt = 0;
					//$grantSSDamt = 0;
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						while($j<($cntDatas[$i]+1)){
							if(!isset($totSSDqty[$i][$j]) && $totSSDqty[$i][$j] == ""){ 
								$totSSDqty[$i][$j] = 0;								 
							}
							if(!isset($grantSSDqty[$i][$j]) && $grantSSDqty[$i][$j] == ""){ 
								$grantSSDqty[$i][$j] = 0;								 
							}
							$pid = $prodCode[$j][$i];
							
							$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pid' 
											and b.deliver_status='DONE' and b.ordertype = 'SALES' 
											and date_format(b.deliver_date,'%Y-%m-%d') >= '$fromdate' and date_format(b.deliver_date,'%Y-%m-%d') <='$todate' 
											group by a.prod_id) subq order by prod_id ";
							
							//echo "Secondary Sales :".$accsql."<br>";
							$accresult = mysql_query($accsql,$conn) or die(mysql_error());
							if(mysql_num_rows($accresult)>0){
								$accrow = mysql_fetch_assoc($accresult);
								$totSSDqty[$i][$j] = $accrow["accqty"];
								$totSSDamt = $totSSDamt+$accrow["accamt"];
							}else{
								$totSSDqty[$i][$j] = 0;
								
							}
							$grantSSDqty[$i][$j] = $grantSSDqty[$i][$j] + $totSSDqty[$i][$j]; 
							if($totSSDqty[$i][$j]=="0"){
								echo "<td>-</td>";
							}else{
								echo "<td>".$totSSDqty[$i][$j]."</td>";
							}
							$j++;
						}//while
					}//for
					$grantSSDamt = $grantSSDamt + $totSSDamt; 
					if($totSSDamt == "0"){
						echo "<td>-</td>";
					}else{
						echo "<td>".number_format($totSSDamt,2,'.',',')."</td>";
					}
					echo "</tr>";
					
					/********************************************************** CANCEL ***********************************************/
					echo "<tr><td>CANCEL</td>";
					$totSSCamt = 0;
					
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						while($j<($cntDatas[$i]+1)){
							if(!isset($totSSCqty[$i][$j]) && $totSSCqty[$i][$j] == ""){ 
								$totSSCqty[$i][$j] = 0;								 
							}
							if(!isset($grantSSCqty[$i][$j]) && $grantSSCqty[$i][$j] == ""){ 
								$grantSSCqty[$i][$j] = 0;								 
							}
							$pid = $prodCode[$j][$i];
							
							$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pid' 
											and b.transit_cancel_status='CANCEL' and b.ordertype = 'SALES' 
											and date_format(b.transit_cancel_date,'%Y-%m-%d') >= '$fromdate' 
											and date_format(b.transit_cancel_date,'%Y-%m-%d') <='$todate' 
											group by a.prod_id) subq order by prod_id ";
							
							//echo "Secondary Sales :".$accsql."<br>";
							$accresult = mysql_query($accsql,$conn) or die(mysql_error());
							if(mysql_num_rows($accresult)>0){
								$accrow = mysql_fetch_assoc($accresult);
								$totSSCqty[$i][$j] = $accrow["accqty"];
								$totSSCamt = $totSSCamt+$accrow["accamt"];
							}else{
								$totSSCqty[$i][$j] = 0;
								
							}
							$grantSSCqty[$i][$j] = $grantSSCqty[$i][$j] + $totSSCqty[$i][$j]; 
							if($totSSCqty[$i][$j]=="0"){
								echo "<td>-</td>";
							}else{
								echo "<td>".$totSSCqty[$i][$j]."</td>";
							}
							$j++;
						}//while
					}//for
					$grantSSCamt = $grantSSCamt + $totSSCamt; 
					if($totSSCamt=="0"){
						echo "<td>-</td>";
					}else{
						echo "<td>".number_format($totSSCamt,2,'.',',')."</td>";
					}
					echo "</tr>";
					
					/********************************************************** TOTAL ***********************************************/
					echo "<tr class='srhighlight1'><td>TOTAL</td>";
					$totalamt = 0;
					
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						while($j<($cntDatas[$i]+1)){
							if(!isset($totalqty[$i][$j]) && $totalqty[$i][$j] == ""){ 
								$totalqty[$i][$j] = 0;								 
							}
							if(!isset($grantqty[$i][$j]) && $grantqty[$i][$j] == ""){ 
								$grantqty[$i][$j] = 0;								 
							}
							$totalqty[$i][$j] = $totSSCqty[$i][$j] + $totSSDqty[$i][$j] + $totSSqty[$i][$j];
							
							$grantqty[$i][$j] = $grantqty[$i][$j] + $totalqty[$i][$j]; 
							
							echo "<td>".$totalqty[$i][$j]."</td>";
							$j++;
						}//while
					}//for
					$totalamt =  $totSSDamt+$totSSamt-$totSSCamt;
					$grantamt = $grantamt + $totalamt; 
					echo "<td>".number_format($totalamt,2,'.',',')."</td>";
					echo "</tr>";
					
			}//while
				
		}else{
			 
		}
		/************************************** Total Quantities (TRANSIT)******************************************/
		echo "<tr><td rowspan='4'>Grant Total - All Distributors</td>";
		echo "<td bgcolor='#C9C9C9'>PENDING</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				echo "<td bgcolor='#C9C9C9'>".$grantSSqty[$i][$j]."</td>";
				$j++;
			}
		}
		echo "<td bgcolor='#C9C9C9'>".number_format($grantSSamt,2,'.',',')."</td></tr>";
		
		/************************************** Total Quantities (DELIVERED)******************************************/
		echo "<tr><td bgcolor='#F6F6F6'>DELIVERED</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				echo "<td bgcolor='#F6F6F6'>".$grantSSDqty[$i][$j]."</td>";
				$j++;
			}
		}
		echo "<td bgcolor='#F6F6F6'>".number_format($grantSSDamt,2,'.',',')."</td></tr>";
		/************************************** Total Quantities (CANCELLED)******************************************/
		echo "<tr><td>CANCEL</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				echo "<td>".$grantSSCqty[$i][$j]."</td>";
				$j++;
			}
		}
		echo "<td>".number_format($grantSSCamt,2,'.',',')."</td></tr>";
		/************************************** Total Quantities******************************************/
		echo "<tr class='srhighlight1'><td>TOTAL</td>";
		for($i=0;$i<sizeof($catDatas);$i++){
			$j=0;
			while($j<($cntDatas[$i]+1)){
				echo "<td>".$grantqty[$i][$j]."</td>";
				$j++;
			}
		}
		echo "<td>".number_format($grantamt,2,'.',',')."</td></tr>";
		echo "</table>";
	}//if
}//select function	


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