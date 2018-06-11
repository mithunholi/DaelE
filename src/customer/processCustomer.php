<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    case 'add' :
        	addCustomer();
        	break;
    	case 'modify' :
        	modifyCustomer();
        	break;
    	case 'update' :
			updateCustomer();
			break;
    	case 'delete' :
        	deleteCustomer();
        	break;
    	default :
		
        print "<script>window.location.href = 'customer/customer.php';</script>";
	}
}else{

	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a Customer
*/
function addCustomer()
{
 global $conn;
 global $_POST;
 global $today;
 
  if($_POST["customer_name"]<>'' && isset($_POST["customer_name"])){ 
  		$oname = str_replace("-","_",$_POST["customer_name"]);
		$oname = strtoupper($oname);
  	   	$sql = "insert into tbl_customer(customer_name,customer_address, customer_cperson, customer_city,
							 customer_mobno,customer_landno,customer_email,customer_dstatus,cdate) 
				values ('$oname', " .strtoupper(sqlvalue(@$_POST["customer_address"], true)).",
						" .strtoupper(sqlvalue(@$_POST["customer_cperson"], true))."," .strtoupper(sqlvalue(@$_POST["customer_city"], true)).",
						" .sqlvalue(@$_POST["customer_mobno"], true)."," .sqlvalue(@$_POST["customer_landno"], true).",
						" .strtolower(sqlvalue(@$_POST["customer_email1"], true)).",'false','$today')";
		
     	mysqli_query($conn,$sql) or die(mysqli_error());
	
	  	if(mysqli_affected_rows($conn) > 0){
			$str ="Added Successfully";
		}

  }else{
     
  	  $str = "Must be enter Customer Name";
  }
  echo $str;

}

function deleteCustomer()
{
	global $conn;
	global $_POST;
    
	for($i=0;$i<count($_POST["userbox"]);$i++){ 
		$str=""; 
		if($_POST["userbox"][$i] != ""){
			$userid=$_POST["userbox"][$i];
			$strSQL = "DELETE FROM tbl_customer WHERE customer_id = '".$_POST["userbox"][$i]."'";
			$result = mysqli_query($conn,$strSQL) or die(mysqli_error());
			if(mysqli_affected_rows($conn)>0){
					$str = "U101";
			}
		}//if  
	}//for 
	if(strlen($str)>1){ 
		echo "Deleted Successfully";
	} 
}

function updateCustomer()
{
 global $conn;
 global $_POST;
 global $today;
 
  if (isset($_POST['customer_id']) && (int)$_POST['customer_id'] > 0) {
        $customerId = (int)$_POST['customer_id'];
    
  		$oname = str_replace("-","_",$_POST["customer_name"]);
		$oname = strtoupper($oname);
  		$sql = "update tbl_customer set customer_name = '$oname', customer_address =  " .strtoupper(sqlvalue(@$_POST["customer_address"], true)).",
			customer_cperson = " .strtoupper(sqlvalue(@$_POST["customer_cperson"], true)).",customer_city = " .strtoupper(sqlvalue(@$_POST["customer_city"], true)).",
			customer_mobno = " .sqlvalue(@$_POST["customer_mobno"], true).",customer_landno = " .sqlvalue(@$_POST["customer_landno"], true).",cdate='$today',
			customer_email = " .strtolower(sqlvalue(@$_POST["customer_email1"], true))." WHERE customer_id = $customerId";
        //echo "SQL Query == ".$sql."<br>";
  		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn)>0){
			$str = "Updated Successfully";
		}else{
			$str = "No Data Affected";
		}
  } else {
     $str = "Wrond Data Entry";
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
