<?php
$url = 'https://universidadbiossmann.com/webservice/_xws/_ws_login.php';
  $postdata = http_build_query(
    array(
        'username'  => 'xbalanque',
        'password'  => '.arkan33',
        'type'      => 'checkpass'
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata,
            
        )
    );

    if($context  = stream_context_create($opts)){ //$result = file_get_contents($url, false, $context);
        if($response = file_get_contents($url, false, $context)){ //$array = json_decode($json,true);
            echo "RESPONSE: ".$response;
        }else{
            $error = error_get_last();
            echo "Error en File get contents: ". $error['message'];
        }
    }else{
        $error = error_get_last();
        echo "Error en stream context create: ". $error['message'];
    }
?>