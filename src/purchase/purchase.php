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
	 $_SESSION["purc_billno"] = "";
	$partno="0";
	$catId = "0";
	$pcode= "0";
	$cname = "";
	$pname = "";
	$prate = "0.00";
	$ptax = "0";
	$suppId = "0";
	$purc_billno="0";
	$status = false;
	
	require_once("../config.php");
	dataRemoves();
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
	if(isset($_GET["purc_billno"])) $purc_billno = $_GET["purc_billno"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($catId) && isset($_SESSION["catId"])) $catId = $_SESSION["catId"];
	if (!isset($suppId) && isset($_SESSION["suppId"])) $suppId = $_SESSION["suppId"];
	if (!isset($suppStatus) && isset($_SESSION["suppStatus"])) $suppStatus = $_SESSION["suppStatus"];
	if (!isset($recordstatus) && isset($_SESSION['recordstatus'])) $recordstatus = $_SESSION["recordstatus"];
	if (!isset($pcode) && isset($_SESSION["pcode"])) $pcode = $_SESSION["pcode"];
	if (!isset($pqty) && isset($_SESSION["pqty"])) $pqty = $_SESSION["pqty"];
	if (!isset($ptax) && isset($_SESSION["ptax"])) $ptax = $_SESSION["ptax"];
	if (!isset($pprice) && isset($_SESSION["pprice"])) $pprice = $_SESSION["pprice"];
	if (!isset($purc_billno) && isset($_SESSION["purc_billno"])) $purc_billno = $_SESSION["purc_billno"];
	//if (!isset($qstatus) && isset($_SESSION["qstatus"])) $qstatus = $_SESSION["qstatus"];
	

	if (isset($catId) && (int)$catId > 0) {
		$queryString = " and $catId = ";
	} else {
		$queryString = '';
	}
	
?>
	<html>
		<head>
			<title>mCRM -- Product Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
            <link href="test.css" type="text/css" rel="stylesheet">
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
				if (isset($purc_billno)) $_SESSION["purc_billno"] = $purc_billno;
			//	if (isset($qstatus)) $_SESSION["qstatus"] = $qstatus;
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
                    <option value="<?php echo "purc_bill_no" ?>"<?php if ($filterfield == "purc_bill_no") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Bill Number")?>
                    </option>
					<option value="<?php echo "supp_name" ?>"<?php if ($filterfield == "supp_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Supplier Name")?>
                    </option>
					<option value="<?php echo "purc_net_amt" ?>"<?php if ($filterfield == "purc_net_amt") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Net Amount") ?>
                    </option>
                    <option value="<?php echo "purc_date" ?>"<?php if ($filterfield == "purc_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Purchased Date") ?>
                    </option>
                    <option value="<?php echo "purc_status" ?>"<?php if ($filterfield == "purc_status") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Status") ?>
                    </option>
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'purchase/purchase.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmProduct" id="frmProduct" action="purchase/purchase.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="New Purchase" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=add')">
         
            <input type="button" name="btnPosting" value="Posting"  onClick="if(onPosting()==true){javascript:formget(this.form,'purchase/processPurchase1.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true');javascript:printHeader('Purchase Admin');}" >
                    <input type="hidden" name="action" value="posting">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
          
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&order=<?php echo "purc_bill_no" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Bill Number") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&order=<?php echo "supp_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Supplier Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&order=<?php echo "purc_net_amt" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Net Amount") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&order=<?php echo "purc_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Purchased Date") ?>
                </a>
            </td>
           
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=truetorder=<?php echo "purc_status" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Status") ?>
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
		
		if($row["supp_name"] != ""){
		$purcid = $row["purc_id"];
		$field = "purc_id";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['purc_id']; ?>">
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["purc_bill_no"])) ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo htmlspecialchars(strtoupper($row["supp_name"])) ?>
           	
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=view&recid=<?php echo $i ?>')">
           
		     <?php echo number_format($row["purc_net_amt"],2,'.',',') ?>
           	
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo date("d-m-Y H:i:s",strtotime($row["purc_date"])) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars(strtoupper($row["purc_status"])) ?>
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
//  global $ccode;
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
  global $a;
  global $purc_billno;


  $uname="";

	
	
  	$res = sql_select($conn);
  	$row = mysqli_fetch_assoc($res);
	$prodcode = $row["purc_id"];
	$field="purc_id";
	
	
			
  //echo "A :::::::::".$a."<br>";
  //echo "Category Id :".$catId."<br>";
  //echo "Purc Bill No :".$purc_billno."<br>";
  $purcid = $row["purc_id"];
