<?php
#define('VIEWABLE',true);
include_once("mysql.class.php");
include_once("paginacion.class.php");

#include_once("site.class.php");
class Admin{
	var $debug = 0;
	var $conexion = '';
	var $resultado = '';
	var $error = '';
	var $confpath = '../cnf/';
	var $libfpath = '../librerias/';
	var $paginador = '';
	function __construct(){
		$this->confpath = CONF_PATH;
		$this->libfpath = LIB_PATH;
		$this->conectarBD();
	}
	
	function conectarBD(){
			include_once($this->confpath."configuracion.cnf.php");
			$conexion = new Conexion();
			$conexion->debug = $this->debug;
			$conexion->conectar(BD_HOST,DB_USER,DB_PSW,DB_NAME);
			$this->conexion = $conexion;

			$paginador = new Paginacion();
			$paginador->init($this->conexion);
			$this->paginador = $paginador;
	}
	
	function comprobar_conexion(){
		if($this->conexion == ''){
			$this->conectarBD();
		}
	}
	function comprobarUsuario($usrlogin,$pswlogin){
		$this->comprobar_conexion();
		$query = "SELECT COUNT(*) as total FROM ".PREFIJO."usuarios WHERE usr_login='$usrlogin' AND AES_DECRYPT(usr_psw,'".AESCRYPT."') = '$pswlogin' AND usr_activo=1";
		$res = $this->conexion->query($query);
		$comp = $this->conexion->fetch($res);
		if($comp[0]['total'] == 1){
			$query = "SELECT kid_usr,usr_login,usr_nombre,usr_correo,fid_perfil FROM ".PREFIJO."usuarios WHERE usr_login='$usrlogin' AND AES_DECRYPT(usr_psw,'".AESCRYPT."') = '$pswlogin' AND usr_activo=1 LIMIT 1";

			$qryId = $this->conexion->query($query);
			$res = $this->conexion->fetch($qryId);
			$datosUsuario = $res[0];

			$seccionesAcceso = $this->obtenerPermisos($datosUsuario['fid_perfil']);

			$querySeccIni = "SELECT seccion_inicial FROM ".PREFIJO."perfil WHERE kid_perfil=".$datosUsuario['fid_perfil'];
			$qryId = $this->conexion->query($querySeccIni);
			$res = $this->conexion->fetch($qryId);
			$firstSecc = $res[0]['seccion_inicial'];

			$this->iniciarSesion($datosUsuario['usr_login'],$datosUsuario['usr_nombre'],$datosUsuario['fid_perfil'],$seccionesAcceso,$firstSecc,$datosUsuario['kid_usr']);
			return true;
		}else{
			return false;
		}
	}
	function obtenerPermisos($fid_perfil){
		if($fid_perfil == 1){//ES ADMIN obtener todos los permisos posibles
			$query = "SELECT acronimo_accion AS acronimo FROM ".PREFIJO."acciones";//NO MOSTRAR Admin, no Consulta de secciones generales
		}else{
			$query = "SELECT *,(SELECT acronimo_accion FROM ".PREFIJO."acciones WHERE fid_accion=kid_accion) AS acronimo FROM ".PREFIJO."permisos WHERE fid_perfil='$fid_perfil'";
		}
		// echo '<div>' . $query  . ';</div>';
		$res = $this->conexion->query($query);
		$permisos = $this->conexion->fetch($res);
		$arrPermisos = array();
		foreach ($permisos as $key => $value) {
			$arrPermisos[] = $value['acronimo'];
			echo ' | ' . $value['acronimo'];
		}
		return $arrPermisos;
	}
	function obtenerUsuarios($limit='',$returnQuery=0){
		$this->comprobar_conexion();
		$limitquery = '';
		
		if($returnQuery == 0) $tablesQuery = "*,(SELECT nombre_perfil FROM ".PREFIJO."perfil WHERE fid_perfil = kid_perfil) as perfil"; else  $tablesQuery = "COUNT(*) as total";
		#$tablesQuery = "*,(SELECT bit_acceso FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as bit,(SELECT ".PREFIJO."perfil.nombre FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as perfil"; else  $tablesQuery = "COUNT(*) as total";
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }

		$query = "SELECT $tablesQuery FROM ".PREFIJO."usuarios as usr ORDER BY kid_usr $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array		
	}

	function obtenerUsuario($id=0){
		$this->comprobar_conexion();

		$query = "SELECT * FROM ".PREFIJO."usuarios as usr WHERE kid_usr='$id'";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		return $this->resultado;	
	}

	function iniciarSesion($usr,$nombre,$perfil,$seccionesAcceso,$firstSecc,$kid_usr){
		if($perfil == 1){
			$admin = TRUE;
		}else{
			$admin = FALSE;
		}
		$query = "SELECT acronimo_accion AS acronimo,nombre_accion AS nombre FROM ".PREFIJO."acciones ORDER BY kid_accion ASC";
		$qryId = $this->conexion->query($query);
		$res = $this->conexion->fetch($qryId);
		$secciones = $res;

		$arrDatos = array(
				'usr' => $usr, 
				'nombre' => $nombre, 
				'perfil' => $perfil, 
				'permisos' => $seccionesAcceso,#$arrSecc['bits'],
				'firstSecc' => $firstSecc,
				'secciones' => $secciones,
				'admin' => $admin,
				'kid_usr' => $kid_usr
			);
		$_SESSION[SESSION_DATA_SET] = $arrDatos;
	}
	function comprobarSesion(){
		#print_r($_SESSION[SESSION_DATA_SET]);
		if(isset($_SESSION[SESSION_DATA_SET]) && is_array($_SESSION[SESSION_DATA_SET])) return true;else return false;
	}
	function salirSesion($pag){
		unset($_SESSION[SESSION_DATA_SET]);
		header("Location:".$pag);
	}
	function obtenerUsr($dato){
		return $_SESSION[SESSION_DATA_SET][$dato];
	}
	function permisosUsuario(){
		return $_SESSION[SESSION_DATA_SET]['permisos'];
	}
	function esAdmin(){
		return $_SESSION[SESSION_DATA_SET]['admin'];
	}
}
$myAdmin = new Admin();
?>