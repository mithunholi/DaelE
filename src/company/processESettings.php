<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
		case 'add' :
			addESetting();
			break;
	    case 'update' :
			updateESetting();
			break;
    	default :
		    print "<script>window.location.href = 'company/ginfo.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}


function addESetting(){
	global $conn;
 	global $_POST;
 	global $today;
	if (isset($_POST['host_name']) && $_POST['host_name'] != '') {
		$hostname = $_POST['host_name'];
		$portno = $_POST['port_no'];
		$username = $_POST['user_name'];
		$password = $_POST['user_password'];
		$frommail = $_POST['from_mail'];
		$fromname = $_POST['from_name'];
		$sql = "insert into tbl_mail_setting (host_name,port_no,user_name,user_password,from_mail,from_name) 
				values ('$hostname','$portno','$username','$password','$frommail','$fromname')";
		$res = mysqli_query($conn,$sql) or die("Error in Add Record :".mysqli_error($conn));
		if(mysqli_affected_rows($conn)>0){
			$str = "Successfully Added";
		}else{
			$str = "No Data Affected";
		}
	}else{
		$str = "Wrong Data Entry";
	}
	echo $str;
}

function updateESetting()
{
	global $conn;
 	global $_POST;
 	global $today;
 
  	if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
        $Id = (int)$_POST['id'];
    	$hostname = $_POST['host_name'];
		$portno = $_POST['port_no'];
		$username = $_POST['user_name'];
		$password = $_POST['user_password'];
		
		$frommail = $_POST['from_mail'];
		$fromname = $_POST['from_name'];
  		$sql = "update tbl_mail_setting set host_name='$hostname', port_no='$portno', user_name='$username',
				user_password='$password',from_mail='$frommail',from_name='$fromname' WHERE id = '$Id' ";
       //echo "SQL Query == ".$sql."<br>";
  		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn)>0){
			$str = "Updated Successfully";
		}else{
			$str = "No Data Affected";
		}
  	}else {
     	$str = "Wrond Data Entry";
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
