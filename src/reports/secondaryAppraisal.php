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
				<select name="cboUser" class="box" id="cboUser" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondaryAppraisal.php?userId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Salesman</option>
						<?php echo $userList; ?>
   				</select>
			</td>
         </tr>
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/secondaryAppraisal.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondaryAppraisal.php?a=reset')">
            </td>
        </tr>
        </table>
     </form>
     <?php
	 $str_fdate = $fromdate;
	 $str_tdate = $todate;
	 $qry2 = " and date_format( odate, '%e/%m/%Y' )  between date_format(str_to_date( '$fromdate', '%e-%m-%Y' ), '%e/%m/%Y' ) 
	 			and date_format(str_to_date( '$todate', '%e-%m-%Y' ), '%e/%m/%Y' )";
	 
	 
     if($userId <> "" and $userId <> "0"){
	 	$grantSSqty = array();
	 	$diff = abs(strtotime($todate) - strtotime($fromdate));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		$diffdays = dateDiff("-",$fromdate,$todate);
		//echo "Days :".$days."<br>";
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr><td><b>TOUR PLAN</b></tr>";
		echo "</table>";
		printMonthlyAppraisal($days,$userId,$str_fdate,$str_tdate);
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr><td><b>SALESMAN's SECONDARY SALES PERFORMANCE</b></tr>";
		echo "</table>";
		printSecondaryAppraisal($days,$userId,$str_fdate,$str_tdate);
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr><td><b>CATEGORY-WISE PERFORMANCE</b></tr>";
		echo "</table>";
		printSecondaryCategoryAppraisal($days,$userId,$str_fdate,$str_tdate);
		echo "<table>";
		echo "<tr><td><i>Monthly Appraisal of Mr.".getSalesman($userId)." on ".date("d-m-Y")."</i></td></tr>";
		echo "</table>";
	}//end if
	

}//select function	


/************************************ User Defined Function ************************************************/

