<?php
session_start();
if($_GET["status"]==true){
		global $ccount;
		global $bdata;
		global $distributorid;
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["regionId"]="";
	$_SESSION["areaId"]="";
	$_SESSION["outletId"] = "";
	$_SESSION["routeId"] = "";
	$_SESSION["distId"] = "";
	$_SESSION["salesId"] = "";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["pageId"]="";
	$_SESSION["btnstatus"] = "";
	$_SESSION["bdata"] ="";
	$status = false;
	$btnstatus = false;
	$distId = "0";
	$salesId = "0";
	$fromdate = date("d-m-Y");
}
  
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
	require_once("../config.php");
	require_once("../library/functions.php");
	$user_name=$_SESSION['User_ID'];
	if($_GET["a"]=="reset"){
	 	$fromdate = date("d-m-Y");
		$todate = date("d-m-Y");
		$salesId= "0";
		$distId = "0";
	}
	if(isset($_GET["fromdate"])) $fromdate = $_GET["fromdate"];
	if(isset($_GET["todate"])) $todate = $_GET["todate"];
	if(isset($_GET["salesId"])) $salesId = $_GET["salesId"];
	if(isset($_GET["distId"])) $distId = $_GET["distId"];
	if (!isset($fromdate) && isset($_SESSION["fromdate"])) $fromdate = $_SESSION["fromdate"];
	//if (!isset($todate) && isset($_SESSION["todate"])) $todate = $_SESSION["todate"];
	if (!isset($salesId) && isset($_SESSION["salesId"])) $salesId = $_SESSION["salesId"];
	if (!isset($distId) && isset($_SESSION["distId"])) $distId = $_SESSION["distId"];
	if (isset($fromdate)) $_SESSION["fromdate"] = $fromdate;
	//if (isset($todate)) $_SESSION["todate"] = $todate;
	if (isset($salesId)) $_SESSION["salesId"] = $salesId;
	if (isset($distId)) $_SESSION["distId"] = $distId;
	// echo "From Date :".$fromdate."=== To Date :".$todate."==Salesman ID :".$salesId."==Dist ID :".$distId."<br>";
	 $salesmanList = buildUserOptionsRep($salesId);
	 if($salesId != "0"){
	 	//echo "From Date :".$fromdate."=== To Date :".$todate."==Salesman ID :".$salesId."==Dist ID :".$distId."<br>"; 
		$fromdate = date("d-m-Y",strtotime($fromdate));
	 	$distributorList = buildAssignedDistributor($salesId,$distId,$fromdate);
	 }
  ?>
  
   <html>
		<head>
			<title>mCRM -- Assign Distributor List</title>
			<meta name="generator" http-equiv="content-type" content="text/html">
 			<link href="main.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="../CRM.js"></script>
           
		</head>
		<body>
        <form name="frmFilter" action="" method="post">
		<table border="1" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
        	<tr>
          		<td align="left" width="10%"><b>From Date:</b></td>
            	<td align="left" width="88%"><input type="text" name="fromdate" id="fromdate" value="<?php echo $fromdate; ?>">
             		<a href="javascript:show_calendar('document.frmFilter.fromdate', document.frmFilter.fromdate.value);">
                    	<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the timestamp">
                    </a>
               	</td>
             </tr>
             <tr>  
             	<td align="left" width="98%" colspan="2">
                  <table>
                    <tr>
                    	<td><b>Salesman :</b></td>
             			<td align="left">
                			<select name="cboSalesman" class="box" id="cboSalesman" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dsrsalesman.php?salesId='+this.value+'&fromdate='+document.frmFilter.fromdate.value)">
     							<option value="0" selected>Choose Salesman</option>
								<?php echo $salesmanList; ?>
   							</select>
						</td>
                    	<?php if($salesId != "0"){ ?>
                    		<td><b>Distributors :</b></td>
                    		<td>
         <select name="cboDistributor" class="box" id="cboDistributor" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dsrsalesman.php?distId='+this.value+'&fromdate='+document.frmFilter.fromdate.value)">
                    		
                                	<option value="0" selected>All Distributors</option>
                                	<?php echo $distributorList; ?>
                           
								</select>
                    		</td>
                    <?php }?>
                   </tr>
				 </table>
               </td>
    	     </tr>
             
             <tr>  
             	<td align="left" width="98%" colspan="2">
				<input type="button" name="action" value="Apply" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dsrsalesman.php?salesId=<?php echo $salesId;?>&distId=<?php echo $distId; ?>&fromdate='+document.frmFilter.fromdate.value)" >
				<input type="button" name="action" value="Reset" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/dsrsalesman.php?a=reset')">
                </td>
		     </tr>
			
		</table>
	</form>
	<br>
