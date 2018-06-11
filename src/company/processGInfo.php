<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
		case 'add' :
			addTemplate();
			break;
	    case 'update' :
			updateTemplate();
			break;
    	default :
		    print "<script>window.location.href = 'company/ginfo.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}


function addTemplate(){
	global $conn;
 	global $_POST;
 	global $today;
	if (isset($_POST['info_name']) && $_POST['info_name'] != '') {
		$infoname = $_POST['info_name'];
		$infodesc = $_POST['info_desc'];
		$sql = "insert into tbl_template (info_name,info_desc,info_date) values ('$infoname','$infodesc','$today')";
		$res = mysqli_query($conn,$sql) or die("Error in Add Record :".mysqli_error());
		if(mysqli_affected_rows()>0){
			$str = "Successfully Added";
		}else{
			$str = "No Data Affected";
		}
	}else{
		$str = "Wrong Data Entry";
	}
	echo $str;
}

function updateTemplate()
{
	global $conn;
 	global $_POST;
 	global $today;
 
  	if (isset($_POST['info_id']) && (int)$_POST['info_id'] > 0) {
        $infoId = (int)$_POST['info_id'];
    	$infoname = $_POST['info_name'];
		$infodesc = $_POST['info_desc'];
		
  		$sql = "update tbl_template set info_name = '$infoname', info_desc =  '$infodesc' WHERE info_id = '$infoId' ";
       //echo "SQL Query == ".$sql."<br>";
  		mysqli_query($conn,$sql);
		if(mysqli_affected_rows()>0){
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