function printSecondaryCategoryAppraisal($days,$userId,$sfdate,$stdate){
	global $conn;
	$fromdate = date('Y-m-d',strtotime($sfdate));
	$todate = date('Y-m-d',strtotime($stdate));
	
	/**************************** Print Heading -- Category with Product List****************************************************************************/
	echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
	echo "<tr class='hr'><td align='left' width='20%'>Distributor</td><td align='left' width='8%'>Date</td>";
	/******************************* Print categories  ****************************************/
	$cat_query = "select * from tbl_categories order by cat_code";
	$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
	if(mysql_num_rows($cat_result)>0){
		$i=0;
		$catcnt = 1;
		$categoryArray = array();
		while($cat_row = mysql_fetch_assoc($cat_result)){
			$catDatas[$i][0] = $cat_row["cat_name"];
			$catDatas[$i][1] = $cat_row["cat_code"];
						
			$categoryArray[$i] =0;
			echo "<td>P".$catcnt."</td>";
			$i++;
			$catcnt++;
		}//while
		echo "<td>Pc</td>";
		echo "<td>Cm</td>";
		echo "</tr>";	
		
		/***************************** Print Secondary Sales Qties based on date ************************************/
		//echo "Start From Date :".$fromdate."<br>";
		$grandSSamt = 0;
		$grantPC =0;
		$grantOC =0;
		$grantCat = array();
		$dist_query ="select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name,a.route_id,
						a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d
						WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id 
						and a.user_id = '$userId' and date_format(a.routeday,'%Y-%m-%d') >= '$fromdate' 
						and date_format(a.routeday,'%Y-%m-%d') <= '$todate' group by a.org_dist_id) subq order by org_dist_id";
		
			//echo "From Date:".$fromdate." =DSQL :".$dist_query."<br>";
		$dist_result = mysql_query($dist_query,$conn);
	
		$distributor = array();
				
		if(mysql_num_rows($dist_result)>0){
			$d = 0;
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				$fromdate = $sfdate;
				/*************************************** to calculate secondary sales by productname **********************************/
				
				$prev="";
				for($j=0;$j<=$days;$j++){
					echo "<tr class='<?php echo $style ?>'>";
					if($prev != $distname){
						echo "<td>".$distname."</td>";
						$prev = $distname;
					}else{
						echo "<td>&nbsp;</td>";
					}
					echo "<td>".date("d-m-Y",strtotime($fromdate))."</td>";
					$fromdate = date("Y-m-d",strtotime($fromdate));
					/*$outletsql = "select * from (select a.outletid from tbl_orderbook a,tbl_orderbook_child b
										where a.userid='$userId' and a.distid='$distid' and b.transit_status='TRANSIT' and b.transit_cancel_status<>'CANCEL' 
										and b.deliver_status <>'DONE' and b.ordertype = 'SALES' and date_format(b.order_date,'%Y-%m-%d') = '$fromdate' 
											group by a.outletid) subq order by outletid ";*/
					$outletsql = "select * from (select b.outletid from tbl_orderbook a,tbl_orderbook_child b
										where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and b.ordertype = 'SALES' 
										and date_format(b.order_date,'%Y-%m-%d') = '$fromdate' group by b.outletid) subq order by outletid ";
					//echo "OSQL :".$outletsql."<br>";
					$outletres = mysql_query($outletsql) or die("Error in SCA-Outlet :".mysql_error());
					$outletCount = 0;
					if(mysql_num_rows($outletres)>0){
					  while($outletrow = mysql_fetch_assoc($outletres)){
						$oid = $outletrow["outletid"];
						$catsql =  "select count(*) catRecord from (select a.categoryid from tbl_orderbook a,tbl_orderbook_child b
										where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and b.outletid='$oid' and b.ordertype = 'SALES' 
										and date_format(b.order_date,'%Y-%m-%d') = '$fromdate' 
										group by a.categoryid) subq ";
						//echo "Query :".$catsql."<br>";				
						$catres = mysql_query($catsql) or die("Error in SCA-Categroy :".mysql_error());
						$catrow = mysql_fetch_assoc($catres);
						$catvalue = $catrow["catRecord"];
						$m=1;
						/********* Category counting************/
						for($z=0;$z<sizeof($categoryArray);$z++){
							if($m == $catvalue){
								//$categoryArray[$m][$z] = $categoryArray[$m][$z] + $catvalue;
								//$categoryArray[$z] = $categoryArray[$z] + $catvalue;
								$categoryArray[$z] = $categoryArray[$z] + 1;
							}
							$m++;
						}//for loop for category counting
						$outletCount++;
					  }//while loop for outlet query
					}		
					/********** Printing categroy array value *******************************/
					$m=0;
					$productivityCall=0;
					for($z=0;$z<sizeof($categoryArray);$z++){
						/*echo "<td>".$categoryArray[$m][$z]."</td>";*/
						if(!isset($grantCat[$z]) && $grantCat[$z] == ""){
							$grantCat[$z] = 0;
						}
						echo "<td>";
						if ($categoryArray[$z]=="0"){
							echo  "-" ;
						}else{
							 echo $categoryArray[$z];
						}
						echo "</td>";
						$grantCat[$z] = $grantCat[$z] + $categoryArray[$z];
						$productivityCall = $productivityCall + $categoryArray[$z];  
						$categoryArray[$z]=0;
						$m++;
					}//end of printing categroy value
					echo "<td>".$productivityCall."</td>";
					echo "<td>".$outletCount."</td>";
					echo "</tr>";
					$grantPC = $grantPC + $productivityCall;
					$grantOC = $grantOC + $outletCount;
					$fromdate = date("d-m-Y",strtotime($fromdate));
					$dateparts = explode("-",$fromdate);
					$d = mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
					$end_date = date("d-m-Y",strtotime("+1 days",$d));
					$fromdate = $end_date;
				}//for loop for days
				echo "<tr class='srhighlight1'><td colspan='2'><b>Total</b></td>";
				for($i=0;$i<sizeof($categoryArray);$i++){
					echo "<td>".$grantCat[$i]."</td>";
					$grantCat[$i]="";
				}
				echo "<td>".$grantPC."</td>";
				echo "<td>".$grantOC."</td>";
				echo "</tr>";
				$grantPC=0;
				$grantOC=0;
				$fromdate = $sfdate;
			
			}//while loop for distributor query
		
			
		}//if for distributor
	}//end of the category if statement
	echo "</table>";
}

