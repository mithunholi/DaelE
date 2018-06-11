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
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	
	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
	if (isset($_GET['customerId'])) $customerId = @$_GET["customerId"];
	
    if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	 
	

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($customerId) && isset($_SESSION["customerId"])) $customerId = $_SESSION["customerId"];
	
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	
	
	
	
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
				
  				mysqli_close($conn);
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
  	if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	
?>
	<form name="frmOutlet" id="frmOutlet" action="company/ginfo.php?status=true&a=del" method="post">
    <table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
    	
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Template" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=add&customerId=<?php echo $customerId ?>')">
            	<input type="button" name="btnDelete" value="Delete" onClick="if(onDeletes()==true){ javascript:formget(this.form,'company/processGInfo.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/ginfo.php?status=true');javascript:printHeader('General Terms Info');}" >
           		<input type="hidden" name="action" value="delete">
            	
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&pageId='+this.value)">
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
            <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&order=<?php echo "info_name" ?>&type=<?php echo $ordtypestr ?>')">
                <?php echo htmlspecialchars("Info Name") ?></a>
            </td>
        
            <td class="hr"><a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&order=<?php echo "info_desc" ?>&type=<?php echo $ordtypestr ?>')">
                <?php echo htmlspecialchars("Info Description") ?></a>
            </td>
		</tr>
	<?php
	$prevvalue="";
	$sno=1;
  	for ($i = $startrec; $i < $reccount; $i++)
  	{
    	$row = mysqli_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		$outcode = $row["info_id"];
		$field = "info_id";
			//echo "Outlet ID :".$outcode;
	?>
    
		<tr class="<?php echo $style ?>" style="cursor:pointer">
            <td align="left">
                <input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['info_id']; ?>">
            </td>
            <td><?php echo $sno; ?></td>
            
            <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
                    <?php echo strtoupper($row["info_name"]) ?>
             </td>
        
            <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=view&filter=<?php echo $outcode; ?>&filter_field=<?php echo $field ?>')">
                    <?php echo htmlspecialchars($row["info_desc"]) ?>
            </td>
            
       </tr>
       
	
	<?php
		
		$sno++;
  	}//for loop
  	mysqli_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form><center>
	<?php 
		showpagenav($page, $pagecount,$pagerange,'company/ginfo.php'); 
} 
 ?></center>
 <?php

/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
 
  if($iseditmode==false)
  {
  	 
  	$mode=false;
	$aa = "add";
	
	

 }
 else
 {
 	$mode=true;
	$aa = "edit";
	
	
 }
 
	

?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
        <tr>
			<td class="hr"><?php echo htmlspecialchars("Template Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" class="uppercase" name="info_name" id="info_name" size="30" maxlength="30" value="<?php echo $row["info_name"] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
        </tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("General Terms Description")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<?php if($row["info_desc"] != ""){ ?>
            	<textarea name="info_desc" class="uppercase" id="info_desc" cols="60" rows="10"><?= $row["info_desc"] ?></textarea>
                <?php }else{ ?>
                <textarea name="info_desc" class="uppercase" id="info_desc" cols="60" rows="10"></textarea>
                <?php } ?>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
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
 
 
  
 
  
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="80%">
    	
		<tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("Template Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo strtoupper(htmlspecialchars($row["info_name"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("General Terms Desc")."&nbsp;" ?></td>
			<td class="dr" align="left"><textarea id="idesc" name="idesc" rows="10" cols="60" readonly><?= htmlspecialchars($row["info_desc"]) ?></textarea></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                	<input type="button" name="btnNext" value="Next Record" 
                    	onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')">
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="customer_validate" enctype="multipart/form-data" action="company/processGInfo.php?status=true&action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
         <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"info_name" => "",
  					"info_desc" => ""
					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(infovalidate()==true){javascript: formget(this.form, 'company/processGInfo.php');}"></p>
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
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
	$customercode = $row["info_id"];
	$field="info_id";
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
			<td><input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true&a=edit&filter=<?php echo $customercode ?>&filter_field=<?php echo $field ?>')"></td>
		</tr>
	</table>
	<?php
  		mysqli_free_result($res);
	} 
/****************** End of the VIEW RECORD Function ***********************************/


/*********************** START EDIT RECORD Function ***********************************/
function editrec($recid)
{
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
  	showrecnav("edit", $recid, $count);
?>
	<br>
	<form name="company_validate" enctype="multipart/form-data" action="company/processGInfo.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="info_id" value="<?php echo $row["info_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(infovalidate()==true){javascript: formget(this.form, 'company/processGInfo.php');}">
        </p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php
	mysqli_free_result($res);
} 
/****************** End of the EDIT RECORD Function ***********************************/

/****************** ***START DELECT RECORD Function ***********************************/
function deleterec($recid)
{
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
  	showrecnav("del", $recid, $count);
?>
	<br>
	<form action="company/processGInfo.php?&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="info_id" value="<?php echo $row["info_id"] ?>">
		<?php showrow($row, $recid) ?>
		<p><input type="submit" name="action" value="Confirm"></p>
	</form>
<?php
  mysqli_free_result($res);
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
  	
	
	$sql = "SELECT * FROM tbl_template WHERE 1 ".$queryString;
			
	
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`info_name` like '" .$filterstr ."')";
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
	}else{
		$sql .= " order by info_name";
	}
	
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    ///echo "SQL :".$sql."<br>";
	
  	$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	
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
  	
	$sql = "SELECT count(*) FROM tbl_template WHERE 1 ".$queryString;
			
	
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`info_name` like '" .$filterstr ."')";
  	}
	
  	//echo "SQL1 :".$sql."<br>";
	
  	$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
  	$row = mysqli_fetch_assoc($res);
  	reset($row);
	
	//echo  "RETURN ROW :".current($row);
  	return current($row);
	
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
