<?php
date_default_timezone_set('America/Mexico_City');
header('Content-Type: text/html; charset=UTF-8');
error_reporting(E_ALL);
//Validar que este archivo sea cargado por un Include y no directamente
if(!defined('VIEWABLE')) {
	header('HTTP/1.0 404 Not Found');
	exit;
}else{
	$thisserver = $_SERVER['SERVER_NAME'];

	$debug = TRUE;
	if($debug) { error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('error_reporting', E_ALL); }

	if($thisserver == 'localhost'){
		define('WEB_PATH',			'http://localhost/soybiossmann/');
		define('APP_PATH',			'D:\PoisonStinger\wamp64\www\soybiossmann/');
		define('APP_URL',			WEB_PATH);
		define('COOKIE_DOMAIN',		'localhost/soybiossmann');
		define('BD_HOST', 			'localhost');
		define('DB_USER', 			'root');
		define('DB_PSW', 			'arkan');
		define('DB_NAME', 			'soybiossmann');
		define('PREFIJO',			'cms_');
		define('SENDER_MAIL',		'noreply@biossmann.com');
		define('SENDER_PASS',		'');
		define('SENDER_MAIL_HOST',	'192.168.4.1');
		define('SENDER_MAIL_PORT',	'');
		define('SENDER_MAIL_SECURE','');
		define('SENDER_MAIL_NAME',	'Intranet SoyBiossmann');
	}else if($thisserver=='soy.biossmann.com'){
		define('WEB_PATH',			'https://soy.biossmann.com/');
		define('APP_PATH',			'/usr/local/apache/htdocs_ssl/');
		define('HOME_DIR',			'');
		define('APP_URL',			WEB_PATH);
		define('COOKIE_DOMAIN',		'soy.biossmann.com');
		define('BD_HOST', 			'localhost');
		define('DB_USER', 			'soybiossmann');
		define('DB_PSW', 			'8Wa9poG-t.X');
		define('DB_NAME', 			'soybiossmann');
		define('PREFIJO',			'cms_');
		define('SENDER_MAIL',		'noreply@biossmann.com');
		define('SENDER_PASS',		'');
		define('SENDER_MAIL_HOST',	'192.168.4.1');
		define('SENDER_MAIL_PORT',	'');
		define('SENDER_MAIL_SECURE','');
		define('SENDER_MAIL_NAME',	'Intranet SoyBiossmann');
	}

	$prefijo = PREFIJO;

	define('ADMIN_PATH',		APP_PATH.'admin/');
	define('ADMIN_URL',			WEB_PATH.'admin/');
	
	define('APP_NAME', 		'SoyBiossmann');
	define('AESCRYPT',		'$0y.Bios5m4n');

	define('COOKIE_DEF',		'soyBiossmann');
	define('SESSION_DATA_SET',	'soyBiossmann');
	define('AES_ENCRYPT',		'$0y.Bios5m4n');
	define('DEBUG',				$debug);

	
	if(!defined('SYS_NAME')){
		define('SYS_NAME',	'Administración de websites');
		
		define('CLASS_PATH',		APP_PATH.'admin/class/');
		define('LIB_PATH',			APP_PATH.'admin/librerias/');
		define('CONF_PATH',			APP_PATH.'admin/cnf/');
		define('FILE_PATH',			APP_PATH.'webfiles/');
		define('WEB_FILE_PATH',		WEB_PATH.'webfiles/');
		define('TEMPLATE_PATH',		APP_PATH.'templates/');
		define('NEWS_PATH',			APP_PATH.'webimgs/noticias/');
		define('WEB_NEWS_PATH',		WEB_PATH.'webimgs/noticias/');
		define('BANNER_PATH',		APP_PATH.'webimgs/banners/');
		define('WEB_BANNER_PATH',	WEB_PATH.'webimgs/banners/');

		define('APP_IMG_PATH',		APP_PATH.'webimgs/');
		define('APP_LOGO_PATH',		APP_IMG_PATH.'empresas/');
		
		define('IMG_PATH',			APP_PATH.'webimgs/');
		define('WEB_IMG_PATH',		WEB_PATH.'webimgs/');

		define('BORRADO',			1);
		
		define('DEFAULT_DATE',		'2019-01-01 00:00:00');
		define('SITE',				'intranet');
	}

	/*ini_set('session.cookie_lifetime',	0);
	ini_set('session.use_cookies',		'On');
	ini_set('session.use_only_cookies',	'On');
	ini_set('session.use_strict_mode',	'On');
	ini_set('session.cookie_httponly',	'On');
	ini_set('session.gc_maxlifetime',	60*60);
	ini_set('session.use_trans_sid',	'Off');
	ini_set('session.referer_check',	HOME_DIR.'/'.WEB_PATH);
	ini_set('session.cache_limiter',	'nocache');
	ini_set('session.hash_function',	'sha256');

	if (!isset($_SESSION)) session_start();*/

	#header('Content-Type: text/html; charset=UTF-8');

	$referer = WEB_PATH;

	#include_once(LIB_PATH.'sesiones.php');
	include_once(LIB_PATH.'Mobile_Detect_2.8.17.php');
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

	include_once(CLASS_PATH . "admin.class.php");#se crea un $myadm
	#include_once(LIB_PATH . "functions.inc.php");
	include_once(CLASS_PATH."permisos.class.php");
	include_once(CLASS_PATH."estructura.class.php");
	include_once(LIB_PATH."funciones.inc.php");

	// LOAD PHPMailer
	require_once(LIB_PATH.'PHPMailer/class.phpmailer.php');
	include_once(LIB_PATH."PHPMailer/class.smtp.php");
	include_once(CLASS_PATH."mail.class.php");#se crea un $mail

	#include_once(CLASS_PATH."admin.class.php");

	if(DEBUG) $myAdmin->debug=1;

	$data1 = NULL;
	if(isset($_GET['data1']) && $_GET['data1'] != ''){ $data1 = $_GET['data1']; }else{ $data1 = 'index'; }
	$data2 = NULL;
	if(isset($_GET['data2']) && $_GET['data2'] != ''){ $data2 = $_GET['data2']; }
	$data3 = NULL;
	if(isset($_GET['data3']) && $_GET['data3'] != ''){ $data3 = $_GET['data3']; }
	$data4 = NULL;
	if(isset($_GET['data4']) && $_GET['data4'] != ''){ $data4 = $_GET['data4']; }
	$data5 = NULL;
	if(isset($_GET['data5']) && $_GET['data5'] != ''){ $data5 = $_GET['data5']; }
	$data6 = NULL;
	if(isset($_GET['data6']) && $_GET['data6'] != ''){ $data6 = $_GET['data6']; }

	$page = $data1;
}
?>