<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["regionId"]="";
	$_SESSION["areaId"]="";
	$_SESSION["outletId"] = "";
	$_SESSION["routeId"] = "";
	$_SESSION["distId"] = "";

	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["pageId"]="";
	
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	
	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
	if (isset($_GET['outletId'])) $outletId = @$_GET["outletId"];
	if (isset($_GET['routeId'])) $routeId = @$_GET["routeId"];
	if (isset($_GET['distId'])) $distId = @$_GET["distId"];
    if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["regionId"])) $regionId = $_GET["regionId"];
    if(isset($_GET["areaId"])) $areaId = $_GET["areaId"];   
	

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($outletId) && isset($_SESSION["outletId"])) $outletId = $_SESSION["outletId"];
	if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
	if (!isset($routeId) && isset($_SESSION["routeId"])) $routeId = $_SESSION["routeId"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($regionId) && isset($_SESSION["regionId"])) $regionId = $_SESSION["regionId"];
    if (!isset($areaId) && isset($_SESSION["areaId"])) $areaId = $_SESSION["areaId"];
	
	//echo "Region ID :".$regionId. " Area Id :".$areaId. " Route Id:".$routeId. " Dist Id:".$distId."<br>";
	// Region
	if($_GET["a"] <> "edit"){
		if($regionId <> '' and $regionId <> '0'){
			$queryString = " and region_code like '$regionId%' ";
		}else {
			$queryString = "";
		} 
	  
		//Area
		if($queryString<> '' and $areaId <> '' and $areaId <> '0'){
		   if($regionId <> '' and $regionId <> '0'){
				$ar = $regionId.$areaId;
				$queryString .= " and dist_code like '$ar%' ";
		   }
		}
		
		
		 // Distributor
		if($regionId <> '' and $regionId <> '0'){
		   if ($distId <> '' and $distId <> '0') {
			//$distId = (int)$_GET['distId'];
				$queryString .= " and dist_id='$distId' ";
		   }
		}
		
		//Route
		if ($distId <> '' and $distId <> '0') {
			if($routeId <> '' and ($routeId <> '0') and $routeId <> 'All Routes'){
				$queryString .= " and route_id='$routeId' ";
			}
		}
	}
	
?>
	<html>
		<head>
			<title>mCRM -- Outlet Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
           
		</head>
		<body>
		<?php
  			require_once("../config.php");
			require_once("../library/functions.php");
			
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
  				$sql = @$_GET["sql"];
 
  				switch ($sql) {
    				case "insert":
	  					sql_insert();
      					break;
    				case "update":
      					sql_update();
      					break;
    				case "delete":
      					sql_delete();
      					break;
  				}
  				switch ($a) {
    				case "add":
      					addrec();
      					break;
    				case "view":
	  					viewrec($recid);
      					break;
    				case "edit":
      					editrec($recid);
      					break;
    				case "del":
      					sql_delete();
      					break;
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
				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				if (isset($outletId)) $_SESSION["outletId"] = $outletId;
				if (isset($routeId)) $_SESSION["routeId"] = $routeId;
				if (isset($distId)) $_SESSION["distId"] = $distId;
				if (isset($regionId)) $_SESSION["regionId"] = $regionId;
				if (isset($areaId)) $_SESSION["areaId"] = $areaId;
  				mysql_close($conn);
		?>
		<table class="bd" width="100%"><tr><td class="hr"></td></tr></table>
	</body>
</html>

<?php
}else{
	print "<script>window.location.href='index.html';</script>";	
}
?>



