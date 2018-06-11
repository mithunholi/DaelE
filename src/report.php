<?php
session_start();
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo"){

?>
<html>
<head>
<title>mCRM--Home</title>
<link href="style_forsms.css" rel="stylesheet" type="text/css">
<style>
img {width: 150px;
background-color: #cccccc;
border: 1px solid #999999;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
padding: 0px 0px 0px 0px;}
.img-shadow {
float:right;
background: url(images/bigshadow.gif) no-repeat bottom right; /* Most major browsers other than IE supports transparent shadow. Newer release of IE should be able to support that. */
}
.img-shadow img {
display: block; /* IE won't do well without this */
position: relative; /* Make the shadow's position relative to its image */
padding: -5px; /* This creates a border around the image */
background-color: #fff; /* Background color of the border created by the padding */
border: 1px solid #cecece; /* A 1 pixel greyish border is applied to the white border created by the padding */
margin: -6px 6px 6px -6px; /* Offset the image by certain pixels to reveal the shadow, as the shadows are 6 pixels wide, offset it by that amount to get a perfect shadow */
}		
</style>
<script type="text/javascript" src="./CRM.js"></script>
</head>
<body leftmargin="0" topmargin="0"  rightmargin="0" style="background:url(images/top.jpg) top repeat-x; width:100%; " >

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style=" border-left:solid thin #DADADA 1px;"> 

<tr>
	<td colspan="4" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >
		<table width="98%" border="0">
      	<tr>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
        </tr>	
		<tr>
			<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/dsrsalesman.php?status=true');javascript:printHeader('SM DSR - Secondary');" style="text-decoration: none; cursor:pointer">
           			<img style="border:none;" src="images/orderbooking.jpg" width="117" height="84">
          		</a>
           </th>
           
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/Daily_Sales_Report.php?status=true');javascript:printHeader('SM DSR - Primary');" style="text-decoration: none; cursor:pointer">
                    	<img style="border:none;" src="images/closingstock.jpg" width="117" height="84">
               
               </a>
            </th>
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/WU_Daily_Sales_Report.php?status=true');javascript:printHeader('WEB USER DSR - Primary');" style="text-decoration: none; cursor:pointer">
                		<img style="border:none;" src="images/webuser.jpg" width="117" height="84">
                    	
                </a>
            </th>
		</tr>
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/dsrsalesman.php?status=true');javascript:printHeader('SM DSR - Secondary');" style="text-decoration: none; cursor:pointer">
              SM DSR - Secondary 
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/Daily_Sales_Report.php?status=true');javascript:printHeader('SM DSR - Primary');" style="text-decoration: none; cursor:pointer">
              SM DSR - Primary
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/WU_Daily_Sales_Report.php?status=true');javascript:printHeader('WEB USER DSR - Primary');" style="text-decoration: none; cursor:pointer">
              WU DSR - Primary
           	</a>
            </th>
        </tr>       
        <tr>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
        </tr>	
		<tr>
			<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/logoutReport.php?status=true');javascript:printHeader('Logout Report');" style="text-decoration: none; cursor:pointer">
           			<img style="border:none;" src="images/logout.jpg" width="117" height="84">
          		</a>
           </th>
           
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/secondaryAppraisal.php?status=true');javascript:printHeader('Salesman Appraisal Report');" style="text-decoration: none; cursor:pointer">
                    	<img style="border:none;" src="images/sappraisal.jpg" width="117" height="84">
               
               </a>
            </th>
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/monthlySecond.php?status=true');javascript:printHeader('Consolidated Secondary Sales Report');" style="text-decoration: none; cursor:pointer">
                		<img style="border:none;" src="images/cssales.jpg" width="117" height="84">
                    	
                </a>
            </th>
		</tr>
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/logoutReport.php?status=true');javascript:printHeader('Logout Report');" style="text-decoration: none; cursor:pointer">
              Logout Report 
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/secondaryAppraisal.php?status=true');javascript:printHeader('Salesman Appraisal Report');" style="text-decoration: none; cursor:pointer">
              Salesman Appraisal
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/monthlySecond.php?status=true');javascript:printHeader('Consolidated Secondary Sales Report');" style="text-decoration: none; cursor:pointer">
              Consolidated - Secondary SR
           	</a>
            </th>
        </tr>     
         <tr>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
           <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
           <th height="19" scope="row">&nbsp;</th>
        </tr>	
		<tr>
			<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/monthlyPrimary-temp.php?status=true');javascript:printHeader('Consolidated Primary Sales Report');" style="text-decoration: none; cursor:pointer">
           			<img style="border:none;" src="images/cpsales.jpg" width="117" height="84">
          		</a>
           </th>
           
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/monthlyPrimaryClose.php?status=true');javascript:printHeader('Consolidated Primary Closing Stock Report')" style="text-decoration: none; cursor:pointer">
                    	<img style="border:none;" src="images/cpstock.jpg" width="117" height="84">
               
               </a>
            </th>
            <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th height="69" scope="row">
            	<a onClick="javascript:LoadDiv('Center_Content','reports/stkreport.php?status=true');javascript:printHeader('Stock Rotation Report');" style="text-decoration: none; cursor:pointer">
                		<img style="border:none;" src="images/srotation.jpg" width="117" height="84">
                    	
                </a>
            </th>
		</tr>
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/monthlyPrimary-temp.php?status=true');javascript:printHeader('Consolidated Primary Sales Report');" style="text-decoration: none; cursor:pointer">
              Consolidated - Primary SR
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/monthlyPrimaryClose.php?status=true');javascript:printHeader('Consolidated Primary Closing Stock Report')" style="text-decoration: none; cursor:pointer">
              Consolidated - Primary CS
           	</a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('Center_Content','reports/stkreport.php?status=true');javascript:printHeader('Stock Rotation Report');" style="text-decoration: none; cursor:pointer">
              Stock Rotation Report
           	</a>
            </th>
        </tr>     
    </table>
  	  <p>&nbsp;</p>
  	  <p>&nbsp;</p>
    <p>&nbsp;</p></td>
</tr>
<tr>
  <td colspan="4" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >&nbsp;</td>
</tr>
</table>
</body>
</html>

<?php
}
else {
		print "<script>window.location.href='index2.php';</script>";	
}
?>
