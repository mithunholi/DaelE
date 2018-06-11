<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["leadId"]="";
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
	if(isset($_GET["leadId"])) $leadId = $_GET["leadId"];

  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($leadId) && isset($_SESSION["leadId"])) $leadId = $_SESSION["leadId"];

	if (isset($leadId) && (int)$leadId > 0) {
		$queryString = " and lead_code=$leadId";
	} else {
		$queryString = '';
	}
	
?>
	<html>
		<head>
			<title>mCRM -- Lead Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
           
	
		</head>
		<body>
		<?php
  			require_once("../config.php");
			require_once("../library/functions.php");
			//echo "Rec Per Page :".REC_PER_PAGE."<br>";
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
				if (isset($leadId)) $_SESSION["leadId"] = $leadId;

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
	global $leadId;
	global $pagerange;
	//echo "Show Rec :".$showrecs."<br>";
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$leadId="";
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
	
	//$categoryList = buildCategoryOptions($leadId);
?>
	<form name="frmListProduct" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
					<option value="<?php echo "cust_name" ?>"<?php if ($filterfield == "cust_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Customer Name")?>
                    </option>
					<option value="<?php echo "prod_description" ?>"<?php if ($filterfield == "prod_description") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Product Description") ?>
                    </option>
                    <option value="<?php echo "ref_by" ?>"<?php if ($filterfield == "ref_by") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Reference") ?>
                    </option>
                    <option value="<?php echo "follow_up_date" ?>"<?php if ($filterfield == "follow_up_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Follow Up Date") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'lead/lead.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="lead/lead.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="Add New Lead" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=add&leadId=<?php echo $leadId ?>')">
            <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'lead/processLead.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?status=true');javascript:printHeader('Lead Admin');}" >
                    <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "cust_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Customer Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "cat_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Category Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "ref_by" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Reference By") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "cust_require" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Customer Require") ?>
                </a>
            </td>
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "follow_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Follow Up Date") ?>
                </a>
            </td>
			<td class="hr">
          		<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?order=<?php echo "remark" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Remark") ?>
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
		if($prev != $row["cat_name"]){
			$prev =$row["cat_name"];
	?>
    	<tr class='srhighlight2'>
        <td align='left' colspan="9">
        	<?php echo $row["cat_name"]; ?>
        </td>
        </tr>
    <?php
		}
		if($row["cust_name"] != ""){
		$leadid = $row["lead_d"];
		$field = "lead_id";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
       <td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['lead_id']; ?>">
      </td>
	  <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["cust_name"])) ?>
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_date"])) ?>
      </td>
	  <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&filter=<?php echo $leadid ?>&filter_field=<?php echo $field ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["ref_by"])) ?>
      </td>
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&filter=<?php echo $leadid ?>&filter_field=<?php echo $field ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["cust_require"])) ?>
      </td>
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
      		<?php echo date("d-m-Y",strtotime($row["follow_date"])) ?>
      </td>
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
      		<?php echo htmlspecialchars(($row["remark"])) ?>
      </td>
    
	</tr>
	<?php
		}
  	}//for loop
  	mysql_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'lead/lead.php'); 
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
  global $leadId;
  global $cattypeId;
  
  if($leadId==0){
  	$leadId = $row["cat_code"];
  }
  $categoryList = buildCategoryOptions($leadId);
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="cust_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["cust_name"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" name="lead_date" size="12" maxlength="12" value="<?php echo date("d-m-Y",strtotime($row["lead_date"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Requirement Description")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_desc" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prod_desc"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Reference By")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="ref_by" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["ref_by"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
    </table>
    <br>
    <table>
   		<tr><td colspan="8" align="right">Add Product</td></tr>
      	<tr>
        	<td>Category Name</td>
            <td>Product Name</td>
            <td>Description</td>
            <td>Quantity</td>
            <td>Price Per Unit</td>
            <td>Tax</td>
            <td>Discount</td>
            <td>Amount</td>
        </tr>
        <tr>
        	<td class="dr" align="left"><input type="text" class="uppercase" name="cat_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["cat_name"])) ?>">
            </td>
		   	<td class="dr" align="left"><input type="text" class="uppercase" name="prod_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prod_name"])) ?>">
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_description" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["prod_description"])) ?>">
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_qty" size="10" maxlength="10" value="<?php echo str_replace('"', '&quot;', trim($row["prod_qty"])) ?>">
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_price" size="15" maxlength="15" value="<?php echo number_format($row["prod_price"],2,'.',',') ?>">
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_tax" size="10" maxlength="10" value="<?php echo number_format($row["prod_tax"],2,'.',',') ?>">
            </td>
            <td class="dr" align="left"><input type="text" class="uppercase" name="prod_discount" size="15" maxlength="15" value="<?php echo number_format($row["prod_discount"],2,'.',',') ?>">
            </td>
             <td class="dr" align="left"><input type="text" class="uppercase" name="prod_amount" size="15" maxlength="15" value="<?php echo number_format($row["prod_discount"],2,'.',',') ?>">
            </td>
		</tr>
        
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
  $sqlQuery = "select a.cat_name from tbl_categories a,tbl_product b where a.cat_code = b.cat_code and b.cat_code =".$row["cat_code"];
  $res = mysql_query($sqlQuery);
  if(mysql_num_rows($res)>0){
  	$resRow = mysql_fetch_assoc($res);
	$catname = $resRow["cat_name"]; 
  }else{
  	$catname="";
  }
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($catname)) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["prod_name"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Short Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["prod_sname"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Description")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["prod_description"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Primary Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_price"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Secondary Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_price1"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Special Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_price2"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Discount")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_discount"],2,'.',',') ?></td>
		</tr>
        
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Discount Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_actual_price"],2,'.',',') ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="products" enctype="multipart/form-data" action="products/processProduct.php?action=add" method="post">
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
   					"prod_name" => "",
					"prod_sname" => "",
  					"prod_description" => "",
					"prod_price" => "",
					"prod_price1" => "",
					"prod_price2" => "",
					"prod_discount" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(prod_validate()==true){javascript: formget(this.form, 'products/processProduct.php');}"></p>
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
	$prodcode = $row["prod_code"];
	$field="prod_code";
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
            	<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                
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
	<form name="products" enctype="multipart/form-data" action="products/processProduct.php?action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["prod_code"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(prod_validate()==true){javascript: formget(this.form, 'products/processProduct.php');}">
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
	<form action="products/processProduct.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["prod_code"] ?>">
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
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	
  	$sql = "select * from (select a.prod_code, a.cat_code, a.prod_name, a.prod_sname, a.prod_description, a.prod_price, a.prod_price1, a.prod_price2, a.prod_discount, 
			(a.prod_price - (a.prod_price * (a.prod_discount /100))) prod_actual_price,b.cat_name 
			from tbl_product a,tbl_categories b 
			where a.cat_code=b.cat_code) subq where 1";

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') or (`prod_description` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."') or (`prod_price2` like '" .$filterstr ."') 
					or (`prod_discount` like '" .$filterstr ."')"; 
	
  	}
  	
  	if (isset($order) && $order!=''){
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
  	
	$sql = "select count(*) from (select a.* from tbl_product a,tbl_categories b where a.cat_code=b.cat_code)subq where 1";
  
    if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') or (`prod_description` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."') or (`prod_price2` like '" .$filterstr ."') 
					or (`prod_discount` like '" .$filterstr ."')"; 
	
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
