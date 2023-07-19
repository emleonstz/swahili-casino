<?php
namespace Emleons\Games;
use Emleons\Games\Dbclass;
use Emleons\Games\SecureLogin;

class Home {
    private $db;
    private $secure;
    public function __construct()
    {
        $this->db = new Dbclass;
        $this->secure = new SecureLogin;
        $this->secure->gamelogics();
    }
    public function getGamesBycategory($category){
        $category = $this->db->clean($category);
        $sql = "SELECT `game_name`,`image`,`path`,`category`,`id` FROM `game` WHERE `category`=:cat";
        $data  = $this->db->get_array_all($sql,['cat'=>$category]);
        return $data;
    }
    public function getGames(){
        $sql = "SELECT `game_name`,`image`,`path`,`category`,`id` FROM `game` ORDER BY `game_name` ASC";
        $data  = $this->db->get_array_all($sql,null);
        return $data;
    }

}