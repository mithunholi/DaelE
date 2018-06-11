<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["regionId"]="";
	$_SESSION["areaId"]="";
	$_SESSION["customerId"] = "";
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
	if (isset($_GET['customerId'])) $customerId = @$_GET["customerId"];
	if (isset($_GET['routeId'])) $routeId = @$_GET["routeId"];
	if (isset($_GET['distId'])) $distId = @$_GET["distId"];
    if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["regionId"])) $regionId = $_GET["regionId"];
    if(isset($_GET["areaId"])) $areaId = $_GET["areaId"];   
	

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($customerId) && isset($_SESSION["customerId"])) $customerId = $_SESSION["customerId"];
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
			<title>mCRM -- Customer Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="CRM.js"></script>
           
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
				if (isset($customerId)) $_SESSION["customerId"] = $customerId;
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
	print "<script>window.location.href='../index.html';</script>";	
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
	global $customerList;
	global $customerId;
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
		$customerId = "";
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
	//echo "Outlet ID:".$customerId."<br>";
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
                		onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?distId=0&customerId=0&routeId=0&regionId='+this.value)">
     					<option value="0" selected>All Region</option>
							<?php echo $regionList; ?>
   				</select>
 		  		</td>
          		<td align="left" width="15%"><b>Area Search:</b> </td>
          		<td align="left" width="35%">
          		<input type="text" name="cboArea" class="box" id="cboArea" value = "<?php echo $areaId; ?>" 
                			onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?distId=0&customerId=0&routeId=0&areaId='+this.value)">
  		  		</td>  
          	</tr>
          	<tr>  
             
              <td align="left" width="15%"><b>Distributors :</b> </td>
    	  	  <td align="left" width="35%">
          	    <select name="cboDistributor" class="box" id="cboDistributor" 
            					onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?distId='+this.value)">
     				<option value="0" selected>All Distributor</option>
						<?php echo $distributorList; ?>
                </select>
 		  	  </td>
              <td align="left" width="15%"><b>Routes :</b></td>
              <td align="left" width="35%">
    			<select name="cboRoute" class="box" id="cboRoute" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>',
                 'customer/customer.php?routeId='+this.value)">
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
					<option value="<?php echo "customer_name" ?>"<?php if ($filterfield == "customer_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Outlet Name")?>
                    </option>
					
                    <option value="<?php echo "customer_cperson" ?>"<?php if ($filterfield == "customer_cperson") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Contact Person") ?>
                    </option>
                    <option value="<?php echo "customer_mobno" ?>"<?php if ($filterfield == "customer_mobno") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Mobile No") ?>
                    </option>
				 </select>
               
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'customer/customer.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=reset')"></td>
		     </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmOutlet" id="frmOutlet" action="customer/customer.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
    	
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Customer" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=add&customerId=<?php echo $customerId ?>')">
            	<input type="button" name="btnDelete" value="Delete" onClick="if(onDeletes()==true){ javascript:formget(this.form,'customer/processCustomer.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?status=true');javascript:printHeader('Outlet Admin');}" >
           		<input type="hidden" name="action" value="delete">
            	
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
           
		</tr>
	</table>

	
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
		<td class="hr">
          	<input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);">
        </td>
        <td class="hr">S.No.</td>
        
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "region_code" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Region&Area Code") ?></a>
        </td>
       
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "dist_id" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Distributor Name") ?></a>
        </td>
          <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "route_id" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Route Name") ?></a>
        </td>
        <td class="hr">OL_SNo</td>
		<td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Customer Name") ?></a>
        </td>
	
        <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "customer_cperson" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Contact Person") ?></a>
        </td>
		<td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?order=<?php echo "customer_mobno" ?>&type=<?php echo $ordtypestr ?>')">
			<?php echo htmlspecialchars("Mobile No") ?></a>
        </td>	
		</tr>
	<?php
	$prevvalue="";
	$sno=1;
  	for ($i = $startrec; $i < $reccount; $i++)
  	{
    	$row = mysql_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		if($row["customer_name"] != ""){
			$outcode = $row["customer_id"];
			$field = "customer_id";
			//echo "Outlet ID :".$outcode;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['customer_id']; ?>">
        </td>
        <td><?php echo $sno; ?></td>
        <?php
			if($prevvalue != $row["regarea"]){
				$prevvalue = $row["regarea"];
				$prevvalue1 = "";
		?>
         	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars($row["regarea"]) ?>
         	</td>
       
         	
       <?php
	   		}else{
		?>
        	
            <td>&nbsp;</td>
        <?php
			}
			if($prevvalue1 != $row["dist_name"]){
				$prevvalue1 = $row["dist_name"];
				$prevvalue2 = "";
		?>
         
         <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars(strtoupper($row["dist_name"])) ?>
         	</td>
         <?php }else{ ?>
         	<td>&nbsp;</td>
         <?php 
		 		}
				if($prevvalue2 != $row["route_name"]){
					$prevvalue2 = $row["route_name"];
					$outno = 1;
			?>
		 
            <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo htmlspecialchars($row["route_code"].'-'.$row["route_name"]); ?>
         	</td>
            <?php
				}else{
			?>
            	<td>&nbsp;</td>
            <?php } ?>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
        	<?php echo $outno; ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
				<?php echo strtoupper($row["customer_name"]) ?>
         </td>
	
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
            	<?php echo htmlspecialchars($row["customer_cperson"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
            	<?php echo htmlspecialchars($row["customer_mobno"]) ?>
        </td>
       </tr>
       
	
	<?php
		$outno++;
		}
		$sno++;
  	}//for loop
  	mysql_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form><center>
	<?php 
		showpagenav($page, $pagecount,$pagerange,'customer/customer.php'); 
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
	if($regionId <> "0" and $regionId <> ""){
	$areaList = buildAreaOptions($regionId,$areaId);
	}
	if($regionId=='' or $areaId == '0' or $areaId==''){
		$areaId = '';
	}
	$distId1 .= $areaId;
	
	if($regionId <> "0" and $regionId <> ""){
		//echo "P1 = ".$distId1." P2=".$distId."<br>";
  		$distributorList = buildDistributorOptionsRep($regionId,$areaId,$distId);
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
		//$distId1 = $regionId.$areaId;
	
	
		if($distId == "0" or $distId == ""){
	 		$distId = $row["dist_id"];
		}
		$distributorList = buildDistributorOptionsRep($regionId,$areaId,$distId);
		
		if($routeId == "0" or $routeId == ""){
			$routeId = $row["route_id"];
		}
		$routeList =  buildRouteOptionsRep($regionId,$areaId,$routeId); 
	}
	
	
	//$routename = getRouteName($routeId);
    //$distname = getDistributorName($distId);

	
 }
 
	

?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
          <tr> 
        	<td width="150" class="hr">Region</td>
   				<td align="left"> 
                	<select name="cboRegion" id="cboRegion" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&regionId='+this.value)">
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
                	<select name="cboArea" id="cboArea" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&areaId='+this.value)">
                    <?php
						echo $areaList;
					?>
     			</td>
  		  </tr>
    	  
    	  <tr> 
   				<td width="150" class="hr">Distributor</td>
   				<td align="left"> 
                	<select name="cboDistributor" id="cboDistributor" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>',
                 'customer/customer.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&distId='+this.value)">
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
                	<select name="cboRoute" id="cboRoute" class="box" value = "<?php echo $routeId; ?>" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&routeId='+this.value)">
     					<option value="" selected>-- Choose Routes --</option>
						<?php
							echo $routeList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		  </tr>

    	  <tr>
			<td class="hr"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="customer_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["customer_name"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
            
		</tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("Customer Address")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<textarea name="customer_address" class="uppercase" id="customer_address" cols="50" rows="4"><?php echo nl2br($row["customer_address"]) ?></textarea>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
   
		 <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="customer_city" class="uppercase" name="customer_city" maxlength="50" size="50" value = "<?php echo nl2br($row["customer_city"]) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="customer_cperson" name="customer_cperson" maxlength="50" size="50" value = "<?php echo $row["customer_cperson"] ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="customer_mobno" name="customer_mobno" maxlength="20" size="20" value = "<?php echo $row["customer_mobno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="customer_landno" name="customer_landno" maxlength="20" size="20" value = "<?php echo $row["customer_landno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="lowercase" id="customer_email1" name="customer_email1" maxlength="30" size="30" value = "<?php echo $row["customer_email1"] ?>">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="lowercase" id="customer_email2" name="customer_email2" maxlength="30" size="30" value = "<?php echo $row["customer_email2"] ?>">
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
  $customerid = $row["customer_id"];
  $customername = $row["customer_name"];
  $customeradd = $row["customer_address"];
  $customercity = $row["customer_city"];
  $customerperson = $row["customer_cperson"];
  $customermobno = $row["customer_mobno"];
  $customerlandno = $row["customer_landno"];
  $customeremail1 = $row["customer_email1"];
  $customeremail2 = $row["customer_email2"];
  
 
  
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Region Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["region_name"]) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["area_name"]) ?></td>
		</tr>
       
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Distributor Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["region_name"].$row["area_name"].'-'.$row["dist_name"]) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Route Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["route_code"].'-'.$row["route_name"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customername) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Customer Address")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customeradd) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customercity) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customerperson) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No.")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customermobno) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No.")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customerlandno) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customeremail1) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($customeremail2) ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                	<input type="button" name="btnNext" value="Next Record" 
                    	onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')">
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="customer_validate" enctype="multipart/form-data" action="customer/processCustomer.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
         <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"region_code" => "",
					"area_code" => "",
   					"customer_name" => "",
  					"customer_address" => "",
					"customer_cperson" => "",
					"customer_city" => "",
					"customer_mobno" => "",
					"customer_landno" => "",
					"customer_email1" => "",
					"customer_email2" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(customervalidate()==true){javascript: formget(this.form, 'customer/processCustomer.php');}"></p>
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
	$customercode = $row["customer_id"];
	$field="customer_id";
	//echo "View : OutletCode :".$customercode;
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
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?a=edit&filter=<?php echo $customercode ?>&filter_field=<?php echo $field ?>')"></td>
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
	<form name="customer_validate" enctype="multipart/form-data" action="customer/processCustomer.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="customer_id" value="<?php echo $row["customer_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(customervalidate()==true){javascript: formget(this.form, 'customer/processCustomer.php');}">
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
	<form action="customer/processCustomer.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="customer_id" value="<?php echo $row["customer_id"] ?>">
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
  	
	
	$sql = "SELECT * FROM (SELECT concat(c.region_name,'',b.area_name) dist_code, concat(concat(c.region_name,'',b.area_name),'-',b.area_desc1) regarea, a.*,
			b.area_name, c.region_name, d.dist_name, 
			e.route_code, e.route_name 
			FROM tbl_customer a,tbl_area b,tbl_region c,tbl_distributor d,tbl_route e 
			WHERE a.region_code=c.region_id and a.area_code=b.area_id and a.dist_id=d.dist_id and a.route_id=e.route_id)SUBQ"." where 1 ".$queryString;
			
	
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`dist_name` like '" .$filterstr ."') or (`dist_code` like '" .$filterstr ."') or (`regarea` like '" .$filterstr ."')
				or (`customer_name` like '" .$filterstr ."') or (`customer_cperson` like '" .$filterstr ."') or (`customer_address` like '" .$filterstr ."') 
				or (`customer_city` like '" .$filterstr ."') or (`customer_mobno` like '" .$filterstr ."') or (`customer_landno` like '" .$filterstr ."')";
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
	}else{
		$sql .= " order by dist_id,route_name";
	}
	
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    ///echo "SQL :".$sql."<br>";
	
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
  	
	$sql = "SELECT count(*) FROM (SELECT concat(c.region_name,'',b.area_name) dist_code,concat(concat(c.region_name,'',b.area_name),'-',b.area_desc1) regarea, a.*,
			 b.area_name, c.region_name, d.dist_name, 
			e.route_code, e.route_name 
			FROM tbl_customer a,tbl_area b,tbl_region c,tbl_distributor d,tbl_route e 
			WHERE a.region_code=c.region_id and a.area_code=b.area_id and a.dist_id=d.dist_id and a.route_id=e.route_id)SUBQ"." where 1 ".$queryString;
			
	
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`dist_name` like '" .$filterstr ."') or (`dist_code` like '" .$filterstr ."') or (`regarea` like '" .$filterstr ."') 
				or (`customer_name` like '" .$filterstr ."') or (`customer_cperson` like '" .$filterstr ."') or (`customer_address` like '" .$filterstr ."') 
				or (`customer_city` like '" .$filterstr ."') or (`customer_mobno` like '" .$filterstr ."') or (`customer_landno` like '" .$filterstr ."')";
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
  	if(isset($_GET["customer_name"]) && $_GET["customer_name"]<>''){ 
     $sql = "insert into `tbl_customer` (`customer_name`, `customer_address`,`customer_cperson`) values (" .sqlvalue(@$_GET["customer_name"], true).", " .sqlvalue(@$_GET["customer_address"], true)."," .sqlvalue(@$_GET["customer_cperson"], true).")";
     mysql_query($sql, $conn) or die(mysql_error());
  	}
}

function sql_update()
{
  	global $conn;
  	global $_POST;

  	$sql = "update `tbl_customer` set `customer_id`=" .sqlvalue(@$_POST["customer_id"], false).", `customer_name`=" .sqlvalue(@$_POST["customer_name"], true).", `customer_address`=" .sqlvalue(@$_POST["customer_address"], true)." where " .primarykeycondition();
  	mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  	global $conn;
	
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			//Outlet
			$strSQL = "DELETE FROM tbl_customer WHERE customer_id = '".$_POST["userbox"][$i]."'";
			$result = mysql_query($strSQL) or die(mysql_error());
			
		}  
	}  
	print("<script>history.go(-1);</script>");
}

function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`customer_id`";
  	if (@$_POST["xcustomer_id"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xcustomer_id"], false);
  	};
  	$pk .= ")";
  	return $pk;
}

/****************** End of the USER DEFINED Functions ***********************************/
?>
