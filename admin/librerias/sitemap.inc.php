<?php
$site = array();
$site['login']['name'] = "Ingreso";
$site['404']['name'] = "Error 404";
$seccsBD = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."seccion ORDER BY kid_seccion ASC"));
foreach($seccsBD as $secc){
	$site[$secc['acronimo']]['name'] = $secc['nombre'];
	$site[$secc['acronimo']]['new']['name'] = "Nuevo";
	$site[$secc['acronimo']]['edit']['name'] = "Editar";
}
$site['blog']['comm']['name'] = "Comentarios";
$site['blog']['comm']['new']['name'] = "Nuevo comentario";
?>