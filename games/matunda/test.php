<?php 
session_start();
require('../../vendor/autoload.php');
use Emleons\Games\Functions;
use Emleons\Games\SecureLogin;
$secure = new SecureLogin;
$funtions = new Functions;

echo $secure->decrypt($secure->getApikey());

?>