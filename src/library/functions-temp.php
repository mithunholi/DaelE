<?php
session_start();
/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkUser()
{

	if(!isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
		header('Location: ' . WEB_ROOT . 'admin/login.php');
		exit;
	}
	
	// the user want to logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

/*
	
*/
function doLogin()
{
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
{
	if (isset($_SESSION['plaincart_user_id'])) {
		unset($_SESSION['plaincart_user_id']);
		session_unregister('plaincart_user_id');
	}
		
	header('Location: login.php');
	exit;
}



function getDistributorName($did)
{
	$sqlQuery = "select a.dist_name from tbl_distributor a,tbl_route b where a.dist_id = b.dist_id and a.dist_id ='$did'";
  	$res = mysql_query($sqlQuery);
  	if(mysql_num_rows($res)>0){
  		$resRow = mysql_fetch_assoc($res);
		$distname = $resRow["dist_name"]; 
  	}else{
  		$distname="";
  	}
	return $distname;
}

function getRouteName($rid)
{
	$sqlQuery = "select a.route_code,a.route_name from tbl_route a where a.route_id ='$rid'";
  	$res = mysql_query($sqlQuery);
  	if(mysql_num_rows($res)>0){
  		$resRow = mysql_fetch_assoc($res);
		$routename = $resRow["route_code"]."-".$resRow["route_name"]; 
  	}else{
  		$routename="";
  	}
	return $routename;
}
/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/
function buildCategoryOptions($catId = 0)
{
	$sql = "SELECT cat_code, cat_parent_code, cat_name
			FROM tbl_categories
			ORDER BY cat_code";
	$result = mysql_query($sql) or die('Cannot get Product. ' . mysql_error());
	
	$categories = array();
	while($row = mysql_fetch_array($result)) {
		list($id, $parentId, $name) = $row;
	
			// the child categories are put int the parent category's array
			$categories[$parentId]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		$name     = $value['name'];
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
{
	if((int)$cId > 0 and $cId <> ""){
		$qry = " WHERE cat_code = '$cId'";
	}else{
		$qry = "";
	}
	$sql = "SELECT prod_name, prod_name 
			FROM tbl_product". $qry ." ORDER BY prod_name";
	//echo "Query =".$sql."<br>";
	$result = mysql_query($sql) or die('Cannot get Product. ' . mysql_error());
	
	$products = array();
	while($row = mysql_fetch_array($result)) {
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
			if ($child['id'] == $pId) {
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
function buildDistributorOptions($catId = 0)
{
	$sql = "SELECT dist_id, concat(dist_code,'-',dist_name) dist_name
			FROM tbl_distributor WHERE dstatus='false'
			ORDER BY dist_id";
    
	//$result = dbQuery($sql) or die('Cannot get Route. ' . mysql_error());
	$result = mysql_query($sql) or die('Cannot get Route. ' . mysql_error());
	$list = '';
	if(mysql_num_rows($result)>0){
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			$name     = $value['name'];
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

function buildDistributorOrderBook($dId,$catId,$imeino)
{

	if($dId <>''){
		$qstr = " and a.dist_code like '$dId%'";
	}else{
		$qstr = "";
	}
	
	$sql = "SELECT a.dist_id, concat(a.dist_code,'-',a.dist_name) dist_name FROM tbl_distributor a,tbl_area b,tbl_user c 
						WHERE a.dstatus='false' and a.area_code = b.area_name and b.salesman=c.Title and c.imei_no = '$imeino' $qstr";
	
	$result = mysql_query($sql) or die('Cannot get Route. ' . mysql_error());
	$list = '';
	if(mysql_num_rows($result)>0){
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			$name     = $value['name'];
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

function buildDistributorOptionsRep($dId,$catId)
{
	//echo "Dist Id :".$dId." =".$catId.'<br>';
	if($dId <>''){
		$qstr = " and dist_code like '$dId%'";
	}else{
		$qstr = "";
	}
	$sql = "SELECT dist_id, concat(dist_code,'-',dist_name) dist_name
			FROM tbl_distributor where dstatus='false'".$qstr." ORDER BY dist_code";
    
	//echo "Query =".$sql;
	//$result = dbQuery($sql) or die('Cannot get Route. ' . mysql_error());
	$result = mysql_query($sql) or die('Cannot get Route. ' . mysql_error());
	if(mysql_num_rows($result)>0){
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
	
			// the child categories are put int the parent category's array
			$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
		
		}	
	
		// build combo box options
		$list = '';
		foreach ($distributors as $key => $value) {
			$name     = $value['name'];
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
{
	$sql = "SELECT Title,Title FROM tbl_user ORDER BY Title";
    
	//$result = dbQuery($sql) or die('Cannot get Route. ' . mysql_error());
	$result = mysql_query($sql) or die('Cannot get Route. ' . mysql_error());
	
	$distributors = array();
	while($row = mysql_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$distributors[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($distributors as $key => $value) {
		$name     = $value['name'];
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

/* Area List */
function buildAreaOptions($rId,$aId){
   if($rId <> ''){
   		$qry = " WHERE region_id = '$rId' ";
   }else{
   		$qry = "";
   }
   $sql = "SELECT area_name,concat(area_name,'-',area_desc1) area_desc
			FROM tbl_area".$qry." ORDER BY area_name";
    
	//echo "SQL REgion :".$sql. " =".$rId."<br>";
	$result = mysql_query($sql) or die('Cannot get Area. ' . mysql_error());
	
	$regions = array();
	while($row = mysql_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$regions[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($regions as $key => $value) {
		$name     = $value['name'];
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
	$sql = "SELECT region_name,concat(region_name,'-',region_desc) region_desc
			FROM tbl_region
			ORDER BY region_name";
    
	//echo "SQL REgion :".$sql. " =".$catId."<br>";
	$result = mysql_query($sql) or die('Cannot get Route. ' . mysql_error());
	
	$regions = array();
	while($row = mysql_fetch_array($result)) {
		list($id, $name) = $row;

		// the child categories are put int the parent category's array
		$regions[$id]['children'][] = array('id' => $id, 'name' => $name);	
	
	}	
	
	// build combo box options
	$list = '';
	foreach ($regions as $key => $value) {
		$name     = $value['name'];
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
function buildRouteOptionsRep($rId,$dId,$catId)
{
	if($rId <> "" and $rId <> "0"){
		$qry = " and dist_code like '$rId%' ";
	}else{
		$qry = "";
	}
	
	if($dId <> "" and $dId <> "0"){
		$qry .= " and dist_id = '$dId' ";
	}
	$sql = "SELECT route_id, route_name
			FROM tbl_route WHERE 1 ".$qry ."
			ORDER BY route_id";
	//echo "Query 111 :".$sql."<br>";
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	if(mysql_num_rows($result)>0){
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
function buildRouteOptions($catId = 0)
{
	$sql = "SELECT route_id, route_name
			FROM tbl_route
			ORDER BY route_id";
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	
	$distributors = array();
	while($row = mysql_fetch_array($result)) {
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
		$name     = $value['name'];
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
{
	if($catId > 0){
		$queryStr = " where dist_id=$catId ";
	}else {
		$queryStr ="";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route $queryStr
			ORDER BY route_id";
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
{
	if((int)$catId >0 ){
		$qry = " WHERE dist_id='$catId' ";
	}else{
		$qry = "";
	}
	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route".$qry." ORDER BY route_id";
	//echo "Query ==".$sql."<br>";
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
{

	$sql = "SELECT route_id, concat(route_code,'-',route_name) route_name
			FROM tbl_route 
			ORDER BY route_id";
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
{
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
	
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	if(mysql_num_rows($result)>0)
	{
		$distributors = array();
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
{
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
	$result = mysql_query($sql) or die('Cannot get Outlet. ' . mysql_error());
	
	$distributors = array();
	if(mysql_num_rows($result)>0){
		while($row = mysql_fetch_array($result)) {
			list($id, $name) = $row;
			$outlets[$id]['children'][] = array('id' => $id, 'name' => $name);	
		}	
	//echo "Cat ID:".$catId;
	// build combo box options
		$list = '';
		foreach ($outlets as $key => $value) {
			$name     = $value['name'];
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
{
	$result  = mysql_query($sql) or die('Error, query failed. ' . mysql_error());
	$row     = mysql_fetch_array($result, MYSQL_ASSOC);
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
{
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
{
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
{
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


?>
