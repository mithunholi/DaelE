<?php
session_start();
require_once "../config.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){

	$action = isset($_POST['action']) ? $_POST['action'] : '';


	switch ($action) {
		
		case 'add' :
			addOP();
			break;
		  
		case 'modify' :
		   updateOP();
			break;
		case 'update' :
			updateOP();
			break;
		case 'delete' :
			deleteOP();
			break;
	   
		default :
			print "<script>window.location.href = '../CRM.php';</script>";
	}//switch
}else{
	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a user
*/
function addOP()
{
  global $conn;
  global $_POST;
  $today = date('Y-m-d H:j:s'); // Today date and Time
  if(isset($_POST['cboCategory']) && $_POST['cboCategory']<>'' && isset($_POST["prod_name"]) && $_POST["prod_name"]<>''){ 
  	  $ccode = strtoupper($_POST['cboCategory']); 
	  $pname = strtoupper($_POST["prod_name"]);
	  
	  $sql_query = "select * from tbl_openbalance where cat_code = '$ccode' and prod_name = '$pname'";
	  $sql_result = mysql_query($sql_query,$conn);
	  if(mysql_num_rows($sql_result)>0){
	  	$str = "Data Already Exists";
	  }else{
      	$sql = "insert into `tbl_openbalance` (`cat_code`,`prod_name`, `prod_qty`, `rec_date`) values ('$ccode'," .strtoupper(sqlvalue(@$_POST["prod_name"], true)).",
	  			 " .sqlvalue(@$_POST["prod_qty"], true).", '$today')";
	  
     	mysql_query($sql, $conn) or die(mysql_error());
	 	if(mysql_affected_rows() >0){
	 		$str ="Added Successful";
	 	}
	  }
  }else{
  	$str = "Must be enter category, product name";
  }
  echo $str;
}

function deleteOP()
{
	global $conn;
	global $_POST;
    
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_openbalance WHERE id = '".$_POST["userbox"][$i]."'";
			$resultset = mysql_query($strSQL,$conn) or die(mysql_error());
		}  
	}  
}

function updateOP()
{
 global $conn;
 global $_POST;
 
  if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0) {
        $catId = (int)$_POST['cat_id'];
  
  		$sql = "update tbl_openbalance set cat_code = " .strtoupper(sqlvalue(@$_POST["cboCategory"], true)).", 
					prod_name = " .strtoupper(sqlvalue(@$_POST["prod_name"], true)).", 
					prod_qty =  " .sqlvalue(@$_POST["prod_qty"], true)." 
  					WHERE id = $catId";
			mysql_query($sql,$conn);
			if(mysql_affected_rows()>0){
				$str = "Updated Successful";
			}else{
				$str = "No Changes";
			}
  } else {
        $str = "Wrong Data Entry";
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
