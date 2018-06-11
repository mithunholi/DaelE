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
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	
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
		$queryString = " and cat_code=$catId";
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
				if (isset($catId)) $_SESSION["catId"] = $catId;

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
	global $categoryList;
	global $catId;
	global $pagerange;
	//echo "Show Rec :".$showrecs."<br>";
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
  	if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	
	$categoryList = buildCategoryOptions($catId);
?>
	<form name="frmListProduct" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
                    <option value="<?php echo "part_code" ?>"<?php if ($filterfield == "part_code") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Part Number")?>
                    </option>
					<option value="<?php echo "prod_name" ?>"<?php if ($filterfield == "prod_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Product Name")?>
                    </option>
				
                    <option value="<?php echo "prod_price" ?>"<?php if ($filterfield == "prod_price") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Product Price") ?>
                    </option>
                    <option value="<?php echo "prod_tax" ?>"<?php if ($filterfield == "prod_tax") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Tax") ?>
                    </option>
                    <option value="<?php echo "prod_discount" ?>"<?php if ($filterfield == "prod_discount") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Discount") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'products/product.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmProduct" id="frmProduct" action="products/product.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="Add New Product" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=add&catId=<?php echo $catId ?>')">
            <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'products/processProduct.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true');javascript:printHeader('Products Admin');}" >
                    <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            <td align="right">View products in : 
    			<select name="cboCategory" class="box" id="cboCategory" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&catId='+this.value)">
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
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "part_code" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Part Number") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_sname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Short Name") ?>
                </a>
            </td>
			
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_price" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Purchased Price") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_price1" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Selling Price") ?>
                </a>
            </td>
             <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "unit_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Unit") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_obal_qty" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product OB") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_qty" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Available Qty") ?>
                </a>
            </td>
            <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_tax" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Tax(%)") ?>
                </a>
            </td>
			<td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&order=<?php echo "prod_discount" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Discount(%)") ?>
                </a>
            </td>
          
		</tr>
	<?php
	$prev="";
  	for ($i = $startrec; $i < $reccount; $i++)
  	{
    	$row = mysqli_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		if($prev != $row["cat_name"]){
			$prev =$row["cat_name"];
	?>
    	<tr class='srhighlight2'>
        <td align='left' colspan="11">
        	<?php echo $row["cat_name"]; ?>
        </td>
        </tr>
    <?php
		}
		if($row["prod_name"] != ""){
		$pcode = $row["prod_code"];
		$field = "prod_code";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['prod_code']; ?>">
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["part_code"])) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["prod_name"])) ?>
           	
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["prod_sname"])) ?>
           	
        </td>
		
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_price"],2,'.',',') ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_price1"],2,'.',',') ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo $row["unit_name"] ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo $row["prod_obal_qty"] ?>
            
        </td>
          <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo $row["prod_qty"] ?>
            
        </td>
         <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_tax"],2,'.',',') ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_discount"],2,'.',',') ?>
            
        </td>
     
	</tr>
	<?php
		}
  	}//for loop
  	mysqli_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'products/product.php'); 
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
  global $catId;
  global $unitId;
