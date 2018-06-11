<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$rstatus = isset($_POST['rstatus']) ? $_POST['rstatus'] : '';
	
	switch ($action) {
		case 'add' :
			addUser();
			break;
		case 'delete':
			deleteUser();
			break;
		case 'update':
			updateUser();
			break;
		default:
			 print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}



/*
    Add a user
*/
function addUser()
{
 global $conn;
 global $_POST;
 global $_SESSION;
 
   
  if(isset($_POST["imei_no"]) && $_POST["imei_no"]<>''){
  	$uname = $_POST["user_name"];
	$imeino = $_POST["imei_no"];
	$empid = $_POST["emp_name"];
	$designid = $_POST["design_name"];
	
	//echo "Uname :".$uname."=Imeino :".$imeino."=Emp_name :".$empid."=Design_name :".$designid."<br>";
  	//$empid = $_POST["emp_id"];
	$levelname = getLevelName($empid); 
	// echo "Level Name :".$levelname."<br>";
  	$query = "SELECT a.* FROM tbl_user a,tbl_employee b,tbl_design c WHERE a.emp_id =b.emp_id and b.emp_dest = c.id and c.levels='$levelname'"; 
	//echo "SQL :".$query."<br>";
	//echo "SESSION :".$_SESSION[$levelname]."<br>";
	$resultset = mysql_query($query,$conn) or die ("Error in LEVELSQL :". mysql_error());
	if(mysql_num_rows($resultset)>=$_SESSION[$levelname]){
	 	$str = "Your License for this Level is :".$_SESSION[$levelname];
	}else{ 
		$uname = $_POST["user_name"];
		$imeino = $_POST["imei_no"];
		$title = $_POST["emp_name"];
		
	  
		$sql1 = "select * from tbl_user where emp_id = '$title' and status = '0'";
		//echo "SQL :".$sql1."<br>";
		$rw = mysql_query($sql1,$conn) or die("Error in ADD SQL :".mysql_error());
		if(mysql_num_rows($rw) > 0){
			$str = "Data already exists";
		}else{	
			$sqlQuery = "select * from tbl_employee where emp_id='$empid'";
			$resData = mysql_query($sqlQuery,$conn);
			$row = mysql_fetch_assoc($resData);
			$uname = $row["emp_dname"];
			$sql = "insert into `tbl_user` (`user_name`, `pass_word`, `imei_no`, `emp_id`,designid,`status`, `dstatus`) 
									values (" .sqlvalue(@$_POST["user_name"], true)."," .sqlvalue(@$_POST["pass_word"], true).",
											" .sqlvalue(@$_POST["imei_no"], true).", " .sqlvalue(@$_POST["emp_name"], true).",
											 " .sqlvalue(@$_POST["design_name"], true).",'0','0')";
			mysql_query($sql, $conn) or die(mysql_error());
			if(mysql_affected_rows()>0){
				$str = "Added Successfully";
			}
		}
	}
  }else{
  	$str = "Wrong Data Entry";
  }
  
 
  echo $str;

}


function getLevelName($dname){
	global $conn;
	$dquery = "SELECT * FROM (SELECT b.levels FROM tbl_employee a, tbl_design b WHERE a.emp_dest = b.id AND a.emp_id = '$dname' GROUP BY b.levels)SUBQ";
	//echo "Query :".$dquery ."<br>";
	$dresult = mysql_query($dquery,$conn) or die("Error in LevelName SQL:".mysql_error());
	$drow = mysql_fetch_assoc($dresult);
	return $drow["levels"];
}