<?php
/*********************** USER DEFINED FUNCTION START *******************************/

 
/****************** Start SELECT Function ***********************************/
function select()
{
	global $a;
  	global $showrecs;
  	global $page;
  	global $filter;
  	global $filterfield;
  	global $wholeonly;
  	global $order;
  	global $ordtype;
	global $outletList;
	global $outletId;
	global $routeId;
	global $regionId;
	global $areaId;
	global $distId;
	global $queryString;
	global $pagerange;
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$outletId = "";
		$areaId = "";
		$regionId = "";
		$routeId = "";
		$distId = "";
		$queryString ="";
  	}

  	$checkstr = "";
  	if ($wholeonly) $checkstr = " checked";
  	if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  	$res = sql_select();
  	$count = sql_getrecordcount();
	//echo "Count =".$count;
  	if ($count % $showrecs != 0) {
    	$pagecount = intval($count / $showrecs) + 1;
  	}
  	else {
    	$pagecount = intval($count / $showrecs);
  	}
  	$startrec = $showrecs * ($page - 1);
  	if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	//echo "Outlet ID:".$outletId."<br>";
	$regionList = buildRegionOptions ($regionId);
	
	if($regionId=='0' or $regionId==''){
		$regionId = '';
	}
	
	$distId1 = $regionId;
	
  
	
    $areaList = buildAreaOptions($regionId,$areaId);
	if($regionId=='' or $areaId == '0' or $areaId==''){
		$areaId = '';
	}
	$distId1 .= $areaId;
	
	
	if($regionId <> "0" and $regionId <> ""){
		
  		$distributorList = buildDistributorOptionsRep($distId1,$distId);
	}
	if($distId <> "0" and $distId <> "" ){
		//echo "<br>"."Dist Id:".$distId. " = ".$distId1." = ".$routeId."<br>";
		//$routeList = buildRouteOptionsRep($regionId,$areaId,$routeId);
		$routeList =  buildRouteOptionsRep1($distId1,$distId,$routeId);
	}
	
	/*if($regionId <> "" and $regionId <> "0"){
		$routeList = buildRouteOptionsReport($regionId,$areaId,$routeId);
	}
	
	//echo "Area Id :".$areaId."<br>";
	if($regionId <> "0" and $regionId <> "" and $routeId <> "" and $routeId <>"0"){
		//echo "<br>"."Dist Id2:".$distId1. " = ".$distId."<br>";
  		$distributorList = buildDistributorOptionsReport($regionId,$areaId,$routeId,$distId);
	}*/
	
