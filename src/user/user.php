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
	$_SESSION["repstatus"]="";
	$_SESSION["designId"]="";
	$_SESSION["empId"]="";
	$_SESSION["user_name"]="";
	$_SESSION["pass_word"]="";
	$_SESSION["imei_no"]="";
	$status = false;
  }
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];
  if (isset($_GET["repstatus"])) $repstatus = @$_GET["repstatus"];
  if (isset($_GET["designId"])) $designId = @$_GET["designId"];
  if (isset($_GET["empId"])) $empId = @$_GET["empId"];
  if (isset($_GET["user_name"])) $user_name = @$_GET["user_name"];
  if (isset($_GET["pass_word"])) $pass_word = @$_GET["pass_word"];
  if (isset($_GET["imei_no"])) $imei_no = @$_GET["imei_no"];  

  	
  if (isset($_GET["filter"])) $filter = stripslashes(@$_GET["filter"]);
  if (isset($_GET["filter_field"])) $filterfield = stripslashes(@$_GET["filter_field"]);
  if(isset($_GET["pageId"])) $pageId = $_GET["pageId"];
  if(isset($_GET["record_count"])){
  	 $record_count = @$_GET["record_count"];
  } else{
  	 $record_count=0;
  }
  

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
  if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
  if (!isset($repstatus) && isset($_SESSION["repstatus"])) $repstatus = $_SESSION["repstatus"];
  if (!isset($designId) && isset($_SESSION["designId"])) $designId = $_SESSION["designId"];
  if (!isset($empId) && isset($_SESSION["empId"])) $empId = $_SESSION["empId"];
  if (!isset($user_name) && isset($_SESSION["user_name"])) $user_name = $_SESSION["user_name"];
  if (!isset($pass_word) && isset($_SESSION["pass_word"])) $pass_word = $_SESSION["pass_word"];
  if (!isset($imei_no) && isset($_SESSION["imei_no"])) $imei_no = $_SESSION["imei_no"];

  
  
 // echo "Design ID :".$designId."<br>";
?>

