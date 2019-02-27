<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
if($page2 == 'delete' && isset($_GET['id']) && $_GET['id']!='' && is_numeric($_GET['id'])){

	$id = $_GET['id'];
	
	$arrPaginas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id} OR iniPadre={$id} OR lvl1={$id} OR lvl2={$id} OR lvl3={$id} OR lvl4={$id} OR lvl5={$id} OR lvl6={$id} OR lvl7={$id} OR lvl8={$id} OR lvl9={$id} OR lvl10={$id}"));
	#echo $id;
	$paginaActual = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE kid_pagina={$id}"));
	$paginaActual = $paginaActual[0];
	#print_r($paginaActual);
	$paginasUpdate = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE padre={$paginaActual['padre']} AND orden > {$paginaActual['orden']}"));
	/*$paginasUpdate = $paginaActual[0];*/
	/*echo "---<pre>";
	print_r($paginasUpdate);
	echo "</pre>";
	die();*/
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
			if(!$myAdmin->delete(PREFIJO."contenido","kid_contenido=".$contenido['kid_contenido'])){
				header("Location:?est&&msg=e6");
			}
		}
		/*echo "^--------------------::::<br>";*/
		/*delete($tabla,"WHERE".$condicion)*/
		if(!$myAdmin->delete(PREFIJO."estructura","kid_pagina=".$pagina['kid_pagina'])){
			header("Location:?est&&msg=e5");
		}
	}
	/*REORDENAMIENTO*/
	foreach($paginasUpdate as $paginaAct){
			$datosAct['orden'] = $paginaAct['orden']-1;
			if(!$myAdmin->conexion->update(PREFIJO."estructura",$datosAct,"WHERE kid_pagina=".$paginaAct['kid_pagina'])){
				header("Location:?est&&msg=e7");
			}
	}
	header("Location:?est&msg=k5");
}else{
	header("Location:?est&msg=e5");
}
?>