<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$id_usr_actual = $myAdmin->obtenerUsr('kid_usr');
if($myAdmin->conexion->query("INSERT INTO ".PREFIJO."registros(nip,fid_usuario_creador,estatus_registro) VALUES(NULL,'$id_usr_actual',1)")){

	$last_id =$myAdmin->conexion->last_id();
	#$reg = new Registro();

	$admRegistro->cambiarEstatus($last_id,0,1);
	
	header("location:".APP_URL."CC/edit/".$last_id);
		
}else{
	echo "Error al generar NIP: ".$myAdmin->conexion->error;
} ?>