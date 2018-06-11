<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	switch ($action) {
	   
    	case 'task' :
			updateTaskStatus();
			break;
		case 'posting' :
			updatePostStatus();
			break;
    	default :
       		print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}




/*
	Posting Sales Items
*/

function updatePostStatus()
{
	global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$lcsql = "select cat_code,prod_code,prod_qty from tbl_lead_child where lead_id='$leadid'";
		$lcres = mysql_query($lcsql,$conn);
		while($lcrow = mysql_fetch_assoc($lcres)){
			$ccode=$lcrow["cat_code"];
			$pcode=$lcrow["prod_code"];
			$pqty=$lcrow["prod_qty"];
			$pmqry = "update tbl_product set prod_qty = prod_qty -$pqty where prod_code='$pcode' and cat_code='$ccode'";
			$pmres = mysql_query($pmqry,$conn);
		}
		$sql = "update tbl_lead_master set pp_status ='YES' where lead_id='$leadid'";
		mysql_query($sql,$conn);
		if(mysql_affected_rows() >0){
			$str= "Successfully Posted";
		}
	}
	echo $str;
}
/*
    Update Task Status
*/
function updateTaskStatus()
{
   global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$tvalue = $_POST['taskstatus'];
		$sql = "update tbl_lead_master set task_status ='$tvalue' where lead_id='$leadid'";
		mysql_query($sql,$conn);
		if(mysql_affected_rows() >0){
			$str= "Successfully Updated";
		}
	}
	echo $str;
}

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

?>
