<?php



function cryptohub_qsencode($data) {
        $req = "";
        foreach ( $data as $key => $value )
                $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
}

function cryptohub_http_post($host, $path, $data, $port = 443) {

        $req = cryptohub_qsencode($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: solvemedia/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;
        

        $response = '';
        if( false == ( $fs = @fsockopen( ($port == 443 ? 'ssl://' : "") . $host, $port, $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1024); // One TCP-IP packet [sic]
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);
        

        return json_decode($response[1], true);
}

function cryptohub_faucet_balance()
{
    $bal = cryptohub_http_post(CRYPTOHUB_SERVER, "/api/faucets/balance/", array("key"=>CRYPTOHUB_FAUCET_KEY));
    //var_dump($bal)
    if(isset($bal["balance"])){
        return $bal["balance"];
    }else{
        return $bal["error_text"];
    }
}

function cryptohub_faucet_pay($adr, $sum, $ref=0)
{
    $ret = cryptohub_http_post(CRYPTOHUB_SERVER, "/api/faucets/payout/", array("key"=>CRYPTOHUB_FAUCET_KEY, "adress"=>$adr, "amount"=>$sum, "referal"=>$ref));
    return $ret;
    
}


?>
