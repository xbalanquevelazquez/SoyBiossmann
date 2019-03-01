<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
define('DIRECTORIO_BD_HOST', 		'localhost');
define('DIRECTORIO_DB_USER', 		'dashboard');
define('DIRECTORIO_DB_PSW', 		'b10ssd4shb04rd.-');
define('DIRECTORIO_DB_NAME', 		'dashboard');

$conn = new Conexion();
$conn->conectar( DIRECTORIO_BD_HOST , DIRECTORIO_DB_USER , DIRECTORIO_DB_PSW , DIRECTORIO_DB_NAME );

include_once(LIB_PATH.'Mobile_Detect_2.8.17.php');
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');