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
	$_SESSION["regionId"]="";
	$_SESSION["areaId"]="";
	$_SESSION["routeId"]="";
	$_SESSION["dstatus"]="";
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	

	$user_name=$_SESSION['User_ID'];
	if(isset($_GET["ddstatus"])) $dd = @$_GET["ddstatus"];
	//echo "DD Status :".$dd;
  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["areaId"])) $areaId = $_GET["areaId"];
	if(isset($_GET["regionId"])) $regionId = $_GET["regionId"];
	if(isset($_GET["routeId"])) $routeId = $_GET["routeId"];
	if(isset($_GET["dstatus"])) $dstatus = $_GET["dstatus"];

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($areaId) && isset($_SESSION["areaId"])) $areaId = $_SESSION["areaId"];
	if (!isset($regionId) && isset($_SESSION["regionId"])) $regionId = $_SESSION["regionId"];
	if (!isset($routeId) && isset($_SESSION["routeId"])) $routeId = $_SESSION["routeId"];
	if (!isset($dstatus) && isset($_SESSION["dstatus"])) $dstatus = $_SESSION["dstatus"];
	 
	//echo "Region Code :".$regionId. " Area Code :".$areaId."<br>";
	if($_GET["a"] <> "edit"){
		if(isset($dstatus) && $dstatus <> ''){
			$queryString = " and dstatus ='$dstatus' ";
		}else{
			$queryString = "";
		}
		
		//Region
		if ($regionId <> '' and $regionId <> '0') {
			$queryString .= " and region_code='$regionId' ";
		} 
		
		//Area
		if($regionId <> '' and $regionId <> '0'){
			if($areaId <> '' and $areaId <> '0'){
				$queryString .= " and area_code like '$areaId%' ";
			}
		}
		
		//Route
		if($routeId <> '' and $routeId <> '0'){
			$queryString .= " and route_id like '$routeId'";
		}
	}
?>
	<html>
		<head>
			<title>mCRM -- Distributor Screen</title>
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
			$button = @$_POST["undo"];
  			$recid = @$_GET["recid"];
  			$page = @$_GET["page"];
			//echo "Button Status :".$a."<br>";
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
						if($button=="Delete"){
      						sql_delete();
						}
      					break;
					case "undo":
						if($button=="Undo"){
					    	sql_undo();
						}elseif($button=="Delete"){
							sql_delete();
						}
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
				if (isset($areaId)) $_SESSION["areaId"] = $areaId;
				if (isset($regionId)) $_SESSION["regionId"] = $regionId;
				if (isset($routeId)) $_SESSION["routeId"] = $routeId;
				if (isset($dstatus)) $_SESSION["dstatus"] = $dstatus;
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
	global $areaId;
	global $regionId;
	global $routeId;
	global $dstatus;
	global $pagerange;

  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$areaId ="";
		$regionId = "";
		$routeId = "";
		$dstatus="false";
		$queryString="";
		$_SESSION["filter"]="";
		$_SESSION["filter_field"]="";
		$_SESSION["regionId"] = "";
		$_SESSION["areaId"] ="";
		$_SESSION["routeId"] ="";
		$_SESSION["dstatus"]="";
  	}

  	$checkstr = "";
  	if ($wholeonly) $checkstr = " checked";
  	if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	if ($count % $showrecs != 0) {
    	$pagecount = intval($count / $showrecs) + 1;
  	}
  	else {
    	$pagecount = intval($count / $showrecs);
  	}
  	$startrec = $showrecs * ($page - 1);
  	if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	
	
	$regionList = buildRegionOptions ($regionId);
	
	if($regionId=='0' or $regionId==''){
		$regionId = '';
		$areaId = '';
		$routeId = '';
	}
	
	$distId1 = $regionId;
	
  	if($areaId == ""){
  		//$areaId = $row["area_code"];
    }
	if($regionId <> "0" and $regionId <>""){
	    $areaList = buildAreaOptions($regionId,$areaId);
	}
	
	if($dstatus == ''){
		$dstatus = $row["dstatus"];
	}
	if($dstatus == 'false'){ 
		$aa = 'del';
	} else{
		$aa='undo';
   }
