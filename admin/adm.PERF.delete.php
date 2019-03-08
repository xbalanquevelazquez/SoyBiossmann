<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($data3) && is_numeric($data3)) {
	$id = $data3;
	$pager = '';
	if(isset($data4) && $data4 == 'pag'){ $pager = '/pag/'.$data5; }
	
	if($res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT COUNT(*) AS total FROM ".PREFIJO."usuarios WHERE fid_perfil=$id"))){
		if($res[0]['total'] > 0){
			$total = $res[0]['total'];
			$s = $total>1?'s':'';
			die("<div class='bg-warning'>Error: Hay ".$res[0]['total']." usuario$s usando este perfil, no puede eliminarlo</div>");
		}else{
			if($myAdmin->conexion->delete(PREFIJO."perfil","kid_perfil=$id")){
				header("Location: ".ADMIN_URL.$data1.$pager);
			}else{
				die("Error: eliminar registro");
			}	
		}
	}else{
		die("Error: en query");
	}
}else{
	die("Error: id");
}
?>