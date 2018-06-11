<?php
session_start();
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){

require_once("config.php");

//Primary Order Booking
	$pob_query = "select count(*) totalRec1 from (select b.* from tbl_orderbook a,tbl_orderbook_child b where a.orderid=b.orderid 
				and b.order_vstatus='0' and b.order_status='PENDING' and b.acc_rej_status = 'FALSE' and b.ordertype='ORDER BOOKING' GROUP BY b.orderid)SUBQ";
	$pob_result = mysql_query($pob_query);
	$pob_row = mysql_fetch_assoc($pob_result);
	
//Closing Stock (Primary Open Stock)
	$query1 = "select count(*) totalRecord from (select a.* from tbl_cstock a, tbl_cstock_child b,tbl_user c where a.cstock_id=b.cstock_id and 
				b.imei_no=b.imei_no and b.vstatus='0' GROUP BY a.cstock_id) subq";
	$result1 = mysql_query($query1);
	$row1 = mysql_fetch_assoc($result1);   

	$query2 = "select count(*) totalRecord1 from tbl_openstock a, tbl_openstock_child b where a.orderid=b.orderid and b.vstatus='0' group by b.orderid";
	$result2 = mysql_query($query2);
	$row2 = mysql_fetch_assoc($result2);

//$query3 = "select count(distinct(orderid)) totalRec2 from tbl_orderbook where vstatus='false' and ordertype='Sales'";
	/*$query3 = "select count(*) totalRec2 from (select b.* from tbl_orderbook a,tbl_orderbook_child b where a.orderid=b.orderid and b.transit_status='TRANSIT' 
				b.transit_vstatus='0' and b.ordertype='ORDER BOOKING' group by b.invoiceid) subq";
	$result3 = mysql_query($query3);
	$row3 = mysql_fetch_assoc($result3);*/

//Return Items
	$return_detail_query = "select count(*) returnRecord from (select a.* from tbl_pr_master a, tbl_pr_child b,tbl_user c 
							where a.returnid=b.returnid and b.imeino = c.imei_no and b.dstatus='0' and b.vstatus='0' 
							and b.ordertype='ORDER BOOKING' group by a.returnid)subq";
							
	$return_detail_result = mysql_query($return_detail_query);
	$return_detail_row = mysql_fetch_assoc($return_detail_result);

