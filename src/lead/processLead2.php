<?php
session_start();
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";

	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   	case 'add' :
        	addLead();
        	break;
    	case 'modify' :
        	modifyLead();
        	break;
    	case 'update' :
			updateLead();
			break;
		case 'reject' :
			rejectLead();
			break;
    	case 'editarea' :
        	if($_GET['action1']=='accept'){
				acceptLead();
			}
        	break;
		case 'delete' :
			deleteLead();
			break;
		default :
       		print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}



/*
    Accept a Lead
*/
function acceptLead()
{
	global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$sql = "update tbl_lead_master set lead_book_status='1',lead_book_date='$today' where lead_id='$leadid'";
		mysql_query($sql,$conn);
		if(mysql_affected_rows() >0){
			$str= "Successfully Accepted";
		}
	}
	echo $str;
}

/*
    Reject a Lead
*/
function rejectLead()
{
	global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$data = $_POST['remarkdata'];
		$remark = $_POST["remark"];
		if($remark != "" or strlen($remark)>1){
			$remark = date('Y-m-d',strtotime($today)).'*'.date('H:i:s',strtotime($today)).'*'.$user_name.'*'.$remark;
			if($data != ""){
				$remark = $data.';'.$remark;
			}
		}else{
			$remark="";
			if($data != ""){
				$remark = $data;
			}
		}
		$sql = "update tbl_lead_master set reject_status ='1',remark='$remark',reject_date='$today' where lead_id='$leadid'";
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
 $username=$_SESSION['User_Name'];
  if(isset($_POST['cust_name']) && $_POST['cust_name']<>''){ 
  		
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
		if($refby == "Others"){
			$refby = $_POST["ref_other_by"];
		}
		$custreq = $_POST["cust_require"];
		$fdate = date("Y-m-d",strtotime($_POST["follow_up_date"]));
		
		$cust_status=false;
		
		$data = $_POST['remarkdata'];
		$remark = $_POST["remark"];
		if($remark != "" or strlen($remark)>1){
			$remark = date('Y-m-d',strtotime($today)).'*'.date('H:i:s',strtotime($today)).'*'.$user_name.'*'.$remark;
			if($data != ""){
				$remark = $data.';'.$remark;
			}
		}else{
			$remark="";
			if($data != ""){
				$remark = $data;
			}
		}
		$today = date("Y-m-d",strtotime($today));
		//echo "Status :".$_POST['recstatus']."<br>";
		if(isset($_POST['recstatus']) and $_POST['recstatus'] != ''){
			if($_POST['recstatus'] == 'new'){
				$cname = strtoupper(str_replace("-","_",$_POST['cust_name']));
				$cust_status=true;
			}else{
				$ccode=$_POST['cust_name'];
			}
		}else{
			$cname = strtoupper(str_replace("-","_",$_POST['cust_name']));
			$cust_status=true;
		}
		
//		echo $cust_sql;
		if($cust_status==true){
			$cust_sql="insert into tbl_customer (customer_id,customer_name, customer_address, customer_city, customer_country, customer_mobno, 
												customer_cperson, customer_email,customer_web, cdate, customer_dstatus) values 
												('','$cname','$cadd','$ccity','$ccountry','$cmobno','$cperson','$cemail','$cweb','$today','false')";

			mysql_query($cust_sql,$conn) or die("Error in Customer Table :".mysql_error());
		
			if(mysql_affected_rows() >0){
				$customer_sql = "select * from tbl_customer order by customer_id desc";
				$customer_result = mysql_query($customer_sql,$conn);
				$customer_row = mysql_fetch_assoc($customer_result);
				$ccode = $customer_row["customer_id"];	
			}
		}				
		$sql = "insert into tbl_lead_master(cust_code, prod_desc, ref_by, lead_book_date, lead_book_status, lead_by, cust_require, follow_up_date, remark,user_id) 
										values  ('$ccode','$pdesc','$refby','$ldate','0','$userid','$custreq','$fdate','$remark','$user_id')";
		
		mysql_query($sql, $conn) or die("Error in Lead Table Query ".mysql_error());
			/*if(mysql_affected_rows() > 0){
				$ssql = "select * from tbl_lead_master order by lead_id desc";
				$result = mysql_query($ssql,$conn);
				$row = mysql_fetch_assoc($result);
				$leadid = $row["lead_id"];
				$sql1 = "insert into tbl_lead_child (lead_id) values ('$leadid') ";
				mysql_query($sql1,$conn) or die("Error in Lead Table :".mysql_error());
				if(mysql_affected_rows()>0){
					$str = "Record Added Successfully";
				}else{
					$str = "New Record Added Failed";
				}
			}else{
				$str = "New Record Added Failed";
			}*/
			if(mysql_affected_rows() > 0){
				$str = "Record Added Successfully";
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

function updateLead()
{
 global $conn;
 global $conn;
 global $_POST;
 global $today;
 $userid= $_SESSION['User_ID'];
 $str="";
 //echo "Customer ID :".$_POST['cust_id']." = Lead ID :".$_POST['lead_id'];
 if(isset($_POST['cust_id']) && $_POST['cust_id']<>'' && isset($_POST['lead_id']) && $_POST['lead_id']<>''){ 
 		$custid =$_POST['cust_id'];
 		$cname = mysql_real_escape_string(strtoupper(str_replace("-","_",$_POST['cust_name'])));
		$cadd = rtrim(mysql_real_escape_string(strtoupper($_POST['cust_add'])));
		$ccity = strtoupper(str_replace("-","_",$_POST['cust_city']));
		$ccountry = strtoupper(str_replace("-","_",$_POST['cust_country']));
		$cperson = strtoupper(str_replace("-","_",$_POST['cust_cperson']));
		$cmobno = $_POST['cust_mobno'];
		$cemail = $_POST['cust_email'];
		$cweb = $_POST['cust_web'];
		$pdesc = rtrim(mysql_real_escape_string(strtoupper($_POST["prod_desc"])));
		$ldate = date("Y-m-d",strtotime($_POST["lead_date"]));
		//$refby = $_POST["ref_by"];
		$refby = $_POST["ref_by"];
		if($refby == "Others"){
			$refby = $_POST["ref_other_by"];
		}
		$custreq = $_POST["cust_require"];
		$fdate = date("Y-m-d",strtotime($_POST["follow_up_date"]));
		//$remark = rtrim(mysql_real_escape_string($_POST["remark"]));
		$data = $_POST['remarkdata'];
		$remark = $_POST["remark"];
		if($remark != "" or strlen($remark)>1){
			$remark = date('Y-m-d',strtotime($today)).'*'.date('H:i:s',strtotime($today)).'*'.$user_name.'*'.$remark;
			if($data != ""){
				$remark = $data.';'.$remark;
			}
		}else{
			$remark="";
			if($data != ""){
				$remark = $data;
			}
		}
		$today = date("Y-m-d",strtotime($today));
		$cust_sql="update tbl_customer set customer_name='$cname', customer_address='$cadd', customer_city='$ccity', customer_country='$ccountry', 
					customer_mobno='$cmobno', customer_cperson='$cperson', customer_email='$cemail',customer_web='$cweb', cdate='$today' where customer_id='$custid'";
//		echo $cust_sql;
		mysql_query($cust_sql,$conn) or die("Error in Customer Table :".mysql_error());
		
		//if(mysql_affected_rows() >0){
			
		$ccode = $_POST["lead_id"];
		$sql = "update tbl_lead_master set ref_by='$refby', prod_desc='$pdesc', lead_book_date='$ldate', lead_by='$userid', cust_require='$custreq', 
							follow_up_date='$fdate', remark= '$remark',user_id='$userid' where lead_id='$ccode'";
		mysql_query($sql, $conn) or die("Error in Lead Table Query ".mysql_error());
			//if(mysql_affected_rows() > 0){
		
				
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
