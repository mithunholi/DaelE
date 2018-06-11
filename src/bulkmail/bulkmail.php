<?php
session_start();
if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["catId"]="";
	$_SESSION["pageId"]="";
	$status = false;
  }
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
	$user_name=$_SESSION['User_ID'];
	require_once("../config.php");
	require_once("../library/functions.php");
	$categoryList="";
	if(isset($catId)) $categoryList = buildEmailCategoryList($catId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Bulk Email</title>
	
    <link href="main.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="../CRM.js"></script>
    <script type="text/javascript" src="script.js"></script>
</head>
<body>
  <table class="tbl" cellspacing="1" cellpadding="5" width="100%" style="border:1px solid black">
    <tr>
      <td valign="top" width="65%">
					
			<form method="post" action="bulkmail/sendmail.php" enctype="multipart/form-data" name="frmemail" target="upload_target" onsubmit="if(email_input_validate()==true){startUpload();}">
            <table border="0" cellspacing="1" cellpadding="5" width="100%">
            		<tr>
                    	<td colspan="3" align="center">
                        	<p id="f1_upload_form" align="center">
                        </td>
                    </tr>
            		<tr>
                    	<td colspan="3" align="right">
                        <input type="button" name="btncontact" id="btncontact" value="Contact List" onclick="toggleEmail('popup');" />
                		<input type="button" name="btnimport" id="btnimport" value="Import Contacts" onClick="javascript:sizeTbl();">
                		</td>
                    </tr>
                    
                    <tr>
                    	<td colspan="3" align="left">
                         	<!-- toggleText -->
    						<?php UploadFile(); ?>
                        </td>
                    </tr>
                   <!-- <tr>
                        <td align="left">Name:</td>
                        <td colspan="2" align="left"><input type="text" name="name" id="name" class="input1" /></td>
                    </tr>-->
                    <tr>
                    	<td class="hr" colspan="3" align="left"><h3><b>Mass Mail</b></h3></td>
                    </tr>
                    <tr>
                        <td class="hr" align="left">To:</td>
                        <td class="dr" colspan="2" align="left">
                        	<input type="hidden" name="hidden_email_to" id="hidden_email_to" class="input1" />
                        	<input type="text" name="email_to" id="email_to" class="input1" />
                        </td>
                    </tr>
                    <tr>
                        <td class="hr" align="left">Subject:</td>
                        <td class="dr" colspan="2" align="left"><input type="text" name="subject" id="subject" class="input1" /></td>
                    </tr>
                    <tr>
                        <td class="hr" align="left">Image:</td>
                        <td class="dr" colspan="2" align="left"><input type="file" name="imgfile" onchange="test(this.value);"/></td>
                    </tr>
                    <tr>
                        <td class="hr" align="left">Message:</td>
                        <td class="dr" colspan="2" align="left"><textarea name="about" id="about" rows="4" cols="40"></textarea></td>
                    </tr>
                    <tr>
                        <td class="dr" colspan="3" align="left">
                       <!-- <input type="button" name="btnSubmit" value="Submit!" onclick="if(email_input_validate()==true){javascript: formget(this.form, 'bulkmail/mail_test.php');}">-->
                       <input type="submit" name="submit" id="submit" value="Send" />
                       
                        </td>
                    </tr>
                </table>
               
	  </td>
      <td valign="top" width="35%">
		<?php 
		   ContactList();
		 ?>
      </td>
    </tr>
   </table>
</body>
</html>

<?php 
	}else{
		print "<script>window.location.href='../index.html';</script>";	
	}
?>


<?php
//----------------------User Defined Function Start Here--------------------------------------
function UploadFile(){
?>
	<div id="toggleText" style="display: none">
    	<form method="post" enctype="multipart/form-data" src="site/example.php">
			<table style="border:1px solid black" cellspacing="1" cellpadding="5" width="100%">
            	<tr>
                	<td class="hr" colspan="2" align="left"><h3><b>Upload Contacts</b></h3></td>
                </tr>
                <tr>
                	<td class="hr" align="left" width="25%">
                    	<?php echo htmlspecialchars("Browse the Excel file:")."&nbsp;" ?>
                    </td>
                    <td class="dr" align="left">
                    	<input type="file" name="uploadedfile" id="uploadedfile" size="50" />
                    </td>
                </tr>
       			<tr>
        			<td class="dr" colspan="2">
                    	<center>
                        <input type="hidden" name="displayText" id="displayText" value="+" />
                        <input type="hidden" name="action" id="action" value="importdata">
			<input type="button" id="btnupload" name="btnupload" value="Upload File" onClick="javascript:uploadFile(this.form,'bulkmail/importData.php');"/>
 			<div id="output"></div>
                        </center>
            		</td>
       		 	</tr>
    		</table>
    	</form>
    </div>
<?php
}//end of the uploadfile function
    
function ContactList(){
global $categoryList;
?>
	<div id="popup" style="display:none; margin-top:70px;">
    	<table border="0" cellspacing="1" cellpadding="5" width="100%">
        	<tr>
            	<td class="hr" align="left" colspan="2"><h3><b>Select Email List</b></h3></td>
            </tr>
        	<tr>
            	<td align="left" class="hr">Category:</td>
                <td align="left" class="dr">
                	<select name="cmbCategory" id="cmbCategory" onchange="showCustomer(this.value)">
                    	<option value="">Choose One</option>
                        <option value="all">All</option>
                        	<?php echo $categoryList; ?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td align="left" class="hr">Email Address:</td>
                <td align="left" class="dr">
                	<select name="emailAddress" id="emailAddress" multiple="multiple">
                    	<option value="">Choose One</option>
                    </select>
                </td>        
            </tr>
            <tr>
            	<td colspan="2" align="center" class="dr">
                	<input type="button" name="btnTo" id="btnTo" value="Done" onclick="getSelectedItems(this.value);" />
                </td>
            </tr>
        </table> 	
     </div>
 <?php
 }//end of contactlist file    
 ?>        
