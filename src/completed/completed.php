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
					<option value="<?php echo "task_status" ?>"<?php if ($filterfield == "task_status") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Task Status") ?>
                    </option>
                   
                    <option value="<?php echo "total_amt" ?>"<?php if ($filterfield == "total_amt") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Quote Amount") ?>
                    </option>
                   
                     
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'completed/completed.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=reset')">
                </td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="completed/completed.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Company Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "lead_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
			
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "proposal_quote_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Quote Date") ?>
                </a>
            </td>
            
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "total_amt" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Total Amount") ?>
                </a>
            </td>
			<td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "balance_amt" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Balance Amount") ?>
                </a>
            </td>
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&order=<?php echo "task_status" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Task Status") ?>
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
	  	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      	</td>
      	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      	</td>
      	
	   	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php 
				if($row["proposal_quote_date"]== "0000-00-00 00:00:00"){
					echo "0000-00-00";
				}else{
					echo date("d-m-Y",strtotime($row["proposal_quote_date"]));
				}
			?>
                   
      	</td>
      	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo number_format($row["total_amt"],2,".",","); ?>
      	</td>
 		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo number_format($row["balance_amt"],2,".",","); ?>
      </td>
      <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=view&recid=<?php echo $i ?>')">
      		<?php echo number_format($row["task_status"],2,".",","); ?>
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
		showpagenav($page, $pagecount,$pagerange,'completed/completed.php'); 
} 
?></center>
<?php
 
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/
/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/

function paymentRetrieve($lid){
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
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=edit&recordstatus=editrecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Edit</a>
            </td>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=edit&recordstatus=deleterecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Delete</a>
			
			</td>
            <td align="center">
            <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=edit&recordstatus=confirmrecord&recordid=<?php echo $rec_id; ?>&recid=<?php echo $recid; ?>')">Confirm</a>
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

function taskRetrieve($lid){
	global $conn;
	global $recid;
	$ret_qry= "select c.task_name,b.id,b.task_summary,b.task_date
				from tbl_lead_master a,tbl_lead_task b,tbl_task c
				where a.lead_id=b.lead_id and b.task_code=c.task_code and b.lead_id='$lid'";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
		
	?>
		<table width="100%" border="1" style="border-collapse:collapse">
        	<tr class="hrhighlightorange"  align='center'>
            	<th width="25%">Task Name</th>
                <th width="50%">Task Summary</th>
                <th width="15%">Task Date</th>

            </tr>
    <?php
		$totamt = 0;
		while($ret_row= mysqli_fetch_assoc($ret_res)){
			$rec_id= $ret_row["id"];
			echo "<tr>";
			echo "<td>".$ret_row["task_name"]."</td>";
			echo "<td>".$ret_row["task_summary"]."</td>";
			echo "<td>".date("d-m-Y",strtotime($ret_row["task_date"]))."</td>";
			echo "</tr>";
        
		}
		
	?>
    
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
                    <input type="button" id="displayText3" onClick="javascript:sizeTbl3();" value="+">
                    <b>Payment Info</b>
               </td>
            </tr>
            </table>      
    </div>       
    <div id="toggleText3" style="display: none">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	<?php 
			if(paymentdataFound($leadid)){	
			?>
            <tr>
            	<td align="left" width="15%">Balance Amount</td>
                <td align="left"><?php echo $balanceamt; ?></td>
        	<tr>
            	<td colspan="2">
                	<?php paymentRetrieve($leadid); ?>
                </td>
            </tr>
         <?php 
		 	}
		 ?>
        </table>
    </div>
    <div align="left">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
            <tr>
                <td colspan="3" class="hrhighlightorange" align="left">
                    <input type="button" id="displayText4" onClick="javascript:sizeTbl4();" value="-">
                    <b>Task Info</b>
               </td>
            </tr>
        </table>      
    </div>    
    <div id="toggleText4" style="display: block">
		<table  border="0" cellspacing="1" cellpadding="5" width="100%">
        	<tr>
            	<td colspan="2">
                	<?php taskRetrieve($leadid); ?>
                </td>
            </tr>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true')"></td>
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
           			<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true&a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>')" value="Add New Payment">
                
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
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='DONE' and c.balance_amt <=0 ";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.quot_id,c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.remark,c.balance_amt,c.task_status 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='DONE' and c.balance_amt <=0 ";
		$sql = "select * from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.quot_id,c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.remark,c.balance_amt,c.task_status 
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
					or (`total_amt` like '" .$filterstr ."') or (task_status like '" .$filterstr ."')"; 
	
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
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='DONE' and c.balance_amt <=0 ";
		$sql = "select count(*) from (select c.prod_desc, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.proposal_demo_date, c.proposal_demo_remark,c.proposal_followup_date,  
				c.proposal_quote_date, c.follow_up_date, c.proposal_quote_no, c.proposal_quote_remark,c.lead_id, c.cust_code, c.cust_require,
				c.total_amt,c.discount_amt,c.balance_amt 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id".$qry.") subq where 1";
	}else{
		$qry = " and c.lead_book_status='1' and c.proposal_quote_status='1' and c.reject_status='0' and c.task_status='DONE' and c.balance_amt <=0 ";
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
					or (`total_amt` like '" .$filterstr ."') or (task_status like '" .$filterstr ."')";  
	
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
