<?php
session_start();

if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	require_once '../config.php';
	require_once "../library/functions.php";
	$action = isset($_POST['action']) ? $_POST['action'] : '';


	switch ($action) {
	
		case 'add' :
			addVendor();
			break;
		  
		case 'update' :
			updateVendor();
			break;
		case 'delete' :
			deleteVendor();
			break;
		default :
			  print "<script>window.location.href = '../index.php';</script>";
	               }
}
else{
	print "<script>window.location.href='../index.html';</script>";	
}



// *********************** USER DEFINED FUNCTIONS ************************************************
/*
    Add a Vendor
*/

function addVendor()
{
  global $conn;
  global $_POST;
 
  //echo "STEP-1 "."<br>";
 /* if(isset($_POST["region_code"]) && $_POST["region_code"]<>'' && isset($_POST["area_code"]) && $_POST["area_code"]<>'' && isset($_POST["supp_name"]) 
  	&& $_POST["supp_name"]<>'')
  { 
  		//echo "STEP-2 "."<br>";
		
			$regioncode = strtoupper($_POST["region_code"]);
			$areacode = strtoupper($_POST["area_code"]);
			$suppcode = $regioncode.$areacode;
  			
			$suppname = str_replace("-","_",$_POST["supp_name"]);
			$suppname = strtoupper($suppname);
		 
		  	$sql = "insert into tbl_supplier (region_code,area_code,supp_name, supp_address, supp_city, supp_pin, 
										supp_state,supp_country,supp_cperson,supp_mobno,supp_landno,supp_faxno,supp_email,supp_website,supp_remark,dstatus) 
				 		values ( '$regioncode','$areacode', '$suppname', " .strtoupper(sqlvalue(@$_POST["supp_address"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["supp_city"], true)).", " .strtoupper(sqlvalue(@$_POST["supp_pin"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_state"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_country"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_cperson"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["supp_mobno"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_landno"], true)).",
								" .sqlvalue(@$_POST["supp_faxno"], true).", 
								" .sqlvalue(@$_POST["supp_email"], true).",
								" .sqlvalue(@$_POST["supp_website"], true).",
								" .sqlvalue(@$_POST["supp_remark"], true).",
								'false')";
								
     	 		// echo "SQL 1:".$sql."<br>";
		  		mysql_query($sql, $conn) or die(mysql_error());
				if(mysql_affected_rows() >0){
					$str = "Added Successfully";
				}
  		
	}else{
		$str = "Wrong Data Entry";
	}
  	echo $str; */
	
	if(isset($_POST["supp_name"]) && $_POST["supp_name"]<>''){ 
  		//echo "STEP-2 "."<br>";
			$suppname = str_replace("-","_",$_POST["supp_name"]);
			$suppname = strtoupper($suppname);
		 
		  	$sql = "insert into tbl_supplier (supp_name, supp_address, supp_city, supp_pin, 
										supp_state,supp_country,supp_cperson,supp_mobno,supp_landno,supp_faxno,supp_email,supp_website,supp_remark,dstatus) 
				 		values ('$suppname', " .strtoupper(sqlvalue(@$_POST["supp_address"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["supp_city"], true)).", " .strtoupper(sqlvalue(@$_POST["supp_pin"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_state"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_country"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_cperson"], true)).", 
								" .strtoupper(sqlvalue(@$_POST["supp_mobno"], true)).",
								" .strtoupper(sqlvalue(@$_POST["supp_landno"], true)).",
								" .sqlvalue(@$_POST["supp_faxno"], true).", 
								" .sqlvalue(@$_POST["supp_email"], true).",
								" .sqlvalue(@$_POST["supp_website"], true).",
								" .sqlvalue(@$_POST["supp_remark"], true).",
								'false')";
								
     	 		// echo "SQL 1:".$sql."<br>";
		  		mysqli_query($conn,$sql) or die(mysqli_error($conn));
				if(mysqli_affected_rows() >0){
					$str = "Added Successfully";
				}
  		
	}else{
		$str = "Wrong Data Entry";
	}
  	echo $str;
}

function deleteVendor()
{
	global $conn;
	global $_POST;
   	/*for($i=0;$i<count($_POST["userbox"]);$i++){  
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
				$strSQL = "DELETE FROM tbl_supplier WHERE dstatus='false' and supp_id = '".$_POST["userbox"][$i]."'";
				$resultset = mysql_query($strSQL) or die(mysql_error());
				if(mysql_affected_rows()>0){
					$str1 = "Deleted Succesfully,";
				}
			}else{
				$str1 = "Unable to delete. Dependent data found in ".$str;
			}
			$str = substr($str1,0,strlen($str1)-1);
			
		}//if  
	}//for  */
	$str="";
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		
		if($_POST["userbox"][$i] != ""){  
			$strSQL = "DELETE FROM tbl_supplier WHERE dstatus='false' and supp_id = '".$_POST["userbox"][$i]."'";
				$resultset = mysqli_query($conn, $strSQL) or die (mysqli_error($conn));
				if(mysqli_affected_rows($resultset)>0){
					$str = "U101";
				}
		}
	}
	if($str == "U101"){
		echo "Data Successfully Deleted";  
	}
}