<html>
<head>
<title>mCRM -- User Screen</title>
<meta name="generator" http-equiv="content-type" content="text/html">
<link type="text/css" rel="stylesheet" href="main.css">
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
  
  //echo "AAAAAAAA =".$a."<br>";
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
      deleterec($recid);
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
  if (isset($repstatus)) $_SESSION["repstatus"] = $repstatus;
  
   if (!isset($designId) && isset($_SESSION["designId"])) $designId = $_SESSION["designId"];
  if (!isset($empId) && isset($_SESSION["empId"])) $empId = $_SESSION["empId"];
  if (!isset($user_name) && isset($_SESSION["user_name"])) $user_name = $_SESSION["user_name"];
  if (!isset($pass_word) && isset($_SESSION["pass_word"])) $pass_word = $_SESSION["pass_word"];
  if (!isset($imei_no) && isset($_SESSION["imei_no"])) $imei_no = $_SESSION["imei_no"];
  
  if (isset($designId)) $_SESSION["designId"] = $designId;
  if (isset($empId)) $_SESSION["empId"] = $empId;
  if (isset($user_name)) $_SESSION["user_name"] = $user_name;
  if (isset($pass_word)) $_SESSION["pass_word"] = $pass_word;
  if (isset($imei_no)) $_SESSION["imei_no"] = $imei_no;
  
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
	global $reccount;
	global $pagerange;
	global $repstatus;
	global $designId;
	
	
  	if ($a == "reset") {
    	$filter = "";
    	$filterfield = "";
    	$wholeonly = "";
    	$order = "";
    	$ordtype = "";
		$designId="";
		//$repstatus ="";
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
<?php 
	if(!($repstatus=="LEVEL5" or $repstatus=="LEVEL2")){
?>
	<form name="frmFilter" action="" method="post">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
			<tr>
				<td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
				<input type="text" name="filter" value="<?php echo $filter ?>">
				<select name="filter_field">
				<option value="">All Fields</option>
				<option value="<?php echo "user_name" ?>"<?php if ($filterfield == "user_name") { echo "selected"; } ?>>
				<?php 
					if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
						echo "User ID";
					}else{
						echo "Mobile User ID";
					} 
				?>
                </option>
				<option value="<?php echo "pass_word" ?>"<?php if ($filterfield == "pass_word") { echo "selected"; } ?>>
				<?php 
					if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
						echo "Password";
					}else{
						echo "Mobile Password";
					} 
				
				 ?>
                </option>
                <?php if(!($repstatus=="LEVEL5" or $repstatus=="LEVEL2")){ ?>
					<option value="<?php echo "imei_no" ?>"<?php if ($filterfield == "imei_no") { echo "selected"; } ?>><?php echo htmlspecialchars("Mobile IMEI No") ?>
                    </option>
                <?php } ?>
                <option value="<?php echo "emp_code" ?>"<?php if ($filterfield == "emp_code") { echo "selected"; } ?>><?php echo htmlspecialchars("Employee Code") ?>
                </option>
				<option value="<?php echo "emp_fname" ?>"<?php if ($filterfield == "emp_fname") { echo "selected"; } ?>><?php echo htmlspecialchars("Employee Name") ?>
                </option>
                <option value="<?php echo "emp_dname" ?>"<?php if ($filterfield == "emp_dname") { echo "selected"; } ?>><?php echo htmlspecialchars("Employee Short Name") ?>
                </option>
                <option value="<?php echo "status" ?>"<?php if ($filterfield == "status") { echo "selected"; } ?>><?php echo htmlspecialchars("Status") ?>
                </option>
				</select>
                
                <input type="button" name="action" value="Apply" onClick="javascript:SearchData(this.form,'user/user.php?status=true&a=filter')">
                <input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=reset')"></td>
				
            </tr>
			
		</table>
	</form>
	<hr size="1" noshade>
    <form name="frmUser" id="frmUser" action="user/user.php?status=true&a=del" method="post">
	<table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
		       	<td align="left">
            		<input type="button" name="btnAdd" value="Add New User" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=add&repstatus=<?php echo $repstatus; ?>')">
            		<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'user/processUser.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=LEVEL1');javascript:printHeader('Sales Force Admin');}" >
                    <input type="hidden" name="action" value="delete">
            	</td>
            
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&pageId='+this.value)">
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
   <?php
	}
	?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
		<tr>
			<td class="hr"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);"></td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "user_name" ?>&type=<?php echo $ordtypestr ?>')">
                	
					<?php 
						if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
							echo "User ID";
						}else{
							echo htmlspecialchars("Mobile User ID");
						} 
						?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "pass_word" ?>&type=<?php echo $ordtypestr ?>')">
					<?php 
						if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
							echo "Password";
						}else{
							echo htmlspecialchars("Mobile Password");
						} 
					?>
                </a>
            </td>
             <?php if(!($repstatus=="LEVEL5" or $repstatus=="LEVEL2")){ ?>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "imei_no" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Mobile IMEI No") ?>
                </a>
            </td>
            <?php } ?>
             <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "emp_code" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Code") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "emp_fname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Name") ?>
                </a>
            </td>
             <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "emp_dname" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Employee Short Name") ?>
                </a>
            </td>
			<td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "design_name" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Designation Name") ?>
                </a>
            </td>
            <td class="hr">
            	<a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&order=<?php echo "status" ?>&type=<?php echo $ordtypestr ?>')">
					<?php echo htmlspecialchars("Status") ?>
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
		
	?>
            <tr class="<?php echo $style ?>" style="cursor:pointer" align="left">
                <td>
                    <input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['user_id']; ?>">
                    
                </td>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["user_name"]) ?><br>
                </td>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["pass_word"]) ?>
                </td>
                 <?php if(!($repstatus=="LEVEL5" or $repstatus=="LEVEL2")){ ?>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["imei_no"]) ?>
                </td>
                <?php } ?>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["emp_code"]) ?>
                </td>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["emp_fname"]) ?>
                </td>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["emp_dname"]) ?>
                </td>
                <td onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">
                    <?php echo htmlspecialchars($row["design_name"]) ?>
                </td>
               
                    <?php 
                        if ($row["status"]==0){
					?>
                    	 <td class="srhighlight1" onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">Enabled</td>
                    <?php
                        }else{
					?>
                         <td class="srhighlight3" onClick="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=view&recid=<?php echo $i ?>')">Disabled</td>
                    <?php   
					   }
                     ?>
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
		showpagenav($page, $pagecount,$pagerange,'user/user.php'); 
} 
/****************** End of the SELECT Function ***********************************/
?>
</center>

