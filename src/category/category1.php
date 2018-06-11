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
	$_SESSION["cattypeId"]="";
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo"){
	

	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];
  	if (isset($_GET["cattypeId"])) $cattypeId = @$_GET["cattypeId"];
	
  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($cattypeId) && isset($_SESSION["cattypeId"])) $cattypeId = $_SESSION["cattypeId"];
	
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
			<title>mCRM -- Category Screen</title>
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
				if (isset($cattypeId)) $_SESSION["cattypeId"] = $cattypeId;
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
	global $pagerange;
	global $cattypeId;
	
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
  	if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	$categoryTypeList = buildCategoryTypeOptions($cattypeId);
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
                    <option value="<?php echo "cat_type" ?>"<?php if ($filterfield == "cat_type") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Category Type")?>
                    </option>
					<option value="<?php echo "cat_name" ?>"<?php if ($filterfield == "cat_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Category Name")?>
                    </option>
					<option value="<?php echo "cat_description" ?>"<?php if ($filterfield == "cat_description") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Category Description") ?>
                    </option>
                    <option value="<?php echo "cat_tax" ?>"<?php if ($filterfield == "cat_tax") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Tax Percentage") ?>
                    </option>
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'category/category.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=reset')">
                </td>
		     </tr>
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmCategory" id="frmCategory" action="category/category.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Category" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=add')">
            	<input type="button" name="btnDelete" value="Delete" onClick="if(onDeletes()==true){ javascript:formget(this.form,'category/processCategory.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?status=true');javascript:printHeader('Category Admin');}" >
                    <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            <td align="right">View Category in : 
    			<select name="cboCategoryType" class="box" id="cboCategoryType" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?cattypeId='+this.value)">
     				<option selected>All Category Type</option>
						<?php echo $categoryTypeList; ?>
   				</select>
 			</td>
		</tr>
	</table>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?order=<?php echo "cat_type" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Category Type") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?order=<?php echo "cat_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Category Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?order=<?php echo "cat_description" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Category Description") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?order=<?php echo "cat_tax" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Tax Percentage") ?>
                </a>
            </td>

		</tr>
	<?php
	$prev="";
  	for ($i = $startrec; $i < $reccount; $i++)
  	{
    	$row = mysql_fetch_assoc($res);
    	$style = "dr";
		
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		$catCode = $row["cat_code"];
		$catField = "cat_code";
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['cat_code']; ?>">
        </td>
        <?php
		   if($prev != $row["cat_type"]){
		   		$prev = $row["cat_type"];
		?>
        	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=view&recid=<?php echo $i ?>')">
        		<?php echo htmlspecialchars(strtoupper($row["cat_type"])) ?>
        	</td>
        <?php
			}else{
			
		?>
        	<td>&nbsp;</td>
         <?php } ?>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=view&recid=<?php echo $i ?>')">
        	<?php echo htmlspecialchars(strtoupper($row["cat_name"])) ?>
        </td>
        	
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars(strtoupper($row["cat_description"])) ?>
        </td>
        <td align="center" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["cat_tax"]) ?>
        </td>		
	</tr>
	<?php
  	}//for loop
  	mysql_free_result($res);
	?>
     <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'category/category.php'); 
} 
 ?></center>
 <?php
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/

/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $cattypeId;
  if($cattypeId==0){
  	$cattypeId = $row["cat_type"];
  }
  $categoryTypeList = buildCategoryTypeOptions($cattypeId);
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Type")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<select name="cat_type" class="uppercase" id="cat_type">
     				<option selected value="0">All Category Type</option>
						<?php echo $categoryTypeList; ?>
   				</select>
            	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" name="cat_name" class="uppercase" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["cat_name"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Description")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" name="cat_description" class="uppercase" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["cat_description"])) ?>">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Tax Percentage")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" name="cat_tax" size="10" maxlength="10" value="<?php echo str_replace('"', '&quot;', trim($row["cat_tax"])) ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
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
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["cat_name"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Description")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["cat_description"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Tax Percentage")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["cat_tax"]) ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="category" enctype="multipart/form-data" action="category/processCategory.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <input type="hidden" name="action" value="add">
		<?php
			$row = array(
					"cat_type" => "",
   					"cat_name" => "",
  					"cat_description" => "",
					"cat_tax" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(cat_validate()==true){javascript: formget(this.form, 'category/processCategory.php');}"></p>
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
			<td>
            <input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?a=edit&recid=<?php echo $recid ?>')">
            </td>
			
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
	<form name="category" enctype="multipart/form-data" action="category/processCategory.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["cat_code"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(cat_validate()==true){javascript: formget(this.form, 'category/processCategory.php');}">
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
	<form action="category/processCategory.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["cat_code"] ?>">
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
	global $cattypeId;
	
	if($cattypeId != "" or $cattypeId != 0){
		$qry = " and a.cat_type = '$cattypeId'";
	}
	
  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT a.cat_code, b.cat_type, a.cat_name, a.cat_description, a.cat_tax FROM tbl_categories a, tbl_category_type b where a.cat_type=b.id ".$qry;
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`cat_type` like '" .$filterstr ."') or (`cat_name` like '" .$filterstr ."') or (`cat_description` like '" .$filterstr ."') 
							or (`cat_tax` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!='') {
		$sql .= " order by `" .sqlstr($order) ."`";
	}else{
		$sql .= " order by cat_type,cat_code";
	}
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
   //echo $sql;
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
	global $cattypeId;
	
	if($cattypeId != "" or $cattypeId != 0){
		$qry = " and a.cat_type = '$cattypeId'";
	}

  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT COUNT(*) FROM tbl_categories a,tbl_category_type b where a.cat_type=b.id ".$qry;
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`cat_type` like '" .$filterstr ."') or (`cat_name` like '" .$filterstr ."') or (`cat_description` like '" .$filterstr ."') 
					or (`cat_tax` like '" .$filterstr ."')";
  	}
  
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);

  	return current($row);
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
