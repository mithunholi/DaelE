<?php session_start();
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
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
  
  if (isset($_GET["orderid"])) $orderid = stripslashes(@$_GET["orderid"]);
  if(isset($_GET["pageId"])) $pageId = $_GET["pageId"]; 
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];
  if(isset($_GET["recstatus"])) $recstatus = $_GET["recstatus"];  

  if (!isset($orderid) && isset($_SESSION["orderid"])) $orderid = $_SESSION["orderid"];
  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
  if (!isset($recstatus) && isset($_SESSION["recstatus"])) $recstatus = $_SESSION["recstatus"];
?>
<?php if(isset($orderid) && $orderid <> ''){ ?>
<html>
<head>
<title>mCRM - Opening Stock Details</title>
<meta name="generator" http-equiv="content-type" content="text/html">
<link href="main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="CRM.js"></script>
</head>
<body>
<?php
  require_once("../config.php");
  
  if($pageId == "" or ((int) $pageId <= 0)){
  	$showrecs = REC_PER_PAGE;
  }else{
  	$showrecs = $pageId;
  }
  $pagerange = PAGE_RANGE;

  $a = @$_GET["a"];

  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  select();

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($orderid)) $_SESSION["orderid"] = $orderid;
  if (isset($pageId)) $_SESSION["pageId"] = $pageId;
  if (isset($recstatus)) $_SESSION["recstatus"] = $recstatus;
  
  mysql_close($conn);
?>
<table class="bd" width="100%"><tr><td class="hr"></td></tr></table>
</body>
</html>
<?php }//end of condition 

}else{
	print "<script>window.location.href='../index.html';</script>";	
}
?>
<?php function select()
  {
  
  global $a;
  global $showrecs;
  global $page;
  global $order;
  global $ordtype;
  global $orderid;
  global $pageId;
  global $conn;
  global $recstatus;
  //echo 'Order Id***:'.$oid;
  if ($a == "reset") {
    $order = "";
    $ordtype = "";
	$pageId = "";
	$orderid = "";
	$page = "";
  }

  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $query = sql_select();
  $res = mysql_query($query,$conn) or die(mysql_error());
  $res1 = mysql_query($query,$conn) or die(mysql_error());
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<?php
$heading = "Primary Opening Stock";
?>

<tr><td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/openingStock.php?status=true&recstatus=All');javascript:printHeader('<?php echo $heading; ?>');"></td></tr>
</table>
<hr size="1" noshade>


<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="100%">
<?php 
	$row1 = mysql_fetch_assoc($res1); 
	$username = getUserName($row1["imeino"]);
?>
<tr align="left">
<td class="hr" width="20%">Order ID : </td><td class="dr" align="left" width="25%"><?php echo  $row1["orderid"]; ?></td>
<td class="hr" width="20%">&nbsp; </td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
<td class="hr" width="20%">Salesman : </td><td class="dr" align="left" width="25%"><?php echo  getEmployeeName($username); ?></td>
<td class="hr" width="20%">&nbsp;</td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
<td class="hr" width="20%">Distributor : </td><td class="dr" align="left" width="25%"><?php echo  $row1["distcode"]." - ".$row1["distname"]; ?></td>
<td class="hr" width="20%">&nbsp;</td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
	  <td class="hr" width="20%">Route Name: </td><td class="dr" align="left" width="25%"><?php echo $row1["route_code"]." - ".$row1["route_name"]; ?></td>
	  <td class="hr" width="20%">&nbsp;</td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
	  <td class="hr" width="20%">Outlet Name: </td><td class="dr" align="left" width="25%"><?php echo $row1["outletname"]; ?></td>
	  <td class="hr" width="20%">&nbsp;</td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
<td class="hr" width="20%">Order Date: </td><td class="dr" align="left" width="25%"><?php echo $row1["odate"]; ?></td>
<td class="hr" width="20%">&nbsp;</td><td class="dr" align="left" width="25%">&nbsp;</td>
</tr>
<tr align="left">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
</table>
<?php
if($a=="Edit"){
?>
<form name="opstockdetail" action="Inbox/processStockDetails.php?aa=<?php echo $a ?>" method="post">
<?php } ?>
	<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="100%">
        <tr align="left">
        	<td class="hr" width="50%"><?php echo htmlspecialchars("Product Name") ?></td>
        	<td class="hr" width="25%"><?php echo htmlspecialchars("Quantity") ?></td>
        </tr>
		<?php
		  $tot=0;
		$prev="";
		for ($i = $startrec; $i < $count; $i++)
		{
			$row = mysql_fetch_assoc($res);
			$style = "dr";
			if ($i % 2 != 0) {
			  $style = "sr";
			}
			if($prev != $row['categoryname']){
				$prev = $row['categoryname'];
		?>
		       <tr><td colspan="2" class="hr" align="left"><b><?php echo $prev; ?></b></td></tr>
      <?php } ?>
		<tr align="left">
            <input type="hidden" name="ordid" id="ordid" value = "<?php echo $row['orderid'] ?>">
            <input type="hidden" name="pid[]" id="pid[<?php echo $i; ?>]" value = "<?php echo $row['prod_id'] ?>">
            <input type="hidden" name="pname" id="pname" value = "<?php echo $row['prodname'] ?>">
            <td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["prodname"]) ?></td>
            <td class="<?php echo $style ?>">
            <?php
				if($a== "Edit") {
			?>
					<input type="text" name="qty[]" id="qty[<?php echo $i; ?>]" value ="<?php echo number_format($row["qty"]) ?>">
			<?php
				}else{
					echo number_format($row["qty"]); 
				} 
			?>
			</td>
		</tr>
	<?php
  		}
  		mysql_free_result($res);
	?>
	</table>
    <?php 
