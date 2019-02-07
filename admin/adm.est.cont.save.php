<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
if(isset($_POST['id']) && $_POST['id'] != '' && is_numeric($_POST['id'])){
	$id = $_POST['id'];
	$datos = array();
	$datos['titulo'] = $_POST['titulo'];
	$_POST['fecha']!=NULL?$datos['ini_vigencia']=$_POST['fecha']:$datos['ini_vigencia']=NULL;
	$_POST['fechaFin']!=NULL?$datos['fin_vigencia']=$_POST['fechaFin']:$datos['fin_vigencia']=NULL;
	$datos['ubicacion'] = $_POST['ubicacion'];
	$datos['consecutivo'] = $_POST['consecutivo'];
	$datos['contenido'] = $_POST['contenido'];
	$datos['fecha_modificacion'] = date("Y-m-d H:i:s");
	$datos['nombre_responsable'] = $_SESSION['resolucion']['nombre'];
	/*function update($tabla,$datos,$condicion='',$type='HTML')*/
	if($myAdmin->conexion->update(PREFIJO."contenido",$datos,"WHERE kid_contenido=".$id)){
		header("Location:?est&cont&edit&fid=".$_POST['fid']."&id=".$_POST['id']."&msg=k1");
	}else{
		header("Location:?est&cont&edit&fid=".$_POST['fid']."&id=".$_POST['id']."&msg=e1");
	}
}else if(isset($_POST['id']) && $_POST['id'] == 'new'){
	$id = $_POST['id'];
	$datos = array();
	$datos['fid_estructura'] = $_POST['fid'];
	$datos['titulo'] = $_POST['titulo'];
	$_POST['fecha']!=NULL?$datos['ini_vigencia']=$_POST['fecha']:'';
	$_POST['fechaFin']!=NULL?$datos['fin_vigencia']=$_POST['fechaFin']:'';
	$datos['ubicacion'] = $_POST['ubicacion'];
	$datos['consecutivo'] = $_POST['consecutivo'];
	$datos['contenido'] = $_POST['contenido'];
	$datos['fecha_alta'] = date("Y-m-d H:i:s");
	$datos['nombre_responsable'] = $_SESSION['resolucion']['nombre'];
	print_r($datos);
	/*function insert($tabla,$datos,$type='HTML')*/
	if($myAdmin->conexion->insert(PREFIJO."contenido",$datos)){
		header("Location:?est&edit&id=".$_POST['fid']."&msg=k1");
	}else{
		header("Location:?est&edit&id=".$_POST['fid']."&msg=e1");
	}
}else{
	header("Location:?est&edit&id=".$_POST['fid']);
}
?>