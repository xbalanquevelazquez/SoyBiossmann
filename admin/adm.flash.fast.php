<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
#print_r($_GET);

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$id = $_GET['id'];
	if($_GET['fast']=='visible'){
		$res['visible'] = 1;
	}else if($_GET['fast']=='invisible'){
		$res['visible'] = '0';
	}
	#echo $res['visible'];
	if($myAdmin->conexion->update(PREFIJO."flashban",$res,"WHERE kid_banner=$id")){
		header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
	}else{
		die("Error: cambiar registro");
	}
}else{
	die("Error: id");
} ?>