$res = sql_select();
  	
  	$row = mysqli_fetch_assoc($res);

  
  if($catId==0){
  	$catId = $row["cat_code"];
  }
  $categoryList = buildCategoryOptions($catId);
  if($unitId==0){
  	$unitId = $row["unit_code"];
  }
  $unitList = buildUnitOptions($unitId);
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
    	<tr> 
   			<td width="150" class="hr" align="left">Category</td>
   				<td align="left"> 
                	<select name="cboCategory" id="cboCategory" class="box">
     					<option value="" selected>-- Choose Category --</option>
						<?php
							echo $categoryList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Part Number")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="part_code" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["part_code"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_name" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prod_name"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Short Name")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" class="uppercase" name="prod_sname" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prod_sname"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Description")."&nbsp;" ?></td>
            <td class="dr" align="left">
            <?php if($row["prod_description"] !=""){ ?>
            <textarea name="prod_description" cols="45" rows="5"><?=$row["prod_description"]?></textarea>
            <?php }else{ ?>
            <textarea name="prod_description" cols="45" rows="5"></textarea>
            <?php } ?>
            <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr> 
   			<td width="150" class="hr" align="left">Unit</td>
   				<td align="left"> 
                	<select name="cboUnit" id="cboUnit" class="box">
     					<option value="" selected>-- Choose Unit --</option>
						<?php
							echo $unitList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Opening Balance")."&nbsp;" ?>
            </td>
        <?php if($row["prod_obal_qty"] == "0" or $row["prod_obal_qty"] == ""){ ?>
        
			<td class="dr" align="left"><input type="text" name="prod_obal_qty" maxlength="10" value="<?php echo $row["prod_obal_qty"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
         
        <?php }else{ ?>
        	<td class="dr" align="left"><input type="text" name="prod_obal_qty" maxlength="10" value="<?php echo $row["prod_obal_qty"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')" readonly>
        <?php } ?>
        	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
        </tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Purchase Price")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" name="prod_price" maxlength="10" value="<?php echo $row["prod_price"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Selling Price")."&nbsp;" ?>
            </td>
			<td class="dr" align="left"><input type="text" name="prod_price1" maxlength="10" value="<?php echo $row["prod_price1"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
     
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Tax")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" name="prod_tax" maxlength="10" value="<?php echo $row["prod_tax"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Discount")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" name="prod_discount" maxlength="10" value="<?php echo $row["prod_discount"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
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
  /*$sqlQuery = "select a.cat_name from tbl_categories a,tbl_product b where a.cat_code = b.cat_code and b.cat_code =".$row["cat_code"];
  $res = mysqli_query($sqlQuery);
  if(mysqli_num_rows($res)>0){
  	$resRow = mysqli_fetch_assoc($res);
	$catname = $resRow["cat_name"]; 
  }else{
  	$catname="";
  }*/
  
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["cat_name"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Part Number")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["part_code"])) ?></td>
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
			<td class="hr" align="left"><?php echo htmlspecialchars("Unit Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["unit_name"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Opening Balance")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo $row["prod_obal_qty"] ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Available Qty")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo $row["prod_qty"] ?></td>
		</tr>
      
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Purchase Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_price"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Selling Price")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_price1"],2,'.',',') ?></td>
		</tr>
       
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Tax")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_tax"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Discount")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo number_format($row["prod_discount"],2,'.',',') ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="products" enctype="multipart/form-data" action="products/processProduct.php?status=true&action=add" method="post">
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"part_code" => "",
   					"prod_name" => "",
					"prod_sname" => "",
  					"prod_description" => "",
					"prod_price" => "",
					"prod_price1" => "",
					"prod_obal_qty" => "",
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
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
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
            	<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true&a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                
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
	<form name="products" enctype="multipart/form-data" action="products/processProduct.php?status=true&action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["prod_code"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(prod_validate()==true){javascript: formget(this.form, 'products/processProduct.php');}">
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
	<form action="products/processProduct.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["prod_code"] ?>">
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
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	
  	$sql = "select * from (select a.*, b.cat_name, c.unit_name 
			from tbl_product a,tbl_categories b,tbl_unit c 
			where a.cat_code=b.cat_code and a.unit_code=c.unit_code) subq where 1";

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`part_code` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."') or (`prod_tax` like '" .$filterstr ."') 
					or (`prod_discount` like '" .$filterstr ."')"; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by cat_code,prod_code";
	}
	
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
  //echo "SQL : ".$sql."<br>";
  	$res = mysqli_query($conn, $sql) or die(mysqli_error());
	
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
  	
	$sql = "select count(*) from (select a.* from tbl_product a,tbl_categories b,tbl_unit c where a.cat_code=b.cat_code and a.unit_code=c.unit_code)subq where 1";
  
    if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`part_code` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."')  
					or (`prod_tax` like '" .$filterstr ."') or (`prod_discount` like '" .$filterstr ."')"; 
	
  	}
  	//echo "SQL Count :".$sql."<br>";
  	$res = mysqli_query($conn, $sql) or die(mysqli_error());
  	$row = mysqli_fetch_assoc($res);
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
