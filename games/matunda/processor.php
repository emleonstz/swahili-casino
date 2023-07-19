<?php
session_start();
require('../../vendor/autoload.php');

use Emleons\Games\SecureLogin;
use Emleons\Games\Functions;

$secure = new SecureLogin;
$funtions = new Functions;
if (isset($_POST['bet']) && isset($_POST['pl']) && $_POST['gamepad'] != null) {
    if ($secure->checklogin()) {
        if ($secure->isActive()) {
            $apiKey = $_POST['gamepad'];
            if ($secure->validateApiKey($apiKey)) {
                $bet = $funtions->clean($_POST['bet']);
                if ($funtions->chekGameSession() == "login") {
                    $user = $secure->decrypt($secure->get_user());
                    $funtions->place_bet($bet, $user, "1");
                } else {
                    $funtions->setGamesession();
                    $user = $secure->decrypt($secure->get_user());
                    $funtions->place_bet($bet, $user, "1");
                }
            } else {
                $funtions->reelError("unAuthorised");
            }
        } else {
            if ($secure->isBlocked()) {
                //user blocked
                $funtions->reelError("block");
            } else {
                //not activated
                $funtions->reelError("activate");
            }
        }
    } else {
        $funtions->reelError("notlogin");
    }
} else {
    if ($secure->checklogin()) {
        if ($secure->isActive()) {
            $funtions->reelError("unknownBet");
        }else{
            $funtions->reelError("block");
        }
    }else{
        $funtions->reelError("notlogin");
    }
}
