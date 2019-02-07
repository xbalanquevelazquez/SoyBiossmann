<?php 
function inicia_sesion($data){
	if(is_array($data)){
		regenera_sesion();
		$_SESSION[SESSION_DATA_SET]['activo'] = TRUE;
		$_SESSION[SESSION_DATA_SET]['nombre'] = $data['firstname'];
		$_SESSION[SESSION_DATA_SET]['apellidos'] = $data['lastname'];
		$_SESSION[SESSION_DATA_SET]['email'] = $data['email'];
		$_SESSION[SESSION_DATA_SET]['foto'] = $data['pictureurl'];
		$_SESSION[SESSION_DATA_SET]['kid_usr'] = $data['id'];
		$_SESSION[SESSION_DATA_SET]['puesto'] = $data['Puesto'];
		$_SESSION[SESSION_DATA_SET]['lugar'] = $data['lugar'];
		return TRUE;
	}else{
		return FALSE;
	}
	setcookie('PHPSESSID', session_id(), 0, '/');
}

function regenera_sesion(){
	//$id_sesion_antigua = session_id();

	session_regenerate_id();

	//$id_sesion_nueva = session_id();

	//echo "Sesión Antigua: $id_sesion_antigua<br />";
	//echo "Sesión Nueva: $id_sesion_nueva<br />";

	//print_r($_SESSION);

}

function cierra_sesion(){
	$_SESSION[SESSION_DATA_SET]['activo'] = FALSE;
	$_SESSION[SESSION_DATA_SET]['nombre'] = '';
	$_SESSION[SESSION_DATA_SET]['apellidos'] = '';
	$_SESSION[SESSION_DATA_SET]['foto'] = '';
	$_SESSION[SESSION_DATA_SET]['kid_usr'] = '';
	$_SESSION[SESSION_DATA_SET]['puesto'] = '';
	$_SESSION[SESSION_DATA_SET]['lugar'] = '';
	unset($_SESSION[SESSION_DATA_SET]['nombre']);

	session_unset();
	session_destroy();

	return TRUE;
}

function logedin(){
	return comprueba_sesion();
}
function comprueba_sesion(){
	return (isset($_SESSION[SESSION_DATA_SET]['activo']) && $_SESSION[SESSION_DATA_SET]['activo'])?TRUE:FALSE;
}
?>