function printSecondaryAppraisal($days,$userId,$sfdate,$stdate){
	global $conn;
	$fromdate = date("Y-m-d",strtotime($sfdate));
	$todate = date("Y-m-d",strtotime($stdate));
	
	 
	 
    /**************************** Print Heading -- Category with Product List****************************************************************************/
	echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
	echo "<tr class='hr'><td align='left' width='15%' rowspan='2'>Distributor</td><td rowspan='2'>Date</td>";
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
			//echo "Cntr :".$i."===Size of Product :".($cntDatas[$i]+1)."<br>";
			while($j<($cntDatas[$i]+1)){
				echo "<td align='left'>".$prodDatas[$j][$i]."</td>";
				$j++;	
				$columnNos++;
			}//while
		}//for
		echo "</tr>";
	}//if
		
	/***************************** Print Secondary Sales Qties based on date ************************************/
	$grantSSamt=0;
    $dspdate1 = date("Y-m-d",$fromdate);
	$dspdate2 = date("Y-m-d",$todate);
	//echo "Start From Date :".$fromdate."<br>";
	$dist_query = "select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name,a.route_id,
						a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d
						WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id 
						and a.user_id = '$userId' and date_format(a.routeday,'%Y-%m-%d') >= '$fromdate' 
						and date_format(a.routeday,'%Y-%m-%d') <= '$todate' group by a.org_dist_id) subq order by org_dist_id";
	//echo $dist_query."<br>";		
	$dist_result = mysql_query($dist_query,$conn);
	$prev = "";
		
	if(mysql_num_rows($dist_result)>0){
		while($dist_row = mysql_fetch_assoc($dist_result)){
			$distid = $dist_row["org_dist_id"];
			$distname = $dist_row["org_dist_name"];
			$fromdate = $sfdate;
			$grantSSamt = 0;
			for($z=0;$z<=$days;$z++){
				echo "<tr class='<?php echo $style ?>'>";
				if($prev != $distname){
					$prev = $distname;
					echo "<td>".$distname."</td>";
				}else{
					echo "<td>&nbsp;</td>";
				}	
				
				/*************************************** to calculate secondary sales by productname **********************************/
				
				echo "<td>".date("d-m-Y",strtotime($fromdate))."</td>";
				$fromdate = date("Y-m-d",strtotime($fromdate));
				
				$totSSqty=0;
				$tamt=0;
				$totSSamt = 0;
				//$grantSSqty[$i][$j] = 0; 
				//$grantOutlet[$i][$j] =0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						$pid = $prodCode[$j][$i];
						
						$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt,count(b.outletid) outno 
									from tbl_orderbook a,tbl_orderbook_child b 
									where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pid' 
									and b.ordertype='SALES' and b.transit_status='TRANSIT' 
									and date_format(b.transit_date,'%Y-%m-%d') = '$fromdate' group by a.prod_id) subq order by prod_id ";
						
						//echo "Secondary Sales :".$accsql."<br>";
						$accresult = mysql_query($accsql,$conn) or die(mysql_error());
						if(mysql_num_rows($accresult)>0){
							$accrow = mysql_fetch_assoc($accresult);
							$totSSqty = $accrow["accqty"];
							$tamt = $accrow["accamt"];
							$outletno = $accrow["outno"];
						}else{
							$totSSqty = 0;
							$tamt = 0;
							$outletno=0;
							
						}
						$grantOutlet[$i][$j] = $grantOutlet[$i][$j] + $outletno;
						$grantSSqty[$i][$j] = $grantSSqty[$i][$j] + $totSSqty; 
						//$outletno = $accrow["outletno"];
						$totSSamt = $totSSamt + $tamt;
						if($totSSqty == "0"){
							echo "<td>-</td>";
						}else{
							echo "<td>".$totSSqty." (".$outletno.")"."</td>";
						}
						
						$j++;
					}//while
					
				}//for
				echo "<td>".number_format($totSSamt,2,'.',',')."</td>";
				echo "</tr>";
				$grantSSamt = $grantSSamt + $totSSamt;
				
				$fromdate = date("d-m-Y",strtotime($fromdate));
			 	$dateparts = explode("-",$fromdate);
        		$d = mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
        		$end_date = date("d-m-Y",strtotime("+1 days",$d));
                    
        		$fromdate = $end_date;
			}//for loop for days
			
			echo "<tr class='srhighlight1'><td colspan='2' align='left'><b>Total Qty</b></td>";
			for($i=0;$i<sizeof($catDatas);$i++){
				$j=0;
				while($j<($cntDatas[$i]+1)){
					echo "<td>".$grantSSqty[$i][$j];
					if($grantOutlet[$i][$j] !='0'){
						echo" (".$grantOutlet[$i][$j].")";
					}
					echo "</td>";
					$grantSSqty[$i][$j] = 0;
					$grantOutlet[$i][$j]=0;
					$j++;
				}
				
			}
			echo "<td>".number_format($grantSSamt,2,'.',',')."</td></tr>";
			
		}//while loop for all distributors
	}//if
	
	echo "</table>";
}


