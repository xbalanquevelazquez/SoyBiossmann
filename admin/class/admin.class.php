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
	function test(){
		echo '--- test ---';
	}
	function comprobarUsuario($usrlogin,$pswlogin){
		//echo $usrlogin." - ".$pswlogin;
		$this->comprobar_conexion();
		$query = "SELECT COUNT(*) as total FROM ".PREFIJO."usuarios WHERE usr_login='$usrlogin' AND AES_DECRYPT(usr_psw,'".AES_ENCRYPT."') = '$pswlogin' AND usr_activo=1";
		#echo $query;
		#die();
		$res = $this->conexion->query($query);
		$comp = $this->conexion->fetch($res);
		#print_r($comp);
		if($comp[0]['total'] == 1){
			$query = "SELECT usr_login,usr_nombre,fid_perfil FROM ".PREFIJO."usuarios WHERE usr_login='$usrlogin' AND AES_DECRYPT(usr_psw,'".AES_ENCRYPT."') = '$pswlogin'";

			$res = $this->conexion->query($query);
			$data = $this->conexion->fetch($res);
			$data = $data[0];

			echo $queryPermisos = "SELECT *,(SELECT acronimo_accion FROM ".PREFIJO."acciones WHERE fid_accion=kid_accion) AS acronimo FROM ".PREFIJO."permisos WHERE fid_perfil = ".$data['fid_perfil'];
			echo "<br>";
			$resPermisos = $this->conexion->query($queryPermisos);
			$permisos = $this->conexion->fetch($resPermisos);

			#print_r(obtenerPermisos($comp[0]['bit']));
			$seccionesAcceso = obtenerPermisos($data[0]['bit']);
			$arrSecc['bits'] = $seccionesAcceso;
			$firstSecc = end($seccionesAcceso);
			$query = "SELECT acronimo FROM ".PREFIJO."seccion WHERE kid_seccion = $firstSecc";
			$res = $this->conexion->query($query);
			$seccIni = $this->conexion->fetch($res);
			$seccionesAccesoImp = implode(",",$seccionesAcceso);
			$query2 = "SELECT acronimo FROM ".PREFIJO."seccion WHERE kid_seccion in($seccionesAccesoImp)";
			$res2 = $this->conexion->query($query2);
			$acronimos = $this->conexion->fetch($res2);
			foreach($acronimos as $acron){
				$arrSecc['acronimos'][] = $acron['acronimo'];
			}
			#echo $seccIni[0]['acronimo'];
			#define('INIT_PAGE',$seccIni[0]['acronimo']);
			#die();
			$this->iniciarSesion($data['usr_login'],$data['usr_nombre'],$data['fid_perfil'],$data['bit'],$seccIni[0]['acronimo'],$arrSecc);
			#echo $seccIni[0]['acronimo'];
			#die();
			#print_r($_SESSION);
			#echo $comp[0]['usr_login']." | ".$comp[0]['usr_nombre']." | ".$comp[0]['usr_perfil']." | ".$comp[0]['bit']." | ".$seccIni[0]['acronimo']." | ".$arrSecc;
			#die();
			return true;
		}else{
			return false;
		}
	}
	function obtenerUsuarios($limit='',$returnQuery=0){
		$this->comprobar_conexion();
		$limitquery = '';
		
		if($returnQuery == 0) $tablesQuery = "*,(SELECT bit_acceso FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as bit,(SELECT ".PREFIJO."perfil.nombre FROM ".PREFIJO."perfil WHERE usr_perfil = kid_perfil) as perfil"; else  $tablesQuery = "COUNT(*) as total";
		if($limit != '') { $limitquery = ' LIMIT '.$limit; }

		$query = "SELECT $tablesQuery FROM ".PREFIJO."usuarios as usr ORDER BY usr_id $limitquery";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		if($returnQuery == 0) return $this->resultado;else return $query;#RETURN Array		
	}

	function obtenerUsuario($id=0){
		$this->comprobar_conexion();

		$query = "SELECT * FROM ".PREFIJO."usuarios as usr WHERE usr_id='$id'";
		#echo $query.'<br />';
		$this->resultado = $this->conexion->query($query);
		$this->resultado = $this->conexion->fetch($this->resultado);
		
		return $this->resultado;	
	}

	function iniciarSesion($usr,$nombre,$perfil,$bit,$seccIni,$arrSecc){
		echo $usr;
		if($bit == 0){
			$admin = TRUE;
		}else{
			$admin = FALSE;
		}
		$arrDatos = array(
							'usr' => $usr, 
							'nombre' => $nombre, 
							'perfil' => $perfil, 
							'bit' => $bit, 
							'seccIni' => $seccIni,
							'permisos' => $arrSecc['bits'],
							'permisosPag' => $arrSecc['acronimos'],
							'admin' => $admin
						);
		#$_SESSION['site']['usr'] = $usr;
		#$_SESSION['site']['nombre'] = $nombre;
		#$_SESSION['site']['perfil'] = $perfil;
		#$_SESSION['site']['bit'] = $bit;
		#$_SESSION['site']['seccIni'] = $seccIni;
		#$permisosUsuario = obtenerPermisos($perfil);
		//$this->confpath;
		#$_SESSION['site']['permisos'] = $arrSecc['bits'];
		#$_SESSION['site']['permisosPag'] = $arrSecc['acronimos'];

		$_SESSION['site'] = $arrDatos;
	}
	function comprobarSesion(){
		#print_r($_SESSION['site']);
		if(isset($_SESSION['site']) && is_array($_SESSION['site'])) return true;else return false;
	}
	function salirSesion($pag){
		unset($_SESSION['site']);
		header("Location:".$pag);
	}
	function obtenerUsr($dato){
		return $_SESSION['site'][$dato];
	}
	function permisosUsuario(){
		return $_SESSION['site']['permisos'];
	}
	function esAdmin(){
		return $_SESSION['site']['admin'];
	}
}
$myAdmin = new Admin();
?>