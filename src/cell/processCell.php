<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once "../config.php";
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';


	switch ($action) {
		
		case 'add' :
			addCell();
			break;
		  
		case 'modify' :
			modifyCell();
			break;
		case 'update' :
			updateCell();
			break;
		case 'delete' :
			deleteCell();
			break;
		
	  
		   
		default :
              print "<script>window.location.href = '../CRM.php';</script>";
	}

}else{
	print "<script>window.location.href='../index.html';</script>";	
}




/*
    Add a Cell
*/
function addCell()
{
 global $conn;
  global $_POST;
 
   
  if(isset($_POST["cell_id"]) && $_POST["cell_id"]<>''){ 
  	 $cellid = $_POST["cell_id"];
  	 $sql_query = "select * from tbl_cell where cell_id = '$cellid'";
	 $sql_result = mysqli_query($conn,$sql_query);
	 if(mysqli_num_rows($sql_result)>0){
	 	$str = "Data Already Exists";
	 }else{
     	$sql = "insert into `tbl_cell` (`cell_id`, `loc_name`, `city`, `region`) values (" .strtoupper(sqlvalue(@$_POST["cell_id"], true)).",
	 		 " .strtoupper(sqlvalue(@$_POST["loc_name"], true)).", " .strtoupper(sqlvalue(@$_POST["city"], true)).",
			 " .strtoupper(sqlvalue(@$_POST["region"], true)).")";
     	mysqli_query($conn,$sql) or die(mysqli_error($conn));
	 	if(mysqli_affected_rows($conn)>0){
	 		$str = "Added Successful";
	 	}
	}
  }else{
  	$str = "Must be enter cell_id";
  }
  echo $str;
}

function deleteCell()
{
	global $conn;
	global $_POST;
   
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			$userid=$_POST["userbox"][$i];
			if(primaryreturn($userid,9) == true){
				$str .= "Primary Return,";
			}
			if(primaryOS($userid,9) == true){
				$str .= "Primary Opening Stock,";
			}
				
			if(secondaryOS($userid,9) == true){
				$str .= "Secondary Opening Stock,";
			}
				
			if(primaryOB($userid,9) == true){
				$str .= "Primary Bookings,";
			}
				
			if(secondaryOB($userid,9) == true){
				$str .= "Secondary Bookings,";
			}
					
			if(Logs($userid,9) == true){
				$str .= "Logout,";
			}
			
			if(tracklogs($userid,9) == true){
				$str .="Track Logs,";
			}
			if($str==""){
				$strSQL = "DELETE FROM tbl_cell WHERE cell_id = '$userid'";
				$resultset = mysqli_query($conn,$strSQL) or die("Error in CellInfo :".mysqli_error($conn));
				if(mysqli_affected_rows($conn)>0){
					$str1 = "Deleted Succesfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
			
		}//if  
	}//for
	//echo $str;  
}

function updateCell()
{
 global $conn;
 global $_POST;
 
  if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0) {
        $catId = (int)$_POST['cat_id'];
		$cellid = $_POST["cell_id"];
  		$sql_query = "select * from tbl_cell where cell_id = '$cellid'";
	 	$sql_result = mysqli_query($conn,$sql_query);
	 	if(mysqli_num_rows($sql_result)>0){
	 		$str = "Cell ID already exists";
	 	}else{
  			$sql = "update tbl_cell set cell_id = " .strtoupper(sqlvalue(@$_POST["cell_id"], true)).",
					loc_name =  " .strtoupper(sqlvalue(@$_POST["loc_name"], true))." ,
			 		city =  " .strtoupper(sqlvalue(@$_POST["city"], true)).", region =  " .strtoupper(sqlvalue(@$_POST["region"], true))."
  					WHERE id = $catId";

  			mysqli_query($conn,$sql);
			if(mysqli_affected_rows($conn) >0){
				$str = "Updated Successful";
			}
		}
  } else {
        $str = "Must be enter cell_id";
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