$categoryList = buildCategoryOptions($catId);
  if($purc_billno=="0" or $purc_billno == ""){
  	$purc_bill_no = $row["purc_bill_no"];
  }else{
  	$purc_bill_no = $purc_billno;
  }
  
  if($suppId == "0" or $suppId == ""){
  	$suppId = $row["supp_id"];
  }
 //echo "Supplier ID :".$suppId."<br>";
  $supplierList = buildSupplierList($suppId);
  
  if($suppId != 0 && $suppStatus!='newadd'){
  	if($catId==0 and $catId != 'newCategory'){
  		$catId = $row["cat_code"];
  	}

  	$categoryList = buildCategoryOptions($catId);
  }else{
  	if($catId==0 and $catId != 'newCategory'){
  		$catId = $row["cat_code"];
  	}

  	
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
  $qstring="";
  //echo "Part No :".$partno."<br>";
  if ($partno !="" and $partno != "0"){
  	//echo "TRUE"."<br>";
  	$qstring = "select a.cat_name, b.cat_code, b.part_code, b.prod_code, b.prod_name, b.prod_sname, b.prod_price, b.prod_discount, 
					b.prod_description, b.prod_price1, b.prod_tax,b.prod_qty,c.unit_name 
					from tbl_categories a,tbl_product b,tbl_unit c where a.cat_code=b.cat_code and b.unit_code=c.unit_code and b.part_code='$partno'";
  }elseif($catId != "" and $catId !="0" and $pcode!="" and $pcode !="0"){
  		$qstring = "select a.cat_name, b.cat_code, b.part_code, b.prod_code, b.prod_name, b.prod_sname, b.prod_price, b.prod_discount, 
					b.prod_description, b.prod_price1, b.prod_tax,b.prod_qty,c.unit_name 
					from tbl_categories a,tbl_product b,tbl_unit c
					where a.cat_code=b.cat_code and b.prod_code='$pcode' and b.unit_code=c.unit_code and b.cat_code='$catId'";
  }
  if($qstring != ""){
  	//echo "QString :".$qstring."<br>";
	$resultset = mysqli_query($conn,$qstring);
	if(mysqli_num_rows($resultset)>0){
		//echo "TRUE"."<br>";
		$rowdata = mysqli_fetch_assoc($resultset);
		$catcode = $rowdata["cat_code"];
		$pcode= $rowdata["prod_code"];
		$cname = $rowdata["cat_name"];
		$pname = $rowdata["prod_name"];
		$uname = $row["unit_name"];
		$row["part_code"] = $rowdata["part_code"];
		$row["prod_sname"] = $rowdata["prod_sname"];
		$row["prod_description"] = $rowdata["prod_description"];
		$row["prod_price"] = $rowdata["prod_price"];
		$row["prod_price1"] = $rowdata["prod_price1"];
		$row["prod_tax"] = $rowdata["prod_tax"];
		$row["prod_discount"]  = $rowdata["prod_discount"];
		$row["prod_qty"]=$rowdata["prod_qty"];
		
		$catcode = $rowdata["cat_code"];
		$pcode= $rowdata["prod_code"];
		$cname = $rowdata["cat_name"];
		$pname = $rowdata["prod_name"];
		$prate = $rowdata["prod_price1"];
		if($ptax == "0" or $ptax == ""){
			$ptax = $rowdata["prod_tax"];
		}
		
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
		
		$pcode=="newProduct";
		$recordstatus="new";
		$catcode="0";
		$searchtext="";
		$catcode ="";
		$pcode= "";
		$cname = "";
		$pname = "";
		$prate = "0.00";
		$ptax = "0";
	}
		
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
	}
	
  }
  if($uname == ""){
  	if($pcode != ""){
		$uname = getUnitName($pcode);
	}
  }
  //echo "Category ID :".$catId."<br>";
  //echo "Supplier ID :".$suppId."<br>";
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
                        <option value="new" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=suppadd&editmode=<?php echo $iseditMode; ?>')">New Supplier</option>
						<?php
							echo $supplierList;
						?>	 
    				</select>
              <?php } ?>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
              </td>
  		</tr>
        <tr>
        	<td width="20%" align="left">Purchase Bill No</td>
   				<td align="left"><input type="text" name="purc_billno" id="purc_billno" value="<?php echo $purc_bill_no; ?>"></td> 
        </tr>
    	<tr>
        	<td colspan="2">
            	<table width="100%" class="hrhighlightblue">
                	<tr>
                    	<td width="10%">Part No</td>
                        <td width="15%">Category Name</td>
                        <?php if($catId == 'newCategory' or $pcode == 'newProduct'){ 
							  }else{
						?>
                        		<td width="10%">Search By</td>
                        <?php } ?>
                        <td width="20%">Product Name</td>
                        <td width="5%">Purchase Qty</td>
                        <td width="5%">Unit</td>
                        <td width="10%">Price Per Unit</td>
                        <td width="5%">Tax</td>
                        <td width="5%">Discount</td>
                        <td width="10%">Amount</td>
                     </tr>
                     <tr>
                     	<td width="10%"><input type="text" name="partno" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a; ?>&partno='+this.value+'&purc_billno='+document.products.purc_billno.value+'&suppId='+document.products.cboSupplier.value)" value="<?php echo $partno; ?>" maxlength="8" size="8"></td>
                        <td width="15%">
                        <?php if(isset($catId) && $catId != "" && $catId == 'newCategory'){ ?>
                        	<input type="text" name="cname" id="cname" class="upperclass" size="20" maxlength="20">
                        <?php }else{ ?>
                        	<select name="cname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a; ?>&catId='+this.value+'&purc_billno='+document.products.purc_billno.value+'&suppId='+document.products.cboSupplier.value)">
                            <option value="">--Choose Category--</option>
                         <!--   <option value="newCategory">New Category</option> -->
							<?php echo $categoryList; ?>
                            </select>
                        <?php } ?>
                        </td>
                        <?php if($catId == 'newCategory' or $pcode == 'newProduct'){ 
							  }else{
						?>
                        <td width="10%"><input type="text" name="searchtext" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a; ?>&searchtext='+this.value+'&purc_billno='+document.products.purc_billno.value)" value="<?php echo $searchtext; ?>" maxlength="10" size="10"></td>
                        <?php } ?>
                        <td width="20%">
                        <?php if((isset($pcode) && $pcode != "" && $pcode == 'newProduct') or ($catId == 'newCategory')){ ?>
                        	<input type="text" name="pname" id="pname" class="upperclass" size="30" maxlength="30">
                        <?php }else{ ?>
                        <select name="pname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'purchase/purchase.php?status=true&a=<?php echo $a; ?>&pcode='+this.value+'&catId='+document.products.cname.value+'&purc_billno='+document.products.purc_billno.value+'&suppId='+document.products.cboSupplier.value)">
                        <option value="">--Choose Product--</option>
                  <!--  <option value="newProduct">New Product</option> -->
							<?php echo $productList; ?>
                        </select>
                        <?php } ?>
                        </td>
                       
                        <td width="10%">
                            <input type="text" name="pqty" value="<?php if($pqty != 0){ echo $pqty; } else { echo '0'; }?>" size="5" onChange="javascript:calculate(this.value,document.products.pprice.value,document.products.ptax.value)">
                        </td>
                         <td width="5%">
                        <input type="text" name="unitname" value="<?php echo substr($uname,0,7); ?>" size="8" maxlength="8" readonly>
                        </td>
                        <td width="10%">
                        <input type="text" name="pprice" value="<?php if($prate != 0){ echo $prate; } else { echo '0.00'; }?>" size="10" onChange="javascript:calcTotalAmount(document.products.pdiscount.value,document.products.pqty.value,this.value,document.products.ptax.value)"></td>
                        <td width="5%">
                        <input type="text" name="ptax" value="<?php if($ptax != 0){ echo $ptax; } else { echo '0.00'; }?>" size="5" onChange="javascript:calcTotalAmount(document.products.pdiscount.value,document.products.pqty.value,document.products.pprice.value,this.value)"></td>
                        <td width="5%">
                            <input type="text" name="pdiscount" value="<?php if($pdiscount != 0){ echo $pdiscount; } else { echo '0'; }?>" size="5" onChange="javascript:calcTotalAmount(this.value,document.products.pqty.value,document.products.pprice.value,document.products.ptax.value)"></td>
                        <td width="10%"><input type="text" name="pamount" id="pamount" value="0.00" readonly size="10"></td>
                     </tr>
                     <tr>
                     	<td colspan="9" align="right">
                            <input type="button" name="btnProductAdd" value="Add" 
                            onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'purchase/purchase.php?status=true&a=<?php echo $a; ?>&recordstatus=newrecord&catId='+document.products.cname.value+'&pcode='+document.products.pname.value+'&pqty='+document.products.pqty.value+'&pprice='+document.products.pprice.value+'&partno='+document.products.partno.value+'&ptax='+document.products.ptax.value+'&pdiscount='+document.products.pdiscount.value+'&pstatus=<?php echo $pcode ?>&cstatus=<?php echo $catId ?>')">
                        </td>
                     </tr>
                </table>
                <?php 
					if($recordstatus=='newrecord'){
						dataAdd($catId,$pcode,$pqty,$pprice,$ptax,$pdiscount);
						$_SESSION['catId']='';
						$_SESSION['pcode']='';
						$_SESSION['pqty']='0';
						$_SESSION['pprice']='0.00';
						$catId='';
						$pcode='';
						$pqty='0';
						$pprice='0.00';
						$recordstatus='';
						if(dataFound()){
							echo "<input type='hidden' name='record_found' value='true'>";
							dataRet($totalamt,$discountamt);
						}
					}elseif($recordstatus=='deleterecord'){
						dataDelete($recordid);
						$recordstatus='';
						if(dataFound()){
							echo "<input type='hidden' name='record_found' value='true'>";
							dataRet($totalamt,$discountamt);
						}
					}elseif($recordstatus=='editrecord'){
						dataEdit($recordid,$totalamt,$discountamt);
						$recordstatus='';
					}elseif($recordstatus=='updaterecord'){
						dataUpdate($recordid,$editedqty,$editedprice,$editedtax,$editeddiscount);
						$editedqty=0;
						$editedprice=0;
						$editedtax=0;
						$recordstatus='';
						if(dataFound()){
							echo "<input type='hidden' name='record_found' value='true'>";
							dataRet($totalamt,$discountamt);
						}
					}elseif($recordstatus=='cancelrecord'){
						$recordstatus='';
						if(dataFound()){
							echo "<input type='hidden' name='record_found' value='true'>";
							dataRet($totalamt,$discountamt);
						}
					}else{
						if(purcFounds($purcid)){
							echo "<input type='hidden' name='record_found' value='true'>";
							if($purcid != "" and $a == 'edit'){
								dataEditRetrieve($purcid);
								$leadid="";
							}else{
								dataCopy($purcid);
							}
							dataRet($totalamt,$discountamt);
						}elseif(dataFound()){
							echo "<input type='hidden' name='record_found' value='true'>";
							dataRet($totalamt,$discountamt);
						}else{
							echo "<input type='hidden' name='record_found'>";
						}
					}
						
				?>
                </td>
            </tr>
            </td>
        </tr>
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/
function purcFounds($pid){
global $conn;	
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_purc_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.purc_id='$pid'";
	//echo "Qry :".$ret_qry;
	$resultset = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($resultset)>0){
		return true;
	}else{
		return false;
	}
}

