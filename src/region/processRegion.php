<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    case 'add' :
        	addRegion();
        	break;
        case 'modify' :
        	modifyRegion();
        	break;
    	case 'update' :
			updateRegion();
			break;
    	case 'delete' :
        	deleteRegion();
        	break;
        default :
        	print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a Region
*/
function addRegion()
{
 global $conn;
  global $_POST;
 
   
  if(isset($_POST["region_name"]) && $_POST["region_name"]<>''){ 
  	 $rname = strtoupper($_POST["region_name"]);
  	 $sql_query = "select * from tbl_region where region_name  = '$rname'";
	 $sql_result = mysql_query($sql_query,$conn);
	 if(mysql_num_rows($sql_result) > 0){
	 	$str = "Data Already Exists";
	 }else{
 	 	$sql = "insert into `tbl_region` (`region_name`, `region_desc`) values (" .strtoupper(sqlvalue(@$_POST["region_name"], true)).",
	 		 	" .strtoupper(sqlvalue(@$_POST["region_desc"], true)).")";
     	//echo $sql;
	 	mysql_query($sql, $conn) or die(mysql_error()); 
     	$str = "Record Added Successfully"; 
	}
  }else{
  	 $str = "Wrong Data Entry";
  }
  echo $str;
 	
}

function deleteRegion()
{
global $conn;
global $_POST;
    for($i=0;$i<count($_POST["userbox"]);$i++){  
				$str="";
		if($_POST["userbox"][$i] != ""){  
		
			$userid = $_POST["userbox"][$i];
						
			if(assignTP($userid,4) == true){
				$str .= "Assign TP,";
			}
					
			if(assignroute($userid,4) == true){
				$str .= "Assign Route,";
			}
			
			if(outlet($userid,4)==true){
				$str .= "Outlet,";
			}
			
			if(distributor($userid,4)==true){
				$str .= "Distributor,";
			}
			
			if(route($userid,4)==true){
				$str .= "Route Plan,";
			}
			
			if(area($userid,4)==true){
				$str .= "Area Plan,";
			}
			
			if($str == ""){
				//Region
				$strSQL = "DELETE FROM tbl_region WHERE region_id = '".$_POST["userbox"][$i]."'";
				$result = mysql_query($strSQL) or die(mysql_error());
				if(mysql_affected_rows()>0){
					$str1 =  "Deleted Successfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
			
		}//if
	}//for  
	echo $str;
}

function updateRegion()
{
 global $conn;
 global $_POST;
 
  if (isset($_POST['region_id']) && (int)$_POST['region_id'] > 0) {
        $regionId = (int)$_POST['region_id'];
  
 		 $sql = "update tbl_region set region_name = " .strtoupper(sqlvalue(@$_POST["region_name"], true)).", 
		 		region_desc =  " .strtoupper(sqlvalue(@$_POST["region_desc"], true))." WHERE region_id = $regionId";
  //echo $sql;
  		mysql_query($sql,$conn);
		if(mysql_affected_rows()>0){
			$str = "Update Successfully";
		}else{
			$str = "Wrong Data Entry";
		}
  } else {
       $str = "Wrong Data Entry ";
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
