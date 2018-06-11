<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    
    	case 'update' :
			updateCompany();
			break;
    	
    	default :
		
        print "<script>window.location.href = 'company/company.php';</script>";
	}
}else{

	print "<script>window.location.href='../index.html';</script>";	
}



function updateCompany()
{
 global $conn;
 global $_POST;
 global $today;
 
  if (isset($_POST['comp_id']) && (int)$_POST['comp_id'] > 0) {
        $compId = (int)$_POST['comp_id'];
    
  		$cname = str_replace("-","_",$_POST["comp_name"]);
		$cname = strtoupper($cname);
		$cadd =  strtoupper($_POST["comp_add"]);
		$cperson  = strtoupper($_POST["comp_cperson"]);
		$ccity = strtoupper($_POST["comp_city"]);
		$czip = strtoupper($_POST["comp_zip"]);
		$cmobile = strtoupper($_POST["comp_mobile"]);
		$cweb = $_POST["comp_web"];
		$cemail = strtolower($_POST["comp_email"]);
  		$sql = "update tbl_master set comp_name = '$cname', comp_add =  '$cadd',
			comp_cperson = '$cperson',comp_city = '$ccity',
			comp_zip = '$czip',comp_mobile = '$cmobile', 
			comp_web = '$cweb', comp_email = '$cemail'  WHERE comp_id = '$compId' ";
       //echo "SQL Query == ".$sql."<br>";
  		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn)>0){
			$str = "Updated Successfully";
		}else{
			$str = "No Data Affected";
		}
  } else {
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
