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
	$_SESSION["partno"]="";
	$_SESSION["recstatus"]="";
	$_SESSION["catId"]= "";
	$_SESSION["suppId"]= "";
	$_SESSION["pcode"]= "";
	$_SESSION["pqty"]= "";
	$_SESSION["pprice"]= "";
	$_SESSION["recordstatus"]= "";
	$partno="0";
	$catId = "0";
	$pcode= "0";
	$cname = "";
	$pname = "";
	$prate = "0.00";
	$ptax = "0";
	$suppId = "0";
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
	if(isset($_GET["suppId"])) $suppId = $_GET["suppId"];
	if(isset($_GET["suppStatus"])) $suppStatus = $_GET["suppStatus"];
	if(isset($_GET["pcode"])) $pcode = $_GET["pcode"];
	if(isset($_GET["pqty"])) $pqty = $_GET["pqty"];
	if(isset($_GET["pprice"])) $pprice = $_GET["pprice"];
	if(isset($_GET["ptax"])) $ptax = $_GET["ptax"];
	if(isset($_GET["pdiscount"])) $pdiscount = $_GET["pdiscount"];
	if(isset($_GET["eqty"])) $editedqty = $_GET["eqty"];
	if(isset($_GET["etprice"])) $editedprice = $_GET["etprice"];
	if(isset($_GET["ettax"])) $editedtax = $_GET["ettax"];
	if(isset($_GET["etdiscount"])) $editeddiscount = $_GET["etdiscount"];
	if(isset($_GET["recordstatus"])) $recordstatus = $_GET["recordstatus"];
	if(isset($_GET["recordid"])) $recordid = $_GET["recordid"];
	if(isset($_GET["partno"])) $partno = $_GET["partno"];
	
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($catId) && isset($_SESSION["catId"])) $catId = $_SESSION["catId"];
	if (!isset($suppId) && isset($_SESSION["suppId"])) $suppId = $_SESSION["suppId"];
	if (!isset($suppStatus) && isset($_SESSION["suppStatus"])) $suppStatus = $_SESSION["suppStatus"];
	
	if (!isset($pcode) && isset($_SESSION["pcode"])) $pcode = $_SESSION["pcode"];
	if (!isset($pqty) && isset($_SESSION["pqty"])) $pqty = $_SESSION["pqty"];
	if (!isset($pprice) && isset($_SESSION["pprice"])) $pprice = $_SESSION["pprice"];
	//if (!isset($qstatus) && isset($_SESSION["qstatus"])) $qstatus = $_SESSION["qstatus"];
	

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
			$emode = @$_GET["editmode"];
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
					case "suppadd":
						addSupplier($emode);
						break;
					case "categoryadd":
						addCategory($emode);
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
				if (isset($suppId)) $_SESSION["suppId"] = $suppId;
				if (isset($pcode)) $_SESSION["pcode"] = $pcode;
				if (isset($pqty)) $_SESSION["pqty"] = $pqty;
				if (isset($pprice)) $_SESSION["pprice"] = $pprice;
			//	if (isset($qstatus)) $_SESSION["qstatus"] = $qstatus;
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
  	if ($startrec < $count) {mysql_data_seek($res, $startrec);}
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
					<option value="<?php echo "prod_description" ?>"<?php if ($filterfield == "prod_description") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Description") ?>
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
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'purchase/purchase.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmProduct" id="frmProduct" action="purchase/purchase.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="New Purchase" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=add&catId=<?php echo $catId ?>')">
            <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'purchase/processPurchase.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true');javascript:printHeader('Products Admin');}" >
                    <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            <td align="right">View products in : 
    			<select name="cboCategory" class="box" id="cboCategory" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?catId='+this.value)">
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
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "part_code" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Part Number") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_sname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Short Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_description" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Description") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_price" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Purchased Price") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_price1" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Selling Price") ?>
                </a>
            </td>
           
            <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_tax" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Tax(%)") ?>
                </a>
            </td>
			<td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?order=<?php echo "prod_discount" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Discount(%)") ?>
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
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["part_code"])) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["prod_name"])) ?>
           	
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["prod_sname"])) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&recid=<?php echo $i ?>')">
			
				<?php echo htmlspecialchars(strtoupper($row["prod_description"])) ?>
           
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_price"],2,'.',',') ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_price1"],2,'.',',') ?>
            
        </td>
        
         <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_tax"],2,'.',',') ?>
            
        </td>
        <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=view&filter=<?php echo $pcode ?>&filter_field=<?php echo $field ?>')">
        	
				<?php echo number_format($row["prod_discount"],2,'.',',') ?>
            
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
		showpagenav($page, $pagecount,$pagerange,'purchase/purchase.php'); 
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
  global $cattypeId;
  global $suppStatus;
  global $suppId;
  global $recstatus;
  global $ccode;
  global $pcode;
  global $recordstatus;
  global $pqty;
  global $pprice;
  global $ptax;
  global $pdiscount;
  global $editedqty;
  global $editedtax;
  global $editeddiscount;
  global $editedprice;
  global $recordid;
  global $searchtext;
  global $partno;
  if($suppId == "0" or $suppId == ""){
  	$suppId = $row["supp_id"];
  }
  //echo "Supplier ID :".$suppId."<br>";
  $supplierList = buildSupplierList($suppId);
  
  if($suppId != 0 && $suppStatus!='newadd'){
  	if($catId==0){
  		$catId = $row["cat_code"];
  	}

  	$categoryList = buildCategoryOptions($catId);
  }else{
  	if($catId==0){
  		$catId = $row["cat_code"];
  	}

  	$categoryList = buildCategoryOptions($catId);
  }
  //echo "Edit Mode :".$iseditmode."<br>";
  if($iseditmode != '1'){
  	$a='add';
  }else{
  	$a='edit';
  }
  if($catId != "" and $catId != "0"){
  	if($pcode == "" or $pcode == "0"){
  		$pcode = $row["prod_code"];
  	}
	//echo "Product Code:".$pcode."<br>";
	//if($pcode != "newProduct"){
	  	$productList = buildProductList($pcode,$catId,$searchtext);
	//}
  }
 
  $prate=0;
  $tax=0;
  //echo "Part No :".$partno."<br>";
  if ($partno !="" and $partno != "0" and isset($partno)){
  	//echo "TRUE"."<br>";
  	$qstring = "select c.supp_id, a.cat_name, b.cat_code, b.part_code, b.prod_code, b.prod_name, b.prod_sname, b.prod_price, b.prod_discount, 
					b.prod_description, b.prod_price1, b.prod_tax,b.prod_qty 
					from tbl_categories a,tbl_product b,tbl_supplier c where a.cat_code=b.cat_code and b.supp_id=c.supp_id and b.part_code='$partno'";
	//echo "Qry :".$qstring."<br>";
	$resultset = mysql_query($qstring,$conn);
	if(mysql_num_rows($resultset)>0){
		//echo "TRUE"."<br>";
		$rowdata = mysql_fetch_assoc($resultset);
		$catcode = $rowdata["cat_code"];
		$pcode= $rowdata["prod_code"];
		$cname = $rowdata["cat_name"];
		$pname = $rowdata["prod_name"];
		$suppId  = $rowdata["supp_id"];
		$row["part_code"] = $rowdata["part_code"];
		$row["prod_sname"] = $rowdata["prod_sname"];
		$row["prod_description"] = $rowdata["prod_description"];
		$row["prod_price"] = $rowdata["prod_price"];
		$row["prod_price1"] = $rowdata["prod_price1"];
		$row["prod_tax"] = $rowdata["prod_tax"];
		$row["prod_discount"]  = $rowdata["prod_discount"];
		$row["prod_qty"]=$rowdata["prod_qty"];

		
	}else{
		//echo "FALSE"."<br>";
		$row["part_code"] = $partno;
		$row["prod_sname"] = "";
		$row["prod_description"] = "";
		$row["prod_price"] = "0.00";
		$row["prod_price1"] = "0.00";
		$row["prod_tax"] = "0";
		$row["prod_discount"]  = "0";
		$row["prod_qty"]="0";
		$suppId ="0";
		$pcode=="newProduct";
		$recordstatus="new";
		$catcode="0";
		$searchtext="";
	}
		$supplierList = buildSupplierList($suppId);
		$categoryList = buildCategoryList($catcode);
		//if($pcode!="newProduct"){
			$productList = buildProductList($pcode,$catcode,$searchtext);
		//}
  }else{
  	//echo "PCode :".$pcode."====CatCode :".$catcode."<br>";
	if($pcode=="newProduct"){
		$row["part_code"] = "0";
		$row["prod_sname"] = "";
		$row["prod_description"] = "";
		$row["prod_price"] = "0.00";
		$row["prod_price1"] = "0.00";
		$row["prod_qty"]="0";
		$row["prod_tax"] = "0";
		$row["prod_discount"]  = "0";
		$recordstatus="new";
	}else{
  		if(($pcode != "" and $pcode != "0") and ($catId != "" and $catId !="0")){
			$qstring = "select * from tbl_product where cat_code='$catId' and prod_code='$pcode'";
			//echo "Query String :".$qstring."<br>";
			
			$qry = mysql_query($qstring,$conn);
			$rowset = mysql_fetch_assoc($qry);
			$row["part_code"] = $rowset["part_code"];
			$row["prod_sname"] = $rowset["prod_sname"];
			$row["prod_description"] = $rowset["prod_description"];
			$row["prod_qty"] = $rowset["prod_qty"];
			$row["prod_price"] = $rowset["prod_price"];
			$row["prod_price1"] = $rowset["prod_price1"];
			$row["prod_tax"] = $rowset["prod_tax"];
			$row["prod_discount"]  = $rowset["prod_discount"];
		}
	}
  }
  
