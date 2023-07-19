<?php 
session_start();
header("Content-Type: application/json");
require('../vendor/autoload.php');
use Emleons\Games\SecureLogin;
$secure = new SecureLogin;
$secure->generateCSRFToken();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
if($secure->checklogin()){
    $error = array('login'=>"alreadylogin");
    echo json_encode($error);
}else {
        if (isset($_POST['simu']) && isset($_POST['pass'])) {
            $phone = trim($_POST['simu']);
            $password = $_POST['pass'];
            if($secure->validatePhoneNumber($phone)){
                $token = $_SESSION['csrf_token'];
                if($secure->validateCSRFToken($token)){
                   $secure->register($phone,$password); 
                }else{
                    $error = array('login' => "notauthorized");
                    echo json_encode($error);
                }
            }else{
                $error = array('login' => "invalidfphone");
                echo json_encode($error);
            }
        } else {
            $error = array('login' => "missingparams");
            echo json_encode($error);
        }
    }
}else{
    $error = array('login'=>"invalidRequest");
    echo json_encode($error);
}
?>