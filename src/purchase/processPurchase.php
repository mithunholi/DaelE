<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   	case 'add' :
        	addPurchasedProduct();
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
		case 'supplierAdd':
			addSupplier();
			break;
		case 'categoryAdd':
			addCategory();
			break;
    	default :
       		print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}




/*	Add a Supplier */
function addSupplier(){
	global $conn;
	$sql = "insert into tbl_supplier (supp_name, supp_address, supp_city, supp_pin, supp_state, supp_country, supp_cperson, supp_mobno, supp_landno, 
									supp_faxno, supp_email, supp_website, supp_remark) 
			VALUES (" .strtoupper(sqlvalue(@$_POST["supp_name"], true))."," .strtoupper(sqlvalue(@$_POST["supp_address"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_city"], true))."," .strtoupper(sqlvalue(@$_POST["supp_pin"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_state"], true))."," .strtoupper(sqlvalue(@$_POST["supp_country"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_cperson"], true))."," .strtoupper(sqlvalue(@$_POST["supp_mobno"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_landno"], true))."," .strtoupper(sqlvalue(@$_POST["supp_faxno"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_email"], true))."," .strtoupper(sqlvalue(@$_POST["supp_website"], true)).",
			" .strtoupper(sqlvalue(@$_POST["supp_remark"], true)).")";
	mysqli_query($conn,$sql) or die(mysqli_error());
	if(mysqli_affected_rows($conn)>0){
	 	$str = "Record Added Successfully";
	}else{
		$str = "Added Record Failed";
	}
	echo $str;

}

/*	Add a Category */
function addCategory(){
	global $conn;
	$sql = "insert into tbl_categories (cat_name, cat_tax, cat_description)	
			VALUES (" .strtoupper(sqlvalue(@$_POST["cat_name"], true))."," .strtoupper(sqlvalue(@$_POST["cat_tax"], true)).",
			" .strtoupper(sqlvalue(@$_POST["cat_description"], true)).")";
	mysqli_query($conn,$sql) or die(mysqli_error());
	if(mysqli_affected_rows($conn)>0){
	 	$str = "Record Added Successfully";
	}else{
		$str = "Added Record Failed";
	}
	echo $str;

}


/*  Add a Purchased Product*/
function addPurchasedProduct()
{
 global $conn;
 global $_POST;
 global $today;
   
  if(isset($_POST['cboCategory']) && $_POST['cboCategory']<>''){ 
  		$ccode = $_POST['cboCategory'];
		$scode = $_POST['cboSupplier'];
		if($_POST["prod_name_status"] == "newProduct"){
			$pname = strtoupper(str_replace("-","_",$_POST["prod_name"]));
		}else{
			$pname = getProductName($_POST["prod_name"]);
		}
		if(isset($_POST["prod_discount"]) && $_POST["prod_discount"] <> ''){
	  		$pd_discount = $_POST["prod_discount"];
	  	}else{
	  		$pd_discount = "0";
	  	}
		$pd_tax= $_POST["prod_tax"];
		
		$pqty = $_POST["purc_qty"] + $_POST["prod_qty"];
		$recstatus = $_POST["recordstatus"];
		if($recstatus=="new"){
  	   		$sql = "insert into tbl_product (supp_id,cat_code, part_code, prod_name, prod_sname, prod_description, prod_price, prod_price1, prod_price2, prod_tax, 
				prod_discount, prod_rec_date,prod_qty) values ('$scode','$ccode'," .strtoupper(sqlvalue(@$_POST["part_code"], true)).", '$pname', 
				" .strtoupper(sqlvalue(@$_POST["prod_sname"], true))."," .strtoupper(sqlvalue(@$_POST["prod_description"], true)).",
				" .strtoupper(sqlvalue(@$_POST["prod_price"], true))."," .strtoupper(sqlvalue(@$_POST["prod_price1"], true)).",
				" .strtoupper(sqlvalue(@$_POST["prod_price2"], true)).",'$pd_tax','$pd_discount','$today','$pqty')";
		
     		
		}else{
			$catId = (int)$_POST['cat_id'];
			$pdesc = $_POST["prod_description"];
			$pprice = $_POST["prod_price"];
			$pprice = $_POST["prod_price1"];
			$sql = "update tbl_product set prod_qty = '$pqty',prod_description= '$pdesc',prod_price = '$pprice',prod_price1='$pprice1',
						prod_tax='$pd_tax',prod_discount = '$pd_discount' where pcode = '$catId'";
			
		}
		echo $sql;
		mysqli_query($conn,$sql) or die(mysqli_error());
	  	if(mysqli_affected_rows($conn)>0){
	 		$str = "Record Added Successfully";
	 	}else{
			$str = "New Record Add Failed";
		}
  }else{
  	 $str = "Wrong Data Entry ";
  }
  echo $str;
}

