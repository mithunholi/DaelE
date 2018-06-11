<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    case 'add' :
        	addArea();
        	break;
        case 'modify' :
        	modifyArea();
        	break;
    	case 'update' :
			updateArea();
			break;
    	case 'delete' :
        	deleteArea();
        	break;
        default :
        	print "<script>window.location.href = '../CRM.php';</script>";
	}
}else{
	print "<script>window.location.href='index.html';</script>";	
}

/*
    Add a Area
*/
function addArea()
{
 global $conn;
  global $_POST;
 
   //echo "Region ID :".$_POST["region_id"]."<br>";
  if(isset($_POST["region_id"]) && $_POST["region_id"]<>'' && isset($_POST["area_name"]) && $_POST["area_name"]<>''){ 
  	 $rid = $_POST["region_id"];
	 $aname = $_POST["area_name"];
  	 $sql_query ="select * from tbl_area where region_id = '$rid' and area_name = '$aname'";
	 $res = mysql_query($sql_query,$conn);
	 if(mysql_num_rows($res)>0){
	 	$str = "Data Already Exists";
	 }else{
 	 	$sql = "insert into `tbl_area` (`region_id`, `area_name`,`area_desc1`) values (" .strtoupper(sqlvalue(@$_POST["region_id"], true)).",
	 		 	" .strtoupper(sqlvalue(@$_POST["area_name"], true)).", " .strtoupper(sqlvalue(@$_POST["area_desc1"], true)).")";
     	// echo $sql;
	  	mysql_query($sql, $conn) or die(mysql_error()); 
		if(mysql_affected_rows()>0){
			$str= "Successfully Saved";
		}
     }
  }else{
  	$str = "Must be type Region and Area Name";
  }
  echo $str;
}

function deleteArea()
{
	for($i=0;$i<count($_POST["userbox"]);$i++){  
				$str="";
		if($_POST["userbox"][$i] != ""){  
			$userid = $_POST["userbox"][$i];
						
			if(assignTP($userid,5) == true){
				$str .= "Assign TP,";
			}
					
			if(assignroute($userid,5) == true){
				$str .= "Assign Route,";
			}
			
			if(outlet($userid,5)==true){
				$str .= "Outlet,";
			}
			
			if(distributor($userid,5)==true){
				$str .= "Distributor,";
			}
			
			if(route($userid,5)==true){
				$str .= "Route Plan,";
			}
			
			
			if($str == ""){
				//area
				$strSQL = "DELETE FROM tbl_area WHERE area_id = '".$_POST["userbox"][$i]."'";
				//echo $strSQL;
				$result = mysql_query($strSQL) or die(mysql_error());
				if(mysql_affected_rows()>0){
					$str1 =  "Deleted Successfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);

		}//if  
	} //for 
	echo $str;
}


function updateArea()
{
 global $conn;
 global $_POST;
 
  if (isset($_POST['area_id']) && (int)$_POST["area_id"]>0 && isset($_POST["area_name"]) && $_POST["area_name"]<>''){ 
        $areaId = (int)$_POST['area_id'];
  
  		$sql = "update tbl_area set region_id = " .strtoupper(sqlvalue(@$_POST["region_id"], true)).",
				area_name = " .strtoupper(sqlvalue(@$_POST["area_name"], true)).", 
  				area_desc1 =  " .strtoupper(sqlvalue(@$_POST["area_desc1"], true))."
			WHERE area_id = $areaId";
  		//echo $sql;
  		mysql_query($sql,$conn);
		if(mysql_affected_rows()>0){
			$str = "Updated Successful";
		}
  } else {
        $str = "Wrong Data Entry";
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
