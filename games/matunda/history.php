<?php 
session_start();
require('../../vendor/autoload.php');
use Emleons\Games\SecureLogin;
use Emleons\Games\Functions;
$secure = new SecureLogin;
$funtions = new Functions;

header('Content-Type: application/json');
$user = $secure->get_user();
$data = $funtions->getBethitory($secure->decrypt($user));

// Convert the data to JSON format
echo json_encode($data);
exit;
?>