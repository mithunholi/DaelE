<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   	case 'add' :
        	//addPurchasedProduct();
			addPurchase();
        	break;
    	case 'modify' :
        	modifyProduct();
        	break;
    	case 'update' :
			updatePurchase();
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
		case 'posting' :
			addPosting();
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
	mysqli_query($sql, $conn) or die(mysqli_error());
	if(mysqli_affected_rows()>0){
	 	$str = "Record Added Successfully";
	}else{
		$str = "Added Record Failed";
	}
	echo $str;

}

/*	Add a Posting */
function addPosting(){
	global $conn;
	global $_POST;
    for($i=0;$i<count($_POST["userbox"]);$i++){ 
		$strSQL = "UPDATE tbl_purc_master SET purc_status='DONE' WHERE purc_id = '".$_POST["userbox"][$i]."'";
		$resultset = mysqli_query($conn,$strSQL) or die(mysqli_error());
		$updateSQL = "select * from tbl_purc_child where purc_id = '".$_POST["userbox"][$i]."'";
		$updateRES = mysqli_query($conn,$updateSQL) or die(mysqli_error());
		if(mysqli_num_rows($updateRES) > 0){
			while($updateROW = mysqli_fetch_assoc($updateRES)){
				$partno = $updateROW["part_code"];
				$pcode = $updateROW["prod_code"];
				$ccode = $updateROW["cat_code"];
				$qty = $updateROW["prod_qty"];
				$upSQL = "update tbl_product set prod_qty = prod_qty + $qty where part_code='$partno'";
				mysqli_query($conn,$upSQL);
				if(mysqli_affected_rows() > 0){
					$strStatus = "U101";
				}

			}//while
		}//if
	}//for
	
	if($strStatus == "U101"){
		
		echo "Posted Successfully";
	}else{
		echo "Posted Failed";
	}
	
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

function updatePurchase()
{
 global $conn;
 global $_POST;
 global $today;
 $userid= $_SESSION['User_ID'];
 $username=$_SESSION['User_Name'];
 $str="";
 //echo "Customer ID :".$_POST['cust_id']." = Lead ID :".$_POST['lead_id'];
 if(isset($_POST['purc_id']) && $_POST['purc_id']<>''){ 
 		$purcid=$_POST['purc_id'];
 		$suppid = $_POST["cboSupplier"];
 		$totamt = $_POST['txttotalamt'];
		$disamt = $_POST['txtdiscount'];
		$purc_billno = $_POST['purc_billno'];	
		$netamt = $totamt - $disamt;
		//$purcid= $_POST["lead_id"];
		$purcdate = $today; 
		$sql = "update tbl_purc_master set purc_bill_no = '$purc_billno',purc_date='$purcdate',supp_id='$suppid',purc_amount='$totamt',purc_discount='$disamt',
					purc_net_amt='$netamt' where purc_id = '$purcid'";
		
		mysqli_query($conn,$sql) or die("Error in PurcM Table Query ".mysqli_error());
		if(mysqli_affected_rows($conn)>0){
			$deleteqry = "delete from tbl_purc_child where purc_id='$purcid'";
			mysqli_query($conn,$deleteqry);
			$sqlqry = "select * from tbl_temp_product";
			$sqlrs = mysqli_query($conn,$sqlqry);
			$totamt=0;
			$sql1="";
			while($sqlrow = mysqli_fetch_assoc($sqlrs)){
				$pid=$sqlrow["prod_code"];
				$cid = $sqlrow["cat_code"];
				$pqty = $sqlrow["prod_qty"];
				$pprice = $sqlrow["prod_price"];
				$pamount = $sqlrow["prod_amount"];
				$ptax = $sqlrow["prod_tax"];
				$pdiscount = $sqlrow["prod_discount"];
				$partno = getPartNumber($pid,$cid);
				if(($pid != "" and $pid != "0") and ($cid != "0" and $cid !="")){
					$sql1 .= "('$purcid','$partno','$cid','$pid','$pqty','$pprice','$pamount','$ptax','$pdiscount'),";
					$totamt = $totamt + $pamount;
				}
			}
			$sqlRes = substr($sql1,0,strlen($sql1)-1);
			//echo "SQL 1".$sqlRes."<br>";
			$sql_query = "insert into tbl_purc_child (purc_id, part_code, cat_code, prod_code, prod_qty, 
								prod_price, prod_amount, prod_tax, prod_discount) values ".$sqlRes;
			mysqli_query($conn,$sql_query) or die("Error in Purchase Table :".mysqli_error());
			
			
		}
	//$totamt = $totamt - $disamt;
	$str = "U100";
		
  }else{
  	 $str = "Wrong Data Entry ";
  }
  if($str=="U100"){
  	$str = "Successfully Data Updated";
  }
  echo $str; 
}


