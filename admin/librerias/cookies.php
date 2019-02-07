<?php
$cookie_name = COOKIE_DEF;
$cookie_expire = time() + 60*60*24*7;//una semana
$cookie_path = '/';
$cookie_domain = COOKIE_DOMAIN;
$cookie_secure = false;
$cookie_httponly = false;

$desplegar_intro = TRUE;


$intro_cookie = '_intro';

if(isset($_COOKIE[$cookie_name.$intro_cookie])){//YA EXISTE COOKIE
	$cookie_stored = $_COOKIE[$cookie_name.$intro_cookie];

	 //if(isset($cookie_stored['intro'])){//EXISTE VAR INTRO
	 	//echo ' | existe intro';
		if($cookie_stored==0){//desplegar intro
			$desplegar_intro = TRUE;
			$cookie_values = 1;

			setcookie($cookie_name.$intro_cookie, $cookie_values, $cookie_expire, $cookie_path,$cookie_domain);

		}else if($cookie_stored>0){//NO desplegar intro
			$desplegar_intro = FALSE;
		}
	//}else{
	//	echo 'Error en cookie';

	//}
}else{//NO EXISTE COOKIE LA ESTABLEZCO
	$cookie_values = 1;
	setcookie($cookie_name.$intro_cookie, $cookie_values, $cookie_expire, $cookie_path,$cookie_domain);
	//setcookie($cookie_name, serialize($cookie_values), $cookie_expire, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);
}
//if( $desplegar_intro ) echo 'TRUE';
//BORRAR COOKIE
//setcookie($cookie_name,'',1, $cookie_path);
// setcookie('COOKIE_DEF','',1, $cookie_path);
//setcookie('IntranetBiossmann_intro','',1, $cookie_path);
//setcookie($cookie_name.$intro_cookie,'',0, $cookie_path);

/*

if(isset($_COOKIE[$cookie_name])){//YA EXISTE COOKIE
	$cookie_stored = unserialize($_COOKIE[$cookie_name]);

	 if(isset($cookie_stored['intro'])){//EXISTE VAR INTRO
	 	//echo ' | existe intro';
		if($cookie_stored['intro']==0){//desplegar intro
			$desplegar_intro = TRUE;
			$cookie_values = serialize(array('intro'=>1));

			setcookie($cookie_name, $cookie_values, $cookie_expire, $cookie_path);

		}else if($cookie_stored['intro']>0){//NO desplegar intro
			$desplegar_intro = FALSE;
		}
	}else{
		echo 'Error en cookie';
	}
}else{//NO EXISTE COOKIE LA ESTABLEZCO
	$cookie_values = serialize(array('intro'=>1));
	setcookie($cookie_name, $cookie_values, $cookie_expire, $cookie_path);
	//setcookie($cookie_name, serialize($cookie_values), $cookie_expire, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);
	$desplegar_intro = TRUE;
}
*/

?>