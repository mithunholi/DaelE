<?php
session_start();
if(isset($_GET["status"]) && $_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["leadId"]="";
	$_SESSION["pageId"]="";
	$_SESSION["cust_code"]="";
	$status = false;
	$fromdate = date("d-m-Y");
	$todate = date("d-m-Y");
  }
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	
	$user_name=$_SESSION['User_ID'];


  	if (isset($_GET["order"])) $order = @$_GET["order"];
  	if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  	if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  	if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  	if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
	if(isset($_GET["leadId"])) $leadId = $_GET["leadId"];
	if(isset($_GET["cust_name"])) $cust_name = $_GET["cust_name"];
	if(isset($_GET["cust_code"])) $cust_code = $_GET["cust_code"];
	if(isset($_GET["fromdate"])) $fromdate = $_GET["fromdate"];
	if(isset($_GET["todate"])) $todate = $_GET["todate"];
	
	//echo "From Date1:".$fromdate."<br>";
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($leadId) && isset($_SESSION["leadId"])) $leadId = $_SESSION["leadId"];
	if (!isset($cust_code) && isset($_SESSION["cust_code"])) $cust_code = $_SESSION["cust_code"];
	if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
	if (!isset($todate) && isset($_SESSION["todate"])) $todate = $_SESSION["todate"];
	if (isset($leadId) && (int)$leadId > 0) {
		$queryString = " and lead_code=$leadId";
	} else {
		$queryString = '';
	}
	//echo "From Date2:".$fromdate."<br>";
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
			
			if(isset($_GET["a"]) && $_GET["a"]=="reset"){
	 			$fromdate = date("d-m-Y");
				$todate = date("d-m-Y");
			}
	
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
  				if (isset($filter)) $_SESSION["filter"] = $filter;
  				if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  				if (isset($pageId)) $_SESSION["pageId"] = $pageId;
				if (isset($leadId)) $_SESSION["leadId"] = $leadId;
				if (isset($cust_code)) $_SESSION["cust_code"] = $cust_code;
				if (isset($fromdate)) $_SESSION["fromdate"] = $fromdate;
				if (isset($todate)) $_SESSION["todate"] = $todate;
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
	global $today;
	global $fromdate;
	global $todate;
	//echo "Show Rec :".$showrecs."<br>";
	//echo "Page :".$page."<br>";
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
						<?php echo htmlspecialchars("Company Name")?>
                    </option>
					<option value="<?php echo "prod_desc" ?>"<?php if ($filterfield == "prod_desc") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Customer Requirement") ?>
                    </option>
                    <option value="<?php echo "customer_mobno" ?>"<?php if ($filterfield == "ref_by") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Mobile No") ?>
                    </option>
                    <option value="<?php echo "customer_cperson" ?>"<?php if ($filterfield == "ref_by") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Contact Person") ?>
                    </option>
                  
                    <option value="<?php echo "follow_up_date" ?>"<?php if ($filterfield == "follow_up_date") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Follow Up Date") ?>
                    </option>
                 </select>
               </td>
             </tr>
             <tr>  
			   <td colspan="5" align="left"><b>Date</b>&nbsp;
                    <input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>">
             		<a href="javascript:show_calendar('document.frmListProduct.fromdate', document.frmListProduct.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
               </td>
              </tr>
              <tr>  
               <td colspan="5" align="left">
                	<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'followup/followup.php?a=filter'+'&fromdate='+document.frmListProduct.fromdate.value)" >
					<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=reset')">
               </td>
              </tr>
			</table>
	</form>
	<hr size="1" noshade>
    <form name="frmLead" id="frmLead" action="followup/followup.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            <input type="button" name="action" value="Add New Lead" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php? cust_code=true&a=add&leadId=<?php echo $leadId ?>')">
            <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){javascript:formget(this.form,'lead/processLead.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?status=true');javascript:printHeader('Lead Admin');}" >
                    <input type="hidden" name="action" value="delete">
                    
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
			</td>
            
		</tr>
	</table>

	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    	<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "customer_id" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("ID") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "customer_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Company Name") ?>
                </a>
            </td>
           
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "prod_desc" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Customer Requirement") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "customer_mobno" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Mobile No") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "customer_cperson" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Contact Person") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "lead_book_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Lead Date") ?>
                </a>
            </td>
            <td class="hr">
          	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?order=<?php echo "follow_up_date" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Follow Up Date") ?>
                </a>
            </td>
			<td class="hr">
          		<?php echo htmlspecialchars("Lead Days Over") ?>
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
		if($row["customer_name"] != ""){
		$leadid = $row["lead_id"];
		$field = "lead_id";
		//$prod_dis_amt = (float) $row["prod_price"] * ((float) $row["prod_discount"] / 100) ;
		$end = strtotime($row["lead_book_date"]);
		$start = strtotime($today);
		$days_between = abs(ceil(($end - $start) / 86400));

	?>
    
	<tr class="<?php echo $style ?>" style="cursor:pointer">
       <td align="left" width="2%">
        	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['lead_id']; ?>">
      </td>
      <td align="left" width="3%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_id"])) ?>
      </td>
	  <td align="left" width="20%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?>
      </td>
     
      <td align="left" width="30%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])) ?>
      </td>
	  <td align="left" width="10%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_mobno"])) ?>
      </td>
      <td align="left" width="15%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo htmlspecialchars(strtoupper($row["customer_cperson"])) ?>
      </td>
       <td align="left" width="10%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?>
      </td>
      <td align="left" width="15%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo date("d-m-Y",strtotime($row["follow_up_date"])) ?>
      </td>
      <td align="center" width="5%" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=view&recid=<?php echo $i ?>&page=<?php echo $page ?>')">
      		<?php echo $days_between ?>
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
		showpagenav($page, $pagecount,$pagerange,'followup/followup.php'); 
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
  global $today;
  global $cust_code;
  global $page;
 // if($leadId==0){
  	//$leadId = $row["cat_code"];
  //}
 
 /* if($row["cust_code"]==0 || $row["cust_code"]==""){
  	$ccode = $row["cust_code"];
  }*/
	$res = sql_select();
  	$count = sql_getrecordcount();
  $remark = $row["remark"];
  if($cust_code=="" or $cust_code=="0"){
  	$ccode = $row["cust_code"];
  }else{
  	$ccode = $cust_code;
  }
 // echo "Customer Code :".$ccode."<br>";
  $customerList = buildLeadCustomerOptions($ccode);
  if($ccode != 'new'){
  	$cust_qry = "select * from tbl_customer where customer_id='$ccode'";
	$cust_res = mysqli_query($conn,$cust_qry);
	$cust_row = mysqli_fetch_assoc($cust_res);
	
  }else{
  	$cust_row = array(
   					"customer_address" => "",
					"customer_city" => "",
					"customer_country" => "",
					"customer_cperson" => "",
  					"customer_mobno" => "",
					"customer_email" => "",
					"customer_web" => ""
					
  					);
 }
 //echo "Edit Mode :".$iseditmode;
  //$categoryList = buildCategoryOptions($leadId);
  //echo "Mode :".$iseditmode." CCode :".$ccode.'<br>';
