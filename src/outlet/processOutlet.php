<?php
session_start();
require_once '../config.php';
require_once "../library/functions.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    case 'add' :
        	addOutlet();
        	break;
    	case 'modify' :
        	modifyOutlet();
        	break;
    	case 'update' :
			updateOutlet();
			break;
    	case 'delete' :
        	deleteOutlet();
        	break;
    	default :
		
        print "<script>window.location.href = 'outlet/outlet.php';</script>";
	}
}else{

	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a Outlet
*/
function addOutlet()
{
 global $conn;
 global $_POST;
 global $today;
 
  if(isset($_POST['cboDistributor']) && $_POST['cboDistributor']<>'' && isset($_POST['cboRoute']) && $_POST['cboRoute']<>'' 
  			&& (int)$_POST['cboRoute'] > 0 && $_POST["outlet_name"]<>'' && isset($_POST["outlet_name"])){ 
  		$dcode =strtoupper($_POST['cboDistributor']);
		$rcode = strtoupper($_POST['cboRoute']);
		$regioncode = strtoupper($_POST['cboRegion']);
		//$areacode= $_POST['cboArea'];
		
		$qry = "select * from tbl_distributor where dist_id = '$dcode'";
		$recordset = mysql_query($qry,$conn);
		if(mysql_num_rows($recordset)>0){
			$rowset = mysql_fetch_assoc($recordset);
			$areacode = strtoupper($rowset["area_code"]);
		}else{
			$areacode = "";
		}
		
		
	
		$oname = str_replace("-","_",$_POST["outlet_name"]);
		$oname = strtoupper($oname);
  	   	$sql = "insert into tbl_outlet(dist_id,region_code,area_code,route_id,outlet_name,outlet_address, outlet_cperson, outlet_city,
							 outlet_mobno,outlet_landno,outlet_email1,outlet_email2,outlet_dstatus,cdate) 
				values ('$dcode','$regioncode','$areacode','$rcode','$oname', " .strtoupper(sqlvalue(@$_POST["outlet_address"], true)).",
						" .strtoupper(sqlvalue(@$_POST["outlet_cperson"], true))."," .strtoupper(sqlvalue(@$_POST["outlet_city"], true)).",
						" .sqlvalue(@$_POST["outlet_mobno"], true)."," .sqlvalue(@$_POST["outlet_landno"], true).",
						" .strtolower(sqlvalue(@$_POST["outlet_email1"], true))."," .strtolower(sqlvalue(@$_POST["outlet_email2"], true)).",
						'false','$today')";
		
     	mysql_query($sql, $conn) or die(mysql_error());
	
	  	if(mysql_affected_rows() > 0){
			$str ="Added Successfully";
		}

  }else{
     
  	  $str = "Must be select one of the Route";
  }
  echo $str;

}

function deleteOutlet()
{
	global $conn;
	global $_POST;
    
	for($i=0;$i<count($_POST["userbox"]);$i++){ 
		$str=""; 
		if($_POST["userbox"][$i] != ""){
			$userid=$_POST["userbox"][$i];
			if(primaryreturn($userid,8) == true){
				$str .= "Primary Return,";
			}
				
			if(secondaryOS($userid,8) == true){
				$str .= "Secondary Opening Stock,";
			}
				
			if(secondaryOB($userid,8) == true){
				$str .= "Secondary Bookings,";
			}
					
			if(assignTP($userid,8) == true){
				$str .= "Assign TP,";
			}
					
			if($str==""){
				//Outlet
				$strSQL = "DELETE FROM tbl_outlet WHERE outlet_id = '".$_POST["userbox"][$i]."'";
				$result = mysql_query($strSQL) or die(mysql_error());
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

function updateOutlet()
{
 global $conn;
 global $_POST;
 global $today;
 
  if (isset($_POST['outlet_id']) && (int)$_POST['outlet_id'] > 0) {
        $outletId = (int)$_POST['outlet_id'];
    
		$dcode = strtoupper($_POST['cboDistributor']);
		$rcode = strtoupper($_POST['cboRoute']);
		$regioncode = strtoupper($_POST['cboRegion']);
		//$areacode= $_POST['cboArea'];
		
		$qry = "select * from tbl_distributor where dist_id = '$dcode'";
		//echo "Qry :".$qry."<br>";
		$recordset = mysql_query($qry,$conn);
		if(mysql_num_rows($recordset)>0){
			$rowset = mysql_fetch_assoc($recordset);
			$areacode = strtoupper($rowset["area_code"]);
			//$routecode = $rowset["route_id"];
		}else{
			$distcode = "";
		}
  		$ccode = strtoupper($_POST['cboRoute']);
  		$oname = str_replace("-","_",$_POST["outlet_name"]);
		$oname = strtoupper($oname);
  		$sql = "update tbl_outlet set region_code = '$regioncode',area_code='$areacode',dist_id='$dcode', route_id='$ccode',
			outlet_name = '$oname', outlet_address =  " .strtoupper(sqlvalue(@$_POST["outlet_address"], true)).",
			outlet_cperson = " .strtoupper(sqlvalue(@$_POST["outlet_cperson"], true)).",outlet_city = " .strtoupper(sqlvalue(@$_POST["outlet_city"], true)).",
			outlet_mobno = " .sqlvalue(@$_POST["outlet_mobno"], true).",outlet_landno = " .sqlvalue(@$_POST["outlet_landno"], true).",
			outlet_email1 = " .strtolower(sqlvalue(@$_POST["outlet_email1"], true)).",cdate='$today',
  			outlet_email2 = " .strtolower(sqlvalue(@$_POST["outlet_email2"], true))."	WHERE outlet_id = $outletId";
        //echo "SQL Query == ".$sql."<br>";
  		mysql_query($sql,$conn);
		if(mysql_affected_rows()>0){
			$str = "Updated Successfully";
		}else{
			$str = "Update Failed";
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
