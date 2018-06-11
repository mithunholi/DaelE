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
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
	if (!isset($recstatus) && isset($_SESSION["recstatus"])) $recstatus = $_SESSION["recstatus"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	
	
	if (isset($leadId) && (int)$leadId > 0) {
		$queryString = " and lead_code=$leadId";
	} else {
		$queryString = '';
	}
	
?>
	<html>
		<head>
			<title>mCRM -- Rejected Lead</title>
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
				if (isset($recstatus)) $_SESSION["recstatus"] = $recstatus;
  				if (isset($filter)) $_SESSION["filter"] = $filter;
  				if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				
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
					<option value="<?php echo "prod_desc" ?>"<?php if ($filterfield == "prod_desc") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Product Description") ?>
                    </option>
                    <option value="<?php echo "ref_by" ?>"<?php if ($filterfield == "ref_by") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Reference") ?>
                    </option>
                    <option value="<?php echo "lead_date" ?>"<?php if ($filterfield == "lead_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Lead Date") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'reject/reject.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="reject/reject.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Customer Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&order=<?php echo "prod_desc" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Description") ?>
                </a>
            </td>
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&order=<?php echo "remark" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Reason") ?>
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
	  <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      </td>
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])) ?>
      </td>
      
     
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["follow_up_date"])) ?>
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
		showpagenav($page, $pagecount,$pagerange,'reject/reject.php'); 
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
  global $recstatus;
  global $ccode;
  global $pcode;
  global $recordstatus;
  global $pqty;
  global $pprice;
  global $editedqty;
  global $editedtax;
  global $editedprice;
  global $recordid;
  global $searchtext;
  $catcode="";
 // if($leadId==0){
  	//$leadId = $row["cat_code"];
  //}
  
  if($row["cust_code"]==0 || $row["cust_code"]==""){
  	$custcode = $row["cust_code"];
  }
  $customerList = buildLeadCustomerOptions($custcode);
  
  //echo "Step 1:".$ccode."<br>";
  if($ccode == "" or $ccode=="0"){
	  $catcode= $row["cat_code"];
  }else{
  		$catcode=$ccode;
  }
  
  $categoryList = buildCategoryList($catcode);
  if($catcode != "" and $catcode != "0"){
  	if($pcode == "" or $pcode == "0"){
  		$pcode = $row["prod_code"];
  	}
  	$productList = buildProductList($pcode,$catcode,$searchtext);
  }
  $prate=0;
  $tax=0;
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
  //$categoryList = buildCategoryOptions($leadId);
?>
 	<input type="button" id="displayText" onClick="javascript:sizeTbl();" value="SHOW CUSTOMER INFO">
	<div id="toggleText" style="display: none">
	<table  border="0" cellspacing="1" cellpadding="5" width="100%">
    	<tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<input type="text" name="cust_name" size="50" maxlength="50" value="<?php echo $row["customer_name"] ?>">
                
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        
		<tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<input type="text" name="cust_cperson" size="50" maxlength="50" value="<?php echo $row['customer_cperson'] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("Contact Number")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<input type="text" name="cust_mobno" size="30" maxlength="30" value="<?php echo $row['customer_mobno'] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td class="hr" align="left" width="20%"><?php echo htmlspecialchars("Email Address")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<input type="text" name="cust_email" size="50" maxlength="50" value="<?php echo $row['customer_email'] ?>">
            
            </td>
		</tr>    	
          	        
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<input type="text" name="lead_date" size="12" maxlength="12"  value="<?php echo $row["lead_book_date"] ?>">
                <a href="javascript:show_calendar('document.frmLead.lead_date', document.frmLead.lead_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             	</a>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left" valign="top"><?php echo htmlspecialchars("Requirement Description")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
            	<?php 
					if($iseditmode != 1){ ?>
                      	<textarea name="prod_desc" id="prod_desc" cols="80" rows="5"></textarea>
                <?php 
					}else{
				?>
						<textarea style="width:350px; height:80px;" cols="42" rows="5" name="prod_desc" id="prod_desc"><?=$row['prod_desc']?></textarea>
                 <?php } ?>
	            <?php echo htmlspecialchars("*")."&nbsp;";  ?>
            </td>
		</tr>
        
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Followup Date")."&nbsp;" ?>
            </td>
			<td class="dr" align="left">
             
             <input type="text" name="follow_up_date" size="12" maxlength="12" value="<?php echo $row['follow_up_date'] ?>">
             <a href="javascript:show_calendar('document.frmLead.follow_up_date', document.frmLead.follow_up_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             </a>
                 
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        </table>
        </div>
        <br>
        <input type="button" id="displayText1" onClick="javascript:sizeTbl1();" value="HIDE DEMO INFO">
		<div id="toggleText1" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	<tr>
				<td class="hr" align="left"><?php echo htmlspecialchars("Demo Date")."&nbsp;" ?></td>
				<td class="dr" align="left">
				<?php	
					if($row["proposal_demo_date"]=='0000-00-00 00:00:00'){
                ?>
                	<input type="text" name="proposal_demo_date" size="12" maxlength="12">
                <?php	
                }else{
				?>
                	<input type="text" name="proposal_demo_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['proposal_demo_date'])); ?>">
                <?php } ?>
             	  <a href="javascript:show_calendar('document.frmLead.proposal_demo_date', document.frmLead.proposal_demo_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             	  </a>
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            	</td>
			</tr>
            <tr>
				<td class="hr" align="left" valign="top"><?php echo htmlspecialchars("Demo Comments")."&nbsp;" ?></td>
				<td class="dr" align="left">
            	<?php if($iseditmode == 1){ ?>
            	<textarea style="width:350px; height:80px; text-align:left; vertical-align:top;" cols="42" rows="5" name="proposal_demo_remark"><?=$row["proposal_demo_remark"]?></textarea>
                <?php }else{ ?>
                	<textarea name="proposal_demo_remark" cols="80" rows="5"></textarea>
                <?php }?>
            	</td>
        	</tr>
        </table>
        </div>
        <br>
        <input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="HIDE QUOTE INFO">
		<div id="toggleText2" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	<tr>
				<td class="hr" align="left"><?php echo htmlspecialchars("Quote Date")."&nbsp;" ?></td>
				<td class="dr" align="left">
                  <input type="text" name="proposal_quote_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['proposal_quote_date'])) ?>">
             	  <a href="javascript:show_calendar('document.frmLead.proposal_quote_date', document.frmLead.proposal_quote_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             	  </a>
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            	</td>
			</tr>
            <tr>
				<td class="hr" align="left" valign="top"><?php echo htmlspecialchars("Quote Comments")."&nbsp;" ?></td>
				<td class="dr" align="left">
            	<?php if($iseditmode == 1){ ?>
            	<textarea style="width:350px; height:80px; text-align:left; vertical-align:top;" cols="42" rows="5" name="proposal_quote_remark"><?=$row["proposal_quote_remark"]?></textarea>
                <?php }else{ ?>
                	<textarea name="proposal_quote_remark" cols="80" rows="5"></textarea>
                <?php }?>
            	</td>
        	</tr>
            <?php
			if(dataFound()){
			?>
            	<tr>
            		<td colspan="2">
                		<table width="100%">
                    		<tr>
                                <td>Category Name</td>
                                <td>Search By</td>
                                <td>Product Name</td>
                                <td>Order Qty</td>
                                <td>Price Per Unit</td>
                                <td>Amount</td>
                              	<td>&nbsp;</td>
                            </tr>
                        	<?php
								dataRetrieve();
							?>
						</table>
                     </td>
                 </tr>
             <?php } ?>
        </table>
        </div>
   
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

