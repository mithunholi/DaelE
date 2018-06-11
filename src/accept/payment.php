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
	$_SESSION["cmbinstype"] = "";
	$_SESSION["cmbpaytype"] = "";
	$_SESSION["cmbpaymethod"] = "";
	$_SESSION["paymentamount"] = "";
	$_SESSION["paymentdate"] = "";
	$_SESSION["paymentnumber"] = "";
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
	if(isset($_GET["recordstatus"])) $recordstatus = $_GET["recordstatus"];
	if(isset($_GET["recordid"])) $recordid = $_GET["recordid"];
	if(isset($_GET["partno"])) $partno = $_GET["partno"];
	if(isset($_GET["cmbinstype"])) $cmbinstype = $_GET["cmbinstype"];
	if(isset($_GET["cmbpaytype"])) $cmbpaytype = $_GET["cmbpaytype"];
	if(isset($_GET["cmbpaymethod"])) $cmbpaymethod = $_GET["cmbpaymethod"];
	if(isset($_GET["paymentamount"])) $paymentamount = $_GET["paymentamount"];
	if(isset($_GET["paymentdate"])) $paymentdate = $_GET["paymentdate"];
	if(isset($_GET["paymentnumber"])) $paymentnumber = $_GET["paymentnumber"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
	if (!isset($recstatus) && isset($_SESSION["recstatus"])) $recstatus = $_SESSION["recstatus"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($leadId) && isset($_SESSION["leadId"])) $leadId = $_SESSION["leadId"];
	if (!isset($cmbinstype) && isset($_SESSION["cmbinstype"])) $cmbinstype = $_SESSION["cmbinstype"];
	if (!isset($cmbpaytype) && isset($_SESSION["cmbpaytype"])) $cmbpaytype = $_SESSION["cmbpaytype"];
	if (!isset($cmbpaymethod) && isset($_SESSION["cmbpaymethod"])) $cmbpaymethod = $_SESSION["cmbpaymethod"];
	if (!isset($paymentamount) && isset($_SESSION["paymentamount"])) $paymentamount = $_SESSION["paymentamount"];
	if (!isset($paymentdate) && isset($_SESSION["paymentdate"])) $paymentdate = $_SESSION["paymentdate"];
	if (!isset($paymentnumber) && isset($_SESSION["paymentnumber"])) $paymentnumber = $_SESSION["paymentnumber"];
	
	if (isset($leadId) && (int)$leadId > 0) {
		$queryString = " and lead_code=$leadId";
	} else {
		$queryString = '';
	}
	//echo "PCODE :::".$pcode."<br>";
?>
	<html>
		<head>
			<title>mCRM -- Accepted Screen</title>
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
				/*if (isset($cmbinstype)) $_SESSION["cmbinstype"] = $cmbinstype;
				if (isset($cmbpaytype)) $_SESSION["cmbpaytype"] = $cmbpaytype;
				if (isset($cmbpaymethod)) $_SESSION["cmbpaymethod"] = $cmbpaymethod;
				if (isset($paymentamount)) $_SESSION["paymentamount"] = $paymentamount;
				if (isset($paymentdate)) $_SESSION["paymentdate"] = $paymentdate;
				if (isset($paymentnumber)) $_SESSION["paymentnumber"] = $paymentnumber;*/
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
                     <option value="<?php echo "lead_book_date" ?>"<?php if ($filterfield == "lead_book_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Lead Book Date") ?>
                    </option>
					<option value="<?php echo "proposal_quote_date" ?>"<?php if ($filterfield == "proposal_quote_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Proposal Quote Date") ?>
                    </option>
                   
                    <option value="<?php echo "total_amt" ?>"<?php if ($filterfield == "total_amt") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Quote Amount") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'accept/payment.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="accept/payment.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Company Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "quot_id" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Quote No") ?>
                </a>
            </td>
            
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "proposal_quote_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Quote Date") ?>
                </a>
            </td>
            
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "total_amt" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Total Amount") ?>
                </a>
            </td>
			<td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&order=<?php echo "balance_amt" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Balance Amount") ?>
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
	  	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      	</td>
      	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      	</td>
      	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo $row["quot_id"] ?>
      	</td>
	   	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php 
				if($row["proposal_quote_date"]== "0000-00-00 00:00:00"){
					echo "0000-00-00";
				}else{
					echo date("d-m-Y",strtotime($row["proposal_quote_date"]));
				}
			?>
                   
      	</td>
      	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo number_format($row["total_amt"],2,".",","); ?>
      	</td>
 		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo number_format($row["balance_amt"],2,".",","); ?>
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
		showpagenav($page, $pagecount,$pagerange,'accept/payment.php'); 
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
  global $recordstatus;
  global $recordid;
  global $partno;
  global $cmbinstype;
  global $cmbpaytype;
  global $cmbpaymethod;
  global $paymentamount;
  global $paymentdate;
  global $paymentnumber;
  global $today;
  global $a;
  $catcode="";
 
  $leadid=$row["lead_id"];
  $quoteid = $row["quot_id"];
  $totalamt = $row["total_amt"];
  $discountamt = $row["discount_amt"];
  $balanceamt = $row["balance_amt"];
  echo "Lead ID :".$leadid."=DiscountAmt :".$discountamt."=Balance Amt :".$balanceamt."<br>";
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
           
            </td>
		</tr>
        </table>
        </div>
		<div align="left">
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
            <tr>
                <td colspan="3" class="hrhighlightorange" align="left">
                    <input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="+">
                    <b>Quotation Info</b>
               </td>
            </tr>
            </table>      
    	</div>       
        <div id="toggleText2" style="display: none">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	
            <tr>
            	<td colspan="2">
                	<?php 
						if(dataFounds($leadid)){	
							dataRet($totalamt,$discountamt);
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
                             <input type="text" name="proposal_quote_no" size="20" maxlength="20" value="<?php echo $row['proposal_quote_no'] ?>" readonly>
                             <?php echo htmlspecialchars("*")."&nbsp;" ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Quote Date")."&nbsp;" ?></td>
                            <td align="left">
                            <?php	
                                if($row["proposal_quote_date"]=='0000-00-00 00:00:00'){
                            ?>
                                <input type="text" name="proposal_quote_date" size="12" maxlength="12" readonly>
                            <?php }else{ ?>
                              <input type="text" name="proposal_quote_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['proposal_quote_date'])) ?>" readonly>
                            <?php } ?>
                             
                            </td>
                        </tr>
                       
                        <tr>
                            <td align="left" width="15%"><?php echo htmlspecialchars("Proposal Followup Date")."&nbsp;" ?></td>
                            <td align="left">
                            <?php	

                                if($row["proposal_followup_date"]=='0000-00-00 00:00:00'){
                            ?>
                            <input type="text" name="proposal_followup_date" size="12" maxlength="12" readonly>
                            <?php } else { ?>
                           <input type="text" name="proposal_followup_date" size="12" maxlength="12" value="<?php echo date('d-m-Y',strtotime($row['proposal_followup_date'])) ?>" readonly>
                           <?php } ?>
                            
                            </td>
                        </tr>
                     
                      
                	</table>
            	</td>
        	</tr>             
        </table>
        </div>
       
        <div align="left">
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
            <tr>
                <td colspan="3" class="hrhighlightorange" align="left">
                    <input type="button" id="displayText3" onClick="javascript:sizeTbl3();" value="-">
                    <b>Payment Info</b>
               </td>
            </tr>
            </table>      
    	</div>       
        <div id="toggleText3" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
        <?php 
			if(paymentdataFound($leadid) && $a == "Edit"){	
		?>
        	<tr>
            	<td colspan="2">
                	<?php dataRetrieve($leadid); ?>
                </td>
            </tr>
         <?php 
		 	}elseif(paymentdataFound($leadid) && ($recordstatus == "editrecord" || $recordstatus == "confirmrecord" || $recordstatus == "deleterecord")){
				$edit_qry = "select a.id,a.lead_id,a.quot_id,a.pay_type,a.pay_method,a.cheque_number,a.paid_amount,a.pay_date,a.bank_info 
							 from tbl_lead_payment a where a.id='$recordid'";
				//echo "Edit Qry :".$edit_qry."<br>";
				$edit_res = mysqli_query($conn,$edit_qry);
				if(mysqli_num_rows($edit_res)>0){
					$edit_row = mysqli_fetch_assoc($edit_res);
					$cmbpaytype = $edit_row["pay_type"];
					$cmbpaymethod = $edit_row["pay_method"];
					$bank_info =$edit_row["bank_info"];
					$paymentamount=$edit_row["paid_amount"];
					$paymentdate = $edit_row["pay_date"];
					$paymentnumber= $edit_row["cheque_number"];
				}
			}
		 ?>
         	<tr>
            	<td align="left" width="15%">Balace Amount</td>
                <td align="left">
                <input type="hidden" name="recordstatus" value="<?php echo $recordstatus; ?>">
                <input type="hidden" name="recordid" value="<?php echo $recordid; ?>">
                <input type="text" name="balance_amt" value="<?php echo $balanceamt; ?>" readonly>
               	</td>
            </tr>
			<tr>
            	<td align="left" width="15%">Payment Type</td>
                <td align="left">
                	<select name="cmbpaytype">
                    	<option value="">--Choose Any One--</option>
                        <?php if($cmbpaytype != "" and $cmbpaytype=="advance"){ ?>
                        <option value="advance" selected>Advance</option>
                        <?php }else{ ?>
                        <option value="advance">Advance</option>
                        <?php } ?>
                        <?php if($cmbpaytype != "" and $cmbpaytype=="install"){ ?>
                        <option value="install" selected>Installment</option>
                        <?php }else{ ?>
                        <option value="install">Installment</option>
                        <?php } ?>
                   </select>
                   <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
           </tr>
           <tr>
           		<td align="left" width="15%">Payment Method</td>
                <td align="left">
                	<select name="cmbpaymethod" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=edit&cmbpaytype='+document.frmLead.cmbpaytype.value+'&cmbpaymethod='+this.value)">
                    	<option value="">--Choose Any One--</option>
                        <?php if($cmbpaymethod != ""  and $cmbpaymethod == "cash"){ ?>
                        <option value="cash" selected>Cash</option>
                        <?php }else{ ?>
                        <option value="cash">Cash</option>
                        <?php } ?>
                        <?php if($cmbpaymethod != ""  and $cmbpaymethod == "cheque"){ ?>
                        <option value="cheque" selected>Cheque</option>
                         <?php }else{ ?>
                         <option value="cheque" >Cheque</option>
                         <?php } ?>
                         <?php if($cmbpaymethod != ""  and $cmbpaymethod == "dd"){ ?>
                        <option value="dd" selected>DD</option>
                        <?php }else{ ?>
                        <option value="dd">DD</option>
                        <?php } ?>
                        <?php if($cmbpaymethod != ""  and $cmbpaymethod == "wt"){ ?>
                        <option value="wt" selected>Wire Transfer</option>
                         <?php }else{ ?>
                        <option value="wt">Wire Transfer</option>
                        <?php } ?>
                    </select>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
               </td>
          </tr>
          <?php if($cmbpaymethod != "" and $cmbpaymethod !="cash"){ ?>
          <tr>     
                <td align="left" width="15%"><?php echo ucfirst($cmbpaymethod)." Number" ?></td>
                <td align="left"><input type="text" name="paymentnumber" size="30" maxlength="30" value="<?php echo $paymentnumber; ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
          </tr>
          <tr>     
                <td align="left" width="15%">Bank Information</td>
                <td align="left">
                	<?php if($bank_info != "") { ?>
                    <textarea name="bankinfo" rows="3" cols="45"><?=$bank_info?></textarea>
                    <?php }else{ ?> 
                	<textarea name="bankinfo" rows="3" cols="45"></textarea>
                    <?php } ?>
                    <?php echo htmlspecialchars("*")."&nbsp;" ?>
               </td>
          </tr>
          <?php } ?>
          <tr>
          		<td align="left" width="15%">Pay Amount</td>
                <td align="left"><input type="text" name="paymentamount" size="15" maxlength="15" value="<?php echo $paymentamount ?>">
				<?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
		  </tr>
          <?php 
		  	if($paymentdate == ""){
				$paymentdate = date("d-m-Y",strtotime($today));
			}else{
				$paymentdate = date("d-m-Y",strtotime($paymentdate));
			}
		  ?>
          <tr>
          		<td align="left" width="15%">Pay Date</td>
                <td align="left">
                	 <input type="text" name="payment_date" size="12" maxlength="12" value="<?php echo $paymentdate ?>">
                    <a href="javascript:show_calendar('document.frmLead.payment_date', document.frmLead.payment_date.value);">
                       <img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a><?php echo htmlspecialchars("*")."&nbsp;" ?>
                </td>
          </tr>
        </table>
        </div>
        
      
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

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

function dataEditRetrieve($lid){
	global $conn;
	$editqry = "select * from tbl_lead_child where lead_id='$lid'";
	$editres= mysqli_query($conn,$editqry);
	if(mysqli_num_rows($editres)>0){
		$tempqry = mysqli_query($conn,"select * from tbl_temp_product where lead_id='$lid'");
		if(mysqli_num_rows($tempqry)<=0){
			$qrydata = "insert into tbl_temp_product (lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount) 
							select lead_id,cat_code,prod_code,prod_qty,prod_price,prod_amount,prod_tax,prod_discount from tbl_lead_child where lead_id='$lid'";
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

function dataEdit($rid,$leadid){
	global $conn;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_lead_payment a,tbl_lead_master b
				where a.lead_id=b.lead_id and a.id='$rid' and a.lead_id='$leadid'";
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
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'accept/payment.php?status=true&a=edit&recordstatus=updaterecord&recordid=<?php echo $recid; ?>&eqty='+document.frmLead.eprod_qty.value+'&ettax='+document.frmLead.eprod_tax.value+'&etdiscount='+document.frmLead.eprod_discount.value+'&etprice=<?php echo $price; ?>')">Update</a>
			<?php
			echo "</td><td>";
			?>
			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>', 'accept/payment.php?status=true&a=edit&recordstatus=cancelrecord&recordid=<?php echo $recid; ?>')">Cancel</a>
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
function dataRetrieve($lid){
	global $conn;
	global $recid;
	$ret_qry= "select b.id,b.pay_type,b.pay_method,b.paid_amount,b.pay_date,b.record_status
				from tbl_lead_master a,tbl_lead_payment b
				where a.lead_id=b.lead_id and b.lead_id='$lid'";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table width="100%" border="1" style="border-collapse:collapse">
        	<tr class="hrhighlightorange"  align='center'>
            	<th width="15%">Payment Type</th>
                <th width="25%">Payment Method</th>
                <th width="10%">Paid Amount</th>
                <th width="10%">Paid Date</th>
                <th width="5%">&nbsp;</th>
                <th width="5%">&nbsp;</th>
                <th width="5%">&nbsp;</th>
            </tr>
    <?php
		$totamt = 0;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$rec_id= $ret_row["id"];
			echo "<tr>";
			echo "<td>".$ret_row["pay_type"]."</td>";
			echo "<td>".$ret_row["pay_method"]."</td>";
			echo "<td align='right'>".number_format($ret_row["paid_amount"],2,'.',',')."</td>";
			echo "<td align='right'>".date("d-m-Y",strtotime($ret_row["pay_date"]))."</td>";
			if($ret_row["record_status"] != "confirmrecord"){
			?>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=edit&recordstatus=editrecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Edit</a>
            </td>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=edit&recordstatus=deleterecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Delete</a>
			
			</td>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=edit&recordstatus=confirmrecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Confirm</a>
            </td>
            </tr>
        <?php
			}else{
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
			}    
			$totamt = $totamt + $ret_row["paid_amount"];
		}
		
	?>
    	
        <?php $totamt = $totamt - $damt; ?>
    	<tr>
        	<td colspan="2" align="right">Total Amount</td>
            <td align="right"><?php echo number_format($totamt,2,'.',','); ?></td>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>
    <?php
	}
}


function dataRet($totamt,$damt){
	global $conn;
	$ret_qry= "select a.id,b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_temp_product a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table id="gradientDemo" width="100%">
        	<tr>
            	<th width="20%">Category Name</th>
                <th width="30%">Product Name</th>
                <th width="10%">Order Qty</th>
                <th width="5%">Tax</th>
                <th width="10%">Discount</th>
                <th width="15%">Price Per Unit</th>
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
			echo "<td align='center'>".$ret_row["prod_tax"]."</td>";
			echo "<td align='center'>".$ret_row["prod_discount"]."</td>";
			echo "<td align='right'>".number_format($ret_row["prod_price"],2,'.',',')."</td>";
			echo "<td align='right'>".number_format($pamt1,2,'.',',')."</td>";
			
			?>
            
            </tr>
        <?php    
			$totamt = $totamt + $pamt1;
		}
		
	?>
    	<tr>
        	<td colspan="6" align="right">Discount</td>
        	<td align="right"><?php echo $damt; ?></td>
        </tr>
        <?php $totamt = $totamt - $damt; ?>
    	<tr>
        	<td colspan="6" align="right">Total Amount</td>
            <td align="right"><?php echo $totamt; ?></td>
        </tr>
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
function showrow($row, $recid)
{
  global $recstatus;
  $leadid = $row["lead_id"];
  $totalamt = $row["total_amt"];
  $discountamt = $row["discount_amt"];
   $remark = $row["remark"];
  $quoteid = $row["quot_id"];
  $balanceamt = $row["balance_amt"];
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
    	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="3" class="hrhighlightorange" align="left">
           		<input type="button" id="displayText2" onClick="javascript:sizeTbl2();" value="+">
                <b>Quotation Info</b>
           </td>
        </tr>
		</table>      
    </div>
     
	 <div id="toggleText2" style="display: none">
     
     	<?php 
			if(dataFounds($leadid)){
			 	dataRetrieves($leadid,$totalamt,$discountamt);
			}
		?> 
        <table class="hrhighlightblue" border="0" cellspacing="1" cellpadding="5" width="100%">
        	<tr>
           		<td align="left" width="15%"><?php echo htmlspecialchars("Quote No")."&nbsp;" ?></td>
                <td align="left" width="3%">:</td>
           		<td align="left"><?php echo htmlspecialchars($row["proposal_quote_no"]) ?></td>
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
                <td align="left" width="15%"><?php echo htmlspecialchars("Remark")."&nbsp;" ?></td>
                <td align="left" width="3%">:</td>
                <td align="left"><input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
					<?php 
						if($remark != ""){
							
                        	getRemarkDetails($remark);
                       	} 
					?>
                </td>
        	</tr>
     	</table>
     </div>
     <div align="left">
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
            <tr>
                <td colspan="3" class="hrhighlightorange" align="left">
                    <input type="button" id="displayText3" onClick="javascript:sizeTbl3();" value="-">
                    <b>Payment Info</b>
               </td>
            </tr>
            </table>      
    	</div>       
        <div id="toggleText3" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	<?php 
			if(paymentdataFound($leadid)){	
			?>
            <tr>
            	<td align="left" width="15%">Balance Amount</td>
                <td align="left"><?php echo $balanceamt; ?></td>
        	<tr>
            	<td colspan="2">
                	<?php dataRetrieve($leadid); ?>
                </td>
            </tr>
         <?php 
		 	}
		 ?>
        </table>
        </div>
      
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/



function dataRetrieves($lid,$totamt,$damt){
	global $conn;
	
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount,a.prod_tax,a.prod_discount 
				from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.lead_id='$lid'";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table id="gradientDemo">
        	<tr>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true')"></td>
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
    <form name="frmLead" enctype="multipart/form-data" action="accept/processAccept.php?status=true&action=update" method="post">
    	<input type="hidden" name="action" value="editarea">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
		<input type="hidden" name="quot_id" value="<?php echo $row["quot_id"] ?>">
		<?php
			
			showrow($row, $recid); 
		?>
      
		<br>
		<?php if($row["balance_amt"] > 0){ ?>
		<hr size="1" noshade>
		<table class="bd" border="0" cellspacing="1" cellpadding="4">
			<tr>
            
				<td>
           			<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true&a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Add New Payment">
                
            	</td>
				
			</tr>
		</table>
        <?php } ?>
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
	<form name="frmLead" enctype="multipart/form-data" action="accept/processPayment.php?status=true&action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="cust_id" value="<?php echo $row["cust_code"] ?>">
        <input type="hidden" name="quot_id" value="<?php echo $row["quot_code"] ?>">
		<?php 
			showroweditor($row, true); 
			
		?>
        
		<p>
        <input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(lead_payment_validate()==true){javascript: formget(this.form, 'accept/processPayment.php');}">
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
	<form name="frmLead" action="accept/processPayment.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="quot_id" value="<?php echo $row["quot_id"] ?>">
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
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='NOT DONE' and c.balance_amt >0 ";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.quot_id,c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.remark,c.balance_amt 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='NOT DONE' and c.balance_amt >0 ";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.quot_id,c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.remark,c.balance_amt 
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
					or (`total_amt` like '" .$filterstr ."') "; 
	
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
	$res = sql_select();
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$uid=$_SESSION['User_ID'];
	
	if($_SESSION['User_Design']=='ADMIN') {
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='NOT DONE' and c.balance_amt >0 ";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.balance_amt 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='NOT DONE' and c.balance_amt >0 ";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.balance_amt 
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
					or (`total_amt` like '" .$filterstr ."') "; 
	
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
