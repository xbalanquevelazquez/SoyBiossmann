<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$id = $data4;
$action = $data3;
if(isset($id) && is_numeric($id) && $id > 0) {
	
	$pager = '';
	if(isset($data5) && $data5 == 'pag'){ $pager = '/pag/'.$data6; }

	if($action=='visible'){
		$res['usr_activo'] = 1;
	}else if($action=='invisible'){
		$res['usr_activo'] = '0';
	}
	if($myAdmin->conexion->update(PREFIJO."usuarios",$res,"WHERE kid_usr=$id")){
			header("Location: ".APP_URL.$data1.$pager);
	}else{
		die("Error: cambiar registro");
	}
}else{
	die("Error: id");
} ?>