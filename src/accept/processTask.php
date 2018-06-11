<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	   
    	case 'update' :
			updateTask();
			break;
    	case 'editarea' :
        	if($_GET['action1']=='reject'){
			
				rejectTask();
			}
        	break;
		case 'delete' :
			deleteTask();
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
function rejectTask()
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

function updateTask()
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
		$taskid = $_POST['cmbtaskname'];
		$tasksummary = trim(nl2br($_POST['txttasksummary']));
		$taskstatus = $_POST['taskstatus'];
		$taskdate = date("Y-m-d",strtotime($today));
		
		if($taskstatus == "new" and $taskid != "0" and $taskid != ""){
			$tm_qry = "insert into tbl_task (task_code) values ('$taskid')";
			mysql_query($tm_qry,$conn) or die("Error in Task Master ");
		}
		
		if($rstatus == "editrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_task 
						set lead_id='$leadid',quot_id='$quoteid',task_code='$taskid',task_summary='$tasksummary',record_status='editrecord',task_date='$taskdate' 
						where id='$rid'";
		}elseif($rstatus == "confirmrecord"){
				$rid=$_POST['recordid'];
				$query = "update tbl_lead_task 
							set lead_id='$leadid',quot_id='$quoteid',task_code='$taskid',task_summary='$tasksummary',
							record_status='confirmrecord',task_date='$taskdate' 
							where id='$rid'";
				if($task_name="COMPLETED"){
					$query1 = "update tbl_lead_master set task_status='DONE' where lead_id='$leadid'";
					mysql_query($query1,$conn);
				}
		}elseif($rstatus=="deleterecord"){
				$rid=$_POST['recordid'];
				$query = "delete from tbl_lead_task where id='$rid'";
		}else{
				$query = "insert into tbl_lead_task values ('','$leadid','$quoteid','$taskid','$tasksummary','newrecord','$taskdate')";
		}
		mysql_query($query,$conn);
		if(mysql_affected_rows()>0){
			$str = "U100";
		}
		
		
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
