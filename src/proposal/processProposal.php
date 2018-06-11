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
			updateProposal();
			break;
		case 'reject' :
			rejectProposal();
			break;
    	case 'editarea' :
        	if($_GET['action1']=='accept'){
				acceptProposal();
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
    Accept a Proposal
*/
function acceptProposal()
{
	global $conn;
	global $today;
	if($_POST['lead_id'] <> '' && isset($_POST['lead_id'])){
		$leadid=$_POST['lead_id'];
		$sql = "update tbl_lead_master set proposal_quote_status='1' where lead_id='$leadid'";
		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn) >0){
			$str= "Successfully Accepted";
		}
	}
	echo $str;
}

/*
    Reject a Proposal
*/
function rejectProposal()
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
		$sql = "update tbl_lead_master set reject_status ='1',remark='$remark', reject_date='$today' where lead_id='$leadid'";
		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn) >0){
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
		$pdesc = rtrim(mysqli_real_escape_string(strtoupper($_POST["prod_desc"])));
		$ldate = date("Y-m-d",strtotime($_POST["lead_date"]));
		$refby = $_POST["ref_by"];
		$custreq = $_POST["cust_require"];
		$fdate = date("Y-m-d",strtotime($_POST["follow_up_date"]));
		$remark = $_POST["remark"];
		$today = date("Y-m-d",strtotime($today));
		$cust_sql="insert into tbl_customer (customer_id,customer_name, customer_address, customer_city, customer_country, customer_mobno, customer_cperson, customer_email,customer_web, cdate, customer_dstatus) values ('','$cname','$cadd','$ccity','$ccountry','$cmobno','$cperson','$cemail','$cweb','$today','false')";
//		echo $cust_sql;
		mysqli_query($conn,$cust_sql) or die("Error in Customer Table :".mysqli_error($conn));
		
		if(mysqli_affected_rows($conn) >0){
			$customer_sql = "select * from tbl_customer order by customer_id desc";
			$customer_result = mysqli_query($conn,$customer_sql);
			$customer_row = mysqli_fetch_assoc($customer_result);
			$ccode = $customer_row["customer_id"];					
		   	$sql = "insert into tbl_lead_master(cust_code, ref_by, lead_book_date, lead_book_status, lead_by, cust_require, follow_up_date, remark) 
										values  ('$ccode','$refby','$ldate','0','$userid','$custreq','$fdate','$remark')";
		
			mysqli_query($conn,$sql) or die("Error in Lead Table Query ".mysqli_error($conn));
			if(mysqli_affected_rows($conn) > 0){
				$ssql = "select * from tbl_lead_master order by lead_id desc";
				$result = mysqli_query($conn,$ssql);
				$row = mysqli_fetch_assoc($result);
				$leadid = $row["lead_id"];
				$sql1 = "insert into tbl_lead_child (lead_id) values ('$leadid') ";
				mysqli_query($conn,$sql1) or die("Error in Lead Table :".mysqli_error($conn));
				if(mysqli_affected_rows($conn)>0){
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
			$resultset = mysqli_query($conn,$strSQL) or die("Error in Deleted ".mysqli_error($conn));
			if(mysqli_affected_rows($conn) > 0){
				$str1 = "Deleted Successfully";
			}else{
				$str1 = "Unable to delete";
			}
		}//if  
	}//for  
  	echo $str;
}

function updateProposal()
{
 global $conn;
 global $conn;
 global $_POST;
 global $today;
 $userid= $_SESSION['User_ID'];
 $username=$_SESSION['User_Name'];
 $str="";
 //echo "Customer ID :".$_POST['cust_id']." = Lead ID :".$_POST['lead_id'];
 if(isset($_POST['cust_id']) && $_POST['cust_id']<>'' && isset($_POST['lead_id']) && $_POST['lead_id']<>''){ 
 		$custid =$_POST['cust_id'];	
 		$cname = mysqli_real_escape_string(strtoupper(str_replace("-","_", $_POST['cust_name'])));
		//$cadd = rtrim(mysqli_real_escape_string(strtoupper($_POST['cust_add'])));
		//$ccity = strtoupper(str_replace("-","_",$_POST['cust_city']));
		//$ccountry = strtoupper(str_replace("-","_",$_POST['cust_country']));
		$cperson = strtoupper(str_replace("-","_",$_POST['cust_cperson']));
		$cmobno = $_POST['cust_mobno'];
		$cemail = $_POST['cust_email'];
		//$cweb = $_POST['cust_web'];
		$pdesc = rtrim(mysqli_real_escape_string(strtoupper($_POST["prod_desc"])));
		$ldate = date("Y-m-d",strtotime($_POST["lead_date"]));
		//$refby = $_POST["ref_by"];
		//$custreq = $_POST["cust_require"];
		$fdate = date("Y-m-d",strtotime($_POST["follow_up_date"]));
		//$remark = rtrim(mysqli_real_escape_string($_POST["remark"]));
		//$today = date("Y-m-d",strtotime($today));
		$qstatus = $_POST["quote_status_code"];
		$pno = $_POST["proposal_quote_no"];
		$pdremark = $_POST['proposal_demo_remark'];
		$pddate = date("Y-m-d",strtotime($_POST['proposal_demo_date']));
		$pqdate = date("Y-m-d",strtotime($_POST['proposal_quote_date']));
		$pfupdate = date("Y-m-d",strtotime($_POST['proposal_followup_date']));
		$pqremark = $_POST['proposal_quote_remark'];
		$totamt = $_POST['txttotalamt'];
		$disamt = $_POST['txtdiscount'];
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
		
		$cust_sql="update tbl_customer set customer_name='$cname', customer_mobno='$cmobno', customer_cperson='$cperson', customer_email='$cemail'
					 where customer_id='$custid'";
//		echo $cust_sql;
		mysqli_query($conn,$cust_sql) or die("Error in Customer Table :".mysqli_error($conn));
		
		//if(mysqli_affected_rows() >0){
			
		$leadid= $_POST["lead_id"];
		
			//if(mysqli_affected_rows() > 0){
		//if(dataFound($ccode)){
			if(dataFound()){
				$deleteqry = "delete from tbl_lead_child where lead_id='$leadid' and proposal_quote_no='$pno'";
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
					if(($pid != "" and $pid != "0") and ($cid != "0" and $cid !="")){
						$rvno = substr($pno,strlen($pno)-3,3);
						$sql1 .= "('$leadid','$cid','$pid','$pqty','$pprice','$pamount','$ptax','$pdiscount','$pno','$rvno'),";
						$totamt = $totamt + $pamount;
					}
				}
				$sqlRes = substr($sql1,0,strlen($sql1)-1);
				//echo "SQL 1".$sqlRes."<br>";
				$sql_query = "insert into tbl_lead_child (lead_id, cat_code, prod_code, prod_qty, prod_price, prod_amount, prod_tax, prod_discount, 
								proposal_quote_no, proposal_revision_no) values ".$sqlRes;
				mysqli_query($conn,$sql_query) or die("Error in Lead Table :".mysqli_error($conn));
			}
			$totamt = $totamt - $disamt;
			$sql = "update tbl_lead_master set follow_up_date='$fdate', proposal_quote_no = '$pno', proposal_demo_date='$pddate', proposal_demo_remark='$pdremark',
							proposal_quote_date='$pqdate', proposal_quote_remark='$pqremark',proposal_followup_date='$pfupdate',quote_status_code='$qstatus', 
							total_amt = '$totamt',discount_amt='$disamt',remark = '$remark',balance_amt = '$totamt' where lead_id='$leadid'";
			mysqli_query($conn,$sql) or die("Error in Lead Table Query ".mysqli_error($conn));
		/*}else{
			if(dataFound()){
				$sqlqry = "select * from tbl_temp_product";
				$sqlrs = mysqli_query($sqlqry,$conn);
				while($sqlrow = mysqli_fetch_assoc($sqlrs)){
					$pid=$sqlrow["prod_code"];
					$cid = $sqlrow["cat_code"];
					$pqty = $sqlrow["prod_qty"];
					$updateqry = "update tbl_lead_child prod_qty='$pqty' where prod_code='$pid' and cat_code='$cid' and lead_id='ccode'";
					mysqli_query($updateqry,$conn) or die("error in lead update :".mysqli_error($conn));
				}
			}
		}*/
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
