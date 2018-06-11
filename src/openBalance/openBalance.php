<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["catId"]="";
	$_SESSION["pageId"]="";
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo"){
	

	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
    if(isset($_GET["catId"])) $catId = $_GET["catId"];

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
    if (!isset($catId) && isset($_SESSION["catId"])) $catId = $_SESSION["catId"];
	if (!isset($prodId) && isset($_SESSION["prodId"])) $prodtId = $_SESSION["prodId"];
	
	if (isset($catId) && (int)$catId > 0) {
		$queryString = "cat_code=$catId";
	} else {
		$queryString = '';
	}
	
	
?>
	<html>
		<head>
			<title>mCRM -- Opening Balance Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script language="javascript" type="text/javascript" src="../CRM.js"></script>
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
  				if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;
				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				if (isset($catId)) $_SESSION["catId"] = $catId;
				if (isset($prodId)) $_SESSION["prodId"] = $prodId;
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
    global $catId;
	global $prodId;
	global $pagerange;
	
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$catId = "";
		$prodId = "";
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
	$categoryList = buildCategoryOptions($catId);
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" class="uppercase" name="filter" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
                    <option value="<?php echo "id" ?>"<?php if ($filterfield == "id") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("ID")?>
                    </option>
					<option value="<?php echo "prod_name" ?>"<?php if ($filterfield == "prod_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Product Name")?>
                    </option>
					
				 </select>
               
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'openBalance/openBalance.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=reset')">
                </td>
		     </tr>
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmopenBalance" id="frmopenBalance" action="openBalance/openBalance.php?a=del" method="post" onSubmit="return onDeletes();">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Opening Stock" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=add')">
             <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'openBalance/processOpenBalance.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?status=true')}" >
           		<input type="hidden" name="action" value="delete">
           </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?pageId='+this.value)">
				<option value="<?php echo $showrecs; ?>"><?php echo $showrecs; ?></option>
				<option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
             </select>
			</td>
            <td align="right">View products in : 
    			<select name="cboCategory" class="box" id="cboCategory" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?catId='+this.value)">
     				<option selected>All Category</option>
						<?php echo $categoryList; ?>
   				</select>
 			</td>
		</tr>
	</table>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?order=<?php echo "id" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("ID") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?order=<?php echo "prod_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?order=<?php echo "prod_qty" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Qty") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?order=<?php echo "rec_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Date") ?>
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
		$catCode = $row["id"];
		$catField = "id";
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['id']; ?>">
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=view&recid=<?php echo $i ?>')">
        	<?php echo htmlspecialchars($row["id"]) ?>
        </td>
        	
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["prod_name"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["prod_qty"]) ?>
        </td>		
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=view&recid=<?php echo $i ?>')">
			<?php echo date("d-m-Y",strtotime($row["rec_date"])) ?>
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
		showpagenav($page, $pagecount,$pagerange,'openBalance/openBalance.php'); 
} 
 ?></center>
 <?php

/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $a;
 //  echo "a =".$a. " EditMode =".$iseditmode."<br>";
  if($iseditmode==false)
  {
  	global $catId;
  	global $prodId;
   // echo "False"."<br>";
	$aa="add";
	$mode=false;
    if($catId == ""){
  	 $catId = "";
  	}
  	$categoryList = buildCategoryOptions($catId);
  
  	if($prodId == ""){
  		$prodId = "";
  	}
  	$productList = buildProductOptions($catId,$prodId);
	$pqty = 0;
 }else{
 	//echo "True"."<br>";
 	$aa="edit";
	$mode=true;
 	$catId = $row["cat_code"];
	$prodId = $row["prod_name"];
	$categoryList = buildCategoryOptions($catId);
	$productList = buildProductOptions($catId,$prodId);
 }
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr> 
   			<td width="150" class="hr">Category</td>
   				<td align="left"> 
                	<select name="cboCategory" id="cboCategory" class="box" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=<?php echo $aa; ?>&iseditmode=<?php echo $mode; ?>&catId='+this.value)">
     					<option value="" selected>-- Choose Category --</option>
						<?php
							echo $categoryList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<select name="prod_name" id="prod_name" class="box">
     					<option value="" selected>-- Choose Product --</option>
						<?php
							echo $productList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Qty")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" name="prod_qty" size="10" maxlength="10" value="<?php echo str_replace('"', '&quot;', trim($row["prod_qty"])) ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
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
  //echo "Cat code :".$row["cat_code"]."<br>";
  $sqlQuery = "select a.cat_name from tbl_categories a,tbl_product b where a.cat_code = b.cat_code and b.cat_code =".$row["cat_code"];
  $res = mysql_query($sqlQuery);
  if(mysql_num_rows($res)>0){
  	$resRow = mysql_fetch_assoc($res);
	$catname = $resRow["cat_name"]; 
  }else{
  	$catname="";
  }
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
        <tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($catname) ?></td>
		</tr>
		<tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["prod_name"]) ?></td>
		</tr>
		<tr align="left">
			<td class="hr"><?php echo htmlspecialchars("Product Qty")."&nbsp;" ?></td>
			<td class="dr"><?php echo htmlspecialchars($row["prod_qty"]) ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="open_stock" enctype="multipart/form-data" action="openBalance/processopenBalance.php?action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <input type="hidden" name="action" value="add">
		<?php
			$row = array(
					"catId" => "",
   					"prod_name" => "",
  					"prod_qty" => "",
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(openstock_validate()==true){javascript: formget(this.form, 'openBalance/processOpenBalance.php');}"></p>
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
            <input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','openBalance/openBalance.php?a=edit&recid=<?php echo $recid ?>')">
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
	<form name="open_stock" enctype="multipart/form-data" action="openBalance/processopenBalance.php?action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["id"] ?>">
		<?php showroweditor($row, true); ?>
		<p><input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(openstock_validate()==true){javascript: formget(this.form, 'openBalance/processopenBalance.php');}"></p>
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
	<form action="openBalance/processopenBalance.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["id"] ?>">
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
  	global $queryString;

  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT `id`, `cat_code`,`prod_name`, `prod_qty`, `rec_date` FROM `tbl_openbalance`";
  	
	if(isset($queryString) && $queryString!=''){
		$sql .= " WHERE $queryString";
	}elseif (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`id` like '" .$filterstr ."') or (`prod_name` like '" .$filterstr ."') or (`prod_qty` like '" .$filterstr ."') or (`rec_date` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
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
  	global $queryString;

  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  		$sql = "SELECT COUNT(*) FROM `tbl_openbalance`";
  
    if(isset($queryString) && $queryString!=''){
		$sql .= " WHERE $queryString";
	}elseif (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`id` like '" .$filterstr ."') or (`prod_name` like '" .$filterstr ."') or (`prod_qty` like '" .$filterstr ."') or (`rec_date` like '" .$filterstr ."')";
  	}
  
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);

  	return current($row);
}


function sql_delete()
{
  	global $conn;
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_openbalance WHERE id = '".$_POST["userbox"][$i]."'";
			$resultset = mysql_query($strSQL) or die(mysql_error());
		}  
	}  
	print("<script>history.go(-1);</script>");
  	//$sql = "delete from `tbl_categories` where " .primarykeycondition();
  	//mysql_query($sql, $conn) or die(mysql_error());
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
