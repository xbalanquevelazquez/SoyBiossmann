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
			if($resultados = $conn->query($query)){
				$numeroDeRegistros = $conn->num_rows($resultados);
				$labelRegistros = 'registro'.(($numeroDeRegistros == 1)?'':'s');

				$dataListado['numeroDeRegistros'] = $numeroDeRegistros;
				$dataListado['labelRegistros'] = $labelRegistros;
				
				################################# 
				$zebra = 0;
				$personal = '';
				$letras = '';
				$resultados = $conn->fetch($resultados);

				foreach($resultados as $res){
					$res['inicial'] = normaliza($res['inicial']);//utf8_encode
					$res['nombre'] = mb_convert_encoding($res['nombre'],'UTF-8');
					$res['paterno'] = mb_convert_encoding($res['paterno'],'UTF-8');
					$res['materno'] = mb_convert_encoding($res['materno'],'UTF-8');

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
						$dataBoton = array("clase"=>"mensaje","tipoDeEnlace"=>"sms","info"=>$res['movil'],"tipoIcono"=>"sms");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					if($res['email'] !== ''){//GENERA CODIGO BOTON EMAIL
						$dataBoton = array("clase"=>"email","tipoDeEnlace"=>"mailto","info"=>$res['email'],"tipoIcono"=>"envelope");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					if($res['movil'] !== ''){//GENERA CODIGO BOTON TEL MOVIL
						$dataBoton = array("clase"=>"phone","tipoDeEnlace"=>"tel","info"=>$res['movil'],"tipoIcono"=>"mobile-alt");
						$botones .= makeTemplate('_directorio_C2_boton.html', $dataBoton);
					} 
					$dataPersonaBox['botones'] = $botones;
					$personal .= makeTemplate('_directorio_C_personabox.html', $dataPersonaBox);
					########END personaBox
				$zebra++;
			}
			$alfabeto = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			$listadoIniciales = array();
			if($resultadoIniciales = $conn->query($queryGroup,$conn)){
				$inicialesResultantes = $conn->fetch($resultadoIniciales);
				foreach($inicialesResultantes as $resInic){
					array_push($listadoIniciales, normaliza($resInic['inicial']));
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
		$data    = array("codigo"=>$html);#mb_detect_encoding
		break;				
		/*****************************************/
		/******** DEFAULT  **********/
		/*****************************************/
		/*****************************************/
		/******** getDetalle  **********/
		/*****************************************/
		case 'getDetalle':
			$html = '';
			$dataPersonaBox = array();

			$id = (isset($_POST['id'])		&&	$_POST['id']!='' 	&& 	make_safe($_POST['id']))	?normaliza($_POST['id'])	:0;

			$query = "SELECT id_empleado as id, nombre, paterno, materno, company, area, puesto, id_edificio, piso, did, ext, email, movil, foto FROM empleados WHERE baja=0 AND id_empleado=$id LIMIT 1";

			if($resultado = $conn->query($query,$conn)){
				$res = $conn->fetch($resultado);
				$res = $res[0];
	
				$queryEdificio = "SELECT nombre, direccion, conmutador, mapa FROM edificios WHERE id_edificio={$res['id_edificio']}";
				$resultadoEdificio = $conn->query($queryEdificio,$conn);
	
				$edificio = $conn->fetch($resultadoEdificio);
				$edificio = $edificio[0];


				$dataPersonaBox['foto'] = $res['foto'];
				$dataPersonaBox['nombreCompleto'] = mb_convert_encoding("{$res['nombre']} {$res['paterno']} {$res['materno']}",'UTF-8');
				$dataPersonaBox['puesto'] = mb_convert_encoding($res['puesto'],'UTF-8');
				$dataPersonaBox['area'] =  mb_convert_encoding($res['area']);

				$botones = '';
				if($res['email'] !== ''){//GENERA CODIGO BOTON
					$dataBoton = array("clase"=>"","tipoDeEnlace"=>"mailto","info"=>$res['email'],"tipoIcono"=>"envelope");
					$botones .= makeTemplate('_directorio_detalle_boton.html', $dataBoton);
				}
				if($res['movil'] !== ''){//GENERA CODIGO BOTON
					$dataBoton = array("clase"=>"","tipoDeEnlace"=>"tel","info"=>$res['movil'],"tipoIcono"=>"mobile-alt");
					$botones .= makeTemplate('_directorio_detalle_boton.html', $dataBoton);
				}
				if($res['ext'] !== ''){//GENERA CODIGO BOTON
					$dataBoton = array("clase"=>"","tipoDeEnlace"=>"","info"=>"{$edificio['conmutador']} ext. {$res['ext']}","tipoIcono"=>"phone");
					$botones .= makeTemplate('_directorio_detalle_boton_ext.html', $dataBoton);
				}
				if($edificio['direccion'] !== ''){//GENERA CODIGO BOTON
					$dataBoton = array("clase"=>"","mapa"=>str_replace(';', ',', $edificio['mapa']),"edificio"=>mb_convert_encoding($edificio['nombre'],'UTF-8'),"direccion"=>$edificio['direccion']);
					$botones .= makeTemplate('_directorio_detalle_boton_mapa.html', $dataBoton);
				} 

				$dataPersonaBox['botones'] = $botones;
			
				$html = makeTemplate('_directorio_detalle.html', $dataPersonaBox);

				$success = TRUE;
				$error   = '';
				$data    = array("codigo"=>$html);
			}else{
				$success = FALSE;
				$error   = 'Error: '.$conn->error;
				$data    = array();
			}			
		break;				
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