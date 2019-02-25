<?php 
$password = "2019#$&CA%SA?¿PLA!RRE.-*".date("Y-m-d")."'HG2019CASAPLARRE.";
$token = '8478348459d717c9958fe4025d5426fcb59bdce7';#sha1(md5($password));
#29b8b734849b000d4b87ab2d24ff63bf4d320ff8
#8478348459d717c9958fe4025d5426fcb59bdce7  
echo curl_get_contents('http://www.casaplarre.com/CASA/SATI/wsdirrep', 'prt=salvador&token='.$token, 'http://www.casaplarre.com/');
function curl_get_contents($url, $postdata, $location)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Connection: close','Host: '.$location));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec ($ch);

    curl_close ($ch);
    return $data;
}

 ?>