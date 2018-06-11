<?php
session_start();
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
?>
 <html>
 <head>
 <meta name="generator" http-equiv="content-type" content="text/html; charset=utf-8">
 <title>mCRM -- Stock Rotation</title>
	<link href="main.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="CRM.js"></script>
	<script type="text/javascript" src="calendar.js"></script>

 </head>
 <body>
 
 <?php
	require_once("../config.php");
  	require_once('../library/functions.php');
	/*echo "Distributor ID :".$_GET['did']."<br>";
	echo "USer ID :".$_GET['uid']."<br>";
	echo "From date :".$_GET['fromdate']."<br>";
	echo "To Date :".$_GET['todate']."<br>";
	*/
	$userid = $_GET['uid'];
	$distid = $_GET['did'];
	$fdate = $_GET['fromdate'];
	$tdate = $_GET['todate'];
	$fmdate = $_GET['fmdate'];
	//echo "FDATE :".$fdate."<
	if($userid <> "" and $userid <> "0"){
	
		$grantSSqty = array();
		/**************************** Print Heading -- Category with Product List****************************************************************************/
		?>
		<table class='tbl1' border='0' cellspacing='0' cellpadding='4'>
		<tr><td><input type='button' name='btnBack' value='Back' onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','reports/stkreport.php?distId=<?php echo $distid;?>&fromdate=<?php echo date('d-m-Y',strtotime($fmdate));?>&todate=<?php echo date('d-m-Y',strtotime($tdate)); ?>')"></td></tr>
        </table>
        <table class='tbl1' border='1' cellspacing='0' cellpadding='4' bgcolor='#EAEAEA'>
		<tr class='hr'><td align='left' width='15%' rowspan='2'>Distributor</td><td align='left' rowspan='2'>Day</td><td rowspan='2'>Status</td>
        <?php
		/******************************* Collect categories with respect to Product List ****************************************/
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
						$cntDatas[$i] = $j;
						$j++;
					}		
				}
				$i++;
				$catcnt++;
			}//while
		}//if
			
		/***************************** Printing Start Here ***********************************************/
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
				while($j<($cntDatas[$i]+1)){
					echo "<td align='left'>".$prodDatas[$j][$i]."</td>";
					$j++;	
					$columnNos++;
				}
			}//for
			echo "</tr>";
		}//if
		
		$ffdate = date("d-m-Y",strtotime($fdate));
		/***************************** Print Secondary Sales Qties based on date ************************************/
		$dist_query ="select * from (select a.org_dist_id, concat(concat(c.region_name,'',d.area_name),'-',b.dist_name) org_dist_name,a.route_id,
						a.region_code,a.area_code FROM tbl_assigntp a,tbl_distributor b,tbl_region c,tbl_area d
						WHERE a.org_dist_id=b.dist_id and a.region_code=c.region_id and a.area_code=d.area_id and a.org_dist_id='$distid'
						and a.user_id = '$userid' and date_format(a.routeday,'%d-%m-%Y') = '$ffdate' group by a.org_dist_id)subq order by org_dist_id";
		
					
		//echo $dist_query."<br>";
		$dist_result = mysql_query($dist_query,$conn);
		$prev = "";
		if(mysql_num_rows($dist_result)>0){
			while($dist_row = mysql_fetch_assoc($dist_result)){
				$distid = $dist_row["org_dist_id"];
				$distname = $dist_row["org_dist_name"];
				/**************************** To find last closing stock date *********************************************/
				$dspdate = date("Y-m-d",strtotime($fdate));
				//echo "DSP DATE :".$dspdate."<br>";
				$dateparts = explode("-",$dspdate);
				//print_r($dateparts);
				
				$d = mktime(0,0,0,$dateparts[1],$dateparts[2],$dateparts[0]);
				$end_date = date("d-m-Y",strtotime("+1 days",$d));
				$rdate = $end_date;
				echo "<tr class='<?php echo $style ?>'>";
				if($prev != $distname){
					$prev = $distname;
					echo "<td rowspan='5'>".$distname."</td>";
				}else{
					echo "<td rowspan='5'>&nbsp;</td>";
				}
				$transitDataAmt=0;
				$primaryInvoiceAmt =0;
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					$totSSamt = 0;
					while($j<($cntDatas[$i]+1)){
						$totSSqty[$i][$j] = 0; 
						$availstock[$i][$j] = 0; 
						$totalQty[$i][$j] = 0; 
						
						
						
						$pid = $prodCode[$j][$i];
						if($fdate != ""){
							$priCSDatas = primaryOpeningStock($pid,$userid,$distid,$fdate );
							$priCSData[$i][$j][0] = $priCSDatas[0];
							$priCSData[$i][$j][1] = $priCSDatas[1];
							$TransitData = primaryTransit($pid,$userid,$distid,$fdate );
							$transitData[$i][$j][0] = $TransitData[0];
							$transitData[$i][$j][1] = $TransitData[1];
					
							
						}else{
							$priCSData[$i][$j][0] = 0;
							$priCSData[$i][$j][1] = "-";
							$transitData[$i][$j][0]=0;
							$transitData[$i][$j][1]=0;
						
						}
						
						$stockinhand[$i][$j] = $priCSData[$i][$j][0] + $transitData[$i][$j][0];
						$transitDataAmt = $transitDataAmt + $transitData[$i][$j][1];
						if($rdate != '0'){
							//echo "From Date :".$fdate."=Next Date :".$rdate."=To Date :".$todate."<br>";
							$primaryInvoiceData = primaryInvoiceData($pid,$userid,$distid,$rdate,$tdate);
							$primaryInvoice[$i][$j][0] = $primaryInvoiceData[0];
							$primaryInvoice[$i][$j][1] = $primaryInvoiceData[1];
						}else{
							$primaryInvoice[$i][$j][0] = 0;
							$primaryInvoice[$i][$j][1] = 0;
						}
							
						$primaryInvoiceAmt = $primaryInvoiceAmt + $primaryInvoice[$i][$j][1];
						$totalQty[$i][$j] = $primaryInvoice[$i][$j][0] + $stockinhand[$i][$j]; 
						$grantSSqty[$i][$j] = $grantSSqty[$i][$j] + $totalQty[$i][$j]; 
						//echo "<td>".$totalQty."</td>";
						$j++;
					
					}//while
				}//for
				
				echo "<td rowspan='4'>Visit Date -".date("d-m-Y",strtotime($fdate))."</td>";
				echo "<td>Mfg. Date</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td>". $priCSData[$i][$j][1] ."</td>";
						
						$j++;
					}
				}
				echo "<td>&nbsp;</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td>Physical Stock</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($priCSData[$i][$j][0] == "0"){
							echo "<td>-</td>";
						}else{
							echo "<td>". $priCSData[$i][$j][0] ."</td>";
						}
						$j++;
					}
				}
				echo "<td>&nbsp;</td>";
				echo "</tr>";
				/******************** Primary Sales ***********************/
				echo "<tr>";
				echo "<td>Transit</td>";
				
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($transitData[$i][$j][0]=="0"){
							echo "<td>-</td>";
						}else{
							echo "<td>".$transitData[$i][$j][0]."</td>";
						}
						$j++;
					}
				}
				echo "<td>".number_format($transitDataAmt,2,'.',',')."</td>";
				echo "</tr>";
				/******************** Total Stock ******************/
				echo "<tr class='srhighlight2'>";
				
				echo "<td>Total Stock</td>";

				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td>".$stockinhand[$i][$j]."</td>";
						$j++;
					}
				}
				//$grantSSamt = $grantSSamt + $totSSamt; 
				echo "<td>".number_format($transitDataAmt,2,'.',',')."</td>";
				echo "</tr>";
				
				/******************** Remaining Stock = Transit ******************/
				echo "<tr>";
			
				echo "<td>Remaining Days</td>";
				echo "<td>Transit</td>";

				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						if($primaryInvoice[$i][$j][0] == 0){
							echo "<td>-</td>";
						}else{
							echo "<td>".$primaryInvoice[$i][$j][0]."</td>";
						}
						$j++;
					}
				}
				//$grantSSamt = $grantSSamt + $totSSamt; 
				echo "<td>".number_format($primaryInvoiceAmt,2,'.',',')."</td>";
				echo "</tr>";
				
				
				/*******************Closing Stock ********************************/
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td>";
				echo "<td class='srhighlight1'>Closing Stock</td>";
				for($i=0;$i<sizeof($catDatas);$i++){
					$j=0;
					while($j<($cntDatas[$i]+1)){
						echo "<td class='srhighlight1'>".$grantSSqty[$i][$j]."</td>";
						$j++;
					}
					
				}
				$totCSAmt = $primaryInvoiceAmt + $transitDataAmt;
				echo "<td class='srhighlight1'>".number_format($totCSAmt,2,'.',',')."</td></tr>";
			}//while
			echo "</table>";	
		}else{
			 
		}
		
	}//if
	?>
    </body>
    </html>
    <?php
}else{
	print "<script>window.location.href='../index.html';</script>";	
}


