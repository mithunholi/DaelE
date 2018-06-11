<?php
//require_once("../config.php");
//global $conn;
/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/

function buildEmailCategoryList($cId=0){
global $conn;
	// build combo box options
	$list = '';
	$pdatas = getCategoryData();
	foreach ($pdatas as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
			
		$list .= "<optgroup label=\"$name\">"; 
			
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $cId) {
				$list.= " selected";
			}
			$list .= ">{$child['name']}</option>\r\n";
		}
			
		$list .= "</optgroup>";
	}
	
	return $list;
}

function buildEmailList($eId=0){
global $conn;
	// build combo box options
	$list = '';
	$pdatas = getEmailData();
	foreach ($pdatas as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
			
		$list .= "<optgroup label=\"$name\">"; 
			
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $cId) {
				$list.= " selected";
			}
			$list .= ">{$child['name']}</option>\r\n";
		}
			
		$list .= "</optgroup>";
	}
	
	return $list;
}


function getEmailData($categoryId){
global $conn;
	$sql = "select concat(name,'-',email_name) ename from tbl_email where cat_id='$categoryId' order by email_name";
	$resultset = mysqli_query($conn,$sql) or die('Cannot get Email. '.mysqli_error());
	$eaddress =array();
	while($row = mysqli_fetch_array($resulset)){
		list($id, $name) = $row;
		$eaddress[$id]['children'][] = array('id' => $id, 'name' => $name);
	}
	return $eaddress;
}

function getCategoryData(){
global $conn;
	$sql = "SELECT cat_id, cat_name
				FROM tbl_email_categories
				ORDER BY cat_name";
		
	$result = mysqli_query($conn,$sql) or die('Cannot get Category. ' . mysqli_error());
		
	$products = array();
	while($row = mysqli_fetch_array($result)) {
		list($id,  $name) = $row;
		// the child categories are put int the parent category's array
		$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
	}	
	return $products;
}

function checkUser()
{global $conn;

	if(!isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
		header('Location: ' . WEB_ROOT . 'admin/login.php');
		exit;
	}
	
	// the user want to logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}


function getRemarkDetails($rmk){
global $conn;
	$pos = strpos($rmk,';');
    if($pos == true){
    	$arraydata1 = explode(';',$rmk); 
        $sizedata = sizeof($arraydata1);
    }else{
    	$sizedata = 1;
        $arraydata1[0] = $rmk;
    }
?>
    <div style='width:80%;height:200px;overflow:auto;border:groove'>
    <table width="100%">
<?php
	$prevvalue="";
	$prevvalue1="";
	$prevvalue2=""; 
	for($m=0;$m<$sizedata;$m++){
		$rdata = $arraydata1[$m];
		$realdata = explode("*",$rdata);
		if($realdata[0] != $prevvalue){
			$prevvalue = $realdata[0];
			//echo "Date :".$realdata[0]."<br>";
			$prevvalue1="";
			$prevvalue2="";
			echo "<tr><td colspan='2' style='color:black'><b>".date('l,F d,Y',strtotime($realdata[0]))."</b></td></tr>";
			echo "<tr><td colspan='2'><hr></td></tr>";
		}
		$timedata = date('l,F d,Y',strtotime($realdata[1]));
		if($realdata[2] != $prevvalue1){
			$prevvalue1 = $realdata[2];
			echo "<tr><td colspan='2' style='color:blue'>". $realdata[2].":"."</td></tr>";
		}
		echo "<tr><td width='80%' style='color:black'>".$realdata[3]."</td>";
		if($timedata != $prevvalue2){
			$prevvalue2 = $timedata;
			echo "<td width='20%' style='color:#999999'>".$timedata."</td>";
		}
		echo "</tr>";
	}//for
?>
    </table>
    </div>
<?php
}
//end of the function


function notesInfoFound(){
global $conn;
	$notes_qry = "select * from tbl_lead_master a,tbl_lead_notes b where a.lead_id=b.lead_id";
	$notes_res = mysqli_query($conn,$notes_qry);
	if(mysqli_num_rows($notes_res)>0){
		return true;
	}else{
		return false;
	}
}

function notesInfoRetrieve($lid){
global $conn;
	$notes_qry = "select * from tbl_lead_master a,tbl_lead_notes b where a.lead_id=b.lead_id and b.lead_id='$lid'";
	$notes_res = mysqli_query($conn,$notes_qry);
	
}

function getCustomerName($cid){
global $conn;
	$cust_qry = "select * from tbl_customer where customer_id='$cid'";
	$cust_qry = mysqli_query($conn,$cust_qry);
	$cust_row = mysqli_fetch_assoc($cust_row);
	return $cust_row["customer_name"];

}

function paymentdataFound($lid){
global $conn;
	$pay_qry= "select a.* from tbl_lead_master a,tbl_lead_payment b
				where a.lead_id=b.lead_id and b.lead_id='$lid'";
	//echo "Qry :".$ret_qry;
	$resultset = mysqli_query($conn,$pay_qry);
	if(mysqli_num_rows($resultset)>0){
		return true;
	}else{
		return false;
	}
}

function taskdataFound($lid){
global $conn;
	$task_qry= "select a.* from tbl_lead_master a,tbl_lead_task b
				where a.lead_id=b.lead_id and b.lead_id='$lid'";
	//echo "Qry :".$ret_qry;
	$resultset = mysqli_query($conn,$task_qry);
	if(mysqli_num_rows($resultset)>0){
		return true;
	}else{
		return false;
	}
}

function dataFounds($lid){
	global $conn;
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code and a.lead_id='$lid'";
	//echo "Qry :".$ret_qry;
	$resultset = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($resultset)>0){
		return true;
	}else{
		return false;
	}
}

function dataChildFound(){
global $conn;
	
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_lead_child a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
	//echo "Qry :".$ret_qry;
	$resultset = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($resultset)>0){
		return true;
	}else{
		return false;
	}
}

function dataFound(){
	global $conn;
	$ret_qry= "select b.prod_name,c.cat_name,a.prod_qty,a.prod_price,a.prod_amount from tbl_temp_product a,tbl_product b,tbl_categories c
				where a.cat_code=c.cat_code and a.prod_code = b.prod_code";
	$ret_res = mysqli_query($conn,$ret_qry);
	if(mysqli_num_rows($ret_res)>0){
	 	return true;
	}else{
		return false;
	}
}
function getLocation ($cid){
global $conn;
	$qry = "select * from tbl_cell where cell_id = $cid";
	$resultset = mysqli_query($conn,$qry);
	if(mysqli_num_rows($resultset)>0){
		$rowset = mysqli_fetch_assoc($resultset);
		if($rowset["loc_name"] != ""){
			$loc_name = $rowset["loc_name"];
		}else{
			$loc_name = "N/A";
		}
	}else{
		$loc_name = "N/A";
	}
	return $loc_name;
}


function  getRegionName($rid){
global $conn;
	$reg_sql = "select concat(region_name,'-',region_desc) regname from tbl_region where region_id='$rid'";
	//echo "SQL :".$reg_sql."<br>";
	$reg_query = mysqli_query($conn,$reg_sql);
	$reg_row = mysqli_fetch_assoc($reg_query) or die("Error in regname :".mysqli_error());
	return $reg_row["regname"];
}

function getAreaName($aid){
global $conn;
	$area_sql = "select concat(area_name,'-',area_desc1) areaname from tbl_area where area_id='$aid'";
	//echo "SQL :".$reg_sql."<br>";
	$area_query = mysqli_query($conn,$area_sql) or die("Error in areaname :".mysqli_error());
	$area_row = mysqli_fetch_assoc($area_query);
	return $area_row["areaname"];


}

