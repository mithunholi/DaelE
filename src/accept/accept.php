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
	
	if(isset($_GET["recordid"])) $recordid = $_GET["recordid"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
	if (!isset($recstatus) && isset($_SESSION["recstatus"])) $recstatus = $_SESSION["recstatus"];
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
				if (isset($recstatus)) $_SESSION["recstatus"] = $recstatus;
  				if (isset($filter)) $_SESSION["filter"] = $filter;
  				if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				if (isset($leadId)) $_SESSION["leadId"] = $leadId;
				
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
                    <option value="<?php echo "follow_up_date" ?>"<?php if ($filterfield == "follow_up_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Follow Up Date") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'accept/accept.php?a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="accept/accept.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Customer Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "prod_desc" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Product Description") ?>
                </a>
            </td>
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "proposal_demo_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Demo Date") ?>
                </a>
            </td>
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "proposal_quote_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Quote Date") ?>
                </a>
            </td>
            
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?order=<?php echo "follow_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Follow Up Date") ?>
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
		$leadid = $row["lead_d"];
		$field = "lead_id";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
       <td align="left">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['lead_id']; ?>">
      </td>
	  <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      </td>
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])) ?>
      </td>
      
	  <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["proposal_demo_date"])) ?>
      </td>
      
	   <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["proposal_quote_date"])) ?>
      </td>
      
     
      <td align="right" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=view&recid=<?php echo $i ?>')">
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
		showpagenav($page, $pagecount,$pagerange,'accept/accept.php'); 
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
            <tr>
            	<td colspan="2">
                	<table width="100%">
                    	<?php
							if(notesInfoFound()){
								notesInfoRetrieve();
							}
						?>
                    	<tr>
                        	<td class="hr" align="left">Notes Head</td>
                            <td class="dr" align="left"><input type="text" size="50" maxlength="50" name="newnoteshead"></td>
                        </tr>
                        <tr>
                        	<td class="hr" align="left">Notes Description</td>
                            <td class="dr" align="left"><textarea name="newnotesdesc" cols="45" rows="3"></textarea></td>
                        </tr>
                     </table>
                </td>
            </tr>
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
                        
                        <tr>
                        	<td><select name="cname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=edit&ccode='+this.value)"><option><?php echo $categoryList; ?></option></select></td>
                            <td><input type="text" name="searchtext" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=edit&searchtext='+this.value)" value="<?php echo $searchtext; ?>"></td>
                        	<td><select name="pname" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=edit&pcode='+this.value+'&ccode='+document.frmLead.cname.value)"><option><?php echo $productList; ?></option></select></td>
                            
							
                            <td>
                            <input type="text" name="pqty" value="0" size="5" onChange="javascript:calculate(this.value,<?php echo $prate; ?>,<?php echo $ptax; ?>)">
                            </td>
                            <td><input type="text" name="pprice" value="<?php if($prate != 0){ echo $prate; } else { echo '0.00'; }?>" size="10"></td>
                            <td><input type="text" name="pamount" id="pamount" value="0.00" readonly size="10"></td>
                            <td><input type="button" name="btnProductAdd" value="Add" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'accept/accept.php?a=edit&recordstatus=newrecord&ccode='+document.frmLead.cname.value+'&pcode='+document.frmLead.pname.value+'&pqty='+document.frmLead.pqty.value+'&pprice='+document.frmLead.pprice.value)"></td>
                            
                        </tr>
                    </table>
                    
                    <?php 
						if($recordstatus=='newrecord'){
							dataAdd($ccode,$pcode,$pqty,$pprice);
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
								dataRetrieve();
							}
						}elseif($recordstatus=='deleterecord'){
							dataDelete($recordid);
							$recordstatus='';
							if(dataFound()){
								dataRetrieve();
							}
						}elseif($recordstatus=='editrecord'){
							dataEdit($recordid);
							$recordstatus='';
						}elseif($recordstatus=='updaterecord'){
							dataUpdate($recordid,$editedqty,$editedprice,$editedtax);
							$editedqty=0;
							$editedprice=0;
							$editedtax=0;
							if(dataFound()){
								dataRetrieve();
							}
						}elseif($recordstatus=='cancelrecord'){
							if(dataFound()){
								dataRetrieve();
							}
						}else{
							if(dataFound()){
								dataRetrieve();
							}
						}
						
					?>
                </td>
            </tr>
        </table>
        </div>
   
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

