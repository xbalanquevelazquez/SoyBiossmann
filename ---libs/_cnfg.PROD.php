<?php 
$debug = TRUE;

if($debug) { error_reporting(E_ERROR); ini_set('display_errors', 1); }
//0 ----- E_ALL

if(!defined('VIEWABLE')) {
	die('Inaccesible');
}

define('WWW_HOST',		'https://soy.biossmann.com');//192.168.62.168
define('HOME_DIR',		'');
define('APP_URL',		WWW_HOST.'/'.HOME_DIR.'/');
define('COOKIE_DEF',	'soyBiossmann');
define('COOKIE_DOMAIN',	'soy.biossmann.com');
define('HTDOCS_HOST',	'/usr/local/apache/htdocs_ssl');
define('BD_HOST', 		'localhost');
define('DB_USER', 		'root');
define('DB_PSW', 		'arkan');
define('DB_NAME', 		'soybiossmann');
// define('WS_PROTOCOL', 	'https://');//
// define('WS_DOMAIN', 	'universidadbiossmann.com');//
// define('WS_LOGIN', 		'/webservice/_xws/');//
// define('ENCRYPT_KEY', 	'63ca24cd29bbc9211dfa413008fe1d92');//
// define('ENCRYPT_METHOD','aes-256-cbc');
define('SESSION_DATA_SET','soyBiossmann');

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

header('Content-Type: text/html; charset=UTF-8');

$referer = WWW_HOST.'/'.HOME_DIR;

include_once('sesiones.php');
#include_once('conexion.php');
include_once('funciones.php');
include_once('Mobile_Detect_2.8.17.php');
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
//include_once('cookies.php');
