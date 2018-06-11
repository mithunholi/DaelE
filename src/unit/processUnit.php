<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
require_once '../config.php';
require_once "../library/functions.php";

	$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
	
    case 'add' :
        addUnit();
        break;
      
    case 'modify' :
        modifyUnit();
        break;
    case 'update' :
		updateUnit();
		break;
    case 'delete' :
        deleteUnit();
        break;
    
  
	   
    default :
        // if action is not defined or unknown
        // move to main category page
        print "<script>window.location.href = '../CRM.php';</script>";
}
?>


<?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}
/*
    Add a unit
*/


function addUnit()
{
 global $conn;
  global $_POST;
 
   
  if(isset($_POST["unit_name"]) && $_POST["unit_name"]<>''){ 
  
	  $unit_name = $_POST["unit_name"];
	  
	  $sqlquery = "select * from tbl_unit where unit_name= '$unit_name'";
	  
	  $result = mysqli_query($conn,$sqlquery) or die(mysqli_error());
	  if(mysqli_num_rows($result) >0){
	  	$str = "Data Already Exists";
	  }else{
	      $sql = "insert into `tbl_unit` (`unit_name`, `unit_description`) 
	 			values (" . strtoupper(sqlvalue(@$_POST["unit_name"], true)).", " . strtoupper(sqlvalue(@$_POST["tunit_description"], true)).")";
	 //echo "SQL :".$sql;
     	mysqli_query($conn, $sql) or die(mysqli_error());
	 	if(mysqli_affected_rows($conn)>0){
	 		$str = "Record Added Successfully";
	 	}
	  }
  }else{
  		$str = "Wrong Data Entry ";
  }
  echo $str;
}



function deleteUnit()
{
global $conn;
global $_POST;

	for($i=0;$i<count($_POST["userbox"]);$i++){  
		$str="";
		if($_POST["userbox"][$i] != ""){ 
			$userid= $_POST["userbox"][$i];
			/*if(primaryreturn($userid,2) == true){
				$str .= "Primary Return,";
			}
				
			if(primaryOS($userid,2) == true){
				$str .= "Primary Opening Stock,";
			}
				
			if(secondaryOS($userid,2) == true){
				$str .= "Secondary Opening Stock,";
			}
				
			if(primaryOB($userid,2) == true){
				$str .= "Primary Bookings,";
			}
				
			if(secondaryOB($userid,2) == true){
				$str .= "Secondary Bookings,";
			}*/
			
			if(product($userid,2) == true){
				$str .="Product,";
			}
			if($str==""){
				$strSQL = "DELETE FROM tbl_unit WHERE unit_code = '".$_POST["userbox"][$i]."' ";  
				
				//echo "SQL =".$strSQL;
				$objQuery = mysql_query($conn,$strSQL) or die(mysql_error()); 
				$str1 = "U101";
			}else{
				$str1 = "U102";
			}
			//$str = substr($str1,0,strlen($str1)-1);
			 
		} //if 
	} //for 
	if($str1 == "U101"){
		echo "Deleted Successfully";
	}else{
		echo "Unable to delete. Dependent data found";
	}
}
 

function updateUnit()
{
 global $conn;
 global $_POST;
 
 	if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0) {
        $catId = (int)$_POST['cat_id'];
  
  		$sql = "update tbl_unit set unit_name = " . strtoupper(sqlvalue(@$_POST["unit_name"], true)).", 
  									unit_description =  " . strtoupper(sqlvalue(@$_POST["unit_description"], true))." WHERE unit_code = $catId";
  		mysql_query($conn,$sql);
  		if(mysql_affected_rows($conn) > 0){
  			$str = "Updated Successfully";
  		}else{
			$str = "No Data Affected";
		}
  } else {
  	$str = "Unit ID not found ";
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
