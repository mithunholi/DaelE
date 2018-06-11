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
	$_SESSION["recstatus"]="";
	$_SESSION["ccode"]= "";
	$_SESSION["pcode"]= "";
	$_SESSION["pqty"]= "";
	$_SESSION["pprice"]= "";
	$_SESSION["recordstatus"]= "";
	$status = false;
	require_once("../config.php");
	dataRemoves();
  }
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	
	$user_name=$_SESSION['User_ID'];
	$editedqty = 0;
	$editedprice= 0;
	$editedtax=0;

  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];
	
	if(isset($_GET["recstatus"])) $recstatus = stripslashes(@$_GET["recstatus"]);
	if(isset($_GET["searchtext"])) $searchtext = stripslashes(@$_GET["searchtext"]);
  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["leadId"])) $leadId = $_GET["leadId"];
	if(isset($_GET["ccode"])) $ccode = $_GET["ccode"];
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
	if(isset($_GET["qstatus"])) $qstatus = $_GET["qstatus"];
	if(isset($_GET["revision_no"])) $revision_no = $_GET["revision_no"];
	if(isset($_GET["quotation_no"])) $quotation_no = $_GET["quotation_no"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
	if (!isset($recstatus) && isset($_SESSION["recstatus"])) $recstatus = $_SESSION["recstatus"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($leadId) && isset($_SESSION["leadId"])) $leadId = $_SESSION["leadId"];
	if (!isset($ccode) && isset($_SESSION["ccode"])) $ccode = $_SESSION["ccode"];
	if (!isset($pcode) && isset($_SESSION["pcode"])) $pcode = $_SESSION["pcode"];
	if (!isset($pqty) && isset($_SESSION["pqty"])) $pqty = $_SESSION["pqty"];
	if (!isset($pprice) && isset($_SESSION["pprice"])) $pprice = $_SESSION["pprice"];
	if (!isset($qstatus) && isset($_SESSION["qstatus"])) $qstatus = $_SESSION["qstatus"];
	if (!isset($revision_no) && isset($_SESSION["revision_no"])) $revision_no = $_SESSION["revision_no"];
	if (!isset($quotation_no) && isset($_SESSION["quotation_no"])) $quotation_no = $_SESSION["quotation_no"];
	
	if (isset($leadId) && (int)$leadId > 0) {
		$queryString = " and lead_code=$leadId";
	} else {
		$queryString = '';
	}
	//echo "PCODE :::".$pcode."<br>";
?>
	<html>
		<head>
			<title>mCRM -- Lead Screen</title>
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
					case "reject":
						rejectrec($recid);
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
				if (isset($recstatus)) $_SESSION["recstatus"] = $recstatus;
  				if (isset($filter)) $_SESSION["filter"] = $filter;
  				if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				if (isset($leadId)) $_SESSION["leadId"] = $leadId;
				if (isset($ccode)) $_SESSION["ccode"] = $ccode;
				if (isset($pcode)) $_SESSION["pcode"] = $pcode;
				if (isset($pqty)) $_SESSION["pqty"] = $pqty;
				if (isset($pprice)) $_SESSION["pprice"] = $pprice;
				if (isset($qstatus)) $_SESSION["qstatus"] = $qstatus;
				if (isset($revision_no)) $_SESSION["revision_no"] = $revision_no;
				if (isset($quotation_no)) $_SESSION["quotation_no"] = $quotation_no;
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
	global $categoryList;
	global $leadId;
	global $pagerange;
	global $recstatus;
	
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
  	
  	$row = mysqli_fetch_assoc($res);
	$prodcode = $row["lead_id"];

  	if ($count % $showrecs != 0) {
    	$pagecount = intval($count / $showrecs) + 1;
  	}
  	else {
    	$pagecount = intval($count / $showrecs);
  	}
  
	$startrec = $showrecs * ($page - 1);
  	if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
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
					<option value="<?php echo "proposal_quote_date" ?>"<?php if ($filterfield == "proposal_quote_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Proposal Quote Date") ?>
                    </option>
                    <option value="<?php echo "proposal_followup_date" ?>"<?php if ($filterfield == "proposal_followup_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Proposal Follow-Up Date") ?>
                    </option>
                    <option value="<?php echo "follow_up_date" ?>"<?php if ($filterfield == "follow_up_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Follow Up Date") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'proposal/proposal.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="proposal/proposal.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Company Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			
          
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&order=<?php echo "proposal_quote_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Quote Date") ?>
                </a>
            </td>
            
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&order=<?php echo "follow_up_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Proposal FollowUp Date") ?>
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
		//echo "Customer MName :".$row["customer_name"];
		//if($row["customer_name"] != ""){
		$leadid = $row["lead_id"];
		$field = "lead_id";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
       <td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['lead_id']; ?>">
      </td>
	  <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      </td>
      
      
	   <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php 
				if($row["proposal_quote_date"]== "0000-00-00 00:00:00"){
					echo "0000-00-00";
				}else{
					echo date("d-m-Y",strtotime($row["proposal_quote_date"]));
				}
			?>
                   
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php 
				if($row["proposal_followup_date"]== "0000-00-00 00:00:00"){
					echo "0000-00-00";
				}else{
					echo date("d-m-Y",strtotime($row["proposal_followup_date"])); 
				}
			?>
      </td>
      
     
	</tr>
	<?php
		//}
  	}//for loop
  	mysqli_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'proposal/proposal.php'); 
} 
?></center>
<?php
 
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/
/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $today;
  global $categoryList;
  global $leadId;
  global $cattypeId;
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
  global $qstatus;
  global $revision_no;
  global $quotation_no;
  $catcode="";
 // if($leadId==0){
  	//$leadId = $row["cat_code"];
  //}
 // echo "Qstatus :".$qstatus."<br>";
  $remark = $row["remark"];
  if($qstatus == 'New'){
  	//echo "QStatusCode :".$row["quote_status_code"]."<br>";
  	if($row["quote_status_code"] == "" or $row["quote_status_code"]=="0"){
  		$qcode = "R0";
	}else{
		$qcode = $row["quote_status_code"];
		$qcode1  = substr($qcode,1,1);
		$qcode1 = $qcode1 + 1;
		$qcode= trim("R".$qcode1);
	}
  }else{
  	$qcode = $row["quote_status_code"];
  }
 // $quotation_no = $row["proposal_quote_no"];
  $leadid=$row["lead_id"];
  $totalamt = $row["total_amt"];
  $discountamt = $row["discount_amt"];
  if($row["cust_code"]==0 || $row["cust_code"]==""){
  	$custcode = $row["cust_code"];
  }
  $customerList = buildLeadCustomerOptions('$custcode');
  
  //echo "Step 1:".$ccode."<br>";
  if($ccode == "" or $ccode=="0"){
	  $catcode= $rowdata["cat_code"];
  }else{
  		$catcode=$ccode;
  }
  //echo "Cat Code :".$catcode."<br>";
  $categoryList = buildCategoryList($catcode);
  if($catcode != "" and $catcode != "0"){
  	if($pcode == "" or $pcode == "0"){
  		$pcode = $row["prod_code"];
  	}
	//echo "Prod Code :".$pcode."<br>";
  	$productList = buildProductList($pcode,$catcode,$searchtext);
  }
 
  $prate=0;
  $tax=0;
  
  //echo "PArt No :".$partno."<br>";
  if ($partno != "" and $partno != "0"){
  	$qstring = "select a.cat_name,b.cat_code,b.prod_code,b.prod_name,b.prod_price1,b.prod_tax 
					from tbl_categories a,tbl_product b where a.cat_code=b.cat_code and b.part_code='$partno'";
	//echo "QString :".$qstring."<br>";
	$resultset = mysqli_query($conn,$qstring);
	$rowdata = mysqli_fetch_assoc($resultset);
	$catcode = $rowdata["cat_code"];
	$pcode= $rowdata["prod_code"];
	$cname = $rowdata["cat_name"];
	$pname = $rowdata["prod_name"];
	$prate = $rowdata["prod_price1"];
	$ptax = $rowdata["prod_tax"];
	
	$categoryList = buildCategoryList($catcode);
	$productList = buildProductList($pcode,$catcode,$searchtext);
  }else{
  
  	if(($pcode != "" or $pcode != "0") and ($catcode != "" or $catcode !="0")){
  		$qstring = "select * from tbl_product where cat_code='$catcode' and prod_code='$pcode'";
		//echo "Query String :".$qstring."<br>";
  		$qry = mysqli_query($conn,$qstring);
		$rowset = mysqli_fetch_assoc($qry);
		$prate = $rowset["prod_price1"];
		$ptax = $rowset["prod_tax"];
		
		
		//$pqty = $rowset["prod_qty"];
		//$tax = $rowset["prod_discount"];
	}
	
  }
   //echo "Cat Code :".$catcode."<br>";
   //echo "Prod Code :".$pcode."<br>";
  //$categoryList = buildCategoryOptions($leadId);