?>
	
	<form name="frmListOutlet" action="" method="post">
		<table border="1" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
        	<tr>
          		<td align="left" width="15%"><b>Region :</b> </td>
          		<td align="left" width="35%">
          		<select name="cboRegion" class="box" id="cboRegion" 
                		onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?distId=0&outletId=0&routeId=0&regionId='+this.value)">
     					<option value="0" selected>All Region</option>
							<?php echo $regionList; ?>
   				</select>
 		  		</td>
          		<td align="left" width="15%"><b>Area Search:</b> </td>
          		<td align="left" width="35%">
          		<input type="text" name="cboArea" class="box" id="cboArea" value = "<?php echo $areaId; ?>" 
                			onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?distId=0&outletId=0&routeId=0&areaId='+this.value)">
  		  		</td>  
          	</tr>
          	<tr>  
             
              <td align="left" width="15%"><b>Distributors :</b> </td>
    	  	  <td align="left" width="35%">
          	    <select name="cboDistributor" class="box" id="cboDistributor" 
            					onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?distId='+this.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
                </select>
 		  	  </td>
              <td align="left" width="15%"><b>Routes :</b></td>
              <td align="left" width="35%">
    			<select name="cboRoute" class="box" id="cboRoute" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>',
                 'outlet/outlet.php?routeId='+this.value)">
     				<option selected>All Routes</option>
						<?php echo $routeList; ?>
   				</select>
 			  </td>
            </tr>
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
					<option value="<?php echo "outlet_name" ?>"<?php if ($filterfield == "outlet_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Outlet Name")?>
                    </option>
					
                    <option value="<?php echo "outlet_cperson" ?>"<?php if ($filterfield == "outlet_cperson") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Contact Person") ?>
                    </option>
                    <option value="<?php echo "outlet_mobno" ?>"<?php if ($filterfield == "outlet_mobno") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Mobile No") ?>
                    </option>
				 </select>
               
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'outlet/outlet.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=reset')"></td>
		     </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmOutlet" id="frmOutlet" action="outlet/outlet.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
    	
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Outlet" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=add&outletId=<?php echo $outletId ?>')">
            	<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'outlet/processOutlet.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?status=true')}" >
           		<input type="hidden" name="action" value="delete">
            	
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?pageId='+this.value)">
				<option value="<?php echo $showrecs; ?>"><?php echo $showrecs; ?></option>
				<option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
             </select>
			</td>
           
		</tr>
	</table>

	
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
		<td class="hr">
          	<input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);">
        </td>
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "region_code" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Region&Area Code") ?></a>
        </td>
       
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "dist_id" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Distributor Name") ?></a>
        </td>
          <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "route_id" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Route Name") ?></a>
        </td>
		<td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "outlet_name" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Outlet Name") ?></a>
        </td>
	
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "outlet_cperson" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Contact Person") ?></a>
        </td>
		<td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?order=<?php echo "outlet_mobno" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Mobile No") ?></a>
        </td>	
		</tr>
	<?php
  	for ($i = $startrec; $i < $reccount; $i++)

  	{
    	$row = mysql_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		if($row["outlet_name"] != ""){
			$outcode = $row["outlet_id"];
			$field = "outlet_id";
			//echo "Outlet ID :".$outcode;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['outlet_id']; ?>">
        </td>
         <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars($row["dist_code"]) ?>
         </td>
       
         <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars(strtoupper(getDistributorName($row["dist_id"]))) ?>
         </td>
          <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars(getRouteName($row["route_id"])); ?>
         </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo strtoupper($row["outlet_name"]) ?>
         </td>
	
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
            	<?php echo htmlspecialchars($row["outlet_cperson"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
            	<?php echo htmlspecialchars($row["outlet_mobno"]) ?>
        </td>
       </tr>
       
	
	<?php
		}
  	}//for loop
  	mysql_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form><center>
	<?php 
		showpagenav($page, $pagecount,$pagerange,'outlet/outlet.php'); 
} 
 ?></center>
 <?php

/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $distId;
  global $regionId;
  global $areaId;
  global $routeId;
  //echo " Dist Id: ".$distId."<br>";
  if($iseditmode==false)
  {
  	 
  	$mode=false;
	$aa = "add";
	
		
	$regionList = buildRegionOptions ($regionId);
  	if($regionId=='0' or $regionId==''){
		$regionId = '';
	}
	$distId1 = $regionId;
	
	$areaList = buildAreaOptions($regionId,$areaId);
	if($regionId=='' or $areaId == '0' or $areaId==''){
		$areaId = '';
	}
	$distId1 .= $areaId;
	
	if($regionId <> "0" and $regionId <> ""){
		//echo "P1 = ".$distId1." P2=".$distId."<br>";
  		$distributorList = buildDistributorOptionsRep($distId1,$distId);
	}
	
  	//echo "Route Id11: ".$routeId. "<br>";	
  	if ((int)$distId > 0 and $distId <> ""){
  	  //echo "Route Id11: ".$routeId. "<br>";
	 // $routeList =  buildRouteForOutlet($distId);
		//$routeList = buildRouteOptionsRep($regionId,$areaId,$routeId);
		$routeList = buildRouteOptionsRep1($regionId,$distId,$routeId);
  	}
	

 }
 else
 {
 	$mode=true;
	$aa = "edit";
	
	//echo "Edit Mode :Region Id :".$regionId ."<br>";
	if($regionId == "0" or $regionId == ""){
		$regionId = $row["region_code"];
	}
	$regionList = buildRegionOptions ($regionId);

	if($regionId <> "0" or $regionId<>""){
		if($areaId == "0" or $areaId == ""){
				$areaId = $row["area_code"];
		}
		$areaList = buildAreaOptions($regionId,$areaId);
		$distId1 = $regionId.$areaId;
	
	
		if($distId == "0" or $distId == ""){
	 		$distId = $row["dist_id"];
		}
		$distributorList = buildDistributorOptionsRep($distId1,$distId);
		
		if($routeId == "0" or $routeId == ""){
			$routeId = $row["route_id"];
		}
		$routeList =  buildRouteOptionsRep($regionId,$areaId,$routeId); 
	}
	
	
	$routename = getRouteName($routeId);
    $distname = getDistributorName($distId);

	
 }
 
	

?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
          <tr> 
        	<td width="150" class="hr">Region</td>
   				<td align="left"> 
                	<select name="cboRegion" id="cboRegion" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&regionId='+this.value)">
     					<option value="" selected>-- Choose Region --</option>
						<?php
							echo $regionList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		  </tr>
          <tr> 
   			<td width="150" class="hr">Area Search:</td>
   				<td align="left"> 
                	<input type="text" class="uppercase" name="cboArea" id="cboArea" value = "<?php echo $areaId; ?>" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&areaId='+this.value)">
     			</td>
  		  </tr>
    	  
    	  <tr> 
   				<td width="150" class="hr">Distributor</td>
   				<td align="left"> 
                	<select name="cboDistributor" id="cboDistributor" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>',
                 'outlet/outlet.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&distId='+this.value)">
     					<option value="" selected>-- Choose Distributor --</option>
						<?php
							echo $distributorList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		  </tr>
          <tr> 
   				<td width="150" class="hr">Routes</td>
   				<td align="left"> 
                	<select name="cboRoute" id="cboRoute" class="box" value = "<?php echo $routeId; ?>" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&routeId='+this.value)">
     					<option value="" selected>-- Choose Routes --</option>
						<?php
							echo $routeList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		  </tr>

    	  <tr>
			<td class="hr"><?php echo htmlspecialchars("Outlet Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="outlet_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["outlet_name"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
            
		</tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("Outlet Address")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<textarea name="outlet_address" class="uppercase" id="outlet_address" cols="50" rows="4"><?php echo nl2br($row["outlet_address"]) ?></textarea>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
   
		 <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="outlet_city" class="uppercase" name="outlet_city" maxlength="50" size="50" value = "<?php echo nl2br($row["outlet_city"]) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="outlet_cperson" name="outlet_cperson" maxlength="50" size="50" value = "<?php echo $row["outlet_cperson"] ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="outlet_mobno" name="outlet_mobno" maxlength="20" size="20" value = "<?php echo $row["outlet_mobno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="outlet_landno" name="outlet_landno" maxlength="20" size="20" value = "<?php echo $row["outlet_landno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="lowercase" id="outlet_email1" name="outlet_email1" maxlength="30" size="30" value = "<?php echo $row["outlet_email1"] ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="lowercase" id="outlet_email2" name="outlet_email2" maxlength="30" size="30" value = "<?php echo $row["outlet_email2"] ?>">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
       
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
  global $conn;
 
  $distid = $row["dist_id"];
  $rid = $row["route_id"];
  $outletid = $row["outlet_id"];
  $outletname = $row["outlet_name"];
  $outletadd = $row["outlet_address"];
  $outletcity = $row["outlet_city"];
  $outletperson = $row["outlet_cperson"];
  $outletmobno = $row["outlet_mobno"];
  $outletlandno = $row["outlet_landno"];
  $outletemail1 = $row["outlet_email1"];
  $outletemail2 = $row["outlet_email2"];
  
  $routename = getRouteName($rid);
  $distname = getDistributorName($distid);
  
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Region Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["region_code"]) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["area_code"]) ?></td>
		</tr>
       
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Distributor Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_code"]."-".$distname) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Route Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($routename) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Outlet Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletname) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Outlet Address")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletadd) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletcity) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletperson) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No.")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletmobno) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No.")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletlandno) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletemail1) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($outletemail2) ?></td>
		</tr>
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/