?>
 
	<table  border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Company Name")."&nbsp;" ?>
            </td>
            
			<td align="left">
            	<?php 
					if($iseditmode==1){ ?>
						<input type="text" name="cust_name" id="cust_name" size="50" maxlength="50" value="<?php echo $cust_row['customer_name'] ?>">
                <?php }else{
				?>
                		<input type="hidden" name="recstatus" id="recstatus" value="<?php echo $ccode; ?>">
                <?php
						if($ccode=='new'){
				?>
                			<input type="text" name="cust_name" id="cust_name" size="50" maxlength="50">
                	<?php }else{ ?>	
                            <select name="cust_name" id="cust_name" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=add&cust_code='+this.value)">
                            	<option value="" selected>Choose One</option>
                                <option value="new">New Customer</option>
                                <?php echo $customerList; ?>
                                
                            </select>
                
           		 	<?php }
						
					} 
				echo htmlspecialchars("*")."&nbsp;"; 
			?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Address")."&nbsp;" ?>
            </td>
			<td align="left">
            	<?php if($iseditmode == 1) { ?>
                <textarea style="width:350px; height:80px; text-align:left; vertical-align:top;" cols="42" rows="5" name="cust_add"><?php echo $cust_row["customer_address"]?></textarea>
                <?php }else{ ?>
                <textarea name="cust_add" cols="80" rows="5"><?php echo $cust_row["customer_address"]?></textarea>
                <?php } ?>
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("City")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_city" size="50" maxlength="50" value="<?php echo $cust_row['customer_city'] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Country")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_country" size="50" maxlength="50" value="<?php echo $cust_row['customer_country'] ?>">
            
            </td>
		</tr>    	
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_cperson" size="50" maxlength="50" value="<?php echo $cust_row['customer_cperson'] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Contact Number")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_mobno" size="30" maxlength="30" value="<?php echo $cust_row['customer_mobno'] ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>    	        
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Email Address")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_email" size="50" maxlength="50" value="<?php echo $cust_row['customer_email'] ?>">
            
            </td>
		</tr>    	
         <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Website")."&nbsp;" ?>
            </td>
			<td align="left">
            	<input type="text" name="cust_web" size="50" maxlength="50" value="<?php echo $cust_row['customer_web'] ?>">
           
            </td>
		</tr>    	        
        <tr>
			<td align="left"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?>
            </td>
			<td align="left">
            <?php 
				if($iseditmode != 1){ 
            		$tdate = date("d-m-Y",strtotime($today));
                }else{ 
			 		$tdate = date("d-m-Y",strtotime($row['lead_book_date']));
			 	} 
			?>
             	<input type="text" name="lead_date" size="12" maxlength="12"  value="<?php echo $tdate ?>" readonly>
                <!--<a href="javascript:show_calendar('document.frmLead.lead_date', document.frmLead.lead_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             	</a>-->
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" valign="top"><?php echo htmlspecialchars("Customer Requirement")."&nbsp;" ?>
            </td>
			<td align="left">
            	<?php 
					if($iseditmode != 1){ ?>
                      	<textarea name="prod_desc" id="prod_desc" cols="80" rows="5"></textarea>
                <?php 
					}else{
				?>
						<textarea style="width:350px; height:80px;" cols="42" rows="5" name="prod_desc" id="prod_desc"><?php echo $row['prod_desc']?></textarea>
                 <?php } ?>
	            <?php echo htmlspecialchars("*")."&nbsp;";  ?>
            </td>
		</tr>
        <tr>
			<td align="left"><?php echo htmlspecialchars("Reference By")."&nbsp;" ?>
            </td>
			<td align="left">
            	<?php
					//echo "Edit Mode :".$iseditmode."<br>";
					if($iseditmode == 1){
						//echo "Ref By :".$row["ref_by"]."<br>";
				?>
                	 	<select id="ref_by" name="ref_by">
                        <?php if ($row["ref_by"] == "" or $row["ref_by"]=="0"){ ?>
                        	<option value="0" selected>Choose One</option>
                            <option value="Email">Email</option>
                            <option value="Phone">Phone</option>
                            <option value="Others">Others</option>
                        <?php }elseif($row["ref_by"] == "Email"){ ?>
                        	<option value="Email" selected>Email</option>
                            <option value="Phone">Phone</option>
                            <option value="Others">Others</option>
                        <?php }elseif($row["ref_by"] == "Phone"){ ?>
                        	<option value="Phone" selected>Phone</option>
                            <option value="Email">Email</option>
                            <option value="Others">Others</option>
                        <?php }else{ ?>
                        	<option value="Others" selected>Others</option>
                            <option value="Email">Email</option>
                            <option value="Phone">Phone</option>
               			<?php
			   			}
						?>
                        </select>
                <?php
					}else{
				?>	
            			
                        <select id="ref_by" name="ref_by">
                            <option value="0">Choose One</option>
                            <option value="Email">Email</option>
                            <option value="Phone">Phone</option>
                            <option value="Others">Others</option>
                		</select>
                <?php
					} 
					if($ccode == "3"){
				?>
                <input type="text" class="uppercase" name="ref_other_by" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["ref_other_by"])) ?>">
            	<?php 
						
				 	} 
					echo htmlspecialchars("*")."&nbsp;"; 
				?>
            </td>
		</tr>
        
        <tr>
			<td align="left"><?php echo htmlspecialchars("Followup Date")."&nbsp;" ?>
            </td>
			<td align="left">
            <?php 
				if($iseditmode != 1){ 
            		$fupdate = date("d-m-Y",strtotime($today));
                }else{ 
			 		$fupdate = date("d-m-Y",strtotime($row['follow_up_date']));
			 	} 
			?>
             <input type="text" name="follow_up_date" size="12" maxlength="12"  value="<?php echo $fupdate ?>">
             
             <a href="javascript:show_calendar('document.frmLead.follow_up_date', document.frmLead.follow_up_date.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
             </a>
                 
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td align="left" valign="top"><?php echo htmlspecialchars("Remark")."&nbsp;" ?>
            </td>
			
            <td align="left">
            	<input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $row['remark']; ?>">
				<?php 
					if($remark != ""){
                    	getRemarkDetails($remark);
                    } 
				?>
                <br>
                <textarea name="remark" id="remark" style="width:350px; height:80px; text-align:left; vertical-align:top;" cols="42" rows="5"></textarea>
            </td>
        </tr>
      </table>
   
