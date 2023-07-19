<?php
namespace Emleons\Games;

use Emleons\Games\Dbclass;
use Emleons\Games\Changanya;


class Functions {
    private $db;
    private $factory;
    public function __construct()
    {
        $this->db = new Dbclass;
        $this->factory = new Changanya;
        
    }
   
    public function get_user_balance($uid){
        $sql = "SELECT `account_balance` FROM `players` WHERE `id` = :user";
        $params= ['user'=>$uid];
        $balance = $this->db->get_array($sql,$params);
        return $balance['account_balance'];
    }
    public function deduct_balance($uid,$amount){
        $sql = "UPDATE `players` SET `account_balance`= account_balance - :amount WHERE `id` = :user;";
        $smt = $this->db;
        
        if($smt->execute($sql,['amount'=>$amount,'user'=>$uid])){
            $sql = "SELECT `account_balance` FROM `players` WHERE `id` = :user";
            $params= ['user'=>$uid];
            $smt = $this->db;
            $data = $smt->get_array($sql,$params);
            $balance = $data['account_balance'];
            return $balance;
        }else{
            $balance = null;
            return $balance;
        }
    }
    public function reward_user($uid,$amount){
        $sql = "UPDATE `players` SET `account_balance`= account_balance + :amount WHERE `id` = :user;";
        $smt = $this->db;
        
        if($smt->execute($sql,['amount'=>$amount,'user'=>$uid])){
            return true;
        }else{
            return false;
        }
    }
    function chekecha($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $string .= $characters[$randomIndex];
    }