/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                	<input type="button" name="btnNext" value="Next Record" 
                    	onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')">
                </td>
	  <?php } ?>
	   </tr>
	</table>
	<hr size="1" noshade>
<?php 
} 
/****************** End of the SHOW RECORD NAVIGATION Function ***********************************/
 

/****************** ************ADD RECORD Function *********************************************/
function addrec()
{
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="outlet_validate" enctype="multipart/form-data" action="outlet/processOutlet.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
         <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"region_code" => "",
					"area_code" => "",
   					"outlet_name" => "",
  					"outlet_address" => "",
					"outlet_cperson" => "",
					"outlet_city" => "",
					"outlet_mobno" => "",
					"outlet_landno" => "",
					"outlet_email1" => "",
					"outlet_email2" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(outletvalidate()==true){javascript: formget(this.form, 'outlet/processOutlet.php');}"></p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php 
} 

/****************** End of the ADD RECORD Function ***********************************/

/****************** START of the VIEW RECORD Function ***********************************/
function viewrec($recid)
{
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
	$outletcode = $row["outlet_id"];
	$field="outlet_id";
	//echo "View : OutletCode :".$outletcode;
  	showrecnav("view", $recid, $count);
?>
	<br>
<?php
	showrow($row, $recid); 
	
	
?>
	<br>

	<hr size="1" noshade>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?a=edit&filter=<?php echo $outletcode ?>&filter_field=<?php echo $field ?>')"></td>
		</tr>
	</table>
	<?php
  		mysql_free_result($res);
	} 
/****************** End of the VIEW RECORD Function ***********************************/