?>
	<div align="left">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left">
           		<input type="button" id="displayText" onClick="javascript:sizeTbl();" value="+">
                <b>Customer Info</b>
           </td>
        </tr>
		</table>      
    </div>
 	
	<div id="toggleText" style="display: none">
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_name" size="50" maxlength="50" value="<?php echo $row["customer_name"] ?>" readonly>
                
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        
		<tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_cperson" size="50" maxlength="50" value="<?php echo $row['customer_cperson'] ?>" readonly>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Contact Number")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_mobno" size="30" maxlength="30" value="<?php echo $row['customer_mobno'] ?>" readonly> 
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Email Address")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_email" size="50" maxlength="50" value="<?php echo $row['customer_email'] ?>" readonly>
            
            </td>
		</tr>    	
          	        
        <tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="lead_date" size="12" maxlength="12"  value="<?php echo $row["lead_book_date"] ?>" readonly>
                <a href="javascript:show_calendar('document.frmLead.lead_date', document.frmLead.lead_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             	</a>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" valign="top" width="15%"><?php echo htmlspecialchars("Customer Requirement")."&nbsp;" ?>
            </td>
			<td align="left">
            	<?php 
					if($iseditmode != 1){ ?>
                      	<textarea name="prod_desc" id="prod_desc" cols="80" rows="5" readonly></textarea>
                <?php 
					}else{
				?>
						<textarea style="width:350px; height:80px;" cols="42" rows="5" name="prod_desc" id="prod_desc" readonly><?=$row['prod_desc']?></textarea>
                 <?php } ?>
	            <?php echo htmlspecialchars("*")."&nbsp;";  ?>
            </td>
		</tr>
        
        <tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Lead Followup Date")."&nbsp;" ?>
            </td>
			<td align="left">
             <?php
			 	if($row['follow_up_date']=='0000-00-00 00:00:00'){ ?>
                <input type="text" name="follow_up_date" size="12" maxlength="12" readonly>
             <?php }else{ ?>
             	<input type="text" name="follow_up_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['follow_up_date'])); ?>" readonly>
			  <?php } ?>
             <a href="javascript:show_calendar('document.frmLead.follow_up_date', document.frmLead.follow_up_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             </a>
                 
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        </table>
        </div>
        
        <br>
        <div align="left">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left">
           		<input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="-">
                <b>Quotation Info</b>
           </td>
        </tr>
		</table>      
    	</div> 
        
		<div id="toggleText2" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	
            <tr>
            	<td colspan="2">
                	<table width="100%" class="hrhighlightblue">
                    	<tr>
                        	<td width="10%">Part No</td>
                        	<td width="15%">Category Name</td>
                            <td width="10%">Search By</td>
                        	<td width="20%">Product Name</td>
                            <td width="5%">Order Qty</td>
                            <td width="10%">Price Per Unit</td>
                            <td width="5%">Tax</td>
                            <td width="5%">Discount</td>
                            <td width="10%">Amount</td>
                            
                            
                        </tr>
                        
                        <tr>
                        	<td width="10%"><input type="text" name="partno" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&partno='+this.value)" value="<?php echo $partno; ?>" maxlength="15" size="15"></td>
                        	<td width="15%"><select name="cname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&ccode='+this.value)"><option><?php echo $categoryList; ?></option></select></td>
                            <td width="10%"><input type="text" name="searchtext" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&searchtext='+this.value)" value="<?php echo $searchtext; ?>" maxlength="10" size="10"></td>
                        	<td width="20%"><select name="pname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&pcode='+this.value+'&ccode='+document.frmLead.cname.value)"><option><?php echo $productList; ?></option></select></td>
                            
							
                            <td width="10%">
                            <input type="text" name="pqty" value="<?php if($pqty != 0){ echo $pqty; } else { echo '0'; }?>" size="5" onChange="javascript:calculate(this.value,<?php echo $prate; ?>,<?php echo $ptax; ?>)">
                            </td>
                            
                            <td width="10%"><input type="text" name="pprice" value="<?php if($prate != 0){ echo $prate; } else { echo '0.00'; }?>" size="10" readonly></td>
                            <td width="5%"><input type="text" name="ptax" value="<?php if($ptax != 0){ echo $ptax; } else { echo '0.00'; }?>" size="10" readonly></td>
                            <td width="5%">
                            <input type="text" name="pdiscount" value="<?php if($pdiscount != 0){ echo $pdiscount; } else { echo '0'; }?>" size="10" onChange="javascript:calcDiscounts(this.value,document.frmLead.pqty.value,document.frmLead.pamount.value)"></td>
                            <td width="10%"><input type="text" name="pamount" id="pamount" value="0.00" readonly size="10"></td>
                            
                            
                        </tr>
                        <tr>
                        	<td colspan="9" align="right">
                            <input type="button" name="btnProductAdd" value="Add" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'proposal/proposal.php?status=true&a=edit&recordstatus=newrecord&ccode='+document.frmLead.cname.value+'&pcode='+document.frmLead.pname.value+'&pqty='+document.frmLead.pqty.value+'&pprice='+document.frmLead.pprice.value+'&partno='+document.frmLead.partno.value+'&ptax='+document.frmLead.ptax.value+'&pdiscount='+document.frmLead.pdiscount.value)">
                            </td>
                        </tr>
                    </table>
                    
                    <?php 
						if($recordstatus=='newrecord'){
							dataAdd($ccode,$pcode,$pqty,$pprice,$ptax,$pdiscount);
							$_SESSION['ccode']='';
							$_SESSION['pcode']='';
							$_SESSION['pqty']='0';
							$_SESSION['pprice']='0.00';
							$ccode='';
							$pcode='';
							$pqty='0';
							$pprice='0.00';
							$recordstatus='';
							if(dataFound()){
								dataRet($totalamt,$discountamt);
							}
						}elseif($recordstatus=='deleterecord'){
							dataDelete($recordid);
							$recordstatus='';
							if(dataFound()){
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
								dataRet($totalamt,$discountamt);
							}
						}elseif($recordstatus=='cancelrecord'){
							$recordstatus='';
							if(dataFound()){
								dataRet($totalamt,$discountamt);
							}
						}else{
							$recordstatus='';
							//echo "*********** :".$qstatus."<br>";
							if($qstatus=='NewQ'){
								if(dataFound()){
									dataRet('0','0');
								}
							}else{
								if(dataFounds($leadid)){
									//echo "Edit:::".$leadid."<br>";	
									if($leadid != "" and $qstatus == 'Edit'){
										//echo "+++++++++ :".$leadid." ----- ".$quotation_no." ------ ".$revision_no."<br>";  
										dataEditRetrieve($leadid,$quotation_no,$revision_no);
										$leadid="";
									}else{
										dataCopy($leadid,$quotation_no,$revision_no);
									}
									dataRet($totalamt,$discountamt);
								}elseif(dataFound()){
									//echo "*************"."<br>";
									dataRet($totalamt,$discountamt);
								}
							}
						}
						
					?>
                </td>
            </tr>
            <tr>
            	<td colspan="2">
                	<table width="100%" class="hrhighlightblue">
                    	
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Quote No")."&nbsp;" ?></td>
                            <td align="left">
                            <?php 
								if($row["proposal_quote_no"] == "" or $row["proposal_quote_no"] == "0"){
									$pqno = getNewQuoteNo($row["customer_name"]);
								}else{
									if($qstatus=='NewQ'){
										$pqno = getRegNewQuoteNo($row["proposal_quote_no"]);
									}else{
										if($qstatus=='New'){
											$pqno = getRevQuoteNo($quotation_no);
										}else{
											$pqno = $quotation_no; //$row["proposal_quote_no"];
										}
									}
								}
							 ?>
                             <input name="quote_status" value="<?php echo $qstatus; ?>" type="hidden">
                             <input name="quote_status_code" size="3" value="<?php echo $qcode; ?>" type="hidden">
                             <input type="text" name="proposal_quote_no" size="20" maxlength="20" value="<?php echo $pqno; ?>" readonly>
                             <?php echo htmlspecialchars("*")."&nbsp;" ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Quote Date")."&nbsp;" ?></td>
                            <td align="left">
                            <?php	
                                if($row["proposal_quote_date"]=='0000-00-00 00:00:00'){
									$quotedate= date('d-m-Y',strtotime($today));
								}else{
									$quotedate= date('d-m-Y',strtotime($row['proposal_quote_date']));
								}
                            ?>
                           
                              <input type="text" name="proposal_quote_date" size="12" maxlength="12" value="<?php echo $quotedate ?>">
                           
                              <a href="javascript:show_calendar('document.frmLead.proposal_quote_date', document.frmLead.proposal_quote_date.value);">
                                <img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                              </a>
                             <?php echo htmlspecialchars("*")."&nbsp;" ?>
                            </td>
                        </tr>
                      
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Proposal Followup Date")."&nbsp;" ?></td>
                            <td align="left">
                            <?php	
                                if($row["proposal_followup_date"]=='0000-00-00 00:00:00'){
                            ?>
                            <input type="text" name="proposal_followup_date" size="12" maxlength="12">
                            <?php } else { ?>
                           <input type="text" name="proposal_followup_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['proposal_followup_date'])) ?>">
                           <?php } ?>
                              <a href="javascript:show_calendar('document.frmLead.proposal_followup_date', document.frmLead.proposal_followup_date.value);">
                                <img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                              </a>
                             <?php echo htmlspecialchars("*")."&nbsp;" ?>
                            </td>
                        </tr>
                       
                        
                        <tr>
                        	<td align="left" valign="top" width="15%"> <?php echo htmlspecialchars("Remarks")."&nbsp;" ?></td>
                            <td align="left">
                            	<input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
								<?php 
									if($remark != ""){
                                		getRemarkDetails($remark);
                       		 		} 
								?>
                                <br>
                            	<textarea name="remark" id="remark" style="width:350px; height:80px; text-align:left; vertical-align:top;" cols="42" rows="5"></textarea>
                            </td>
                	</table>
            	</td>
        	</tr>             
        </table>
        </div>
        
   
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

