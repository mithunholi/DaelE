<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   
    	case 'update' :
			updatePayment();
			break;
    	case 'editarea' :
        	if($_GET['action1']=='reject'){
			
				rejectPayment();
			}
        	break;
		case 'delete' :
			deletePayment();
			break;
		default :
       		print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}





/*
    Reject a Payment
*/
function rejectPayment()
{
   global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$sql = "update tbl_lead_master set reject_status ='1',reject_date='$today' where lead_id='$leadid'";
		mysql_query($sql,$conn);
		if(mysql_affected_rows() >0){
			$str= "Successfully Rejected";
		}
	}
	echo $str;
}
/*
    Add a Lead
*/
function addLead()
{
 global $conn;
 global $_POST;
 global $today;
  $userid= $_SESSION['User_ID'];
  if(isset($_POST['cust_name']) && $_POST['cust_name']<>''){ 
  		
		$cname = strtoupper(str_replace("-","_",$_POST['cust_name']));
		$cadd = strtoupper(str_replace("-","_",$_POST['cust_add']));
		$ccity = strtoupper(str_replace("-","_",$_POST['cust_city']));
		$ccountry = strtoupper(str_replace("-","_",$_POST['cust_country']));
		$cperson = strtoupper(str_replace("-","_",$_POST['cust_cperson']));
		$cmobno = $_POST['cust_mobno'];
		$cemail = $_POST['cust_email'];
		$cweb = $_POST['cust_web'];
		$pdesc = rtrim(mysql_real_escape_string(strtoupper($_POST["prod_desc"])));
		$ldate = date("Y-m-d",strtotime($_POST["lead_date"]));
		$refby = $_POST["ref_by"];
		$custreq = $_POST["cust_require"];
		$fdate = date("Y-m-d",strtotime($_POST["follow_up_date"]));
		$remark = $_POST["remark"];
		$today = date("Y-m-d",strtotime($today));
		$cust_sql="insert into tbl_customer (customer_id,customer_name, customer_address, customer_city, customer_country, customer_mobno, customer_cperson, customer_email,customer_web, cdate, customer_dstatus) values ('','$cname','$cadd','$ccity','$ccountry','$cmobno','$cperson','$cemail','$cweb','$today','false')";
//		echo $cust_sql;
		mysql_query($cust_sql,$conn) or die("Error in Customer Table :".mysql_error());
		
		if(mysql_affected_rows() >0){
			$customer_sql = "select * from tbl_customer order by customer_id desc";
			$customer_result = mysql_query($customer_sql,$conn);
			$customer_row = mysql_fetch_assoc($customer_result);
			$ccode = $customer_row["customer_id"];					
		   	$sql = "insert into tbl_lead_master(cust_code, ref_by, lead_book_date, lead_book_status, lead_by, cust_require, follow_up_date, remark) 
										values  ('$ccode','$refby','$ldate','0','$userid','$custreq','$fdate','$remark')";
		
			mysql_query($sql, $conn) or die("Error in Lead Table Query ".mysql_error());
			if(mysql_affected_rows() > 0){
				$ssql = "select * from tbl_lead_master order by lead_id desc";
				$result = mysql_query($ssql,$conn);
				$row = mysql_fetch_assoc($result);
				$leadid = $row["lead_id"];
				$sql1 = "insert into tbl_lead_child (lead_id,prod_desc) values ('$leadid','$pdesc') ";
				mysql_query($sql1,$conn) or die("Error in Lead Table :".mysql_error());
				if(mysql_affected_rows()>0){
					$str = "Record Added Successfully";
				}else{
					$str = "New Record Added Failed";
				}
			}else{
				$str = "New Record Added Failed";
			}
	  }else{
	  	$str = "New Record Added Failed";
	  }
  }else{
  	 $str = "Wrong Data Entry ";
  }
  echo $str;
}