?>
	<form name="frmFilter" action="" method="post">
		<table border="1" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
        	<tr>
          		<td align="left" width="15%"><b>Region :</b> </td>
          		<td align="left" width="35%">
          		<select name="cboRegion" class="box" id="cboRegion" 
                		onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?&regionId='+this.value)">
     					<option value="0" selected>All Region</option>
							<?php echo $regionList; ?>
   				</select>
 		  		</td>
          		<td align="left" width="15%"><b>Area :</b> </td>
          		<td align="left" width="15%">
          		<select name="cboArea" class="box" id="cboArea" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?areaId='+this.value)">
                	<option value="0" selected>All Area</option>
							<?php echo $areaList; ?>
   				</select>
  		  		</td> 
              
          	</tr>
			<tr>
				<td align="left" width="15%"><b>Custom Filter</b>&nbsp;</td>
                <td colspan="5" align="left">
				<input type="text" class="uppercase" name="filter" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
                    	
					<option value="<?php echo "dist_name" ?>"<?php if ($filterfield == "dist_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Distributor Name")?>
                    </option>
					<option value="<?php echo "dist_cperson" ?>"<?php if ($filterfield == "dist_cperson") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Contact Person") ?>
                    </option>
                    <option value="<?php echo "dist_mobno" ?>"<?php if ($filterfield == "dist_mobno") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Mobile Number") ?>
                    </option>
				 </select>
                
				
			<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'distributor/distributor.php?a=filter')" >
			<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?status=true&dstatus=false')">					
            </td>
            </tr>
		
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmDistributor" id="frmDistributor" action="distributor/distributor.php?a=<?php echo $aa; ?>&ddstatus=<?php echo $dstatus; ?>" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="Add New Distributor" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=add')">
           <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'distributor/processDistributor.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?status=true&dstatus=false');javascript:printHeader('Distributor Admin');}" >
           <input type="hidden" name="action" value="delete">
           </td>
                        
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
           
		</tr>
	</table>

	
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?order=<?php echo "regarea" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Region&Area Code") ?>
                </a>
            </td>
       		
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?order=<?php echo "dist_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Distributor Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?order=<?php echo "dist_cperson" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Contact Person") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?order=<?php echo "dist_mobno" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Mobile Number") ?>
                </a>
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
		$distCode = strtoupper($row["dist_id"]);
		$distField = "dist_code";
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
		<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['dist_id']; ?>">
        </td>
      
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=view&recid=<?php echo $i ?>')">
        	<?php echo htmlspecialchars(strtoupper($row["regarea"])) ?>
        </td>
     
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=view&recid=<?php echo $i ?>')">
        	<?php echo htmlspecialchars(strtoupper($row["dist_name"])) ?>
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars(strtoupper($row["dist_cperson"])) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["dist_mobno"]) ?>
        </td>
   </tr>
	<?php
  	}//for loop
  	mysql_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'distributor/distributor.php'); 
} 
 ?></center>
<?php

