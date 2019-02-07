<?php
error_reporting(E_ALL);
#session_start();
//Activar validación de archivos cargados
if(!defined('VIEWABLE')) define('VIEWABLE',true);
include_once("cnf/configuracion.cnf.php");

include_once(CLASS_PATH."mysql.class.php");
$myAdmin = new Conexion();
include_once(CLASS_PATH."paginacion.class.php");
include_once(LIB_PATH."functions.inc.php");

/*DEBUG*/
$myAdmin->debug(1);
$page='show';
$page=file_exists(APPX_PATH.'frog/plugins/lista_links/adm.'.$page.'.php')?$page:'404';
include(APPX_PATH.'frog/plugins/lista_links/adm.'.$page.'.php');
$myAdmin->close(); ?>