function dataAdd($cc,$pc,$pq,$pr,$pt,$pd){
	global $conn;
	//echo "Data ADD :"." CC= ".$cc. "==PC :".$pc."==PQ :".$pq."==PR ".$pr."==PT :".$pt."==PD :".$pd."<br>";
	$chk_qry = "select * from tbl_temp_product where cat_code='$cc' and prod_code='$pc'";
	$chk_res = mysqli_query($conn,$chk_qry);
	if(mysqli_num_rows($chk_res)<=0){
		$amt = $pq * $pr;
		$orgamt = $amt + ($amt * ($pt/100)) - $pd;
		$ins_qry = "insert into tbl_temp_product (cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
					values ('$cc','$pc','$pq','$pr','$orgamt','$pt','$pd')";
		$ins_res = mysqli_query($conn,$ins_qry);
	}
}

function dataEditRetrieve($pid){
	global $conn;
	$editqry = "select * from tbl_purc_master a,tbl_purc_child b where a.purc_id=b.purc_id and a.purc_id='$pid'";
	//echo "Edit Qry :".$editqry."<br>";
	$editres= mysqli_query($conn,$editqry);
	if(mysqli_num_rows($editres)>0){
		$tempqry = mysqli_query($conn,"select * from tbl_temp_product where lead_id='$pid'");
		if(mysqli_num_rows($tempqry)<=0){
			$qrydata = "insert into tbl_temp_product (lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
							select purc_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount from tbl_purc_child 
							where purc_id='$pid'";
			mysqli_query($conn,$qrydata);
		}
	}
}

