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
	
	if(isset($_GET["menutype"])) $menutype = $_GET["menutype"];

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	
	if (!isset($menutype) && isset($_SESSION["menutype"])) $menutype = $_SESSION["menutype"];
	
	
?>
	<html>
		<head>
			<title>mCRM -- Product Screen</title>
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
  				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				
				if (isset($menutype)) $_SESSION["menutype"] = $menutype;

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
	global $categoryList;
	global $menutype;
	global $pagerange;
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
	
  	}

  	$checkstr = "";
  	
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
	
	$url = "invoice/primary.php?status=true&menutype=$menutype";
	//echo "URL :".$url."<br>";
?>
	<form name="frmListProduct" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" class="uppercase" name="filter" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
					<option value="<?php echo "invno" ?>"<?php if ($filterfield == "invno") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Invoice Number")?>
                    </option>
					
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'invoice/primary.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmProduct" id="frmProduct" action="invoice/primary.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <?php if($count<=0){ ?>
            	<input type="button" name="action" value="Add New Inovice No" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=add')">
            <?php } ?>
			<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'invoice/processPrimary.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>')}" >
           		<input type="hidden" name="action" value="delete">
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?pageId='+this.value)">
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
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?order=<?php echo "prefix" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Invoice Prefix") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?order=<?php echo "invno" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Invoice No") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?order=<?php echo "cdate" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Last Updated Date") ?>
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
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['id']; ?>">
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars($row["prefix"]) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars($row["invno"]) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=view&recid=<?php echo $i ?>')">
			
				<?php echo date("d-m-Y",strtotime($row["cdate"])) ?>
           
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
		showpagenav($page, $pagecount,$pagerange,'invoice/primary.php'); 
} 
?></center>
<?php
 
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/

/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $categoryList;

 
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr>
			<td class="hr"><?php echo htmlspecialchars("Invoice Prefix")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prefix" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prefix"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("Invoice Number")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="invno" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["invno"])) ?>">
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
			<td class="hr"><?php echo htmlspecialchars("Invoice Prefix")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["prefix"]) ?></td>
		</tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("Invoice Number")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["invno"]) ?></td>
		</tr>
		
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/




/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
 //echo "Record Id :".$recid. " Count=".$count."<br>";
 global $menutype;
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?status=true&menutype=<?php echo $menutype ?>')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
 global $menutype;
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?status=true&menutype=<?php echo $menutype ?>')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="invoice" enctype="multipart/form-data" action="invoice/processPrimary.php?action=add&type=<?php echo $menutype; ?>" method="post">
		<p>
        	<input type="hidden" name="action" value="add">
            <input type="hidden" name="type" value="<?php echo $menutype; ?>">
            <input type="hidden" name="sql" value="insert"></p>
		<?php
			$row = array(
   					"prefix" => "",
  					"invno" => "" 
					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(invoice_validate()==true){javascript: formget(this.form, 'invoice/processPrimary.php');}"></p>
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
	$fieldvalue = $row["id"];
	$field="id";
	//echo "ViewRec : Rec ID :".$recid." Count=".$count."<br>";
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
            	<input type="button" name="btnAdd" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?a=edit&filter=<?php echo $fieldvalue ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                
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
	global $menutype;
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
  	showrecnav("edit", $recid, $count);
?>
	<br>
	<form name="invoice" enctype="multipart/form-data" action="invoice/processPrimary.php method="post">
    	<input type="hidden" name="action" value="update">
        <input type="hidden" name="type" value="<?php echo $menutype; ?>">
       	<input type="hidden" name="sql" value="update">
		<input type="hidden" name="id" value="<?php echo $row["id"] ?>">
		<?php showroweditor($row, true); ?>
		<p><input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(invoice_validate()==true){javascript: formget(this.form, 'invoice/processPrimary.php');}"></p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php
	mysql_free_result($res);
} 
/****************** End of the EDIT RECORD Function ***********************************/

/****************** ***START DELECT RECORD Function ***********************************/
function deleterec($recid)
{
	global $menutype;
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
  	showrecnav("del", $recid, $count);
?>
	<br>
	<form action="invoice/processPrimary.php?action=delete&type=<?php echo $menutype; ?>" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="id" value="<?php echo $row["id"] ?>">
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
	global $menutype;
	
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	
  	$sql = "SELECT * FROM `tbl_invoice` WHERE invtype='$menutype' ";
  	
	
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`invno` like '" .$filterstr ."') or (`prefix` like '" .$filterstr ."')";
  	}
  	
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    //echo "SQL : ".$sql."<br>";
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
	global $menutype;
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "SELECT COUNT(*) FROM `tbl_invoice` WHERE invtype='$menutype'";
  
    if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`invno` like '" .$filterstr ."') or (`prefix` like '" .$filterstr ."')";
  	}
  	//echo "SQL Count :".$sql."<br>";
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
  	if(isset($_GET["prod_name"]) && $_GET["prod_name"]<>''){ 
     $sql = "insert into `tbl_product` (`prod_name`, `prod_description`) values (" .sqlvalue(@$_GET["cat_name"], true).", " .sqlvalue(@$_GET["cat_description"], true).")";
     mysql_query($sql, $conn) or die(mysql_error());
  	}
}

function sql_update()
{
  	global $conn;
  	global $_POST;

  	$sql = "update `tbl_categories` set `cat_code`=" .sqlvalue(@$_POST["cat_code"], false).", `cat_name`=" .sqlvalue(@$_POST["cat_name"], true).", `cat_description`=" .sqlvalue(@$_POST["cat_description"], true)." where " .primarykeycondition();
  	mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  	global $conn;

  	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_invoice WHERE id = '".$_POST["userbox"][$i]."'";
			$resultset = mysql_query($strSQL) or die(mysql_error());
		}  
	}  
	print("<script>history.go(-1);</script>");
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
