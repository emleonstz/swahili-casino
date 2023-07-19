<?php

namespace Emleons\Games;
use PDO;
use PDOException;

class Dbclass {
private $env;
private $host;
private $dbname;
private $username;
private $password;

public function __construct()
{
    
    $envVariables = $this->getEnv();
    $this->host = $envVariables['DB_HOST'];
    $this->dbname = $envVariables['DB_NAME'];
    $this->username = $envVariables['DB_USER'];
    $this->password = $envVariables['DB_PASS'];
}
function connect(){
$host = $this->host;
$dbname = $this->dbname;
$username = $this->username;
$password = $this->password;
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
function pdo_start($sql){
    $smt = $this->connect();
    $start = $smt->prepare($sql);
    return $start;
}
public function clean($string) {
    // Replace all spaces with hyphens.
    $string = str_replace(' ', '-', $string);
  
    // Remove all special characters.
    $pattern = '[^a-zA-Z0-9-]';
    $string = preg_replace($pattern, '', $string);
  
    return $string;
  }
function execute($sql,$params){
   $smt = $this->pdo_start($sql);
   $exc = $smt->execute($params);
   return $exc;
}
function get_json($sql,$params){
    $smt= $this->pdo_start($sql);
    $smt->execute($params);
    $result = $smt->fetch(PDO::FETCH_ASSOC);
    $data = json_encode($result);
    header('Content-Type: application/json');
    echo $data;
}

function get_array($sql,$params){
    $smt= $this->pdo_start($sql);
    $smt->execute($params);
    $result = $smt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function get_column($sql,$params){
    $smt= $this->pdo_start($sql);
    $smt->execute($params);
    $result = $smt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}
function get_array_all($sql,$params){
    $smt= $this->pdo_start($sql);
    $smt->execute($params);
    $result = $smt->fetchAll(PDO::FETCH_ASSOC);
    return $result;

}
function getEnv()
    {
        $currentDirectory = __DIR__;
        $parentDirectory = dirname($currentDirectory);
        $filePath = $parentDirectory . '/.env';
        $variables = array();

        // Read the contents of the .env file
        $envVariables = file_get_contents($filePath);

        // Parse the variables into an associative array
        $lines = explode("\n", $envVariables);
        foreach ($lines as $line) {
            $line = trim($line);

            // Ignore comments and empty lines
            if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $variables[$key] = $value;
            }
        }

        return $variables;
    }
}