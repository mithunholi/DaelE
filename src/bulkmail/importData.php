<?php

	require_once("../config.php");
	include("../reader.php");
	
	$target_path = "../bulkmail/upload/";
	if(isset($_FILES['uploadedfile']['name'])){
		//$filename = $_POST["uploadedfile"];
    	if($_FILES['uploadedfile']['type']=='application/vnd.ms-excel'){
			$fname = $_FILES['uploadedfile']['name'];
			//$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
			if(isset($_FILES['uploadedfile']['tmp_name']) && ($_FILES['uploadedfile']['tmp_name'] <> '')){
				$fname = uploadExcel('uploadedfile', $target_path, $fname); //customer image1
				ExtractExcelData($fname);
    			//$qry = "insert into tbl_import values('','$userid','$operatorid','$fname','','$today','','0')";
				//$result =  mysql_query($qry) or die("Error in Table");
				//echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";
			} else{
    			echo "There was an error uploading the file, please try again!";
			}
		}else{
			echo "There was an file extension error, please upload XLS file only!";
		}
	}else{
		echo "Please upload XLS file";
	}
	
	
function uploadExcel($inputName, $uploadDir, $filename){
	global $operatorid;
	$filePath = '';
	$today = date("d-m-Y H:i:s");
	$today1 = date("H_i_s");
	//echo "Today :".$today." :".$today1."<br>";
    $image     = $_FILES[$inputName];
	// if a file is given
    if (trim($image['tmp_name']) != '') {
    	// get the image extension
        $ext = substr(strrchr($image['name'], "."), 1); 
		$ext1 = explode(".",$filename); 
		$fpath = $ext1[0]."_".$today1;
		//echo "Filename :".$filename." : "."Extension :".$fpath."<br>";
		// generate a random new file name to avoid name conflict
        $filePath = $fpath . ".$ext";
		//$filePath = $filename.time()
    	if (!move_uploaded_file($image['tmp_name'], $uploadDir . $filePath)) {
			$filePath = '';
		}
	}
	return $filePath;
}
	
function ExtractExcelData($filename){
	global $today;
	global $target_path;
	$fpath = $target_path.$filename;
	//echo "New filename :".$fpath."<br>";
	$excel = new Spreadsheet_Excel_Reader();
	$excel->read($fpath);    
	$x=1;
	$rows = $excel->sheets[0]['numRows'];
	$cols = $excel->sheets[0]['numCols'];
	//echo "Rows :".$rows."<br>";
	$i=0;
	$siteid = array();
	$infraid = array();
	$bdata="";
	$sdata="";
	//echo "Rows :".$excel->sheets[0]['numRows']."<br>";
	while($x<=$excel->sheets[0]['numRows'] - 1) {
		$y=1;
		$j=1;
		$b=1;
		//echo "Cols :".$excel->sheets[0]['numCols']."<br>";  
      	while($y<=$excel->sheets[0]['numCols']) {
			if($y == 1){
				$cell = isset($excel->sheets[0]['cells'][$x+1][$y]) ? $excel->sheets[0]['cells'][$x+1][$y] : '';
				$sdata .= "("."'".$cell."',"; 
			}
			if($y >=2 && $y<=3){
				$cell = isset($excel->sheets[0]['cells'][$x+1][$y]) ? $excel->sheets[0]['cells'][$x+1][$y] : '';
				$sdata .= "'".$cell."',";
			}
			if($y == 4) {
				$cell = isset($excel->sheets[0]['cells'][$x+1][$y]) ? $excel->sheets[0]['cells'][$x+1][$y] : '';
				//$catid = getCategoryId($cell);
				$catid="";
				$sdata .= "'".$cell."','$filename','$today'),";	
				//insert_data($sdata);
				//$sdata="";
			}
			//echo "Sdata :".$sdata."<br>";
			$y++;
      	}//inner while  
      	$x++;
    }//outer while

	if($sdata != "" && strlen($sdata)>1){
		$stdata = substr($sdata,0,strlen($sdata)-1);
		$qry = "insert into tbl_email(title,name,comp_name,email_id,filename,create_date) values ".$stdata; 
		//echo "sdata :".$qry."<br>";
		//echo "Bdata :".substr($bdata,0,strlen($bdata)-1)."<br>";
		$result = mysql_query($qry);
		if(mysql_affected_rows() > 0){
			echo "Successfully Record Added";
		}
	}
}
?>