<?php 
} 
/****************** End of the EDIT  Function ***********************************/

/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid,$rstatus)
{
  $remark = $row["remark"];
?>
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        	<td colspan="2" class="hrhighlightorange" align="left"><b>Customer Info</b></td>
        </tr>
    	<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Company Name")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["customer_name"])) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Address")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["customer_address"])) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("City")."&nbsp;" ?></td>
			<td align="left"><?php echo htmlspecialchars(strtoupper($row["customer_city"])) ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Lead Date")."&nbsp;" ?></td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["lead_book_date"])) ?></td>
		</tr>
       
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Customer Requirement")."&nbsp;" ?></td>
			<td align="left"><?php echo str_replace('<br/>', '\n', nl2br($row["prod_desc"])); ?></td>
		</tr>
		<tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Contact Mobile No")."&nbsp;" ?></td>
			<td align="left"><?php echo strtoupper($row["customer_mobno"]) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Email")."&nbsp;" ?></td>
			<td align="left"><?php echo strtoupper($row["customer_email"]) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Contact Person")."&nbsp;" ?></td>
			<td align="left"><?php echo strtoupper($row["customer_cperson"]) ?></td>
		</tr>
        <tr>
			<td align="left" width="20%"><?php echo htmlspecialchars("Follow Up Date")."&nbsp;" ?></td>
			<td align="left"><?php echo date("d-m-Y",strtotime($row["follow_up_date"])) ?></td>
		</tr>
       
       
        <?php
			if($rstatus=='reject'){
				
			
		?>
        		<tr>
        		<td align="left" width="20%">Reason</td>
            	<td align="left">
                	<input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
                	<textarea name="remark" id="remark" cols="45" rows="5"></textarea>
                </td>
                </tr>
        <?php
			}else{
		?>
        		<tr>
        			<td align="left" width="20%">Remarks</td>
            		<td align="left"><input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
					<?php 
                        if($remark != ""){
                           getRemarkDetails($remark);
                        } ?>
            		</td>
        		</tr>
       <?php } ?>
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/