function getAreaNames($rid,$aid){
global $conn;
	$area_sql = "select concat(concat(b.region_name,'',a.area_name),'-',area_desc1) areaname from tbl_area a,tbl_region b where a.region_id=b.region_id and a.area_id='$aid' and b.region_id='$rid'";
	//echo "SQL :".$area_sql."<br>";
	$area_query = mysqli_query($conn,$area_sql) or die("Error in areanames :".mysqli_error());
	$area_row = mysqli_fetch_assoc($area_query);
	return $area_row["areaname"];
}

function getDistributorCode($did){
global $conn;
	$dist_sql = "select concat(b.region_name,'',c.area_name) regcode from tbl_distributor a,tbl_region b,tbl_area c where a.region_code=b.region_id and a.area_code=c.area_id and a.dist_id='$did'";
	$dist_query = mysqli_query($conn,$dist_sql) or die("Error in DistCode :".mysqli_error());
	$dist_row = mysqli_fetch_assoc($dist_query);
	return $dist_row["regcode"];
}

function getDistributorNames($did){
global $conn;
	$dist_sql = "select concat(concat(b.region_name,'',c.area_name),'-',a.dist_name) regcode from tbl_distributor a,tbl_region b,tbl_area c where a.region_code=b.region_id and a.area_code=c.area_id and a.dist_id='$did'";
	$dist_query = mysqli_query($conn,$dist_sql) or die("Error in DistCode :".mysqli_error());
	$dist_row = mysqli_fetch_assoc($dist_query);
	return $dist_row["regcode"];
}

function pageRangeList($showrec){
global $conn;
	$pagedata ="<option value='$showrec'>$showrec</option>";
	$pagedata .="<option value='25'>25</option>";
    $pagedata .="<option value='50'>50</option>";
    $pagedata .="<option value='75'>75</option>";
    $pagedata .="<option value='100'>100</option>";
    $pagedata .="<option value='125'>125</option>";
   return $pagedata;
}

function getToday(){
global $conn;
	date_default_timezone_set('Asia/Calcutta');
	$datetime = new DateTime(); 
	return $datetime->format('Y-m-d H:i:s'); // Prints "2011/03/20 07:16:17"
}
/*
	
*/
function doLogin()
{global $conn;
	// if we found an error save the error message in this variable
	$errorMessage = '';
	
	$userName = $_POST['txtUserName'];
	$password = $_POST['txtPassword'];
	
	// first, make sure the username & password are not empty
	if ($userName == '') {
		$errorMessage = 'You must enter your username';
	} else if ($password == '') {
		$errorMessage = 'You must enter the password';
	} else {
		// check the database and see if the username and password combo do match
		$sql = "SELECT user_id
		        FROM tbl_user 
				WHERE user_name = '$userName' AND user_password = MD5('$password')";
				
		$result = dbQuery($sql);
	   
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$_SESSION['plaincart_user_id'] = $row['user_id'];
			
			// log the time when the user last login
			$sql = "UPDATE tbl_user 
			        SET user_last_login = NOW() 
					WHERE user_id = '{$row['user_id']}'";
			dbQuery($sql);

			// now that the user is verified we move on to the next page
            // if the user had been in the admin pages before we move to
			// the last page visited
			if (isset($_SESSION['login_return_url'])) {
				header('Location: ' . $_SESSION['login_return_url']);
				exit;
			} else {
				header('Location: index.php');
				exit;
			}
		} else {
			$errorMessage = 'Wrong username or password';
		}		
			
	}
	
	return $errorMessage;
}

/*
	Logout a user
*/
function doLogout()
{global $conn;
	if (isset($_SESSION['plaincart_user_id'])) {
		unset($_SESSION['plaincart_user_id']);
		session_unregister('plaincart_user_id');
	}
		
	header('Location: login.php');
	exit;
}

