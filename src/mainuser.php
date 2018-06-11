<?php
session_start();
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']=="awoo"){

require_once("config.php");
$user_name = $_SESSION['User_Name'];
$user_design = $_SESSION['User_Design'];
?>
<html>
<head>
<title>mCRM--Inbox</title>
<link href="CRM.css" rel="stylesheet" type="text/css">
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
</head>
<body leftmargin="0" topmargin="0"  rightmargin="0" style="background:url(images/top.jpg) top repeat-x; width:100%; " >

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style=" border-left:solid thin #DADADA 1px;"> 

<tr>
	<td colspan="3" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >
		<table width="98%" border="0">
      	<tr>
        	<th width="9%" height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
       	    <th width="25%" height="19" scope="row">&nbsp;</th>
            <th width="9%" height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
       	    <th width="25%" height="19" scope="row">&nbsp;</th>
            <th width="9%" height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
       	    <th width="25%" height="19" scope="row">&nbsp;</th>
        </tr>
        <?php
			if($user_design =='ADMIN')
			{
		?>
          <tr>
        	<th width="9%" height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
       	  	<th width="25%" height="69" scope="row">
        		<a onClick="javascript:alert('Please use salesman user only');" style="text-decoration: none; cursor:pointer">
            		<img src="images/order.jpg" width="117" height="84" border="none;">
                 </a>
            </th>
           	<th width="4%" height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
       	  	<th width="23%" height="69" scope="row">
        		<a onClick="javascript:alert('Please use salesman user only');" style="text-decoration: none; cursor:pointer">
               		<img src="images/closingstock.jpg" width="117" height="84" border="none;">            	
                </a>
            </th>
        	<th width="37%" height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th width="2%" height="69" scope="row">&nbsp;</th>
        </tr>
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            	<a onClick="javascript:alert('Please use salesman user only');" style="text-decoration: none; cursor:pointer">
            		Primary Order Booking
                </a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            	<a onClick="javascript:alert('Please use salesman user only');" style="text-decoration: none; cursor:pointer">
            		Primary Closing Stock
                </a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="19" scope="row">&nbsp;</th>
        </tr>
        <?php
			}elseif($user_design =='WEBUSER'){
		?>
        <tr>
			<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="69" scope="row">
        		<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','orderbooking.php?status=true');javascript:printHeader('Primary OrderBooking');" style="text-decoration: none; cursor:pointer">
            	<img src="images/order.jpg" width="117" height="84">
                </a>
            </th>
           <!--  
        	<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="69" scope="row">
        	<a onClick="javascript:LoadDiv('<?php// echo CENTER_DIV ?>','priclosingstock.php?status=true');javascript:printHeader('Primary ClosingStock');" style="text-decoration: none; cursor:pointer">
               <img src="images/closingstock.jpg" width="117" height="84">
               
            </a>
        	</th>
        	<th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="69" scope="row">&nbsp;</th>-->
         </tr>
         <tr>
         	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="19" scope="row">
        		<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','orderbooking.php?status=true');javascript:printHeader('Primary OrderBooking');" style="text-decoration: none; cursor:pointer">
                Primary Order Booking
                </a>
            </th>
            <!--
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="19" scope="row">
            <a onClick="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','priclosingstock.php?status=true');javascript:printHeader('Primary OpeningStock');" style="text-decoration: none; cursor:pointer">
               Primary Opening Stock
               
            </a>
        	</th>-->
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	<th height="19" scope="row">&nbsp;</th>
         </tr>
        <?php } ?>
      
    </table>
</td>
</tr>
<tr>
  <td colspan="3" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >&nbsp;</td>
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
