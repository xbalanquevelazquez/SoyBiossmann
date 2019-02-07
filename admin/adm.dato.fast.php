<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$id = $_GET['id'];
	if($_GET['fast']=='visible'){
		$res['visible'] = 1;
	}else if($_GET['fast']=='invisible'){
		$res['visible'] = '0';
	}
	if($myAdmin->conexion->update("dato_reforma",$res,"WHERE kid_dato=$id")){
		header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=3");
	}else{
		die("Error: eliminar registro");
	}
}else{
	die("Error: id");
} ?>