function deleteLead()
{
global $conn;
global $_POST;
    for($i=0;$i<count($_POST["userbox"]);$i++){ 
				$str=""; 
		if($_POST["userbox"][$i] != ""){
			//$userid= $_POST["userbox"][$i];
			
			$strSQL = "DELETE FROM tbl_lead_master,tbl_lead_child 
						USING tbl_lead_master INNER JOIN tbl_lead_child 
						WHERE tbl_lead_master.lead_id=tbl_lead_child.lead_id and tbl_lead_master.lead_id = '".$_POST["userbox"][$i]."'";
			$resultset = mysql_query($strSQL,$conn) or die("Error in Deleted ".mysql_error());
			if(mysql_affected_rows() > 0){
				$str1 = "Deleted Successfully";
			}else{
				$str1 = "Unable to delete";
			}
		}//if  
	}//for  
  	echo $str;
}

function updatePayment()
{
 global $conn;

 global $_POST;
 global $today;
 $userid= $_SESSION['User_ID'];
 $str="";
 //echo "Customer ID :".$_POST['cust_id']." = Lead ID :".$_POST['lead_id'];
 if(isset($_POST['lead_id']) && $_POST['lead_id']<>''){ 
 		$rstatus = $_POST["recordstatus"];
 		$quoteid =$_POST['quot_id'];
		$leadid =$_POST['lead_id'];
		$balanceamt = $_POST['balance_amt'];
		$totalamt = $_POST['total_amt'];
 		$payment_type = $_POST['cmbpaytype'];
		$payment_method = $_POST['cmbpaymethod'];
		$payment_amount	= $_POST['paymentamount'];
		$payment_date = date("Y-m-d",strtotime($_POST['payment_date']));
		if($payment_method != "cash"){
			$payment_number = $_POST['paymentnumber'];
			$bankinfo = $_POST['bankinfo'];
			if($rstatus == "editrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_payment set lead_id='$leadid',quot_id='$quoteid',pay_type='$payment_type',pay_method='$payment_method',
						cheque_number='$payment_number',paid_amount='$payment_amount',pay_date='$payment_date',bank_info='$bankinfo',record_status='editrecord' 
						where id='$rid'";
			}elseif($rstatus == "confirmrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_payment set lead_id='$leadid',quot_id='$quoteid',pay_type='$payment_type',pay_method='$payment_method',
						cheque_number='$payment_number',paid_amount='$payment_amount',pay_date='$payment_date',bank_info='$bankinfo',record_status='confirmrecord' 
						where id='$rid'";
			}elseif($rstatus=="deleterecord"){
				$rid=$_POST['recordid'];
				$query = "delete from tbl_lead_payment where id='$rid'";
			}else{
				$query = "insert into tbl_lead_payment values ('','$leadid','$quoteid','$payment_type','$payment_method','$payment_number',
					'$payment_amount','$payment_date','$bankinfo','newrecord')";
			}
		}else{
			if($rstatus == "editrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_payment set lead_id='$leadid',quot_id='$quoteid',pay_type='$payment_type',pay_method='$payment_method',
						paid_amount='$payment_amount',pay_date='$payment_date',record_status='editrecord' where id='$rid'";
			}elseif($rstatus=="confirmrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_payment set lead_id='$leadid',quot_id='$quoteid',pay_type='$payment_type',pay_method='$payment_method',
						paid_amount='$payment_amount',pay_date='$payment_date',record_status='confirmrecord' where id='$rid'";
			}elseif($rstatus=="deleterecord"){
				$rid=$_POST['recordid'];
				$query = "delete from tbl_lead_payment where id='$rid'";
			}else{
				$query = "insert into tbl_lead_payment values ('','$leadid','$quoteid','$payment_type','$payment_method','',
					'$payment_amount','$payment_date','','newrecord')";
			}
		}
		mysql_query($query,$conn);
		if($rstatus=="confirmrecord"){
			$balanceamt = $balanceamt - $payment_amount;
			$sql = "update tbl_lead_master set balance_amt='$balanceamt' where lead_id='$leadid'";
			mysql_query($sql, $conn) or die("Error in Lead Table Query ".mysql_error());
		}
		$str = "U100";
	
  }else{
  	 $str = "Wrong Data Entry ";
  }
  if($str=="U100"){
  	$str = "Successfully Data Updated";
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