    return $string;
    }
    public function clean($string) {
        // Replace all spaces with hyphens.
        $string = str_replace(' ', '-', $string);
      
        // Remove all special characters.
        $pattern = '[^a-zA-Z0-9-]';
        $string = preg_replace($pattern, '', $string);
      
        return $string;
      }
      
    public function checkLogin(){
        if(isset($_SESSION['user'])){
            $status = "login";
            return $status;
        }else{
            $status = "notlogin";
            return $status;
        }
    }
    public function chekGameSession(){
        if(isset($_SESSION['game_session'])){
            $status = "login";
            return $status;
        }else{
            $status = "notlogin";
            return $status;
        }
    }
    public function setGamesession(){
        if(isset($_SESSION['game_session'])){
            return $_SESSION['game_session'];
        }else{
            $_SESSION['game_session']=$this->chekecha(10);
            return $_SESSION['game_session'];
        }
    }
    public function get_game_cost($gameID){
        $sql = "SELECT `cost_per_reel` FROM `game` WHERE `id` = :game";
        $params = ['game'=>$gameID];
        $smt= $this->db;
        $data =$smt->get_array($sql,$params);
        return $data['cost_per_reel'];
    }
    public function get_reels($gameID){
        $sql = "SELECT `reel_name` FROM `reels` WHERE `game_id` = :game";
        $params = ['game'=>$gameID];
        $smt= $this->db;
        $reel = $smt->get_array_all($sql,$params);
        return $reel;
    }
    public function isFoundreel($reel,$gameID){
        $sql="SELECT COUNT(`reel_name`) as num FROM `reels` WHERE `reel_name` = :reel AND `game_id` = :game";
        $params =['reel'=>$reel,'game'=>$gameID];
        $smt= $this->db;
        $data = $smt->get_array($sql,$params);
        if($data['num']<1){
            $result = false;
            return $result;
        }else{
            $result = true;
            return $result;
        }
    }
    public function get_reel_value($gameID,$reel){
        $sql = "SELECT `unit` FROM `reels` WHERE `game_id` = :game AND `reel_name` = :reel;";
        $params = ['game'=>$gameID,'reel'=>$reel];
        $smt= $this->db;
        $reel = $smt->get_array($sql,$params);
        return $reel['unit'];
    }
    function count_bet_perReel($reel,$uid,$game_session,$gameID){
        $sql = "SELECT COUNT(`reel`) as num FROM `bets` WHERE `reel`= :reel AND `user_id` = :user AND `game_session` = :sessio AND `game_id` = :game";
        $smt = $this->db;
        $data = $smt->get_array($sql,['reel'=>$reel,'user'=>$uid,'sessio'=>$game_session,'game'=>$gameID]);
        return $data['num'];
    }
    public function place_bet($reel,$uid,$gameId){
        header('Content-Type: application/json');
        if($this->isFoundreel($reel,$gameId)){
        $login_status = $this->checkLogin();
        $game_session_status = $this->chekGameSession(); 
        if($login_status=="notlogin"){
            //user not login
            $result = array('reel'=>"N/A",'balance'=>0,'time'=>0,'bet'=>"usernotlogin");
            echo json_encode($result);
        }elseif($game_session_status == "login"){
            //user login but game session not init
            $game_session = $this->setGamesession();
            //check user balance
            $sql = "SELECT `account_balance` FROM `players` WHERE `id` = :user";
            $params= ['user'=>$uid];
            $smt = $this->db;
            $data = $smt->get_array($sql,$params);
            $balance = $data['account_balance'];
            
            if($balance<1){
                //user balance not enough to bet
                $result = array('reel'=>$reel,'balance'=>$balance,'time'=>0,'bet'=>"insuficientBalance");
                echo json_encode($result);
            }elseif($balance<$this->get_game_cost($gameId)){
                //insufficient balnce
                $result = array('reel'=>$reel,'balance'=>$balance,'time'=>0,'bet'=>"insuficientBalance");
                echo json_encode($result);
            }else{
                if($this->isFoundreel($reel,$gameId)){
                //decut user balance
                $reel_unit = $this->get_reel_value($gameId,$reel);
                $cost = $this->get_game_cost($gameId);
                $newbalance = $this->deduct_balance($uid,$cost);
                //insert bet
                if($newbalance == null){
                    $result = array('reel'=>$reel,'balance'=>$balance,'time'=>0,'bet'=>"deductBalanceError");
                    echo json_encode($result);
                }else{
                   $sql = "INSERT INTO `bets` (`id`, `game_id`, `reel`, `reel_unit`, `cost`, `user_id`, `game_session`, `result_reel`, `status`, `created_at`) VALUES (NULL, '$gameId', '$reel', '$reel_unit', '$cost', '$uid', '$game_session', 'waiting', 'pending', current_timestamp())";
                   $smt= $this->db;
                   if($smt->execute($sql,null)){
                    $times = $this->count_bet_perReel($reel,$uid,$game_session,$gameId);
                    $result = array('reel'=>$reel,'balance'=>$newbalance,'time'=>$times,'bet'=>"placed");
                    echo json_encode($result);
                   } 
                }
                }
                
               
            }
        }
    }else{
        $result = array('reel'=>"N/A",'balance'=>0,'time'=>0,'bet'=>"unkownReel");
        echo json_encode($result); 
    }
    }
    function reelError($message){
        header('Content-Type: application/json');
        $result = array('reel'=>"N/A",'balance'=>0,'time'=>0,'bet'=>$message);
        echo json_encode($result); 
    }
     public function hasBets($userId,$sessionID){
        $sql = "SELECT COUNT(`reel`) AS num FROM `bets` WHERE `user_id` = :user AND `game_session` = :gsession";
        $smt = $this->db;
        $data = $smt->get_array($sql,['user'=>$userId,'gsession'=>$sessionID]);
        if($data['num']<1){
            return false;
        }else{
            return true;
        }
     }
     public function randomize(){
        
        $generatedNumber = $this->factory->changa();
        return $generatedNumber;
     }
     public function getReeL($reelid,$gameID){
        $sql = "SELECT * FROM `reels` WHERE `id` = :reel AND `game_id` = :game;";
        $smt  = $this->db;
        return $smt->get_array($sql,['reel'=>$reelid,'game'=>$gameID]);
        
     }
     public function updateBets($uid,$gameID,$game_session,$namba){
        $sql = "UPDATE `bets` SET `result_reel`='$namba' WHERE `game_id` = :game AND `user_id`= :user AND `game_session` = :gsession";
        if($this->db->execute($sql,['user'=>$uid,'game'=>$gameID,'gsession'=>$game_session])){
            return true;
        }else{
            return false;
        }
     }
     function handleWinsLoss($uid,$gameID,$game_session,$namba,$reelname,$reel_unit){
        if ($this->updateBets($uid,$gameID,$game_session,$reelname)) {
            // code...
            $sql = "SELECT COUNT(`reel`) as num FROM `bets` WHERE `game_id` = :game AND `user_id` = :user AND `game_session`=:gsession AND `reel` = :thereel";
            $isWin = $this->db->get_array($sql,['user'=>$uid,'game'=>$gameID,'gsession'=>$game_session,'thereel'=>$reelname]);
            if($isWin['num']<1){
                $reel = ucfirst($reelname);
                $result = array('namba'=>$namba,'won'=>0,'balnace'=>0,'status'=>"lost",'bonus'=>null);
                $reel = ucfirst($reelname);
                echo json_encode($result);
                $this->db->execute("UPDATE `bets` SET `status`='lost' WHERE `game_id` = :game AND `user_id`= :user AND `game_session` = :gsession",['user'=>$uid,'game'=>$gameID,'gsession'=>$game_session]);
                unset($_SESSION['game_session']);
            }else{
                $stake = $this->db->get_array("SELECT SUM(`cost`) AS cost FROM `bets` WHERE `reel` = :thereel AND `user_id` = :user AND `game_id` = :game AND `game_session` = :gsession ;",
                ['thereel'=>$reelname,'user'=>$uid,'game'=>$gameID,'gsession'=>$game_session]);
                $betStake = $stake['cost'];
                $unit = $reel_unit;
                $wonPrize = $betStake * $unit;
                if($this->reward_user($uid,$wonPrize)){
                    $balance = $this->get_user_balance($uid);
                    $reel = ucfirst($reelname);
                   $result = array('namba'=>$namba,'won'=>$wonPrize,'balnace'=>$balance,'status'=>"won",'bonus'=>$reel);
                    echo json_encode($result); 
                    $this->db->execute("UPDATE `bets` SET `status`='won' WHERE `game_id` = :game AND `user_id`= :user AND `game_session` = :gsession AND `reel` = :thereel",['user'=>$uid,'game'=>$gameID,'gsession'=>$game_session,'thereel'=>$reelname]);
                    $this->db->execute("UPDATE `bets` SET `status`='lost' WHERE `game_id` = :game AND `user_id`= :user AND `game_session` = :gsession AND `reel` != :thereel",['user'=>$uid,'game'=>$gameID,'gsession'=>$game_session,'thereel'=>$reelname]);
                    unset($_SESSION['game_session']);
                }
            }
        }
     }
     function getBethitory($uid){
        $sql = "SELECT `id`,`reel`,`cost`,`result_reel`,`status`,DATE_FORMAT(`created_at`,'%M, %d, %Y %h:%i') AS date FROM `bets` WHERE `user_id` = :user ORDER BY `id` DESC LIMIT 10 ;";
        return $this->db->get_array_all($sql,['user'=>$uid]);
     }
     
     
     
     public function getGameSession(){
        if(isset($_SESSION['game_session'])){
            return $_SESSION['game_session'];
        }else{
            $this->setGamesession();
            return $_SESSION['game_session'];
        }
     }
     function sessionErrorr(){
            header('Content-Type: application/json');
            $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>"invalidRequest",'bonus'=>null);
            echo json_encode($result);
     }
     function sessionErrorrMsg($message){
        header('Content-Type: application/json');
        $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>$message,'bonus'=>null);
        echo json_encode($result);
    }
     function notlogin(){
        header('Content-Type: application/json');
        $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>"notlogin",'bonus'=>null);
        echo json_encode($result);
    }
    function getResults($login,$accont_sattus,$balance,$message,$token){
        $result = array('login_status'=>$login,'accont_status'=>$accont_sattus,'user_balance'=>$balance,"request_message"=>$message,'toekn'=>$token);
        echo json_encode($result);
    }
     public function processBet($uid,$gameID,$game_session){
        header('Content-Type: application/json');
        if(! $this->checkLogin()=="login"){
            $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>"notlogin",'bonus'=>null);
            echo json_encode($result);
        }elseif(! $this->chekGameSession() == "login"){
            $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>"sclectbetfirts",'bonus'=>null);
            echo json_encode($result);
        }elseif ($this->hasBets($uid,$game_session)) {
            $namba= $this->randomize();
            $wining_reel = $this->getReeL($namba,$gameID);
            $reelname = $wining_reel['reel_name'];
            $reel_unit= $wining_reel['unit'];
            $this->handleWinsLoss($uid,$gameID,$game_session,$namba,$reelname,$reel_unit);
        }elseif (! $this->hasBets($uid,$game_session)) {
            $result = array('namba'=>0,'won'=>0,'balnace'=>0,'status'=>"nobetselected",'bonus'=>null);
            echo json_encode($result);
        }
     }
}