//echo "AAAAAAAAAAAAAA =".$a."<br>";
if ($a == "Edit" or $a == "Reject"){
	
?>
<table>
<tr align="left"><td>Remark :</td>
<td><textarea name="remark" id="remark" rows="4" cols="30"><?php echo $row["remark"]; ?></textarea></td>
</tr>
</table>
<?php
}

 if($row1['status'] != 'Invoice'){
?>
<table>
 <tr>
	<td>
		<table class="tbl" border="0" cellspacing="1" cellpadding="5" width="50%">
			<tr>
		<?php 
			if($a!="Edit" && $a != "Reject"){
				$mailcontent = getEmailId($row1["distcode"]);
				$mailcontent .= "?subject=myreport";
				//echo "Mail content =".$mailcontent."<br>";
					//echo "Not in Edit Mode"."<br>";
					$url= "Inbox/openingStock.php?status=true&recstatus=All";
			?>
            <td align="right">
                <form name="export" target ="_blank" action="Inbox/stockDetailstoCSV.php"  method="post">
                <input type="submit" name="expcsv" id="expcsv" value="Export to XLS">
                <input type="hidden" name="orderid" id="orderid" value="<?php echo $row1["orderid"]; ?>">
                </form>
            </td>
            <td align="right">
                <form name="mail" target="_blank" action="mailto:selva_gee@yahoo.com?subject=Comments from MailTo Syntax Page">
					<input type="button" name="mail" id="mail" value="Mail" onClick="javascript:document.mail.submit();">
    			</form>
            </td>
            <td align="right">
                <input type="button" name="edit" id="edit" value="Edit" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?orderid=<?php echo $orderid; ?>&a=Edit');">
            </td>
          </tr>
     	</table>
   	</td>
   	<td>
     <form name="frmaccept" action="" method="post">
     <input type="hidden" name="order_id" id="order_id" value = "<?php echo $orderid ?>" >
     
     <input type="hidden" name="aa" id="aa" value="Accept">
     	
        <table class="tbl" border="0" cellspacing="0" cellpadding="0" width="50%">
    	 <tr>	
       		<td align="left">
            <input type="button" name="accept" value="Accept" 
            	onClick="javascript:formget(this.form,'Inbox/processStockDetails.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>');">
            </td>
        </tr>
        <tr>
        <td colspan="2">
        	<div id="output" style="color: blue;"></div>
        </td>
        </tr>
     </table>
     </form>
  </td>
  </td>
  <td>
     <form name="frmreject" action="" method="post">
     <input type="hidden" name="order_id" id="order_id" value = "<?php echo $orderid ?>" >
     
     <input type="hidden" name="aa" id="aa" value="Reject">
      <table class="tbl" border="0" cellspacing="0" cellpadding="0" width="50%">
    	<tr>
	     	<td align="left">
            	<input type="button" name="reject" value="Reject" onClick=" javascript:formget(this.form,'Inbox/processStockDetails.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>');">
            </td>
        </tr>
        <tr>
        <td colspan="2" width="100%">
        	<div id="output" style="color: blue;"></div>
        </td>
        </tr>
     </table>
     </form>
   </td>
   	<?php
   	}
  	else{
  	?>
       <td align="right">
    <input type="hidden" name="action" id="action" value="update">
    <input type="hidden" name="order_id" id="order_id" value= "<?php echo $orderid; ?>">
    
    <input type="hidden" name="aa" value="<?php echo $a ?>">
    
	<input type="button" name="btnedit" id="btnedit" value="Update" onClick="javascript:formget(this.form,'Inbox/processStockDetails.php');javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/openingStock.php?status=true&recstatus=All')"></td>
    <td align="right">
    <input type="button" name="cancel" id="cancel" value="Cancel" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?orderid=<?php echo $orderid; ?>&a=Cancel');">
    </td>
    <div id="output" style="color: blue;"></div>
   <?php
   	}
   ?>
	</tr>
	</table>
<?php
}
if($a=="Edit"){
?>
 </form>
<?php } ?>
<br>
<?php //showpagenav($page, $pagecount); ?>
<?php }



