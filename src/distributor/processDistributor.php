<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo")
{
$_SESSION['menuid']=1;
	$action = isset($_POST['action']) ? $_POST['action'] : '';


	switch ($action) {
	
		case 'add' :
			addDistributor();
			break;
		  
		case 'modify' :
			modifyDistributor();
			break;
		case 'update' :
			updateDistributor();
			break;
		case 'delete' :
			deleteDistributor();
			break;
		default :
			  print "<script>window.location.href = '../CRM.php';</script>";
	}

}else{
	print "<script>window.location.href='index.html';</script>";	
}



// *********************** USER DEFINED FUNCTIONS ************************************************
/*
    Add a Distributor
*/

function addDistributor()
{
  global $conn;
  global $_POST;
 
  //echo "STEP-1 "."<br>";
  if(isset($_POST["region_code"]) && $_POST["region_code"]<>'' && isset($_POST["area_code"]) && $_POST["area_code"]<>'' && isset($_POST["dist_name"]) 
  	&& $_POST["dist_name"]<>'')
  { 
  		//echo "STEP-2 "."<br>";
		
			$regioncode = strtoupper($_POST["region_code"]);
			$areacode = strtoupper($_POST["area_code"]);
			$distcode = $regioncode.$areacode;
  			//$sqlquery = "select * from tbl_distributor where region_code='$regioncode' and area_code='$areacode' and dstatus='false'";
			//echo "SQL 1:".$sqlquery."<br>";
			//echo "STEP-3 "."<br>";
			//$rowset = mysql_query($sqlquery,$conn);
			//if(mysql_num_rows($rowset)>0)
			//{
			//	$str = "Data Already Exists";
			//}else{ 
				//echo "STEP-4 "."<br>";
				$distname = str_replace("-","_",$_POST["dist_name"]);
				$distname = strtoupper($distname);
		 
		  		$sql = "insert into `tbl_distributor` (region_code,area_code,dist_name, dist_address, dist_city, dist_pin, 
										dist_state, dist_cperson, dist_email1, dist_email2,dist_mobno,dist_landno,dist_tin,dstatus) 
				 		values ( '$regioncode','$areacode', '$distname', " .strtoupper(sqlvalue(@$_POST["dist_address"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["dist_city"], true)).", " .strtoupper(sqlvalue(@$_POST["dist_pin"], true)).",
								" .strtoupper(sqlvalue(@$_POST["dist_state"], true)).",
								" .strtoupper(sqlvalue(@$_POST["dist_cperson"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["dist_email1"], true)).",
								" .strtoupper(sqlvalue(@$_POST["dist_email2"], true)).",
								" .sqlvalue(@$_POST["dist_mobno"], true).", " .sqlvalue(@$_POST["dist_landno"], true).",
								" .sqlvalue(@$_POST["dist_tin"], true).",'false')";
     	 		// echo "SQL 1:".$sql."<br>";
		  		mysql_query($sql, $conn) or die(mysql_error());
				if(mysql_affected_rows() >0){
					$str = "Added Successfully";
				
					$sql1 = "select * from tbl_distributor order by dist_id desc";
					$res1 = mysql_query($sql1,$conn);
					if(mysql_num_rows($res1)>0){
						$row1 = mysql_fetch_assoc($res1);
						$distid = $row1["dist_id"];
					}
					for($i=0;$i<count($_POST["userbox"]);$i++){
						//echo "Post Data :".$_POST["userbox"][$i]."<br>";
						if($_POST["userbox"][$i] != ""){
							$datas = split("-",$_POST["userbox"][$i]);
							$strSQL = "insert into tbl_hier (hid,dist_id,hname) values ('$datas[0]','$distid','$datas[1]')";
							$hsql = mysql_query($strSQL,$conn) or die ("Error in Hierarchy :".mysql_error());
						}
					}
		    	}
  		
	}else{
		$str = "Wrong Data Entry";
	}
  	echo $str;
}

function deleteDistributor()
{
	global $conn;
	global $_POST;
   	for($i=0;$i<count($_POST["userbox"]);$i++){  
		$str="";
		if($_POST["userbox"][$i] != ""){  
			$userid=$_POST["userbox"][$i];
			if(primaryreturn($userid,7) == true){
				$str .= "Primary Return,";
			}
				
			if(primaryOS($userid,7) == true){
				$str .= "Primary Opening Stock,";
			}
				
			if(secondaryOS($userid,7) == true){
				$str .= "Secondary Opening Stock,";
			}
				
			if(primaryOB($userid,7) == true){
				$str .= "Primary Bookings,";
			}
				
			if(secondaryOB($userid,7) == true){
				$str .= "Secondary Bookings,";
			}
					
			if(Logs($userid,7) == true){
				$str .= "Logout,";
			}
					
			if(assignTP($userid,7) == true){
				$str .= "Assign TP,";
			}
					
			if(assignroute($userid,7) == true){
				$str .= "Assign Route,";
			}
					
			if(outlet($userid,7) == true){
				$str .= "Outlet,";
			}
			
			if(shortmsg($userid,7) == true){
				$str .= "Short Msg,";
			}
			
			if($str==""){
				$strSQL = "DELETE FROM tbl_distributor WHERE dstatus='false' and dist_id = '".$_POST["userbox"][$i]."'";
				$resultset = mysql_query($strSQL) or die(mysql_error());
				if(mysql_affected_rows()>0){
					$str1 = "Deleted Succesfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
			
		}//if  
	}//for  
	echo $str;  
}

function updateDistributor()
{
 global $conn;
 global $_POST;
 //echo $_POST["region_code"]. "  RID ".$_POST["route_id"]."     ".$_POST["dist_name"]."   ".$_POST['dist_id']."<br>";
  if (isset($_POST["region_code"]) && $_POST["region_code"]<>'' && isset($_POST["area_code"]) && $_POST["area_code"]<>'' && isset($_POST["dist_name"]) 
  	&& $_POST["dist_name"]<>'' && isset($_POST['dist_id']) && (int)$_POST['dist_id'] > 0) 
  {
        $distId = (int)$_POST['dist_id'];
		$regioncode = strtoupper($_POST["region_code"]);
			$areacode = strtoupper($_POST["area_code"]);
			$distcode = $regioncode.$areacode;
  		$sql = "update tbl_distributor set region_code = '$regioncode',area_code='$areacode', 
						dist_name = " .strtoupper(sqlvalue(@$_POST["dist_name"], true)).", 
					  	dist_address =  " .strtoupper(sqlvalue(@$_POST["dist_address"], true)).",dist_city = " .strtoupper(sqlvalue(@$_POST["dist_city"], true)).",
					  	dist_pin = " .sqlvalue(@$_POST["dist_pin"], true).",dist_state = " .strtoupper(sqlvalue(@$_POST["dist_state"], true)).",
					  	dist_cperson = " .strtoupper(sqlvalue(@$_POST["dist_cperson"], true)).",dist_email1 = " .strtoupper(sqlvalue(@$_POST["dist_email1"], true)).",
					  	dist_email2 = " .strtoupper(sqlvalue(@$_POST["dist_email2"], true)).",dist_mobno = " .sqlvalue(@$_POST["dist_mobno"], true).",
					  	dist_landno = " .sqlvalue(@$_POST["dist_landno"], true).",
						dist_tin = " .sqlvalue(@$_POST["dist_tin"], true)." WHERE dist_id = $distId";
		//echo $sql;
		mysql_query($sql,$conn);
		if(mysql_affected_rows() > 0){
			  	$str = "Updated Successful";
		}
		
  }else{
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