function getEmployeeName($empid)
{global $conn;
	$sqlQuery = "select emp_dname from tbl_employee where emp_id = '$empid'";
	
	$res = mysqli_query($conn,$sqlQuery) or die("Error Username1 :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$distname = $resRow["emp_dname"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}

function getEmployeeNames($empid)
{global $conn;
	$sqlQuery = "select a.emp_dname from tbl_employee a,tbl_user b where a.emp_id = b.Title and b.user_id = '$empid'";
//	echo "SQL :".$sqlQuery;
	$res = mysqli_query($conn,$sqlQuery) or die("Error Username :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$distname = $resRow["emp_dname"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}

function getDesignationName($desid)
{global $conn;
	$sqlQuery = "select design_name from tbl_design where id = '$desid'";
	$res = mysqli_query($conn,$sqlQuery) or die("Error in Dist :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$distname = $resRow["design_name"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}
function getDistributorName($did)
{global $conn;
	$sqlQuery = "select dist_name from tbl_distributor where dist_id ='$did'";
  	//echo "SQL Query :".$sqlQuery."<br>";
	$res = mysqli_query($conn,$sqlQuery) or die("Error in Distributor :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$distname = $resRow["dist_name"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}

function getRouteName($rid)
{global $conn;
	$sqlQuery = "select a.route_code,a.route_name from tbl_route a where a.route_id ='$rid'";
  	$res = mysqli_query($conn,$sqlQuery) or die("Error in Route :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$routename = $resRow["route_code"]."-".$resRow["route_name"]; 
  	}else{
  		$routename="";
  	}
	return $routename;
}

function getOutletNames($did,$rid){
global $conn;
	$sql = "select outlet_name from tbl_outlet where dist_id='$did' and route_id = '$rid'";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die("Error in Outlet :".mysqli_error());
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		return $row["outlet_name"];
	}else{
		return "";
	}

}

function getOutletName($oid){
global $conn;
	$sqlQuery = "select outlet_name from tbl_outlet where outlet_id ='$oid'";
  	$res = mysqli_query($conn,$sqlQuery) or die("Error in Outlet :".mysqli_error());
  	if(mysqli_num_rows($res)>0){
  		$resRow = mysqli_fetch_assoc($res);
		$routename = $resRow["outlet_name"]; 
  	}else{
  		$routename="";
  	}
	return $routename;

}
//salesman list from usertable


//user name list from report 

function buildUserOptionsRep($userId = 0)
{global $conn;
	   $sql = "SELECT b.user_id, a.emp_dname
				FROM tbl_employee a, tbl_user b,tbl_design c
				WHERE a.emp_id = b.Title and a.emp_dest = c.id and c.levels='LEVEL1' and b.status='0' and b.dstatus='0'
				ORDER BY b.user_id";
		//echo "User Query =".$sql. " User ID :".$userId."<br>";
		$result = mysqli_query($conn,$sql) or die('Cannot get employee. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $userId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


//Template List

function buildTemplateOption($Id=0)
{global $conn;
	$sql = "SELECT info_id,info_name
				FROM tbl_template order by info_name";
				
		//echo "User Query =".$sql. " User ID :".$userId."<br>";
		$result = mysqli_query($conn,$sql) or die('Cannot get template. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $Id) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;

}



//Category List

function buildCategoryList($cId = 0)
{global $conn;
	   $sql = "SELECT cat_code, cat_name
				FROM tbl_categories
				ORDER BY cat_name";
		
		$result = mysqli_query($conn,$sql) or die('Cannot get Category. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $cId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}




//Supplier List

function buildSupplierList($sId = 0)
{global $conn;
	   $sql = "SELECT supp_id, supp_name
				FROM tbl_supplier
				ORDER BY supp_name";
		
		$result = mysqli_query($conn,$sql) or die('Cannot get Supplier. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				
				if ($child['id'] == $sId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}

//VAT List

function buildTaxList($cId = 0)
{global $conn;
	   $sql = "SELECT value,concat(name,'',value) vat
				FROM tbl_taxes
				ORDER BY id";
		
		$result = mysqli_query($conn,$sql) or die('Cannot get Tax. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $cId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


//Product List

function buildProductList($pId = 0,$cId = 0,$search='')
{global $conn;
	//echo "PID :".$pId."<br>";
	$qry='';
	if($cId != 0 and $cId != ''){
		$qry = " WHERE cat_code='$cId'";
	}
	if($search != ''){
		$qry .= " and prod_name like '$search%'";
	}
	$sql = "SELECT prod_code, prod_name
				FROM tbl_product".$qry." ORDER BY prod_name";
	//echo "SQL :".$sql."<br>";	
	$result = mysqli_query($conn,$sql) or die('Cannot get Product. ' . mysqli_error());
		
	$products = array();
	while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
	}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $pId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


//User Name List

function buildUserOptions($empId = 0,$lname)
{global $conn;
	   $sql = "SELECT a.emp_id, concat( b.design_name , ' -- ', a.emp_fname ) emp_name
				FROM tbl_employee a, tbl_design b
				WHERE a.emp_dest = b.id and b.levels = '$lname'
				ORDER BY a.emp_id";
		
		$result = mysqli_query($conn,$sql) or die('Cannot get employee. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $empId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


//User Name List

function buildDesignationList($dId = 0,$lname)
{ 
	global $conn;
	   $sql = "SELECT id, design_name
				FROM tbl_design
				WHERE levels = '$lname'
				ORDER BY design_name";
		
		$result = mysqli_query($conn,$sql) or die('Cannot get employee. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $dId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}



//Employee List

function buildEmployeeList($eid,$did){
global $conn;
  $sql = "SELECT emp_id, concat(emp_code,'-',emp_fname) empname
				FROM tbl_employee
				WHERE emp_dest = '$did' and status='0'
				ORDER BY emp_fname";
  //echo "Emp List :".$sql."<br>";
  //echo "Emp Id :".$eid."<br>";		
		$result = mysqli_query($conn,$sql) or die('Cannot get employee. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $eid) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;


}
//User Name List

function buildUserDatas($empId = 0)
{global $conn;
	   $sql = "SELECT a.emp_id, concat(a.emp_dname, ' -- ', b.design_name ) emp_name
				FROM tbl_employee a, tbl_design b
				WHERE a.emp_dest = b.id
				ORDER BY emp_id";
		//echo "Query =".$sql. " EMP ID :".$empId."<br>";
		$result = mysqli_query($conn,$sql) or die('Cannot get employee data: ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $empId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/
function buildCategoryOptions($catId = 0)
{
global $conn;
	$sql = "SELECT cat_code, cat_name
			FROM tbl_categories
			ORDER BY cat_code";
	$result = mysqli_query($conn, $sql) or die('Cannot get Category Name. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}


/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/
function buildUnitOptions($conn,$unitId = 0)
{
global $conn;
	$sql = "SELECT unit_code, unit_name
			FROM tbl_unit
			ORDER BY unit_code";
	$result = mysqli_query($conn,$sql) or die('Cannot get Unit Name. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $unitId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

//build Task Options
function buildTaskOptions($taskId = 0)
{global $conn;
	$sql = "SELECT task_code, task_name
			FROM tbl_task
			ORDER BY task_code";
	$result = mysqli_query($conn,$sql) or die('Cannot get Task Name. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $taskId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

function buildLeadCustomerOptions($catId=0){
global $conn;
	$sql = "SELECT customer_id, customer_name
			FROM tbl_customer
			ORDER BY customer_id";
	$result = mysqli_query($conn,$sql) or die('Cannot get Customer Name. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

/*
	Generate combo box options containing the categories type we have.
	if $catId is set then that category is selected
*/
function buildCategoryTypeOptions($catId = 0)
{global $conn;
	$sql = "SELECT cat_code, cat_type
			FROM tbl_categories
			ORDER BY cat_type";
	$result = mysqli_query($conn,$sql) or die('Cannot get Category Type. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}


/*
	Generate combo box options containing the designation we have.
	if $catId is set then that designation is selected
*/
function buildDesignationOptions($catId = 0)
{global $conn;
	$sql = "SELECT design_name, design_name
			FROM tbl_design
			ORDER BY design_name";
	//echo "SQL :".$sql;
	$result = mysqli_query($conn,$sql) or die('Cannot get Designation. ' . mysqli_error());
	
	$categories = array();
	while($row = mysqli_fetch_array($result)) {
		list($id,  $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

//Product List
function buildProductOptions($cId=0,$pId=0)
{global $conn;
	if((int)$cId > 0 and $cId <> ""){
		$qry = " WHERE cat_code = '$cId'";
	}else{
		$qry = "";
	}
	$sql = "SELECT prod_name, prod_name 
			FROM tbl_product". $qry ." ORDER BY prod_name";
	//echo "Query =".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Product. ' . mysqli_error());
	
	$products = array();
	while($row = mysqli_fetch_array($result)) {
		list($id,  $name) = $row;
	
			// the child categories are put int the parent category's array
			$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($products as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $pId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

function buildDesignOptions($desId = 0,$lid){
global $conn;
	if($lid != ""){
		$qry = " and levels='$lid'";
	}else{
		$qry ="";
	}
	$sql = "SELECT id, design_name
			FROM tbl_design 
			WHERE 1 and design_name <>'SUPER ADMIN'".$qry." ORDER BY design_name ";
    
	
	$result = mysqli_query($conn, $sql) or die('Cannot get Designation. ' . mysqli_error());
	//$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $desId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
		}
    }
	return $list;
}

function buildAssignedDistributor($saleId = 0,$catId = 0,$cdate){
global $conn;
	//echo "Sale ID :".$saleId."Dist ID :".$catId."<br>";
	
	
	
	$sql = "select * from (select a.org_dist_id, concat(concat(b.region_name,'',c.area_name),'-',d.dist_name) dist_name 
			FROM tbl_assigntp a,tbl_region b,tbl_area c,tbl_distributor d
			WHERE a.region_code=b.region_id and a.area_code=c.area_id and a.org_dist_id=d.dist_id
			and a.status='on' and a.user_id = '$saleId' and date_format(a.routeday,'%d-%m-%Y') = '$cdate' group by a.org_dist_id) subq order by org_dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die ('Cannot get Distributor in Assign. '.mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)){
			list($id,$name) = $row;
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);
		}
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				//echo "Child ID :".$child['id']."===Name :".$child['name']."==Cat ID :".$catId."<br>";
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list .= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
    }
	return $list;
			
}

function buildAssignDistributor($saleId = 0,$catId = 0){
global $conn;
	//echo "Sale ID :".$saleId."Dist ID :".$catId."<br>";
	$sql = "select * from (select a.dist_id, concat(concat(b.region_name,'',c.area_name),'-',d.dist_name) dist_name 
			FROM tbl_assignroute a,tbl_region b,tbl_area c,tbl_distributor d where a.region_code=b.region_id and a.area_code=c.area_id and 
			a.dist_id=d.dist_id and a.user_id = '$saleId' group by a.dist_id) subq order by dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die ('Cannot get Distributor in Assign. '.mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)){
			list($id,$name) = $row;
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);
		}
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				//echo "Child ID :".$child['id']."===Name :".$child['name']."==Cat ID :".$catId."<br>";
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list .= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
    }
	return $list;
			
}

function buildStockRotation($catId = 0)
{global $conn;
	//echo "Sale ID :".$saleId."Dist ID :".$catId."<br>";
	$sql = "select * from (select dist_id, concat(dist_code,'-',dist_name) dist_name FROM tbl_assignroute group by dist_id) subq order by dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die ('Cannot get Distributor in Assign. '.mysqli_error());
	$list = '';
	/*if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)){
			list($id,$name) = $row;
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);
		}
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				//echo "Child ID :".$child['id']."===Name :".$child['name']."==Cat ID :".$catId."<br>";
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list .= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
    }
	return $list;*/
			
}


function buildAssignDistributor21($catId = 0){
global $conn;
	//echo "Sale ID :".$saleId."Dist ID :".$catId."<br>";
	$sql = "select * from (SELECT a.dist_id,ucase(concat(concat(d.region_name,'',e.area_name),'-',c.dist_name)) dist_name 
			FROM tbl_assignroute a,tbl_distributor c,tbl_region d,tbl_area e 
			WHERE a.dist_id=c.dist_id and a.region_code = d.region_id and a.area_code=e.area_id  group by a.dist_id)subq order by dist_id";
			 
			
	//$sql = "select * from (select dist_id, concat(dist_code,'-',dist_name) dist_name FROM tbl_assignroute group by dist_id) subq order by dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die ('Cannot get Distributor in Assign. '.mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)){
			list($id,$name) = $row;
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);
		}
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				//echo "Child ID :".$child['id']."===Name :".$child['name']."==Cat ID :".$catId."<br>";
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list .= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
		//echo $list;
    }
	return $list;
			
}


function buildAssignRoute($saleId = 0,$catId = 0){
global $conn;
	$sql = "select route_id, concat(route_code,'-',route_name) route_name FROM tbl_assignroute where user_id = '$saleId' order by dist_id";

	$result = mysqli_query($conn,$sql) or die ('Cannot get Distributor in Assign. '.mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)){
			list($id,$name) = $row;
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);
		}
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
    }
	return $list;
			
}
/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/
function buildDistributorOptions($catId = 0)
{global $conn;
	$sql  = "SELECT a.dist_id,ucase(concat(concat(b.region_name,'',c.area_name),'-',a.dist_name)) dist_name 
			FROM tbl_distributor a,tbl_region b,tbl_area c
			where a.region_code=b.region_id and a.area_code=c.area_id and a.dstatus='false' order by a.dist_id";
	
    
	//$result = dbQuery($sql) or die('Cannot get Route. ' . mysqli_error());
	$result = mysqli_query($conn,$sql) or die('Cannot get Distributor. ' . mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
    }
	return $list;
}

function buildDistributorOrderBook($dId,$imeino,$days)
{global $conn;

	if($dId <>''){
		$qstr = " and a.org_dist_code like '$dId%'";
	}else{
		$qstr = "";
	}
	$sql = "SELECT a.org_dist_id,ucase(concat(concat(d.region_name,'',e.area_name),'-',c.dist_name)) distname 
			FROM tbl_assigntp a,tbl_user b,tbl_distributor c,tbl_region d,tbl_area e 
						WHERE a.user_id = b.user_id and a.org_dist_id=c.dist_id and a.region_code=d.region_id and a.area_code=e.area_id 
						and b.imei_no = '$imeino' and date_format(a.routeday,'%d-%m-%Y')= '$days' group by a.org_dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get AssignRoute. ' . mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			///$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $dId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	}  
	return $list;

}


function buildUserAssignTP($empId = 0,$day=0,$day1=0)
{global $conn;
	   $day =date(($day));
	   $day1 =date(($day1));
	   $sql = "SELECT * FROM (SELECT b.user_id, a.emp_dname
				FROM tbl_employee a, tbl_user b,tbl_assigntp c
				WHERE a.emp_id = b.Title and b.user_id = c.user_id and b.status='0' and b.dstatus='0'
				and date_format( c.routeday, '%Y-%m-%d' ) >= '$day' and date_format( c.routeday, '%Y-%m-%d' ) <= '$day1'
				GROUP BY user_id)SUBQ ORDER BY user_id";
		//echo "Query =".$sql."<br>";
		$result = mysqli_query($conn,$sql) or die('Cannot get employee. ' . mysqli_error());
		
		$products = array();
		while($row = mysqli_fetch_array($result)) {
			list($id,  $name) = $row;
		
				// the child categories are put int the parent category's array
				$products[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($products as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $empId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	
	return $list;
}


function buildDistributorAssignTP($dId,$uId,$day)
{global $conn;
	$qstr = " and date_format( routeday, '%e/%m/%Y' )  = date_format(str_to_date('$day', '%e-%m-%Y'), '%e/%m/%Y') ";
	
	
	$sql = "SELECT a.org_dist_id,ucase(concat(concat(d.region_name,'',e.area_name),'-',c.dist_name)) distname 
			FROM tbl_assigntp a,tbl_user b,tbl_distributor c,tbl_region d,tbl_area e 
			WHERE a.user_id = b.user_id and a.org_dist_id=c.dist_id and a.region_code = d.region_id and a.area_code=e.area_id 
			and b.user_id = '$uId' ".$qstr." group by a.org_dist_id";
	//echo "SQL :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get AssignRoute. ' . mysqli_error());
	$list = '';
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $dId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	}  
	return $list;

}
function buildDistributorOptionsReport($rId,$aId,$rrId,$catId)
{global $conn;
	//echo "Dist Id :".$dId." =".$catId.'<br>';
	if($rId <>''){
		$qstr = " and a.region_code = '$rId'";
	}else{
		$qstr = "";
	}
	
	if($aId <> ''){
		$qstr .= " and a.area_code like '$aId%'";
	}
	
	if($rrId <> '' and $rrId <> 'All Routes'){
		$qstr .= " and d.route_id = '$rrId'";
	}
	
	/*$sql = "SELECT dist_id, ucase(concat(dist_code,'-',dist_name)) dist_name
			FROM tbl_distributor where dstatus='false'".$qstr." ORDER BY dist_code";*/
    $sql = "SELECT a.dist_id,ucase(concat(b.region_name,'',c.area_name),'-',a.dist_name) dist_name 
			FROM tbl_distributor a,tbl_region b,tbl_area c,tbl_route d 
			where a.region_code=b.region_id and a.area_code=c.area_id and a.region_code = d.region_code and a.area_code=d.area_code and 
			a.dstatus='false'".$qstr." order by a.dist_id";
	//echo "SQL Query :".$sql."<br>";		
	$result = mysqli_query($conn,$sql) or die('Cannot get Distributor. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	   
		return $list;
	}
}

function buildDistributorOptionsRep2($dId,$catId)
{global $conn;
	//echo "Dist Id :".$dId." =".$catId.'<br>';
	if($dId <>''){
		$qstr = " and dist_code like '%$dId%'";
	}else{
		$qstr = "";
	}
	$sql = "SELECT dist_id, ucase(concat(dist_code,'-',dist_name)) dist_name
			FROM tbl_distributor where dstatus='false'".$qstr." ORDER BY dist_code";
    
	$result = mysqli_query($conn,$sql) or die('Cannot get Distributor. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	   
		return $list;
	}
}


function buildDistributorOptionsRep($rId,$aId,$catId)
{global $conn;
	//echo "Dist Id :".$dId." =".$catId.'<br>';
	$qstr="";
	if($rId <>''){
		$qstr .= " and a.region_code = '$rId' ";
	}
	
	if($aId <> ''){
		$qstr .= " and a.area_code='$aId' ";
	}
	$sql = "SELECT a.dist_id,ucase(concat(concat(b.region_name,'',c.area_name),'-',a.dist_name)) dist_name 
			FROM tbl_distributor a,tbl_region b,tbl_area c
			where a.region_code=b.region_id and a.area_code=c.area_id and a.dstatus='false'".$qstr." order by a.dist_id";
			
	/*$sql = "SELECT dist_id, ucase(concat(dist_code,'-',dist_name)) dist_name
			FROM tbl_distributor where dstatus='false'".$qstr." ORDER BY dist_code";*/
    //echo "SQL11 :".$sql."<b>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Distributor. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	   
		return $list;
	}
}

//for Webuser


/** Assign Distributor **/

function buildAssignDistributorOptions($rId,$aId,$catId)
{global $conn;
	$qstr="";
	if($rId <>''){
		$qstr .= " and a.region_code = '$rId' ";
	}
	
	if($aId <> ''){
		$qstr .= " and a.area_code='$aId' ";
	}
	
	$sql = "SELECT a.dist_id,ucase(concat(concat(b.region_name,'',c.area_name),'-',a.dist_name)) dist_name 
			FROM tbl_distributor a,tbl_region b,tbl_area c
			where a.region_code=b.region_id and a.area_code=c.area_id 
			and a.dstatus='false'".$qstr." and dist_id not in (SELECT dist_id FROM tbl_assignroute) ORDER BY a.dist_id";
	
   // echo "SQL ".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Distributor11. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}
	   
		return $list;
	}
}

/* Salesman List */
function buildSalesOptions($catId=0)
{global $conn;
	$sql = "SELECT Title,Title FROM tbl_user ORDER BY Title";
 
	$result = mysqli_query($conn,$sql) or die('Cannot get User. ' . mysqli_error());
	
	$distributors = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($distributors as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}

function buildAreaOptions1($rId,$aId){
global $conn;
   //echo "rId :".$rId."<br>";
   if($rId <> ''){
   		$qry = " and region_id = '$rId' ";
   }else{
   		$qry = "";
   }
   
  
   $sql = "SELECT area_id,concat(area_name,'-',area_desc1) area_desc
			FROM tbl_area WHERE 1 ".$qry." ORDER BY area_name";
    
	//echo "SQL REgion :".$sql. " =".$rId. " ====".$aId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Area. ' . mysqli_error());
	
	$regions = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$regions[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($regions as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $aId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;

}

/* Area List */
function buildAreaOptions($rId,$aId){
global $conn;
   //echo "rId :".$rId."<br>";
   if($rId <> ''){
   		$qry = " WHERE region_id = '$rId' ";
   }else{
   		$qry = "";
   }
   
   
   $sql = "SELECT area_id,concat(area_name,'-',area_desc1) area_desc
			FROM tbl_area".$qry." ORDER BY area_name";
    
	//echo "SQL REgion :".$sql. " =".$rId. " ====".$aId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Area. ' . mysqli_error());
	
	$regions = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$regions[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($regions as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $aId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;

}
/* Region List */

function buildRegionOptions($catId = 0)
{
global $conn;
	$sql = "SELECT region_id,concat(region_name,'-',region_desc) region_desc
			FROM tbl_region
			ORDER BY region_name";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysqli_query($conn, $sql) or die('Cannot get Region. ' . mysqli_error($conn));
	
	$regions = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$regions[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($regions as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}

/* Route Plan List */
function buildRoutePlanOptions($catId = 0){
global $conn;
	$sql = "SELECT id,concat(rpid,'-',rpdesc) rpname
			FROM tbl_routeplan
			ORDER BY id";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route Plan. ' . mysqli_error());
	
	$routeplan = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$routeplan[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($routeplan as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}


/* Salesman List */
function buildAssignedSalesman($dId=0, $catId = 0){
global $conn;
	$sql = "SELECT a.user_id,concat(b.emp_dname,' -- ',c.design_name) emp_name 
			FROM tbl_user a,tbl_employee b,tbl_design c,tbl_assignroute d WHERE a.Title = b.emp_id and b.emp_dest = c.id and a.user_id=d.user_id and d.dist_id='$dId' 
			ORDER BY a.user_id";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Employee. ' . mysqli_error());
	
	$routeplan = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$routeplan[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($routeplan as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}


/* Salesman List */
function buildSalesmanOptions($catId = 0){
global $conn;
	$sql = "SELECT a.user_id,concat(b.emp_dname,' -- ',c.design_name) emp_name 
			FROM tbl_user a,tbl_employee b,tbl_design c WHERE a.Title = b.emp_id and b.emp_dest = c.id 
			ORDER BY a.user_id";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Employee. ' . mysqli_error());
	
	$routeplan = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$routeplan[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($routeplan as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}


/* Salesman List for AssignRoute */
function buildSalesmanOptions1($catId = 0,$rstatus){
global $conn;
	$sql = "SELECT * FROM (SELECT a.user_id,concat(b.emp_dname,' -- ',c.design_name) emp_name 
			FROM tbl_user a,tbl_employee b,tbl_design c WHERE a.Title = b.emp_id and b.emp_dest = c.id and c.design_name='$rstatus' and a.status='0' and a.dstatus='0' GROUP BY emp_name)SUQ 
			ORDER BY user_id";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Employee. ' . mysqli_error());
	
	$routeplan = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$routeplan[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($routeplan as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
   
	return $list;
}

function buildRouteOptionsRep1($rId,$dId,$catId)
{global $conn;

	if($rId <> "" and $rId <> "0"){
		 $qry = " and region_code like '$rId%' ";
	}else{
		 $qry = "";
	}
 
	//echo "RID :".$rId. " DID: ".$dId."<br>";
	if($dId <> "" and $dId <> "0"){
		$SqlQuery = "select * from tbl_distributor where dist_id = '$dId'";
		$resData = mysqli_query($conn,$SqlQuery) or die (mysqli_error());
		$rowData = mysqli_fetch_assoc($resData);
		//$rcode = $rowData["region_code"];
		$acode = $rowData["area_code"];
		$qry .= " and area_code = '$acode' ";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) routename
			FROM tbl_route WHERE 1 ".$qry ."
			ORDER BY route_id";
	//echo "Query 111 :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}//foreach
	}//if	
	return $list;
}

/** Assign Route TP List **/

function buildRouteAssignTP($rId,$dId,$catId)
{global $conn;

	if($rId <> "" and $rId <> "0"){
		 $qry = " and region_code like '$rId%' ";
	}else{
		 $qry = "";
	}
 
	//echo "RID :".$rId. " DID: ".$dId."<br>";
	if($dId <> "" and $dId <> "0"){
		$SqlQuery = "select * from tbl_distributor where dist_id = '$dId'".$qry;
		$resData = mysqli_query($conn,$SqlQuery) or die (mysqli_error());
		$rowData = mysqli_fetch_assoc($resData);
		//$rcode = $rowData["region_code"];
		$acode = $rowData["area_code"];
		$qry .= " and area_code = '$acode' ";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) routename
			FROM tbl_route WHERE 1 ".$qry ."
			ORDER BY route_id";
	//echo "Query 111 :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	
		$list = '';
		$list1 = '';
		$list2 = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			//$list .= "<optgroup label=\"$name\">"; 
			$lstatus = false;
			foreach ($children as $child) {
				$list1 .= $child['id'].',';
				$list2 .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list2.= " selected";
					$lstatus = true;
				}
				
				$list2 .= ">{$child['name']}</option>\r\n";
			}
			
			
			
		}//foreach
		$list1 = substr($list1,0,strlen($list1)-1);
		$list3 = "<option value=\"{$list1}\"";
		if($list1 == $catId){
				$list3 .= " selected";
		}
		$list3 .= ">All</option>\r\n";
		$list = "<optgroup label=\"$name\">".$list3.$list2."</optgroup>";
	}//if	
	return $list;
}

/** Assign Route List **/

/*function buildAssignRoute($rId,$dId,$catId)
{

	if($rId <> "" and $rId <> "0"){
		 $qry = " and region_code like '$rId%' ";
	}else{
		 $qry = "";
	}
 
	//echo "RID :".$rId. " DID: ".$dId."<br>";
	if($dId <> "" and $dId <> "0"){
		$SqlQuery = "select * from tbl_distributor where dist_id = '$dId'";
		$resData = mysqli_query($SqlQuery) or die (mysqli_error());
		$rowData = mysqli_fetch_assoc($resData);
		//$rcode = $rowData["region_code"];
		$acode = $rowData["area_code"];
		$qry .= " and area_code = '$acode' ";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) routename
			FROM tbl_route WHERE 1 ".$qry ."
			ORDER BY route_id";
	//echo "Query 111 :".$sql."<br>";
	$result = mysqli_query($sql) or die('Cannot get Route. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	
		$list = '';
		$list1 = '';
		$list2 = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			$lstatus = false;
			foreach ($children as $child) {
				$list1 .= $child['id'].",";
				$list2 .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list2.= " selected";
					$lstatus = true;
				}
				
				$list2 .= ">{$child['name']}</option>\r\n";
				
			}
			
		}//foreach
		
		
		$list .= "<option value=\"{$list1}\"";
			if(strpos($catId,",")== true){
				$list .= " selected";
			}
			$list .= ">All</option>\r\n";
			$list .= $list2;
			$list .= "</optgroup>";
	}//if	
	$rdata[0] = substr($list1,0,strlen($list1)-1);
	$rdata[1] = $list;
	return $rdata;
}*/

/* Route List */
function buildRouteOptionsRep($rId,$dId,$catId)
{global $conn;
	if($rId <> "" and $rId <> "0" and $rId <> "All Region"){
		$qry = " and region_code = '$rId' ";
	}else{
		$qry = "";
	}
	
	if($dId <> "" and $dId <> "0" ){
		$qry .= " and area_code like '$dId%' ";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) routename
			FROM tbl_route WHERE 1 ".$qry ."
			ORDER BY route_id";
	//echo "Query 111 :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	if(mysqli_num_rows($result)>0){
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
			
			$list .= "<optgroup label=\"$name\">"; 
			
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				
				$list .= ">{$child['name']}</option>\r\n";
			}
			
			$list .= "</optgroup>";
		}//foreach
	}//if	
	return $list;
}

/* Route List */
function buildRouteOptionsReport($rId,$aId,$catId = 0)
{global $conn;
	if($rId <> "" and $rId <> "All Regions"){
		$qry = " and region_code = '$rId' ";
	}
	
	if($aId <> ""){
		$qry .= " and area_code like '$aId%' ";
	}
	//echo "CAT ID :".$catId."<br>";	
	
	$sql = "SELECT route_id, route_name
			FROM tbl_route WHERE 1 ".$qry." ORDER BY route_id";
	//echo "SQL ** ".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	
	$distributors = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
		
	//	if ($parentId == 0) {
			// we create a new array for each top level categories
		//	$categories[$id] = array('name' => $name, 'children' => array());
	//	} else {
			// the child categories are put int the parent category's array
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
	//	}
	}	
	//echo "Cat ID:".$catId;
	// build combo box options
	$list = '';
	foreach ($outlets as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}


/* Route List */
function buildRouteOptions($catId = 0)
{global $conn;
	$sql = "SELECT route_id, route_name
			FROM tbl_route
			ORDER BY route_id";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	
	$distributors = array();
	while($row = mysqli_fetch_array($result)) {
		list($id, $name) = $row;
		
	//	if ($parentId == 0) {
			// we create a new array for each top level categories
		//	$categories[$id] = array('name' => $name, 'children' => array());
	//	} else {
			// the child categories are put int the parent category's array
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
	//	}
	}	
	//echo "Cat ID:".$catId;
	// build combo box options
	$list = '';
	foreach ($outlets as $key => $value) {
		//$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}

/** Filter Route List **/
function buildRouteFilterOptions($catId)
{global $conn;
	if($catId > 0){
		$queryStr = " where dist_id=$catId ";
	}else {
		$queryStr ="";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route $queryStr
			ORDER BY route_id";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
		
			$list .= "<optgroup label=\"$name\">"; 
		
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				$list .= ">{$child['name']}</option>\r\n";
			}
			$list .= "</optgroup>";
		}
		return $list;

}


/** Filter Route List for Outlet Page **/
function buildRouteForOutlet($catId)
{global $conn;
	if((int)$catId >0 ){
		$qry = " WHERE dist_id='$catId' ";
	}else{
		$qry = "";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route".$qry." ORDER BY route_id";
	//echo "Query ==".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
		
			$list .= "<optgroup label=\"$name\">"; 
		
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				$list .= ">{$child['name']}</option>\r\n";
			}
			$list .= "</optgroup>";
		}
		return $list;

}

/** Filter Route List **/
function buildRouteFilterForOutlet($catId)
{global $conn;

	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route 
			ORDER BY route_id";
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
		
			$list .= "<optgroup label=\"$name\">"; 
		
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $catId) {
					$list.= " selected";
				}
				$list .= ">{$child['name']}</option>\r\n";
			}
			$list .= "</optgroup>";
		}
		return $list;

}
/* Routes in Report */
function buildRouteReportOptions($dId,$rId)
{global $conn;
	//echo "Route Report :dId =".$dId." =rId :".$rId."<br>";
	if($dId > 0){
		$queryStr = " where dist_id=$dId ";
	}else {
		$queryStr ="";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route $queryStr
			ORDER BY route_id";
			
    //echo "Route Report Qry : ".$sql."<br>";
	
	$result = mysqli_query($conn,$sql) or die('Cannot get Route. ' . mysqli_error());
	if(mysqli_num_rows($result)>0)
	{
		$distributors = array();
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
		
			$list .= "<optgroup label=\"$name\">"; 
		
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $rId) {
					$list.= " selected";
				}
				$list .= ">{$child['name']}</option>\r\n";
			}
			$list .= "</optgroup>";
		}
		return $list;
    }else{
		return "";
	}
}

/* Outlets in Report */
function buildOutletReportOptions($dId,$rId,$oId)
{global $conn;
	if($dId > 0 && $rId > 0)
	{
		$queryStr = " where dist_id=$dId and route_id=$rId";
	}
	elseif($dId <= 0 && $rId >0 )
	{
		$queryStr = " where route_id=$rId";
	}
	elseif($dId >0 && $rId <= 0)
	{
		$queryStr = " where dist_id=$dId";
	}
	else
	{
		$queryStr ="";
	}
	$sql = "SELECT outlet_id, outlet_name
			FROM tbl_outlet $queryStr
			ORDER BY outlet_id";
			
	echo "SQL *** :".$sql."<br>";
	$result = mysqli_query($conn,$sql) or die('Cannot get Outlet. ' . mysqli_error());
	
	$distributors = array();
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			//$name     = $value['name'];
			$children = $value['children'];
		
			$list .= "<optgroup label=\"$name\">"; 
		
			foreach ($children as $child) {
				$list .= "<option value=\"{$child['id']}\"";
				if ($child['id'] == $oId) {
					$list.= " selected";
				}
				$list .= ">{$child['name']}</option>\r\n";
			}
			$list .= "</optgroup>";
		}
		
	}else{
		$list = "";
	}
	return $list;
}


/*
	Create the paging links
*/
function getPagingNav($sql, $pageNum, $rowsPerPage, $queryString = '')
{global $conn;
	$result  = mysqli_query($conn,$sql) or die('Error, query failed. ' . mysqli_error());
	$row     = mysqli_fetch_array($result, mysqli_ASSOC);
	$numrows = $row['numrows'];
	
	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);
	
	$self = $_SERVER['PHP_SELF'];
	
	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link
	
	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?page=$page{$queryString}\">[Prev]</a> ";
	
		$first = " <a href=\"$self?page=1{$queryString}\">[First Page]</a> ";
	}
	else
	{
		$prev  = ' [Prev] ';       // we're on page one, don't enable 'previous' link
		$first = ' [First Page] '; // nor 'first page' link
	}
	
	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?page=$page{$queryString}\">[Next]</a> ";
	
		$last = " <a href=\"$self?page=$maxPage{$queryString}{$queryString}\">[Last Page]</a> ";
	}
	else
	{
		$next = ' [Next] ';      // we're on the last page, don't enable 'next' link
		$last = ' [Last Page] '; // nor 'last page' link
	}
	
	// return the page navigation link
	return $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last; 
}

function getPagingQuery($sql, $itemPerPage = 10)
{global $conn;
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
	
	// start fetching from this row number
	$offset = ($page - 1) * $itemPerPage;
	
	return $sql . " LIMIT $offset, $itemPerPage";
}

function getPagingLink($sql, $itemPerPage = 10, $strGet = '')
{global $conn;
	//echo "gee :".$sql;
	$result        = dbQuery($sql);
	$pagingLink    = '';
	$totalResults  = dbNumRows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
	// how many link pages to show
	$numLinks      = 10;

		
	// create the paging links only if we have more than one page of results
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		
		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <a href=\"$self?page=$page&$strGet/\">[Prev]</a> ";
			} else {
				$prev = " <a href=\"$self?$strGet\">[Prev]</a> ";
			}	
				
			$first = " <a href=\"$self?$strGet\">[First]</a> ";
		} else {
			$prev  = ''; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <a href=\"$self?page=$page&$strGet\">[Next]</a> ";
			$last = " <a href=\"$self?page=$totalPages&$strGet\">[Last]</a> ";
		} else {
			$next = ''; // we're on the last page, don't show 'next' link
			$last = ''; // nor 'last page' link
		}

		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;		
		
		$end   = min($totalPages, $end);
		
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = " $page ";   // no need to create a link to current page
			} else {
				if ($page == 1) {
					$pagingLink[] = " <a href=\"$self?$strGet\">$page</a> ";
				} else {	
					$pagingLink[] = " <a href=\"$self?page=$page&$strGet\">$page</a> ";
				}	
			}
	
		}
		
		$pagingLink = implode(' | ', $pagingLink);
		
		// return the page navigation link
		$pagingLink = $first . $prev . $pagingLink . $next . $last;
	}
	
	return $pagingLink;
}


function showpagenav($page, $pagecount,$pagerange,$url)
{global $conn;
	///echo "Page = ".$page. "  PageCount =".$pagecount."<br>";
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<?php if ($page > 1) { ?>
<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>?page=1')">&lt;&lt;&nbsp;First</a>&nbsp;</td>
<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>?page=<?php echo $page - 1 ?>')">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>

<?php } ?>
<?php
  //global $pagerange;
  //echo "Page Range = ".$pagerange. " =PageCount =".$pagecount."<br>";
  if ($pagecount > 1) {

	  if ($pagecount % $pagerange != 0) {
		$rangecount = intval($pagecount / $pagerange) + 1;
	  }
	  else {
		$rangecount = intval($pagecount / $pagerange);
	  }

  	for ($i = 1; $i < $rangecount + 1; $i++) {
		$startpage = (($i - 1) * $pagerange) + 1;
		$count = min($i * $pagerange, $pagecount);
    	if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      		for ($j = $startpage; $j < $count + 1; $j++) {
        		if ($j == $page) {
               ?>
					<td><b><?php echo $j ?></b></td>
		 <?php } 
		 	   else { ?>
				<td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>?page=<?php echo $j ?>')"><?php echo $j ?></a></td>
		<?php  }//if 
		   }//for loop 
		}//if 
		
    }//for
 }//if
 ?>
<?php 
	if ($page < $pagecount) 
	 {
?>
		<td>&nbsp;<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>?page=<?php echo $page + 1 ?>')">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
        <td><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','<?php echo $url; ?>?page=<?php echo $pagecount ?>')">Last&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php 
	} 
?>
</tr>
</table>
<?php 
}//end of the function 

/************************************* Delete Functions *******************************************************************************************/

function tracklogs($uid,$status){
global $conn;
	
 if($status==1){
 	$qry = " and user_id='$uid'";
 }else if($status==9){
 	$qry = " and cell_id='$uid'";
 }
global $conn;
 $track_qry = "select * from tbl_tracklogs where 1".$qry;
 $track_result = mysqli_query($conn,$track_qry) or die("Error in Track :".mysqli_error());
 if(mysqli_num_rows($track_result)>0){
	return true;
 }else{
	return false;
 }
}

function cellinfo($uid){
global $conn;
 $cell_qry = "select a.* from tbl_cell a,tbl_tracklogs b where a.cell_id=b.cell_id and b.user_id='$uid'";
 //echo $cell_qry."<br>";
 $cell_result = mysqli_query($conn, $cell_qry) or die("Error in Track :".mysqli_error());
 if(mysqli_num_rows($cell_result)>0){
	return true;
 }else{
	return false;
 }
}

function primaryreturn($uid,$status){
global $conn;
 if($status==1){
  $qry = " and b.userid='$uid'";
 }else if($status==2){
  $qry = " and a.categoryid='$uid'";
 }else if($status==3){
  $qry = " and a.prod_id='$uid'";
 }else if($status==6){
  $qry = " and b.routeid='$uid'";
 }else if($status==7){
  $qry = " and b.distid='$uid'";
 }else if($status==8){
  $qry = " and b.outletid='$uid'";
 }else if($status==9){
  $qry = " and b.cellid='$uid'";
 }
global $conn;
 $pr_qry = "select a.* from tbl_pr_master a,tbl_pr_child b where a.returnid=b.returnid".$qry;
 $pr_result = mysqli_query($conn, $pr_qry) or die("Error in Primary Return :".mysqli_error($conn));
 if(mysqli_num_rows($pr_result)>0){
	return true;
 }else{
	return false;
 }
}

function secondaryOS($uid,$status){
global $conn;
 if($status==1){
  $qry = " and b.userid='$uid'";
 }else if($status==2){
  $qry = " and a.categoryid='$uid'";
 }else if($status==3){
  $qry = " and a.prod_id='$uid'";
 }else if($status==6){
  $qry = " and b.route_id='$uid'";
 }else if($status==7){
  $qry = " and b.distid='$uid'";
 }else if($status==8){
  $qry = " and b.outletid='$uid'";
 }else if($status==9){
  $qry = " and b.cellid='$uid'";
 }
  $os_qry = "select a.* from tbl_openstock a,tbl_openstock_child b where a.orderid=b.orderid".$qry; 
  $os_result = mysqli_query($conn,$os_qry) or die("Error in Primary OS :".mysqli_error());
  if(mysqli_num_rows($os_result)>0){
	return true;
  }else{
	return false;
  }
}

function primaryOS($uid,$status){
global $conn;
  if($status==1){
    $qry = " and b.user_id='$uid'";
  }else if($status==2){
    $qry = " and a.cat_code='$uid'";
  }else if($status==3){
    $qry = " and a.prod_id='$uid'";
  }else if($status==7){
    $qry = " and b.dist_id='$uid'";
  }else if($status==9){
    $qry = " and b.cell_id='$uid'";
  }
  
  $cs_qry = "select a.* from tbl_cstock a,tbl_cstock_child b where a.cstock_id=b.cstock_id".$qry;
  $cs_result = mysqli_query($conn,$cs_qry) or die("Error in Secondary OS :".mysqli_error());
  if(mysqli_num_rows($cs_result)>0){
	return true;
  }else{
	return false;
  }
}

function primaryOB($uid,$status){
global $conn;
  if($status==1){
    $qry = " and b.userid='$uid'";
  }else if($status==2){
    $qry = " and a.categoryid='$uid'";
  }else if($status==3){
    $qry = " and a.prod_id='$uid'";
  }else if($status==7){
    $qry = " and b.distid='$uid'";
  }else if($status==9){
    $qry = " and b.cellid='$uid'";
  }
  $pob_qry = "select a.* from tbl_orderbook a,tbl_orderbook_child b where a.orderid=b.orderid and b.ordertype='ORDER BOOKING'".$qry;
  $pob_result = mysqli_query($conn,$pob_qry) or die("Error in Primary OB :".mysqli_error());
  if(mysqli_num_rows($pob_result)>0){
	return true;
  }else{
	return false;
  }
}

function secondaryOB($uid,$status){
global $conn;
  if($status==1){
    $qry = " and b.userid='$uid'";
  }else if($status==2){
    $qry = " and a.categoryid='$uid'";
  }else if($status==3){
    $qry = " and a.prod_id='$uid'";
  }else if($status==6){
    $qry = " and b.routeid='$uid'";
  }else if($status==7){
    $qry = " and b.distid='$uid'";
  }else if($status==8){
  	$qry = " and b.outletid='$uid'";
  }else if($status==9){
    $qry = " and b.cellid='$uid'";
  }
  $sob_qry = "select a.* from tbl_orderbook a,tbl_orderbook_child b where a.orderid=b.orderid and b.ordertype='SALES'".$qry;
  $sob_result = mysqli_query($conn,$sob_qry) or die("Error in Secondary OB :".mysqli_error());
  if(mysqli_num_rows($sob_result)>0){
	return true;
  }else{
	return false;
  }
}

function Logs($uid,$status){
global $conn;
  if($status==1){
    $qry=" and userid='$uid'";
  }else if($status==7){
    $qry=" and distid='$uid'";
  }else if($status==9){
    $qry = " and (login_cellid='$uid' or logout_cellid='$uid')";
  }
  $log_qry = "select * from tbl_log where 1".$qry;
  $log_result = mysqli_query($conn,$log_qry) or die("Error in Log :".mysqli_error());
  if(mysqli_num_rows($log_result)>0){
	return true;
  }else{
	return false;
  }
}

function assignTP($uid,$status){
global $conn;
  if($status==1){
    $qry=" and user_id='$uid'";
  }else if($status==4){
    $qry=" and region_code='$uid'";
  }else if($status==5){
    $qry=" and area_code='$uid'";
  }else if($status==6){
  	$qry=" and route_id ='$uid'";
  }else if($status==7){
    $qry=" and dist_id='$uid'";
  }else if($status==8){
  	$qry = " and outlet_id='$uid'";
  }
  $atp_qry = "select * from tbl_assigntp where 1".$qry;
  $atp_result = mysqli_query($conn,$atp_qry) or die("Error in AssignTP :".mysqli_error());
  if(mysqli_num_rows($atp_result)>0){
	return true;
  }else{
	return false;
  }
}

function assignroute($uid,$status){
global $conn;
  if($status==1){
    $qry=" and user_id='$uid'";
  }else if($status==4){
    $qry=" and region_code='$uid'";
  }else if($status==5){
    $qry=" and area_code='$uid'";
  }else if($status==7){
    $qry=" and dist_id='$uid'";
  }
  $ar_qry = "select * from tbl_assignroute where 1".$qry;
  $ar_result = mysqli_query($conn,$ar_qry) or die("Error in AssignRoute :".mysqli_error());
  if(mysqli_num_rows($ar_result)>0){
	return true;
  }else{
	return false;
  }
}


function area($uid,$status){
global $conn;
  if($status==4){
    $qry=" and region_id='$uid'";
  }
  
  $area_qry = "select * from tbl_area where 1".$qry;
  $area_result = mysqli_query($conn,$area_qry) or die("Error in Area :".mysqli_error());
  if(mysqli_num_rows($area_result)>0){
	return true;
  }else{
	return false;
  }
}


function route($uid,$status){
global $conn;
  if($status==4){
    $qry=" and region_code='$uid'";
  }else if($status==5){
    $qry=" and area_code='$uid'";
  }
  $route_qry = "select * from tbl_route where 1".$qry;
  $route_result = mysqli_query($conn,$route_qry) or die("Error in Route :".mysqli_error());
  if(mysqli_num_rows($route_result)>0){
	return true;
  }else{
	return false;
  }
}


function outlet($uid,$status){
global $conn;
  if($status==1){
    $qry=" and user_id='$uid'";
  }else if($status==4){
    $qry=" and region_code='$uid'";
  }else if($status==5){
    $qry=" and area_code='$uid'";
  }else if($status==6){
  	$qry=" and route_id='$uid'";
  }else if($status==7){
    $qry=" and dist_id='$uid'";
  }
  $outlet_qry = "select * from tbl_outlet where 1".$qry;
  $outlet_result = mysqli_query($conn,$outlet_qry) or die("Error in Outlet :".mysqli_error());
  if(mysqli_num_rows($outlet_result)>0){
	return true;
  }else{
	return false;
  }
}


function distributor($uid,$status){
global $conn;
  if($status==4){
    $qry=" and region_code='$uid'";
  }else if($status==5){
    $qry=" and area_code='$uid'";
  }
  $dist_qry = "select * from tbl_distributor where 1".$qry;
  $dist_result = mysqli_query($conn,$dist_qry) or die("Error in Distributor :".mysqli_error());
  if(mysqli_num_rows($dist_result)>0){
	return true;
  }else{
	return false;
  }
}


function shortmsg($uid,$status){
global $conn;
  if($status==1){
    $qry = " and user_id='$uid'";
  }else if($status==7){
    $qry = " and distid='$uid'";
  }
  $smsg_qry = "select * from tbl_shortmsg where 1".$qry;
  $smsg_result = mysqli_query($conn,$smsg_qry) or die("Error in Short MSG :".mysqli_error());
  if(mysqli_num_rows($smsg_result)>0){
 	return true;
  }else{
	return false;
  }
}

function distributorhier($uid){
global $conn;
  $hr_qry = "select a.* from tbl_hier a,tbl_distributor b where a.dist_id = b.dist_id and a.hid='$uid'";
  //echo $hr_qry."<br>";
  $hr_result = mysqli_query($conn,$hr_qry) or die("Error in Distributor :".mysqli_error());
  if(mysqli_num_rows($hr_result)>0){
	return true;
  }else{
	return false;
  }
}

function product($uid,$status){
global $conn;
  if($status=='2'){
     $qry = " and cat_code='$uid'";
  }
  $product_qry = "select * from tbl_product where 1".$qry;
  $product_result = mysqli_query($conn,$product_qry) or die("Error in Product :".mysl_error());
  if(mysqli_num_rows($product_result)>0){
	return true;
  }else{
	return false;
  }
}
?>
