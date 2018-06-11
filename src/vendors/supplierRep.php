<?php
session_start();
if($_GET["status"]==true){
		global $ccount;
		global $bdata;
		global $distributorid;
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["regionId"]="";
	$_SESSION["areaId"]="";
	$_SESSION["outletId"] = "";
	$_SESSION["routeId"] = "";
	$_SESSION["distId"] = "";
	$_SESSION["salesId"] = "";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["pageId"]="";
	$_SESSION["btnstatus"] = "";
	$_SESSION["bdata"] ="";
	$status = false;
	$btnstatus = false;
	$distId = "0";
	
  }
  
  if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	require_once("../config.php");
	require_once("../library/functions.php");
	$user_name=$_SESSION['User_ID'];
	 if($_GET["a"]=="reset"){
	 	$fromdate = date("d-m-Y");
		$todate = date("d-m-Y");
		$salesId=0;
		$distId = "0";
	 }
	if(isset($_GET["regionId"])) $regionId = $_GET["regionId"];
	if(isset($_GET["areaId"])) $areaId= $_GET["areaId"];   
	if (isset($_GET["distId"])) $distId= stripslashes(@$_GET["distId"]);
	
	
	
	if (!isset($regionId) && isset($_SESSION["regionId"])) $regionId = $_SESSION["regionId"];
	if (!isset($areaId) && isset($_SESSION["areaId"])) $areaId = $_SESSION["areaId"];
	if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
	
	 
	$regionList = buildRegionOptions ($regionId);
	$distId1 = $regionId;
	$areaList = buildAreaOptions($regionId,$areaId);
	if($regionId=='' or $areaId == '0' or $areaId==''){
		$areaId = '';
	}
	$distId1 .= $areaId;
	if($regionId <> "0" and $regionId <> ""){
		$distributorList = buildDistributorOptionsRep($regionId,$areaId,$distId);
	}
	
	if (isset($regionId)) $_SESSION["regionId"] = $regionId;
	if (isset($areaId)) $_SESSION["areaId"] = $areaId;
	if (isset($distId)) $_SESSION["distId"] = $distId; 
  ?>
  
   <html>
		<head>
			<title>mCRM -- Assign Distributor List</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
           
		</head>
		<body>
        <form name="frmFilter" action="" method="post">
		<table border="1" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
        	 <tr>     
    			<td width="15%" align="left"><b>Region</b></td>
        		<td align="left">
       			<select name="cboRegion" class="box" id="cboRegion" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php?regionId='+this.value)">
     				<option value="0" selected>All Region</option>
						<?php echo $regionList; ?>
   				</select>
              </td>
          	</tr>   
     		<tr>
     			<td align="left" width="15%"><b>Area Search:</b> </td>
        		<td align="left">
          			<input type="text" name="cboArea" class="box" id="cboArea" value = "<?php echo $areaId; ?>" 
                			onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php?areaId='+this.value)">
  				</td>  
     		</tr>
	 		<tr>     
                <td width="15%" align="left"><b>Distributor</b></td>
                <td align="left">
       				<select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php?distId='+this.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
   		</select>
       
        </td>
        
     </tr> 
    
             <tr>  
             	<td align="left" colspan="2">
				<input type="button" name="action" value="Apply" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php)" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php?a=reset')"></td>
		     </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
<?php
	if($distId == "0"){
		//$qry = "select a.dist_code,a.dist_name,b.outlet_name,c.route_code,c.route_name from tbl_distributor a,tbl_outlet b where a.dist_id=b.dist_id order by dist_name";
		$qry = "SELECT a.dist_id, concat(concat(d.region_name,'',c.area_name),'-',a.dist_name) dist_name, b.route_id, b.route_code, b.route_name
				FROM tbl_distributor a, tbl_route b,tbl_area c,tbl_region d
				WHERE a.area_code = b.area_code and a.area_code=c.area_id and a.region_code = d.region_id
				AND a.region_code = b.region_code
				ORDER BY a.dist_name, b.route_code";
		
	}else{
	
		$qry = "SELECT a.dist_id, concat(concat(d.region_name,'',c.area_name),'-',a.dist_name) dist_name, b.route_id, b.route_code, b.route_name
				FROM tbl_distributor a, tbl_route b,tbl_area c,tbl_region d
				WHERE a.area_code = b.area_code and a.area_code=c.area_id and a.region_code = d.region_id
				AND a.region_code = b.region_code and a.dist_id = '$distId'
				ORDER BY a.dist_name, b.route_code";
		
	}
	//echo $qry."<br>";
	showAllDistributors($qry);
	

}else{
	print "<script>window.location.href='../index.html';</script>";	
}

 ?>
 
 
 
 
<?php
//********** USER DEFINED FUNCTIONS ******************* 


function showAllDistributors($sqlQuery){
	$sqlResult = mysql_query($sqlQuery);
	if(mysql_num_rows($sqlResult) >0){
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
	 	echo "<tr class='hr'><td align='left'>SNo</td><td align='center'>Distributor</td><td align='center'>Route</td><td align='center'>Outlet</td></tr>";
		$sno=1;
		$prev="";
		while($sqlRow = mysql_fetch_assoc($sqlResult)){
				
				echo "<td align='left' width='5%'>".$sno."</td>";
				if($prev != $sqlRow["dist_name"]){
					$prev = $sqlRow["dist_name"];
					$prev1="";
					$prev2="";
					echo "<td align='left' width='30%'>".strtoupper($sqlRow["dist_name"])."</td>";
				}else{
					echo "<td align='left' width='30%'>&nbsp;</td>";
				}
				echo "<td align='left' width='35%'>".strtoupper($sqlRow["route_code"])."-".strtoupper($sqlRow["route_name"])."</td>";
				$outletname = getOutletNames($sqlRow["dist_id"],$sqlRow["route_id"]);
				if($outletname !=""){
					echo "<td align='left' width='30%'>".strtoupper($outletname)."</td>";
				}else{
					echo "<td align='left' width='30%'>-</td>";
				}
				
				echo "</tr>";
				$sno++;
		}
	  	echo "</table>"; 
	}else{
		// No distributor Found
	}
}