<?php
/********************* START SHOW ROW RECORDS ***********************************/
function showrow($row, $recid)
{
	global $repstatus;
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
		<tr>
			<td class="hr" align="left">
				<?php
					if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
						echo "User ID";
					}else{
						echo htmlspecialchars("Mobile User ID");
					}  
				?>
            </td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["user_name"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left">
				<?php  
					
					if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){
						echo "Password";
					}else{
						echo htmlspecialchars("Mobile Password");
					}  
				?>
			</td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["pass_word"]) ?></td>
		</tr>
        <?php if(!($repstatus=="LEVEL5" or $repstatus=="LEVEL2")){ ?>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Mobile IMEI Number")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["imei_no"]) ?></td>
		</tr>
       <?php } ?>
        <tr>
        	<td class="hr"><?php echo htmlspecialchars("Employee Name")."&nbsp;" ?></td>
            <td class="dr" align="left">
            	<?php echo $row["emp_code"].'-'.$row["emp_fname"]; ?>
            </td>
        </tr>
        <tr>
        	<td class="hr"><?php echo htmlspecialchars("Employee Short Name")."&nbsp;" ?></td>
            <td class="dr" align="left">
            	<?php echo $row["emp_dname"]; ?>
            </td>
        </tr>
        <tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Designation")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo htmlspecialchars($row["design_name"]) ?></td>
		</tr>
		<tr>
			<td class="hr" align="left"><?php echo htmlspecialchars("Status")."&nbsp;" ?></td>
			<td class="dr" align="left">
				<?php 
					if($row["status"] == "0"){ 
						echo "Enabled";
					}else{
						echo "Disabled";
					}
				 ?>
             </td>
		</tr>
       
	</table>
<?php 
} 
/****************** End of the SHOW ROW Function ***********************************/

/****************** Start EDIT ROW Function ***********************************/
function showroweditor($row, $iseditmode)
{
  global $conn;
  global $repstatus;
  global $designId;
  global $_SESSION;
  global $empId;
  global $user_name;
  global $pass_word;
  global $imei_no;
  global $recid;
  //echo "Design Id :".$designId."<br>";

  if($iseditmode==false)
  {
  	$aa = "add";
	$mode = "false";
  }else{
  	$aa = "edit";
	$mode = "true";
  }
 //  echo "Edit Mode :".$designId."<br>";
$res = sql_select();
  	
  	$row = mysqli_fetch_assoc($res);
  if($user_name == "" or $user_name == "0"){
  	$user_name = $row["user_name"];
  }
  
  if($pass_word == "" or $pass_word == "0"){
  	$pass_word = $row["pass_word"];
  }
  
  if($imei_no == "" or $imei_no == "0"){
  	$imei_no = $row["imei_no"];
  }
  
  if($designId == "0" or $designId == ""){
  	$designId = $row["designid"];
  }
  $old_emp_name = $row["emp_id"];
  if($designId <> "" and $designId <> ""){
  	if($userId == "0" or $userId == ""){
		
		$userId = $row["emp_id"];
		$empId = $row["emp_id"];
	}
 }
  //echo "Emp Id :".$empId."<br>";	
  //	$levelname = getLevelName($designId); //to get Level Name
	
  	//$designList = buildUserOptions($designId,$repstatus);
	//$designList1 = buildUserOptions($userId);
	$designList = buildDesignationList($designId,$repstatus);
  	if($designId <> "0" and $designId <>""){
		$employeeList = buildEmployeeList($empId,$designId);
	}
	if($empId <> "0" and $empId <> ""){
		//$employeeList = buildEmployeeList($empId,$designId);
		$empShort_name = getEmployeeName($empId);
	}
	
?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="80%">
		<tr>
         <td class="hr"><?php echo htmlspecialchars("Mobile User ID")."&nbsp;" ?></td>	    
         <td class="dr" align="left">
		     <input type="text" name="user_name" maxlength="15" value="<?php echo $user_name ?>">
             <?php echo htmlspecialchars("*")."&nbsp;" ?>
         
         </td>
        </tr>
		<tr>
			<td class="hr"><?php echo htmlspecialchars("Mobile Password")."&nbsp;" ?></td>
			<td class="dr" align="left"><input type="text" name="pass_word" maxlength="10" value="<?php echo $pass_word; ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <?php if($repstatus=="LEVEL5" or $repstatus=="LEVEL2"){ ?>
        		<input type="hidden" name="old_imei_no" maxlength="20" value="<?php echo $imei_no; ?>">
        		<input type="hidden" name="imei_no" maxlength="20" value="<?php echo $imei_no; ?>">
                <input type="hidden" name="old_emp_name" value="<?php echo $old_emp_name; ?>">
                <input type="hidden" name="Title" maxlength="20" value="<?php echo str_replace('"', '&quot;', trim($row["emp_id"])) ?>">
         <?php }else{
         ?>
		 <tr>
			<td class="hr"><?php echo htmlspecialchars("Mobile IMEI Number")."&nbsp;" ?></td>
			<td class="dr" align="left">
            <input type="hidden" name="old_imei_no" maxlength="20" value="<?php echo $imei_no; ?>">
            <input type="text" name="imei_no" maxlength="20" value="<?php echo $imei_no; ?>">
            <?php echo htmlspecialchars("*")."&nbsp;" ?>
            </td>
		</tr>
        <?php } ?>
        <tr>
			<td class="hr"><?php echo htmlspecialchars("Employee Name")."&nbsp;" ?></td>
			<td class="dr" align="left"> 
            	<input type="hidden" name="old_design_name" value="<?php echo $row['designid']; ?>">
                <input type="hidden" name="old_emp_name" value="<?php echo $old_emp_name; ?>">
            	<select name="design_name" onChange="LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=<?php echo $aa; ?>&recid=<?php echo $recid ?>&user_name='+document.user.user_name.value+'&pass_word='+document.user.pass_word.value+'&imei_no='+document.user.imei_no.value+'&designId='+this.value)">
                	<option selected>-- Choose Any one --</option>
                    <?php echo $designList; ?>
                </select>
            	<select name="emp_name">
                	<option selected>-- Choose Any one --</option>
                    <?php echo $employeeList; ?>
                </select>
                <?php echo htmlspecialchars("*")."&nbsp;" ?>
             </td>
 		</tr>
        <?php if($empId!=''){ ?>
        <tr>
			<td class="hr"><?php echo htmlspecialchars("Employee Short Name")."&nbsp;" ?></td>
			<td class="dr" align="left"><?php echo $empShort_name; ?></td>
		</tr>
        <?php }if($row['designid'] != '1'){ ?>
        <tr>
        	<td class="hr"><?php echo htmlspecialchars("Status")."&nbsp;" ?></td>
			<td class="dr" align="left">
            	<input type="hidden" name="old_status" value="<?php echo $row['status']; ?>">
            	<select name="status">
                	<?php if($row["status"] == 0){ ?>
                    	<option value="0" selected>Enabled</option>
                        <option value="1">Disabled</option>
                    <?php }else{ ?>
						<option value="1" selected>Disabled</option>
                        <option value="0">Enabled</option>
                   <?php } ?>
            	</select>
            </td>
        </tr>
        <?php }else{
		?>
			<input type="hidden" name="old_status" value="<?php echo $row['status']; ?>">
            <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
       <?php }
		 ?>
	</table>