/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $regionId;
  global $areaId;
  global $routeId;
  
  if($iseditmode==false)
  {
  	 $mode=false;
	 $aa = "add";
	 $regionList = buildRegionOptions ($regionId);
  	 if($regionId=='0' or $regionId==''){
		$regionId = '';
	 }
	 $distId1 = $regionId;
	// echo "Region Id :".$regionId." Area Id :".$areaId."<br>";
	 $areaList = buildAreaOptions1($regionId,$areaId);
	 if($regionId=='' or $areaId == '0' or $areaId==''){
		$areaId = '';
	 }
	 $hierarchyList=getHierarchyList($aa);
	 
  }else{
  	$mode=true;
	$aa = "edit";
	if($regionId == "0" or $regionId == ""){
  	 	$regionId = $row["region_code"];
  	}
  	$regionList = buildRegionOptions($regionId);
	if($regionId <> "0" or $regionId<>""){
  		if($areaId == "0" or $areaId == ""){
  			$areaId = $row["area_code"];
  		}
  		$areaList = buildAreaOptions1($regionId,$areaId);
	}
	$hierarchyList = getHierarchyList($aa);
  }
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="100%">
    	
        <tr>
		  <td class="hr" align="left"><?php echo htmlspecialchars("Region Code")."&nbsp;" ?></td>
          <td class="dr" align="left">
          	<select name="region_code" class="box" id="region_code" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&regionId='+this.value)">
     			<option selected>All Regions</option>
					<?php echo $regionList; ?>
   			</select>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
          </td>
       </tr>
       <tr>
       	<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
        <td class="dr" align="left">
        	<select name="area_code" class="box" id="area_code">
     			<option selected>All Area</option>
					<?php echo $areaList; ?>
   			</select>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
        </td>
       </tr>
       
       <tr>
       		<td class="hr" align="left"><?php echo htmlspecialchars("Distributor Name")."&nbsp;" ?></td>
            <td class="dr"  align="left">
             <input type="text" class="uppercase" name="dist_name" maxlength="50" size="50" value="<?php echo str_replace('"', '&quot;', trim($row["dist_name"])) ?>">
            	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
        	 <td class="hr" align="left"><?php echo htmlspecialchars("Hierarchy")."&nbsp;" ?></td>
             <td class="dr" align="left"><table><tr><?php echo $hierarchyList; ?></tr></table></td>
        </tr>
         <tr>
       		<td class="hr" align="left"><?php echo htmlspecialchars("TIN Number")."&nbsp;" ?></td>
            <td class="dr"  align="left">
             <input type="text" class="uppercase" name="dist_tin" maxlength="20" size="20" value="<?php echo str_replace('"', '&quot;', trim($row["dist_tin"])) ?>">
            	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
       		<td class="hr" align="left"><?php echo htmlspecialchars("Address")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<textarea name="dist_address" id="dist_address" class="uppercase" cols="50" rows="4">
				<?php 
					//echo str_replace('"', '&quot;', trim($row["dist_address"])) 
					echo nl2br($row["dist_address"]); 
				?>
                </textarea>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
            
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="dist_city" name="dist_city" maxlength="50" size="50" value = "<?php echo nl2br($row["dist_city"]) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Pin/Zip Code")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="dist_pin" name="dist_pin" maxlength="20" size="20" value = "<?php echo nl2br($row["dist_pin"]) ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("State")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="dist_state" name="dist_state" maxlength="20" size="20" value = "<?php echo nl2br($row["dist_state"]) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="dist_cperson" name="dist_cperson" maxlength="50" size="50" value = "<?php echo $row["dist_cperson"] ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="dist_email1" name="dist_email1" maxlength="50" size="50" value = "<?php echo $row["dist_email1"] ?>">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="dist_email2" name="dist_email2" maxlength="50" size="50" value = "<?php echo $row["dist_email2"] ?>">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="dist_mobno" name="dist_mobno" maxlength="20" size="20" value = "<?php echo $row["dist_mobno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" id="dist_landno" name="dist_landno" maxlength="20" size="20" value = "<?php echo $row["dist_landno"] ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
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
	$regionname = getRegionName($row["region_code"]);
	$areaname = getAreaName($row["area_code"]);
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Region Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($regionname) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($areaname) ?></td>
		</tr>
       
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Distributor Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["region_name"].$row["area_name"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Distributor Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_name"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Address")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_address"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_city"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Pin/Zip Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_pin"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("State")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_state"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email1")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_email1"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email2")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_email2"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_mobno"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Land No")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_landno"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("TIN No")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["dist_tin"]) ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?status=true&dstatus=false')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?status=true&dstatus=false')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="distributor_validate" enctype="multipart/form-data" action="distributor/processDistributor.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"region_code" => "",
					"area_code" => "",
					"dist_code" => "",
					"route_code" => "",
					"route_name" => "",
   					"dist_name" => "",
  					"dist_address" => "",
					"dist_city" => "",
					"dist_pin" => "",
					"dist_state" => "",
					"dist_cperson" => "",
					"dist_mobno" => "",
					"dist_landno" =>"",
					"dist_tin" =>"",
					"dist_email1" =>"",
					"dist_email2" =>""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(distributorvalidate()==true){javascript: formget(this.form, 'distributor/processDistributor.php');}"></p>
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
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?a=edit&recid=<?php echo $recid ?>')"></td>
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
	<form name="distributor_validate" enctype="multipart/form-data" action="distributor/processDistributor.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="dist_id" value="<?php echo $row["dist_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(distributorvalidate()==true){javascript: formget(this.form, 'distributor/processDistributor.php');}">
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
	<form action="distributor/processDistributor.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="dist_id" value="<?php echo $row["dist_id"] ?>">
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
	
	//echo "Mode :".$a."<br>";
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	
	/*$sql = "SELECT * FROM (SELECT concat(concat(c.region_name,'',b.area_name),'-',b.area_desc1) regarea, c.region_id, b.area_id, c.region_name, b.area_name,
				a.route_id, a.route_code, a.route_name, a.route_address FROM tbl_route a,tbl_area b,tbl_region c 
				WHERE 1 and a.region_code = b.region_id and a.area_code = b.area_id and a.region_code = c.region_id)SUBQ WHERE 1 ". $queryString;*/
	//echo "Query String :".$queryString."<br>";
	$sql = "SELECT * FROM (SELECT concat(c.region_name,'',b.area_name) dist_code, concat(concat(c.region_name,'',b.area_name),'-',b.area_desc1) regarea, a.*, 
			c.region_name,b.area_name 
			FROM tbl_distributor a,tbl_area b,tbl_region c 
			WHERE a.region_code = b.region_id and a.area_code=b.area_id and a.region_code = c.region_id)SUBQ"." where 1 ".$queryString;
  	
	if(isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`regarea` like '" .$filterstr ."') or (`area_code` like '" .$filterstr ."') or (`dist_code` like '" .$filterstr ."') or (`dist_name` like '" .$filterstr ."') or (`dist_cperson` like '" .$filterstr ."') or (`dist_mobno` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
	}else{
		$sql .= " order by dist_id ";
	}
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
   //echo "SQL :".$sql."<BR>";
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
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	/*$sql = "SELECT count(*) FROM (SELECT concat(concat(a.region_code,'',a.area_code),'-',b.area_desc1) regarea,a.* FROM tbl_distributor a,tbl_area b WHERE a.region_code = b.region_id and a.area_code=b.area_name )SUBQ". " where 1 ".$queryString;*/
	
	$sql = "SELECT count(*) FROM (SELECT concat(c.region_name,'',b.area_name) dist_code,concat(concat(c.region_name,'',b.area_name),'-',b.area_desc1) regarea, a.*, 
			c.region_name,b.area_name 
			FROM tbl_distributor a,tbl_area b,tbl_region c 
			WHERE a.region_code = b.region_id and a.area_code=b.area_id and a.region_code = c.region_id)SUBQ"." where 1 ".$queryString;
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`regarea` like '" .$filterstr ."') or (`area_code` like '" .$filterstr ."') or (`dist_code` like '" .$filterstr ."') or (`dist_name` like '" .$filterstr ."') or (`dist_cperson` like '" .$filterstr ."') or (`dist_mobno` like '" .$filterstr ."')";
  	}
    //echo "SQL 1:".$sql."<BR>";
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);
	
  	return current($row);
}