function showAssignedDistributors(){
	
	$sql = "select SUBQ.*,concat(concat(SUBQ.region_code,'',SUBQ.area_code),'-',n.area_desc1) regarea FROM (select a.*,	concat(b.emp_fname,'',b.emp_lname) empname from tbl_assignroute a,tbl_employee b where a.salesman=b.emp_id group by a.dist_id)SUBQ,tbl_area n where SUBQ.region_code=n.region_id and SUBQ.area_code=n.area_name";
	
	$result = mysql_query($sql) or die("Error in Assign Route ".mysql_error());
	echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
	echo "<tr class='hr'><td align='left'>SNo</td><td align='center'>RegionCode</td><td align='center'>Distributor</td><td align='center'>Salesman</td></tr>";
	if(mysql_num_rows($result)>0){
		$sno=1;
		while($rs = mysql_fetch_assoc($result)){
				echo "<tr class='dr'>";
				echo "<td align='left' width='5%'>".$sno."</td>";
				echo "<td align='left' width='45%'>".strtoupper($rs["regarea"])."</td>";
				echo "<td align='left' width='30%'>".strtoupper($rs["dist_name"])."</td>";
				echo "<td align='left' width='20%'>".$rs["empname"]."</td>";
				echo "</tr>";
				$sno++;
		}
		
	}else{
		echo "<tr><td colspan='3'>No Data Found</td></tr>";
	}
	echo "</table>"; 
}

function showUnassignedDistributors(){
	
	$sqlQuery = "select concat(concat(a.region_code,'',a.area_code),'-',b.area_desc1) regarea,a.dist_name,a.dist_id 
						from tbl_distributor a,tbl_area b where a.region_code = b.region_id and a.area_code=b.area_name order by a.dist_name";
	$sqlResult = mysql_query($sqlQuery);
	if(mysql_num_rows($sqlResult) >0){
		$sno=1;
		echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
	 	echo "<tr class='hr'><td align='left'>SNo</td><td align='center'>RegionCode</td><td align='center'>Distributor</td><td align='center'>Salesman</td></tr>";
		while($sqlRow = mysql_fetch_assoc($sqlResult)){
				$distid = $sqlRow["dist_id"];
				$sql = "select a.*,concat(b.emp_fname,'',b.emp_lname) empname from tbl_assignroute a,tbl_employee b 
						where a.salesman=b.emp_id and a.dist_id = '$distid' group by a.user_id";
				
			
				$result = mysql_query($sql) or die("Error in Assign Route ".mysql_error());
				if(mysql_num_rows($result)>0){
					//nothing
					
				}else{
					$rs = mysql_fetch_assoc($result);
					$ename = $rs["empname"];
					echo "<tr class='dr'>";
					echo "<td align='left' width='5%'>".$sno."</td>";
					echo "<td align='left' width='45%'>".strtoupper($sqlRow["regarea"])."</td>";
					echo "<td align='left' width='30%'>".strtoupper($sqlRow["dist_name"])."</td>";
					echo "<td align='left' width='20%'>".$ename."</td>";
					echo "</tr>";
					$sno++;
				}	
				
				
		}
	  	echo "</table>"; 
	}else{
		// No distributor Found
	}
}

function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`outlet_id`";
  	if (@$_POST["xoutlet_id"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xoutlet_id"], false);
  	};
  	$pk .= ")";
  	return $pk;
}

/*function getDistributorNames($dId)
{
	global $conn;
	$dsql = "select dist_name from tbl_distributor where dist_id = '$dId'";
	//echo "D SQL :".$dsql."<br>";
	$dres = mysql_query($dsql, $conn) or die(mysql_error());
  	$drow = mysql_fetch_assoc($dres);
	//$dresult = mysql_query($dsql,$conn) or die(mysql_error());
	//$drow = mysql_fetch_assoc($dresult); 
	return $drow["dist_name"];
}

function getRouteNames($rId)
{
	global $conn;
	if(strpos($rId,",")== false){
		$rsql = "select concat(route_code,'-',route_name) routename from tbl_route where route_id = '$rId'";
		$rresult = mysql_query($rsql,$conn);
		$rrow = mysql_fetch_assoc($rresult);
		$rdata = $rrow["routename"];
	}else{
		$rsql = "select concat(route_code,'-',route_name) routename from tbl_route where route_id in ($rId)";
		//echo "R SQL :".$rsql."<br>";
		$rresult = mysql_query($rsql,$conn);
		$rdata="";
		while($rrow = mysql_fetch_assoc($rresult)){
			$rdata .= $rrow["routename"].",";
		}
		$rdata = substr($rdata,0,strlen($rdata)-1);
	}
	
	return $rdata;
}*/
/****************** End of the USER DEFINED Functions ***********************************/
?>
