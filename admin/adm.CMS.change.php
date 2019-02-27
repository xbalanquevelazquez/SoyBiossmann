<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
	$id = $data3;
	if($data2 == 'desactivar'){
		$datos = array();
		$datos['publicado']=0;
		$seleccionar = 'hijos';
	}else if($data2 == 'activar'){
		$datos = array();
		$datos['publicado']=1;
		$seleccionar = 'padres';
	}
	if($data2 == 'visible'){
		$datos = array();
		$datos['visible']=1;
		$seleccionar = 'padres';
	}else if($data2 == 'invisible'){
		$datos = array();
		$datos['visible']=0;
		$seleccionar = 'hijos';
	}
	if($data2 == 'up'){//hacia "arriba"
		$datos = array();
		$paginaActual = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura where kid_pagina={$id}"));
		$paginaActual = $paginaActual[0];
		$nuevoOrden = $paginaActual['orden']-1;
		$padre = $paginaActual['padre'];
		$paginaModif = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE padre={$padre} AND orden='{$nuevoOrden}'"));
		$paginaModif = $paginaModif[0];
		$nId = $paginaModif['kid_pagina'];
		$nuevoOrdenN = $paginaModif['orden']+1;
		/*echo "<pre>";
		print_r($paginaActual);
		print_r($paginaModif);
		echo "</pre>";
		echo "-";*/
		$seleccionar = 'mover';
	}else if($data2 == 'down'){//hacia "abajo"
		$datos = array();
		$paginaActual = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura where kid_pagina={$id}"));
		$paginaActual = $paginaActual[0];
		$nuevoOrden = $paginaActual['orden']+1;
		$padre = $paginaActual['padre'];
		$paginaModif = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE padre={$padre} AND orden='{$nuevoOrden}'"));
		$paginaModif = $paginaModif[0];
		$nId = $paginaModif['kid_pagina'];
		$nuevoOrdenN = $paginaModif['orden']-1;
		/*echo "<pre>";
		print_r($paginaActual);
		print_r($paginaModif);
		echo "</pre>";
		echo "+";*/
		#$datos['visible']=0;
		$seleccionar = 'mover';
	}
	
	/*function update($tabla,$datos,$condicion='',$type='HTML')*/
	if($seleccionar == 'hijos'){
		$arrPaginas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id} OR iniPadre={$id} OR lvl1={$id} OR lvl2={$id} OR lvl3={$id} OR lvl4={$id} OR lvl5={$id} OR lvl6={$id} OR lvl7={$id} OR lvl8={$id} OR lvl9={$id} OR lvl10={$id}"));
		foreach($arrPaginas as $pagina){
			if(!$myAdmin->conexion->update(PREFIJO."estructura",$datos,"WHERE kid_pagina=".$pagina['kid_pagina'])){
				header("Location:".CURRENT_SECCION);
			}
		}
		if(isset($_GET['fast'])){
			header("Location:".CURRENT_SECCION."edit/$id");
		}else{
			header("Location:".CURRENT_SECCION);
		}
	}else if($seleccionar == 'padres'){
		$arrPaginas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id}"));
		$arrPaginas = $arrPaginas[0];
		$arrPadres = array();
		array_push($arrPadres,$id);
		array_push($arrPadres,$arrPaginas['iniPadre']);
		array_push($arrPadres,$arrPaginas['lvl1']);
		array_push($arrPadres,$arrPaginas['lvl2']);
		array_push($arrPadres,$arrPaginas['lvl3']);
		array_push($arrPadres,$arrPaginas['lvl4']);
		array_push($arrPadres,$arrPaginas['lvl5']);
		array_push($arrPadres,$arrPaginas['lvl6']);
		array_push($arrPadres,$arrPaginas['lvl7']);
		array_push($arrPadres,$arrPaginas['lvl8']);
		array_push($arrPadres,$arrPaginas['lvl9']);
		array_push($arrPadres,$arrPaginas['lvl10']);
		foreach($arrPadres as $page){
			if($page != NULL){
				if(!$myAdmin->conexion->update(PREFIJO."estructura",$datos,"WHERE kid_pagina=".$page)){
					die($myAdmin->conexion->error);
				}
			}			
		}
		if(isset($_GET['fast'])){
			header("Location:".CURRENT_SECCION."edit/$id");
		}else{
			header("Location:".CURRENT_SECCION);
		}
	}else if($seleccionar == 'mover'){
		#$id -> $nuevoOrden
		#$nId -> $nuevoOrdenN
		$datos['orden'] = $nuevoOrden;
		if($myAdmin->conexion->update(PREFIJO."estructura",$datos,"WHERE kid_pagina=".$id)){
			$datos['orden'] = $nuevoOrdenN;
			if($myAdmin->conexion->update(PREFIJO."estructura",$datos,"WHERE kid_pagina=".$nId)){
				header("Location:".CURRENT_SECCION);
			}else{
				die($myAdmin->conexion->error);
			}
		}else{
			die($myAdmin->conexion->error);
		}
	}
?>