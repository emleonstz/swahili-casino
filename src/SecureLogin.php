<?php

namespace Emleons\Games;

use Emleons\Games\Dbclass as GlobalDbclass;
use RandomLib\Factory;
use Emleons\Games\ValidatePhone;


require('Dbclass.php');
class SecureLogin
{
    private $db;
    private $random;
    private $validator;


    public function __construct()
    {
        $this->db = new GlobalDbclass;
        $this->random = new Factory;
        $this->validator = new ValidatePhone;
       
       
    }
    public function set_user($uid,$apiKey)
    {
        $_SESSION['user'] = $this->encrypt($uid);
        $_SESSION['api_key']=$this->encrypt($apiKey);
    }

    public function get_user(){
        return $_SESSION['user'];
    }
    public function getApikey(){
        return $_SESSION['api_key'];
    }
    public function validateApiKey($key){
        $user = $this->decrypt($this->get_user());
        $keys = $this->decrypt($key);
        $sql = "SELECT COUNT(`api_key`) AS num,`api_key` FROM `players` WHERE `id` = :user AND `api_key` = :keys";
        $num =$this->db->get_array($sql,['user'=>$user,'keys'=>$keys]);
        if($num['num']<1){
            return false;
        }else{
            $sessionkey = $this->decrypt($this->getApikey());
            if($sessionkey === $num['api_key']){
                return true;
            }else{
                return false;
            }
        }

    }
   
    
    public function checklogin(){
        if(isset($_SESSION['user']) && $_SESSION['user'] != null ){
            return true;
            if($this->isValidUser()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function isValidUser(){
        $user = $this->decrypt($this->get_user());
        $status = $this->db->get_array("SELECT COUNT(`id`) AS num FROM `players` WHERE `id` = :user",['user'=>$user]);
        if($status['id'] <1){
            return false;
        }else{
            return true;
        }
    }

    function decrypt($encryptedText)
    {
        $data = $this->db->get_array("SELECT `private_key` FROM `encrypt` WHERE `id` = '2'", null);
        $privateKey = $data['private_key'];
        $encrypted = base64_decode($encryptedText);
        openssl_private_decrypt($encrypted, $decrypted, $privateKey);
        return $decrypted;
    }

    function encrypt($plaintext)
    {
        $data = $this->db->get_array("SELECT `public_key` FROM `encrypt` WHERE `id` = '2'", null);
        $publicKey = $data['public_key'];
        openssl_public_encrypt($plaintext, $encrypted, $publicKey);
        $base64Encrypted = base64_encode($encrypted);
        return $base64Encrypted;
    }
    public function chekExpireSession()
    {
        $timeout= $this->db->getEnv();
        if (isset($_SESSION['last_activity'])) {
            $inactive_duration = time() - $_SESSION['last_activity'];
            if ($inactive_duration >$timeout['SESSION_TIMEOUT'] ) {
                session_destroy();
                header('Location: logout.php');
                exit();
            }
        }
       return $_SESSION['last_activity'] = time();
    }
    public function updateSesion(){
        $_SESSION['last_activity'] = time();
    }
    function validatePhoneNumber($number) {
        $number = trim($number);
        $pattern = '/^(0|\+255)\d{9}$/';
        return preg_match($pattern, $number);
    }

    function removePrefix($phoneNumber) {
        $prefixes = array("0", "+255");
        foreach ($prefixes as $prefix) {
            if (strpos($phoneNumber, $prefix) === 0) {
                $phoneNumber = substr($phoneNumber, strlen($prefix));
                break;
            }
        }
        return $phoneNumber;
    }
    public function logOut()
    {
        unset($_SESSION['api_key']);
        unset($_SESSION['user']);
        session_destroy();
        header('location: home');
        
    }

    public function generateCSRFToken()
{
    $token = openssl_random_pseudo_bytes(32); // Generate a random token
    $_SESSION['csrf_token'] = hash('sha256', $token); // Store the token in the session
    $_SESSION['csrf_token_expiry'] = time() + 3600 * 24; // Set token expiry time (24 hours in this example)
    return $token;
}

function validateCSRFToken($token) {
    if (isset($_SESSION['csrf_token']) && isset($_SESSION['csrf_token_expiry'])) {
        if ($_SESSION['csrf_token'] ===  $token && $_SESSION['csrf_token_expiry'] >= time()) {
            unset($_SESSION['csrf_token']); // Remove the token from the session after successful validation
            unset($_SESSION['csrf_token_expiry']);
            return true;
        }
    }
    return false;
}

    
    public function login($phone,$password){
        header('Content-Type: application/json');
        if($this->validator->validate($phone)){
            $sql="SELECT *,COUNT(`phone`) as num FROM `players` WHERE `phone` = :simu";
            $userinfo = $this->db->get_array($sql,['simu'=>$phone]);
            if ($userinfo['num']<1) {
                $error = array('login'=>"userontfound");
                echo json_encode($error);
            }else{
                $hash = $userinfo['password'];
                if(password_verify($password,$hash)){
                   $ccountStatus = $userinfo['account_status'];
                   if($ccountStatus == "pending"){
                    $user =  $userinfo['id'];
                    $_SESSION['user_tmp'] = $this->encrypt($user);
                    $error = array('login'=>"notverified");
                    echo json_encode($error);
                   }elseif($ccountStatus == "blocked"){
                    $this->logOut();
                    header('Location: blocked.php');
                   }else{
                    session_destroy();
                    session_start();
                    $userId = $userinfo['id'];
                    $clientsecreate = $userinfo['api_key'];
                    $this->set_user($userId,$clientsecreate);
                    
                    $error = array('login'=>"pass");
                    echo json_encode($error); 
                   }
                }else{
                    $error = array('login'=>"invalidpassword");
                    echo json_encode($error); 
                }
            }

        }else{
            $error = array('login'=>"invalidfphone");
            echo json_encode($error);
        }

    }
    function chekecha($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }
    function chekechaNum($length) {
        $characters = '0123456789';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }
    
    public function register($phone,$password){
        header('Content-Type: application/json');
        if($this->validator->validate($phone)){
            $sql="SELECT *,COUNT(`phone`) as num FROM `players` WHERE `phone` = :simu";
            $userinfo = $this->db->get_array($sql,['simu'=>$phone]);
            if (!$userinfo['num']<1) {
                $error = array('login'=>"alredyexist");
                echo json_encode($error);
            }else{
            $pass = password_hash($password,PASSWORD_BCRYPT);
            $simu = $phone;
            $apiKey = $this->chekecha(10);
            $status = "pending";
            $b = 0;
            $dateString = date("Y-m-d H:i:s");
            $sql = "INSERT INTO `players` (`phone`, `password`, `status`, `created_at`, `account_balance`, `api_key`, `account_status`) VALUES (:simu, :pass, :statusi, :dated, :blance, :api, :statuses)";
            if($this->db->execute($sql,['simu'=>$phone,'pass'=>$pass,'statusi'=>$status,'dated'=>$dateString,'blance'=>$b,'api'=>$apiKey,'statuses'=>$status])){
                $user_status = $this->db->get_array("SELECT * FROM `players` WHERE `phone` = :sim",['sim'=>$simu]);
                if($user_status['account_status'] == "pending"){
                    $namba = $this->validator->remove_tz_prefix($phone);
                    $no = "255".$namba;
                    $user = $user_status['id'];
                    $_SESSION['user_tmp'] = $this->encrypt($user);
                    $this->sendActivation($no,$user);
                    $error = array('login'=>"notverifed");
                    echo json_encode($error); 
                }elseif ($user_status['account_status']=="blocked") {
                    $this->logOut();
                    header('Location: blocked.php');
                }else{
                    $error = array('login'=>"unknownEnerror");
                    echo json_encode($error);
                }
            }
        }

        }else{
            $error = array('login'=>"invalidphone");
            echo json_encode($error); 
        }
    }

    public function activateAccount($otp){
        if(isset($_SESSION['user_tmp'])){
            $user = $this->decrypt($_SESSION['user_tmp']);
            $usereneterd = $this->db->clean($otp);
            $code = $this->db->get_array("SELECT * FROM `opt_tmp` WHERE `user_id` = :user",['user'=>$user]);
            if($code['code'] === $usereneterd){
               if( $this->db->execute("UPDATE `players` SET `account_status`='active' WHERE `id` = '$user' AND `account_status` = 'pending' ",null)){
                $message = '<script> window.location.href="index.php?action=login"</script>';
                unset($_SESSION['user_tmp']);
                echo $message;
               }else{
                $message = "Swal.fire(
                    'Tatizo!',
                    'Tatizo limejitokeza tafadhali jaribu tana baadae!',
                    'info'
                  )";
                echo '<script>'.$message.'</script>' ;
               }

                
            }else{
                $message = "Swal.fire(
                    'Tatizo!',
                    'Umeingiza tarakimu zisizo endana tafadhali ingiza tarakimu zilizo tumwa katika sms!',
                    'error'
                  )";
                  echo '<script>'.$message.'</script>' ;
            }
        }else{
            $message = "Swal.fire(
                'Haitambuliki!',
                'Tafadhali bonyeza kwanza kitufe cha ingia au jiunge ili kuendelea!',
                'error'
              )";
              echo '<script>'.$message.'</script>' ;
        }
    }
    public function sendActivation($phone,$user){
        $expiration_date = time() + 300;
        $sql = "SELECT COUNT(`code`) AS num,`code`,`expire_time` FROM `opt_tmp` WHERE `user_id` = :user";
        $num = $this->db->get_array($sql,['user'=>$user]);
        if($num['num']<1){
            $code = $this->chekechaNum(6);
            $message = "Tumia tarakimu ".$code." ili kuwezesha akaunti yako";
            $this->db->execute("INSERT INTO `opt_tmp` (`id`, `user_id`, `expire_time`, `code`) VALUES (NULL, '$user', '$expiration_date', '$code')",null);

            //send otp
                $this->nexSms($phone,$message);
            
        }else {
            $current_time = time();
            $time = $num['expire_time'];
            if ($current_time > $expiration_date) {
                // The data has expired.
                $code = $this->chekechaNum(6);
                $message = "Tumia tarakimu ".$code." ili kuwezesha akaunti yako";
                $this->db->execute("UPDATE `opt_tmp` SET `code`='$code' WHERE `user_id` = '$user'",null);
                $this->nexSms($phone,$message);
            
            } else {
                // The data has not expired.
                 $code = $num['code'];
                 $message = "Tumia tarakimu ".$code." ili kuwezesha akaunti yako";
            //send otp
                $this->nexSms($phone,$message);
            
            }
        }
    }
    function nexSms($phone, $code) {
        $user = "NextSms User name";//your next sms user name
        $pass = "++nf+rfOC8YJj2hGwOH/=";//your nextsms password
        $baseUrl = "https://messaging-service.co.tz/link/sms/v1/text/single?username=" . $user . "&password=" . $this->decrypt($pass) . "&from=RMNDR&to=" . $phone . "&text=" . $code;
      
        $curl = curl_init();
      
        curl_setopt_array($curl, array(
          CURLOPT_URL => $baseUrl,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          )
        ));
      
