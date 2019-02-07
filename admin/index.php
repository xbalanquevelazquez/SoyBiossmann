<?php
define('VIEWABLE',true);

include_once("cnf/configuracion.cnf.php");

$estructura = new Estructura();

$page=key($_GET);
next($_GET);
$page2=key($_GET);
next($_GET);
$page3=key($_GET);

if($page=='') $page='login';

if(!$myAdmin->comprobarSesion()){// No hay session
	
	if(isset($_POST['cmp']) && is_array($_POST['cmp']) && !empty($_POST['cmp'])){//vienen datos de form
		$arrWords=array("'","%",'"','SELECT','select','GROUP','group','=','--');
		$_POST['cmp']['usrlogin']=str_replace($arrWords,'',$_POST['cmp']['usrlogin']);
		$_POST['cmp']['pswlogin']=str_replace($arrWords,'',$_POST['cmp']['pswlogin']);
				
		if($myAdmin->comprobarUsuario($_POST['cmp']['usrlogin'],$_POST['cmp']['pswlogin'])){
			
			if(!isset($page) || $page=='' || $page='login') { echo $page = $myAdmin->obtenerUsr('seccIni'); } 
			header("Location:".$_SERVER['PHP_SELF']."?".$page);
		}else{
			echo "<div class='aviso'>Usuario o contraseña incorrecta</div>";
		}
	}else{//NO HAY SESSION NI DATOS DE LOGIN
		$page='login';
	}

}else{//HAY SESSION
		if($page=='') {
			$page = $myAdmin->obtenerUsr('seccIni');
		}else{
			if(!in_array($page,$myAdmin->obtenerUsr('permisosPag'))){
				//echo "sin permisos<br>";
				$page='404';
			}else{
				//$page = $myAdmin->obtenerUsr('seccIni');
			}
		}
	$page=file_exists('adm.'.$page.'.php')?$page:'404';
}

include('adm.'.$page.'.php');
?>