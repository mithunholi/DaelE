<?php
session_start();
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){

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
	<td colspan="3" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >
		<table width="98%" border="0">
      	<tr>
          <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th height="19" scope="row">&nbsp;</th>
         
        </tr>
         	
		<tr>
			
           	<th width="37%" height="69" scope="row">
            <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true');javascript:printHeader('Cell_ID Info');" style="text-decoration: none; cursor:pointer">
            <img style="border:none;" src="images/cellId.jpg" width="117" height="84">
            </a>
            </th>
           
		</tr>



		<tr>
         
           <th width="37%" height="19" scope="row">
          <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true');javascript:printHeader('Cell_ID Info');" style="text-decoration: none;cursor:pointer">Cell-Id Info</a>
          </th>
          
		</tr>

		 	
		
        		
      	<tr>
          <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th height="19" scope="row">&nbsp;</th>
        
        </tr>
         	
		
    </table>
  	  <p>&nbsp;</p>
  	  <p>&nbsp;</p>
    <p>&nbsp;</p></td>
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