function getNewQuoteNo($custname){
global $conn;
	$compname = substr($_SESSION['Company_Name'],0,2);
	$year = date("Y");
	$custname = substr($custname,0,2);
	
	$strdata = $compname.'/'.$custname.'/'.$year;
	$quote_qry  = "select proposal_quote_no from tbl_lead_master where proposal_quote_no like '$strdata%' limit 1";
	$quote_res  = mysqli_query($conn,$quote_qry);
	if(mysqli_num_rows($quote_res)>0){
		$quote_row  = mysqli_fetch_assoc($quote_res);
		$pqno = $quote_row["proposal_quote_no"];
		/*$quoteno = rtrim($pqno,1);
		$revno = substr($quoteno,strlen($quoteno)-1,1);
		$revno = $revno + 1;
		$quoteno = trim($quoteno.$revno);*/
		$quotesno = substr($pqno,0,13);
		//echo "QNo :".$quotesno."<br>";
		$revno = substr($pqno,13,2);
		$revno = $revno + 1;
		if($revno <10){
			$revno = "0".$revno;
		}
		$quoteno = $quotesno.$revno.'R01';
	}else{
		$quoteno = $strdata.'/'.'PQ01R01';
	}	
	//echo "Quote No :".$quoteno."<br>";
	return $quoteno;	
}

