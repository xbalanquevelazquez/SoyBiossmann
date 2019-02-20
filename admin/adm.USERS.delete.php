<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($data3) && is_numeric($data3)) {
	$id = $data3;

	$pager = '';
	if(isset($data4) && $data4 == 'pag'){ $pager = '/pag/'.$data5; }

	if($myAdmin->conexion->delete(PREFIJO."usuarios","kid_usr=$id")){
		header("Location: ".APP_URL.$data1.$pager);
	}else{
		die("Error: eliminar registro");
	}
}else{
	die("Error: id");
}
?>