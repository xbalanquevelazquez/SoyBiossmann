<?PHP
header("Content-Type: application/json", true);
define('VIEWABLE',TRUE);
@session_start();
include_once("../cnf/cnfg.crs.php");
include_once(CLASS_PATH . "dbi.class.php");
include_once(CLASS_PATH . "notas.class.php");
$db = new BD();

$success = FALSE;
$error   = 'No especificado';
$data    = array();
$action  = '';
$revisarExpediente = false;
$actualizarRegistro = false;
$tipoActualizacion = '';
$idEstudio = $_POST['idEstudio'];
$datosExpediente = $db->ExecuteUno('crs_cuestionario_medico', "kid_cuestionario = $idEstudio");

if (isset($_POST['action']) && $_POST['action'] != '') {
	switch ($_POST['action']) {
		case 'terminar':
			$data    = array('action'=>$_POST['action']);
			$tabla = 'crs_cuestionario_medico';
			$dataPost = prepareSqlFrom($_POST);
			$dataPost['fecha_termina'] = "'" . dateMeFull() . "'";
			// $dataPost['fid_usr'] = $_SESSION['site']['kid_usr'];
			unset($dataPost['action']);
			unset($dataPost['idEstudio']);
			if ($existe = $db->ExecuteUno($tabla, "kid_cuestionario = $idEstudio")){
				$sql = prepareSQL($dataPost, 'update', $tabla, "kid_cuestionario = $idEstudio");
				$msnOk = '';
				$data['sql'] = $sql;
				// edis($sql);
				$do = $db->Insert($sql);
				if ($do){
					$revisarExpediente = false;
					$buffer  = "<div class='bg-success'>Datos guardados</div>";
					$success = TRUE;
					$error   = '';
					if ($_POST['aprobado'] == 1){
						$actualizarRegistro = true;
						$tipoActualizacion = 'continua';
					} else if ($_POST['aprobado'] == 2){
						$actualizarRegistro = true;
						$tipoActualizacion = 'termina';
					}
					$data    = array("mensaje"=>'Ok',"codigo"=>$buffer, 'alerta'=>'');
				} else {
					$success = FALSE;
					$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
					$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo realizar el registro', 'sql'=>$sql);
				}
			} else {
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
				$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo realizar el registro', 'sql'=>$sql);
			}
			break;
		case 'cancelar':
			$tabla = 'crs_cuestionario_medico';
			$dataPost = prepareSqlFrom($_POST);
			$dataPost['fecha_termina'] = "'" . dateMeFull() . "'";
			$dataPost['aprobado'] = 3;
			// $dataPost['fid_usr'] = $_SESSION['site']['kid_usr'];
			unset($dataPost['action']);
			unset($dataPost['idEstudio']);
			if ($existe = $db->ExecuteUno($tabla, "kid_cuestionario = $idEstudio")){
				$sql = prepareSQL($dataPost, 'update', $tabla, "kid_cuestionario = $idEstudio");
				$msnOk = '';
				$data['sql'] = $sql;
				$datos = array("estatus_registro"=>9);
				$sqlUp = prepareSQL($datos, 'update', 'crs_registros', 'nip = ' . $existe['fid_registro']);
				// edis($sql);
				$do = $db->Insert($sql);
				if ($do){
					$revisarExpediente = false;
					$buffer  = "<div class='bg-success'>Datos guardados</div>";
					$actualizarRegistro = true;
					$tipoActualizacion = 'termina';
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok',"codigo"=>$buffer, 'alerta'=>'');
				} else {
					$success = FALSE;
					$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
					$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo realizar el registro', 'sql'=>$sql);
				}
			} else {
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
				$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo realizar el registro', 'sql'=>$sql);
			}
			break;
		case 'close':
			$action = $_POST['action'];
			$tabla = 'crs_nota_' . $_POST['tabla'];
			$dataPost = prepareSqlFrom($_POST);
			$dataPost['fecha_termina'] = "'" . dateMeFull() . "'";
			$dataPost['fid_usr'] = $_SESSION['site']['kid_usr'];
			unset($dataPost['action']);
			unset($dataPost['tabla']);
			if ($existe = $db->ExecuteUno($tabla, "idEstudio = $idEstudio")){
				$sql = prepareSQL($dataPost, 'update', $tabla, "idEstudio = $idEstudio");
				$msnOk = '';
			} else {
				$dataPost['fecha_registro'] = "'" . dateMeFull() . "'";
				$sql = prepareSQL($dataPost, 'create', $tabla);
				$msnOk = '';
			}
			$data['sql'] = $sql;
			// edis($sql);
			$do = $db->Insert($sql);
			if ($do){
				$revisarExpediente = true;
				$buffer  = "<div class='bg-success'>Datos guardados</div>";
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer, 'alerta'=>'');
			} else {
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
				$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo realizar el registro');
			}
			break;
		case 'update':
			$action = $_POST['action'];
			$tabla = 'crs_nota_' . $_POST['tabla'];
			$dataPost = prepareSqlFrom($_POST);
			unset($dataPost['action']);
			unset($dataPost['tabla']);
			if ($existe = $db->ExecuteUno($tabla, "idEstudio = $idEstudio")){
				$sql = prepareSQL($dataPost, 'update', $tabla, "idEstudio = $idEstudio");
				$msnOk = 'ok-cambio';
			} else {
				$dataPost['fecha_registro'] = "'" . dateMeFull() . "'";
				$dataPost['fecha_termina'] = "'" . dateMeFull() . "'";
				$dataPost['fid_usr'] = $_SESSION['site']['kid_usr'];
				$sql = prepareSQL($dataPost, 'create', $tabla);
				$msnOk = '';
			}
			$data['sql'] = $sql;
			// edis($sql);
			$do = $db->Insert($sql);
			if ($do){
				$revisarExpediente = true;
				$buffer = "<div class='bg-success'>Datos guardados</div>";
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer, 'alerta'=>$msnOk);
			} else {
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se pudo actualizar el registro</div>";
				$data    = array("mensaje"=>'Nel',"codigo"=>'', 'alerta'=>'No se pudo actualizar el registro');
			}
			break;
		default:
			$success = FALSE;
			$error   = 'Error, no existe el método (' . $_POST['action'] . ') ' . __LINE__;
			$data    = array('action'=>$_POST['action']);
			break;
	}
} else {
	$success = FALSE;
	$error   = 'Error al especificar el método';
	$data    = array();
}
if ($revisarExpediente){
	$sql = "SELECT * FROM crs_notas_exp WHERE estatus = 1 ORDER BY orden ASC";
	// $menu = dis($sql);
	if ($todas = $db->Execute($sql)){
		$totalNotas = count($todas);
		$completadas = 0;
		foreach ($todas as $una) {
			$tablaNota = 'crs_nota_' . $una['tabla'];
			if ($datosEstaNota = $db->ExecuteUno($tablaNota, "`idEstudio` = $idEstudio")){
				if ($datosEstaNota['fecha_termina'] != '0000-00-00 00:00:00'){
					$completadas++;
				}
			}
		}
		if ($totalNotas == $completadas){
			$data['alerta'] = 'final';
			$data['salto'] = APP_URL . 'MED/notas/' . $datosExpediente['fid_registro'];
		}
	}
}
if ($actualizarRegistro){
	switch ($tipoActualizacion) {
		case 'continua':
			$registro = $db->ExecuteUno('crs_registros', 'nip = ' . $existe['fid_registro']);
			$response = $admRegistro->cierraExpedienteMedico($registro['nip'], $registro['correo']);
			$success = $response['success'];
			$error   = $response['error'];
			break;
		case 'termina':
			// Es rechazado, cambia a estatus 9
			$datos = array("estatus_registro"=>9);
			$sql = prepareSQL($datos, 'update', 'crs_registros', 'nip = ' . $existe['fid_registro']);
			// edis($sql);
			$do = $db->Insert($sql);
			break;
	}
}
$respuesta = array('action'=>$action,'success'=>$success,'error'=>$error,'data'=>$data);
echo json_encode($respuesta);
?>
