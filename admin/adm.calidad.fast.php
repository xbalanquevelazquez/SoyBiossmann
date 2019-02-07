<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$id = $_GET['id'];
	if($_GET['fast']=='visible'){
		$res['activo'] = 1;
	}else if($_GET['fast']=='invisible'){
		$res['activo'] = '0';
	}
	if($myAdmin->conexion->update(PREFIJO."calidad_empresa",$res,"WHERE kid_empresa=$id")){
		header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
	}else{
		die("Error: eliminar registro");
	}
}else{
	die("Error: id");
} ?>