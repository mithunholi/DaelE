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
<title>mCRM -- Take Photos</title>
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
  //echo "count".$count;
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  //echo "startrec".$startrec;
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
  //echo "reccount".$reccount;

?>

  <form action="" method="post">
    <table border="0" cellspacing="1" cellpadding="4" width="100%" bgcolor="#EAEAEA">
      <tr>
	    <td colspan="5" align="left"><b>Custom Filter</b>&nbsp;
          <input type="text" name="filter" value="<?php echo $filter ?>">
			<select name="filter_field">
			<option value="">All Fields</option>
			
           
            <option value="<?php echo "subject" ?>"<?php if ($filterfield == "subject") { echo "selected"; } ?>><?php echo htmlspecialchars("Subject") ?>
            </option>
           <option value="<?php echo "recdate" ?>"<?php if ($filterfield == "recdate") { echo "selected"; } ?>><?php echo htmlspecialchars("Date") ?>
            </option>
			</select>
         <input type="button" name="action" value="Apply Filter" onClick="javascript:SearchData(this.form,'messages/takephoto.php?a=filter')">
		 <input type="button" name="action" value="Reset Filter" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=reset')"></td>
	  </tr>
    </table>
  </form>
  <hr size="1" noshade>
  
 <form name="frmTakePhoto" id="frmTakePhoto" action="messages/takephoto.php?a=del" method="post">	
  <table class="bd" border="0" cellspacing="1" cellpadding="4" width="100%">
		<tr>
        	<td align="left">
        	<input type="button" name="btnDelete" value="Delete"  onClick="if(onDeletes()==true){ javascript:formget(this.form,'messages/processPhoto.php'); javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?status=true')}" >
            <input type="hidden" name="action" value="delete">
            </td>
            <td align="right">Rows Per Page
    	     <select name="pageperrecord" onChange="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?pageId='+this.value)">
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
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?order=<?php echo "id" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("User Name") ?>
          </a>
       </td>
		
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?order=<?php echo "subject" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("Subject") ?>
          </a>
       </td>
        <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?order=<?php echo "description" ?>&type=<?php echo $ordtypestr ?>')">
		      <?php echo htmlspecialchars("Description") ?>
          </a>
       </td>
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?order=<?php echo "photo" ?>&type=<?php echo $ordtypestr ?>')">
              <?php echo htmlspecialchars("Photos") ?>
          </a>
       </td>
       <td class="hr">
          <a class="hr" href="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?order=<?php echo "recdate" ?>&type=<?php echo $ordtypestr ?>')">
              <?php echo htmlspecialchars("Date") ?>
          </a>
       </td>
    </tr>
    <?php
  		for ($i = $startrec; $i < $reccount; $i++)
  	{
    	$row = mysql_fetch_assoc($res);
		//echo "RES:".$row["id"];
    	$style = "dr";
    	if ($i % 2 != 0) {
      		$style = "sr";
    	}
	?>
	<tr class="<?php echo $style ?>" style="cursor:pointer"> 
    <td align="left">
       	<input name="userbox[]" type="checkbox" id="userbox<?php echo $i;?>"  value="<?php echo $row['id'] ; ?>">
    </td>
	
	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars(getEmployeeName($row["Title"])) ?>
    </td>
	<td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["subject"]) ?>
    </td>
    <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["description"]) ?>
    </td>
    <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=view&recid=<?php echo $i ?>')">
		<img src="images/photo/<?php echo htmlspecialchars($row['photo']) ?>" width="50" height="50">
    </td>
    <td align="left" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=view&recid=<?php echo $i ?>')">
		<?php echo htmlspecialchars($row["recdate"]) ?>
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
	showpagenav($page, $pagecount,$pagerange,'messages/takephoto.php'); 
} 
?></center>

<?php 
function showrow($row, $recid)
   
  {
  global $conn;
  
?>

<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("User Name")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars(getEmployeeName($row["Title"])) ?></td>
</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Subject")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["subject"]) ?></td>
</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Description")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["description"]) ?></td>
</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Photos")."&nbsp;" ?></td>
<td class="dr" align="left"><img src="images/photo/<?php echo htmlspecialchars($row["photo"]) ?>"width="320" height="240"></td>

</tr>
<tr>
<td class="hr" align="left"><?php echo htmlspecialchars("Date")."&nbsp;" ?></td>
<td class="dr" align="left"><?php echo htmlspecialchars($row["recdate"]) ?></td>
</tr>
</table>
<?php 
} 
?>

<?php 
function getUserName($imno){
	global $conn;
  $sqlQuery = "select a.Title from tbl_user a,tbl_photo b where a.imei_no = b.imei_no and b.imei_no=".$imno;
  //echo "SQL =".$sqlQuery;
  $res = mysql_query($sqlQuery);
  if(mysql_num_rows($res)>0){
  	$resRow = mysql_fetch_assoc($res);
	$username = $resRow["Title"]; 
  }else{
  	$username="";
  }
  return $username;
}


//Start Show Record Navigation
function showrecnav($a, $recid, $count)
{
	//global $conn;
  
  	
?>
	<table class="bd" border="0" cellspacing="1" cellpadding="4">
		<tr>
			<td><input type="button" name="btnBack" value="Back" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?status=true')"></td>
            <?php 
			if ($recid > 0) 
			{ 
			?>
				<td><input type="button" name="btnPrior" value="Prior Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>')"></td>
			<?php 
			} 
			if ($recid < $count - 1) { ?>
				<td><input type="button" name="btnNext" value="Next Record" onClick="javascript:LoadDiv('<?php echo CENTER_DIV ?>','messages/takephoto.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>')"></td>
	  <?php } ?>
		</tr>
	</table>
	<hr size="1" noshade>
<?php 
} 


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
<br>
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
  
 // $sql = "SELECT `id`, `subject`, `description`, `photo`, `recdate` FROM `tbl_photo`";
 $sql = "SELECT * FROM (select a.*,b.Title from `tbl_photo` a, tbl_user b WHERE a.imei_no = b.imei_no GROUP BY a.id) subq";
  
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`Title` like '" .$filterstr ."') or (`subject` like '" .$filterstr ."') or (`recdate` like '" .$filterstr ."')";
  }

  if (isset($order) && $order!=''){
  	 $sql .= " order by `" .sqlstr($order) ."`";
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
 // $sql = "SELECT COUNT(*) FROM `tbl_photo`";
 $sql = "SELECT COUNT(*) FROM (select a.*,b.Title from `tbl_photo` a, tbl_user b WHERE a.imei_no = b.imei_no  GROUP BY a.id) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`Title` like '" .$filterstr ."') or (`subject` like '" .$filterstr ."') or (`recdate` like '" .$filterstr ."')";
  }
  //echo "SQL :". $sql."<br>";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} 


?>