function sql_undo()
{
  	global $conn;
    global $userdata;
	
	
  	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$queryset = "SELECT * from tbl_distributor where dist_code in (SELECT dist_code FROM tbl_distributor WHERE dist_id = '".$_POST["userbox"][$i]."') and dstatus='false'";
			//echo $queryset.'<br>';
			$rowset = mysql_query($queryset) or die(mysql_error());
			if(mysql_num_rows($rowset)>0){
				print("<script>alert('Cannot undo this data. Bcaz Data Already Exists');</script>");
			}else{
				$strSQL = "UPDATE tbl_distributor SET dstatus='false' WHERE dist_id = '".$_POST["userbox"][$i]."'";
				$resultset = mysql_query($strSQL) or die(mysql_error());
			}
		}  
	}
	print("<script>history.go(-1);</script>");
}

function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`dist_id`";
  	if (@$_POST["xdist_id"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xdist_id"], false);
  	};
  	$pk .= ")";
  	return $pk;
}


function getHierarchyList($estatus){
	$hier_sql = "select * from tbl_design where levels='LEVEL1'";
	
	//echo "SQL :".$reg_sql."<br>";
	$hier_query = mysql_query($hier_sql);
	$data="";
	$j=0;
	while($hier_row = mysql_fetch_assoc($hier_query)){
		$hid = $hier_row["id"];
		$hdata = $hier_row["design_name"];
		$vdata = $hid."-".$hdata;
		if($estatus == "edit"){
			$sqldata = "select * from tbl_distributor a,tbl_hier b where a.dist_id=b.dist_id and b.hname='$hdata'";
		//echo "SQL DATA :".$sqldata."<br>";
			$resultdata = mysql_query($sqldata);
			if(mysql_num_rows($resultdata)>0){
				$data .= "<td><input type='checkbox' name='userbox[]' id='userbox<?php echo $j; ?>' value='$vdata' checked>$hdata</td>";
			}else{
				$data .= "<td><input type='checkbox' name='userbox[]' id='userbox<?php echo $j; ?>' value='$vdata'>$hdata</td>";
			}
		}else{
			$data .= "<td><input type='checkbox' name='userbox[]' id='userbox<?php echo $j; ?>' value='$vdata'>$hdata</td>";
		}
		$j++;
	}
	return $data;
}
/****************** End of the USER DEFINED Functions ***********************************/
?>
