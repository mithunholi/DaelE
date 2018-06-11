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
	$distId="";
  } 
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
  
  $user_name=$_SESSION['User_ID'];
  
  if(isset($_GET["fromdate"])) $fromdate = $_GET["fromdate"];
  if(isset($_GET["todate"])) $todate = $_GET["todate"];
   if(isset($_GET["distId"])) $distId = $_GET["distId"];  
  //if(isset($_GET["userId"])) $userId = $_GET["userId"];  
 
  
  //echo "Initial Values =".$distId."<br>"; 
  
  if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
  if (!isset($todate) && isset($_SESSION["todate"])) $todate = $_SESSION["todate"];
  
  if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
  //if (!isset($userId) && isset($_SESSION["userId"])) $userId = $_SESSION["userId"];
  
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
  
  

  $a = @$_GET["a"];
 
  switch ($a) {
   
	case "filter":
	  select();
	  break;
	
    default:
      select();
      break;
  }

 
  if (isset($fromdate)) $_SESSION["fromdate"] = $fromdate;
  if (isset($todate)) $_SESSION["todate"] = $todate;
 
  if (isset($distId)) $_SESSION["distId"] = $distId;
  //if (isset($userId)) $_SESSION["userId"] = $userId;
 
  mysqli_close($conn);
?>

</body>
</html>
<?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}

function select(){
	global $fromdate;
	global $todate;
	global $distId;
	global $conn;
	if($fromdate == "") {	
		$fromdate = date("d-m-Y");
	}

	if($todate == "") {	
		$todate = date("d-m-Y");
	}
	$fromdate = date("d-m-Y",strtotime($fromdate));
	$todate = date("d-m-Y",strtotime($todate));
	$distributorList = buildAssignDistributor21($distId);
?>
	<form name="frmFilter">
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
				<select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stkreport.php?distId='+this.value+'&fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
   				</select>
			</td>
           
         </tr>
        
         <tr>
         	<td colspan="2" align="left">
            	<input type="button" name="action" value="Apply" onClick="javascript:findData1(this.form,'reports/stkreport.php?fromdate='+document.frmFilter.fromdate.value+'&todate='+document.frmFilter.todate.value)">
			 	<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stkreport.php?a=reset')">
            </td>
        </tr>
        </table>
    </form>
    
     <?php
	 if($distId <> "" and $distId <> "0"){
	 	$grantSSqty = array();
	 	/**************************** Print Heading -- Category with Product List****************************************************************************/
		
		/******************************* Collect categories with respect to Product List ****************************************/
		
		//Find UserId
		$userqry = "select * from tbl_assignroute where dist_id='$distId'";
		$userresult = mysqli_query($conn,$userqry) or die ("Error in Assign Route ".mysqli_error($conn));
		if(mysqli_num_rows($userresult)>0){
			$userrow = mysqli_fetch_assoc($userresult);
			$userId = $userrow["user_id"];
		}
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
		
		//echo "Query :".$dist_query."<br>";
		
		$dist_result = mysqli_query($conn,$dist_query);
		$prev = "";
		if(mysqli_num_rows($dist_result)>0){
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
			echo "<tr><td width='10%'>&nbsp;</td><td width='35%'>Salesman</td><td width='15%'>Visit Date</td></tr>";
			while($dist_row = mysqli_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				//$todate = $stockdate;
				$dspdate1 = date("Y-m-d",strtotime($fromdate));
				$dspdate2 = date("Y-m-d",strtotime($todate));
				/**************************** To find last closing stock date *********************************************/
				$dspdate = date("Y-m-d",strtotime($todate));
				$prevqry = "select * from (select b.rec_date,b.user_id from tbl_cstock a,tbl_cstock_child b where a.cstock_id=b.cstock_id and 
								b.user_id='$userId' and b.dist_id = '$distid' 
								and date_format(b.rec_date,'%Y-%m-%d') >= '$fromdate' and date_format(b.rec_date,'%Y-%m-%d') <= '$todate' 
								group by b.rec_date) subq order by rec_date desc";
				//echo "Prev Qry :".$prevqry."<br>";
				$prevresult = mysqli_query($conn,$prevqry) or die("Error in Stockist Sales ".mysqli_error());
				if(mysqli_num_rows($prevresult) > 0){
					$prevval='';
					while($prevrow = mysqli_fetch_assoc($prevresult)){
						$rdate = $prevrow["rec_date"];
						if(date("d-m-Y",strtotime($rdate)) != $prevval){
							$prevval =date("d-m-Y",strtotime($rdate));
							$username = getUserName($prevrow["user_id"]);
							echo "<tr><td>";
							?>
                        	<input type="radio" name="radiondate" value="<?php echo $rdate ?>" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'reports/stockRotationReport.php?fromdate='+this.value+'&fmdate=<?php echo $fromdate; ?>&todate=<?php echo $todate ?>&uid=<?php echo $userId; ?>&did=<?php echo $distid; ?>')">
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
			echo "<table class='tbl1' border='0' cellspacing='0' cellpadding='4' width='100%'>";
			echo "<tr align='center'><td><b>No Data Found</b></td></tr>";
			echo "</table>";
		}
	}
	?>
<?php    
}


function getUserName($uid){
	$usersql = "select a.emp_dname empname from tbl_employee a,tbl_user b where a.emp_id=b.Title and b.user_id='$uid'";
	$userresult = mysqli_query($conn,$usersql);
	$userrow = mysqli_fetch_assoc($userresult);
	return $userrow["empname"];
}
?>