function getRegNewQuoteNo($proposal_quote_no){
global $conn;
		$pqno1 = substr($proposal_quote_no,0,13);
		//echo "PR! :".$pqno1."<br>";
		$qry = "SELECT substring( proposal_quote_no, 1, 15 ) prop_quote_no FROM `tbl_lead_child` WHERE proposal_quote_no LIKE '$pqno1%'
				GROUP BY substr( proposal_quote_no, 1, 15 )
				ORDER BY substr( proposal_quote_no, 1, 15 ) DESC LIMIT 1";
		//echo "Qry :".$qry."<br>";
		$res =  mysqli_query($conn,$qry);
		$rset = mysqli_fetch_assoc($res);
		$pqno = $rset["prop_quote_no"];
		//echo "PQNO :".$pqno."<br>";
		$seqno = substr($pqno,strlen($pqno)-2,2);
		$seqno = $seqno + 1;
		if($seqno < 10){
			$seqno = trim("0".$seqno);
		}
		$quoteno = trim($pqno1.$seqno."R01");
		/*$quoteno = rtrim($pqno,1);
		$revno = substr($quoteno,strlen($quoteno)-1,1);
		$revno = $revno + 1;
		$quoteno = trim($quoteno.$revno);*/
		
		/*$quotesno = substr($pqno,0,13);
		$revno = substr($pqno,13,2);
		$revno = $revno + 1;
		if($revno <10){
			$revno = "0".$revno;
		}
		$quoteno = $quotesno.$revno.substr($pqno,strlen($pqno)-3,3);*/
	return $quoteno;
}
function getRevQuoteNo($proposal_quote_no){
global $conn;
	/*$compname = substr($_SESSION['Company_Name'],0,2);
	$year = date("Y");
	$custname = substr($custname,0,2);
	$strdata = $compname.'/'.$custname.'/'.$year;
	$quote_qry  = "select proposal_quote_no from tbl_lead_master where proposal_quote_no like '$strdata%'";
	$quote_res  = mysql_query($quote_qry);
	if(mysql_num_rows($quote_res)>0){
		$quote_row  = mysql_fetch_assoc($quoteres);
		$pqno = $quote_row["proposal_quote_no"];*/
	//echo "Quote No1 :".$proposal_quote_no."<br>";	
	$pqno = substr($proposal_quote_no,0,15);
	//echo "Quote No :".$pqno."<br>";
	$quote_qry  = "select proposal_revision_no from tbl_lead_child where proposal_quote_no like '$pqno%' order by proposal_revision_no desc";
	//echo "quote Qry :".$quote_qry."<br>";
	$quote_res  = mysqli_query($conn,$quote_qry);
	$quote_row  = mysqli_fetch_assoc($quote_res);
	$revisionno = $quote_row["proposal_revision_no"];
	
	/*	$quoteno = substr($pqno,0,13);	
		$revno = substr($pqno,13,4);
		$code  = substr($revno,0,1);
		$code1  = substr($revno,1,3);
		$code = $code + 1;
		$codes = $code.$code1;
		$quoteno = $quoteno.$codes;
		return $quoteno;*/
	//}
	
	$revno = substr($revisionno,strlen($revisionno)-2,2);
	//echo "RevNo :".$revno."<br>";
	$revno = $revno + 1;
	if($revno < 10){
		$revno = trim("0".$revno);
	}
	$revsno = trim("R".$revno);
	//echo "REvNo1 :".$revsno."<br>";
	$revsno = trim($pqno.$revsno);
	//echo "F :".$revsno."<br>";
	return $revsno;
}

