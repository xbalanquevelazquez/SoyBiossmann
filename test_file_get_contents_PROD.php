<?php
$url = 'https://universidadbiossmann.com/webservice/_xws/_ws_login.php';//192.168.150.51
$location = 'universidadbiossmann.com';
echo ini_get ( 'allow_url_fopen' );
echo "<br>";
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
            'header'  => array('Content-type: application/x-www-form-urlencoded', 'Connection: close','Host: '.$location),
            'content' => $postdata,
            'ignore_errors' => true
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


    echo "<hr>";
//     $content=file_get_contents("http://www.google.com",FALSE,NULL,0,20);
// echo $content; 
?>