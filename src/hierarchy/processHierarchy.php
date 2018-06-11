<?php
session_start();
require_once "../config.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){

	$action = isset($_GET['action']) ? $_GET['action'] : '';
	//$action = isset($_POST['action']) ? $_POST['action'] : '';
	switch ($action) {
	    case 'add' :
        	addHierarchy();
        	break;
    	case 'update':
			updateHierarchy();
			break;
		case 'delete':
			deleteHierarchy();
			break;
    	default :
		
        print "<script>window.location.href = 'hierarchy\hierarchy1.php';</script>";
	}
}else{

	print "<script>window.location.href='../index.html';</script>";	
}

/*
    Add a Hierarchy
*/
function addHierarchy()
{
 global $conn;
 global $_POST;
  	$today = date('Y-m-d H:j:s');
  	$hname = $_POST["hname"];  
	
	 
	$query = "select * from tbl_hierarchy where hname = '$hname'";
	$resultset = mysql_query($query) or die ("Error in Hierarchy Search :".mysql_error());
	if(mysql_num_rows($resultset)>0){
		echo "A102";
	}else{
		$data =array();
		for($i=0;$i<count($_POST["statusdata"]);$i++){
			$data[$i][0] = $_POST["statusdata"][$i];
			$data[$i][1] = "NO";
			for($j=0;$j<count($_POST["userbox"]);$j++){
				if($_POST["userbox"][$j] == $data[$i][0]){
					$data[$i][1] = "YES";
				}
			}
		}
		
		$values = "('','$hname',";
		
		for($i=0;$i<sizeof($data);$i++){      
				$values .= "'".$data[$i][1]."'";
				$values .= ',';
		}
		$values = substr($values,0,strlen($values)-1);
		$values .= ",'$today','')";
		$sql = "insert into tbl_hierarchy values ".$values;
		//echo "SQL :".$sql."<br>";
		$result = mysql_query($sql) or die("Error in Hierarchy Insereted ".mysql_error());
		if(mysql_affected_rows()>0){
			echo "Successfully Saved";
		}else{
			echo "Argument Missing";
		}
	}	
	print "<script>window.location.href='../CRM.php';</script>";	
	
}



function deleteHierarchy()
{

	global $conn;
	global $_POST;
    
	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			//Outlet
			//echo "Outlet name:".$_POST["userbox"][$i]."<br>";
			$strSQL = "DELETE FROM tbl_hierarchy WHERE id =". stripslashes($_POST["userbox"][$i]);
			//echo "SQL :".$strSQL."<br>";
			$result = mysql_query($strSQL) or die(mysql_error());
			
		}  
	}  
}

function updateHierarchy()
{
	global $conn;
 	global $_POST;
  	$today = date('Y-m-d H:j:s');
  	$hname = strtoupper($_POST["hname"]);  
	$id = $_POST["id"];
	 
	if($_POST["ob"]=='on')$ob="YES" else $ob ="NO";
	if($_POST["ps"]=='on')$ps = "YES" else $ob = "NO";
	if($_POST["pd"]=='on')$pd = "YES" else $pd = "NO";
	$dr = $_POST["dr"];
	$to = $_POST["to"];
	$tp = $_POST["tp"];
	$no = $_POST["no"];
	$dsr = $_POST["dsr"];
	$sm = $_POST["sm"];
	$track = $_POST["track"];
	
	$qry = "update tbl_hierarchy set ob = '$ob', ps = '$ps', pd = '$pd', dr = '$dr', to = '$to', tp = '$tp', no ='$no', dsr = '$dsr', sm = '$sm', track='$track' 
					where id = '$id'";
	echo $qry;
	
		/*$values .= ",'$today','')";
		
		$sql = "update tbl_hierarchy set "$values." WHERE id='$id'";
		//echo "SQL :".$sql."<br>";
		$result = mysql_query($sql) or die("Error in Hierarchy Updated ".mysql_error());
		if(mysql_affected_rows()>0){
			echo "Successfully Updated";
		}else{
			echo "Argument Missing";
		}*/
	
	print "<script>window.location.href='../CRM.php';</script>";	
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

function getDistributorList($distname){
	global $conn;
	$dqry = "select dist_id,region_code,area_code,dist_code,dist_name from tbl_distributor where dist_id='$distname'";
	$dresult = mysql_query($dqry,$conn);
	if(mysql_num_rows($dresult)>0){
		$drow = mysql_fetch_row($dresult);
		$data[0] = $drow[0];
		$data[1] = $drow[1];
		$data[2] = $drow[2];
		$data[3] = $drow[3];
		$data[4] = $drow[4];
	}
	return $data;
}

function getRouteList($routeid){
	global $conn;
	$rqry = "select route_code,route_name from tbl_route where route_id='$routeid'";
	$rresult = mysql_query($rqry,$conn);
	if(mysql_num_rows($rresult)>0){
		$rrow = mysql_fetch_row($rresult);
		$rdata[0] = $rrow[0];
		$rdata[1] = $rrow[1];
		 
	}
	return $rdata;
}

function getRouteIdList($rcode,$acode){
	global $conn;
	$rqry = "select route_id,route_code,route_name from tbl_route where region_code='$rcode' and area_code='$acode'";
	$rresult = mysql_query($rqry,$conn);
	if(mysql_num_rows($rresult)>0){
		$rsdata=array();
		$i=0;
		while($rrow = mysql_fetch_row($rresult)){
			$rsdata[$i][0] = $rrow[0];
			$rsdata[$i][1] = $rrow[1];
			$rsdata[$i][2] = $rrow[2];
			$i++;
		}
		 
	}
	return $rsdata;
}
function getRoutePlanList($rpid){
	global $conn;
	$rpqry = "select id,rpid,rpdesc from tbl_routeplan where id='$rpid'";
	$rpresult = mysql_query($rpqry,$conn);
	if(mysql_num_rows($rpresult)>0){
		$rprow = mysql_fetch_row($rpresult);
		$rpdata[0] = $rprow[0];
		$rpdata[1] = $rprow[1];
		$rpdata[2] = $rprow[2]; 
	}
	return $rpdata;
}

function getUserList($uid){
	global $conn;
	$uqry = "select Title from tbl_user where user_id = '$uid'";
	$uresult = mysql_query($uqry,$conn);
	if(mysql_num_rows($uresult)>0){
		$urow = mysql_fetch_row($uresult);
		$udata[0] = $urow[0];
	}
	return $udata;
}
function  getOutletId($oname){
	global $conn;
	$outletqry = "select outlet_id from tbl_outlet where outlet_name = '$oname'";
	$outletresult = mysql_query($outletqry,$conn);
	if(mysql_num_rows($outletresult)>0){
		$outletrow = mysql_fetch_row($outletresult);
		$outletdata = $outletrow[0];
	}
	return $outletdata;

}
?>