?>
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="2" class="hrhighlightorange" align="left"><b>Purchase Info</b>
            <input type="hidden" name="recordstatus" value="<?php echo $recordstatus; ?>">
            </td>
        </tr>
    	<tr> 
   				<td width="20%" align="left">Supplier Name</td>
   				<td align="left"> 
                <?php
					if($suppStatus=='newadd'){
				?>
                		<input type="text" name="cboSupplier" id="cboSupplier" maxlength="30" size="30">
              <?php }else{ ?>
                   	<select name="cboSupplier" id="cboSupplier" class="box">
     					<option value="" selected>-- Choose Supplier --</option>
                        <option value="new" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=suppadd&editmode=<?php echo $iseditMode; ?>')">New Supplier</option>
						<?php
							echo $supplierList;
						?>	 
    				</select>
              <?php } ?>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
              </td>
  		</tr>
    	<tr> 
   				<td width="20%" align="left">Category</td>
   				<td align="left"> 
                	<select name="cboCategory" id="cboCategory" class="box">
     					<option value="" selected>-- Choose Category --</option>
                        <option value="new" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=categoryadd&suppId='+document.products.cboSupplier.value+'&editmode=<?php echo $iseditMode; ?>')">New Category</option>
						<?php
							echo $categoryList;
						?>	 
    				</select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
  		</tr>
        
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Part Number")."&nbsp;" ?></td>
            
			<td align="left">
            <?php
            	if($pcode=="newProduct"){ 
			?>
            		<input type="text" class="uppercase" name="part_code" size="30" maxlength="30">
            <?php }else{ ?>
            <input type="text" class="uppercase" name="part_code" size="30" maxlength="30" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=edit&partno='+this.value);" value="<?php echo $row["part_code"]; ?>">
            <?php }
				echo htmlspecialchars("*")."&nbsp;" 
			?>
            
            </td>
		</tr>
		<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?>
            </td>
			<td align="left">
            <?php
				if($pcode=="newProduct"){ 
			?>
            		<input type="hidden" name="prod_name_status" id="prod_name_status" value="<?php echo $pcode; ?>">
            		<input type="text" class="uppercase" name="prod_name" id="prod_name" size="30" maxlength="30">
            <?php
				}else{
			?>    

                    <select name="prod_name" id="prod_name" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=edit&pcode='+this.value+'&catId='+document.products.cboCategory.value)">
                    <option value="" selected>-- Choose Product --</option>
                    <option value="newProduct">New Product</option>
                    <?php
                        echo $productList;
                    ?>
			        </select>    
            <?php }
			echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Product Short Name")."&nbsp;" ?>
            </td>
			<td align="left"><input type="text" class="uppercase" name="prod_sname" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["prod_sname"])) ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Product Description")."&nbsp;" ?>
            
            </td>
			<td align="left">
            <?php if($row["prod_description"] != ""){ ?>
            	<textarea name="prod_description" cols="45" rows="5"><?=$row["prod_description"]?></textarea>
            <?php }else{ ?>
				<textarea name="prod_description" cols="45" rows="5"></textarea>
            <?php } ?>
            
            <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Available Qty")."&nbsp;" ?>
            </td>
			<td align="left"><input type="text" name="prod_qty" maxlength="10" value="<?php echo $row["prod_qty"] ?>" readonly>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Purchased Qty")."&nbsp;" ?>
            </td>
			<td align="left"><input type="text" name="purc_qty" maxlength="10" value="<?php echo $row["purc_qty"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Purchase Price")."&nbsp;" ?>
            </td>
			<td align="left"><input type="text" name="prod_price" maxlength="10" value="<?php echo $row["prod_price"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Selling Price")."&nbsp;" ?>
            </td>
			<td align="left"><input type="text" name="prod_price1" maxlength="10" value="<?php echo $row["prod_price1"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Product Tax")."&nbsp;" ?></td>
			<td align="left"><input type="text" name="prod_tax" maxlength="10" value="<?php echo $row["prod_tax"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Product Discount")."&nbsp;" ?></td>
			<td align="left"><input type="text" name="prod_discount" maxlength="10" value="<?php echo $row["prod_discount"] ?>" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
            <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