<?php
	//printHeaders();
	if($salesId != "0"){
		showAllSalesmans($salesId,$distId,$fromdate);			
	}else{
		//showSearchSalesmans($salesId);
	}

}else{
	print "<script>window.location.href='../index.html';</script>";	
}

 ?>
 
 
 
 
<?php
//********** USER DEFINED FUNCTIONS ******************* 


function showAllSalesmans($sid,$did,$fromdate)
{
	
		//echo "Sales ID:".$sid."==Distributor ID:".$did."<br>";
	$fromdate = date("d-m-Y",strtotime($fromdate));
	if($did == "0"){
		$sql = "select * from (select a.org_dist_id, b.dist_name,a.route_id,
				concat(c.route_code,'-',c.route_name) route_name,a.region_code,a.area_code
		 		FROM tbl_assigntp a,tbl_distributor b,tbl_route c 
				WHERE a.org_dist_id=b.dist_id and a.route_id=c.route_id and a.user_id = '$sid' 
				and date_format(a.routeday,'%d-%m-%Y') = '$fromdate' group by a.org_dist_id) subq order by org_dist_id";
	}else{
		$sql = "select * from (select  a.org_dist_id, b.dist_name,a.route_id,
				concat(c.route_code,'-',c.route_name) route_name,a.region_code,a.area_code
		 		FROM tbl_assigntp a,tbl_distributor b,tbl_route c 
				WHERE a.org_dist_id=b.dist_id and a.route_id=c.route_id and a.user_id = '$sid' 
				and a.org_dist_id = '$did' and date_format(a.routeday,'%d-%m-%Y') = '$fromdate' group by a.org_dist_id) subq order by org_dist_id";
	}
	//echo "SQL :".$sql."<br>";
	$mainResult = mysql_query($sql) or ("Error in Assign TP:".mysql_error());
	if(mysql_num_rows($mainResult)>0){
		while($mainRow = mysql_fetch_assoc($mainResult)){
			$distcode = getDistributorCode($mainRow["org_dist_id"]);
			//************************************ Print Top Headings *****************************************************************
			echo "<table border='1' cellspacing='0' cellpadding='4' width='100%' bgcolor='#EAEAEA'>";
			echo "<tr><td width='15%' align='left'>SALESMAN :</td><td align='left' width='25%'>".getSalesmanNames($sid)."</td>";
			echo "<td width='10%' align='left'>REGION :</td><td align='left' width='20%'>".getRegionName($mainRow["region_code"])."</td>";
			echo "<td width='10%' align='left'>DATE :</td><td align='left' width='20%'>".$fromdate."</td></tr>";
			//echo "<tr><td width='20%'>AREA :</td><td>".getAreaName($sid)."</td></tr>";
			echo "<tr><td widht='15%' align='left'>DISTRIBUTOR :</td><td width='25%' align='left'>".$distcode."-".$mainRow["dist_name"] ."</td>";
			echo "<td width='10%' align='left'>AREA :</td><td width='20%' align='left' colspan='3'>".getAreaName($mainRow["area_code"])."</td>";

			
			echo "</tr></table>";
		
		
			$did = $mainRow["org_dist_id"];
			
			/**************************** Print Heading -- Category with Product List****************************************************************************/
			echo "<table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>";
			echo "<tr class='hr'><td align='left' rowspan='2' width='5%'>SNo</td><td align='left' rowspan='2' width='20%'>DEALERS</td>";
			echo "<td align='left' rowspan='2' width='20%'>ROUTE NAME</td>";
			
			$cat_query = "select * from tbl_categories order by cat_code";
			$cat_result = mysql_query($cat_query) or die("Error in Categories :".mysql_error());
			if(mysql_num_rows($cat_result)>0){
				$catDatas = array();
				$prodDatas = array();
				$cntDatas = array();
				$prodCode = array();
				$i=0;
				$catcnt = 1;
				while($cat_row = mysql_fetch_assoc($cat_result)){
					$catDatas[$i][0] = $cat_row["cat_name"];
					$catDatas[$i][1] = $cat_row["cat_code"];
					
					$prod_query = "select * from tbl_product where cat_code='".$catDatas[$i][1]."' order by prod_code";
					//echo "Prod Quyery :".$prod_query."<br>";
					$prod_result = mysql_query($prod_query);
					if(mysql_num_rows($prod_result)>0){
						$j=0;
						while($prod_row = mysql_fetch_assoc($prod_result)){
							$prodDatas[$j][$i] = $prod_row["prod_sname"];
							$prodCode[$j][$i] = $prod_row["prod_code"];
							//echo "Cat Datas :".$catDatas[$i][0]." = Prod Datas :".$prodDatas[$j][$i]."<br>";
							$cntDatas[$i] = $j;
							$j++;
						}		
						
					}
					$i++;
					$catcnt++;
				}//while
			}//if
			
			//echo "Size of CatData :".sizeof($catDatas)." = Size of ProdData :".sizeof($prodDatas)." = Cat Cout :".$catcnt."<br>";
			echo "<td align='left' rowspan='2'>OS-OB</td>";
			if(sizeof($catDatas) > 0){
				for($i=0;$i<sizeof($catDatas);$i++){
					$colsize = $cntDatas[$i] + 1;
					$colwidth = $colsize * 15;
					echo "<td colspan='$colsize' align='center' width='$colwidth%'>".$catDatas[$i][0]."</td>";
				}//for
				echo "<td align='left' rowspan='2' width='15%'>BILL AMOUNT</td>";
				echo "</tr>";
				echo "<tr>";
				
				$columnNos = 1;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					//echo "Cntr :".$i."===Size of Product :".($cntDatas[$i]+1)."<br>";
					while($j<($cntDatas[$i]+1)){
						echo "<td align='left'>".$prodDatas[$j][$i]."</td>";
						$j++;	
						$columnNos++;
					}
				}//for
				
				echo "</tr>";
				//echo "</table>";
			}//if
	
	/*************** End of the Print Heading Category with Product List*****************************************************************************************/
		
		
		
		
	/************************* Start to Print Outlet List ******************************************************************************************************/	
			if($did != '0'){
				$subSQL = "select a.outlet_id,a.outlet_name,concat(b.route_code,'-',b.route_name) routename from tbl_outlet a,tbl_route b 
							where a.route_id=b.route_id and a.dist_id = '$did' group by a.outlet_id";
			}else{
				$subSQL = "select a.outlet_id,a.outlet_name,concat(b.route_code,'-',b.route_name) routename from tbl_outlet a,tbl_route b 
							where a.route_id=b.route_id group by a.outlet_id";
			}
			//echo "SUB SQL :".$subSQL."<br>";
			$subResult = mysql_query($subSQL);
			if(mysql_num_rows($subResult)>0){
				$rec =1;
				$totqty = array();
				$totOSQty = array();
				$grandtotal = 0;
				while($subRow = mysql_fetch_assoc($subResult)){
					$style = "dr";
    				if ($rec % 2 != 0) {
      					$style = "sr";
    				}
					$oid = $subRow["outlet_id"];
					$oname = $subRow["outlet_name"];
					echo "<tr><td width='5%' align='left' rowspan='2'>".$rec."</td>";
					echo "<td width='20%' align='left' rowspan='2'>".strtoupper($subRow["outlet_name"])."</td>";
					echo "<td width='20%' align='left' rowspan='2'>".strtoupper($subRow["routename"])."</td>";
					echo "<td class='srhighlight2'>OS</td>";
					$totamount = 0;
					
					/*$stockQuery = "select sum(qty) qty
											from tbl_openstock where date_format(odate,'%d-%m-%Y') = '$fromdate' 
											and ordertype='Sales' and prodname='$pname' and outletname='$oname' GROUP BY prodname";
							$stockResult = mysql_query($stockQuery);*/
					/******************************** Print Opening Stock ***********************************************/
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						//$totqty[$i][
						//echo "Cntr :".$i."===Size of Product :".($cntDatas[$i]+1)."<br>";
						while($j<($cntDatas[$i]+1)){
							
							if(!isset($totOSQty[$i][$j]) && $totOSQty[$i][$j] == ""){ 
								$totOSQty[$i][$j] = 0; 
								//echo "First [$i][$j]:".$totqty[$i][$j]."<br>";
							}
							$pname = $prodDatas[$j][$i];
							$pid = $prodCode[$j][$i];
							$stockQuery = "select sum(qty) qty
											from tbl_openstock a,tbl_openstock_child b where a.orderid=b.orderid 
											and date_format(b.rec_date,'%d-%m-%Y') = '$fromdate' 
											and a.prod_id='$pid' and b.outletid='$oid' GROUP BY a.prod_id";
							$stockResult = mysql_query($stockQuery);
							//echo "Child Query :".$stockQuery."<br>";
							
							if(mysql_num_rows($stockResult)>0){
								$stockRow = mysql_fetch_assoc($stockResult);
								echo "<td class='srhighlight2'>".$stockRow["qty"]."</td>";
								//$totamount = $totamount + $stockRow["amount"];
								
								$totOSQty[$i][$j] = $totOSQty[$i][$j] + $stockRow["qty"];
								//echo "Total Qty [$i][$j]:".$totqty[$i][$j]."<br>";
							}else{
								echo "<td class='srhighlight2'>-</td>";
							}//if
							$j++;	
						}//while loop
					}//for loop
					echo "<td align='right'>&nbsp;</td></tr>";
					echo "<tr>";
					echo "<td>OB</td>";
					/***********************************Print Secondary Order Booking *****************************************/		
					for($i=0;$i<sizeof($catDatas);$i++){
						$j=0;
						//$totqty[$i][
						//echo "Cntr :".$i."===Size of Product :".($cntDatas[$i]+1)."<br>";
						while($j<($cntDatas[$i]+1)){
							
							if(!isset($totqty[$i][$j]) && $totqty[$i][$j] == ""){ 
								$totqty[$i][$j] = 0; 
								//echo "First [$i][$j]:".$totqty[$i][$j]."<br>";
							}
							$pname = $prodDatas[$j][$i];
							$pid = $prodCode[$j][$i];
							$childQuery = "select sum(a.qty) qty,sum(a.amount) amount
											from tbl_orderbook a,tbl_orderbook_child b where a.orderid=b.orderid 
											and date_format(b.order_date,'%d-%m-%Y') = '$fromdate' 
											and b.ordertype='SALES' and b.transit_status='TRANSIT'
											and a.prod_id='$pid' and b.outletid='$oid' GROUP BY a.prod_id";
							//echo "Child Query :".$childQuery."<br>";
							$childResult = mysql_query($childQuery);
							if(mysql_num_rows($childResult)>0){
								$childRow = mysql_fetch_assoc($childResult);
								echo "<td>".$childRow["qty"]."</td>";
								$totamount = $totamount + $childRow["amount"];
								
								$totqty[$i][$j] = $totqty[$i][$j] + $childRow["qty"];
								//echo "Total Qty [$i][$j]:".$totqty[$i][$j]."<br>";
							}else{
								echo "<td>-</td>";
							}//if
							$j++;	
						}//while loop
					}//for loop
					echo "<td align='right'>".number_format($totamount,2,'.',',')."</td></tr>";
					$grandtotal = $grandtotal + $totamount;
					$rec++;
				}//while loop
				
				//************* Print Total OS Qty *******************
					echo "<tr>";
				  	echo "<td width='20%' align='left' colspan='3' rowspan='2'>Total Qty</td>";
					echo "<td class='srhighlight2'>OS</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
					 echo "<td class='srhighlight2'>".$totOSQty[$i][$j]."</td>";
					 $j++;
					}
					
				}
				echo "<td>&nbsp;</td></tr>";
				
				//************* Print Total OB Qty *******************
					echo "<tr>";
				  	echo "<td>OB</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
					 echo "<td>".$totqty[$i][$j]."</td>";
					 $j++;
					}
					
				}
				echo "<td>".number_format($grandtotal,2,'.',',')."</td></tr>";
				
			}else{
				//echo "Size of Cat :".$columnNos."<br>";
				$z=$columnNos+3;
				
				echo "<tr><td colspan='$z'>No Assigned Outlet</td></tr>";
			}
			echo "</table>";
			echo "<table border='1'>";
			$diid = $mainRow["org_dist_id"];
			$im = getImeiNumber($sid);
			//Market Report Message
			$marketrep = getShortMsg($diid,$im,'Market',$fromdate);
			echo "<tr><td width='40%'>Market Report :</td>";
			//Competitor Message
			$competitorrep = getShortMsg($diid,$im,'Competitor',$fromdate);
			echo "<td width='40%'>Competitor Activities:</td>";
			//General Message
			$generalrep = getShortMsg($diid,$im,'General',$fromdate);
			echo "<td width='40%'>General Comments:</td>";
			echo "</tr>";
			echo "<tr><td>".$marketrep."</td>";
			echo "<td>".$competitorrep."</td>";
			echo "<td>".$generalrep."</td>";
			echo "</tr>";
			
			echo "</table>";
			echo "<br>";
		}//while
	}//if
	else{
				echo "<table border='0' cellspacing='0' cellpadding='4' width='100%'>";
				echo "<tr><td><b>No Data Found</b></td></tr>";
				echo "</table>";
	}
}