function dataAdd($cc,$pc,$pq,$pr,$pt,$pd){
	global $conn;
	$chk_qry = "select * from tbl_temp_product where cat_code='$cc' and prod_code='$pc'";
	$chk_res = mysqli_query($conn,$chk_qry);
	if(mysqli_num_rows($chk_res)<=0){
		$amt = $pq * $pr;
		$ins_qry = "insert into tbl_temp_product (cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
					values ('$cc','$pc','$pq','$pr','$amt','$pt','$pd')";
		$ins_res = mysqli_query($conn,$ins_qry);
	}
}

function dataEditRetrieve($lid,$qid,$rid){
	global $conn;
	$editqry = "select * from tbl_lead_child where lead_id='$lid' and proposal_quote_no='$qid' and proposal_revision_no='$rid'";
	//echo "Edit Qry :".$editqry."<br>";
	$editres= mysqli_query($conn,$editqry);
	if(mysqli_num_rows($editres)>0){
		$tempqry = mysqli_query($conn,"select * from tbl_temp_product where lead_id='$lid'");
		if(mysqli_num_rows($tempqry)<=0){
			$qrydata = "insert into tbl_temp_product (lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
							select lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount from tbl_lead_child 
							where lead_id='$lid' and proposal_quote_no='$qid'";
			mysql_iquery($conn,$qrydata);
		}
	}
}

function dataCopy($lid,$qid,$rid){
	global $conn;
	$ppqno = substr($qid,0,15);
	$editqry = "select * from tbl_lead_child where lead_id='$lid' and proposal_quote_no LIKE '$ppqno%' and proposal_revision_no='$rid'";
	//echo "Copy Qry :".$editqry."<br>";
	$editres= mysqli_query($conn,$editqry);
	if(mysqli_num_rows($editres)>0){
		$tempqry = mysqli_query($conn,"select * from tbl_temp_product where lead_id='$lid'");
		if(mysqli_num_rows($tempqry)<=0){
			$qrydata = "insert into tbl_temp_product (lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
							select lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount from tbl_lead_child 
							where lead_id='$lid' and proposal_quote_no LIKE '$ppqno%' and proposal_revision_no='$rid'";
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
                <th width="10%">Order Qty</th>
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
					<input type="text" name="eprod_discount" id="eprod_discount" value="<?php echo $ret_row["prod_discount"]; ?>" size="8" maxlength="8">
            	</td>
            <?php	
			}else{
            	echo "<td>".$ret_row["prod_qty"]."</td>";
            	echo "<td>".$ret_row["prod_tax"]."</td>";
            	echo "<td>".$ret_row["prod_discount"]."</td>";
				
			}
			$amt = $ret_row["prod_qty"]*$ret_row["prod_price"];
			$amt1 = $amt + ($amt * ($ret_row["prod_tax"]/100)) - $ret_row["prod_discount"];
			echo "<td>".$ret_row["prod_price"]."</td>";
			echo "<td>".$amt1."</td>";
			echo "<td>";
			?>
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'proposal/proposal.php?status=true&a=edit&recordstatus=updaterecord&recordid=<?php echo $recid; ?>&eqty='+document.frmLead.eprod_qty.value+'&ettax='+document.frmLead.eprod_tax.value+'&etdiscount='+document.frmLead.eprod_discount.value+'&etprice=<?php echo $price; ?>')">Update</a>
			<?php
			echo "</td><td>";
			?>
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'proposal/proposal.php?status=true&a=edit&recordstatus=cancelrecord&recordid=<?php echo $recid; ?>')">Cancel</a>
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
function dataRetrieve($totamt,$damt){
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
                <th width="10%">Order Qty</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="15%">Price Per Unit</th>
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
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			echo "<td align='right'>".number_format($ret_row["prod_price"],2,'.',',')."</td>";
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			?>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&recordstatus=editrecord&recordid=<?php echo $recid; ?>')">Edit</a>
            </td>
            <td align="center">
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&recordstatus=deleterecord&recordid=<?php echo $recid; ?>')">Delete</a>
			</td>
            
			<?php
			echo "</tr>";
            
			$totamt = $totamt + $pamt1;
		}
		
	?>
    	<tr>
        	<td colspan="6" align="right">Discount</td>
        	<td align="right">
            <input type="text" name="txtdiscount" id="txtdiscount" value="<?php echo $damt; ?>" onChange="javascript:Discount(this.value,<?php echo $totamt ?>)">
            </td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <?php $totamt = $totamt - $damt; ?>
    	<tr>
        	<td colspan="6" align="right">Total Amount</td>
            <td align="right"><input type="text" name="txttotalamt" id="txttotalamt" value="<?php echo $totamt; ?>" readonly></td>
            <td colspan="2">&nbsp;</td></tr>
    	</table>
    <?php
	}
}