function printMonthlyAppraisal($days,$userId,$sfdate,$stdate)
{
	global $conn;
	$fromdate = date('Y-m-d',strtotime($sfdate));
	/**************************** Print Heading -- Category with Product List****************************************************************************/
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
		echo "<tr>";
		echo "<td width='70%' style='vertical-align:top'>";
		echo "<table width='100%' border='1'>";
		echo "<tr class='hr'><td align='left' width='15%'>Date</td><td width='35%'>T.Programme</td><td width='35%'>Actual Worked</td><td width='15%'>Remark</td></tr>";
	/******************************* Collect categories with respect to Product List ****************************************/
				
		
	/***************************** Print Secondary Sales Qties based on date ************************************/
		$countrec = 0;
		for($z=0;$z<=$days;$z++){
			//echo "Start From Date :".$fromdate."<br>";
			$fromdate = date("Y-m-d",strtotime($fromdate));
	 		if($distId <> "0" and $distId <> ""){
				$dist_query = "select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name, 
								a.route_id,a.comment,a.region_code,a.area_code,date_format(a.routeday,'%Y-%m-%d') routedate 
							   FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d 
						  	   WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id 
							   and a.user_id = '$userId' and a.org_dist_id = '$distId' and a.editedby in ('ADMIN','SALESMAN')
						  		and date_format(a.routeday,'%Y-%m-%d') = '$fromdate' group by a.org_dist_id) subq order by org_dist_id";
			}else{
				$dist_query = "select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name, 
								a.route_id,a.comment,a.region_code,a.area_code,date_format(a.routeday,'%Y-%m-%d') routedate 
							   FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d 
						  	   WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id 
							   and a.user_id = '$userId' and a.editedby in ('ADMIN','SALESMAN')
						  		and date_format(a.routeday,'%Y-%m-%d') = '$fromdate' group by a.org_dist_id) subq order by org_dist_id";
			}
			//echo "Dist Query :".$dist_query."<br>";
			$dist_result = mysql_query($dist_query,$conn);
			$prev = "";
			
			if(mysql_num_rows($dist_result) >0){
				while($dist_row = mysql_fetch_assoc($dist_result)){
					$distid = $dist_row["org_dist_id"];
					$distname = $dist_row["org_dist_name"];
					$olddistname = $dist_row["old_dist_name"];	
					$rdate = $dist_row["routedate"];
					/*************************************** to calculate secondary sales by productname **********************************/
					echo "<tr class='<?php echo $style ?>'>";
					if($prev != $rdate){
						$countrec = $countrec + 1;
						$prev = $rdate;
						echo "<td width='15%'>".date('d-m-Y',strtotime($rdate)).'('.date('D',strtotime($rdate)).')'."</td>";
					}else{
						echo "<td width='15%'>&nbsp;</td>";
					}
					echo "<td width='35%'>".$olddistname."</td>";
					echo "<td width='35%'>".$distname."</td>";
					echo "<td width='15%'>";
						if($dist_row["comment"]=="")
						{ 
							echo "-";
						}else{ 
							echo $dist_row["comment"];
						 }
					echo "</td>";
					echo "</tr>";
					
				}//while	
			}else{
				echo "<tr class='<?php echo $style ?>'>";
				echo "<td>".date('d-m-Y',strtotime($fromdate)).'('.date('D',strtotime($fromdate)).')'."</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "</tr>";
			}
			$fromdate= date("d-m-Y",strtotime($fromdate));
			$dateparts = explode("-",$fromdate);
            $d = mktime(0,0,0,$dateparts[1],$dateparts[0],$dateparts[2]);
            $end_date = date("d-m-Y",strtotime("+1 days",$d));
                    
            $fromdate = $end_date;
			//echo "End of Date :".$fromdate."<br>";
		}//for loop for days
		echo "</table></td>";
		
		$dspdate1 = date("Y-m-d",strtotime($sfdate));
		$dspdate2 = date("Y-m-d",strtotime($stdate));
		/************************************* Print Total Appraisal Days *******************************************************/
		echo "<td width='25%' style='vertical-align:top'>";
		echo "<table width='100%' border='1'>";
		echo "<tr class='hr'><td width='75%'>Distributor Covered</td><td width='25%'>Days</td></tr>";
		$dist_query = "select a.org_dist_id,b.dist_name org_dist_name from tbl_assigntp a,tbl_distributor b where a.org_dist_id=b.dist_id and a.user_id='$userId' 
								and date_format(a.routeday,'%Y-%m-%d') >= '$dspdate1' and a.editedby in ('ADMIN','SALESMAN') 
								and date_format(a.routeday,'%Y-%m-%d') <= '$dspdate2' group by a.org_dist_id";
			//echo "SQL :".$dist_query."<br>";
		$distresult = mysql_query($dist_query) or die ("Error in Monthly Appraisal:".mysql_error());
		
		if(mysql_num_rows($distresult)>0){
			while($distrow = mysql_fetch_assoc($distresult)){
				$distid = $distrow["org_dist_id"];
				$distname = $distrow["org_dist_name"];
					
				$dsql = "select count(*) drecord from (select * from tbl_assigntp where org_dist_id='$distid' and user_id='$userId'
								and date_format(routeday,'%Y-%m-%d') >= '$dspdate1' and date_format(routeday,'%Y-%m-%d') <= '$dspdate2' 
								and editedby in ('ADMIN','SALESMAN') group by routeday, org_dist_id)subq";
					//echo "SQL :".$dsql."<br>";
				$dresult = mysql_query($dsql) or die ("Error in Month Appraisal :".mysql_error());
				echo "<tr><td>".$distname."</td>";
				if(mysql_num_rows($dresult)>0){
					$drow = mysql_fetch_assoc($dresult);
					echo "<td>".$drow["drecord"]."</td>";
					
				}else{
					echo "<td>0</td>";
				}
				echo "</tr>";
			}//while
			echo "<tr><td colspan='2'>&nbsp;</td></tr>";
			echo "<tr><td align='left'>Total No of Days</td><td>".$countrec."</td></tr>";
		}//if
		else{
			echo "<tr><td colspan='2'>No Data Found</td></tr>";
		}
							
		echo "</table></td>";
		echo "</tr>";
		echo "</table>";
		
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

function getSalesman($uid){
	$sql = "SELECT b.user_id, a.emp_dname emp_name
				FROM tbl_employee a, tbl_user b where a.emp_id = b.Title and b.user_id = '$uid'";
	$result = mysql_query($sql) or die ("Error in Monthly Appraisal :".mysql_error());
	$row = mysql_fetch_assoc($result);
	return $row["emp_name"];
}
?>