/***************** Add New Supplier *********************************************/
function addSupplier($edmode){
	if($edmode=='1'){
		$edstatus='edit';
	}else{
		$edstatus='add';
	}
?>
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?action=supplierAdd" method="post">
    <p><input type="hidden" name="action" value="supplierAdd"></p>
	<table width="100%" border="0" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left"><b>Vendor Information</b></td>
    	<tr>
        <tr>
       		<td align="left" width="20%"><?php echo htmlspecialchars("Vendor Name")."&nbsp;" ?></td>
            <td align="left">
             <input type="text" class="uppercase" name="supp_name" id="supp_name" maxlength="50" size="50">
            	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
      	<tr>
       		<td align="left" width="20%"><?php echo htmlspecialchars("Address")."&nbsp;" ?></td>
			<td align="left">
               	<textarea name="supp_address" id="supp_address" class="uppercase" cols="50" rows="4"></textarea> 
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
        </tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" class="uppercase" id="supp_city" name="supp_city" maxlength="50" size="50">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Pin/Zip Code")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_pin" name="supp_pin" maxlength="20" size="20" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("State")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" class="uppercase" id="supp_state" name="supp_state" maxlength="20" size="20">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Country")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" class="uppercase" id="supp_country" name="supp_country" maxlength="20" size="20">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" class="uppercase" id="supp_cperson" name="supp_cperson" maxlength="50" size="50">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Mobile No")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_mobno" name="supp_mobno" maxlength="10" size="10" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Land No")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_landno" name="supp_landno" maxlength="20" size="20" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Fax No")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_faxno" name="supp_faxno" maxlength="20" size="20" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Email")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_email" name="supp_email" maxlength="50" size="50">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
          
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Website")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_website" name="supp_website" maxlength="40" size="40">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Remark")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" id="supp_remark" name="supp_remark" maxlength="100" size="100">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
	</table>
   <p>
   <input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="javascript: formget(this.form, 'purchase/processPurchase.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=<?php echo $edstatus ?>');"></p>
   
	</form>
