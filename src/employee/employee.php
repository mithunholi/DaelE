<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
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
	if(isset($_GET["designId"])) $designId = $_GET["designId"];
	
  	if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  	if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  	if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  	if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
	if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
	if (!isset($designId) && isset($_SESSION["designId"])) $designId = $_SESSION["designId"];

	if (isset($_GET['catId']) && (int)$_GET['catId'] >= 0) {
		$catId = (int)$_GET['catId'];
		$queryString = "&catId=$catId";
	} else {
		$catId = 0;
		$queryString = '';
	}
?>
	<html>
		<head>
			<title>mCRM -- Category Screen</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
		</head>
		<body>
		<?php
  			require_once("../config.php");
			require_once("../library/functions.php");
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
				if (isset($designId)) $_SESSION["designId"] = $designId;
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
	global $pagerange;
	global $designId;
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		
  	}

  	$checkstr = "";
  	if ($wholeonly) $checkstr = " checked";
  	if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	if ($count % $showrecs != 0) {
    	$pagecount = intval($count / $showrecs) + 1;
  	}
  	else {
    	$pagecount = intval($count / $showrecs);
  	}
  	$startrec = $showrecs * ($page - 1);
  	if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
  	$reccount = min($showrecs * $page, $count);
	
	
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" class="uppercase" value="<?php echo $filter ?>">
				
                  <select name="filter_field">
					<option value="">All Fields</option>
                    <option value="<?php echo "emp_code" ?>"<?php if ($filterfield == "emp_code") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Employee Code")?>
                    </option>
					<option value="<?php echo "emp_fname" ?>"<?php if ($filterfield == "emp_fname") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Employee Name")?>
                    </option>
                    <option value="<?php echo "emp_dname" ?>"<?php if ($filterfield == "emp_dname") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Employee Short Name")?>
                    </option>
                    <option value="<?php echo "emp_gender" ?>"<?php if ($filterfield == "emp_gender") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Gender")?>
                    </option>
					<option value="<?php echo "design_name" ?>"<?php if ($filterfield == "design_name") { echo "selected"; } ?>>
						<?php echo htmlspecialchars("Designation") ?>
                    </option>
                    
				 </select>
                
				<input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'employee/employee.php?status=true&a=filter')" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=reset')">
                </td>
		     </tr>
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmCategory" id="frmCategory" action="employee/employee.php?a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
			<td align="left">
            	<input type="button" name="action" value="Add New Employee" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=add')">
            	<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'employee/processEmployee.php?'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true');javascript:printHeader('Employee Info');}" >
                    <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&pageId='+this.value)">
				<option value="<?php echo $showrecs; ?>"><?php echo $showrecs; ?></option>
				<option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
             </select>
			</td>
		</tr>
	</table>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_code" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Code") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_fname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_dname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Short Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_gender" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Gender") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_doj" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Date of Join") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_dest" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Designation") ?>
                </a>
            </td>
             <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_email" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Email") ?>
                </a>
            </td>
             <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&order=<?php echo "emp_mobno" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Mobile No") ?>
                </a>
            </td>
        
		</tr>
	<?php
  	for ($i = $startrec; $i < $reccount; $i++)

  	{
    	$row = mysqli_fetch_assoc($res);
    	$style = "dr";
		
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
		$empID = $row["emp_id"];
		$empField = "emp_id";
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer">
    	<?php 
			if($row["design_name"] == "ADMIN" or $row["design_name"] == "WEBUSER"){
		?>
        		<td>&nbsp;
        			
        		</td>
        <?php
			}else{
		?>		
    			<td>
        			<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['emp_id']; ?>">
        		</td>
        <?php
			}
		?>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_code"]) ?>
        </td>
		<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_fname"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_dname"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_gender"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars(date("d-m-Y",strtotime($row["emp_doj"]))) ?>
        </td>		
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["design_name"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_email"]) ?>
        </td>
        <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=view&recid=<?php echo $i ?>')">
			<?php echo htmlspecialchars($row["emp_mobno"]) ?>
        </td>
       
	</tr>
	<?php
  	}//for loop
  	mysqli_free_result($res);
	?>
     <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
	<br><center>
	<?php 
		//showpagenav($page, $pagecount); 
		showpagenav($page, $pagecount,$pagerange,'employee/employee.php?status=true'); 
} 
 ?></center>
 <?php
