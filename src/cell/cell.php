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
	$status = false;
  }
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	

	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];

	if (isset($_GET['catId']) && (int)$_GET['catId'] >= 0) {
		$catId = (int)$_GET['catId'];
		$queryString = "&catId=$catId";
	} else {
		$catId = 0;
		$queryString = '';
	}
?>
	<html>
		<head>
			<title>mCRM -- Cell Info Screen</title>
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
	  					//sql_insert();
      					break;
    				case "update":
      					//sql_update();
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
					//echo "Rec id :".$recid."<br>";
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
  				if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;
				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
  				mysqli_close($conn);
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
	global $pagerange;

  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
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
  	if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" class="uppercase" name="filter" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
					<option value="<?php echo "cell_id" ?>"<?php if ($filterfield == "cell_id") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Cell ID")?>
                    </option>
					<option value="<?php echo "loc_name" ?>"<?php if ($filterfield == "loc_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Location Name") ?>
                    </option>
                    <option value="<?php echo "city" ?>"<?php if ($filterfield == "city") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("City") ?>
                    </option>
                    <option value="<?php echo "region" ?>"<?php if ($filterfield == "region") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Region") ?>
                    </option>
				 </select>
                <input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'cell/cell.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=reset')">
                </td>
		     </tr>
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmCell" id="frmCell" action="cell/cell.php?status=true&a=del" method="post" onSubmit="return onDeletes();">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Cell ID" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=add')">
               <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'cell/processCell.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&status=true'); javascript:printHeader('Cell Info Admin');}" >
           	   <input type="hidden" name="action" value="delete">
            </td>
            <div id="output" style="color: blue;"></div>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
		</tr>
	</table>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&order=<?php echo "cell_id" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Cell ID") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&order=<?php echo "loc_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Location Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&order=<?php echo "city" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("City") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&order=<?php echo "region" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Region") ?>
                </a>
            </td>

		</tr>
	<?php
  	for ($i = $startrec; $i < $reccount; $i++)

  	{
    	$row = mysqli_fetch_assoc($res);
    	$style = "dr";
		
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		$catCode = $row["cell_id"];
		$catField = "cell_id";
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['cell_id']; ?>">
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=view&recid=<?php echo $i ?>')">
        	<?php echo htmlspecialchars($row["cell_id"]) ?>
        </td>
        	
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["loc_name"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["city"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["region"]) ?>
        </td>
        		
	</tr>
	<?php
  	}//for loop
  	mysqli_free_result($res);
	?>
     <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'cell/cell.php'); 
} 
 ?></center>
 <?php
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/

/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Cell ID")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="hidden" name="old_cell_id" value="<?php echo $row['cell_id'] ?>">
            	<input type="text" class="uppercase" name="cell_id" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["cell_id"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Location Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" name="loc_name" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["loc_name"])) ?>">
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" name="city" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["city"])) ?>">
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Region")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" name="region" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["region"])) ?>">
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
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
		<tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Cell ID")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["cell_id"]) ?></td>
		</tr>
		<tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Location Name")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["loc_name"]) ?></td>
		</tr>
		<tr align="left">
			<td class="hr"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["city"]) ?></td>
		</tr>
        <tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Region")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["region"]) ?></td>
		</tr>
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/




/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
//echo "Record Id :".$recid. " Count=".$count."<br>";
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="cell" enctype="multipart/form-data" action="cell/processCell.php?status=true&action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <input type="hidden" name="action" value="add">
		<?php
			$row = array(
   					"cell_id" => "",
  					"loc_name" => "",
					"city" => "",
					"region" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(cell_validate()==true){javascript: formget(this.form, 'cell/processCell.php');}"></p>
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
			<td>
            <input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true&a=edit&recid=<?php echo $recid ?>')">
            </td>
			
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
	<form name="cell" enctype="multipart/form-data" action="cell/processCell.php?status=true&action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["id"] ?>">
		<?php showroweditor($row, true); ?>
		<p><input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(cell_validate()==true){javascript: formget(this.form, 'cell/processCell.php');}"></p>
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
	<form action="cell/processCell.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["id"] ?>">
		<?php showrow($row, $recid) ?>
		<p><input type="submit" name="action" value="Confirm"></p>
	</form>
<?php
  mysqli_free_result($conn,$res);
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

  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT * FROM `tbl_cell`";
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`cell_id` like '" .$filterstr ."') or (`loc_name` like '" .$filterstr ."') or (`city` like '" .$filterstr ."') or (`region` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
  //echo "SQL : ".$sql."<br>";
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

  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT COUNT(*) FROM `tbl_cell`";
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`cell_id` like '" .$filterstr ."') or (`loc_name` like '" .$filterstr ."')";
  	}
    //	echo "SQL Count :".$sql."<br>";
  	$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
  	$row = mysqli_fetch_assoc($res);
  	reset($row);
	
  	return current($row);
}


function sql_delete()
{
  	global $conn;
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_cell WHERE cell_id = '".$_POST["userbox"][$i]."'";
			$resultset = mysqli_query($conn,$strSQL) or die(mysqli_error($conn));
		}  
	}  
	print("<script>history.go(-1);</script>");
  	//$sql = "delete from `tbl_categories` where " .primarykeycondition();
  	//mysqli_query($sql, $conn) or die(mysqli_error());
}
function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`cat_code`";
  	if (@$_POST["xcat_code"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xcat_code"], false);
  	};
  	$pk .= ")";
  	return $pk;
}

/****************** End of the USER DEFINED Functions ***********************************/
?>
