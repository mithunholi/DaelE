<?php
	$sqlData = "select * from tbl_design where levels='LEVEL1'";
	$resultData = mysql_query($sqlData) or die("error in Hierarchy Menu :".mysql_error());
	$hierdata = array();
	$i=0;
	if(mysql_num_rows($resultData)>0){
		while($rowdata = mysql_fetch_assoc($resultData)){
			$hierdata[$i] = $rowdata["design_name"]; 
			$i++;
		}//while
	}
?>
<div id="Left">
<div id="Left_H">
  <a id="Left_Text">Main</a>
</div>
<div id="dhtmlgoodies_slidedown_menu">
  <ul>
  <?php 
	if($user_design == 'ADMIN'){
  ?>
	 <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','home.php');javascript:printHeader(' ');" >Admin</a>
       <ul>
         <li>
           <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employeeMenu.php');javascript:printHeader(' ');" ><b>Employees</b></a>
           <ul>
             <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','designation/designation.php?status=true');javascript:printHeader('Hierarchy List');">
               	  Hierarchy List
               </a>
             </li>
             <hr color="#B2B2B2" size="1px"/>
             <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','employee/employee.php?status=true');javascript:printHeader('Employee Info');">
                 Employees List
               </a>
             </li>
           </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','userMenu.php');javascript:printHeader(' ');" ><b>Users</b></a>                 
            <ul>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=ADMIN');javascript:printHeader('Sales Force Admin');">
                  Admin
                </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=SALESMAN');javascript:printHeader('Sales Force Admin');">
                 Sales Force
               </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=WEBUSER');javascript:printHeader('Web Users Admin');">
                 Web Users
               </a>
              </li>
            </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','productMenu.php');javascript:printHeader(' ');" ><b>Products</b></a>                  
            <ul>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?status=true');javascript:printHeader('Category Admin');">
                	Category
                </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true');javascript:printHeader('Products Admin');">
                	Products
                </a>
              </li>
             
              <hr color="#B2B2B2" size="1px"/>
               	<li>
                 <a href="#">Reports</a>
                 <ul>	
                	<li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/productReport.php?status=true');javascript:printHeader('Products List');">
                   		Products List
                 	</a>
                    </li>
                  
                 </ul>	
               </li>
            </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','regionMenu.php');javascript:printHeader(' ');" ><b>Regions</b></a>                   
		    <ul>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','region/region.php?status=true');javascript:printHeader('Region Plan');">
                  Region Plan
                </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
              	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','area/area.php?status=true');javascript:printHeader('Area Plan');">
                  Area Plan
                </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','route/route.php?status=true');javascript:printHeader('Route Admin');">
                  Route Plan
                </a>
              </li>
              
              <hr color="#B2B2B2" size="1px"/>
               	<li>
                 <a href="#">Reports</a>
                 <ul>	
                	<li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','route/routeReport.php?status=true');javascript:printHeader('Regions Map');">
                   		Regions Map
                 	</a>
                    </li>
                  
                 </ul>	
               </li>	
            </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributorMenu.php');javascript:printHeader(' ');" ><b>Distributors</b></a>	                    				
             <ul>
               <li>
                 <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributor.php?status=true&dstatus=false');javascript:printHeader('Distributor Admin');">
                   Distributors
                 </a>
               </li>
               <hr color="#B2B2B2" size="1px"/>
               <li>
                 <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','outlet/outlet.php?status=true');javascript:printHeader('Outlet Admin');">
                   Outlets
                 </a>
               </li>
               <hr color="#B2B2B2" size="1px"/>
               	<li>
                 <a href="#">Reports</a>
                 <ul>	
                	<li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','distributor/distributorRep.php?status=true');javascript:printHeader('Distributor List');">
                   		Distributor List
                 	</a>
                    </li>
                  
                 </ul>	
               </li>
             </ul>
           </li>
           <hr color="#B2B2B2" size="1px"/>
           <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','assignMenu.php');javascript:printHeader(' ');" ><b>Assign</b></a>                     
             <ul>
               <li>
                 <a href="#">
                   SalesForce Distributor
                 </a>	
                 <?php 
				  if(sizeof($hierdata)>0){
				  	$i=0;
				 ?>
                 	<ul>
                 <?php
				 	while($i<sizeof($hierdata)){
				 ?>
                 		<li>
                        <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','assignroute/assignroute.php?status=true&repstatus=<?php echo $hierdata[$i]; ?>');javascript:printHeader('Assign Distributor');">
                        	<?php echo $hierdata[$i]; ?>
						</a>
                        </li>
                        <hr color="#B2B2B2" size="1px"/>
                <?php
						$i++;
					}//while
				?>
                	</ul>
                 <?php
				 }//if
				?>
                    	
               </li>
               <hr color="#B2B2B2" size="1px"/>
                <li>
                 <a href="#">
                   SalesForce TourPlan
                 </a>	
                 <?php 
				  if(sizeof($hierdata)>0){
				  	$i=0;
				 ?>
                 	<ul>
                 <?php
				 	while($i<sizeof($hierdata)){
				 ?>
                 		<li>
                        <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','assignTP/assignTP.php?status=true&repstatus=<?php echo $hierdata[$i]; ?>');javascript:printHeader('Assign Tour Plan');">
                        	<?php echo $hierdata[$i]; ?>
						</a>
                        </li>
                        <hr color="#B2B2B2" size="1px"/>
                <?php
						$i++;
					}//while
				?>
                	</ul>
                 <?php
				 }//if
				?>
               </li>
               <hr color="#B2B2B2" size="1px"/>
               	<li>
                 <a href="#">Reports</a>
                 <ul>	
                	<li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','assignroute/arlist.php?status=true');javascript:printHeader('Daywise Distributor Map');">
                   		Daywise Distributor Map
                 	</a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','assignroute/aplist.php?status=true');javascript:printHeader('Global Distributor Map');">
                   		Global Distributor Map
                 	</a>
                    </li>
                 </ul>	
               </li>
             </ul>
           </li>	
         
          
           
       </ul>
     </li>
     
     <li><a href="#" >Settings</a>
       <ul>
       	 <!-- <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoiceMenu.php');javascript:printHeader(' ');" ><b>Invoice Number</b></a>
          	<ul>
               <li>
                 <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?status=true&menutype=Primary');javascript:printHeader('Primary Invoice Number');">
                   Primary Sales
                 </a>
               </li>
               <hr color="#B2B2B2" size="1px"/>
               <li>
                 <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','invoice/primary.php?status=true&menutype=Secondary');javascript:printHeader('Secondary Invoice Number');">
                 Secondary Sales
                 </a>
               </li>
             </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>-->
          <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true');javascript:printHeader('Cell Info Admin');">
             Cell-ID Info
             </a>
          </li>
       </ul>
     </li>
     <?php
		}
	 ?>
     <?php
		if($user_design =='WEBUSER')
		{
	   ?>
     
       	<li><a href="#" ><b>Primary</b></a>                 
           <ul>
       	 		<li>
         			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','orderbooking.php?status=true');javascript:printHeader('Primary OrderBooking');">
             			OrderBooking
            		</a>
         		</li>
         		<hr color="#B2B2B2" size="1px"/>
         		<li>
         			<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','priclosingstock.php?status=true');javascript:printHeader('Primary ClosingStock');">
             			Opening Stock
            		</a>
         		</li>
           </ul>
        </li>
     <?php } ?>
      <?php
		if($user_design=='ADMIN')
		{
		?>
        	<li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messagesMenu.php');javascript:printHeader(' ');" ><b>Messages</b></a>                 
              <ul>
                
                <li> 
                  <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?status=true');javascript:printHeader('Short Messages');">
                  Short Messages
                  </a>
                </li>
              </ul>
            </li>
            <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','inboxMenu.php?status=true');javascript:printHeader(' ');" >Inbox</a>
              <ul>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','inbox.php?status=true');javascript:printHeader(' ');" ><b>Primary</b></a>
                  <ul>
                    <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=Order Booking&recstatus=All&acceptstatus=false');javascript:printHeader('Primary OrderBooking');">
                            OrderBooking(<?php echo $_SESSION['Primary_Order_Booking']; ?>)
                     </a>
                    </li>
                   
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Inventory/inventory.php?status=true&repstatus=Order Booking&recstatus=Inventory'); javascript:printHeader('Primary Inventory');">
                          SandBox(<?php echo $inventory_detail_row['inventoryRecord']; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Order Booking&recstatus=Invoice&tstatus=TRANSIT'); javascript:printHeader('Primary Transit');">
                          Transit(<?php echo $primaryTransit; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Payment/payment.php?status=true&repstatus=Order Booking&recstatus=Invoice'); javascript:printHeader('Primary Delivered');">
                          Delivered(<?php echo $payment_detail_row['paymentRecord']; ?>)
                      </a>
                    </li>
                   <!--  <hr color="#B2B2B2" size="1px"/>
                   <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Order Booking&recstatus=Invoice&tstatus=CANCEL'); javascript:printHeader('Primary Cancelled');">
                          Cancelled(<?php //echo $primaryCancel; ?>)
                      </a>
                    </li> -->
                     <hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/closingStock.php?status=true&recstatus=All');javascript:printHeader('Primary OpeningStock');">
                      OpeningStock(<?php echo $row1['totalRecord']; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                    	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Order Booking&recstatus=All'); javascript:printHeader('Primary Return Products');">
                    	 Returns(<?php echo $primaryReturn ; ?>)
                    	</a>
                    </li>
                  </ul>
                </li>
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','secondaryMenu.php?status=true');javascript:printHeader(' ');" ><b>Secondary</b></a>
                  <ul>
                   
                    
                    <?php
                    if	($_SESSION['domainname'] != 'Manufacturer'){
					?>
                     <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=Sales&recstatus=All&acceptstatus=false'); javascript:printHeader('Secondary Sales');">
                    	 Order Booking(<?php echo $row2['totalRec2']; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Inventory/inventory.php?status=true&repstatus=Sales&recstatus=Inventory'); javascript:printHeader('Primary Payments');">
                          Inventory(<?php echo $inventory_detail_row1['inventoryRecord1']; ?>)
                      </a>
                    </li>
                     <hr color="#B2B2B2" size="1px"/>
                     <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Sales&recstatus=Invoice&tstatus=TRANSIT'); javascript:printHeader('Secondary Transit');">
                          Transit(<?php echo $secondaryTransit; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Sales&recstatus=Invoice&tstatus=DONE'); javascript:printHeader('Secondary Delivered');">
                          Delivered(<?php echo $secondaryDeliever; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Sales&recstatus=Invoice&tstatus=CANCEL'); javascript:printHeader('Secondary Cancelled');">
                          Cancelled(<?php echo $secondaryCancel; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                       <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Payment/payment.php?status=true&repstatus=Sales&recstatus=Invoice'); javascript:printHeader('Secondary Payments');">
                       Payments(<?php echo $payment_detail_row1['paymentRecord1']; ?>)
                       </a>
                    </li>
                    <?php }else{ ?>
                     <hr color="#B2B2B2" size="1px"/>
                     <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Sales&recstatus=Invoice&tstatus=TRANSIT'); javascript:printHeader('Secondary Sales');">
                          Order Booking(<?php echo $sec_row["secRecord"]; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Payment/payment.php?status=true&repstatus=Sales&recstatus=Invoice'); javascript:printHeader('Secondary Delivered');">
                          Delivered(<?php echo $payment_detail_row1["paymentRecord1"]; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Transit/transit.php?status=true&repstatus=Sales&recstatus=Invoice&tstatus=CANCEL'); javascript:printHeader('Secondary Cancelled');">
                          Cancelled(<?php echo $secondaryCancel; ?>)
                      </a>
                    </li>
                    <?php } ?>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/openingStock.php?status=true&recstatus=All');javascript:printHeader('Secondary OpeningStock');">
                    	OpeningStock(<?php echo $row3['totalRecord3']; ?>)
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Sales&recstatus=All'); javascript:printHeader('Secondary Return Products');">
                          Returns(<?php echo $secondaryReturn ; ?>)
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <!--
			<li><a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','acceptedInboxMenu.php?status=true');javascript:printHeader(' ');" >Accepted Inbox</a>        
              <ul>
                <li><a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','acceptedPrimaryMenu.php?status=true');javascript:printHeader(' ');" ><b>Primary</b></a>                  <ul>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=Order Booking&recstatus=Invoice&acceptstatus=true'); javascript:printHeader('Invoice Primary OrderBooking');">
                    	OrderBooking
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li><a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/closingStock.php?status=true&recstatus=Invoice');javascript:printHeader('Invoice Primary ClosingStock');">
                      ClosingStock
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Order Booking&recstatus=Return Invoice');javascript:printHeader('Primary Return Items');">
                    	Returns
                      </a>
                    </li>
                    
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/Payment/paymentAccept.php?status=true&repstatus=Order Booking');javascript:printHeader('Primary Payments');">
                    	Payments
                      </a>
                    </li> 
                  </ul>
                </li>
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','acceptedSecondaryMenu.php?status=true');javascript:printHeader(' ');" ><b>Secondary</b></a>     			  <ul>    
                  	<li>
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/orderbookRep.php?status=true&repstatus=Sales&recstatus=Invoice&acceptstatus=true'); javascript:printHeader('Invoice Secondary Sales');">
                    	Sales
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/openingStock.php?status=true&recstatus=Invoice');javascript:printHeader('Invoice Secondary OpeningStock');">
                    	OpeningStock
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/Returns/Returns.php?status=true&repstatus=Sales&recstatus=Return Invoice');javascript:printHeader('Secondary Return Items');">
                    	Returns
                      </a>
                    </li>
                  
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php //echo CENTER_DIV ?>','Inbox/Payment/paymentAccept.php?status=true&repstatus=Sales'); javascript:printHeader('Secondary Payments');">
                       Payments
                      </a>
                    </li> 
                  </ul>
                </li>
              </ul>
            </li>-->
            <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','trackMenu.php?status=true');javascript:printHeader(' ');" >Tracking</a>
              <ul>
                 <li> 
                   <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','trackingLogs.php?status=true');javascript:printHeader('Location');">
                     Location
                   </a>
                 </li>
              </ul>
            </li>
            <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','report.php');javascript:printHeader(' ');" >Reports</a>
              <ul>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','primaryReport.php');javascript:printHeader(' ');" ><b>Primary</b></a>                  	
                  <ul>  
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','salesReport.php?status=true&repstatus=Order Booking');javascript:printHeader('Primary OrderBooking');">
                        OrderBooking
                      </a>
                    </li>
                  	<hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','closingReport.php?status=true');javascript:printHeader('Primary ClosingStock');">
                        ClosingStock
                      </a>
                    </li>                      
                    <hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','returnReport.php?status=true&repstatus=Order Booking');javascript:printHeader('Primary Returns');">
                        Returns
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','paymentReport.php?status=true&repstatus=Order Booking');javascript:printHeader('Primary Payments');">
                      Payments
                      </a>
                    </li>
                  </ul>
                </li>
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','secondaryReport.php');javascript:printHeader(' ');" ><b>Secondary</b></a>                  <ul>  
                    <li>
                	  <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','stockReport.php?status=true');javascript:printHeader('Secondary OpeningStock');">
                    	OpeningStock
                      </a>
                    </li>
                  	<hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','salesReport.php?status=true&repstatus=Sales');javascript:printHeader('Secondary Sales');">
                        Sales
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','returnReport.php?status=true&repstatus=Sales');javascript:printHeader('Secondary Returns');">
                        Returns
                      </a>
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','paymentReport.php?status=true&repstatus=Sales');javascript:printHeader('Secondary Payments');">
                        Payments
                      </a>
                    </li>
				  </ul>
                </li>         
                
               <!--  <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','attendanceLogsRep.php?status=true');javascript:printHeader('Attendance Log Report');">
                 <b>Attendance Log</b>
                </a></li> -->
                
                
                
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dsrsalesman.php?status=true');javascript:printHeader('DSR - Secondary');">
                 <b>DSR - Secondary</b>
                </a></li>   
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/Daily_Sales_Report.php?status=true');javascript:printHeader('DSR - Summary');">
                 <b>DSR - Primary</b>
                </a></li> 
                 <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/logoutReport.php?status=true');javascript:printHeader('Logout Report');">
                 <b>Logout Report</b>
                </a></li> 
                <!--
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthAppraisal.php?status=true');javascript:printHeader('Monthly Appraisal Report');">
                 <b>Monthly Appraisal Report</b>
                </a></li> 
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondAppReport.php?status=true');javascript:printHeader('Secondary Sales Appraisal  Report');">
                 <b>Secondary Sales Appraisal</b>
                </a></li>
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondCategoryApp.php?status=true');javascript:printHeader('SAR-Category');">
                 <b>SAR-Category</b>
                </a></li> -->
                
                 <hr color="#B2B2B2" size="1px"/>
                 <li>
                 <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/secondaryAppraisal.php?status=true');javascript:printHeader('Salesman Appraisal');">
                 <b>Salesman Appraisal</b>
                 </a></li>
                 <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimary-temp.php?status=true');javascript:printHeader('Monthly Consolidated Primary');">
                 <b>Consolidated - Primary</b>
                </a></li>  
                 <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlySecond.php?status=true');javascript:printHeader('Monthly Consolidated Secondary');">
                 <b>Consolidated - Secondary</b>
                </a></li> 
                 <!--<hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stockRotation.php?status=true');javascript:printHeader('Stock Rotation Report');">
                 <b>Stock Rotation Format</b>
                </a></li>  -->
                 <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/monthlyPrimaryClose.php?status=true');javascript:printHeader('Monthly Consolidated Primary Closing');">
                 <b>Consolidated - Primary CS</b>
                </a></li> 
                <!-- 
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','dsrReport.php?status=true');javascript:printHeader('Distributor Stock Report');">
                 <b>Distributor Stock Report</b>
                </a></li>               
                -->
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dailySalesReport.php?status=true');javascript:printHeader('Daily Sales Report');">
                 <b>Daily Sales - Test</b>
                </a></li> 
                <hr color="#B2B2B2" size="1px"/>
                <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cellMenu.php?status=true');javascript:printHeader(' ');" ><b>Tracking Logs</b></a>                  <ul>
                    <li>
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','trackingLogsRep.php?status=true');javascript:printHeader('Cell id');">
                        Cell ID
                      </a>
                    </li>
                  </ul>
                </li>
               
               
              </ul>
            </li>
         
          <?php
			}
		   ?>
           </ul>
           </div>
        <script type="text/javascript">
			expandFirstItemAutomatically = 1;
			expandMenuItemByUrl = true;
			initSlideDownMenu();
		</script>