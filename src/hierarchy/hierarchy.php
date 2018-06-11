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
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo"){
	

	$user_name=$_SESSION['User_ID'];


  
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["hierId"])) $hierId = $_GET["hierId"];
	if(isset($_GET["dname"])) $dname = $_GET["dname"];

  
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($hierId) && isset($_SESSION["hierId"])) $hierId = $_SESSION["hierId"];
	//echo "Filter =".$filter.'<br>'."Filter Field=".$filter_field.'<br>';

	// echo "Region Id:".$queryString;
?>
	<html>
		<head>
			<title>mCRM -- Hierarchy Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
		</head>
		<body>
		<?php
  			require_once("../config.php");
			require_once("../library/functions.php");
			//$distributorList = buildDistributorOptions($distId);
  			if($pageId == "" or ((int) $pageId <= 0)){
  				$showrecs = REC_PER_PAGE;
  			}else{
  				$showrecs = $pageId;
  			}
  			$pagerange = PAGE_RANGE;

  			$a = @$_GET["a"];
  			$recid = @$_GET["recid"];
  			$page = @$_GET["page"];
			//echo "A =".$a;
  			if (!isset($page)) $page = 1;
  				
  				

  			if (isset($pageId)) $_SESSION["pageId"] = $pageId;
			if (isset($hierId)) $_SESSION["hierId"] = $hierId;
			//echo "Hier ID:".$hierId."=Status :".$a."<br>";
  			$hierarchyList = buildDesignationOptions($hierId);
		?>
        <form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Designation:</b>&nbsp;
				
                  <select name="cboDesignation">
					<option value="">Select Designation</option>
					<?php echo $hierarchyList; ?>
                  </select>
				  <input type="button" name="action" value="Add" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'hierarchy/hierarchy.php?a=Add&hierId='+document.frmFilter.cboDesignation.value)">
            </tr>
            
		</table>
        <br>
		</form>
        <form name="frmHierarchy" method="post" action="hierarchy/processHierarchy.php?action=add">
        	<table border="0" cellspacing="1" cellpadding="4" width="100%">
            
            <?php if($a == "Add"){ ?>
			<tr align="left">
            	<td class="hr" width='15%'><b>Hierarchy Name :</b></td>
                <td align='left'><input type="hidden" name="hname" id="hname" value="<?php echo $hierId; ?>">
        			<?php echo $hierId; ?>
            	</td>
            </tr>
            <tr><td class="hr" width="15%"><b>Status :</b></td><td>&nbsp;</td></tr>
            <tr align="left">
            	<td colspan="2">
                	<table>
                    
            	<?php
					$arrayData = array('Order Book'=>'YES','Physical Stock'=>'YES','Payment Details'=>'YES','Damage Return'=>'YES','Tag Order'=>'YES',
										'Tour Plan'=>'YES','New Outlet'=>'YES','DSR'=>'YES','Short Message'=>'YES','Tracking'=>'YES');
					$j=0;											
					foreach($arrayData as $key => $value){
						//echo $value;
					?>
                    	<tr><td>
                        <input type="hidden" name="statusdata[]" id="statusdata<?php echo $j;?>" value="<?php echo $key; ?>">
                    	<input name="userbox[]" type="checkbox" id="userbox<?php echo $j;?>"  value="<?php echo $key;?>" checked><?php echo $key; ?>
                        </td></tr>
                    <?php
					$j++;
					}
				?>
                	</table>
               </td>
           </tr>
           <tr>
           <td>
           		<input type="submit" name="btnAdd" id="btnAdd" value="Save">
                         
           </td>
           <?php } ?>
           </table>
        </form>
       
        
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
function readAllDatas()
{
	global $a;
  	global $showrecs;
  	global $page;
  	global $hierId;
	global $pagerange;
	
	
  	if ($a == "reset") {
    	$hierId ="";
		
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
	//echo "** Region ID =".$regionId;
	
	$hierarchyList = buildDesignationOptions($hierId);
?>
	
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="100%">
		<tr>
			<td class="hr">
            <a class="hr">
				<?php echo htmlspecialchars("Hierachy Name") ?>
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
		
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
		
		<td align="left">
				<?php echo htmlspecialchars(strtoupper($row["hname"])) ?>
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
		showpagenav($page, $pagecount,$pagerange,'hierarchy/hierarchy.php'); 
} 
 ?>
     </center>
<?php
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/
/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $regionId;
  global $regionList;
  
  if($areaId==0){
  	$areaId = $row["area_id"];
  }
 
  if($regionId==''){
  	$regionId = $row["region_id"];
  }
  $regionList = buildRegionOptions($regionId);
  $designId = $row["area_manager"];
  $areaList =  buildUserOptions($designId)
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Region Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<select name="region_id" class="box" id="region_id">
     				<option selected>All Regions</option>
						<?php echo $regionList; ?>
   				</select>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" name="area_name" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["area_name"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Description")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" id="area_desc1" name="area_desc1" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["area_desc1"])) ?>">
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

?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Code")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["area_name"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Area Description")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["area_desc1"])) ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?status=true')">
            </td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="area_validate" enctype="multipart/form-data" action="area/processArea.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"hname" => "",
   					"ob" => "",
  					"ps" => "",
					"pd" => "",
					"dr" => "",
					"to" => "",
					"tp" => "",
					"no" => "",
					"dsr" => "",
					"sm" => "",
					"track" => "",
					"hdate" => "",
					"status" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(areavalidate()==true){javascript: formget(this.form, 'area/processArea.php');}"></p>
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
	$areacode = $row["area_id"];
	$field="area_id";
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
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?a=edit&filter=<?php echo $row['area_id'] ?>&filter_field=<?php echo $field ?>')"></td>
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
	<form name="area_validate" enctype="multipart/form-data" action="area/processArea.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="area_id" value="<?php echo $row["area_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(areavalidate()==true){javascript: formget(this.form, 'area/processArea.php');}">
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
	<form action="area/area/processArea.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="area_id" value="<?php echo $row["area_id"] ?>">
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
	
  	$sql = "SELECT * FROM `tbl_hierarchy`";
		
  	
    //echo "<br>"."SQL :". $sql. "<br>";
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
	
  	
  	$sql = "SELECT COUNT(*) FROM `tbl_hierarchy`";
  	
	
  	
	//echo "<br>"."SQL :". $sql. "<br>";
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);
	
  	return current($row);
}



function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`area_name`";
  	if (@$_POST["xregion_name"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xregion_name"], false);
  	};
  	$pk .= ")";
  	return $pk;
}

/****************** End of the USER DEFINED Functions ***********************************/
?>