function primaryOpeningStock($prodname,$uId,$did,$rddate){
	global $conn;
	$rddate = date("Y-m-d",strtotime($rddate));
	$pcssql = "select * from (select a.prod_id, sum(a.prod_qty) pcsqty,b.rec_date,a.prod_mfd from tbl_cstock a,tbl_cstock_child b
											where a.cstock_id=b.cstock_id and b.user_id='$uId' and b.dist_id='$did' and a.prod_id = '$prodname' 
											and date_format(b.rec_date,'%Y-%m-%d') ='$rddate' 
											group by a.prod_id,b.rec_date) subq order by rec_date desc ";
	//echo "OS :".$pcssql."<br>";
	$pcsresult = mysql_query($pcssql,$conn) or die(mysql_error());
	if(mysql_num_rows($pcsresult)>0){
		$pcsrow = mysql_fetch_assoc($pcsresult);
		$data[0] = $pcsrow["pcsqty"];
		$data[1] = $pcsrow["prod_mfd"];
	}else{
		$data[0] = 0;
		$data[1] = "-";
	}
	return $data;
}

function primaryTransit($pname,$userId,$distid,$rdate){
	global $conn;
	$rdate = date("Y-m-d",strtotime($rdate));
	$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' 
											and a.prod_id = '$pname' and b.transit_status ='TRANSIT'  
											and b.ordertype = 'ORDER BOOKING' and date_format(b.transit_date,'%Y-%m-%d') <= '$rdate' 
											and (b.deliver_status ='FALSE' or (b.deliver_status='DONE' and date_format( b.deliver_date, '%Y-%m-%d' ) > '$rdate')) 
											group by a.prod_id) subq order by prod_id ";
	//echo "Transit :".$accsql."<br>";
	$accresult = mysql_query($accsql,$conn) or die(mysql_error());
	if(mysql_num_rows($accresult)>0){
		$accrow = mysql_fetch_assoc($accresult);
		$data[0] = $accrow["accqty"];
		$data[1] = $accrow["accamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;
}

function primaryDeliver($pname,$userId,$distid,$rdate){
	global $conn;
	$rdate = date("Y-m-d",strtotime($rdate));
	$accsql = "select * from (select a.prod_id, sum(a.qty) accqty,sum(a.amount) accamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' 
											and a.prod_id = '$pname' and b.deliver_status ='DONE'  
											and b.ordertype = 'ORDER BOOKING' and date_format(b.deliver_date,'%Y-%m-%d') <= '$rdate' 
											group by a.prod_id) subq order by prod_id ";
	//echo "Transit :".$accsql."<br>";
	$accresult = mysql_query($accsql,$conn) or die(mysql_error());
	if(mysql_num_rows($accresult)>0){
		$accrow = mysql_fetch_assoc($accresult);
		return $accrow["accqty"];
	}else{
		return "0";
	}
}
function primaryInvoiceData($pname,$userId,$distid,$fddate,$tddate){
	global $conn;
	//echo "From Date :".$fddate."=To Date :".$tddate."<br>";
	$fdate = date("Y-m-d",strtotime($fddate));
	$tdate = date("Y-m-d",strtotime($tddate));
	$invsql = "select * from (select a.prod_id, sum(a.qty) invqty,sum(a.amount) invamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid=b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id='$pname' 
											and b.transit_status='TRANSIT' and b.deliver_status ='FALSE'
											and b.ordertype = 'ORDER BOOKING' and date_format(b.transit_date,'%Y-%m-%d') >= '$fdate' 
											and date_format(b.transit_date,'%Y-%m-%d') <= '$tdate' 
											group by a.prod_id) subq order by prod_id ";
	//echo "SQL :".$invsql."<br>";
	$invresult = mysql_query($invsql,$conn) or die(mysql_error());
	if(mysql_num_rows($invresult)>0){
		$invrow = mysql_fetch_assoc($invresult);
		$data[0] = $invrow["invqty"];
		$data[1] = $invrow["invamt"];
	}else{
		$data[0] = 0;
		$data[1] = 0;
	}
	return $data;	
}

function secondarySales($pname,$userId,$distid,$rdate){
	global $conn;
	$rddate = date("Y-m-d",strtotime($rddate));
	$secsql = "select * from (select a.prod_id, sum(a.qty) secqty,sum(a.amount) secamt from tbl_orderbook a,tbl_orderbook_child b 
											where a.orderid = b.orderid and b.userid='$userId' and b.distid='$distid' and a.prod_id = '$pname' 
											and b.transit_status='TRANSIT' and b.deliver_status = 'FALSE' and b.transit_cancel_status ='FALSE' 
											and b.ordertype = 'SALES' and date_format(b.transit_date,'%Y-%m-%d') = '$rddate' 
											group by a.prod_id) subq order by prod_id ";
	$secresult = mysql_query($secsql,$conn) or die(mysql_error());
	if(mysql_num_rows($secresult)>0){
		$secrow = mysql_fetch_assoc($secresult);
		return $secrow["secqty"];
	}else{
		return "0";
	}

}

function dateDiff($dformat, $endDate, $beginDate)
{
	//echo "StoTime :".strtotime($beginDate). " EtoTime :".strtotime($endDate). " Diff :". strtotime($endDate)-strtotime($beginDate)."<br>";
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}
?>
