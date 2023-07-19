<?php 
require('vendor/autoload.php');
use Emleons\TzNamba\ValidateNamba;

$test = new ValidateNamba;
$phone = "+254656413291";

if($test->validate($phone)){
	echo $phone." is a valid phone".'<br>';
}else{
	echo $phone." is a invalid phone".'<br>';
}

$removed_prefix = $test->remove_tz_prefix($phone);
echo "removed prefix from ".$phone." to ".$removed_prefix;
?>