function rejectrow($row, $recid){
$remark = $row["remark"];
?>
	<table border="0" cellspacing="1" cellpadding="5" width="100%" class="hrhighlightblue">
    	<tr>
        		<td align="left" width="20%">Reason</td>
            	<td align="left">
                	<input type='hidden' name='remarkdata' id='remarkdata' value="<?php echo $remark; ?>">
                	<textarea name="remark" id="remark" cols="45" rows="5"></textarea>
                </td>
        </tr>
        
     </table>
<?php
}

/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
	global $pageId;
	global $page;
	//echo "Page Id:".$pageId."<br>";
	//echo "Page111 :".$page."<br>";
 //echo "Record Id :".$recid. " Count=".$count."<br>";
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?status=true&page=<?php echo $page ?>')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
			<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td>
                <input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?status=true')"></td>
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
	global $page;
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
	$prodcode = $row["lead_id"];
	$field="lead_id";
	//echo "Product Code :".$prodcode."<br>";
	//echo "ViewRec : Rec ID :".$recid." Count=".$count."<br>";
  	showrecnav("view", $recid, $count);
//	recid=<?php echo $i 
?>
	<br>
    <form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?action=update" method="post">
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
           			<input type="button" name="btnEdit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=edit&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>&page=<?php echo $page ?>')" value="Edit Record">
                
            	</td>
				<td>
            		<input type="button" name="btnReject" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?a=reject&filter=<?php echo $prodcode ?>&filter_field=<?php echo $field ?>&page=<?php echo $page ?>')" value="Reject Record">
                    
                
            	</td>
            	<td>
            		<input type="button" name="btnAccept" onClick="javascript:formget(this.form,'lead/processLead.php?action1=accept');javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?status=true');javascript:printHeader('Lead Admin');" value="Accept Record">
                
            	</td>
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
	//echo "CAll Reject REc Functin :"."<br>";
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
  	//showrecnav("view", $recid, $count);
	$remark = $row["remark"];
?>
	<br>
	<form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?action=reject" method="post">
		<input type="hidden" name="action" value="reject">
		<input type="hidden" name="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="cust_id" value="<?php echo $row["cust_code"] ?>">
        
        
		<?php 
			
			showrow($row,$recid,"reject");
			// rejectrow($row,$recid); 
		?>
        
		<p>
        <input type="button" id="btnedit" name="btnedit" value="Update" onClick="if(reject_validate()==true){javascript: formget(this.form,'lead/processLead.php');}">
        </p>
        <div id="output" style="color: blue;"></div>
        
	</form>
