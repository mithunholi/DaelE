<?php
  define("CENTER_DIV","Center_Content");
  define("REC_PER_PAGE",25);
  define("PAGE_RANGE",10);

 //$conn=mysqli_connect("127.0.0.1", "eoxys_db", "eoxys123");
  // $conn = mysql_connect("mysql","eoxys_db","eoxys123") or die("Cannot connect to the DB");
// $conn = @mysql_connect("mysql","root"," ") or die("Cannot uuu connect to the DB");
  // mysql_select_db("ecrm",$conn) or die("Cannot connect to the DB");
  // date_default_timezone_set('Asia/Calcutta');
  // $datetime = new DateTime(); 
  // $today = $datetime->format('Y-m-d H:i:s'); // Prints "2011-03-20 07:16:17"
  $conn = mysqli_connect("127.0.0.1", "root", "", "ecrm");

if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
 
echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($conn) . PHP_EOL;

//mysqli_close($link);
?>