function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<?php if ($page > 1) { ?>
<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?page=<?php echo $page - 1 ?>')">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
<?php } ?>
<?php
  global $pagerange;

  if ($pagecount > 1) {

  if ($pagecount % $pagerange != 0) {
    $rangecount = intval($pagecount / $pagerange) + 1;
  }
  else {
    $rangecount = intval($pagecount / $pagerange);
  }
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);

    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<td><b><?php echo $j ?></b></td>
<?php } else { ?>
<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?page=<?php echo $j ?>')"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?page=<?php echo $startpage ?>')"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/opstockDetails.php?page=<?php echo $page + 1 ?>')">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $orderid;
  $sql1 = "update tbl_openstock set vstatus='true' where orderid='$orderid'";
  $res1 = mysql_query($sql1,$conn) or die('Error in Update'.mysql_error());
  $sql = "SELECT *
  			FROM `tbl_openstock`";
  $sql .= " WHERE orderid='$orderid'";
 // echo "SQL STATEMENT =".$sql."<br>";
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
 // $res = mysql_query($sql, $conn) or die(mysql_error());
  //return $res;
  $_SESSION['menuid']=3;
  return $sql;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
   global $orderid;
  $sql = "SELECT COUNT(*) FROM `tbl_openstock`";
  $sql .= " WHERE orderid='$orderid'";
   //echo "SQL STATEMENT COUNT =".$sql."<br>";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} 
function getEmailId($did){
	global $conn;
	$sql = "select dist_email1,dist_email2 from tbl_distributor where dist_code = '$did'";
	$rs = mysql_query($sql,$conn);
	$rw = mysql_fetch_assoc($rs);
	$eid1 = $rw["dist_email1"];
	$eid2 = $rw["dist_email2"];
	if($eid1 != ""){
		$eid = $eid1;
	}
	
	if($eid2 != "" && $eid1 == ""){
		$eid = $eid2;
	}
	
	if($eid1 != "" && $eid2 != ""){
		$eid = $eid1.",".$eid2;
	}
	
	if($eid1 == "" && $eid2 == ""){
		$eid = "";
	}
	
	return $eid;
	
}

function getUserName($imno){
	global $conn;
  $sqlQuery = "select a.Title from tbl_user a,tbl_orderbook b where a.imei_no = b.imeino and b.imeino=".$imno;
  $res = mysql_query($sqlQuery);
  if(mysql_num_rows($res)>0){
  	$resRow = mysql_fetch_assoc($res);
	$username = $resRow["Title"]; 
  }else{
  	$username="";
  }
  return $username;
}

function getEmployeeName($empid)
{
	$sqlQuery = "select concat(emp_fname,' ',emp_lname) emp_name from tbl_employee where emp_id = '$empid'";
	
	$res = mysql_query($sqlQuery);
  	if(mysql_num_rows($res)>0){
  		$resRow = mysql_fetch_assoc($res);
		$distname = $resRow["emp_name"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}
?>
