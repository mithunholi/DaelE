<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   	case 'add' :
        	addProduct();
        	break;
    	case 'modify' :
        	modifyProduct();
        	break;
    	case 'update' :
			updateProduct();
			break;
    	case 'delete' :
        	deleteProduct();
        	break;
    	default :
       		print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a user
*/
function addProduct()
{
 global $conn;
 global $_POST;
 global $today;
   
  if(isset($_POST['cboCategory']) && $_POST['cboCategory']<>''){ 
  		$ccode = $_POST['cboCategory'];
		$ucode = $_POST['cboUnit'];
		$pname = strtoupper(str_replace("-","_",mysqli_real_escape_string ($conn, $_POST["prod_name"])));
		
		if(isset($_POST["prod_discount"]) && $_POST["prod_discount"] <> ''){
	  		$pd_discount = $_POST["prod_discount"];
	  	}else{
	  		$pd_discount = "0";
	  	}
		$pd_tax= $_POST["prod_tax"];
		
		
  	   	$sql = "insert into tbl_product (cat_code, part_code, prod_name, prod_sname, prod_description, prod_price, prod_price1, unit_code, prod_obal_qty, 
				prod_qty, prod_tax, prod_discount, prod_rec_date) values ('$ccode'," .strtoupper(sqlvalue(@$_POST["part_code"], true)).", '$pname', 
				" .strtoupper(sqlvalue(@$_POST["prod_sname"], true))."," .strtoupper(sqlvalue(@$_POST["prod_description"], true)).",
				" .strtoupper(sqlvalue(@$_POST["prod_price"], true))."," .strtoupper(sqlvalue(@$_POST["prod_price1"], true)).",'$ucode',
				" .sqlvalue(@$_POST["prod_obal_qty"], true)."," .sqlvalue(@$_POST["prod_obal_qty"], true).",'$pd_tax','$pd_discount','$today')";
		
     	mysqli_query($conn, $sql) or die(mysqli_error());
	  	if(mysqli_affected_rows($conn)>0){
	 		$str = "Record Added Successfully";
	 	}else{
			$str = "New Record Added Failed";
		}
  }else{
  	 $str = "Wrong Data Entry ";
  }
  echo $str;
}

function deleteProduct()
{
global $conn;
global $_POST;
    for($i=0;$i<count($_POST["userbox"]);$i++){ 
				$str=""; 
		if($_POST["userbox"][$i] != ""){
			$userid= $_POST["userbox"][$i];
			/*if(primaryreturn($userid,3) == true){
				$str .= "Primary Return,";
			}
				
			if(primaryOS($userid,3) == true){
				$str .= "Primary Opening Stock,";
			}
				
			if(secondaryOS($userid,3) == true){
				$str .= "Secondary Opening Stock,";
			}
				
			if(primaryOB($userid,3) == true){
				$str .= "Primary Bookings,";
			}
				
			if(secondaryOB($userid,3) == true){
				$str .= "Secondary Bookings,";
			}*/
			
			//if(product($userid,2) == true){
				//$str .="Product,";
			//}
			//if($str==""){  
				$strSQL = "DELETE FROM tbl_product WHERE prod_code = '".$_POST["userbox"][$i]."'";
				$resultset = mysqli_query($conn, $strSQL) or die(mysqli_error());
				if(mysqli_affected_rows() > 0){
					$str1 = "U101";
				}
			//}else{
				//$str1 = "Unable to delete. Dependent data found in ".$str;
			//}
			//$str = substr($str1,0,strlen($str1)-1);
		}//if  
	}//for 
	if($str1 == "U101"){ 
  		echo "Deleted Successfully";
	}
}

function updateProduct()
{
 global $conn;
 global $_POST;
 global $today;
  if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0 && isset($_POST["prod_name"]) && $_POST["prod_name"] <> "") {
        $catId = (int)$_POST['cat_id'];
  		
		if(isset($_POST["prod_discount"]) && $_POST["prod_discount"] <> ''){
	  		$pd_discount = $_POST["prod_discount"];
	  	}else{
	  		$pd_discount = "0";
	  	}
  		$pd_tax= $_POST["prod_tax"];
  		$pname = strtoupper(str_replace("-","_",$_POST["prod_name"]));
		$spname = strtoupper(str_replace("-","_",$_POST["prod_sname"]));
  		$sql = "update tbl_product set cat_code = ".strtoupper(sqlvalue(@$_POST["cboCategory"], true)).", 
						unit_code = ".strtoupper(sqlvalue(@$_POST["cboUnit"], true)).", 
						part_code= ".strtoupper(sqlvalue(@$_POST["part_code"], true)).",prod_name = '$pname', prod_sname = '$spname',
						prod_description =  " .strtoupper(sqlvalue(@$_POST["prod_description"], true)).",
						prod_price = " .sqlvalue(@$_POST["prod_price"], true).",prod_price1 = " .sqlvalue(@$_POST["prod_price1"], true).",
						prod_obal_qty = " .sqlvalue(@$_POST["prod_obal_qty"], true).",prod_discount='$pd_discount',prod_tax='$pd_tax',
						prod_qty = " .sqlvalue(@$_POST["prod_obal_qty"], true).",prod_rec_date='$today'"." WHERE prod_code = $catId";

		mysqli_query($conn, $sql);
  		if(mysqli_affected_rows() > 0){
  			$str = "Updated Successfully";
  		}else{
			$str = "No Data Affected";
		}
  } else {
        $str = "Category ID not found ";
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
