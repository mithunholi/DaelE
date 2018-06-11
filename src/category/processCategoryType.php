<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";

if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
?>

<?php

	$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
	
    case 'add' :
        addCategoryType();
        break;
      
    case 'modify' :
        modifyCategoryType();
        break;
    case 'update' :
		updateCategoryType();
		break;
    case 'delete' :
        deleteCategoryType();
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
    Add a Category Type
*/
function addCategoryType()
{
 global $conn;
  global $_POST;
 
   
  if(isset($_POST["cat_type"]) && $_POST["cat_type"]<>''){ 
  
	  $cattype = $_POST["cat_type"];
	  $sqlquery = "select * from tbl_category_type where cat_type= '$cattype'";
	  
	  $result = mysql_query($sqlquery,$conn) or die(mysql_error());
	  if(mysql_num_rows($result) >0){
	  	$str = "Data Already Exists";
	  }else{
	      $sql = "insert into `tbl_category_type` (`cat_type`, `cat_type_desc`) 
	 			values (" . strtoupper(sqlvalue(@$_POST["cat_type"], true)).", " . strtoupper(sqlvalue(@$_POST["cat_type_desc"], true)).")";
	 //echo "SQL :".$sql;
     	mysql_query($sql, $conn) or die(mysql_error());
	 	if(mysql_affected_rows()>0){
	 		$str = "Record Added Successfully";
	 	}
	  }
  }else{
  		$str = "Wrong Data Entry ";
  }
  echo $str;
}

function deleteCategory()
{
global $conn;
global $_POST;

	for($i=0;$i<count($_POST["userbox"]);$i++){  
		$str="";
		if($_POST["userbox"][$i] != ""){ 
			$userid= $_POST["userbox"][$i];
			$strSQL = "SELECT FROM tbl_category_type where cat_type='$userid'";
			$objQry = mysql_query($strSQL) or die("Error in Category Type :".mysql_error());
			if(mysql_num_rows($objQry)>0){
				//if(product($userid,2) == true){
					$str ="Category,";
				//}
		    }
			if($str==""){
				$strSQL = "DELETE FROM tbl_category_type ";  
				$strSQL .="WHERE id = '".$_POST["userbox"][$i]."' ";  
				//echo "SQL =".$strSQL;
				$objQuery = mysql_query($strSQL) or die(mysql_error()); 
				$str1 = "Deleted Successfully,";
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
			
		} //if 
	} //for 
	echo $str; 
}
 

function updateCategoryType()
{
 global $conn;
 global $_POST;
 
 	if (isset($_POST['cat_id']) && (int)$_POST['cat_id'] > 0) {
        $catId = (int)$_POST['cat_id'];
  
 		
  		$sql = "update tbl_category_type set cat_type = " . strtoupper(sqlvalue(@$_POST["cat_type"], true)).", 
  									cat_type_desc =  " . strtoupper(sqlvalue(@$_POST["cat_type_desc"], true))." WHERE id = $catId";
  		mysql_query($sql,$conn);
  		if(mysql_affected_rows() > 0){
  			$str = "Updated Successfully";
  		}else{
			$str = "Update Failed";
		}
  } else {
  	$str = "Category Type ID not found ";
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