function dataCopy($lid){
	global $conn;
	//$ppqno = substr($qid,0,15);
	$editqry = "select * from tbl_purc_child where purc_id='$lid'";
	//echo "Copy Qry :".$editqry."<br>";
	$editres= mysqli_query($conn,$editqry);
	if(mysqli_num_rows($editres)>0){
		$tempqry = mysqli_query($conn,"select * from tbl_temp_product where lead_id='$lid'");
		if(mysqli_num_rows($tempqry)<=0){
			$qrydata = "insert into tbl_temp_product (lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
							select purc_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount from tbl_purc_child 
							where purc_id='$lid'";
			mysqli_query($conn,$qrydata);
		}
	}
}

function dataDelete($rid){
	global $conn;
	$del_qry = "delete from tbl_temp_product where id='$rid'";
	mysqli_query($conn,$del_qry);
}

function dataUpdate($rid,$eqty,$eprice,$etax,$ediscount){
	global $conn;
	$amt = $eqty * $eprice;
	$totamt = $amt + ($amt * ($etax/100));
	$totamt = $totamt - $ediscount;
	$update_qry = "update tbl_temp_product set prod_qty = '$eqty',prod_amount='$totamt',prod_tax='$etax',prod_discount='$ediscount' where id='$rid'";
	mysqli_query($conn,$update_qry);
}

