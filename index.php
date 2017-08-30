<?php
$gen_start=microtime(true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(isset($_GET["logout"]) && $_GET["logout"]==1){
				var_dump("log out");
                $started = null;
                $auth = null;
                unset($_SESSION["started"]);
                unset($_SESSION["adr"]);
                header("Location:/");
        }


include("settings.php");

require_once("solvemedialib.php");
require_once("cryptohublib.php");
require_once("faucetlib.php");


$form_error=null;
$faucet_balance = getBalance();

faucetInit();

include("template.php");
?>

