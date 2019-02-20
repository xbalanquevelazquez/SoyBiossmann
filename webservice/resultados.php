<?php
session_start();

header("Content-Type: application/json", true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
define("VIEWABLE",TRUE);

include_once("../admin/cnf/configuracion.cnf.php");
include_once("cnfg.directorio.php");
#if(DEBUG){

#}
$success 		= FALSE;
$error   		= 'No especificado';
$data    		= array();
$action  		= 'No se especeficó action';
$now	 		= date("Y-m-d H:i:s");
$prefijo 		= PREFIJO;

if(isset($_POST['action']) && $_POST['action']!=''){
	$action = $_POST['action'];
	// edi('action: ' . $action);
	// $action = '';
	switch ($action) {
		/*****************************************/
		/******** getListado  **********/
		/*****************************************/
		case 'getListado':
			$html = '';
			$dataListado = array();
			$dataInicial = array();
			$dataPersonaBox = array();
			$dataLetraAncla = array();

			$nombre = (isset($_POST['nombre'])		&&	$_POST['nombre']!='' 	&& 	make_safe($_POST['nombre']))	?normaliza($_POST['nombre'])	:'';
			$query = "SELECT id_empleado as id,	TRIM(nombre) as nombre, TRIM(paterno) as paterno, TRIM(materno) as materno, company, ext, email, movil, SUBSTRING(TRIM(paterno),1,1) as inicial, foto FROM empleados WHERE baja=0";
			$detail = '';
			$queryGroup = "SELECT TRIM(nombre) as nombre, TRIM(paterno) as paterno,SUBSTRING(TRIM(paterno),1,1) as inicial FROM empleados WHERE baja=0";

			$arrNombre = explode(' ', $nombre);

			foreach($arrNombre as $nombreFragmento){
				if ($nombreFragmento != ""){
				    $detail .= " AND (nombre like '%$nombreFragmento%' OR paterno like '%$nombreFragmento%' OR materno like '%$nombreFragmento%' OR email like '%$nombre%' OR movil like '%$nombre%')";
				}
			}

			$query .= $detail." ORDER BY paterno, materno, nombre ASC";
			$queryGroup .= $detail." GROUP BY inicial ORDER BY inicial ASC";

			$inicial = '';
			$imprimirInicial = FALSE;
			if($resultados = mysql_query($query,$conn)){
				$numeroDeRegistros = mysql_num_rows($resultados);
				$labelRegistros = 'registro'.(($numeroDeRegistros == 1)?'':'s');

				$dataListado['numeroDeRegistros'] = $numeroDeRegistros;
				$dataListado['labelRegistros'] = $labelRegistros;
				
				################################# 

				$zebra = 0;
				$personal = '';
				$letras = '';
				while($res = mysql_fetch_assoc($resultados)){
					$res['inicial'] = normaliza(utf8_encode($res['inicial']));
					$res['nombre'] = utf8_decode($res['nombre']);
					$res['paterno'] = utf8_decode($res['paterno']);
					$res['materno'] = utf8_decode($res['materno']);

					if($res['inicial'] != $inicial){
						$inicial = $res['inicial'];
						$imprimirInicial = TRUE;
						$dataInicial = array("inicial"=>$inicial);
						$personal .= makeTemplate('_directorio_B_inicial.html', $dataInicial);
					}else{
						$imprimirInicial = FALSE;
					}
					
					########personaBox
					$dataPersonaBox['zebra'] = ($zebra%2)?' zebra':'';
					$dataPersonaBox['resID'] = $res['id'];
					if($res['foto'] != ''){
						$foto=$res['foto']; 
					}else{ 
						$foto='no-photo.jpg'; 
					}
					$dataPersonaBox['foto'] = $foto;
					$dataPersonaBox['nombreCompleto'] = $res['nombre']." ".$res['paterno']." ".$res['materno'];
					$dataPersonaBox['nombreCompletoCoincidencias'] = encuentraCoincidencia($arrNombre,$res['paterno'])." ".encuentraCoincidencia($arrNombre,$res['materno'])." ".encuentraCoincidencia($arrNombre,$res['nombre']);
					$dataPersonaBox['ext'] = $res['ext']!=''? "Ext. ".$res['ext']:'';
					$botones = '';
					if($res['movil'] !== ''){//GENERA CODIGO BOTON SMS
						$dataBoton = array("clase"=>"mensaje","tipoDeEnlace"=>"sms","info"=>$res['movil'],"tipoIcono"=>"mensaje");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					if($res['email'] !== ''){//GENERA CODIGO BOTON EMAIL
						$dataBoton = array("clase"=>"email","tipoDeEnlace"=>"mailto","info"=>$res['email'],"tipoIcono"=>"mail");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					if($res['movil'] !== ''){//GENERA CODIGO BOTON TEL MOVIL
						$dataBoton = array("clase"=>"phone","tipoDeEnlace"=>"tel","info"=>$res['movil'],"tipoIcono"=>"phone");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					$dataPersonaBox['botones'] = $botones;
					$personal .= makeTemplate('_directorio_C_personabox.html', $dataPersonaBox);
					########END personaBox
				$zebra++;
			}
			$alfabeto = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$listadoIniciales = array();
			if($resultadoIniciales = mysql_query($queryGroup,$conn)){
				while($resInic = mysql_fetch_assoc($resultadoIniciales)){
					array_push($listadoIniciales, normaliza(utf8_encode($resInic['inicial'])));
				}
			}
			
			foreach($alfabeto as $letra){
				$letraACtual = array("letra"=>$letra);
				if(in_array(strtoupper($letra), $listadoIniciales)){ 
					$letras .= makeTemplate('_directorio_D_letra_ancla.html', $letraACtual);
				}else{
					$letras .= makeTemplate('_directorio_D_letra_bullet.html', $letraACtual);
				}
			} 


			########## INFO PARA PLANTILLA COMPLETA

			$dataListado['personal'] = $personal;
			$dataListado['letras'] = $letras;
			$html = makeTemplate('_directorio_A_listado.html', $dataListado);


		}else{
			$html = "<div class='resultados'>
				<div class='columnaIzq transicion'>
					<div class='bloqueDePersonal'>Sin resultados</div>
				</div>
			</div>";
		}
		$success = TRUE;
		$error   = '';
		$data    = array("codigo"=>$html);
		break;				
		/*****************************************/
		/******** DEFAULT  **********/
		/*****************************************/
		default:
			$success = FALSE;
			$error   = 'Error, no existe la action';
			$data    = array();
			break;
	}
}else{
	$success = FALSE;
	$error   = 'Error al especificar action';
	$data    = array();
}

$respuesta = array('action'=>$action,'success'=>$success,'error'=>$error,'data'=>$data);
echo json_encode($respuesta);
?>