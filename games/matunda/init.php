<?php
session_start();
require('../../vendor/autoload.php');
use Emleons\Games\SecureLogin;
use Emleons\Games\Functions;
$secure = new SecureLogin;
$functions = new Functions;
$secure->generateCSRFToken();
if(1==1){
    if($secure->checklogin()){
        $token = $_SESSION['csrf_token'];
        if($secure->validateCSRFToken($token)){
            
            if($functions->chekGameSession()=="login"){
                $s= $functions->getGameSession();
                $user = $secure->get_user();
                $functions->processBet($secure->decrypt($user),"1",$s);
            }else{
                $s= $functions->getGameSession();
                $user = $secure->get_user();
                $functions->processBet($secure->decrypt($user),"1",$s);  
            }
        }else{
            $functions->sessionErrorrMsg("failtoplacebet");
        }
        
    }else{
        $functions->notlogin();
    }
}else{
    $functions->sessionErrorrMsg("invalidRequest");
}


?>

