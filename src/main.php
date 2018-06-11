<?php
//error_reporting(0);
if(isset($_POST['userid']) and isset($_POST['userpass'])) {
	require_once('config.php');
	 //require_once("./library/utilfunctions.php");
	 if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


	//get the user id,password and company id -------------------------
	$userid=$_POST['userid'];
	
	$userpass=$_POST['userpass'];
	$qry = "select * from tbl_user where user_name='$userid'";
	//echo "Qry :".$qry."<br>";
	//$result1 = mysqli_query($conn, $queryString);
	$resultset = mysqli_query($conn, $qry);
	// while ($row = $resultset->fetch_assoc()) {
     
	//alert($resultset);
	$rowset = mysqli_fetch_assoc($resultset);
	
	if($userid!="" and $userpass!="" and mysqli_num_rows($resultset)==1 and $userpass==$rowset["pass_word"]){
	
		$cmpid = $rowset["comp_id"];
		$uname = $rowset["user_name"];
		$qryString = "select a.user_id,a.user_name,a.pass_word,a.imei_no,a.emp_id,emp_dname,c.design_name,d.comp_name, 
								d.wu_license, d.admin_license, d.msf_license,d.wsf_license 
								from tbl_user a,tbl_employee b,tbl_design c,tbl_master d 
								where a.comp_id=d.comp_id and d.validate_status='0' and d.comp_dstatus='0' and 
								a.emp_id=b.emp_id and b.emp_dest=c.id and d.comp_id='$cmpid' and a.user_name='$uname' and a.status='0' and a.dstatus='0'";
		//echo "SQL :".$qryString."<br>";								
		$result1 = mysqli_query($conn, $qryString);
		//echo "SQL :".$qryString."<br>";
		$row1 = mysqli_fetch_row($result1);
	 	//echo 'Password = '.$row1[2];
		
		if($userid!="" and $userpass!="" and mysqli_num_rows($result1)==1 and $userpass==$row1[2]){
			session_start();
			$_SESSION['User_ID']=$row1[0];		
			$_SESSION['User_Name']=$row1[1]; 
			$_SESSION['imeinumber'] = $row1[3];
			$_SESSION['Title']=$row1[5];	
			$_SESSION['menuid']=1;
			$_SESSION['User_Design'] = $row1[6];
			$_SESSION['Company_Name'] = $row1[7];
			$_SESSION['LEVEL5'] = $row1[9]; //admin user license
			$_SESSION['LEVEL2'] = $row1[8]; //web user license
			$_SESSION['LEVEL1'] = $row1[10]; //mobile sales force user license
			$_SESSION['LEVEL3'] = $row1[11]; //web sales force user license
				
			
		}else{
			//direct to login error
			print "<script>window.location.href='index2.php';</script>";
		}
		if($row1[6] <> 'ADMIN' and $row1[6] <> 'WEBUSER'){
		echo "User Password :".$userpass."=== :".$row1[6]."<br>";
		
			print "<script>window.location.href='login_error.php';</script>";	
		}else{
			print "<script>window.location.href='index.php'</script>";
		}//else
	}else{
		print "<script>window.location.href='login_error.php';</script>";
	}
}else{
	print "<script>window.location.href='index2.php';</script>";
}

?>



