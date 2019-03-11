<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$id = $data3;

if($data2 == 'delete' && is_numeric($id)){
	$arrPaginas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id} OR iniPadre={$id} OR lvl1={$id} OR lvl2={$id} OR lvl3={$id} OR lvl4={$id} OR lvl5={$id} OR lvl6={$id} OR lvl7={$id} OR lvl8={$id} OR lvl9={$id} OR lvl10={$id}"));
	$paginaActual = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE kid_pagina={$id}"));
	$paginaActual = $paginaActual[0];
	$paginasUpdate = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE padre={$paginaActual['padre']} AND orden > {$paginaActual['orden']}"));
	/*BORRADO*/
	foreach($arrPaginas as $pagina){
		/*echo "<pre>";
		print_r($pagina);
		echo "</pre>";
		echo "CONTENIDOS ASOCIADOS::::<br>";*/
		$arrContenidos = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."contenido where fid_estructura = ".$pagina['kid_pagina'].""));
		foreach($arrContenidos as $contenido){
			/*echo "<pre>";
			print_r($contenido);
			echo "</pre>";*/
			if(!$myAdmin->conexion->delete(PREFIJO."contenido","kid_contenido=".$contenido['kid_contenido'])){
				header("Location:".CURRENT_SECCION."?msg=e6");
			}
		}
		/*echo "^--------------------::::<br>";*/
		/*delete($tabla,"WHERE".$condicion)*/
		if(!$myAdmin->conexion->delete(PREFIJO."estructura","kid_pagina=".$pagina['kid_pagina'])){
			header("Location:".CURRENT_SECCION."?msg=e5");
		}
	}
	/*REORDENAMIENTO*/
	foreach($paginasUpdate as $paginaAct){
			$datosAct['orden'] = $paginaAct['orden']-1;
			if(!$myAdmin->conexion->update(PREFIJO."estructura",$datosAct,"WHERE kid_pagina=".$paginaAct['kid_pagina'])){
				header("Location:".CURRENT_SECCION."?msg=e7");
			}
	}
	header("Location:".CURRENT_SECCION."?msg=k5");
}else{
	header("Location:".CURRENT_SECCION."?msg=e5");
}
?>