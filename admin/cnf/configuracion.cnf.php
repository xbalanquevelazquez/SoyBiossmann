<?php
#echo ;
//Validar que este archivo sea cargado por un Include y no directamente
if(!defined('VIEWABLE')) {
	header('HTTP/1.0 404 Not Found');
	exit;
}else{
	$thisserver = $_SERVER['SERVER_NAME'];

	date_default_timezone_set('America/Mexico_City');
	$debug = TRUE;
	if($debug) { error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('error_reporting', E_ALL); }

	if($thisserver == 'localhost'){
		define('WWW_HOST',			'http://localhost');
		define('HOME_DIR',			'soybiossmann');
		define('APP_URL',			WWW_HOST.'/'.HOME_DIR.'/');
		define('APP_PATH',			'D:\PoisonStinger\wamp64\www\soybiossmann/');#
		define('WEB_PATH',			'http://localhost/soybiossmann/');//
		define('COOKIE_DOMAIN',		'localhost');
		define('BD_HOST', 			'localhost');
		define('DB_USER', 			'root');
		define('DB_PSW', 			'arkan');
		define('DB_NAME', 			'soybiossmann');
		define('PREFIJO',			'cms_');
	}else if($thisserver=='soy.biossmann.com'){
		define('WWW_HOST',			'https://soy.biossmann.com');
		define('HOME_DIR',			'');
		define('APP_URL',			WWW_HOST.'/');
		define('APP_PATH',			'/usr/local/apache/htdocs_ssl/');#
		define('WEB_PATH',			'https://soy.biossmann.com/');//
		define('COOKIE_DOMAIN',		'soy.biossmann.com');
		define('BD_HOST', 			'localhost');
		define('DB_USER', 			'soybiossmann');
		define('DB_PSW', 			'8Wa9poG-t.X');
		define('DB_NAME', 			'soybiossmann');
		define('PREFIJO',			'cms_');
	}

	define('COOKIE_DEF',		'soyBiossmann');
	#define('HTDOCS_HOST',		'D:\PoisonStinger\wamp64\www\soybiossmann/');
	$prefijo = PREFIJO;
	define('SESSION_DATA_SET',	'soyBiossmann');
	define('AES_ENCRYPT',		'$0y.Bios5m4n');
	define('DEBUG',				$debug);

	
	if(!defined('SYS_NAME')){
		define('SYS_NAME','Administración de websites');
		
		define('CLASS_PATH',		APP_PATH.'admin/class/');
		define('LIB_PATH',			APP_PATH.'admin/librerias/');
		define('CONF_PATH',			APP_PATH.'admin/cnf/');
		define('FILE_PATH',			APP_PATH.'webfiles/pdf/');
		define('WEB_FILE_PATH',		WEB_PATH.'webfiles/pdf/');
		define('TEMPLATE_PATH',		APP_PATH.'admin/templatesdir/');
		define('NEWS_PATH',			APP_PATH.'webimgs/noticias/');
		define('WEB_NEWS_PATH',		WEB_PATH.'webimgs/noticias/');
		define('BANNER_PATH',		APP_PATH.'webimgs/banners/');
		define('WEB_BANNER_PATH',	WEB_PATH.'webimgs/banners/');

		define('APP_IMG_PATH',		APP_PATH.'webimgs/');
		define('APP_LOGO_PATH',		APP_IMG_PATH.'empresas/');
		
		define('WEB_IMG_PATH',		WEB_PATH.'webimgs/');
		define('WEB_LOGO_PATH',		WEB_IMG_PATH.'empresas/');

		define('BORRADO',			1);
		
		define('DEFAULT_DATE',		'2019-01-01 00:00:00');
		define('SITE',				'index');
	}

	ini_set('session.cookie_lifetime',	0);
	ini_set('session.use_cookies',		'On');
	ini_set('session.use_only_cookies',	'On');
	ini_set('session.use_strict_mode',	'On');
	ini_set('session.cookie_httponly',	'On');
	ini_set('session.gc_maxlifetime',	60*60);
	ini_set('session.use_trans_sid',	'Off');
	ini_set('session.referer_check',	WWW_HOST);
	ini_set('session.cache_limiter',	'nocache');
	ini_set('session.hash_function',	'sha256');

	if (!isset($_SESSION)) session_start();

	header('Content-Type: text/html; charset=UTF-8');

	$referer = WWW_HOST.'/'.HOME_DIR;

	include_once(LIB_PATH.'sesiones.php');
	include_once(LIB_PATH.'Mobile_Detect_2.8.17.php');
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

	include_once(CLASS_PATH."admin.class.php");
	include_once(CLASS_PATH."permisos.class.php");
	include_once(CLASS_PATH."estructura.class.php");
	include_once(LIB_PATH."funciones.inc.php");

	if(DEBUG) $myAdmin->debug=1;

	$data1 = NULL;
	if(isset($_GET['data1']) && $_GET['data1'] != ''){ $data1 = $_GET['data1']; }else{ $data1 = 'index'; }
	$page = $data1;

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
}
?>