function dataRetrieve($lid){
	global $conn;
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.lead_id='$lid'";
	echo "Query :".$ret_qry."<br>";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table width="100%" border="1">
        	<tr class="hr"  align='center'>
            	<td width="20%">Category Name</td>
                <td width="30%">Product Name</td>
                <td width="10%">Order Qty</td>
                <td width="15%">Price Per Unit</td>
                <td width="15%">Amount</td>
                <td width="5%">&nbsp;</td>
                <td width="5%">&nbsp;</td>
            </tr>
    <?php
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$recid= $ret_row["id"];
			echo "<tr>";
			echo "<td>".$ret_row["cat_name"]."</td>";
			echo "<td>".$ret_row["prod_name"]."</td>";
			$pqty = $ret_row["prod_qty"];
			echo "<td>".$pqty."</td>";
			echo "<td>".$ret_row["prod_price"]."</td>";
			echo "<td>".$ret_row["prod_amount"]."</td>";
			
			echo "</tr>";
		}
	?>
    	</table>
    <?php
	}
}



function dataRemoves(){
	$del_qry = "delete from tbl_temp_product";
	$del_res = mysqli_query($del_qry);
}
/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
  global $recstatus;
  $leadid= $row["lead_id"];
  $remark = $row["remark"];
?>
	
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="2" class="hrhighlightorange" align="left"><b>Customer Info</b></td>
        </tr>
    	<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?></td>
		</tr>
		<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?></td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?></td>
		</tr>
       
		<tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Customer Requirement")."&nbsp;" ?></td>
			<td align="left"><?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])); ?></td>
		</tr>
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Reference By")."&nbsp;" ?></td>
			<td align="left"><?php echo strtoupper($row["ref_by"]) ?></td>
		</tr>
      
        <tr>
			<td width="20%" align="left"><?php echo htmlspecialchars("Reject Date")."&nbsp;" ?></td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["reject_date"])) ?></td>
		</tr>
        <tr>
        	<td align="left" width="20%">Remarks</td>
            <td align="left"><input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
			<?php 
            	if($remark != ""){
                	getRemarkDetails($remark);
                } ?>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php')"></td>
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
			
			showrow($row, $recid); 
		?>
      
		<br>

        </form>
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
	global $recstatus;
	
	//echo "Filter :".$filter." FilterField :".$filterfield."<br>";
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	$uid=$_SESSION['User_ID'];
	
	if($_SESSION['User_Design']=='ADMIN') {
		$qry = " and c.reject_status='1'";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, 
				c.proposal_quote_date, c.follow_up_date, c.reject_date,c.lead_id, c.cust_code, c.cust_require,c.remark,c.ref_by  
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.reject_status='1'";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, 
				c.proposal_quote_date, c.follow_up_date, c.reject_date,c.lead_id, c.cust_code, c.cust_require,c.remark,c.ref_by  
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.user_id='$uid'".$qry.") subq where 1";
	}
	
	
  	

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`reject_date` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') "; 
	
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
	
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$uid=$_SESSION['User_ID'];
	
	if($_SESSION['User_Design']=='ADMIN') {
		$qry = " and c.reject_status='1'";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, 
				c.proposal_quote_date, c.follow_up_date, c.reject_date,c.lead_id, c.cust_code, c.cust_require,c.remark,c.ref_by  
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.reject_status='1'";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, 
				c.proposal_quote_date, c.follow_up_date, c.reject_date,c.lead_id, c.cust_code, c.cust_require,c.remark,c.ref_by  
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.user_id='$uid'".$qry.") subq where 1";
	}

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`reject_date` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') "; 
	
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
