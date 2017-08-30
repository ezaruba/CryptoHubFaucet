<?php
// Captcha settings
//define("CAPTCHA_TYPE", "Solvemedia");  // can be: "Solvemedia" ,  "no" to disable
define("CAPTCHA_TYPE", "Solvemedia");
define("DB_FILE_NAME", "data.db");

// Solvemedia settings
define("SM_CHALLENGEKEY", "");
define("SM_PRIVKEY", "");
define("SM_HASHKEY", "");

// Faucet settings
define("FAUCET_REWARD_MIN", 0.0001);
define("FAUCET_REWARD_MAX", 0.0005);
define("FAUCET_INTERVAL_MINUTES", 3);
define("FAUCET_REF_PERCENT", 10);  // 0 to disable ref system


//Faucet gaming settings
define("FAUCET_MODE", "common");  // can be: "common" (without gaming), "game_boxes"

//Game boxes settings
define("FAUCET_GAME_BOXES_TEXT", "Choose 2 boxes to play. Only one of boxes contains maximum reward.");
define("FAUCET_GAME_BOXES_BOX_IMG", "/imgs/box.png");
define("FAUCET_GAME_BOXES_WIN_TEXT", "Yes! You have found the right box and got maximum reward!");
define("FAUCET_GAME_BOXES_LOSE_TEXT", "Oh, no! You haven't found the right box. You are getting minimum reward 0.0001 XLR");


// Cryptohub settings
define("CRYPTOHUB_SERVER", "cryptohub.online");
define("CRYPTOHUB_FAUCET_KEY", "");

// Template settings
define("COIN_NAME", "Solaris");
define("COIN_CODE", "XLR");
define("FAUCET_TITLE", "XLR - faucet");
define("FAUCET_WELCOME_TEXT", "Welcome to XLR-Faucet! Here you can get some XLR, fill the form to start.");
define("FAUCET_LOGO", "https://cryptohub.online/static/img/XLR-icon.png"); // logo should be 50*50

?>