function getProductName($pn){
	$qry = "select prod_name from tbl_product where prod_code='$pn'";
	$resultset = mysqli_query($conn,$qry);
	$rowset = mysqli_fetch_assoc($resultset);
	return $rowset["prod_name"];
}
function deleteProduct()
{
global $conn;
global $_POST;
    for($i=0;$i<count($_POST["userbox"]);$i++){ 
				$str=""; 
		if($_POST["userbox"][$i] != ""){
			$userid= $_POST["userbox"][$i];
			if(primaryreturn($userid,3) == true){
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
			}
			
			//if(product($userid,2) == true){
				//$str .="Product,";
			//}
			if($str==""){  
				$strSQL = "DELETE FROM tbl_product WHERE prod_code = '".$_POST["userbox"][$i]."'";
				$resultset = mysqli_query($conn,$strSQL) or die(mysqli_error());
				if(mysqli_affected_rows($conn) > 0){
					$str1 = "Deleted Successfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
		}//if  
	}//for  
  	echo $str;
}

function updateProduct()
{
 global $conn;
 global $_POST;
 global $today;
 	echo "Cat ID :".$_POST['cat_id'].':::'.$_POST["prod_name"]."<br>";
  if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0 && isset($_POST["prod_name"]) && $_POST["prod_name"] <> "") {
        $catId = (int)$_POST['cat_id'];
  		
		if(isset($_POST["prod_discount"]) && $_POST["prod_discount"] <> ''){
	  		$pd_discount = $_POST["prod_discount"];
	  	}else{
	  		$pd_discount = "0";
	  	}
  		$pd_tax= $_POST["prod_tax"];
  		//$pname = strtoupper(str_replace("-","_",$_POST["prod_name"]));
		if($_POST["prod_name_status"] == "newProduct"){
			$pname = strtoupper(str_replace("-","_",$_POST["prod_name"]));
		}else{
			$pname = getProductName($_POST["prod_name"]);
		}
		$pqty = $_POST["purc_qty"] + $_POST["prod_qty"];
		$spname = strtoupper(str_replace("-","_",$_POST["prod_sname"]));		
  		$sql = "update tbl_product set cat_code = ".strtoupper(sqlvalue(@$_POST["cboCategory"], true)).", 
						part_code= ".strtoupper(sqlvalue(@$_POST["part_code"], true)).",prod_name = '$pname', prod_sname = '$spname',
						prod_description =  " .strtoupper(sqlvalue(@$_POST["prod_description"], true)).",
						prod_price = " .sqlvalue(@$_POST["prod_price"], true).",prod_price1 = " .sqlvalue(@$_POST["prod_price1"], true).",
						prod_discount='$pd_discount',prod_tax='$pd_tax',prod_qty='$pqty',
						prod_rec_date='$today'"." WHERE prod_code = $catId";
		echo "SQL :".$sql;
		mysqli_query($conn,$sql);
  		if(mysqli_affected_rows($conn) > 0){
  			$str = "Updated Successfully";
  		}else{
			$str = "Update Failed";
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
