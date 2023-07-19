<?php
session_start();
require('../vendor/autoload.php');
use Emleons\Games\SecureLogin;
use Emleons\Games\Functions;
$secure = new SecureLogin;
$functions = new Functions;
header('Content-Type: application/json');
$secure->generateCSRFToken();
if(1==1){
        if($secure->checklogin()){
            if($secure->isActive()){
                $balance = $functions->get_user_balance($secure->decrypt($secure->get_user()));
                $apiKey  = $secure->getApikey();
                $functions->getResults("logedin","active",$balance,"pass",$apiKey);
            }else{
                if($secure->isBlocked()){
                    //user blocked
                    
                    $functions->getResults("not_login","blocked",0,"pass",null);
                }else{
                    //not activated
                    $functions->getResults("not_login","notactive",0,"pass",null);
                }
            }
        }else{
            $functions->getResults("not_login",null,0,"pass",null);
        }
    
}else{
    $functions->getResults("invalidrequest",null,0,"fail",null);
}


?>