function dataAdd($cc,$pc,$pq,$pr){
	global $conn;
	$chk_qry = "select * from tbl_temp_product where cat_code='$cc' and prod_code='$pc'";
	$chk_res = mysqli_query($conn,$chk_qry);
	if(mysqli_num_rows($chk_res)<=0){
		$amt = $pq * $pr;
		$ins_qry = "insert into tbl_temp_product (cat_code,prod_code,prod_qty,prod_price,prod_amount) values ('$cc','$pc','$pq','$pr','$amt')";
		$ins_res = mysqli_query($conn,$ins_qry);
	}
}

function dataDelete($rid){
	global $conn;
	$del_qry = "delete from tbl_temp_product where id='$rid'";
	mysqli_query($conn,$del_qry);
}

function dataUpdate($rid,$eqty,$eprice,$etax){
	global $conn;
	$amt = $eqty * $eprice;
	$totamt = $amt + ($amt * $etax);
	$update_qry = "update tbl_temp_product set prod_qty = '$eqty',prod_amount='$totamt' where id='$rid'";
	mysqli_query($conn,$update_qry);
}

function dataEdit($rid){
	global $conn;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_temp_product a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
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
		$i=1;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$recid= $ret_row["id"];
			
			echo "<tr>";
			echo "<td>".$ret_row["cat_name"]."</td>";
			echo "<td>".$ret_row["prod_name"]."</td>";
			if($rid==$recid){
				$price = $ret_row["prod_price"];
				$tax = $ret_row["prod_tax"];
				echo "<td>";
			?>
            	<input type="text" name="eprod_qty" id="eprod_qty" value="<?php echo $ret_row["prod_qty"]; ?>" size="5" maxlength="5">
            <?php
				echo "</td>";
			}else{
            	echo "<td>".$ret_row["prod_qty"]."</td>";
			}
			echo "<td>".$ret_row["prod_price"]."</td>";
			echo "<td>".$ret_row["prod_amount"]."</td>";
			echo "<td>";
			?>
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'accept/accept.php?a=edit&recordstatus=updaterecord&recordid=<?php echo $recid; ?>&eqty='+document.frmLead.eprod_qty.value+'&ettax=<?php echo $tax; ?>&etprice=<?php echo $price; ?>')">Update</a>
			<?php
			echo "<td>";
			?>
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'accept/accept.php?a=edit&recordstatus=cancelrecord&recordid=<?php echo $recid; ?>')">Cancel</a>
			<?php
			echo "</td>";
			echo "</tr>";
		}
	?>
    	</table>
    <?php
	}
}


function dataRetrieve($lid){
	global $conn;
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.lead_id='$lid'";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table width="100%" border="1">
        	<tr class="hr"  align='center'>
            	<td width="25%">Category Name</td>
                <td width="35%">Product Name</td>
                <td width="10%">Order Qty</td>
                <td width="15%">Price Per Unit</td>
                <td width="15%">Amount</td>
              
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
	$del_res = mysqli_query($conn,$del_qry);
}
/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
  global $recstatus;
  $leadid = $row["lead_id"];