function deleteUser()
{
	global $conn;
	global $_POST;
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		$str="";
		if($_POST["userbox"][$i] != ""){  
			$userid = $_POST["userbox"][$i];
			if(cellinfo($userid) == true){
				$str .= "CellInfo,";
			}
			if(tracklogs($userid,1) == true){
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
					
			if(Logs($userid,1) == true){
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
					
		
			if($str==""){					
				$strSQL = "DELETE FROM tbl_user WHERE id = '".$_POST["userbox"][$i]."'";
				$resultset = mysql_query($strSQL) or die(mysql_error());
				$str1 = "Deleted Successfully,";
			}else{
				$str1="Unable to delete."+'\n'+"Dependent data found in ".$str;
			}	
			$str = substr($str1,0,strlen($str1)-1);
			
		}//if
	}//for  
	echo $str;
}

function updateUser()
{
 global $conn;
 global $_POST;
 global $rstatus;
 
  if (isset($_POST['user_id']) && (int)$_POST['user_id'] > 0) {
      $userId = (int)$_POST['user_id'];
  	  $uname = $_POST["user_name"];
	  $imeino = $_POST["imei_no"];
	  $empid = $_POST["emp_name"];
	  $designid = $_POST["design_name"];
	  $oldempid = $_POST["old_emp_name"];
	  $oldimeino = $_POST["old_imei_no"];
	  $status = $_POST["status"];
	  $oldstatus = $_POST["old_status"];
	  //$empid = $_POST["emp_id"];
	  //$imeino = $_POST["imei_no"];
	  //$sql1 = "select * from tbl_user where user_name = '$uname' and imei_no = '$imeino'";
	  //$rw = mysql_query($sql1);
	  //if(mysql_num_rows($rw) > 1){
		//	$str = "Data Already Exists";
	  //}else{	
	 
	  /*if($oldempid != $empid and $oldstatus==$status){
	  	 $qry = " and emp_id = '$empid' and status = '$status'";
	  }else if($oldimeino != $imeino and $oldstatus==$status){
	  	$qry = " and imei_no = '$imeino' and status = '$status'";
	  }else if($oldempid != $empid and $oldimeino != $imeino and $oldstatus==$status){
	  	$qry = " and (emp_id = '$empid' or imei_no = '$imeino') and status = '$status'";
	  }else if($oldempid!=$empid and $oldstatus != status){
	  	$qry = " and emp_id = '$empid' and status = '$status'";
	  }	else if($oldimeino != $imeino and $oldstatus!=$status){
	    $qry = " and imei_no = '$imeino' and status = '$status'";
	  }else if($oldempid != $empid and $oldimeino != $imeino and $oldstatus!=$status){
	  	$qry = " and (emp_id = '$empid' or imei_no = '$imeino') and status = '$status'";
	  }else if($oldstatus!=$status){
	  	$qry = " and emp_id = '$empid' and status = '$status'";
	  }*/
	  
	  if($oldempid != $empid and $oldstatus == $status){
	  	 $qry = " and emp_id = '$empid' and status = '$status'";
	  }else if($oldimeino != $imeino and $oldstatus == $status){
	  	$qry = " and imei_no = '$imeino' and status = '$status'";
	  }else if($oldempid != $empid and $oldimeino != $imeino and $oldstatus == $status){
	  	$qry = " and (emp_id = '$empid' or imei_no = '$imeino') and status = '$status'";
	  }else if ($oldempid == $empid and $oldimeino == $imeino and $oldstatus != $status){
	  	$qry = " and (emp_id = '$empid' or imei_no = '$imeino') and status='$status'";
	  }else if ($oldempid != $empid and $oldimeino != $imeino and $oldstatus != $status){
	  	$qry = " and (emp_id = '$empid' or imei_no = '$imeino') and status='$status'";
	  }else if ($oldempid == $empid and $oldstatus != $status){
	  	$qry = " and emp_id = '$empid' and status='$status'";
	  } else if ($oldimeino == $imeino and $oldstatus != $status){
	  	$qry = " and imei_no = '$imeino' and status = '$status'";
	  }
	  if(($oldempid == $empid) and ($oldimeino == $imeino) and ($oldstatus == $status)){
	  	//echo "Condition Failed"."<br>";
	  	$sql = "update tbl_user set user_name = " .sqlvalue(@$_POST["user_name"], true).",  pass_word =  " .sqlvalue(@$_POST["pass_word"], true).",  
			 		 emp_id = '$empid',designid='$designid',imei_no = '$imeino', status =  " .sqlvalue(@$_POST["status"], true).
					" WHERE user_id = $userId";
			
		mysql_query($sql,$conn);
	  	
		if(mysql_affected_rows()>0){
			$str = "Updated Successfully";
		}else{
			$str = "No Data Affected";
		}
	  	
	  }else{
  		//echo "Condition success"."<br>";
		/*if($cstatus=="1"){
	  		$sql1 = "select * from tbl_user where emp_id = '$empid' and status = '0'";
		}else if($cstatus=="2"){
			$sql1 = "select * from tbl_user where imei_no = '$imeino' and status='0'";
		}else{
			$sql1 = "select * from tbl_user where status='0' and (emp_id = '$empid' or imei_no = '$imeino')";
		}*/
		$sql1 = "select * from tbl_user where 1".$qry;
		//echo $sql1."<br>";
	  	$result1= mysql_query($sql1,$conn) or die("Error in UPDATE SQL :".mysql_error());
	  	if(mysql_num_rows($result1)>0){
	  		$str = "Data already exists";
	  	}else{
	  		$sql = "update tbl_user set user_name = " .sqlvalue(@$_POST["user_name"], true).",  pass_word =  " .sqlvalue(@$_POST["pass_word"], true).",  
			 		 emp_id = '$empid',designid='$designid',imei_no = '$imeino',status =  " .sqlvalue(@$_POST["status"], true).
					" WHERE user_id = $userId";
			
			mysql_query($sql,$conn);
	  	
			if(mysql_affected_rows()>0){
				$str = "Updated Successfully";
			}else{
				$str = "No Data Affected";
			}
	 	} 	
	 }
  }else{
  	  echo "User not found";
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
