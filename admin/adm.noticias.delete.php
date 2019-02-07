<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$id = $_GET['id'];
	if($myAdmin->delete(PREFIJO."noticias","kid_noticia=$id")){
		header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=6");
	}else{
		die("Error: eliminar registro");
	}
}else{
	die("Error: id");
}
?>