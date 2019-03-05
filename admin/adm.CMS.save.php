<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$type = $data3;
$id  = $data4;

switch($type){
	case 'new'://pag nueva
		/**
			kid_pagina
			nivel
			padre
			nombre
			alias
			publicado
			orden
			plantilla
			visible
			-----------------------
			Array
		(
		    [nivel] => 2
		    [padre] => 3
		    [orden] => 3
		    [type] => sup
		    [titulo] => después de misión
		    [alias] => atribuciones
		    [visible] => 1
		    [plantilla] => 2
		)
			*/
			/*echo "<pre>";
			print_r($_POST);
			echo "</pre>";*/
		#print_r($_POST);

			$datos = array();
			$datos['nivel'] = $_POST['nivel'];
			$datos['padre'] = $_POST['padre'];
			$datos['nombre'] = $_POST['titulo'];
			$datos['alias'] = convertirDatoSeguro($_POST['alias']);
			$datos['publicado'] = isset($_POST['publicado'])?$_POST['publicado']:0;
			$datos['descripcion'] = isset($_POST['descripcion'])?$_POST['descripcion']:'';
			$datos['keywords'] = isset($_POST['keywords'])?$_POST['keywords']:'';
			#$datos['grupodestacados'] = $_POST['grupodestacados'];


			if (isset($_POST['newsite'])) {
				$nextId = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT COUNT(*) as siguiente FROM ".PREFIJO."estructura")	);
				$datos['kid_pagina']=($nextId[0]['siguiente'])+1;
			}


			if(isset($_POST['newpage'])){
				$totalChilds = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT MAX(orden) as max FROM ".PREFIJO."estructura WHERE padre=".$datos['padre'])	);
				$datos['orden'] = $totalChilds[0]['max']+1;

			}else{
				$datos['orden'] = $_POST['orden'];
			}
			$datos['plantilla'] = $_POST['plantilla'];
			$datos['visible'] = isset($_POST['visible'])?$_POST['visible']:0;
			/*AUMENTAR*/
			
			if(isset($_POST['type'])){
				if($_POST['type']=='inf' || $_POST['type']=='sup'){
					$registrosCambio = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE padre={$datos['padre']} AND orden>={$datos['orden']}"));
					/*echo "<pre>";
					print_r($registrosCambio);
					echo "</pre>";*/
					foreach($registrosCambio as $reg){
						$cambio = array();
						$cambio['orden'] = $reg['orden']+1;
						$myAdmin->conexion->update(PREFIJO."estructura",$cambio,"WHERE kid_pagina=".$reg['kid_pagina'],'TEXT');
					}
				}else if($_POST['type']=='child'){
				
				} 
			}
			#echo "<hr>";
			#print_r($datos);
			
			/*insert($tabla,$datos,$type='HTML')*/
			
			if($myAdmin->conexion->insert(PREFIJO."estructura",$datos,'TEXT')){
			   /**
			  `kid_contenido` int(11) NOT NULL auto_increment,
			  `fid_estructura` int(11) NOT NULL,
			  `contenido` longtext collate latin1_spanish_ci,
			  `fecha_alta` datetime NOT NULL,
			  `fecha_modificacion` datetime default NULL,
			  `nombre_responsable` varchar(70) collate latin1_spanish_ci NOT NULL default 'Administrador del sitio web',
				*/

			  #die();
				$lastEstrucutraId = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT LAST_INSERT_ID() as last"));
				$contenido = array();
				$contenido['fid_estructura'] = $lastEstrucutraId[0]['last'];
				$contenido['contenido'] = $_POST['contenido'];
				$contenido['fid_estructura'] = $lastEstrucutraId[0]['last'];
				$contenido['fecha_alta'] = date("Y-m-d H:i:s");
				$contenido['nombre_responsable'] = $myAdmin->obtenerUsr('nombre');
			
				if($myAdmin->conexion->insert(PREFIJO."contenido",$contenido,'TEXT')){
					if(isset($_POST['newsite'])){/*es sitio- regresar a la estructura*/
						header("Location:".CURRENT_SECCION);
					}else{/*es página normal, regresar al editor*/
						#$last = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT LAST_INSERT_ID() as last"));
						$last = $lastEstrucutraId[0]['last'];
						header("Location:".CURRENT_SECCION."edit/".$last."?msg=k1");
					}
				}
			}else{
				die($myAdmin->conexion->error);
				#echo $myAdmin->conexion->error;
				#header("Location:".CURRENT_SECCION."new/".$_POST['type']."/".$_POST['oldId']."&msg=e1&ertext=". );
			}
		break;
	default://es update
		if(isset($_POST['id']) && $_POST['id'] != '' && is_numeric($_POST['id'])){
			$id = $_POST['id'];
			$datos = array();
			$datos['nombre'] = $_POST['titulo'];
			$datos['alias'] = convertirDatoSeguro($_POST['alias']);
			$datos['plantilla'] = $_POST['plantilla'];
			$datos['descripcion'] = $_POST['descripcion'];
			$datos['keywords'] = $_POST['keywords'];
			$datos['publicado'] = isset($_POST['publicado'])?$_POST['publicado']:0;
			$datos['visible'] = isset($_POST['visible'])?$_POST['visible']:0;
			#print_r($_POST);

			#$datos['grupodestacados'] = $_POST['grupodestacados'];
			/*function update($tabla,$datos,$condicion='',$type='HTML')*/
		   /**
		  `kid_contenido` int(11) NOT NULL auto_increment,
		  `fid_estructura` int(11) NOT NULL,
		  `contenido` longtext collate latin1_spanish_ci,
		  `fecha_alta` datetime NOT NULL,
		  `fecha_modificacion` datetime default NULL,
		  `nombre_responsable` varchar(70) collate latin1_spanish_ci NOT NULL default 'Administrador del sitio web',
			*/
			#$lastEstrucutraId = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT LAST_INSERT_ID() as last"));
			$contenido=array();
			$contenido['fid_estructura'] = $id;
			$contenido['contenido'] = $_POST['contenido'];
			#$contenido['fid_estructura'] = $lastEstrucutraId[0]['last'];
			$contenido['fecha_modificacion'] = date("Y-m-d H:i:s");
			$contenido['nombre_responsable'] = $myAdmin->obtenerUsr('nombre');
		
			#print_r($contenido);
			#die();
			if($myAdmin->conexion->update(PREFIJO."contenido",$contenido,"WHERE fid_estructura=".$id)){
				if($myAdmin->conexion->update(PREFIJO."estructura",$datos,"WHERE kid_pagina=".$id,'TEXT')){
					header("Location:".CURRENT_SECCION."edit/".$id."?msg=k1");
				}else{
					header("Location:".CURRENT_SECCION."edit/".$id."?msg=e1");
				}
			}
		}else{
			header("Location:".CURRENT_SECCION."edit/".$_POST['fid']);
		}
		break;
}
?>