function dataRet($totamt,$damt){
	global $conn;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_temp_product a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
	//echo "RET_QRY :".$ret_qry."<br>";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table id="gradientDemo">
        	<tr>
            	<th width="15%">Category Name</th>
                <th width="25%">Product Name</th>
                <th width="10%">Order Qty</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="15%">Price Per Unit</th>
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
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			echo "<td align='right'>".number_format($ret_row["prod_price"],2,'.',',')."</td>";
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			?>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&recordstatus=editrecord&recordid=<?php echo $recid; ?>')">Edit</a>
            </td>
            <td align="center">
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&recordstatus=deleterecord&recordid=<?php echo $recid; ?>')">Delete</a>
			</td>
            
			<?php
			echo "</tr>";
            
			$totamt = $totamt + $pamt1;
		}
		
	?>
    	<tr>
        	<td colspan="6" align="right">Discount</td>
        	<td align="right">
            <input type="text" name="txtdiscount" id="txtdiscount" value="<?php echo $damt; ?>" onChange="javascript:Discount(this.value,<?php echo $totamt ?>)">
            </td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <?php $totamt = $totamt - $damt; ?>
    	<tr>
        	<td colspan="6" align="right">Total Amount</td>
            <td align="right"><input type="text" name="txttotalamt" id="txttotalamt" value="<?php echo $totamt; ?>" readonly></td>
            <td colspan="2">&nbsp;</td></tr>
    	</table>
    <?php
	}
}

