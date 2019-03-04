<?php 
if(!defined('VIEWABLE')) {
	die('Inaccesible');
}
define('CALIDAD_BD_HOST', 		'localhost');
define('CALIDAD_DB_USER', 		'medicas2_nuke1');
define('CALIDAD_DB_PSW', 		'm3cIcBBgQeRZ');
define('CALIDAD_DB_NAME', 		'intranetbiossmann_old');//medicas2_nuke1

$connCalidad = new Conexion();
$connCalidad->conectar( CALIDAD_BD_HOST , CALIDAD_DB_USER , CALIDAD_DB_PSW , CALIDAD_DB_NAME );