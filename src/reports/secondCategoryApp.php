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
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondCategoryApp.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/secondCategoryApp.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondCategoryApp.php?a=reset')">
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
		echo "<tr class='hr'><td align='left' width='8%'>Date</td><td align='left' width='20%'>Distributor</td>";
		/******************************* Print categories  ****************************************/
		$cat_query = "select * from tbl_categories";
		$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
		if(mysql_num_rows($cat_result)>0){
			$i=0;
			$catcnt = 1;
			$categoryArray = array();
			while($cat_row = mysql_fetch_assoc($cat_result)){
				$catDatas[$i][0] = $cat_row["cat_name"];
				$catDatas[$i][1] = $cat_row["cat_code"];
						
				//$categoryArray[0][$i] =0;
				$categoryArray[$i] =0;
				/*$catArray = "P".$i;
				echo "<td>".$catArray.'-'.${$catArray}."</td>";
				$$catArray = array();
				$$catArray[0] = 0;
				echo "C :".$$catArray."<br>";*/
				echo "<td>P".$catcnt."</td>";
				$i++;
				$catcnt++;
			}//while
			echo "<td>Pc</td>";
			echo "<td>Cm</td>";
			echo "</tr>";	
		
			/***************************** Print Secondary Sales Qties based on date ************************************/
			//echo "Start From Date :".$fromdate."<br>";
			for($j=0;$j<=$days;$j++){
				
	 			if($distId <> "0" and $distId <> ""){
					$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name, region_code,area_code FROM tbl_assigntp 
						  		WHERE user_id = '$userId' and org_dist_id = '$distId' 
								and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
				}else{
					$dist_query = "select * from (select org_dist_id, concat(org_dist_code,'-',org_dist_name) org_dist_name,route_id,
								concat(route_code,'-',route_name) route_name,region_code,area_code FROM tbl_assigntp 
								WHERE user_id = '$userId' 
								and date_format(routeday,'%d-%m-%Y') = '$fromdate' group by org_dist_id) subq order by org_dist_id";
				}
				//echo "From Date:".$fromdate." =DSQL :".$dist_query."<br>";
				$dist_result = mysql_query($dist_query,$conn);
				$prev = "";
				$distributor = array();
				$grandSSamt = 0;
				
				if(mysql_num_rows($dist_result)>0){
					
					$d = 0;
					while($dist_row = mysql_fetch_assoc($dist_result)){
						$distid = $dist_row["org_dist_id"];
						$distname = $dist_row["org_dist_name"];
						
							
						
						/*************************************** to calculate secondary sales by productname **********************************/
						echo "<tr class='<?php echo $style ?>'>";
						if($prev != $distname){
							echo "<td>".$fromdate."</td>";
							$prev = $fromdate;
						}else{
							echo "<td>&nbsp;</td>";
						}
						echo "<td>".$distname."</td>";
						
						$outletsql = "select * from (select outletid from tbl_orderbook 
													where userid='$userId' and distid='$distid' and ucase(tstatus)='TRANSIT' 
													and ordertype = 'Sales' and date_format(odate,'%d-%m-%Y') = '$fromdate' 
													group by outletid) subq order by outletid ";
						//echo "OSQL :".$outletsql."<br>";
						$outletres = mysql_query($outletsql) or die("Error in SCA-Outlet :".mysql_error());
						$outletCount = 0;
						while($outletrow = mysql_fetch_assoc($outletres)){
							$oid = $outletrow["outletid"];
							$catsql =  "select count(*) catRecord from (select categoryid from tbl_orderbook 
													where userid='$userId' and distid='$distid' and outletid = '$oid' and ucase(tstatus)='TRANSIT' 
													and ordertype = 'Sales' and date_format(odate,'%d-%m-%Y') = '$fromdate' 
													group by categoryid) subq";
							$catres = mysql_query($catsql) or die("Error in SCA-Categroy :".mysql_error());
							$catrow = mysql_fetch_assoc($catres);
							$catvalue = $catrow["catRecord"];
							$m=1;
							/********* Category counting************/
							for($z=0;$z<sizeof($categoryArray);$z++){
								if($m == $catvalue){
									//$categoryArray[$m][$z] = $categoryArray[$m][$z] + $catvalue;
									$categoryArray[$z] = $categoryArray[$z] + $catvalue;
								}
								$m++;
							}//for loop for category counting
							$outletCount++;
						}//while loop for outlet query
						
						/********** Printing categroy array value *******************************/
						$m=0;
						$productivityCall=0;
						for($z=0;$z<sizeof($categoryArray);$z++){
							/*echo "<td>".$categoryArray[$m][$z]."</td>";*/
							echo "<td>".$categoryArray[$z]."</td>";
							$productivityCall = $productivityCall + $categoryArray[$z];  
							$categoryArray[$z]=0;
							$m++;
						}//end of printing categroy value
						echo "<td>".$productivityCall."</td>";
						echo "<td>".$outletCount."</td>";
						echo "</tr>";
					}//while loop for distributor query
				}//if
				$dateparts = explode("-",$fromdate);
				$d = mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
				$end_date = date("d-m-Y",strtotime("+1 days",$d));
						
				$fromdate = $end_date;
					
			}//for loop for days
			echo "<tr><td colspan='2'><b>Total</b></td>";
			for($i=0;$i<sizeof($categoryArray);$i++){
				
					echo "<td>&nbsp;</td>";
				
			}
			echo "<td>&nbsp;</td>";
			echo "<td>&nbsp;</td>";
			echo "</tr>";
		}//end of the category if statement
		echo "</table>";
	}//end of the user if statement
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