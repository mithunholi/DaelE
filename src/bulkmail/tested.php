<?php
$scriptname=end(explode('/',$_SERVER['PHP_SELF']));
$scriptpath=str_replace($scriptname,'',$_SERVER['PHP_SELF']);
echo 'scriptname :'.$scriptname."<br />";
echo 'path :'.$scriptpath."<br />";
echo "Dir :".dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'file.php'."<br />";
?>