<?php
	mysqli_free_result($res);
} 
/****************** End of the REJECT RECORD Function ***********************************/

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
	<form name="frmLead" enctype="multipart/form-data" action="lead/processLead.php?action=update" method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="lead_id" id="lead_id" value="<?php echo $row["lead_id"] ?>">
        <input type="hidden" name="cust_id" id="cust_id" value="<?php echo $row["cust_code"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        <input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(lead_validate()==true){javascript: formget(this.form, 'lead/processLead.php');}">
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
	global $fromdate;
	
	$uid=$_SESSION['User_ID'];
	if(isset($fromdate)){
		$fromdate = date("d-m-Y",strtotime($fromdate));
		
	}
	//echo "Filter :".$filter." FilterField :".$filterfield."<br>";
  	$filterstr = sqlstr($filter);
	if($filterfield != 'lead_id') {
  		if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	}
	
	if($_SESSION['User_Design']=='ADMIN') {
  	$sql = "select * from (select c.prod_desc, b.customer_id, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
			b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.ref_by, c.follow_up_date,c.remark,c.lead_id,c.cust_code,c.cust_require 
			from tbl_customer b,tbl_lead_master c 
			where c.cust_code=b.customer_id and c.lead_book_status='0' and c.reject_status='0' and date_format( c.follow_up_date, '%d-%m-%Y' ) = '$fromdate') subq where 1";
	}else{
	$sql = "select * from (select c.prod_desc, b.customer_id, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
			b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.ref_by, c.follow_up_date,c.remark,c.lead_id,c.cust_code,c.cust_require 
			from tbl_customer b,tbl_lead_master c 
			where c.cust_code=b.customer_id and c.lead_book_status='0' and c.reject_status='0' and c.user_id='$uid' 
			and date_format( c.follow_up_date, '%d-%m-%Y' ) = '$fromdate') subq where 1";
	}
	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
		if($filterfield != 'lead_id') {
    		$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
		}else{
			$sql .= " and ".sqlstr($filterfield) ." = '".$filterstr."'";
		}
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`prod_desc` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') or (`customer_mobno` like '" .$filterstr ."') or (`customer_cperson` like '" .$filterstr ."')"; 
	
  	}
  	
  	if (isset($order) && $order!=''){
		 $sql .= " order by `" .sqlstr($order) ."`";
    }else{
		$sql .= " order by lead_book_date desc";
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
	global $fromdate;
	
  	$filterstr = sqlstr($filter);
	if($filterfield != 'lead_id') {
  		if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	}
  	
	if(isset($fromdate)){
		$fromdate = date("d-m-Y",strtotime($fromdate));
		
	}
	
	$uid=$_SESSION['User_ID'];
	
	if($_SESSION['User_Design']=='ADMIN') {
  		$sql = "select count(*) from (select c.prod_desc, b.customer_id, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.ref_by, c.follow_up_date,c.remark,c.lead_id,c.cust_code,c.cust_require 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.lead_book_status='0' and c.reject_status='0' and date_format( c.follow_up_date, '%d-%m-%Y' ) = '$fromdate') subq where 1";
	}else{
		$sql = "select count(*) from (select c.prod_desc,b.customer_id, b.customer_name, b.customer_address,b.customer_city,b.customer_country, b.customer_mobno,
				b.customer_cperson,b.customer_email,b.customer_web,c.lead_book_date, c.ref_by, c.follow_up_date,c.remark,c.lead_id,c.cust_code,c.cust_require 
				from tbl_customer b,tbl_lead_master c 
				where c.cust_code=b.customer_id and c.lead_book_status='0' and c.reject_status='0' and c.user_id='$uid' and date_format( c.follow_up_date, '%d-%m-%Y' ) = '$fromdate') subq where 1";
	}
	
	if(isset($queryString) && $queryString !=''){
		$sql .= " $queryString";
	}
	
	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	if($filterfield != 'lead_id') {
    		$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
		}else{
			$sql .= " and ".sqlstr($filterfield) ." = '".$filterstr."'";
		}
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`customer_name` like '" .$filterstr ."') or (`lead_book_date` like '" .$filterstr ."') or (`prod_desc` like '" .$filterstr ."') 
					or (`follow_up_date` like '" .$filterstr ."') or (`customer_mobno` like '" .$filterstr ."') or (`customer_cperson` like '" .$filterstr ."')"; 
	
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