/*********************** START EDIT RECORD Function ***********************************/
function editrec($recid)
{
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
  	showrecnav("edit", $recid, $count);
?>
	<br>
	<form name="outlet_validate" enctype="multipart/form-data" action="outlet/processOutlet.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="outlet_id" value="<?php echo $row["outlet_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(outletvalidate()==true){javascript: formget(this.form, 'outlet/processOutlet.php');}">
        </p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php
	mysql_free_result($res);
} 
/****************** End of the EDIT RECORD Function ***********************************/

/****************** ***START DELECT RECORD Function ***********************************/
function deleterec($recid)
{
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
  	showrecnav("del", $recid, $count);
?>
	<br>
	<form action="outlet/processOutlet.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="outlet_id" value="<?php echo $row["outlet_id"] ?>">
		<?php showrow($row, $recid) ?>
		<p><input type="submit" name="action" value="Confirm"></p>
	</form>
<?php
  mysql_free_result($res);
} 
/****************** End of the DELETE RECORD Function ***********************************/
 


function sqlvalue($val, $quote)
{
	if ($quote)
    	$tmp = sqlstr($val);
  	else
    	$tmp = $val;
  	if ($tmp == "")
    	$tmp = "NULL";
  	elseif ($quote)
    	$tmp = "'".$tmp."'";
  
  	return $tmp;
}

function sqlstr($val)
{
  	return str_replace("'", "''", $val);
}

function sql_select()
{
  	global $conn;
  	global $order;
  	global $ordtype;
  	global $filter;
  	global $filterfield;
  	global $wholeonly;
 	global $queryString;
	global $a;
	
	//echo "Action :".$a."<br>";
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "SELECT `region_code`,`area_code`, `dist_code`, `outlet_id`, `dist_id`,`route_id`,`outlet_name`, `outlet_cperson`, `outlet_address`, `outlet_city`,
			`outlet_mobno`,`outlet_landno`,`outlet_email1`,`outlet_email2` FROM `tbl_outlet` WHERE 1 ". $queryString;
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`region_code` like '" .$filterstr ."') or (`area_code` like '" .$filterstr ."') or (`dist_id` like '" .$filterstr ."') 
				or (`outlet_name` like '" .$filterstr ."') or (`outlet_cperson` like '" .$filterstr ."') or (`outlet_address` like '" .$filterstr ."') 
				or (`outlet_city` like '" .$filterstr ."') or (`outlet_mobno` like '" .$filterstr ."') or (`outlet_landno` like '" .$filterstr ."')";
  	}
  	
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    //echo "SQL :".$sql."<br>";
	
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
	global $queryString;
	global $a;
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT COUNT(*) FROM `tbl_outlet` WHERE 1 ". $queryString;
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= "  and (`region_code` like '" .$filterstr ."') or (`area_code` like '" .$filterstr ."') or (`dist_id` like '" .$filterstr ."') 
				or (`outlet_name` like '" .$filterstr ."') or (`outlet_cperson` like '" .$filterstr ."') or (`outlet_address` like '" .$filterstr ."') 
				or (`outlet_city` like '" .$filterstr ."') or (`outlet_mobno` like '" .$filterstr ."') or (`outlet_landno` like '" .$filterstr ."')";
  	}
  	//echo "SQL1 :".$sql."<br>";
	
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);
	
	//echo  "RETURN ROW :".current($row);
  	return current($row);
	
}

function sql_insert()
{
  	global $conn;
  	global $_GET;
  	//echo 'Imei No :'.$_POST["imei_no"]."<br>";
    //echo 'Imei No111 :'.@$_GET["imei_no"];
  	if(isset($_GET["outlet_name"]) && $_GET["outlet_name"]<>''){ 
     $sql = "insert into `tbl_outlet` (`outlet_name`, `outlet_address`,`outlet_cperson`) values (" .sqlvalue(@$_GET["outlet_name"], true).", " .sqlvalue(@$_GET["outlet_address"], true)."," .sqlvalue(@$_GET["outlet_cperson"], true).")";
     mysql_query($sql, $conn) or die(mysql_error());
  	}
}

function sql_update()
{
  	global $conn;
  	global $_POST;

  	$sql = "update `tbl_outlet` set `outlet_id`=" .sqlvalue(@$_POST["outlet_id"], false).", `outlet_name`=" .sqlvalue(@$_POST["outlet_name"], true).", `outlet_address`=" .sqlvalue(@$_POST["outlet_address"], true)." where " .primarykeycondition();
  	mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  	global $conn;
	
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			//Outlet
			$strSQL = "DELETE FROM tbl_outlet WHERE outlet_id = '".$_POST["userbox"][$i]."'";
			$result = mysql_query($strSQL) or die(mysql_error());
			
		}  
	}  
	print("<script>history.go(-1);</script>");
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

/****************** End of the USER DEFINED Functions ***********************************/
?>