?>
	<div align="left"><input type="button" id="displayText" onClick="javascript:sizeTbl();" value="SHOW CUSTOMER INFO"></div>
	<div id="toggleText" style="display: none">
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="50%">
    	<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Customer Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?></td>
		</tr>
       
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Product Description")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])); ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Reference By")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo strtoupper($row["ref_by"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Customer Require")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo strtoupper($row["cust_require"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Follow Up Date")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo date("d-m-Y",strtotime($row["follow_up_date"])) ?></td>
		</tr>
    </table>
    </div>
    <br>
    <div align="left"><input type="button" id="displayText1" onClick="javascript:sizeTbl1();" value="HIDE DEMO INFO" align="left"></div>
	<div id="toggleText1" style="display: block">
    <table class="tbl1" border="0" cellspacing="1" cellpadding="5" width="50%">
		<tr>
        	<td class="hr" align="left"><?php echo htmlspecialchars("Demo Date")."&nbsp;" ?></td>
            <td class="dr" align="left">
            <?php 
            	if($row["proposal_demo_date"]=='0000-00-00 00:00:00'){
                	echo '0000-00-00 00:00:00';
                }else{
                	echo date("d-m-Y",strtotime($row["proposal_demo_date"]));
                }
             ?>
             </td>
        </tr>
        <tr>
        	<td class="hr" align="left"><?php echo htmlspecialchars("Demo Remark")."&nbsp;" ?></td>
            <td class="dr" align="left"><?php echo htmlspecialchars($row["proposal_demo_remark"]) ?></td>
        </tr>
     </table>
     </div>
     <br>
     <div align="left"><input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="HIDE QUOTE INFO" align="left"></div>
	 <div id="toggleText2" style="display: block">
     <table class="tbl" border="0" cellspacing="1" cellpadding="5" width="50%">
        <tr>
        	<td class="hr" align="left"><?php echo htmlspecialchars("Quote Date")."&nbsp;" ?></td>
            <td class="dr" align="left">
            <?php
            	if($row["proposal_quote_date"]=='0000-00-00 00:00:00' or $row["proposal_quote_date"]==''){
                	echo '0000-00-00 00:00:00';
                }else{
                	echo $row["proposal_quote_date"];
                }
            ?>
            </td>
        </tr>
        <tr>
           <td class="hr" align="left"><?php echo htmlspecialchars("Quote Remark")."&nbsp;" ?></td>
           <td class="dr" align="left"><?php echo htmlspecialchars($row["proposal_quote_remark"]) ?></td>
        </tr>
     </table>
     </div>
     <?php
	 	if(dataFounds($leadid)){
	 ?>
     		<br>
            <div align="left"><input type="button" id="displayText3" onClick="javascript:sizeTbl3();" value="HIDE QUOTATION" align="left"></div>
            <div id="toggleText3" style="display: block">
             <?php dataRetrieve($leadid) ?>
            </div>
	<?php 
		}
	?>
    
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?action=add" method="post">
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
    <form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?action=update" method="post">
    	<input type="hidden" name="action" value="editarea">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
		
		<?php
			
			showrow($row, $recid); 
		?>
      
		<br>

		<hr size="1" noshade>
		<table class="bd" border="0" cellspacing="1" cellpadding="4">
			<tr>
				<td>
           			<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Edit Record">
                
            	</td>
				<td>
            		<input type="button" name="btnReject" onClick="javascript:formget(this.form,'proposal/processProposal.php?action1=reject');javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?status=true');javascript:printHeader('Lead Admin');" value="Reject Record">
                
            	</td>
            	<td>
            		<input type="button" name="btnAccept" onClick="javascript:formget(this.form,'proposal/processProposal.php?action1=accept');javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/accept.php?status=true');javascript:printHeader('Lead Admin');" value="Accept Record">
                
            	</td>
			</tr>
		</table>
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
	<form name="frmLead" enctype="multipart/form-data" action="proposal/processProposal.php?action=update" method="post">
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
	<form name="frmLead" action="lead/processLead.php?action=delete" method="post">
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
	
	
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0'";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark, 
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	
	
	
  	

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`prod_desc` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by lead_id";
	}
	
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    //echo "SQL : ".$sql."<br>";
  	$res = mysqli_query($conn,$sql) or die(mysqli_error());
	
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
  	
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0'";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark, 
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	

	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`prod_desc` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') "; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by lead_id";
	}
	
	
  	//echo "SQL Count :".$sql."<br>";
  	$res = mysqli_query($conn,$sql) or die(mysqli_error());
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