<?php 
} 
/****************** End of the EDIT  Function ***********************************/
 
/****************** START of the SHOW RECORD NAVIGATION Function ***********************************/
function showrecnav($a, $recid, $count)
{
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true')"></td>
			<?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
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
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true')"></td>
		</tr>
	</table>
	<hr size="1" noshade>
	<form name="user" enctype="multipart/form-data" action="user/processUser.php?status=true&action=add" method="post">
		<p><input type="hidden" name="sql" value="insert"></p>
        <input type="hidden" name="action" value="add">
		<?php
			$row = array(
   					"user_name" => "",
  					"pass_word" => "",
  					"imei_no" => "",
					"Title" => ""
					);
			showroweditor($row, false);
		?>
		<p><input type="button" name="btnAdd" id="btnAdd" value="Save" onClick="if(user_validate()==true){javascript: formget(this.form, 'user/processUser.php');}"></p>
        <div id="output" style="color: blue;"></div>
	</form>
<?php 
} 
/****************** End of the ADD RECORD Function ***********************************/


 
/****************** START of the VIEW RECORD Function ***********************************/
function viewrec($recid)
{

	//echo "STEP-0";
	$res = sql_select();
  	$count = sql_getrecordcount();
  	//mysql_data_seek($res, $recid);
  	//$row = mysql_fetch_assoc($res);
	//echo "STEP-1";
  	showrecnav("view", $recid, $count);
	//echo "STEP-2";
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
            <input type="button" name="btnEdit" id="btnEdit" value="Edit Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&a=edit&recid=<?php echo $recid ?>')">
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
	global $repstatus;
  	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
  	showrecnav("edit", $recid, $count);
	
?>
	<br>
	<form name="user" enctype="multipart/form-data" action="user/processUser.php?action=update&rstatus=<?php echo $repstatus; ?>" method="post">
		<input type="hidden" name="sql" value="update">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="rstatus" value="<?php echo $repstatus; ?>">
		<input type="hidden" name="user_id" value="<?php echo $row["user_id"] ?>">
		<?php showroweditor($row, true); ?>
        <p>
        	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="if(user_validate()==true){javascript: formget(this.form, 'user/processUser.php');}">
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
	global $repstatus;
	
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$user[$i] = $_POST["userbox"][$i];
			echo "User Rec :".$user[$i];
		}
 	}

	$res = sql_select();
  	$count = sql_getrecordcount();
  	mysql_data_seek($res, $recid);
  	$row = mysql_fetch_assoc($res);
	showrecnav("del", $recid, $count);