        $response = curl_exec($curl);
      
        if ($response === false) {
          //to do 

        } else {
          //to do
        }
      
        curl_close($curl);
      }
    public function isActive(){
        $user = $this->decrypt($this->get_user());
        $status = $this->db->get_array("SELECT `account_status` FROM `players` WHERE `id` = :user",['user'=>$user]);
        if($status['account_status'] == "active"){
            return true;
        }else{
            return false;
        }
    }
    public function isBlocked(){
        $user = $this->decrypt($this->get_user());
        $status = $this->db->get_array("SELECT `account_status` FROM `players` WHERE `id` = :user",['user'=>$user]);
        if($status['account_status'] == "bloked"){
            return true;
        }else{
            return false;
        }
    }
    public function resend_otp($phone){
        if($this->validator->validate($phone)){
            $userinfo = $this->db->get_array("SELECT *,COUNT(`phone`) AS num FROM `players` WHERE `phone` = :phone;",['phone'=>$phone]);
            if($userinfo['num']<1){
                $message = "Swal.fire(
                    'Haitambuliki!',
                    'Tafadhali ingiza namba uliyotumia kuunda akaunti au bofya kitufe cha ingia au jiunge ili kuendelea!',
                    'error'
                  )";
                  echo '<script>'.$message.'</script>' ;
            }else{
                if($userinfo['account_status']=="active"){
                    $message = "Swal.fire(
                        'Taarifa!',
                        'Akaunti imekwisha wezeshwa tafadhali ingia katika akaunti yako ufurahie michezo pendwa!',
                        'info'
                      )";
                      echo '<script>'.$message.'</script>' ;
                }elseif($userinfo['account_status']=="blocked"){
                    header('location: index.php');
                }elseif($userinfo['account_status']=="pending"){
                    $_SESSION['user_tmp'] = $this->encrypt($userinfo['id']);
                    $this->sendActivation($phone,$userinfo['id']);
                    $message = "Swal.fire(
                        'Ujumbe umetumwa',
                        'Ujumbe wa kuwezesha akaunt yako umetumwa katika namba yako ya simu',
                        'success'
                      )";
                      echo '<script> window.location.href="validate.php" </script>' ;
                }
            }
        }else{
            $message = "Swal.fire(
                'Kosa!',
                'Namba ya simu sio sahihi!',
                'error'
              )";
              echo '<script>'.$message.'</script>' ;
        }
    }
    function gamelogics(){
        $this->removeBetsMatunda();
    }
    function removeBetsMatunda(){
        if($this->checklogin()){
            $user = $this->get_user();
            $uid = $this->decrypt($user);
            $bets = $this->db->get_array("SELECT COUNT(`reel`) AS num FROM `bets` WHERE `user_id` = :user",['user'=>$uid]);
            if($bets['num']<1){

            }else{
                $totalbet = $this->db->get_array("SELECT SUM(`cost`) AS num FROM `bets` WHERE `user_id` = :user AND `status` = :states",['user'=>$uid,'states'=>"pending"]);
                if($totalbet['num'] == NULL){

                }else{
                    if($totalbet['num']<1){

                    }else{
                        $sum = $totalbet['num'];
                        $sql = "UPDATE `players` SET `account_balance`= account_balance + :amount WHERE `id` = :user;";
                        $this->db->execute($sql,['amount'=>$sum,'user'=>$uid]);
                        $sql = "DELETE FROM `bets` WHERE `user_id` = :user AND `status` = :states";
                        $this->db->execute($sql,['user'=>$uid,'states'=>"pending"]);
                    }
                }
            }

        }
     }
}
