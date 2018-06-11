<?php
require_once("../config.php");

$categoryId = $_GET["q"];
if(isset($categoryId) && $categoryId != ""){
	if($categoryId == "all"){
		$sql = "select concat(name,'-',email_id) name,concat(name,'           ',email_id) ename 
						from tbl_email order by email_id";
	}else{
		$sql = "select concat(name,'-',email_id) name,concat(name,'           ',email_id) ename 
						from tbl_email where cat_id='$categoryId' order by email_id";
	}
	//echo $sql;
	$resultset = mysql_query($sql) or die('Cannot get Email. '.mysql_error());
	$eaddress =array();
	$list = '';
	while($rowset = mysql_fetch_array($resultset)){
		list($id, $name) = $rowset;
		$eaddress[$id]['children'][] = array('id' => $id, 'name' => $name);
	}
	
	
	foreach ($eaddress as $key => $value) {
		
		$name     = $value['name'];
		$children = $value['children'];
		//echo "name :".$name." === "." children :".$children."<br>";	
		//$list .= "<optgroup label=\"$name\">"; 
			
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $cId) {
				$list.= " selected";
			}
			$list .= ">{$child['name']}</option>\r\n";
		}
			
		//$list .= "</optgroup>";
	}
	echo $list;
}
?>