//Payment
	$payment_detail_query = "select count(*) paymentRecord from (select b.* from tbl_orderbook a,tbl_orderbook_child b 
							where a.orderid=b.orderid and b.deliver_status='DONE' and b.deliver_vstatus='0' and b.ordertype='ORDER BOOKING' group by b.invoiceid) subq";

	$payment_detail_result = mysql_query($payment_detail_query);
	$payment_detail_row = mysql_fetch_assoc($payment_detail_result);
	
	//to calculate primary inventory
	
	
	$inventory_detail_query = "select count(*) inventoryRecord from (select b.* from tbl_orderbook a,tbl_orderbook_child b 
								where a.orderid=b.orderid and b.acc_rej_status='INVENTORY' and b.acc_rej_vstatus='0' 
								and b.transit_status='FALSE' and b.transit_cancel_status = 'FALSE' and b.ordertype='ORDER BOOKING' group by b.orderid) subq";
	$inventory_detail_result = mysql_query($inventory_detail_query);
	$inventory_detail_row = mysql_fetch_assoc($inventory_detail_result);
	
	$transit_query = "SELECT count(*) transitRecord FROM (SELECT b.* FROM tbl_orderbook a,tbl_orderbook_child b,tbl_user c 
						WHERE a.orderid=b.orderid and b.imeino=c.imei_no and b.transit_status = 'TRANSIT' and b.deliver_status ='FALSE' 
						AND b.transit_cancel_status = 'FALSE' and b.ordertype = 'ORDER BOOKING' and b.transit_vstatus ='0' GROUP BY a.orderid) subq";
						
	//echo $transit_query;
	$transit_result = mysql_query($transit_query);
	$transit_row = mysql_fetch_assoc($transit_result);
	
	
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
        	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=ORDER BOOKING&recstatus=PENDING&acceptstatus=false');javascript:printHeader('Primary OrderBooking');" style="text-decoration: none; cursor:pointer">
            <img src="images/orderbook.jpg" width="117" height="84">
            </a>
          </th>
        <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th height="69" scope="row">
          	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Inventory/inventory.php?status=true&repstatus=ORDER BOOKING&recstatus=INVENTORY'); javascript:printHeader('Primary SandBox');" style="text-decoration: none; cursor:pointer">
               <img src="images/sandbox.jpg" width="117" height="84">
           </a>
        
        </th>
        
        <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th height="69" scope="row">
        	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=ORDER BOOKING&recstatus=TRANSIT'); javascript:printHeader('Primary Transit');" style="text-decoration: none; cursor:pointer">
                <img src="images/transit.jpg" width="117" height="84">
            </a>
                   
        </th>
        
		
		</tr>
        
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=ORDER BOOKING&recstatus=PENDING&acceptstatus=false');javascript:printHeader('Primary OrderBooking');" style="text-decoration: none; cursor:pointer">
            Order Booking(<?php echo $pob_row['totalRec1']; ?>)
            </a>
            </th>
             <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Inventory/inventory.php?status=true&repstatus=ORDER BOOKING&recstatus=INVENTORY'); javascript:printHeader('Primary SandBox');" style="text-decoration: none; cursor:pointer">
                SandBox(<?php echo $inventory_detail_row['inventoryRecord']; ?>)
            </a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
           	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=ORDER BOOKING&recstatus=TRANSIT'); javascript:printHeader('Primary Transit');" style="text-decoration: none; cursor:pointer">
                Transit(<?php echo $transit_row["transitRecord"]; ?>)
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
              <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Payment/payment.php?status=true&repstatus=ORDER BOOKING&recstatus=DONE'); javascript:printHeader('Primary Delivered');"  style="text-decoration: none; cursor:pointer">
                  <img src="images/payment.jpg" width="127" height="84">
               </a>
            </th>     
             <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th height="69" scope="row">
        	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/closingStock.php?status=true&recstatus=All');javascript:printHeader('Primary OpeningStock');" style="text-decoration: none; cursor:pointer">
            <img src="images/closingstock.jpg" width="117" height="84">
            </a>
        </th>  
        <th height="69" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th height="69" scope="row">
        	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Order Booking&recstatus=All'); javascript:printHeader('Primary Return Products');" style="text-decoration: none; cursor:pointer">
            <img src="images/index.jpg" width="117" height="84">
            </a>            
        </th>
        </th> 	              
         </tr>
         <tr>
             <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        	 <th height="19" scope="row">
             <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Payment/payment.php?status=true&repstatus=ORDER BOOKING&recstatus=DONE'); javascript:printHeader('Primary Delivered');"  style="text-decoration: none; cursor:pointer">             
             Delivered(<?php echo $payment_detail_row['paymentRecord']; ?>)             
              </a>
             </th>                 	 
             
            
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/closingStock.php?status=true&recstatus=All');javascript:printHeader('Primary OpeningStock');" style="text-decoration: none; cursor:pointer">
            Opening Stock(<?php echo $row1['totalRecord']; ?>)
            </a>
            </th>
            <th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
            <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Order Booking&recstatus=All'); javascript:printHeader('Primary Return Products');" style="text-decoration: none; cursor:pointer">
            Returns(<?php echo $return_detail_row['returnRecord']; ?>)
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
        		
                <a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Rejected/reject.php?status=true&repstatus=ORDER BOOKING&recstatus=REJECTED');javascript:printHeader('Primary Rejected');" style="text-decoration: none; cursor:pointer">
                	<img src="images/reject.jpg" width="117" height="84">
            	</a>
                   
        	</th>
        </tr>
        <tr>
        	<th height="19" scope="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th height="19" scope="row">
           	<a onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Rejected/reject.php?status=true&repstatus=ORDER BOOKING&recstatus=REJECTED');javascript:printHeader('Primary Rejected');" style="text-decoration: none; cursor:pointer">
                Rejected
            </a>
            </th>  
        </tr>
    </table>
</td>
</tr>
<tr>
  <td colspan="4" valign="top" style=" border-right:solid thin #BFBFBF 1px;" >&nbsp;</td>
</tr>
</table>
</body>
</html>

<?php
echo "SSSSSSSSSSSSSSSS";
}
else {
		print "<script>window.location.href='index2.php';</script>";	
}
?>
