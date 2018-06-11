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
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondAppReport.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/secondAppReport.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondAppReport.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 
	 $qry2 = " and date_format( odate, '%e/%m/%Y' )  between date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) and date_format(str_to_date( '$todate', '%e-%m-%Y' ), '%e/%m/%Y' )";
	 
	 
     if($userId <> "" and $userId <> "0"){
	 	$grantSSqty = array();
	 	$diff = abs(strtotime($todate) - strtotime($fromdate));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		$diffdays = dateDiff("-",$fromdate,$todate);
		//echo "Days :".$days."<br>";
		
		
				/**************************** Print Heading -- Category with Product List****************************************************************************/
				echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
				echo "<tr class='hr'><td align='left' width='15%' rowspan='2'>Distributor</td><td rowspan='2'>Date</td>";
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
		for($z=0;$z<=$days;$z++){
			//echo "Start From Date :".$fromdate."<br>";
	 		if($distId <> "0" and $distId <> ""){
				$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name, region_code,area_code FROM tbl_assigntp 
						  		WHERE user_id = '$userId' and org_dist_id = '$distId' 
								and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
			}else{
				$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name,region_code,area_code FROM tbl_assigntp 
							WHERE user_id = '$userId' and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
			}
		
			$dist_result = mysql_query($dist_query,$conn);
			$prev = "";
			if(mysql_num_rows($dist_result)>0){
				$grantSSamt=0;
				while($dist_row = mysql_fetch_assoc($dist_result)){
					$distid = $dist_row["org_dist_id"];
					$distname = $dist_row["org_dist_name"];
				
					
				
					/*************************************** to calculate secondary sales by productname **********************************/
					echo "<tr class='<?php echo $style ?>'>";
					if($prev != $distname){
						$prev = $distname;
						echo "<td>".$distname."</td>";
					}else{
						echo "<td>&nbsp;</td>";
					}
					echo "<td>".$fromdate."</td>";
					$totSSamt=0;
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						while($j<($cntDatas[$i]+1)){
							if(!isset($totSSqty[$i][$j]) && $totSSqty[$i][$j] == ""){ 
								$totSSqty[$i][$j] = 0; 
							}
							$pname = $prodDatas[$j][$i];
							if($userId <> "" and $userId <> "0"){
								$accsql = "select * from (select prod_id, sum(qty) accqty,sum(amount) accamt from tbl_orderbook 
											where userid='$userId' and distid='$distid' and prodname = '$pname' and ucase(tstatus)='TRANSIT' 
											and ordertype = 'Sales' and date_format(odate,'%d-%m-%Y') = '$fromdate' group by prod_id) subq order by prod_id ";
							}else{
								$accsql = "select * from (select prod_id, sum(qty) accqty,sum(amount) accamt from tbl_orderbook 
											where userid='$userId' and distid='$distid' and prodname = '$pname' and ucase(tstatus)='TRANSIT' 
											and ordertype = 'Sales' and date_format(odate,'%d-%m-%Y') = '$fromdate' group by prod_id) subq order by prod_id ";
							}
							//echo "Secondary Sales :".$accsql."<br>";
							$accresult = mysql_query($accsql,$conn) or die(mysql_error());
							if(mysql_num_rows($accresult)>0){
								$accrow = mysql_fetch_assoc($accresult);
								$totSSqty[$i][$j] = $accrow["accqty"];
							}else{
								$totSSqty[$i][$j] = 0;
							}
							$grantSSqty[$i][$j] = $grantSSqty[$i][$j] + $totSSqty[$i][$j]; 
							$totSSamt = $totSSamt + $accrow["accamt"];
							echo "<td>".$totSSqty[$i][$j]."</td>";
							$j++;
						}//while
					}//for
					echo "<td>".$totSSamt."</td>";
					echo "</tr>";
					$grantSSamt = $grantSSamt + $totSSamt;
				}//while
				
			 }else{
			 
			 }
			 	
			 $dateparts = explode("-",$fromdate);
             $d = mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
             $end_date = date("d-m-Y",strtotime("+1 days",$d));
                    
             $fromdate = $end_date;
			 //echo "End of Date :".$fromdate."<br>";
		}//for loop for days
		echo "<tr><td colspan='2'>Total Qty</td>";
			for($i=0;$i<sizeof($catDatas);$i++){
				$j=0;
				while($j<($cntDatas[$i]+1)){
					echo "<td>".$grantSSqty[$i][$j]."</td>";
					$j++;
				}
			}
		echo "<td>".$grantSSamt."</td></tr>";
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