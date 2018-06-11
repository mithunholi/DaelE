<?php
session_start();
if(isset($_SESSION['User_Name']) and $_SESSION['User_Name']!=""){
 	require_once("config.php");
	$quoteno = $_GET["quotation_no"];
	$leadid = $_GET["lead_no"];
	$templateid = $_GET["tempid"];
	$filterFrame="";
   	$filterFrame.= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
						<html xmlns='http://www.w3.org/1999/xhtml'>
						<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
										
						<title>Quotation</title>
						</head>

						<body>";
	$filterFrame .= "<table><tr><td width='5%'>&nbsp;</td><td width='10%'>&nbsp;</td><td width='50%'>&nbsp;</td><td width='10%'>&nbsp;</td><td width='15%'>&nbsp;</td><td width='10%'>&nbsp;</td></tr>";
	$filterFrame .= "<tr><td colspan='6'><h2>Quotation</h2></td></tr>";
	$companyInfo = getCompanyInfo();
	$clientInfo = getClientInfo($leadid);
	$templateInfo = getTemplate($templateid);
	$filterFrame .="<tr><td colspan='3' align='left'>Kind Attn:$clientInfo[0]</td><td colspan='4' align='left'>$companyInfo[0]</td></tr>";
	$filterFrame .="<tr><td colspan='3' align='left'>$clientInfo[1]</td><td colspan='4' align='left'>$companyInfo[1]</td></tr>";
	$filterFrame .="<tr><td colspan='3'>&nbsp;</td><td colspan='4' align='left'>$companyInfo[2]</td></tr>";
	$filterFrame .="<tr><td colspan='3'>&nbsp;</td><td colspan='4' align='left'>$companyInfo[3]</td></tr>";
	$filterFrame .="<tr><td colspan='3'>&nbsp;</td><td colspan='4' align='left'>$companyInfo[4]</td></tr>";
	
	$filterFrame .="<tr><td colspan='3' align='left'>Quote No.:$quoteno</td><td colspan='4'>&nbsp;</td></tr>";
	$filterFrame .= "<tr><td colspan='3' align='left'>DATE :".date('l,F j,Y')."</td><td colspan='4'>&nbsp;</td></tr>";
	$filterFrame .= "<tr><td colspan='7'>------------------------------------------------------------------------------------------------------------------</td></tr>";
	$filterFrame .= "<tr><td align='left'>SL No</td>";
	$filterFrame .= "<td align='left'>Product</td>";
	$filterFrame .= "<td align='left'>Description</td>";
	$filterFrame .= "<td align='left'>Qty</td>";
	$filterFrame .= "<td align='center'>Unit Price</td>";
	$filterFrame .= "<td align='center'>Amount in INR</td></tr>";
	
	$filterFrame .= "<tr><td colspan='7'>-------------------------------------------------------------------------------------------------------------------</td></tr>";
	
	$qry = "select a.prod_name,a.prod_description,b.prod_qty,b.prod_price,b.prod_tax,b.prod_amount from tbl_product a,tbl_lead_child b 
						where a.prod_code=b.prod_code and b.proposal_quote_no='$quoteno' and b.lead_id='$leadid'";
	$res = mysql_query($qry,$conn);
	if(mysql_num_rows($res)>0){
		$i=1;
		$totamt=0;
		while($row = mysql_fetch_assoc($res)){
			$prodamt = $row['prod_amount'] + ($row['prod_amount'] * ($row['prod_tax']/100));
			$filterFrame .= "<tr><td>$i</td><td>".$row['prod_name']."</td>";
			$filterFrame .= "<td>".$row['prod_description']."</td><td>".$row['prod_qty']."</td><td>".$row['prod_price']."</td><td>".$prodamt."</td>";
			$filterFrame .= "</tr>"	;
			$totamt = $totamt + $prodamt;
			$i++;
		}
		$filterFrame .= "<tr><td align='left'>Total:</td><td align='right' colspan='5'>$totamt</td></tr>";
		$filterFrame .= "<tr><td colspan='7'>-------------------------------------------------------------------------------------------------------------------</td></tr>";
		$filterFrame .= "<tr><td align='left' colspan='7'>TOTAL INDIAN RUPEES IN WORDS :<b>".strtoupper(convert_number_to_words($totamt))." ONLY</b></td></tr>";
		$filterFrame .= "<tr><td align='left' colspan='7'>&nbsp;</td></tr>";
		$filterFrame .= "<tr><td align='left' colspan='7'><i>GENERAL TERMS AND CONDITIONS:</i></td></tr>";
		$filterFrame .= "<tr><td align='left' colspan='7'>".$templateInfo."</td></tr>";
	}
	$filterFrame .= "</table>";
	$filterFrame .= "</body>";
	$filterFrame .= "</html>";
	
	
	header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=quotation.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
	print $filterFrame;
	exit;
}else{
	print "<script>window.location.href='../index.html';</script>";	
}
?>
	
	
<?php	

function getTemplate($id){
	$tqry = "select info_desc from tbl_template where info_id='$id'";
	$tres = mysql_query($tqry);
	$trow = mysql_fetch_array($tres, MYSQL_NUM);
	return $trow[0];
}
function getClientInfo($lid){
	$lqry = "select a.customer_cperson,a.customer_mobno from tbl_customer a,tbl_lead_master b where a.customer_id=b.cust_code and b.lead_id='$lid'";
	$lres = mysql_query($lqry);
	$lrow = mysql_fetch_array($lres, MYSQL_NUM);
	$cinfo = array();
	$cinfo[0]=$lrow[0];
	$cinfo[1]=$lrow[1];
	return $cinfo;	
}


function getCompanyInfo(){
	$query = "select comp_name,comp_add,concat(comp_city,'-',comp_zip) city,comp_email,comp_mobile from tbl_master";
	$resultset = mysql_query($query);
	$rowset = mysql_fetch_assoc($resultset);
	$compinfo = array();
	$compinfo[0]=$rowset["comp_name"];
	$compinfo[1]=$rowset["comp_add"];
	$compinfo[2]=$rowset["city"];
	$compinfo[3]=$rowset["comp_email"];
	$compinfo[4]=$rowset["comp_mobile"];
	return $compinfo;
}

function convert_number_to_words($number) {
   
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
    return $string;
}
?>
	