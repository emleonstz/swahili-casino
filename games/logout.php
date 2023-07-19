<?php 
session_start();
header("Content-Type: application/json");
require('../vendor/autoload.php');
use Emleons\Games\SecureLogin;
$secure = new SecureLogin;
$message = array('user_state'=>"logedout");
$secure->logOut();
$current_page = $_SERVER['REQUEST_URI'];

header('Location: home');
?>