function dataRemoves(){
global $conn;
	$del_qry = "delete from tbl_temp_product";
	$del_res = mysqli_query($conn,$del_qry);
}
/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid,$sstatus)
{
  global $recstatus;
  $leadid = $row["lead_id"];
  $totalamt = $row["total_amt"];
  $discountamt = $row["discount_amt"];
  $field="lead_id";
  $remark = $row["remark"];
  $buildTemplateList = buildTemplateOption();
?>
	
    <div align="left">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left">
           		<input type="button" id="displayText" onClick="javascript:sizeTbl();" value="+">
                <b>Customer Info</b>
           </td>
        </tr>
		</table>      
    </div>
	<div id="toggleText" style="display: none">
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	
    	<tr>
			<td align="left" width="15%"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?></td>
		</tr>
		<tr>
			<td width="15%" align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?></td>
		</tr>
       
		<tr>
			<td width="15%" align="left"><?php echo htmlspecialchars("Customer Requirement")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])); ?></td>
		</tr>
        <tr>
			<td width="15%" align="left"><?php echo htmlspecialchars("Customer Mobile No.")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo strtoupper($row["customer_mobno"]) ?></td>
		</tr>
        <tr>
			<td width="15%" align="left"><?php echo htmlspecialchars("Email ")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo htmlspecialchars($row["customer_email"]) ?></td>
		</tr>
        <tr>
			<td width="15%" align="left"><?php echo htmlspecialchars("Lead Follow-Up Date")."&nbsp;" ?></td>
            <td align="left" width="3%">:</td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["follow_up_date"])) ?></td>
		</tr>
    </table>
    </div>
    <div align="left">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%">
    	<tr>
        	<td colspan="3" class="hrhighlightblue" align="left">
           		<input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="-">
                <b>Quotation Info</b><input type="button" id="btnQuoteAdd" name="btnQuoteAdd" value="Add New Quotation" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?cat_code=&status=true&a=edit&qstatus=NewQ&filter=<?php echo $leadid ?>&filter_field=<?php echo $field ?>')">
           </td>
        </tr>
		</table>      
    </div>
    <div id="toggleText2" style="display: block">
    
    <?php 
		if(dataFounds($leadid)){
			$lcqry = "SELECT proposal_quote_no FROM tbl_lead_child WHERE lead_id = '$leadid' GROUP BY substring( proposal_quote_no, 1, 15 )";
			$lcres = mysqli_query($conn,$lcqry);
			$i=1;
			while($lcrow = mysqli_fetch_assoc($lcres)){
			  $ppquoteno = $lcrow["proposal_quote_no"];
			  $revisionno= getRevisionNo($leadid,$ppquoteno);
	?>
    		  
    		  <div align="left">
    			<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    			<tr>
                    <td colspan="3" class="hrhighlightorange" align="left">
                        <input type="button" id="dynDisplayText<?php echo $i ?>" onClick="javascript:dynsizeTbl(<?php echo $i ?>);" value="+">
                        <b>Quotation<?php echo $i?></b>
                        <input type="button" id="btnQuoteAdd" name="btnQuoteAdd" value="Add Revision" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&qstatus=New&quotation_no=<?php echo $ppquoteno; ?>&revision_no=<?php echo $revisionno; ?>&filter=<?php echo $leadid ?>&filter_field=<?php echo $field ?>')">
                   </td>
        		</tr>
				</table>      
    		</div>
            
            
            <div id="dyntoggleText<?php echo $i ?>" style="display: none">
            <?php
				$pqno = substr($ppquoteno,0,15);
				$revqry = "SELECT distinct(proposal_revision_no) proposal_revision_no 
									from tbl_lead_child WHERE lead_id = '$leadid' and proposal_quote_no LIKE '$pqno%' ORDER BY proposal_revision_no";
				//echo "Rev Qry :".$revqry."<br>";
				$revres = mysqli_query($conn,$revqry);
				$totrecno = mysqli_num_rows($revres);
				$j=1;
				while($revrow = mysqli_fetch_assoc($revres)){
					$revno = $revrow["proposal_revision_no"];
					$ppquoteno = trim($pqno.$revno);
			?>
    			<div align="left">
    				<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    					<tr>
                    	<td colspan="2" class="hrhighlightorange" align="left">
                        <input type="button" id="dynDisplayText<?php echo $i.$j ?>" onClick="javascript:dynsizeTbl(<?php echo $i.$j ?>);" value="+">
                        <b>Revision<?php echo $j?></b>
                        <?php
							if($j == $totrecno){
						?>
                        <input type="button" id="btnQuoteAdd" name="btnQuoteAdd" value="Edit Revision<?php echo $j ?>" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=edit&qstatus=Edit&quotation_no=<?php echo $ppquoteno; ?>&revision_no=<?php echo $revno; ?>&filter=<?php echo $leadid ?>&filter_field=<?php echo $field ?>')">
                         <input type="button" id="btnQuotePreview" name="btnQuotePreview" value="Show Quote Preview" onClick="javascript:quotePreview();">
                        <?php } ?>
                        </td>
                        <td align="left">
                        <div id="toggleQuote" style="display: none">
                        	<select name="cmbtemplate" id="cmbtemplate" onChange="if(templateValidate()==true){newWindow(this.value,'<?php echo $ppquoteno ?>','<?php echo $leadid ?>');}">
                            	<option value="">Choose One Template</option>
                            	<?php echo $buildTemplateList; ?>
                            </select>
                        </div>
                   		</td>
        				</tr>
					</table>      
    			</div>
     			<div id="dyntoggleText<?php echo $i.$j ?>" style="display: none">
           		  <?php	
			 		dataRetrieves($leadid,$ppquoteno,$revno,$totalamt,$discountamt);
				  ?> 
             		<table class="hrhighlightblue" border="0" cellspacing="1" cellpadding="5" width="100%">
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Quote No")."&nbsp;" ?></td>
                            <td align="left" width="3%">:</td>
                            <td align="left"><?php echo htmlspecialchars($ppquoteno) ?></td>
                        </tr>
						<tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Quote Date")."&nbsp;" ?></td>
                            <td align="left" width="3%">:</td>
                            <td align="left">
                            <?php
                                if($row["proposal_quote_date"]=='0000-00-00 00:00:00' or $row["proposal_quote_date"]==''){
                                    echo '00-00-0000';
                                }else{
                                    echo date("d-m-Y",strtotime($row["proposal_quote_date"]));
                                }
                            ?>
                            </td>
                		</tr>
                        <tr>
                            <td	align="left" width="15%"><?php echo htmlspecialchars("Proposal Follow-up Date")."&nbsp;" ?></td>
                            <td align="left" width="3%">:</td>
                            <td align="left">
                                <?php 
                                    if($row["proposal_followup_date"]=='0000-00-00 00:00:00'){
                                        echo '00-00-0000';
                                    }else{
                                        echo date("d-m-Y",strtotime($row["proposal_followup_date"]));
                                    }
                                 ?>
                            </td>
                        </tr>
                       
                        <tr>
                            <td align="left" width="15%">Remarks</td>
                            <td align="left" width="3%">:</td>
                            <td align="left"><input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
							<?php 
								if($remark != ""){
                                	getRemarkDetails($remark);
                       		 } ?>
                    		</td>
                		</tr>
            		</table>
     			</div>
     <?php
	 			$j++;
			}//First while
			echo "</div>";
	 		$i++;
	 	}//Outside while
     }//if
	 ?>
     </div>
     <?php 
	 	if($sstatus=="reject"){ 
	 		$sstatus="";
	 ?>
      	<div>
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
                <tr>
                <td align="left" width="15%">Remarks</td>
                <td align="left" width="3%">:</td>
                <td align="left">
                    <textarea name="remark" id="remark" cols="42" rows="5"></textarea>
                </td>
                </tr>
            </table>    
         </div>
	<?php } ?>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/


