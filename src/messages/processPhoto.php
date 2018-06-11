<?php
session_start();
require_once "../config.php";
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
?>
<?php

	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
	
	   case 'delete' :
			deletePhoto();
			break;
	   default :
			  print "<script>window.location.href = '../CRM.php';</script>";
	}
?>
</body>
</html>

<?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}



function deletePhoto()
{
  	global $conn;
	global $_POST;

  	for($i=0;$i<count($_POST["userbox"]);$i++){  
		if($_POST["userbox"][$i] != ""){  
			deletePhotos($_POST["userbox"][$i] );
			$strSQL = "DELETE FROM tbl_photo  WHERE id = '".$_POST["userbox"][$i]."'";
			
			//echo 'Del :'.$strSQL.'<br>';
			$resultset = mysql_query($strSQL) or die(mysql_error());
		
		}  
	}  
	
}

function deletePhotos($id){
	$strSQL = "select * FROM tbl_photo WHERE id = '$id'";
	$resultset = mysql_query($strSQL) or die(mysql_error());
	$row=mysql_fetch_assoc($resultset);
	$filename=$row["photo"];
	unlink("../images/photo/" .$filename);
	
	
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
