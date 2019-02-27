<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
echo "borrar";
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
	$id = $_GET['id'];
	/*function delete($tabla,$condicion) SIN WHERE*/
	if($myAdmin->delete(PREFIJO."contenido","kid_contenido=".$id)){
		header("Location:?est&edit&id=".$_GET['fid']."&msg=k2");
	}else{
		header("Location:?est&edit&id=".$_GET['fid']."&msg=e2");
	}
}else{
	header("Location:?est&edit&id=".$_GET['fid']."&msg=e2");
}


?>