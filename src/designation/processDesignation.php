<?php
session_start();
require_once "../config.php";
require_once "../library/functions.php";
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	
		case 'add' :
			addDesign();
			break;
		  
		case 'modify' :
			modifyDesign();
			break;
		case 'update' :
			updateDesign();
			break;
		case 'delete' :
			deleteDesign();
			break;
		
	  
		   
		default :
			// if action is not defined or unknown
			// move to main category page
			print "<script>window.location.href = '../CRM.php';</script>";
	}

}else{
	print "<script>window.location.href='../index.html';</script>";	
}


/*
    Add a Design
*/
function addDesign()
{
 global $conn;
  global $_POST;
 
   
  if(isset($_POST["design_name"]) && $_POST["design_name"]<>''){ 
     $sql = "insert into `tbl_design` (`design_name`, `design_desc`,levels) values (" .strtoupper(sqlvalue(@$_POST["design_name"], true)).", " .strtoupper(sqlvalue(@$_POST["design_desc"], true)).", " .strtoupper(sqlvalue(@$_POST["levels"], true)).")";
     mysqli_query($conn, $sql) or die(mysqli_error());
	 if(mysqli_affected_rows($conn)>0){
	 	$str = "Record Added Successfully";
	 }
  }else{
  		$str = "Wrong Data Entry ";
  }
  echo $str;
}

function deleteDesign()
{
	global $conn;
	global $_POST;

	for($i=0;$i<count($_POST["userbox"]);$i++){  
		$str="Unable to delete. Dependent data found in ";
		if($_POST["userbox"][$i] != ""){  
			$userid = $_POST["userbox"][$i];
			$emp_qry = "select * from tbl_employee where emp_dest = '$userid'";
			$emp_result = mysqli_query($conn, $emp_qry) or die("Error in Emp :".mysqli_error());
			if(mysqli_num_rows($emp_result)>0){
				
				$user_qry = "select * from tbl_user where designid = '".$_POST["userbox"][$i]."'";
				//echo "Query :".$user_qry."<br>";
				$user_result = mysqli_query($conn, $user_qry) or die("Error in user :".mysqli_error());
				if(mysqli_num_rows($user_result)>0){
					$user_row = mysqli_fetch_assoc($user_result);
					$userid= $user_row["user_id"];
					//$empid = $user_row["Title"];
					$designid = $user_row["designid"];
					if(cellinfo($userid) == true){
						$str .= "CellInfo,";
					}
					if(tracklogs($userid, $status) == true){
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
					if(distributorhier($userid) == true){
						$str .= "Distributor,";
					}
				}
				$str .= "Employee,";
			}else{					
				$strSQL = "DELETE FROM tbl_design WHERE id = '$userid'";
				$resultset = mysqli_query($conn, $strSQL) or die(mysqli_error());
				if(mysqli_affected_rows($conn)>0){
					$str = "Deleted Successfully,";
				}
			}
			$str = substr($str,0,strlen($str)-1);
			
		}//if	
	}//for loop  
	echo $str;
}  
 

function updateDesign()
{
 global $conn;
 global $_POST;

  if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0) {
        $catId = (int)$_POST['cat_id'];
  
  		$sql = "update tbl_design set design_name = " .strtoupper(sqlvalue(@$_POST["design_name"], true)).", levels = " .strtoupper(sqlvalue(@$_POST["levels"], true)).",design_desc =  " .strtoupper(sqlvalue(@$_POST["design_desc"], true))." 
  			WHERE id = $catId";
  		mysqli_query($conn, $sql);
  		if(mysqli_affected_rows($conn) > 0){
  			$str = "Updated Successfully";
  		}
  } else {
        $str = "Designation ID not found ";
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