function addPurchase()
{
 global $conn;
 
 global $_POST;
 global $today;
 $userid= $_SESSION['User_ID'];
 $username=$_SESSION['User_Name'];
 $str="";
 //echo "Customer ID :".$_POST['cust_id']." = Lead ID :".$_POST['lead_id'];
 if(isset($_POST['cboSupplier']) && $_POST['cboSupplier']<>''){ 
 		$suppid = $_POST["cboSupplier"];
 		$totamt = $_POST['txttotalamt'];
		$disamt = $_POST['txtdiscount'];
		$purc_billno = $_POST['purc_billno'];	
		$netamt = $totamt - $disamt;
		
		//echo "Total Amt :".$totamt."=Discount Amt :".$disamt."= Net Amt :".$netamt."<br>";
		//$purcid= $_POST["lead_id"];
		$purcdate = $today; 
		$sql = "insert into tbl_purc_master (purc_id,purc_bill_no,purc_date,supp_id,purc_amount,purc_discount,purc_net_amt) values ('','$purc_billno','$purcdate','$suppid','$totamt','$disamt','$netamt')";
		mysqli_query($conn,$sql) or die("Error in Lead Table Query ".mysqli_error());
		if(mysqli_affected_rows($conn)>0){
			$sqlqry = "select * from tbl_purc_master order by purc_id desc limit 1";
			$sqlres = mysqli_query($conn,$sqlqry);
			$sqlrow = mysqli_fetch_assoc($sqlres);
			$purcid = $sqlrow["purc_id"];
			if(dataFound()){
				$deleteqry = "delete from tbl_purc_child where purc_id='$purcid'";
				mysqli_query($conn,$deleteqry);
				
				$sqlqry = "select * from tbl_temp_product";
				$sqlrs = mysqli_query($conn,$sqlqry);
				$totamt=0;
				$sql1="";
				while($sqlrow = mysqli_fetch_assoc($sqlrs)){
					$pid=$sqlrow["prod_code"];
					$cid = $sqlrow["cat_code"];
					$pqty = $sqlrow["prod_qty"];
					$pprice = $sqlrow["prod_price"];
					$pamount = $sqlrow["prod_amount"];
					$ptax = $sqlrow["prod_tax"];
					$pdiscount = $sqlrow["prod_discount"];
					$partno = getPartNumber($pid,$cid);
					if(($pid != "" and $pid != "0") and ($cid != "0" and $cid !="")){
						$sql1 .= "('$purcid','$partno','$cid','$pid','$pqty','$pprice','$pamount','$ptax','$pdiscount'),";
						$totamt = $totamt + $pamount;
					}
				}
				$sqlRes = substr($sql1,0,strlen($sql1)-1);
				//echo "SQL 1".$sqlRes."<br>";
				$sql_query = "insert into tbl_purc_child (purc_id, part_code, cat_code, prod_code, prod_qty, prod_price, prod_amount, prod_tax, prod_discount) values ".$sqlRes;
				mysqli_query($conn,$sql_query) or die("Error in Purchase Table :".mysqli_error());
			}
			//$totamt = $totamt - $disamt;
			$str = "U100";
		}
  }else{
  	 $str = "Wrong Data Entry ";
  }
  if($str=="U100"){
  	$str = "Successfully Data Added";
  }
  echo $str;
}

function getPartNumber($pcode,$catcode){
	global $conn;
	$pqry = "select part_code from tbl_product where prod_code='$pcode' and cat_code='$catcode' limit 1";
	$pres = mysqli_query($conn,$pqry);
	$prow = mysqli_fetch_assoc($pres);
	return $prow["part_code"];
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
