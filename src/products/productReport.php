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

	if (isset($catId) && (int)$catId > 0) {
		$queryString = " and b.cat_code=$catId";
	} else {
		$queryString = '';
	}
	
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
				if (isset($catId)) $_SESSION["catId"] = $catId;

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
	global $catId;
	global $pagerange;
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$catId="";
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
	
	$categoryList = buildCategoryOptions($catId);
?>
	<form name="frmListProduct" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td align="left">Category : 
                    <select name="cboCategory" class="box" id="cboCategory" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/productReport.php?catId='+this.value)">
                        <option selected>All Category</option>
                            <?php echo $categoryList; ?>
                    </select>
 				</td>
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmProduct" id="frmProduct" action="products/productReport.php?a=del" method="post">
	

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/productReport.php?order=<?php echo "cat_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Category Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/productReport.php?order=<?php echo "prod_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/productReport.php?order=<?php echo "prod_sname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Short Name") ?>
                </a>
            </td>
           
		</tr>
	<?php
	$prev = "";
  	for ($i = $startrec; $i < $count; $i++)
  	{
    	$row = mysql_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
      <?php 
	  	if($prev != $row["cat_name"]){
			$prev = $row["cat_name"];
	  ?>
    	<td align="left">
 		     <?php echo htmlspecialchars(strtoupper($row["cat_name"])) ?>
        </td>
      <?php
	  	}else{
	  ?>
      	<td align="left">&nbsp;</td>
      <?php
	  	}
	  ?>
		<td align="left">
 		     <?php echo htmlspecialchars(strtoupper($row["prod_name"])) ?>
        </td>
        <td align="left">
           
		     <?php echo htmlspecialchars(strtoupper($row["prod_sname"])) ?>
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
		//showpagenav($page, $pagecount,$pagerange,'products/productReport.php'); 
} 
?></center>

<?php

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
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	
  		$sql = "SELECT a.cat_code,a.cat_name,b.prod_code,b.prod_name,b.prod_sname FROM tbl_categories a,tbl_product b WHERE 1 and a.cat_code=b.cat_code";
  	
	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`cat_name` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') or (`prod_name` like '" .$filterstr ."')"; 
	
  	}
  	
  	if (isset($order) && $order!='') {
		$sql .= " order by `" .sqlstr($order) ."`";
	}else{
		$sql .= " order by cat_code,prod_code";
	}
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
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "SELECT COUNT(*) FROM tbl_categories a,tbl_product b WHERE 1 and a.cat_code=b.cat_code";
  
    if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`cat_name` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') or (`prod_name` like '" .$filterstr ."')";  
	
  	}
  	//echo "SQL Count :".$sql."<br>";
  	$res = mysql_query($sql, $conn) or die(mysql_error());
  	$row = mysql_fetch_assoc($res);
  	reset($row);

	//echo  "RETURN ROW :".current($row);
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