function dataEdit($rid,$totamt,$damt){
	global $a;
	global $conn;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_temp_product a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
	?>
    <table width="100%" border="1" style="border-collapse:collapse">
        	<tr class="hrhighlightorange"  align='center'>
            	<th width="15%">Category Name</th>
                <th width="25%">Product Name</th>
                <th width="10%">Purchased Qty</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="15%">Price Per Unit</th>
                <th width="10%">Amount</th>
                <th width="5%">&nbsp;</th>
                <th width="5%">&nbsp;</th>
            </tr>
    
    <?php
		$i=1;
		$totamt = 0;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$recid= $ret_row["id"];
			
			echo "<tr>";
			echo "<td>".$ret_row["cat_name"]."</td>";
			echo "<td>".$ret_row["prod_name"]."</td>";
			if($rid==$recid){
				$price = $ret_row["prod_price"];
				$tax = $ret_row["prod_tax"];
				
			?>
				<td>
			      	<input type="text" name="eprod_qty" id="eprod_qty" value="<?php echo $ret_row["prod_qty"]; ?>" size="5" maxlength="5">
            	</td>
				<td>
					<input type="text" name="eprod_tax" id="eprod_tax" value="<?php echo $ret_row["prod_tax"]; ?>" size="5" maxlength="5">
            	</td>
				<td>
					<input type="text" name="eprod_discount" id="eprod_discount" value="<?php echo $ret_row["prod_discount"]; ?>" size="5" maxlength="5">
            	</td>
                <td>
					<input type="text" name="eprod_price" id="eprod_price" value="<?php echo $ret_row["prod_price"]; ?>" size="12" maxlength="12">
            	</td>
            <?php	
			}else{
            	echo "<td>".$ret_row["prod_qty"]."</td>";
            	echo "<td>".$ret_row["prod_tax"]."</td>";
            	echo "<td>".$ret_row["prod_discount"]."</td>";
				echo "<td>".$ret_row["prod_price"]."</td>";
			}
			$amt = $ret_row["prod_qty"]*$ret_row["prod_price"];
			$amt1 = $amt + ($amt * ($ret_row["prod_tax"]/100)) - $ret_row["prod_discount"];
			
			echo "<td>".$amt1."</td>";
			echo "<td>";
			?>
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'purchase/purchase.php?status=true&a=<?php echo $a; ?>&recordstatus=updaterecord&recordid=<?php echo $recid; ?>&eqty='+document.products.eprod_qty.value+'&ettax='+document.products.eprod_tax.value+'&etdiscount='+document.products.eprod_discount.value+'&etprice=<?php echo $price; ?>')">Update</a>
			<?php
			echo "</td><td>";
			?>
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'purchase/purchase.php?status=true&a=<?php echo $a; ?>&recordstatus=cancelrecord&recordid=<?php echo $recid; ?>')">Cancel</a>
			<?php
			echo "</td>";
			echo "</tr>";
			$totamt = $totamt + $ret_row["prod_amount"];
		}
	?>
    	
    	</table>
    <?php
	}
}