<?php
}
/********************* End of Supplier Function *********************************/

/******************** START NEW CATEGORY ****************************************/
function addCategory($edmode){
	if($edmode=='1'){
		$edstatus='edit';
	}else{
		$edstatus='add';
	}
?>
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?action=categoryAdd" method="post">
    <p><input type="hidden" name="action" value="categoryAdd"></p>
	<table width="100%" border="0" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left"><b>Vendor Information</b></td>
    	<tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" name="cat_name" class="uppercase" size="30" maxlength="30">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Category Description")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" name="cat_description" class="uppercase" size="50" maxlength="50">
                <?php echo htmlspecialchars("(optional)")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Tax Percentage")."&nbsp;" ?></td>
			<td align="left">
            	<input type="text" name="cat_tax" size="10" maxlength="10" onKeyUp="this.value=this.value.replace(/[^\d\.*]+/g, '')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
	</table>
	
   <p>
   <input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="javascript: formget(this.form, 'purchase/processPurchase.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=<?php echo $edstatus ?>');"></p>
   
	</form>
<?php
}
/********************************End of Category *******************************/

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
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="2" class="hrhighlightorange" align="left"><b>Purchased Product Info</b></td>
        </tr>
    	<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Supplier Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["supp_name"])) ?></td>
		</tr>
    	<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Category Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($catname)) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Part Number")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["part_code"])) ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Product Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["prod_name"])) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Product Short Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["prod_sname"])) ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Product Description")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["prod_description"])) ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Purchased Price")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["prod_price"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Selling Price")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["prod_price1"],2,'.',',') ?></td>
		</tr>
        
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Product Tax")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["prod_tax"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Product Discount")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["prod_discount"],2,'.',',') ?></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?action=add" method="post">
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"part_code" => "",
   					"prod_name" => "",
					"prod_sname" => "",
  					"prod_description" => "",
					"prod_price" => "",
					"prod_price1" => "",
					"prod_price2" => "",
					"prod_discount" => "",
					"prod_tax" => "",
					"prod_qty" => "",
					"purc_qty" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(prod_validate()==true){javascript: formget(this.form, 'purchase/processPurchase.php');}"></p>
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
            	<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                
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
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="cat_id" value="<?php echo $row["prod_code"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(prod_validate()==true){javascript: formget(this.form, 'purchase/processPurchase.php');}">
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
	<form action="purchase/processPurchase.php?action=delete" method="post">
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
	
	
  	$sql = "select * from (select a.part_code,a.prod_code, a.cat_code, a.prod_name, a.prod_sname, a.prod_description, a.prod_price, a.prod_price1,  
			a.prod_tax, a.prod_discount, b.cat_name,c.supp_name
			from tbl_product a,tbl_categories b,tbl_supplier c 
			where a.cat_code=b.cat_code and a.supp_id=c.supp_id) subq where 1";

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`part_code` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') 
					or (`prod_description` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."') or (`supp_name` like '" .$filterstr ."') 
					or (`prod_tax` like '" .$filterstr ."') or (`prod_discount` like '" .$filterstr ."')"; 
	
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
  	
	$sql = "select count(*) from (select a.part_code,a.prod_code, a.cat_code, a.prod_name, a.prod_sname, a.prod_description, a.prod_price, a.prod_price1,  
			a.prod_tax, a.prod_discount, b.cat_name,c.supp_name
			from tbl_product a,tbl_categories b,tbl_supplier c 
			where a.cat_code=b.cat_code and a.supp_id=c.supp_id) subq where 1";
  
    if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`prod_name` like '" .$filterstr ."') or (`part_code` like '" .$filterstr ."') or (`prod_sname` like '" .$filterstr ."') 
					or (`prod_description` like '" .$filterstr ."') 
					or (`prod_price` like '" .$filterstr ."') or (`prod_price1` like '" .$filterstr ."') or (`supp_name` like '" .$filterstr ."') 
					or (`prod_tax` like '" .$filterstr ."') or (`prod_discount` like '" .$filterstr ."')"; 
	
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
