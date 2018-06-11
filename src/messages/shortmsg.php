<?php session_start();
  if($_GET["status"]==true){
  	$_SESSION["order"]="";
	$_SESSION["type"]="";
	$_SESSION["filter"]="";
	$_SESSION["filter_field"]="";
	$_SESSION["fromdate"]="";
	$_SESSION["todate"]="";
	$_SESSION["pageId"]="";
	$status = false;
  }
if(isset($_SESSION['yiketil']) and $_SESSION['yiketil']="awoo"){
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_GET["filter"])) $filter = @$_GET["filter"];
  if (isset($_GET["filter_field"])) $filterfield = @$_GET["filter_field"];
  if(isset($_GET["pageId"])) $pageId = $_GET["pageId"]; 

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];
  if (!isset($pageId) && isset($_SESSION["pageId"])) $pageId = $_SESSION["pageId"];
?>
<html>
<head>
<title>mCRM -- Short Msg view</title>
<link href="main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../CRM.js"></script>
</head>
<body>
<?php
  require_once("../config.php");
  require_once('../library/functions.php');
  if($pageId == "" or ((int) $pageId <= 0)){
  	$showrecs = REC_PER_PAGE;
  }else{
  	$showrecs = $pageId;
  }
  $pagerange = PAGE_RANGE;
	
  $a = @$_GET["a"];
 
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  switch ($a) {
    case "view":
	   viewrec($recid);
      break;
	case "del":
	  sql_delete();
	  break;
    default:
	  echo "<script language='javascript'>Test();</script>";
      select();
      break;
  }

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;
  if (isset($pageId)) $_SESSION["pageId"] = $pageId;
  mysql_close($conn); 
?>
<table class="bd" width="100%"><tr><td class="hr"></td></tr></table>
</body>
</html>
<?php 
}else{
	print "<script>window.location.href='../index.html';</script>";		
}
?>


<?php 
/******************************** USER DEFINED FUNCTIONS ***************************************/
function select()
{
  global $a;
  global $showrecs;
  global $page;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $order;
  global $ordtype;
  global $pagerange;
 
  if ($a == "reset") {
    $filter = "";
    $filterfield = "";
    $wholeonly = "";
    $order = "";
    $ordtype = "";
  }

  $checkstr = "";
  if ($wholeonly) $checkstr = " checked";
  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);

?>

  <form action="" method="post">
    <table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
      <tr>
	    <td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
          <input type="text" name="filter" value="<?php echo $filter ?>">
			<select name="filter_field">
			<option value="">All Fields</option>
		
            <option value="<?php echo "rec_date" ?>"<?php if ($filterfield == "rec_date") { echo "selected"; } ?>><?php echo htmlspecialchars("Receive Date") ?>
            </option>
			</select>
         <input type="button" name="action" value="Apply Filter" onClick="javascript:SearchData(this.form,'messages/shortmsg.php?a=filter')">
		 <input type="button" name="action" value="Reset Filter" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=reset')"></td>
	  </tr>
    </table>
  </form>
  <hr size="1" noshade>
  
 <form name="frmshortMsgView" id="frmshortMsgView" action="messages/shortmsg.php?a=del" method="post" onSubmit="return onDeletes();">	
  <table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
        	<td align="left">
            <input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'messages/processMsg.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?status=true');javascript:printHeader('Short Messages');}" >
            <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?pageId='+this.value)">
				<?php echo pageRangeList($showrecs); ?>
             </select>
		  </td>
   		</tr>
  </table>
  <table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
    <tr>
    	<td class="hr">
        	<input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="javascript:ClickCheckAll(this.form);">
        </td>
        
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?order=<?php echo "emp_dname" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("Salesman") ?>
          </a>
       </td>	
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?order=<?php echo "message" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("Message") ?>
          </a>
       </td>
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?order=<?php echo "message" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("Activity") ?>
          </a>
       </td>		        
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?order=<?php echo "rec_date" ?>&type=<?php echo $ordtypestr ?>')">
              <?php echo htmlspecialchars("Receive Date") ?>
          </a>
       </td>
    </tr>
    <?php
  		for ($i = $startrec; $i < $reccount; $i++)
  		{
    		$row = mysql_fetch_assoc($res);
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
		}
			//$username = getUserName($row["imei_no"]);
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer"> 
    <td align="left">
       	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['id'] ; ?>">
    </td>
	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["emp_dname"]) ?>
    </td>
    <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["message"]) ?>
    </td>
     <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["activity"]) ?>
    </td>
	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["rec_date"]) ?>
    </td>
	</tr>
	<?php
  	}
  	mysql_free_result($res);
	?>
	 <input type="hidden" name="hdnCount" id="hdnCount" value="<?php echo $i; ?>">
	</table>
    </form>
