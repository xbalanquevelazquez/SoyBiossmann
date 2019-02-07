<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$id = $_GET['id'];
	if($myAdmin->delete("como_dice","kid_dice=$id")){
			header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=3");
	}else{
		die("Error: eliminar registro");
	}
}else{
	die("Error: id");
} ?>