function purchaseRecordRetrieve($pid){
	global $conn;
	global $a;
	$ret_qry= "select b.part_code,c.cat_name,d.prod_name,b.prod_qty,b.prod_price,b.prod_amount,b.prod_tax,b.prod_discount,e.unit_name
				from tbl_purc_master a,tbl_purc_child b,tbl_categories c,tbl_product d,tbl_unit e
				where a.purc_id=b.purc_id and b.cat_code=c.cat_code and b.prod_code = d.prod_code and d.unit_code=e.unit_code and b.purc_id='$pid'";
	//echo "RET_QRY :".$ret_qry."<br>";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table id="gradientDemo">
        	<tr>
            	<th width="15%">Category Name</th>
                <th width="25%">Product Name</th>
                <th width="10%">Purchased Qty</th>
                <th width="5%">Unit</th>
                <th width="15%">Price Per Unit</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="10%">Amount</th>
                
            </tr>
    <?php
		$totamt = 0;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$recid= $ret_row["id"];
			echo "<tr>";
			echo "<td>".$ret_row["cat_name"]."</td>";
			echo "<td>".$ret_row["prod_name"]."</td>";
			$pqty = $ret_row["prod_qty"];
			$ptax = $ret_row["prod_tax"];
			$pdiscount = $ret_row["prod_discount"];
			$pamt = $pqty * $ret_row["prod_price"];
			$pamt1 = $pamt + ($pamt * ($ptax/100)) - $pdiscount;
			echo "<td align='center'>".$pqty."</td>";
			echo "<td align='center'>".$ret_row["unit_name"]."</td>";
			echo "<td align='center'>".number_format($ret_row["prod_price"])."</td>";
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			?>
         
			<?php
			echo "</tr>";
            
			$totamt = $totamt + $pamt1;
		}
		
	?>
    	
    	<tr>
        	<td colspan="7" align="right">Total Amount</td>
            <td align="right"><?php echo number_format($totamt,2,'.',','); ?></td>
        </tr>    
    	</table>
    <?php
	}
}
function dataRet($totamt,$damt){
	global $conn;
	global $a;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount,d.unit_name 
				from tbl_temp_product a,tbl_product b,tbl_categories c,tbl_unit d
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and b.unit_code=d.unit_code";
	//echo "RET_QRY :".$ret_qry."<br>";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table id="gradientDemo">
        	<tr>
            	<th width="15%">Category Name</th>
                <th width="25%">Product Name</th>
                <th width="10%">Purchased Qty</th>
                <th width="5%">Unit</th>
                <th width="10%">Price Per Unit</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="10%">Amount</th>
                <th width="5%">&nbsp;</th>
                <th width="5%">&nbsp;</th>
            </tr>
    <?php
		$totamt = 0;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$recid= $ret_row["id"];
			echo "<tr>";
			echo "<td>".$ret_row["cat_name"]."</td>";
			echo "<td>".$ret_row["prod_name"]."</td>";
			$pqty = $ret_row["prod_qty"];
			$ptax = $ret_row["prod_tax"];
			$pdiscount = $ret_row["prod_discount"];
			$pamt = $pqty * $ret_row["prod_price"];
			$pamt1 = $pamt + ($pamt * ($ptax/100)) - $pdiscount;
			echo "<td align='center'>".$pqty."</td>";
			echo "<td align='center'>".$ret_row["unit_name"]."</td>";
			echo "<td align='center'>".$ret_row["prod_price"]."</td>";
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			?>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a; ?>&recordstatus=editrecord&recordid=<?php echo $recid; ?>')">Edit</a>
            </td>
            <td align="center">
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a; ?>&recordstatus=deleterecord&recordid=<?php echo $recid; ?>')">Delete</a>
			</td>
            
			<?php
			echo "</tr>";
            
			$totamt = $totamt + $pamt1;
		}
		
	?>
    	<tr>
        	<td colspan="7" align="right">Discount</td>
        	<td align="right">
            <input type="text" name="txtdiscount" id="txtdiscount" value="<?php echo $damt; ?>" onChange="javascript:Discount(this.value,<?php echo $totamt ?>)">
            </td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <?php $totamt = $totamt - $damt; ?>
    	<tr>
        	<td colspan="7" align="right">Total Amount</td>
            <td><input type="text" name="txttotalamt" id="txttotalamt" value="<?php echo $totamt; ?>" readonly align="right"></td>
            <td colspan="2">&nbsp;</td></tr>
    	</table>
    <?php
	}
}

