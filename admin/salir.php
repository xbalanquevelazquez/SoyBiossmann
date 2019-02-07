<?php
session_start();
define('VIEWABLE',true);
include_once("cnf/configuracion.cnf.php");
include_once(CLASS_PATH."admin.class.php");#se crea un $myadm
$myAdmin->salirSesion("index.php");
?>