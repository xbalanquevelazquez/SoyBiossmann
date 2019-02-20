<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
/*
define('WWW_HOST',		'http://localhost');//192.168.62.168
define('HOME_DIR',		'intranetBiossmann');
define('COOKIE_DEF',	'intranetBiossmann');
define('COOKIE_DOMAIN',	'localhost');
define('HTDOCS_HOST',	'C:\XiuhBalam\wamp\www\intranetBiossmann/');
define('WS_PROTOCOL', 	'https://');//
define('WS_DOMAIN', 	'universidadbiossmann.com');//
define('WS_LOGIN', 		'/webservice/_xws/');//
define('ENCRYPT_KEY', 	'63ca24cd29bbc9211dfa413008fe1d92');//
define('ENCRYPT_METHOD','aes-256-cbc');
define('SESSION_DATA_SET','Intranet');

ini_set('session.cookie_lifetime',0);
ini_set('session.use_cookies','On');
ini_set('session.use_only_cookies','On');
ini_set('session.use_strict_mode','On');
ini_set('session.cookie_httponly','On');
ini_set('session.gc_maxlifetime',60*60);
ini_set('session.use_trans_sid','Off');
ini_set('session.referer_check',WWW_HOST);
ini_set('session.cache_limiter','nocache');
ini_set('session.hash_function','sha256');


if (!isset($_SESSION)) session_start();

header('Content-Type: text/html; charset=ISO-8859-1');

*/
//('mysql:host=localhost;dbname=dashboard', 'dashboard', 'b10ssd4shb04rd.-')
//https://192.168.62.168/moodle_v1/
// print_r($_SERVER);

//$referer = WWW_HOST.'/'.HOME_DIR; //ruta y script /foo/bar.php; //www.example.com
//$_SERVER['REQUEST_URI'];


//include_once(LIB_PATH.'sesiones.php');
//include_once(LIB_PATH.'conexion.php');
//include_once(LIB_PATH.'funciones.php');
define('DIRECTORIO_BD_HOST', 		'localhost');
define('DIRECTORIO_DB_USER', 		'dashboard');
define('DIRECTORIO_DB_PSW', 		'b10ssd4shb04rd.-');
define('DIRECTORIO_DB_NAME', 		'dashboard');

$conn = new Conexion();
$conn->conectar( DIRECTORIO_BD_HOST , DIRECTORIO_DB_USER , DIRECTORIO_DB_PSW , DIRECTORIO_DB_NAME );

include_once(LIB_PATH.'Mobile_Detect_2.8.17.php');
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');