function updateVendor()
{
 global $conn;
 global $_POST;
 //echo $_POST["region_code"]. "  RID ".$_POST["route_id"]."     ".$_POST["dist_name"]."   ".$_POST['dist_id']."<br>";
  /*if (isset($_POST["region_code"]) && $_POST["region_code"]<>'' && isset($_POST["area_code"]) && $_POST["area_code"]<>'' && isset($_POST["supp_name"]) 
  	&& $_POST["supp_name"]<>'' && isset($_POST['supp_id']) && (int)$_POST['supp_id'] > 0) 
  {
        $suppId = (int)$_POST['supp_id'];
		$regioncode = strtoupper($_POST["region_code"]);
		$areacode = strtoupper($_POST["area_code"]);
		$suppcode = $regioncode.$areacode;
		$suppadd = trim(strtoupper($_POST["supp_address"]));
  		$sql = "update tbl_supplier set region_code = '$regioncode',area_code='$areacode', 
						supp_name = " .strtoupper(sqlvalue(@$_POST["supp_name"], true)).", 
					  	supp_address =  '$suppadd',
						supp_city = " .strtoupper(sqlvalue(@$_POST["supp_city"], true)).",
					  	supp_pin = " .sqlvalue(@$_POST["supp_pin"], true).",
						supp_state = " .strtoupper(sqlvalue(@$_POST["supp_state"], true)).",
					  	supp_country = " .strtoupper(sqlvalue(@$_POST["supp_country"], true)).",
						supp_cperson = " .strtoupper(sqlvalue(@$_POST["supp_cperson"], true)).",
						supp_mobno = " .sqlvalue(@$_POST["supp_mobno"], true).",
						supp_landno = " .sqlvalue(@$_POST["supp_landno"], true).",
						supp_faxno = " .sqlvalue(@$_POST["supp_faxno"], true).",
						supp_email = " .strtoupper(sqlvalue(@$_POST["supp_email"], true)).",
						supp_website=" .strtoupper(sqlvalue(@$_POST["supp_website"], true)).",
						supp_remark=" .strtoupper(sqlvalue(@$_POST["supp_remark"], true))." WHERE supp_id = $suppId";
		//echo $sql;
		mysql_query($sql,$conn);
		if(mysql_affected_rows() > 0){
			  	$str = "Updated Successful";
		}
		
  }else{
        $str = "Wrong Data Entry";
  }*/
  if (isset($_POST["supp_name"]) && $_POST["supp_name"]<>'' && isset($_POST['supp_id']) && (int)$_POST['supp_id'] > 0) 
  {
        $suppId = (int)$_POST['supp_id'];
		
		$suppadd = trim(strtoupper($_POST["supp_address"]));
  		$sql = "update tbl_supplier set supp_name = " .strtoupper(sqlvalue(@$_POST["supp_name"], true)).", 
					  	supp_address =  '$suppadd',
						supp_city = " .strtoupper(sqlvalue(@$_POST["supp_city"], true)).",
					  	supp_pin = " .sqlvalue(@$_POST["supp_pin"], true).",
						supp_state = " .strtoupper(sqlvalue(@$_POST["supp_state"], true)).",
					  	supp_country = " .strtoupper(sqlvalue(@$_POST["supp_country"], true)).",
						supp_cperson = " .strtoupper(sqlvalue(@$_POST["supp_cperson"], true)).",
						supp_mobno = " .sqlvalue(@$_POST["supp_mobno"], true).",
						supp_landno = " .sqlvalue(@$_POST["supp_landno"], true).",
						supp_faxno = " .sqlvalue(@$_POST["supp_faxno"], true).",
						supp_email = " .strtoupper(sqlvalue(@$_POST["supp_email"], true)).",
						supp_website=" .strtoupper(sqlvalue(@$_POST["supp_website"], true)).",
						supp_remark=" .strtoupper(sqlvalue(@$_POST["supp_remark"], true))." WHERE supp_id = $suppId";
		//echo $sql;
		mysqli_query($conn,$sql);
		if(mysqli_affected_rows($conn) > 0){
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
