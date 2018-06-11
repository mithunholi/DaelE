<?php 

 $conn = mysqli_connect("127.0.0.1", "root", "", "ecrm");
 if (!defined('CENTER_DIV')) define('CENTER_DIV', 'Center_Content');
  //define("CENTER_DIV","Center_Content");
  if (!defined('REC_PER_PAGE')) define('REC_PER_PAGE', '50');
  // define("REC_PER_PAGE",50);
  if (!defined('PAGE_RANGE')) define('PAGE_RANGE', '10');
  // define("PAGE_RANGE",10);
?>
