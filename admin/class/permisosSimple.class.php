<?php
define('VIEWABLE',true);
function generarPermisos(){
	$permisos = array();
	include("../../cnfg.php");
	$secciones = mysql_query("SELECT * FROM ".PREFIJO."seccion",$conexion);
	$arrSecciones = array();
	while($secc = mysql_fetch_assoc($secciones)){
		$arrSecciones[] = $secc;
	}
	mysql_free_result($secciones);
	mysql_close($conexion);
	for($i = 0;$i < count($arrSecciones); $i++){
		$permisos[] = pow(2,$arrSecciones[$i]['kid_seccion']);
	}
	return $permisos;
}
function obtenerPermisos($perfil){
	include("../../cnfg.php");
	$perfilres = mysql_query("SELECT * FROM ".PREFIJO."perfil WHERE kid_perfil={$perfil} LIMIT 1",$conexion);
	$perfil = mysql_fetch_assoc($perfilres);
	mysql_free_result($perfilres);
	mysql_close($conexion);
	$permisos = generarPermisos();
	$tempBIT=$perfil['bit_acceso'];
	$cuantos = count($permisos);
	$arrPermisos = array();
	for($i=$cuantos;$i > 0;$i--){
		if($tempBIT >= $permisos[$i]){
			$arrPermisos[] = $permisos[$i];
			$tempBIT = $tempBIT-$permisos[$i];
			if($tempBIT == 2) $arrPermisos = $permisos;/*Se asignan todos los permisos, es administrador*/
		}
	}
	return $arrPermisos;
}
function validarPermiso($permiso,$userArrPermisos){
	return in_array($permiso,$userArrPermisos)?true:false;
}
/*
$permisosUsuario = obtenerPermisos(2);/*indicar perfil del usuario* /
echo validarPermiso(16,$permisosUsuario)?'si':'no';
*/
?>