<?php
session_start();
require_once "../config.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$mtype = isset($_POST["type"]) ? $_POST["type"] : '';
	switch ($action) {
	   	case 'add' :
        	addPrimary();
        	break;
    	case 'modify' :
        	modifyPrimary();
        	break;
    	case 'update' :
			updatePrimary();
			break;
    	case 'delete' :
        	deletePrimary();
        	break;
    	default :
       		/*print "<script>window.location.href = '../CRM.php';</script>";*/
	}
}else{
	print "<script>window.location.href='index.html';</script>";	
}

/*
    Add a user
*/
function addPrimary()
{
  global $conn;
  global $_POST;
  global $mtype;
  
   $today = date('Y-m-d H:j:s'); // Today date and Time
    
  if(isset($_POST['prefix']) && $_POST['prefix']<>'' && $_POST['invno'] && $_POST['invno']<>''){ 
  	   	$sql = "insert into `tbl_invoice` (`prefix`,`invno`, `invtype`,`cdate`) values (" .strtoupper(sqlvalue(@$_POST["prefix"], true))."," .strtoupper(sqlvalue(@$_POST["invno"], true)).",'$mtype','$today')";
		
     	mysql_query($sql, $conn) or die(mysql_error());
	  	/*print("<script>history.go(-1);</script>");*/
		$str = "Added Successfully";
	
  }else{
		$str = "Must be enter prefix and invno";
  }
  	echo $str;
//  javascript:process('products/product.php')

}

function deletePrimary()
{
global $conn;
global $_POST;
   for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_invoice WHERE id = '".$_POST["userbox"][$i]."'";
			$resultset = mysql_query($strSQL) or die(mysql_error());
		}  
	}  
    /*print "<script>window.location.href = 'products/product.php';</script>";*/
}

function updatePrimary()
{
 global $conn;
 global $_POST;
 global $mtype;
  
   $today = date('Y-m-d H:j:s'); // Today date and Time
   
  if (isset($_POST['prefix']) && $_POST['prefix']<>'' && $_POST['invno'] && $_POST['invno']<>'') {
  	$id = $_POST['id'];
    $sql = "update tbl_invoice set invno = " .strtoupper(sqlvalue(@$_POST["invno"], true)).", prefix =  " .strtoupper(sqlvalue(@$_POST["prefix"], true)).",
			invtype = '$mtype'
			WHERE id = $id";
  
  	mysql_query($sql,$conn);
	if(mysql_affected_rows()>0){
		$str = "Updated Successfully";
	}

  }else{
  	$str = "Must be enter prefix and invno";
  }
  	echo $str;

  /*print "<script>window.location.href = '../CRM.php';</script>";*/
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
