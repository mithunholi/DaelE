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


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["regionId"])) $regionId = $_GET["regionId"];
	

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($regionId) && isset($_SESSION["regionId"])) $regionId = $_SESSION["regionId"];
	//echo "Filter =".$filter.'<br>'."Filter Field=".$filter_field.'<br>';

	
	
	// echo "Region Id:".$queryString;
?>
	<html>
		<head>
			<title>mCRM -- Route Screen</title>
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
				if (isset($regionId)) $_SESSION["regionId"] = $regionId;
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
	global $distributorList;
	global $distId;
	global $regionId;
	global $pagerange;
	
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$regionId ="";
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
	if($regionId==''){
  		$regionId = $row["region_id"];
  	}
  	$regionList = buildRegionOptions($regionId);
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
					<option value="<?php echo 'hname' ?>" <?php if ($filterfield == "area_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Hierarchy Name")?>
                    </option>
					
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'hierarchy/hierarchy1.php?a=filter')">
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=reset')"></td>
				
            </tr>
		
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmarea" id="frmarea" action="hierarchy/hierarchy1.php?a=del" method="post" onSubmit="return onDeletes();">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%"> 
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Hierarchy" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=add')">
				<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'hierarchy/processHierarchy.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?status=true');javascript:printHeader('Hierarchy Screen');}" >
                <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?pageId='+this.value)">
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

	
	
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="100%">
		<tr>
			<td class="hr" width="5%">
            	<input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);">
            </td>
			<td class="hr">
            <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?order=<?php echo "hname" ?>&type=<?php echo $ordtypestr ?>')">
				<?php echo htmlspecialchars("Hierarchy Name") ?>
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
		<td align="left" width="5%">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['area_id']; ?>">
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=view&filter=<?php echo $areacode ?>&filter_field=<?php echo $field ?>')">
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
		showpagenav($page, $pagecount,$pagerange,'hierarchy/hierarchy1.php'); 
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
    	<tr align="left">
            	<td class="hr" width='20%'><b>Hierarchy Name :</b></td>
                <td align='left'>
                	<input type="text" name="hname" id="hname" value="<?php echo $row["hname"]; ?>">
        			
            	</td>
        </tr>
		<tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["ob"]=="YES"){ ?>
						<input name="ob" type="checkbox" id="ob" checked>
					<?php
                    }else{
					?>
                    	<input name="ob" type="checkbox" id="ob" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Order Booking")."&nbsp;" ?></td>
		</tr>
		<tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["ob"]=="YES"){ ?>
						<input name="ob" type="checkbox" id="ps" checked>
					<?php
                    }else{
					?>
                    	<input name="ob" type="checkbox" id="ps" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Primary Stock")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["pd"]=="YES"){ ?>
						<input name="pd" type="checkbox" id="pd" checked>
					<?php
                    }else{
					?>
                    	<input name="pd" type="checkbox" id="pd" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Payment Details")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["dr"]=="YES"){ ?>
						<input name="dr" type="checkbox" id="dr" checked>
					<?php
                    }else{
					?>
                    	<input name="dr" type="checkbox" id="dr" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Damage Return")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["to"]=="YES"){ ?>
						<input name="to" type="checkbox" id="to" checked>
					<?php
                    }else{
					?>
                    	<input name="to" type="checkbox" id="to" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tag Order")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["tp"]=="YES"){ ?>
						<input name="tp" type="checkbox" id="tp" checked>
					<?php
                    }else{
					?>
                    	<input name="tp" type="checkbox" id="tp" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tour Plan")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["no"]=="YES"){ ?>
						<input name="no" type="checkbox" id="no" checked>
					<?php
                    }else{
					?>
                    	<input name="no" type="checkbox" id="no" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("New Outlook")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["dsr"]=="YES"){ ?>
						<input name="dsr" type="checkbox" id="dsr" checked>
					<?php
                    }else{
					?>
                    	<input name="dsr" type="checkbox" id="dsr" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("DSR")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["sm"]=="YES"){ ?>
						<input name="sm" type="checkbox" id="sm" checked>
					<?php
                    }else{
					?>
                    	<input name="sm" type="checkbox" id="sm" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Short Message")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["track"]=="YES"){ ?>
						<input name="track" type="checkbox" id="track" checked>
					<?php
                    }else{
					?>
                    	<input name="track" type="checkbox" id="track" checked>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tracking")."&nbsp;" ?></td>
		</tr>
        
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{

?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr align="left">
            	<td class="hr" width='20%'><b>Hierarchy Name :</b></td>
                <td align='left'>
                	<?php echo $row["hname"]; ?>
        			
            	</td>
        </tr>
		<tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["ob"]=="YES"){ ?>
						<input name="ob" type="checkbox" id="ob" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="ob" type="checkbox" id="ob" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Order Booking")."&nbsp;" ?></td>
		</tr>
		<tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["ob"]=="YES"){ ?>
						<input name="ob" type="checkbox" id="ps" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="ob" type="checkbox" id="ps" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Primary Stock")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["pd"]=="YES"){ ?>
						<input name="pd" type="checkbox" id="pd" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="pd" type="checkbox" id="pd" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Payment Details")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["dr"]=="YES"){ ?>
						<input name="dr" type="checkbox" id="dr" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="dr" type="checkbox" id="dr" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Damage Return")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["to"]=="YES"){ ?>
						<input name="to" type="checkbox" id="to" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="to" type="checkbox" id="to" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tag Order")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["tp"]=="YES"){ ?>
						<input name="tp" type="checkbox" id="tp" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="tp" type="checkbox" id="tp" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tour Plan")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["no"]=="YES"){ ?>
						<input name="no" type="checkbox" id="no" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="no" type="checkbox" id="no" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("New Outlook")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["dsr"]=="YES"){ ?>
						<input name="dsr" type="checkbox" id="dsr" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="dsr" type="checkbox" id="dsr" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("DSR")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["sm"]=="YES"){ ?>
						<input name="sm" type="checkbox" id="sm" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="sm" type="checkbox" id="sm" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Short Message")."&nbsp;" ?></td>
		</tr>
        <tr>
			
			<td class="dr" align="left">
            	<?php 
					if($row["track"]=="YES"){ ?>
						<input name="track" type="checkbox" id="track" checked disabled>
					<?php
                    }else{
					?>
                    	<input name="track" type="checkbox" id="track" checked disabled>
					<?php }?>
				
            </td>
            <td class="hr" align="left"><?php echo htmlspecialchars("Tracking")."&nbsp;" ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?status=true')">
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
   					"area_name" => "",
  					"area_desc1" => ""
		
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
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','hierarchy/hierarchy1.php?a=edit&filter=<?php echo $row['area_id'] ?>&filter_field=<?php echo $field ?>')"></td>
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
		<input type="hidden" name="id" value="<?php echo $row["id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="javascript: formget(this.form, 'hierarchy/processHierarchy.php?action=update');">
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
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT * FROM `tbl_hierarchy`";
		
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`hname` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
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
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT COUNT(*) FROM `tbl_hierarchy`";
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`hname` like '" .$filterstr ."')";
  	}
  	
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
