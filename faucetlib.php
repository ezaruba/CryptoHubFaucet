<?php
    $DB = null;
    $claim_result = "";
    $time_left = null;
	$win_max = null;
    $form_error = null;

    if(isset($_SESSION["started"])){
        $started = $_SESSION["started"];
    }else{
        $started = null;
    }
    
    function count_decimals($x){
        return  strlen(substr(strrchr($x+"", "."), 1));
    }

    function random($min, $max){
        $decimals = max(count_decimals($min), count_decimals($max));
        $factor = pow(10, $decimals);
        return rand($min*$factor, $max*$factor) / $factor;
    }

	function showHomeUrl()
	{
		$prot = "http";
		if(isset($_SERVER['HTTPS'])){
			$prot = "https";
		}
		return $prot . "://" . $_SERVER['SERVER_NAME'] . "/";
	}

   
	function openDB()
	{
		$file = "db-folder/" . DB_FILE_NAME;
		$db = new SQLite3($file);
		$db->busyTimeout(5000);
		$db->exec('PRAGMA journal_mode=WAL;');
		return $db;
	}
	
	function initDB()
	{
		$db = openDB();
		$db->exec('CREATE TABLE IF NOT EXISTS claims (`id` INTEGER PRIMARY KEY, `ip` VARCHAR(255), `adr` VARCHAR(255), `dt` DATETIME, `amount` DECIMAL)');
		return $db;
	}

	function getBalance()
	{
		$cache = "db-folder/" . "bal_cache.dat";
		if(!file_exists($cache)){
			$bal = cryptohub_faucet_balance();
			file_put_contents($cache,$bal);
			return $bal;
		}
		$dif = time() - filemtime($cache);
		if($dif>60){
			$bal = cryptohub_faucet_balance();
			file_put_contents($cache,$bal);
			return $bal;
		}
		return file_get_contents($cache);
	}
	
	function claimTimeLeft($ip,$adr="")
	{
        global $DB;
        if($adr){
            $statement = $DB->prepare("SELECT * FROM claims WHERE `ip`=:ip OR `adr`=:adr ORDER BY `dt` DESC");
            $statement->bindValue(':ip', $ip, SQLITE3_TEXT);
            $statement->bindValue(':adr', $adr, SQLITE3_TEXT);
        }else{
            $statement = $DB->prepare("SELECT * FROM claims WHERE `ip`=:ip ORDER BY `dt` DESC");
            $statement->bindValue(':ip', $ip, SQLITE3_TEXT);
        }
        $result = $statement->execute();
        if(count($result)){
            $d = $result->fetchArray();
            $dif = time() - strtotime($d["dt"]);
            if(ceil($dif / 60) < FAUCET_INTERVAL_MINUTES){
                return ceil(FAUCET_INTERVAL_MINUTES - ($dif / 60));
            }
            return 0;
        }
	}
	
	function claimSuccess($ip, $amount, $adr)
	{
        global $DB;
        $statement = $DB->prepare("INSERT INTO claims (`id`,`ip`,`adr`,`dt`,`amount`) VALUES (NULL, :ip,:adr,:dt,:amount)");
        $statement->bindValue(':ip', $ip, SQLITE3_TEXT);
        $statement->bindValue(':adr', $adr, SQLITE3_TEXT);
        $statement->bindValue(':amount', $amount, SQLITE3_TEXT);
        $statement->bindValue(':dt', date("Y-m-d H:i:s"), SQLITE3_TEXT);
        $result = $statement->execute();
        if(count($result)){
            return $result->fetchArray();
        }
	}
	
	function faucet_process($ip, $adr)
    {
        global $time_left,$claim_result,$started,$win_max;
        //$mode
        if(FAUCET_MODE=="game_boxes"){
            if(isset($_SESSION["started"]) && $_SESSION["started"] == 1){
                if(isset($_POST["boxes_sel"])){
                    $adr = $_SESSION["adr"];
                    $boxes_sel = $_POST["boxes_sel"];
                    if(count($boxes_sel) == 2){
                        $i1 = $boxes_sel[0];
                        $i2 = $boxes_sel[1];
                        $sum = max(array($_SESSION["prizes"][$i1],$_SESSION["prizes"][$i2]));
						if($sum == FAUCET_REWARD_MAX){
							$win_max = true;
						}
                        $ret = cryptohub_faucet_pay($adr, $sum);
                        if(isset($ret["success"])){
                            $claim_result = $ret["message"];
                            claimSuccess($ip, $sum, $adr);
                            $time_left = claimTimeLeft($_SERVER["REMOTE_ADDR"]);
                            if(isset($_SESSION["r"]) && $_SESSION["r"] != $adr){
                                cryptohub_faucet_pay($_SESSION["r"], $sum*0.1, 1);
                            }
                            $started = null;
                            unset($_SESSION["started"]);
                        }else{
                            $form_error = $ret["error_text"];
                        }
                    }
                }
            }else{
                $started = $_SESSION["started"] = 1;
                $_SESSION["adr"] = $adr;
                $reward_min = FAUCET_REWARD_MIN;
                $_SESSION["prizes"] = [$reward_min,$reward_min,$reward_min,$reward_min,$reward_min,$reward_min,$reward_min,$reward_min,$reward_min];
                $i = rand(0,8);
                $_SESSION["prizes"][$i] = FAUCET_REWARD_MAX; 
            }
        }else{
			if(isset($_POST["reward_but"])){
		        $sum = random(FAUCET_REWARD_MIN, FAUCET_REWARD_MAX);
		        $ret = cryptohub_faucet_pay($adr, $sum);
		        if(isset($ret["success"])){
		            $claim_result = $ret["message"];
		            claimSuccess($ip, $sum, $adr);
		            $time_left = claimTimeLeft($_SERVER["REMOTE_ADDR"]);
		            if(isset($_SESSION["r"]) && $_SESSION["r"] != $adr){
		                cryptohub_faucet_pay($_SESSION["r"], $sum*0.1, 1);
		            }
					$started = null;
                    unset($_SESSION["started"]);
		        }else{
		            $claim_result = $ret["error_text"];
            	}
            }else{
				$started = $_SESSION["started"] = 1;
                $_SESSION["adr"] = $adr;
			}
        }
        
    }
    
    function faucetInit()
    {
        global $time_left,$claim_result,$started;
        $time_left = claimTimeLeft($_SERVER["REMOTE_ADDR"]);
        if($started){
            if(!isset($_SESSION["adr"])){
                $started = null;
                unset($_SESSION["started"]);
            }
        }


		if(isset($_GET["logout"]) && $_GET["logout"]==1){
                $started = null;
                $auth = null;
                unset($_SESSION["started"]);
                unset($_SESSION["adr"]);
                header("Location:/");
        }
        
        if(isset($_GET["r"])){
            $_SESSION["r"] = $_GET["r"];
        }

        
        if($time_left<=0){
            if(!$started && isset($_POST["adr"])){if((CAPTCHA_TYPE == "no" || isset($_POST["adcopy_challenge"])) && strlen($_POST["adr"])>=8){
				$captcha_error = null;
				if(CAPTCHA_TYPE == "Solvemedia"){
					$solvemedia_response = solvemedia_check_answer(SM_PRIVKEY,
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["adcopy_challenge"],
                        $_POST["adcopy_response"],
                        SM_HASHKEY);      
					$captcha_passed = $solvemedia_response->is_valid;
					if(!$captcha_passed){
						$captcha_error = $solvemedia_response->error;
					}
				}
                           
                if ($captcha_error && CAPTCHA_TYPE != "no") {
                    $form_error = "Error captcha: " . $captcha_error;
                }
                else {
                    $time_left = claimTimeLeft($_SERVER["REMOTE_ADDR"]);
                    if($time_left<=0){
                        $time_left2 = claimTimeLeft($_SERVER["REMOTE_ADDR"],htmlspecialchars($_POST["adr"]));
                        if($time_left2<=0){
                            faucet_process($_SERVER["REMOTE_ADDR"],htmlspecialchars($_POST["adr"]));
                        }else{
                            $claim_result = "You have to wait " . $time_left2 . " minutes";
                        }
                    }
                }
            }}
            else if($started){
                faucet_process($_SERVER["REMOTE_ADDR"],$_SESSION["adr"]);
            }
        }
    }
	
	$DB = initDB();
	
?>