function getImeiNumber($sid){
	$qry = "select imei_no from tbl_user where user_id='$sid'";
	//echo "IMEI :".$qry."<br>";
	$result = mysql_query($qry) or die("Error in User :".mysql_error());
	$row = mysql_fetch_assoc($result);
	return $row["imei_no"];
	
}
function getShortMsg($did,$imei,$act,$day){
		$queryset = "select a.* from tbl_shortmsg a,tbl_user b 
					where a.user_id=b.user_id and a.distid = '$did' and b.imei_no = '$imei' and a.activity like '$act%' and date_format(a.rec_date,'%d-%m-%Y')='$day'";
		//echo $queryset."<br>";
		$shtresult = mysql_query($queryset) or die("Error in Short Msg :".mysql_error());
		if(mysql_num_rows($shtresult)>0){
			$shtrow = mysql_fetch_assoc($shtresult);
			return $shtrow["message"];
		}else{
			return "-";
		}

}




function primarykeycondition()
{
  	global $_POST;
  	$pk = "";
  	$pk .= "(`outlet_id`";
  	if (@$_POST["xoutlet_id"] == "") {
    	$pk .= " IS NULL";
  	}else{
  		$pk .= " = " .sqlvalue(@$_POST["xoutlet_id"], false);
  	};
  	$pk .= ")";
  	return $pk;
}