function getUnitName($pid){
global $conn;
	$uqry = "select b.unit_name from tbl_product a,tbl_unit b where a.unit_code=b.unit_code and a.prod_code='$pid'";
	$ures = mysqli_query($conn,$uqry);
	$urow = mysqli_fetch_assoc($ures);
	return $urow["unit_name"];
}
function dataRemoves(){
global $conn;
	$del_qry = "delete from tbl_temp_product";
	$del_res = mysqli_query($conn,$del_qry);
}
/***************** Add New Supplier *********************************************/
function addSupplier($edmode){
	if($edmode=='1'){
		$edstatus='edit';
	}else{
		$edstatus='add';
	}
?>
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?status=true&action=supplierAdd" method="post">
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
   <input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="javascript: formget(this.form, 'purchase/processPurchase.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $edstatus ?>');"></p>
   
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
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?status=true&action=categoryAdd" method="post">
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
   <input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="javascript: formget(this.form, 'purchase/processPurchase.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $edstatus ?>');"></p>
   
	</form>
<?php
}
/********************************End of Category *******************************/

/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
  
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
			<td align="left" width="20%"><?php echo htmlspecialchars("Bill Number")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["purc_bill_no"])) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Purchased Date")."&nbsp;" ?></td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["purc_date"])) ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Purchased Amount")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["purc_amount"],2,'.',',') ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Discount Amount")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["purc_discount"],2,'.',',') ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Net Amount")."&nbsp;" ?></td>
			<td align="left"><?php echo number_format($row["purc_net_amt"],2,'.',',') ?></td>
		</tr>
		<tr>
        	<td colspan="2">
            	<?php purchaseRecordRetrieve($row["purc_id"]); ?>
            </td>
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
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase1.php?status=true&action=add" method="post">
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
					"part_code" => "",
					"purc_bill_no" => "",
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
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(purchase_validate()==true){javascript: formget(this.form, 'purchase/processPurchase1.php');}"></p>
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
	$prodcode = $row["purc_id"];
	$field="purc_id";
	//echo "ViewRec : Rec ID :".$recid." Count=".$count."<br>";
  	showrecnav("view", $recid, $count);
?>
	<br>
<?php
	showrow($row, $recid); 
	
	if($row["purc_status"]=="PENDING"){
?>
        <br>
        <hr size="1" noshade>
        <table class="bd" border="0" cellspacing="1" cellpadding="4">
            <tr>
                <td>
                    <input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?&	status=true&a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                    
                </td>
            
            </tr>
        </table>
	<?php
	}
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
	<form name="products" enctype="multipart/form-data" action="purchase/processPurchase.php?status=true&action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="purc_id" value="<?php echo $row["purc_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="javascript: formget(this.form, 'purchase/processPurchase1.php');">
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
	<form action="purchase/processPurchase.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["purc_id"] ?>">
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
	
	
  	$sql = "select * from (select c.supp_name,c.supp_id,a.purc_id,a.purc_bill_no,a.purc_net_amt,a.purc_date,a.purc_amount,a.purc_discount,a.purc_status
			from tbl_purc_master a,tbl_purc_child b,tbl_supplier c 
			where a.supp_id=c.supp_id and a.purc_id=b.purc_id group by a.purc_id) subq where 1";

	/*if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}*/
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`supp_name` like '" .$filterstr ."') or (`purc_date` like '" .$filterstr ."') or (`purc_bill_no` like '" .$filterstr ."') 
					or (`prod_net_amt` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by purc_date desc";
	}
	
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
	global $queryString;
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "select count(*) from (select c.supp_name,a.purc_id,a.purc_bill_no,a.purc_net_amt,a.purc_date
			from tbl_purc_master a,tbl_purc_child b,tbl_supplier c 
			where a.supp_id=c.supp_id and a.purc_id=b.purc_id group by a.purc_id) subq where 1";

	/*if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}*/
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`supp_name` like '" .$filterstr ."') or (`purc_date` like '" .$filterstr ."') or (`purc_bill_no` like '" .$filterstr ."') 
					or (`prod_net_amt` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by purc_date desc";
	}
	
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  	//echo "SQL Count :".$sql."<br>";
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
