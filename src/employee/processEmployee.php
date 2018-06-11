<?php
session_start();
require_once "../config.php";
require_once "../library/functions.php";
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){

?>
<?php

	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	
	   case 'add' :
			addEmployee();
			break;
		  
	   case 'modify' :
			modifyEmployee();
			break;
	   case 'update' :
			updateEmployee();
			break;
	   case 'delete' :
			deleteEmployee();
			break;
	   default :
			  print "<script>window.location.href = '../CRM.php';</script>";
	}
?>

<?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}


/*
    Add a Employee
*/
function addEmployee()
{
 global $conn;
 global $_POST;
 
 
 if(isset($_POST["emp_fname"]) && $_POST["emp_fname"]<>'' && isset($_POST["emp_code"]) && $_POST["emp_code"]<>''){
 	$dname = strtoupper($_POST["emp_dname"]);
	$ecode = strtoupper($_POST["emp_code"]);
	$sqldata = "select * from tbl_employee where emp_code='$ecode'";
	$resdata = mysqli_query($conn, $sqldata) or die ("Error in SQL1 :".mysqli_error());
	if(mysqli_num_rows($resdata)>0){
		$str = "Employee Code already exists";
	}else{
		$sql_query= "select * from tbl_employee order by emp_id desc";
		$res = mysqli_query($conn, $sql_query);
		
		$row = mysqli_fetch_assoc($res);
		$empid = $row["emp_id"];
		if($empid <> ""){
			$eid = substr($empid,3,strlen($empid));
			$eid2 = (int)$eid + 1;
			$eid1 = substr($empid,0,3);
			$empresid = $eid1.$eid2;
				//echo "EMP ID:".$empid;
		}else{
			$empresid = "EMP10001";
		}
			
		$dob = date("Y-m-d",strtotime($_POST["emp_dob"]));
		$doj = date("Y-m-d",strtotime($_POST["emp_doj"]));
		
		$sql = "insert into tbl_employee values ('$empresid', " .strtoupper(sqlvalue(@$_POST["emp_code"], true)).",
					 " .strtoupper(sqlvalue(@$_POST["emp_fname"], true)).", 
					" .strtoupper(sqlvalue(@$_POST["emp_dname"], true)).",
					" .strtoupper(sqlvalue(@$_POST["emp_gender"], true)).", '$dob', " .strtoupper(sqlvalue(@$_POST["emp_address"], true)).",
					'$doj', " .strtoupper(sqlvalue(@$_POST["emp_dest"], true)).", " .strtoupper(sqlvalue(@$_POST["emp_email"], true)).",
					" .sqlvalue(@$_POST["emp_mobno"], true).", " .sqlvalue(@$_POST["emp_telno"], true).",'0')";
		
		mysqli_query( $conn, $sql) or die(mysqli_error());
		if(mysqli_affected_rows($conn)>0){
			$str = "Record Added Successfully";
		}
	}
	
  }else{
  		$str = "Wrong Data Entry ";
  }
  echo $str;
}



function deleteEmployee()
{
global $conn;
global $_POST;

	for($i=0;$i<count($_POST["userbox"]);$i++){ 
		$str = "Unable to delete. Dependent data found in "; 
		$userid=$_POST["userbox"][$i];
		if($userid != ""){  
			
			$user_qry = "select * from tbl_user where emp_id = '$userid'";
			
	

			$user_result = mysqli_query($conn, $user_qry) or die("Error in user :".mysqli_error());

			if(mysqli_num_rows($user_result)>0){
				
				$user_row = mysqli_fetch_assoc($user_result);
				$userid= $user_row["emp_id"];
				if(cellinfo($userid) == true){
					$str .= "CellInfo,";
				}
				if(tracklogs($userid) == true){
					$str .= "Track Logs,";
				}
				
				if(primaryreturn($userid,1) == true){
					$str .= "Primary Return,";
				}
				
				if(primaryOS($userid,1) == true){
					$str .= "Primary Opening Stock,";
				}
				
				if(secondaryOS($userid,1) == true){
					$str .= "Secondary Opening Stock,";
				}
				
				if(primaryOB($userid,1) == true){
					$str .= "Primary Bookings,";
				}
				
				if(secondaryOB($userid,1) == true){
					$str .= "Secondary Bookings,";
				}
					
				if(Log($userid,1) == true){
					$str .= "Logout,";
				}
					
				if(assignTP($userid,1) == true){
					$str .= "Assign TP,";
				}
					
				if(assignroute($userid,1) == true){
					$str .= "Assign Route,";
				}
					
				if(outlet($userid,1) == true){
					$str .= "Outlet,";
				}
			
				if(shortmsg($userid,1) == true){
					$str .= "Short Msg,";
				}
					
				$str .= "User,";
			}else{					
				$strSQL = "DELETE FROM tbl_employee WHERE emp_id = '".$_POST["userbox"][$i]."'";
				$resultset = mysqli_query($conn, $strSQL) or die(mysqli_error());
				$str = "Deleted Successfully,";
			}
			$str = substr($str,0,strlen($str)-1);
			
		}//if  
	}//for  
	echo $str;
}
 

function updateEmployee()
{
 global $conn;
 global $_POST;
 $str="";
  $empId = $_POST["emp_id"];
  if(isset($_POST["emp_code"]) && $_POST["emp_code"]<>'' )
  { 
        $empCode = strtoupper($_POST['emp_code']);
  } else {
        $str = "Employee ID not found ";
  }
  
  if(isset($_POST["emp_fname"]) && $_POST["emp_fname"]<>''){
  	$empfname = strtoupper($_POST['emp_fname']);
  }else{
  	$str = "Employee Name Not Found ";
  }
  
  if(isset($_POST["emp_dname"]) && $_POST["emp_dname"]<>''){
  	$emplname = strtoupper($_POST['emp_dname']);
  }else{
  	$str = "Display Name Not Found ";
  }
  
  $address = trim($_POST["emp_address"]);
  $address = strtoupper($address);
  $dob = date("Y-m-d",strtotime($_POST["emp_dob"]));
  $doj = date("Y-m-d",strtotime($_POST["emp_doj"]));
		
  if($str == ""){		
   	$sql = "update tbl_employee set emp_code = '$empCode', emp_fname = '$empfname',
  			emp_dname =  '$emplname', 
  			emp_gender =  " .strtoupper(sqlvalue(@$_POST["emp_gender"], true)).", emp_dob =  '$dob', 
			emp_address =  '$address', emp_doj = '$doj', 
			emp_dest =  " .strtoupper(sqlvalue(@$_POST["emp_dest"], true)).",emp_email =  " .strtoupper(sqlvalue(@$_POST["emp_email"], true)).",
			emp_mobno =  " .sqlvalue(@$_POST["emp_mobno"], true).",	emp_telno =  " .sqlvalue(@$_POST["emp_telno"], true)." 
  			WHERE emp_id = '$empId'";
	//echo "SQL :".$sql."<br>";
   	mysqli_query($conn, $sql);
  	if(mysqli_affected_rows($conn) > 0){
  		$str = "Updated Successfully";
  	}else{
		$str = "No Data Affected";
	}
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