function getRevisionNo($lid,$pqno){
	global $conn;
	$pqno= substr($pqno,0,15);
	$rqry = "SELECT distinct(proposal_revision_no) proposal_revision_no 
									from tbl_lead_child WHERE lead_id = '$lid' and proposal_quote_no LIKE '$pqno%' ORDER BY proposal_revision_no desc";
	$rres = mysqli_query($conn,$rqry);

	if(mysqli_num_rows($rres)>0){
		$rrow = mysqli_fetch_assoc($rres);
		return $rrow["proposal_revision_no"];
	}else{
		return 0;
	}
	
}
function dataRetrieves($lid,$qid,$rid,$totamt,$damt){
	global $conn;
	$qid = substr($qid,0,15);
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.lead_id='$lid' and a.proposal_quote_no LIKE '$qid%' and proposal_revision_no='$rid'";
	//echo "dataRET :".$ret_qry."<br>'";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table width="100%" border="1" style="border-collapse:collapse;">
        	<tr class="hrhighlightorange">
            	<th width="20%">Category Name</th>
                <th width="30%">Product Name</th>
                <th width="10%">Order Qty</th>
                <th width="10%">Tax</th>
                <th width="10%">Discount</th>
                <th width="10%">Price Per Unit</th>
                <th width="10%">Amount</th>
              
            </tr>
    <?php
		$totamt=0;
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
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			echo "<td align='right'>".number_format($ret_row["prod_price"],2,'.',',')."</td>";
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			echo "</tr>";
			$totamt = $totamt + $pamt1;
		}
	?>
    	<tr><td colspan="6" align="right">Discount</td><td align="right">
			<?php 
				echo number_format($damt,2,'.',','); 
				$totamt = $totamt - $damt;	
			?></td></tr>
    	<tr><td colspan="6" align="right">Total Amount</td><td align="right"><?php echo number_format($totamt,2,'.',','); ?></td></tr>
    	</table>
    <?php
	}
}

/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
 //echo "Record Id :".$recid. " Count=".$count."<br>";
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?custcode=&cat_code=&status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?status=true&action=add" method="post">
        <p><input type="hidden" name="action" value="add"></p>
		<?php
			$row = array(
   					"lead_id" => "",
					"cust_name" => "",
					"lead_date" => "",
					"cat_name" => "",
  					"prod_description" => "",
					"prod_qty" => "",
					"ref_by" => "",
					"cust_require" => "",
					"follow_up_date" => "",
					"remark"=>""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(lead_validate()==true){javascript: formget(this.form, 'lead/processLead.php');}"></p>
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
	$prodcode = $row["lead_id"];
	$field="lead_id";
	//echo "Product Code :".$prodcode."<br>";
	//echo "ViewRec : Rec ID :".$recid." Count=".$count."<br>";
  	showrecnav("view", $recid, $count);
?>
	<br>
    <form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?status=true&action=update" method="post">
    	<input type="hidden" name="action" value="editarea">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
		
		<?php
			
			showrow($row, $recid,"view"); 
		?>
      
		<br>

		<hr size="1" noshade>
		<table class="bd" border="0" cellspacing="1" cellpadding="4">
			<tr>
				
				<td>
            		<input type="button" name="btnReject" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?cat_code=& status=true&a=reject&recid=<?php echo $recid ?>');" value="Reject Record">
                
            	</td>
                <?php 
					if(dataFounds($prodcode)){
				?>
            	<td>
            		<input type="button" name="btnAccept" onClick="javascript:formget(this.form,'proposal/processProposal.php?status=true&action1=accept');javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true');javascript:printHeader('Lead Admin');" value="Accept Record">
                
            	</td>
                <?php
					}
				?>
			</tr>
		</table>
        </form>
	<?php
  		mysqli_free_result($res);
} 
/****************** End of the VIEW RECORD Function ***********************************/

/*********************** START EDIT RECORD Function ***********************************/
function rejectrec($recid)
{
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
  	showrecnav("edit", $recid, $count);
?>
	<br>
	<form name="frmLead" enctype="multipart/form-data" action="proposal/processProposal.php?status=true&action=reject" method="post">
		<input type="hidden" name="action" value="reject">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="cust_id" value="<?php echo $row["cust_code"] ?>">
		<?php 
			showrow($row,$recid,"reject"); 
			
		?>
        
		<p>
        <input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(reject_validate()==true){javascript: formget(this.form, 'proposal/processProposal.php');}">
        </p>
        <div id="output" style="color: blue;"></div>
        
	</form>
<?php
	mysqli_free_result($res);
} 
/****************** End of the EDIT RECORD Function ***********************************/

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
	<form name="frmLead" enctype="multipart/form-data" action="proposal/processProposal.php?status=true&action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="cust_id" value="<?php echo $row["cust_code"] ?>">
		<?php 
			showroweditor($row, true); 
			
		?>
        
		<p>
        <input type="button" name="btnedit" id="btnedit" value="Update" onClick="javascript: formget(this.form, 'proposal/processProposal.php');">
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
	<form name="frmLead" action="lead/processLead.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
		<?php showrow($row, $recid,"view") ?>
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
	global $recstatus;
	
	$uid=$_SESSION['User_ID'];
	//echo "Filter :".$filter." FilterField :".$filterfield."<br>";
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	if($_SESSION['User_Design']=='ADMIN') {
		//echo "SESSION ID :".$_SESSION['LEVEL5']."<br>";
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='0' and c.reject_status='0'";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.quote_status_code,c.remark
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='0' and c.reject_status='0'";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.quote_status_code,c.remark
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.user_id='$uid'".$qry.") subq where 1";
	
  	}

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`proposal_quote_date` like '" .$filterstr ."') 
					or (`proposal_followup_date` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by lead_id";
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
	global $recstatus;
	
	$uid=$_SESSION['User_ID'];
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
  	if($_SESSION['User_Design']=='ADMIN') {
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='0' and c.reject_status='0'";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date, 
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,c.quote_status_code 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='0' and c.reject_status='0'";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date, 
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,c.quote_status_code 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.user_id='$uid'".$qry.") subq where 1";
	
	}

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`proposal_quote_date` like '" .$filterstr ."') 
					or (`proposal_followup_date` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by lead_id";
	}
	
	
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