function getSalesmanNames($uid){
	global $conn;
	$usql = "select a.emp_dname empname from tbl_employee a,tbl_user b where b.Title = a.emp_id and b.user_id='$uid'";
	$ures = mysql_query($usql,$conn) or die(mysql_error());
	$urow = mysql_fetch_assoc($ures);
	return $urow["empname"];
}
/*function getAreaNames($acode){
	global $conn;
	$asql = "select a.area_desc1 areaname from tbl_area a where a.area_name='$acode'";
	$ares = mysql_query($asql,$conn) or die(mysql_error());
	$arow = mysql_fetch_assoc($ares);
	return $arow["areaname"];
}

function getRegionNames($rcode){
	global $conn;
	$rgsql = "select region_desc from tbl_region where region_name='$rcode'";
	$rgres = mysql_query($rgsql,$conn) or die(mysql_error());
	$rgrow = mysql_fetch_assoc($rgres);
	return $rgrow["region_desc"];
}*/
function getRouteNames($rId)
{
	global $conn;
	if(strpos($rId,",")== false){
		$rsql = "select concat(route_code,'-',route_name) routename from tbl_route where route_id = '$rId'";
		$rresult = mysql_query($rsql,$conn);
		$rrow = mysql_fetch_assoc($rresult);
		$rdata = $rrow["routename"];
	}else{
		$rsql = "select concat(route_code,'-',route_name) routename from tbl_route where route_id in ($rId)";
		//echo "R SQL :".$rsql."<br>";
		$rresult = mysql_query($rsql,$conn);
		$rdata="";
		while($rrow = mysql_fetch_assoc($rresult)){
			$rdata .= $rrow["routename"].",";
		}
		$rdata = substr($rdata,0,strlen($rdata)-1);
	}
	
	return $rdata;
}
/****************** End of the USER DEFINED Functions ***********************************/
?>