<br><center>
<?php 
	//showpagenav($page, $pagecount); 
	showpagenav($page, $pagecount,$pagerange,'messages/shortmsg.php'); 
} 
?></center>

<?php 
function showrow($row, $recid)
  {
  global $conn;
 
  	/*if($row["vstatus"]=='false'){
	
  		$sql1 = "update tbl_shortmsg set vstatus='true' where id=".$row['id'];
		
  		$res1 = mysql_query($sql1,$conn) or die('Error in Update'.mysql_error());
	}*/
  
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Salesman")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["emp_dname"]) ?></td>
</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Message")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["message"]) ?></td>
</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Date")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["rec_date"]) ?></td>
</tr>
</table>
<?php } ?>

<?php 


//end of the show page nav function

//Start Show Record Navigation
function showrecnav($a, $recid, $count)
{
	global $conn;
  
  	
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php')"></td>
            <?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/shortmsg.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
	  <?php } ?>
		</tr>
	</table>
	<hr size="1" noshade>
<?php 
} 
//end of the show Record Navigation

//Start View Record Function
function viewrec($recid)
{
  //echo "REC ID=".$recid;
    
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("view", $recid, $count);
?>
<br>
<?php showrow($row, $recid) ?>
<?php
  mysql_free_result($res);
} 
//end of the View Record Funcion

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;
  
  $filterstr = sqlstr($filter);
  
  
  
  if ($filterstr!='') $filterstr = "%" .$filterstr ."%";
  
  //$sql = "SELECT * FROM (SELECT orderid, imeino, distname, outletname, sum( amount ) amount, odate, vstatus FROM tbl_orderbook GROUP BY orderid LIMIT 0 , 30) subq";
 /* $sql = "SELECT * FROM (select a.*,b.Title from `tbl_cstock` a, tbl_user b WHERE a.imei_no = b.imei_no and a.dstatus='false' GROUP BY a.cstock_id) subq";*/
 $sql = "SELECT * FROM (select a.*,c.emp_dname from `tbl_shortmsg` a, tbl_user b,tbl_employee c WHERE a.user_id = b.user_id and b.Title=c.emp_id GROUP BY a.id) subq";
// $sql = "SELECT `id`, `message`, `rec_date` FROM `tbl_shortmsg`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`emp_dname` like '" .$filterstr ."') or (`rec_date` like '" .$filterstr ."')";
  }

  if (isset($order) && $order!=''){
  	 $sql .= " order by `" .sqlstr($order) ."`";
  }else{
  	$sql .= " order by id desc ";
  }
  //echo "SQL :". $sql."<br>";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if ( $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT COUNT(*) FROM (select a.*,c.emp_dname from `tbl_shortmsg` a, tbl_user b,tbl_employee c WHERE a.user_id = b.user_id and b.Title=c.emp_id GROUP BY a.id) subq";
// $sql = "SELECT `id`, `message`, `rec_date` FROM `tbl_shortmsg`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`emp_dname` like '" .$filterstr ."') or (`rec_date` like '" .$filterstr ."')";
  }
  //echo "SQL :". $sql."<br>";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} 



?>