?>
	<br>
	<form enctype="multipart/form-data"  action="user/processUser.php?status=true&action=delete" method="post">
		<input type="hidden" name="sql" value="delete">
        <input type="hidden" name="action" value="delete">
		<input type="hidden" name="user_id" value="<?php echo $row["user_id"] ?>">
		<?php showrow($row, $recid) ?>
		<p><input type="button" name="btnDelete" value="Confirm"  onClick="if(onDeletes()==true){javascript:formget(this.form, 'user/processUser.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=LEVEL1');javascript:printHeader('Sales Force Admin');}">
        </p>
        <div id="output" style="color: blue;"></div>
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
  	global $repstatus;
	
	
	
  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  	
	$sql = "SELECT * from (select a.*,b.emp_code,b.emp_fname,b.emp_dname,c.design_name 
				FROM tbl_user a,tbl_employee b,tbl_design c where a.emp_id=b.emp_id and a.dstatus='0' and b.status='0' and b.emp_dest=c.id and c.levels = '$repstatus') subq where 1";
  	
	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`user_name` like '" .$filterstr ."') or (`pass_word` like '" .$filterstr ."') or (`emp_code` like '" .$filterstr ."') or (`imei_no` like '" .$filterstr ."') or (`emp_dname` like '" .$filterstr ."') or (`emp_fname` like '" .$filterstr ."') ";
  	}
  
  	if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  	if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  
 //echo "SQL :".$sql."<br>";
  	$res = mysqli_query($conn, $sql) or die("Error in SQL1 :".mysql_error());
  	return $res;
}

function sql_getrecordcount()
{
	global $conn;
  	global $order;
  	global $ordtype;
  	global $filter;
  	global $filterfield;
  	global $repstatus;

	

  	$filterstr = sqlstr($filter);
  	if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
	
	
  	$sql = "SELECT count(*) from (select a.*,b.emp_code,b.emp_fname,b.emp_dname,c.design_name 
				FROM tbl_user a,tbl_employee b,tbl_design c where a.emp_id=b.emp_id and a.dstatus='0' and b.status='0' and b.emp_dest=c.id and c.levels = '$repstatus') subq where 1";
  
  	if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    	$sql .= " and " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  	} elseif (isset($filterstr) && $filterstr!='') {
    	$sql .= " and (`user_name` like '" .$filterstr ."') or (`pass_word` like '" .$filterstr ."') or (`emp_code` like '" .$filterstr ."') or (`imei_no` like '" .$filterstr ."') or (`emp_dname` like '" .$filterstr ."') or (`emp_fname` like '" .$filterstr ."') ";
  	}
  
  //echo "SQL1 :".$sql."<br>";
  	$res = mysqli_query($conn,$sql) or die("Error in SQL2 :".mysql_error());
  	$row = mysqli_fetch_assoc($res);
  	reset($row);
  	return current($row);
}



function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`user_id`";
  	if (@$_POST["xuser_id"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xuser_id"], false);
  	};
  	$pk .= ")";
  	return $pk;
}

function getLevelName($dname){
	global $conn;
	$dquery = "SELECT * FROM (SELECT b.levels FROM tbl_employee a, tbl_design b WHERE a.emp_dest = b.id AND a.emp_id = '$dname' GROUP BY b.levels)SUBQ";
	//echo "Query :".$dquery ."<br>";
	$dresult = mysql_query($dquery,$conn) or die("Error in LevelName SQL:".mysql_error());
	$drow = mysql_fetch_assoc($dresult);
	return $drow["levels"];
}

/****************** End of the USER DEFINED Functions ***********************************/
?>
