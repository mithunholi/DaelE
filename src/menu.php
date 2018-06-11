<?php
 require_once('config.php');
	$sqlData = "select * from tbl_design where levels='LEVEL1'";
	$resultData = mysqli_query($conn, $sqlData) or die("error in Hierarchy Menu :".mysqli_error());
	$hierdata = array();
	$i=0;
	if(mysqli_num_rows($resultData)>0){
		while($rowdata = mysqli_fetch_assoc($resultData)){
			$hierdata[$i] = $rowdata["design_name"]; 
			$i++;
		}//while
	}
	//echo "*********************** :".$user_design."<br>";
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
	 <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/home.php');javascript:printHeader(' ');" >Admin</a>
       <ul>
         <li>
           <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/employeeMenu.php');javascript:printHeader(' ');" ><b>Employees</b></a>
           <ul>
             <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','designation/designation.php?status=true');javascript:printHeader('Designation List');">
               	  Designation List
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
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/userMenu.php');javascript:printHeader(' ');" ><b>Users</b></a>                 
            <ul>
              <li>
                <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=LEVEL5');javascript:printHeader('Sales Force Admin');">
                  Admin
                </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=LEVEL1');javascript:printHeader('Sales Force Admin');">
                 Sales Force
               </a>
              </li>
              <hr color="#B2B2B2" size="1px"/>
              <li>
               <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','user/user.php?status=true&repstatus=LEVEL2');javascript:printHeader('Web Users Admin');">
                 Web Users
               </a>
              </li>
            </ul>
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','unit/unit.php?status=true');javascript:printHeader('Unit Admin');" >
          	<b>Unit</b></a>                  
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','task/task.php?status=true');javascript:printHeader('Task Admin');" >
          	<b>Tasks</b></a>                  
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','category/category.php?status=true');javascript:printHeader('Category Admin');" >
          	<b>Category</b></a>                  
          </li>
          <hr color="#B2B2B2" size="1px"/>
          <li>
          <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','products/product.php?status=true');javascript:printHeader('Products Admin');" ><b>Products</b></a>          </li>
       	  <hr color="#B2B2B2" size="1px"/>
          <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','vendors/vendor.php?a=&status=true&dstatus=false');javascript:printHeader('Vendor Admin');">
                   <b>Vendors</b>
             </a>
          </li>
         
           <hr color="#B2B2B2" size="1px"/>
           <li>
           		<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','customer/customer.php?status=true');javascript:printHeader('Customer Admin');">
           			<b>Customer</b>
                </a>
           </li>
          
       </ul>
     </li>
   
     <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/inventoryMenu.php');javascript:printHeader('Inventory');" >Inventory</a>
       <ul>
		  <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','purchase/purchase.php?status=true');javascript:printHeader('Purchase Admin');">
             <b>Purchase</b>
             </a>
          </li>
          
          <hr color="#B2B2B2" size="1px"/>
          <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','stock/stock.php?status=true');javascript:printHeader('Stock Admin');">
             <b>Stock</b>
             </a>
          </li>
       </ul>
     </li>
     <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/settingsMenu.php?status=true');javascript:printHeader('Setting');" >Settings</a>
       <ul>
       	 
          <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','cell/cell.php?status=true');javascript:printHeader('Cell Info Admin');">
             Cell-ID Info
             </a>
          </li>
          <hr color="#B2B2B2" size="1px"/>
           <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/company.php?status=true');javascript:printHeader('Company Info');">
             Company Info
             </a>
          </li>
          <hr color="#B2B2B2" size="1px"/>
           <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/ginfo.php?status=true');javascript:printHeader('General Terms Info');">
             General Terms Info  
             </a>
          </li>
          <hr color="#B2B2B2" size="1px"/>
           <li>
             <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','company/esettings.php?status=true');javascript:printHeader('Email Settings');">
             Email Settings  
             </a>
          </li>
       </ul>
     </li>
     <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','bulkmail/bulkmail.php?status=true');javascript:printHeader('Bulk Email');" >Bulk-email</a></li>
     
     <?php
		}
	 ?>
    
      <?php
		if($user_design=='ADMIN' or $user_design =='WEBUSER')
		{
		?>
        	
            <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/inboxMenu.php?status=true');javascript:printHeader(' ');" >Sales</a>
              <ul>
                   <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','lead/lead.php?status=true');javascript:printHeader('Lead Booking');">
                            Lead Booking
                     </a>
                    </li>
                	<hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','proposal/proposal.php?status=true'); javascript:printHeader('Proposal');">
                          Proposal
                      </a>
                     
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li><a href="#"><b>Accepted</b></a>
                       <ul>
       	 				<li>
                        	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/payment.php?status=true'); javascript:printHeader('Payment Info');">
                          	Payment
                      		</a>
                        </li>
                        <hr color="#B2B2B2" size="1px"/>
                        <li>
                        	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','accept/task.php?status=true'); javascript:printHeader('Task Info');">
                          	Task
                      		</a>
                        </li>
                       </ul>
                        
                    </li>
                    <hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','completed/completed.php?status=true'); javascript:printHeader('Completed Lead');">
                          Completed
                      </a>
                    </li>
                  	<hr color="#B2B2B2" size="1px"/>
                    <li> 
                      <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reject/reject.php?status=true'); javascript:printHeader('Rejected Lead');">
                          Rejected
                      </a>
                    </li>
                 
                
              </ul>
            </li>
            <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/inboxMenu.php?status=true');javascript:printHeader(' ');" >Lead Follow Up</a>
              <ul>
                 <li>
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/followup.php?status=true');javascript:printHeader('Follow Up Leads');">
                    Today's Leads
                    </a>
                 </li>
                 <hr color="#B2B2B2" size="1px"/>
                 <li> 
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/futureleads.php?status=true'); javascript:printHeader('Future Leads');">
                       Future Leads
                    </a>
                 </li>
                 <hr color="#B2B2B2" size="1px"/>
                 <li> 
                 	<a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','followup/noupdateleads.php?status=true'); javascript:printHeader('Not Updated Leads');">
                       Not Updated Leads
                    </a>
                 </li>
              </ul>
            </li>  
         <?php } ?>
           <li><a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','menufolder/inboxMenu.php?status=true');javascript:printHeader(' ');" >Reports</a>
           		<ul>
                   <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/lead.php?status=true');javascript:printHeader('Lead Wise Reports');">
                            Lead Wise
                     </a>
                   </li>
                   <hr color="#B2B2B2" size="1px"/>
                   <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/lead.php?status=true');javascript:printHeader('Lead Wise Reports');">
                            Sales Wise
                     </a>
                   </li>
                   <hr color="#B2B2B2" size="1px"/>
                   <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/lead.php?status=true');javascript:printHeader('Lead Wise Reports');">
                            Purchase Wise
                     </a>
                   </li>
                   <hr color="#B2B2B2" size="1px"/>
                   <li>
                     <a href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/lead.php?status=true');javascript:printHeader('Lead Wise Reports');">
                            Stock
                     </a>
                   </li>
            	</ul>
           </li>
           </ul>
           </div>
        <script type="text/javascript">
			expandFirstItemAutomatically = 1;
			expandMenuItemByUrl = true;
			initSlideDownMenu();
		</script>