/****************** Start SHOW PAGE NAVIGATION Function ***********************************/
/****************** End of the SHOW PAGE NAVIGATION Function ***********************************/


/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $designId;
echo "testing ===".$row["emp_id"];

  //$res = sql_select();
  	//while ($row = mysqli_fetch_assoc($res)) {
	//echo $row['levels'];
	//}
  
  ///echo "Dest ID:".$designId." == ".$row["emp_dest"]."<br>";
  //if($designId == ""){
  
  	$designId = $row["emp_dest"];
	$lname = $row["levels"];

  //}
  
  $designList = buildDesignOptions($designId,$lname);
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="75%">
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Code")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" name="emp_code" class="uppercase" size="10" maxlength="10" value="<?php echo str_replace('"', '&quot;', trim($row["emp_code"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" name="emp_fname" class="uppercase" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["emp_fname"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Short Name")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" name="emp_dname" class="uppercase" size="50" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["emp_dname"])) ?>">
               <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Gender")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<select name="emp_gender">
                	<?php if ($row["emp_gender"] == "M"){
					?>
                     <option value="M" selected>Male</option>
                     <option value="F">Female</option>
                     <?php } elseif($row["emp_gender"] == "F"){
					 ?>
                      <option value="F" selected>Female</option>
                      <option value="M">Male</option>
                      <?php }else{ ?>
                      <option value="M">Male</option>
                      <option value="F">Female</option>
                      <?php } ?>
                </select>	
            	<?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Date of Birth:")."&nbsp;"  ?></td>
			<td class="dr" align="left">
            <input type="text" name="emp_dob" id="emp_dob" class="uppercase" size="11" maxlength="11" value="<?php echo date("d-m-Y",strtotime($row["emp_dob"])) ?>">
                 <a href="javascript:show_calendar('document.employee.emp_dob', document.employee.emp_dob.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="dd-mm-yyyyy">
             	 </a>
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Address:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <?php if($row["emp_address"] != ''){ 
					$empaddress = nl2br($row["emp_address"]);
					?>
               	<textarea class="uppercase" name="emp_address" rows="3">
            		<?php echo $empaddress; ?>
                </textarea>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            <?php }else{ ?>
					<textarea class="uppercase" name="emp_address" rows="3"></textarea>	
			<?php }
			?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Date of Joining:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="text" class="uppercase" name="emp_doj" size="11" maxlength="11" value="<?php echo date("d-m-Y",strtotime($row["emp_doj"])) ?>">
                <a href="javascript:show_calendar('document.employee.emp_doj', document.employee.emp_doj.value);">
                	<img src="images/cal.gif" width="16" height="16" border="0" alt="dd-mm-yyyy">
                  
             	 </a>
                 <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Designation:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<select name="emp_dest" id="emp_dest" class="box">
                	<option selected>-- Choose Designation -- </option>
                    <?php echo $designList; ?>
                </select>
            	
              <?php echo htmlspecialchars("*")."&nbsp;" ?> 
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("E-mail:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" class="lowercase" name="emp_email" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["emp_email"])) ?>">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" name="emp_mobno" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["emp_mobno"])) ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Tel. No:")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="text" name="emp_telno" size="30" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["emp_telno"])) ?>" onKeyUp="this.value=this.value.replace(/\D/,'')">
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
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Code:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["emp_code"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Name:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["emp_fname"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Employee Short Name:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["emp_dname"])) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Gender:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper($row["emp_gender"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Date of Birth:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo date("d-m-Y",strtotime($row["emp_dob"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Address:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(trim($row["emp_address"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Date of Joining:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo date("d-m-Y",strtotime($row["emp_doj"])) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Designation:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars(strtoupper(getDesignationName($row["emp_dest"]))) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Email:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["emp_email"]) ?></td>
		</tr>
         <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile No:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["emp_mobno"]) ?></td>
		</tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Tel. No:")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["emp_telno"]) ?></td>
		</tr>
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/




/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
 global $today;
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="employee" enctype="multipart/form-data" action="employee/processEmployee.php?status=true&action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <input type="hidden" name="action" value="add">
		<?php
			$row = array(
   					"emp_code" => "",
  					"emp_fname" => "",
					"emp_dname" => "",
					"emp_gender" => "",
					"emp_dob" => date("d-m-Y",strtotime($today)),
					"emp_address" => "",
					"emp_doj" => date("d-m-Y",strtotime($today)),
					"emp_dest" => "",
					"emp_email" => "",
					"emp_mobno" => "",
					"emp_telno" => ""
  					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(employee_validate()==true){javascript: formget(this.form, 'employee/processEmployee.php?status=true');}"></p>
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
  	showrecnav("view", $recid, $count);
?>
	<br>
<?php
	showrow($row, $recid); 
$emp_test = $row["emp_id"];
?>
	<br>

	<hr size="1" noshade>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td>
            <input type="button" name="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true&a=edit&recid=<?php echo $recid ?>')">
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
	echo "datttta====".$row["emp_id"];
  	showrecnav("edit", $recid, $count);
?>
	<br>
	<form name="employee" enctype="multipart/form-data" action="employee/processEmployee.php?status=true&action=update" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
		<input type="hidden" name="emp_id" value="<?php echo $row["emp_id"] ?>">
		<?php showroweditor($row, true); ?>
		<p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(employee_validate()==true){javascript: formget(this.form, 'employee/processEmployee.php');}">
        </p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php
	mysqli_free_result($res);
} 
/****************** End of the EDIT RECORD Function ***********************************/

/****************** ***START DELETE RECORD Function ***********************************/
function deleterec($recid)
{
	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysqli_data_seek($res, $recid);
  	$row = mysqli_fetch_assoc($res);
  	showrecnav("del", $recid, $count);
?>
	<br>
	<form action="employee/processEmployee.php?action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
		<input type="hidden" name="cat_id" value="<?php echo $row["emp_id"] ?>">
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

  	$filterstr = sqlstr($filter);
  	if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "select * from (SELECT a.emp_id, a.emp_code, a.emp_fname,a.emp_dname, a.emp_gender, a.emp_dob,
			a.emp_address,a.emp_doj, a.emp_dest, a.emp_email,a.emp_telno, a.emp_mobno,b.design_name,b.levels
			FROM `tbl_employee` a,tbl_design b where a.emp_dest=b.id and a.status='0') subq";
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`emp_code` like '" .$filterstr ."') or (`emp_fname` like '" .$filterstr ."') or (`emp_dname` like '" .$filterstr ."') 
					or (`design_name` like '" .$filterstr ."')
					or (`emp_gender` like '" .$filterstr ."') or (`emp_dest` like '" .$filterstr ."') or (`emp_telno` like '" .$filterstr ."') 
					or (`emp_email` like '" .$filterstr ."') or (`emp_mobno` like '" .$filterstr ."')";
  	}
  
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
    //echo "SQL :".$sql."<br>";
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

  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "SELECT COUNT(*) FROM (SELECT a.emp_id, a.emp_code, emp_fname, a.emp_dname, a.emp_gender, a.emp_dob,
			a.emp_address,a.emp_doj, a.emp_dest, a.emp_email,a.emp_telno, a.emp_mobno,b.design_name,b.levels
			FROM `tbl_employee` a,tbl_design b where a.emp_dest=b.id and a.status='0') subq";
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " where (`emp_code` like '" .$filterstr ."') or (`emp_fname` like '" .$filterstr ."') or (`emp_dname` like '" .$filterstr ."') 
					or (`design_name` like '" .$filterstr ."')
					or (`emp_gender` like '" .$filterstr ."') or (`emp_dest` like '" .$filterstr ."') or (`emp_telno` like '" .$filterstr ."') 
					or (`emp_email` like '" .$filterstr ."') or (`emp_mobno` like '" .$filterstr ."')";
  	}
    //echo "SQL1 :".$sql."<br>";
  	$res = mysqli_query($conn, $sql) or die(mysqli_error());
  	$row = mysqli_fetch_assoc($res);
  	reset($row);

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
