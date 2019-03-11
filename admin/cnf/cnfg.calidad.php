<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
$thisserver = $_SERVER['SERVER_NAME'];
if($thisserver == 'localhost' || $thisserver == '192.168.3.7'){
	define('CALIDAD_BD_HOST', 		'localhost');
	define('CALIDAD_DB_NAME', 		'intranetbiossmann_old');//medicas2_nuke1
}else if($thisserver=='soy.biossmann.com'){
	define('CALIDAD_BD_HOST', 		'192.168.150.14');
	define('CALIDAD_DB_NAME', 		'medicas2_nuke1');//medicas2_nuke1
}
define('CALIDAD_DB_USER', 		'medicas2_nuke1');
define('CALIDAD_DB_PSW', 		'm3cIcBBgQeRZ');

$connCalidad = new Conexion();
$connCalidad->conectar( CALIDAD_BD_HOST , CALIDAD_DB_USER , CALIDAD_DB_PSW , CALIDAD_DB_NAME );