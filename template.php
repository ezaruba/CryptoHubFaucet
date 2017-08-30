<!DOCTYPE html>
<html class="">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo FAUCET_TITLE ?></title>
	<meta name="description" content="cryptocurrency, pool, marketplace, faucet">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="/style.css">
	<meta name="coinzilla" content="coinzilla-verification" />
	<meta name="adbit-site-verification" content="c457fea3847683664f4903b315313807841de2a380e157b93e30cf4ff274c634" />
	<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
</head>
<body class="">
    <div class="top"><div style="background:url('<?php echo FAUCET_LOGO ?>');" class="logo"></div><?php echo FAUCET_TITLE ?></div>
    
    <div class="banners_top">
        <div class="banner">
	    <!--- Advertisment #1 code  --->
        </div>
        <div class="banner">
	    <!--- Advertisment #2 code  --->
        </div>
    </div>
    
    <?php if($started){ ?>


	


    <form class="faucet_form" method="post">

		<?php if($form_error){ ?>
		<div class="form_error">
		    <?php echo $form_error ?>
		</div>
		<?php } ?>
		
		<?php if($claim_result){ ?>
		<div class="form_error2">
		    <?php echo $claim_result ?>
		</div>
		<?php } ?>


        <div class="your_adr">Your <?php echo COIN_CODE;?> adress: <?php echo $_SESSION["adr"]?> <a href="?logout=1">logout</a></div>

	<?php if(FAUCET_MODE == "game_boxes"){ ?>
        <div class="title"><?php echo FAUCET_GAME_BOXES_TEXT;?></div>
        <div class="game_boxes">
            <?php for($i=0;$i<9;$i++){ ?>
                <div class="box"><img src="<?php echo FAUCET_GAME_BOXES_BOX_IMG;?>"><input type="checkbox" name="boxes_sel[]" value="<?php echo $i ?>"></div>
            <?php } ?>
            <div class="done"><input type="submit" value="Play"></div>
        </div>
	<?php } ?>

	<?php if(FAUCET_MODE == "common"){ ?>
        <div class="common">
            <div class="done"><input type="submit" name="reward_but" value="Get reward"></div>
        </div>
	<?php } ?>


    </form>
    
    <script>
        $(document).ready(function(){
            $(".game_boxes .box").click(function(){
                if($(this).hasClass("sel")){
                    $(this).removeClass("sel");
                    $(this).find("input").prop('checked', false);
                }else{
                    alr = $(".game_boxes .box.sel").length;
                    if(alr<2){
                        $(this).addClass("sel");
                        $(this).find("input").prop('checked', true);
                    }
                }
            });
        });
    </script>
    
    <?php }else{ ?>
    
    <form class="faucet_form" method="post">
        <div class="text">
            <?php echo FAUCET_WELCOME_TEXT ?>
            <br>
            <b>You can win <?php echo FAUCET_REWARD_MIN ?> - <?php echo FAUCET_REWARD_MAX ?> <?php echo COIN_CODE ?>  every <?php echo FAUCET_INTERVAL_MINUTES ?> minutes</b>
        </div>
        
	<div>
        	<!--- Advertisment #7 (middle of the page) code  --->
	</div>
    
        <div class="balance">Faucet balance: <?php echo $faucet_balance ?> <?php echo COIN_CODE ?></div>
        <?php if($form_error){ ?>
        <div class="form_error">
            <?php echo $form_error ?>
        </div>
        <?php } ?>
        
        <?php if($claim_result){ ?>
        <div class="form_error2">
			<?php if(FAUCET_MODE == "game_boxes"){ ?>
				<div>
				<?php if($win_max){ ?>
					<?php echo FAUCET_GAME_BOXES_WIN_TEXT ?>
				<?php }else{ ?>
					<?php echo FAUCET_GAME_BOXES_LOSE_TEXT ?>
				<?php } ?>
				</div>
			<?php } ?>

            <?php echo $claim_result ?>
        </div>
        <?php } ?>
        
        <?php if($time_left>0){ ?>
        <div class="waiting" rel="<?php echo $time_left*60 ?>">
            You have to wait <?php echo $time_left ?> minutes
        </div>
        <?php }else{ ?>
        <div class="fields">
            <span class="lft">Enter your <?php echo COIN_CODE;?> adress</span>
            <span><input placeholder="<?php echo COIN_CODE;?> address" size="40" type="text" name="adr"></span>
        </div>
	<?php if(CAPTCHA_TYPE == "Solvemedia") { ?>
        <div class="captcha">
            <?php echo solvemedia_get_html(SM_CHALLENGEKEY);?>
        </div>
	<?php } ?>
        <div class="submit">
            <input type="submit" value="Play">
        </div>
        <?php } ?>
    </form>
    <?php } ?>
    
    
    <?php if(isset($_SESSION["adr"]) && FAUCET_REF_PERCENT){ ?>
    
    <div class="faucet_form" style="margin-top:100px;">
        <p>You will get <?php echo FAUCET_REF_PERCENT ?>% of winnings of everyone who makes claims entering to faucet by your link:</p>
        <div class="lnk" style="background:yellow;color:maroon;padding:8px;"><?php echo showHomeUrl();?>?r=<?php echo $_SESSION["adr"] ?></div>
    </div>
    
    <?php } ?>
    
    
    <div class="banners_bot">
        <div class="banner">
            <!--- Advertisment #3 code  --->

        </div>
        <div class="banner">
            <!--- Advertisment #4 code  --->

        </div>
        <div class="banner">
	    <!--- Advertisment #5 code  --->	
            
        </div>
        <div class="banner">
	    <!--- Advertisment #6 code  --->
            
        </div>
    </div>
    

    <footer>
	Faucet works using <a target="_blank" href="https://cryptohub.online/">Cryptohub</a> API / Script.  <span class="gentime">Page generation time: <?php echo round(microtime(true)-$gen_start,3); ?> sec.</span>
    </footer>

    <!--- JS scripts from advertisings  --->		

    <!--- end JS scripts from advertisings  --->

    <script src="ads.js"></script>
    <script>
    $(function() {
        setTimeout(function(){
            ifr_block = $("iframe:first").attr("stndz-blocked");
            if(document.getElementById('bPXaQSujvDNs') && String(ifr_block)=="undefined"){
                
            } else {
                $("body").html('<div class="adblock">Please disable Adblock</div>');
            }
        },500);
		if($(".waiting").length){
			delay = Number($(".waiting").attr("rel"));
			setTimeout(function(){
				location.href="?ready=1";
			},delay*1000);
		}
    });
    </script>
    	
    
</body>
</html>
