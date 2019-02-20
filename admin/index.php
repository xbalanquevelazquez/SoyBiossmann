<?php
session_start();
define('VIEWABLE',TRUE);
include_once("cnf/configuracion.cnf.php");
if(DEBUG){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
$includeHeader = TRUE;
$paginaDespliegue = "home.php";
$tituloDePagina = 'Inicio';
$msg='';

$query = "SELECT *,(SELECT acronimo_accion FROM crs_acciones WHERE fid_accion=kid_accion) AS acronimo FROM crs_permisos WHERE fid_perfil = 2";

if(!$myAdmin->comprobarSesion()){// No hay session
	if(isset($_POST['cmp']) && is_array($_POST['cmp']) && !empty($_POST['cmp'])){//vienen datos de form
		$arrWords=array("'","%",'"','SELECT','select','GROUP','group','=','--');
		$_POST['cmp']['usrlogin']=str_replace($arrWords,'',$_POST['cmp']['usrlogin']);
		$_POST['cmp']['pswlogin']=str_replace($arrWords,'',$_POST['cmp']['pswlogin']);
				
		if($myAdmin->comprobarUsuario($_POST['cmp']['usrlogin'],$_POST['cmp']['pswlogin'])){
			
			if(!isset($data1) || $data1=='' || $data1='LOGIN') { $data1 = $myAdmin->obtenerUsr('firstSecc'); } 

			header("Location: ".APP_URL.$data1);
		}else{
			echo "<div class='bg-warning'>Usuario o contraseña incorrecta o usuario desactivado</div>";
			$data1='LOGIN';
		}
	}else{//NO HAY SESSION NI DATOS DE LOGIN
		$data1='LOGIN';
	}
} else {//HAY SESSION
	// print_r($_SESSION['site']['permisos']);
		if($data1=='' || $data1=='LOGIN') {
			$data1 = $myAdmin->obtenerUsr('firstSecc');
		} else {
			if(!in_array($data1,$myAdmin->obtenerUsr('permisos')) && $data1 != 'SALIR'){
				// $data1='404';
				// $tituloDePagina = 'Error';
			}
		}
	if(!file_exists('adm.'.$data1.'.php')){
		$data1 = '404';
		$tituloDePagina = 'Error';
	}
}


if($includeHeader){	include_once("header.php"); }

include('adm.'.$data1.'.php');

if($includeHeader){	include_once("footer.php"); }
?>