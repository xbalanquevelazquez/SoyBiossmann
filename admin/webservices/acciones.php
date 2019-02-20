<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json", true);
define('VIEWABLE',TRUE);
include_once("../cnf/cnfg.crs.php");
if(DEBUG){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

$success 		= FALSE;
$error   		= 'No especificado';
$data    		= array();
$action  		= '';
$now	 		= date("Y-m-d H:i:s");
$prefijo 		= PREFIJO;

if(isset($_POST['action']) && $_POST['action']!=''){
	$action = $_POST['action'];
	// edi('action: ' . $action);
	// $action = '';
	switch ($action) {
		/*****************************************/
		/******** getSeccionesPInicial  **********/
		/*****************************************/
		case 'getSeccionesPInicial':
			$secciones = isset($_POST['secciones']) && trim($_POST['secciones']) != ''?trim($_POST['secciones']):'Sin especificar';
			$seccion_inicial = isset($_POST['seccion_inicial']) && trim($_POST['seccion_inicial']) != ''?trim($_POST['seccion_inicial']):'';

			if($secciones == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita seleccionar al menos una sección</div>";
				$data    = array();	
			}else{
		    	$buffer = '';
		    	/*$temp = explode(",", $secciones);
		    	$secciones = '';
		    	for($t=0; $t < count($temp); $t++){
		    		if($t > 0) $secciones .= ',';
		    		$secciones .= "'{$temp[$t]}'";
		    	}*/
				$query = "SELECT * FROM ".PREFIJO."acciones WHERE kid_accion in ($secciones)";
		    	$resSecciones = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    	if($myAdmin->conexion->numfilas >= 1){
					$buffer = "<select name='cmp[seccion_inicial]' class='form-control'>";
		    		foreach($resSecciones as $perf){
		    			$selected = "";
		    			if($seccion_inicial != '' && $seccion_inicial == $perf['acronimo_accion']){
							$selected = " selected='selected'";
		    			}
			    		$buffer .= "<option value='{$perf['acronimo_accion']}'$selected>".utf8_encode($perf['nombre_accion'])."</option>";
			    	}
			    	$buffer .= "</select>";
		    	}
		    	
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
			}
		break;
		/*****************************************/
		/******** setEstatusMunicipio  **********/
		/*****************************************/
		case 'setEstatusMunicipio':
			$municipio = isset($_POST['municipio']) && trim($_POST['municipio']) != '' && is_numeric($_POST['municipio'])?trim($_POST['municipio']):'Sin especificar';
			$valor = isset($_POST['valor']) && trim($_POST['valor']) != '' && is_numeric($_POST['valor'])?trim($_POST['valor']):'Sin especificar';

			if($municipio == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el municipio</div>";
				$data    = array();	
			}else if($valor == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el valor</div>";
				$data    = array();	
			}else{
		    	$buffer = '';
		    	
				if($valor < 0) $valor=0;
				if($valor > 2) $valor=2;
				$datos = array('estatus_municipio' => $valor);
		    	
		    	if($myAdmin->conexion->update(PREFIJO.'cat_municipios',$datos,"WHERE kid_municipio=$municipio")){
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
		    	}else{
		    		$success = FALSE;
					$error   = "<div class='bg-warning'>".$myAdmin->conexion->error."</div>";
					$data    = array();	
		    	}
		    	
				
			}			
			break;
		/*****************************************/
		/******** getLocalidadesPorCP  **********/
		/*****************************************/
			case 'getLocalidadesPorCP':
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el CP correctamente</div>";
					$data    = array();	
				}else{
					//echo "SELECT * FROM ".PREFIJO."cat_cp WHERE codigo_postal LIKE '$id%'";
					$query = "SELECT * FROM ".PREFIJO."cat_cp WHERE codigo_postal LIKE '$id%'";
		    		$localidades = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_localidad$postfijo'>Localidad <span class='obligatorio'>*</span></label><select id='fid_localidad$postfijo' class='form-control'>";
						$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($localidades as $loc){
			    			$selected = "";
			    			/*if($seccion_inicial != '' && $seccion_inicial == $perf['acronimo_accion']){
								$selected = " selected='selected'";
			    			}*/
				    		$buffer .= "<option value='{$loc['kid_cp']}' estado='{$loc['fid_entidad']}' municipio='{$loc['fid_municipio']}' codigo_postal='{$loc['codigo_postal']}' $selected > CP {$loc['codigo_postal']} | ".utf8_encode($loc['tipo_asentamiento'])." ".utf8_encode($loc['asentamiento'])."  </option>";/*utf8_encode($loc['nombre_accion'])*/
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		/*****************************************/
		/******** getLocalidadesPorEstado  **********/
		/*****************************************/
			case 'getLocalidadesPorEstado':
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el Estado correctamente</div>";
					$data    = array();	
				}else{
					$query = "SELECT * FROM ".PREFIJO."cat_cp WHERE fid_entidad='$id'";
		    		$localidades = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_localidad$postfijo'>Localidad <span class='obligatorio'>*</span></label><select id='fid_localidad$postfijo' class='form-control'>";
						$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($localidades as $loc){
			    			$selected = "";
			    			
				    		$buffer .= "<option value='{$loc['kid_cp']}' estado='{$loc['fid_entidad']}' municipio='{$loc['fid_municipio']}' codigo_postal='{$loc['codigo_postal']}' $selected > CP {$loc['codigo_postal']} | ".utf8_encode($loc['tipo_asentamiento'])." ".utf8_encode($loc['asentamiento'])."  </option>";
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		/*****************************************/
		/******** getLocalidadesPorMunicipio  **********/
		/*****************************************/
			case 'getLocalidadesPorMunicipio':
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$municipio = isset($_POST['municipio']) && trim($_POST['municipio']) != '' && is_numeric($_POST['municipio'])?trim($_POST['municipio']):'Sin especificar';
				$localidad = isset($_POST['localidad']) && trim($_POST['localidad']) != '' && is_numeric($_POST['localidad'])?trim($_POST['localidad']):0;
				if($municipio == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el Estado correctamente</div>";
					$data    = array();	
				}else{
					$query = "SELECT * FROM ".PREFIJO."cat_cp WHERE fid_entidad='$id' AND fid_municipio='$municipio'";
		    		$localidades = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_localidad$postfijo'>Localidad <span class='obligatorio'>*</span></label><select id='fid_localidad$postfijo' class='form-control'>";
						$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($localidades as $loc){
			    			$selected = "";
			    			if($localidad != 0 && $localidad == $loc['kid_cp']){
		 						$selected = " selected='selected'";
		 	    			}
				    		$buffer .= "<option value='{$loc['kid_cp']}' estado='{$loc['fid_entidad']}' municipio='{$loc['fid_municipio']}' codigo_postal='{$loc['codigo_postal']}' $selected > CP {$loc['codigo_postal']} | ".utf8_encode($loc['tipo_asentamiento'])." ".utf8_encode($loc['asentamiento'])."  </option>";
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		
		/*****************************************/
		/******** setLocalidadPorMunicipio  **********/
		/*****************************************/
			case 'setLocalidadPorMunicipio':
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$municipio = isset($_POST['municipio']) && trim($_POST['municipio']) != '' && is_numeric($_POST['municipio'])?trim($_POST['municipio']):'Sin especificar';
				$localidad = isset($_POST['localidad']) && trim($_POST['localidad']) != '' && is_numeric($_POST['localidad'])?trim($_POST['localidad']):0;
				if($municipio == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el Estado correctamente</div>";
					$data    = array();	
				}else{
					$query = "SELECT * FROM ".PREFIJO."cat_cp WHERE fid_entidad='$id' AND fid_municipio='$municipio'";
		    		$localidades = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_localidad$postfijo'>Localidad <span class='obligatorio'>*</span></label><select id='fid_localidad$postfijo' class='form-control'>";
						$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($localidades as $loc){
			    			$selected = "";
			    			if($localidad != 0 && $localidad == $loc['kid_cp']){
		 						$selected = " selected='selected'";
		 	    			}
				    		$buffer .= "<option value='{$loc['kid_cp']}' estado='{$loc['fid_entidad']}' municipio='{$loc['fid_municipio']}' codigo_postal='{$loc['codigo_postal']}' $selected > CP {$loc['codigo_postal']} | ".utf8_encode($loc['tipo_asentamiento'])." ".utf8_encode($loc['asentamiento'])."  </option>";
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		/*****************************************/
		/******** getLocalidadesReset  **********/
		/*****************************************/
			case 'getLocalidadesReset':
				$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
				$buffer = "<label for='fid_localidad$postfijo'>Localidad <span class='obligatorio'>*</span></label><select id='fid_localidad$postfijo' class='form-control'>";
				$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		
				$buffer .= "</select>";

				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
			break;
		/*****************************************/
		/******** getEstados  **********/
		/*****************************************/
			case 'getEstados':
				$estado = isset($_POST['estado']) && trim($_POST['estado']) != '' && is_numeric($_POST['estado'])?trim($_POST['estado']):'Sin especificar';
				if($estado == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el Estado correctamente</div>";
					$data    = array();	
				}else{
					//echo "SELECT * FROM ".PREFIJO."cat_cp WHERE codigo_postal LIKE '$id%'";
					$query = "SELECT * FROM ".PREFIJO."cat_entidades_federativas WHERE estatus_entidad > 0";
		    		$registros = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_entidad$postfijo'>Estado <span class='obligatorio'>*</span></label><select id='fid_entidad$postfijo' class='form-control'>";
						#$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($registros as $reg){
			    			$selected = "";
			    			if($estado != '' && $estado == $reg['kid_entidad']){
								$selected = " selected='selected'";
			    			}
				    		$buffer .= "<option value='{$reg['kid_entidad']}' $selected > ".utf8_encode($reg['nombre_entidad'])."  </option>";/*{$reg['abreviatura_entidad']} | *//*utf8_encode($loc['nombre_accion'])*/
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		/*****************************************/
		/******** getMunicipios  **********/
		/*****************************************/
			case 'getMunicipios':
				$municipio = isset($_POST['municipio']) && trim($_POST['municipio']) != '' && is_numeric($_POST['municipio'])?trim($_POST['municipio']):'Sin especificar';
				$estado = isset($_POST['estado']) && trim($_POST['estado']) != '' && is_numeric($_POST['estado'])?trim($_POST['estado']):'Sin especificar';
				if($municipio == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el Municipio correctamente</div>";
					$data    = array();	
				}else{
					//echo "SELECT * FROM ".PREFIJO."cat_cp WHERE codigo_postal LIKE '$id%'";
					$query = "SELECT * FROM ".PREFIJO."cat_municipios WHERE fid_entidad='$estado'";
		    		$registros = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));

		    			$postfijo = isset($_POST['postfijo']) && $_POST['postfijo']!=''?$_POST['postfijo']:'';
						$buffer = "<label for='fid_municipio$postfijo'>Municipio <span class='obligatorio'>*</span></label><select id='fid_municipio$postfijo' class='form-control'>";
						#$buffer .= "<option value='0'>SELECCIONA UNA LOCALIDAD</option>";
			    		foreach($registros as $reg){
			    			$selected = "";
			    			if($municipio != '' && $municipio == $reg['cat_key']){
								$selected = " selected='selected'";
			    			}
				    		$buffer .= "<option value='{$reg['cat_key']}' $selected > ".utf8_encode($reg['nombre_municipio'])."  </option>";/*utf8_encode($loc['nombre_accion'])*/
				    	}
				    	$buffer .= "</select>";

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
				}
			break;
		/*****************************************/
		/******** SAVE CC  **********/
		/*****************************************/
			case 'save':
				$alerta = '';
				$diasTranscurridos = '';
				$tabla = isset($_POST['tabla']) && trim($_POST['tabla']) != ''?trim($_POST['tabla']):'Sin especificar';
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				//$interaccion = isset($_POST['interaccion']) && trim($_POST['interaccion']) != '' && is_numeric($_POST['interaccion'])?trim($_POST['interaccion']):'Sin especificar';
				//$proposito = isset($_POST['proposito']) && trim($_POST['proposito']) != '' && is_numeric($_POST['proposito'])?trim($_POST['proposito']):'Sin especificar';
				//$resultado = isset($_POST['resultado']) && trim($_POST['resultado']) != '' && is_numeric($_POST['resultado'])?trim($_POST['resultado']):'Sin especificar';
				#print_r($_POST['datos']);
				$datosIn = json_decode($_POST['datos'],TRUE);
				$datosNSEIn = json_decode($_POST['datosNSE'],TRUE);
				
				#print_r($datosIn);
				$datos = isset($_POST['datos']) && count($datosIn) > 0 && is_array($datosIn)? $datosIn: array();
				$datosNSE = isset($_POST['datosNSE']) && count($datosNSEIn) > 0 && is_array($datosNSEIn)? $datosNSEIn: array();
				

				if($tabla == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar la tabla para guardar los datos</div>";
					$data    = array();
				}else if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				/*}else if($interaccion == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>No se ha indicado la interacción</div>";
					$data    = array();	
				}else if($proposito == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el propósito</div>";
					$data    = array();	
				}else if($resultado == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el resultado</div>";
					$data    = array();	*/
				}else if(count($datos) < 1){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar los datos para guardar </div>";
					$data    = array();	
				}else{
					if($datos['fecha_nacimiento'] == '') $datos['fecha_nacimiento'] = NULL;
					if($datos['fum'] == '') $datos['fum'] = NULL;
					$datos['aviso_contacto'] = htmlentities($datos['aviso_contacto']);

		    		if($myAdmin->conexion->update(PREFIJO.$tabla,$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){

						$existeRegistroNSE = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT COUNT(*) as total FROM ".PREFIJO."subsidio WHERE fid_registro='$id' LIMIT 1"));
						$existeRegistroNSE = $existeRegistroNSE[0]['total'];
		    			if(count($datosNSE) > 0 && $datos['tiene_subsidio']==1){//HAY DATOS DE SUBSIDIO PARA GUARDAR
		    				
		    				if($existeRegistroNSE > 0){//EXISTE REGISTRO, ACTUALIZAR
		    					$myAdmin->conexion->update(PREFIJO.'subsidio',$datosNSE,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE);
		    				}else{//NO EXISTE REGISTRO, CREAR
		    					$datosNSE['fid_registro'] = $id;
		    					$myAdmin->conexion->insert(PREFIJO.'subsidio',$datosNSE,'TEXT',FALSE);
		    				}
		    				//$myAdmin->conexion->update(PREFIJO.'registros',array('tiene_subsidio'=>1),$condicion=" WHERE nip='$id'",'TEXT',FALSE);

		    			}else{//NO HAY DATOS DE SUBSIDIO
		    				//$myAdmin->conexion->update(PREFIJO.'registros',array('tiene_subsidio'=>0),$condicion=" WHERE nip='$id'",'TEXT',FALSE);
		    				if($existeRegistroNSE > 0){
		    					$datosNSE = array('pregunta_1'=>0,'pregunta_2'=>0,'pregunta_3'=>0,'pregunta_4'=>0,'pregunta_5'=>0,'pregunta_6'=>0);
		    					$myAdmin->conexion->update(PREFIJO.'subsidio',$datosNSE,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE);
		    				}
		    			}

		    			//$notasinteraccion = $_POST['notasinteraccion'];
		    			//if($myAdmin->conexion->update(PREFIJO.'interacciones',array('fid_proposito'=>$proposito,'fid_resultado'=>$resultado,'nota'=>$notasinteraccion),$condicion=" WHERE kid_interaccion='$interaccion' AND fid_registro='$id'",'TEXT',FALSE)){
							//VERIFICACION DE EXCEPCIONES
			    			$data = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."registros WHERE nip='$id' LIMIT 1"));
			    			$data = $data[0];

			    			$admRegistro->setRegistro($data);
							$admRegistro->validarDatos();
							/*$admRegistro->proposito = $proposito;
							$admRegistro->resultado = $resultado;
							$admRegistro->validarInteraccion();*/

							$nse = '';
							$aplicaSubsidio = '';
							if(count($datosNSE) > 0 && $datos['tiene_subsidio']==1){
								$resultadoNSE = $admRegistro->validaNSE($datosNSE);
								$admRegistro->alerta .= $resultadoNSE['alerta'];
								$nse = $admRegistro->getNSE($resultadoNSE['NSE']);
								$aplicaSubsidio = $admRegistro->aplicaSubsidio();
							}

										    			
							$buffer = "<div class='bg-success'>Datos guardados </div>";
							$success = TRUE;
							$error   = '';
							$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"alerta"=>$admRegistro->alerta,"diastranscurridos"=>$admRegistro->diasTranscurridos,"edad"=>$admRegistro->edad,"NSE"=>$nse,'aplicasubsidio'=>$aplicaSubsidio);
		    			}else{
			    			$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
		    			
		    		/*}else{
		    			$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
		    		}*/
				}
			break;
		/*****************************************/
		/******** SAVE LOG registro con acompañante  **********/
		/*****************************************/
			case 'saveconacompanante':
				$alerta = '';
				$diasTranscurridos = '';
				$tabla = isset($_POST['tabla']) && trim($_POST['tabla']) != ''?trim($_POST['tabla']):'Sin especificar';
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				#$interaccion = isset($_POST['interaccion']) && trim($_POST['interaccion']) != '' && is_numeric($_POST['interaccion'])?trim($_POST['interaccion']):'Sin especificar';
				#$proposito = isset($_POST['proposito']) && trim($_POST['proposito']) != '' && is_numeric($_POST['proposito'])?trim($_POST['proposito']):'Sin especificar';
				#$resultado = isset($_POST['resultado']) && trim($_POST['resultado']) != '' && is_numeric($_POST['resultado'])?trim($_POST['resultado']):'Sin especificar';
				#print_r($_POST['datos']);
				$datosIn = json_decode($_POST['datos'],TRUE);
				$datosNSEIn = json_decode($_POST['datosNSE'],TRUE);
				
				#print_r($datosIn);
				$datos = isset($_POST['datos']) && count($datosIn) > 0 && is_array($datosIn)? $datosIn: array();
				$datosNSE = isset($_POST['datosNSE']) && count($datosNSEIn) > 0 && is_array($datosNSEIn)? $datosNSEIn: array();

				$acompanante = array();
				
				if(isset($_POST['acompanante']) && count($_POST['acompanante'])>0){
					$acompanante = json_decode($_POST['acompanante'],TRUE);
				}
				if($tabla == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar la tabla para guardar los datos</div>";
					$data    = array();
				}else if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				}else if(count($datos) < 1){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar los datos para guardar </div>";
					$data    = array();	
				}else{
					if($datos['fecha_nacimiento'] == '') $datos['fecha_nacimiento'] = NULL;
					if($datos['fum'] == '') $datos['fum'] = NULL;
					$datos['aviso_contacto'] = htmlentities($datos['aviso_contacto']);

		    		if($myAdmin->conexion->update(PREFIJO.$tabla,$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){

		    			if(count($acompanante)>0 && $acompanante['kid_acompanante'] != 0){//GUARDAR INFORMACION DE ACOMPAÑANTE
		    				$error = '';
		    				if(!$myAdmin->conexion->update(PREFIJO.'acompanantes',$acompanante,$condicion=" WHERE kid_acompanante='{$acompanante['kid_acompanante']}'",'TEXT',FALSE)){
				    			$success = FALSE;
								$error   .= "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
								$data    = array();	
			    			}
		    			}

						$existeRegistroNSE = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT COUNT(*) as total FROM ".PREFIJO."subsidio WHERE fid_registro='$id' LIMIT 1"));
						$existeRegistroNSE = $existeRegistroNSE[0]['total'];
		    			if(count($datosNSE) > 0 && $datos['tiene_subsidio']==1){//HAY DATOS DE SUBSIDIO PARA GUARDAR
		    				
		    				if($existeRegistroNSE > 0){//EXISTE REGISTRO, ACTUALIZAR
		    					$myAdmin->conexion->update(PREFIJO.'subsidio',$datosNSE,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE);
		    				}else{//NO EXISTE REGISTRO, CREAR
		    					$datosNSE['fid_registro'] = $id;
		    					$myAdmin->conexion->insert(PREFIJO.'subsidio',$datosNSE,'TEXT',FALSE);
		    				}
		    				//$myAdmin->conexion->update(PREFIJO.'registros',array('tiene_subsidio'=>1),$condicion=" WHERE nip='$id'",'TEXT',FALSE);

		    			}else{//NO HAY DATOS DE SUBSIDIO
		    				//$myAdmin->conexion->update(PREFIJO.'registros',array('tiene_subsidio'=>0),$condicion=" WHERE nip='$id'",'TEXT',FALSE);
		    				if($existeRegistroNSE > 0){
		    					$datosNSE = array('pregunta_1'=>0,'pregunta_2'=>0,'pregunta_3'=>0,'pregunta_4'=>0,'pregunta_5'=>0,'pregunta_6'=>0);
		    					$myAdmin->conexion->update(PREFIJO.'subsidio',$datosNSE,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE);
		    				}
		    			}

							//VERIFICACION DE EXCEPCIONES
			    			$data = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."registros WHERE nip='$id' LIMIT 1"));
			    			$data = $data[0];

			    			$admRegistro->setRegistro($data);
							$admRegistro->validarDatos();
						
							$nse = '';
							$aplicaSubsidio = '';
							if(count($datosNSE) > 0 && $datos['tiene_subsidio']==1){
								$resultadoNSE = $admRegistro->validaNSE($datosNSE);
								$admRegistro->alerta .= $resultadoNSE['alerta'];
								$nse = $admRegistro->getNSE($resultadoNSE['NSE']);
								$aplicaSubsidio = $admRegistro->aplicaSubsidio();
							}


			    			
							$buffer = "<div class='bg-success'>Datos guardados </div>";
							$success = TRUE;
							$error   .= '';
							$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"alerta"=>$admRegistro->alerta,"diastranscurridos"=>$admRegistro->diasTranscurridos,"edad"=>$admRegistro->edad,"NSE"=>$nse,'aplicasubsidio'=>$aplicaSubsidio);
		    			
		    		}else{
		    			$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
		    		}
				}
			break;
		/*****************************************/
		/******** SAVE INTERACCION  **********/
		/*****************************************/
			case 'saveinteraccion':
				$alerta = '';
				$diasTranscurridos = '';
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$interaccion = isset($_POST['interaccion']) && trim($_POST['interaccion']) != '' && is_numeric($_POST['interaccion'])?trim($_POST['interaccion']):'Sin especificar';
				$proposito = isset($_POST['proposito']) && trim($_POST['proposito']) != '' && is_numeric($_POST['proposito'])?trim($_POST['proposito']):'Sin especificar';
				$resultado = isset($_POST['resultado']) && trim($_POST['resultado']) != '' && is_numeric($_POST['resultado'])?trim($_POST['resultado']):'Sin especificar';
				#print_r($_POST['datos']);
				
				
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				}else if($interaccion == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>No se ha indicado la interacción</div>";
					$data    = array();	
				}else if($proposito == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el propósito</div>";
					$data    = array();	
				}else if($resultado == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el resultado</div>";
					$data    = array();	
				}else{

	    			$notasinteraccion = $_POST['notasinteraccion'];
		    		if($myAdmin->conexion->update(PREFIJO.'interacciones',array('fid_proposito'=>$proposito,'fid_resultado'=>$resultado,'nota'=>$notasinteraccion),$condicion=" WHERE kid_interaccion='$interaccion' AND fid_registro='$id'",'TEXT',FALSE)){
						//VERIFICACION DE EXCEPCIONES
			    			$data = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."registros WHERE nip='$id' LIMIT 1"));
			    			$data = $data[0];

			    			$admRegistro->setRegistro($data);
							$admRegistro->validarDatos();
							$admRegistro->proposito = $proposito;
							$admRegistro->resultado = $resultado;
							$admRegistro->validarInteraccion();
			    			
							$buffer = "<div class='bg-success'>Datos guardados </div>";
							$success = TRUE;
							$error   = '';
							$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"alerta"=>$admRegistro->alerta,"diastranscurridos"=>$admRegistro->diasTranscurridos);
		    			}else{
			    			$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
		    			
		    		
				}
			break;
		/*****************************************/
		/******** SAVE LOGISTICA  **********/
		/*****************************************/
			case 'savelogistica':
				$alerta = '';
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$proveedor_logistico = isset($_POST['proveedor_logistico']) && trim($_POST['proveedor_logistico']) != ''?trim($_POST['proveedor_logistico']):0;
				$num_guia = isset($_POST['num_guia']) && trim($_POST['num_guia']) != ''?trim($_POST['num_guia']):'';
				$fecha_estimada_entrega = isset($_POST['fecha_estimada_entrega']) && trim($_POST['fecha_estimada_entrega']) != ''?trim($_POST['fecha_estimada_entrega']):'';
				#print_r($_POST['datos']);

				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				}else if($proveedor_logistico == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el proveedor logistico</div>";
					$data    = array();	
				}else if($num_guia == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el número guia</div>";
					$data    = array();	
				}else{
					$datos = array();
					$datos['fecha_estimada_entrega'] = $fecha_estimada_entrega == ''?NULL:$fecha_estimada_entrega;
					$datos['proveedor_logistico'] = $proveedor_logistico;
					$datos['num_guia'] = $num_guia;
					
		    		if($myAdmin->conexion->update(PREFIJO.'registro_adicional',$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){
							//VERIFICACION DE EXCEPCIONES
			    			$data = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."registro_adicional WHERE fid_registro='$id' LIMIT 1"));
			    			$data = $data[0];

			    			if($data['proveedor_logistico']==0){
			    				$alerta .= '<span class=restriccion>No ha registrado el proveedor logístico</span><br/>';
			    			}
			    			if($data['num_guia']==''){
			    				$alerta .= '<span class=restriccion>No ha registrado el número de guía</span><br/>';
			    			}
			    			if($data['fecha_estimada_entrega']==''){
			    				$alerta .= '<span class=restriccion>No ha registrado la fecha estimada de entrega</span><br/>';
			    			}
			    			
							$buffer = "<div class='bg-success'>Datos guardados </div>";
							$success = TRUE;
							$error   = '';
							$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"alerta"=>$admRegistro->alerta.$alerta);
		    			
		    			
		    		}else{
		    			$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
		    		}
				}
			break;
		/*****************************************/
		/******** VALIDAR REGISTRO  **********/
		/*****************************************/
			case 'validarregistro':
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$code = isset($_POST['code']) && trim($_POST['code']) != ''?trim($_POST['code']):'Sin especificar';
				$now = date("Y-m-d H:i:s");
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "Necesita indicar el id correctamente";
					$data    = array();
				}else if($code == 'Sin especificar'){
					$success = FALSE;
					$error   = "Necesita indicar el código correctamente";
					$data    = array();	
				}else{
					$query = "SELECT * FROM ".PREFIJO."registros WHERE nip='$id' AND code='$code'";
					if($registro = $myAdmin->conexion->fetch($myAdmin->conexion->query($query))){
						$registro = $registro[0];
						if($registro['estatus_registro']==3){

							$queryConsentimiento = "SELECT * FROM ".PREFIJO."registro_adicional WHERE fid_registro='$id' LIMIT 1";
							if($consentimiento = $myAdmin->conexion->fetch($myAdmin->conexion->query($queryConsentimiento))){
								$datos = array('fecha_ultima_actualizacion'=>$now);
								$myAdmin->conexion->update(PREFIJO."registro_adicional",$datos," WHERE fid_registro='$id'");
								$buffer = $consentimiento[0];
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"registro"=>$registro);
							}else{
								
								$datos = array('fid_registro'=>$id,'fecha_ultima_actualizacion'=>$now);
								if(!$myAdmin->conexion->insert(PREFIJO."registro_adicional",$datos,$type='TEXTO')){
						    			$success = FALSE;
										$error   = "Error al insertar:".$myAdmin->conexion->error;
										$data    = array();	
						    	}
								$consentimiento = array('fid_registro'		=>'',
														'calle'				=>'',
														'num_exterior'		=>'',
														'num_interior'		=>'',
														'cp'				=>'',
														'fid_entidad'		=>'',
														'fid_municipio'		=>'',
														'fid_localidad'		=>'',
														'consentimiento'	=>'',
														'comprobante_pago'	=>'',
														'fid_acompanante'	=>'',
														'tiene_subsidio'	=>'');
								$buffer = $consentimiento;
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
							}

						}else{
							$success = FALSE;
							$error   = "<div class='bg-success setpadding5 text-white'>Sus datos se están procesando, puede cerrar ésta ventana.</div>";
							$data    = array();	
						}
					}else{
			    		$success = FALSE;
						$error   = "Error: Revise que el enlace que utilizó sea correcto, no encontramos sus datos en el sistema.";#Error al consultar registro:".$myAdmin->conexion->error." | ".$query." | ".count($registro);
						$data    = array();	
			    	}
				}
			break;		
		/*****************************************/
		/******** SAVE CONSENTIMIENTO  **********/
		/*****************************************/
			case 'saveconsentimiento':
				$now = date("Y-m-d H:i:s");
				$tabla = isset($_POST['tabla']) && trim($_POST['tabla']) != ''?trim($_POST['tabla']):'Sin especificar';
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$datosIn = json_decode($_POST['datos'],TRUE);
				$datos = isset($_POST['datos']) && count($datosIn) > 0 && is_array($datosIn)? $datosIn: array();
				$consentimiento = isset($_POST['consentimiento']) && trim($_POST['consentimiento']) != '' && is_numeric($_POST['consentimiento'])?trim($_POST['consentimiento']):'Sin especificar';
				$alerta = '';
				if($tabla == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar la tabla para guardar los datos</div>";
					$data    = array();
				}else if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				}else if($consentimiento == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Falta el dato de consentimiento</div>";
					$data    = array();	
				}else if(count($datos) < 1){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar los datos para guardar </div>";
					$data    = array();	
				}else{
					$datos['fecha_ultima_actualizacion'] = $now;
					$datos['consentimiento'] = $consentimiento;
		    		if($myAdmin->conexion->update(PREFIJO.$tabla,$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){

		    				$queryConsentimiento = "SELECT * FROM ".PREFIJO."registro_adicional WHERE fid_registro='$id'";
		    				if($resConsentimiento = $myAdmin->conexion->query($queryConsentimiento)){
		    					$infoFaltante = 0;
		    					$consentimientoData = $myAdmin->conexion->fetch($resConsentimiento);
		    					$consentimientoData = $consentimientoData[0];

								if($consentimientoData['calle'] == ''){ $infoFaltante++; }
								if($consentimientoData['num_exterior'] == ''){ $infoFaltante++; }
								
		    					if($consentimientoData['cp'] == '' || strlen($consentimientoData['cp'])<=4){ $infoFaltante++; }
								if($consentimientoData['fid_entidad'] == '99'){ $infoFaltante++; }
								if($consentimientoData['fid_municipio'] == '999'){ $infoFaltante++; }
								if($consentimientoData['fid_localidad'] == '0'){ $infoFaltante++; }

								if($infoFaltante > 0){
									$s = $infoFaltante>1?'s':'';
									$alerta .="<span class=restriccion>Faltan $infoFaltante dato$s que completar</span><br/>";
								}

								if($consentimientoData['consentimiento'] == '0'){ $alerta .= "<span class=restriccion>Tiene que aceptar el consentimiento informado</span><br/>"; }
								
								if($consentimientoData['comprobante_pago'] == ''){ $alerta .= "<span class=restriccion>No ha enviado comprobante de pago</span><br/>"; }

								$buffer = "<div class='bg-success'>Datos guardados </div>";
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"consentimiento"=>$consentimiento,"alerta"=>$alerta);
		    				}else{
		    					$success = FALSE;
								$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
								$data    = array();	
		    				}


							
		    		}else{
		    			$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
		    		}
				}
			break;		
		/*****************************************/
		/*******  CONCLUIR CONSENTIMIENTO  *******/
		/*****************************************/
			case 'concluirconsentimiento':
				$now = date("Y-m-d H:i:s");
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
				$code = isset($_POST['code']) && trim($_POST['code']) != ''?trim($_POST['code']):'Sin especificar';
				$datos = array();
				$datosRegistro = array();
				$alerta = '';
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el id para guardar los datos</div>";
					$data    = array();	
				}else if($code == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Falta el codigo</div>";
					$data    = array();	
				}else{
					$datos['fecha_ultima_actualizacion'] = $now;
					
		    		if($myAdmin->conexion->update(PREFIJO.'registro_adicional',$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){

		    			$datosRegistro['estatus_registro'] = 4;
		    			if($myAdmin->conexion->update(PREFIJO.'registros',$datosRegistro,$condicion=" WHERE nip='$id' AND code='$code'",'TEXT',FALSE)){
		    					$admRegistro->cambiarEstatus($id,3,4);
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok');

						}else{
			    			$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
							
		    		}else{
		    			$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
		    		}
				}
			break;
		/*****************************************/
		/******** setAcompanante  **********/
		/*****************************************/
			case 'setAcompanante':
				$now = date("Y-m-d H:i:s");
				$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'';
				$id_usr_actual = isset($_POST['id_usr_actual']) && trim($_POST['id_usr_actual']) != '' && is_numeric($_POST['id_usr_actual'])?trim($_POST['id_usr_actual']):'';
				$acompanante = array(	
										'kid_acompanante'=> '0',
										'alias'=>'',
										'nombre_acompanante'=>'',
										'apellido_paterno_acompanante'=>'',
										'apellido_materno_acompanante'=>'',
										'cp_acompanante'=>'',
										'fid_entidad_acompanante'=>'99',
										'fid_municipio_acompanante'=>'999',
										'fid_localidad_acompanante'=>'0',
										'correo_acompanante'=>'',
										'lada_acompanante'=>'',
										'telefono_acompanante'=>''
									);
				if($id == ''){//SIN DATOS, GENERAR UNO NUEVO
					$queryinsert = "INSERT INTO ".PREFIJO."acompanantes(kid_acompanante,fecha_creacion_acompanante,alias,fid_entidad_acompanante,fid_municipio_acompanante,fid_localidad_acompanante,estatus_acompanante,fid_usuario_creador_acompanante) 
					VALUES(NULL,'$now','',99,999,0,1,'$id_usr_actual')";
					if($myAdmin->conexion->query($queryinsert)){
						$last_id =$myAdmin->conexion->last_id();
						$acompanante['kid_acompanante'] = $last_id;

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$acompanante);
					}else{
						$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
					}
					
				}else if($id == 0){//SOLO DEVOLVER CAMPOS VACIOS
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok',"codigo"=>$acompanante);
				}else{//CON DATOS, OBTENER
					$queryinsert = "SELECT * FROM ".PREFIJO."acompanantes WHERE kid_acompanante={$id}";
					if($acompananteRes = $myAdmin->conexion->query($queryinsert)){
						
						$acompanante = $myAdmin->conexion->fetch($acompananteRes);
						$acompanante = $acompanante[0];

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$acompanante);
					}else{
						$success = FALSE;
						$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
						$data    = array();	
					}
				}

				break;
		/*****************************************/
		/******** getRegistros  **********/
		/*****************************************/
			case 'getRegistros':
				$id = isset($_POST['id']) && trim($_POST['id']) != ''?trim($_POST['id']):'Sin especificar';
				$type = isset($_POST['type']) && trim($_POST['type']) != ''?trim($_POST['type']):'Sin especificar';
				if($id == 'Sin especificar'){
					$success = FALSE;
					$error   = "<div class='bg-warning'>Necesita indicar el valor a buscar correctamente</div>";
					$data    = array();	
				}else{
					$prefijo = PREFIJO;
					
					$condicion = "";
					$precondicion = explode(' ', $id);
					$cond = 1;
					foreach($precondicion as $a_condition){
						if($cond == 1){
							$condicion .= "WHERE ";
						}else{
							$condicion .= "OR  ";
						}
						$condicion .= " (nip LIKE '%$a_condition%' OR nombre LIKE '%$a_condition%' OR apellido_paterno LIKE '%$a_condition%') ";
						$cond++;
					}

					if($type=='MED'){
						$condicion .= " AND estatus_registro > 1";
					}
					

					$queryTotal = "SELECT COUNT(*) AS total FROM {$prefijo}registros $condicion";
					$query 		= "SELECT *,
									(SELECT nombre_entidad FROM {$prefijo}cat_entidades_federativas WHERE fid_entidad=kid_entidad) AS nombre_entidad,
									(SELECT nombre_municipio FROM {$prefijo}cat_municipios WHERE {$prefijo}cat_municipios.fid_entidad={$prefijo}registros.fid_entidad AND fid_municipio=cat_key) AS nombre_municipio,
									(SELECT CONCAT(tipo_asentamiento,' ',asentamiento) AS nombre_localidad FROM {$prefijo}cat_cp WHERE fid_localidad=kid_cp) AS nombre_localidad,
									(SELECT usr_nombre FROM crs_usuarios WHERE fid_usuario_creador=kid_usr) AS nombre_usuario_creador
									 FROM {$prefijo}registros $condicion ORDER BY nip DESC";
					$registrosTotal = $myAdmin->conexion->fetch($myAdmin->conexion->query($queryTotal));

					
					if($registrosTotal[0]['total'] == 0) $continuar = false; else $continuar = true;

					if($continuar){
						#$myAdmin->paginador->data1 = 'CC';
						$paginacion = $myAdmin->paginador->paginar($queryTotal,10,10,"$type");
						$registros 	= $myAdmin->conexion->fetch($myAdmin->conexion->query($query));#$query.' LIMIT '.$paginacion['LIMIT']
					}

						$buffer = "";

						$buffer .='<div class="fixed espaciador"></div>';
						$buffer .='<div class="paginador">';
						if($continuar){
							$buffer .= "Total de registros: ".$registrosTotal[0]['total']; 
						}
						$buffer .='</div>';
						$buffer .='<table class="table table-bordered table-striped table-hover">';
						$buffer .='	<thead class="bg-secondary text-white">';

						$buffer .='	<tr>';
						$buffer .='		<th>NIP</th>';
						$buffer .='		<th>Nombre(s)</th>';
						$buffer .='		<th>Apellido paterno</th>';
						if($type=='CC'){
							$buffer .='		<th>Estatus</th>';
						$buffer .='		<th>Usuario creador</th>';
						}
						$buffer .='		<th></th>';
						$buffer .='	</tr>';
						$buffer .='	</thead>';
						$buffer .='	<tbody class="">';

						if($registrosTotal[0]['total'] < 1){
							
											$buffer .='		<td colspan="6" align="center" class="bg-warning">No se encontraron datos</td>';

						}else{
							
							foreach($registros as $res){

								$admRegistro->setRegistro($res);
								$admRegistro->validarDatos();
	
								$buffer .='			<tr>';
								$buffer .='				<td>'.$admRegistro->nip.'</td>';
								$buffer .='				<td>'.$admRegistro->nombre.'</td>';
								$buffer .='				<td>'.$admRegistro->apellido_paterno.'</td>';

							if($type=='CC'){

								$buffer .='				<td class="mediumText">';

								$buffer .= $admRegistro->getEstatus();
								/*$buffer .='					<table class="cleanTable centerContent bloquesGrandes">';
								$buffer .='						<tr>';
							
							

								if($admRegistro->infoFaltante == 0){
									$icon = 'fa-check-square';
									$color = 'text-success';
									
									
									$label = 'Completado<br />';
								}else{
									$icon = 'fa-caret-square-right';
									$color = 'text-primary';
									$s = $admRegistro->infoFaltante>1?'s':'';
									$label = 'En progreso<br />';
								}
								if($admRegistro->alerta != ''){
									$color = 'text-danger';
								}
								$label .= $admRegistro->alerta;
								
								$buffer .='<td><i class="fa '.$icon.' '.$color.'" data-toggle="tooltip" data-placement="top" data-html="true" title="Datos básicos: '.$label.'<small>Registro creado: '.$res['fecha_creacion'].'"></small></i></td>';
							
								if($admRegistro->estatus_registro >= 2){
									$icon = 'fa-caret-square-right';
									$color = 'text-primary';
									$label = 'En progreso<br />';
								}else{
									$icon = 'fa-square';
									$color = 'text-secondary';
									$label = '';
								}

							
								$buffer .='						</tr>';
								$buffer .='					</table>';*/
								$buffer .='				<td>'.$res['nombre_usuario_creador'].'</td>';

								$buffer .='				<td><a href="'.APP_URL.$type.'/view/'.$res['nip'].'" class="btn btn-primary"><i class="fa fa-eye"></i> &nbsp;Consulta</a></td>';
								$buffer .='			</tr>';
							} else if ($type=='MED'){//END IF type==CC
								$buffer .='				<td><a href="'.APP_URL.'MED/notas/'.$res['nip'].'" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>';
								$buffer .='			</tr>';
							}//END IF type==MED
			
							}
						}
	
						$buffer .='	</tbody>';
						$buffer .='</table>';
						$buffer .='<div class="fixed espaciador"></div>';
						

						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"query"=>$query);
				}
			break;

		/*****************************************/
		/******** getPropositosResultados  **********/
		/*****************************************/
			case 'getPropositosResultados':
				$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."cat_propositos";
				$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
				if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
				
				$buffer = '';
				if($continuar){
					$query = "SELECT * FROM ".PREFIJO."cat_propositos ORDER BY kid_proposito";
					$arr = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));
				
					
				$buffer .= '<div class="fixed espaciador"></div>';
				$buffer .= '<p>Total de registros: '.$resInit[0]['total'].'</p>';
				$buffer .= '<table class="table table-bordered">';
				$buffer .= '	<thead class="bg-secondary text-white">';
				$buffer .= '	<tr>';
				$buffer .= '		<th>Propósito</th>';
				$buffer .= '		<th>Resultados</th>';
				$buffer .= '		<th>Estatus</th>';
				$buffer .= '	</tr>';
				$buffer .= '	</thead>';
				$buffer .= '	<tbody>';
	
	
				if(count($arr) <= 0){
				
							$buffer .= '		<tr>';
							$buffer .= '		<td colspan="4" align="center" class="aviso">No se encontraron datos</td>';
							$buffer .= '		</tr>';

				}else{
					foreach($arr as $res){
						$proposito_actual = $res['kid_proposito'];
						$cssEstatus = 'txt-';
						$descEstatus = '';
						switch ($res['estatus_proposito']) {
							case '0':
								$cssEstatus .= $descEstatus = 'inactivo';
								$ccsTR = 'bg-inactivo txt-inactivo';
								break;
							case '1':
								$cssEstatus .= $descEstatus =  'activo';
								$ccsTR = '';
								break;
						}
			
				$buffer .= '			<tr class="'.$ccsTR.'">';
				
				$buffer .= '				<td>'.$res['proposito'].'</td>';
				$buffer .= '				<td>';
					
					$queryResultados = "SELECT * FROM ".PREFIJO."cat_resultados WHERE fid_proposito=$proposito_actual ORDER BY kid_resultado ASC";
					$resultados =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryResultados));
					$buffer .= '<table class="table table-bordered cleanTable borderTable paddingTable">';
					foreach($resultados as $resultado){
						$cssEstatusRes = 'txt-';
						$descEstatusRes = '';
						switch ($resultado['estatus_resultado']) {
							case '0':
								$cssEstatusRes .= $descEstatusRes = 'inactivo';
								$ccsTRres = 'bg-inactivo txt-inactivo';
								break;
							case '1':
								$cssEstatusRes .= $descEstatusRes =  'activo';
								$ccsTRres = '';
								break;
						}
						$buffer .= '<tr class="'.$ccsTRres.'">';
						$buffer .= '<td>'.$resultado['resultado'].'</td>';
						$buffer .= '<td><i class="fa fa-circle '.$cssEstatusRes.' onoff clickable" alt="'.$descEstatusRes.'" title="'.$descEstatusRes.'" idu="'.$resultado['kid_resultado'].'" datascope="resultado" valor="'.$resultado['estatus_resultado'].'" ></i></td>';
						$buffer .= '</tr>';
						}
						$buffer .= '<tr><td colspan="2">
										<div class="form-row">
											<div class="col-4">
												<div class="form-group">
													<input type="text" class="form-control" datascope="resultado" id="resultset'.$res['kid_proposito'].'" idproposito="'.$res['kid_proposito'].'" />
												</div>
											</div>
										
											<div class="col-1">
												<div class="form-group">
													<button class="btn bg-primary btnAdd" inputlinked="resultset'.$res['kid_proposito'].'"><i class="fa fa-plus"></i></button>
												</div>
											</div>
										</div>
										</div>
									</td></tr>';
						$buffer .= '</table>';
							
						$buffer .= '				</td>';
						$buffer .= '				<td><i class="fa fa-circle '.$cssEstatus.' onoff clickable" alt="'.$descEstatus.'" title="'.$descEstatus.'" idu="'.$res['kid_proposito'].'" datascope="proposito" valor="'.$res['estatus_proposito'].'"></i></td>';
						$buffer .= '			</tr>';
						}
					}
					$buffer .= '	 	<tr><td colspan="2">
						<div class="form-row">
							<div class="col-4">
								<div class="form-group">
									<input type="text" class="form-control" datascope="proposito" id="propositoset" />
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<button class="btnAdd btn bg-primary" inputlinked="propositoset"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
					</td></tr>';
						$buffer .= '</table>';
					$buffer .= '	</tbody>';
					$buffer .= '</table>';
					$buffer .= '<div class="fixed espaciador"></div>';
				}
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"query"=>$query);
			break;

		/*****************************************/
		/******** getProveedoresLogisticos  **********/
		/*****************************************/
			case 'getProveedoresLogisticos':
				$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."cat_proveedor_logistico";
				$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
				if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
				
				$buffer = '';
				if($continuar){
					$query = "SELECT * FROM ".PREFIJO."cat_proveedor_logistico ORDER BY kid_proveedor_logistico";
					$arr = $myAdmin->conexion->fetch($myAdmin->conexion->query($query));
				
					$buffer .= '<div class="fixed espaciador"></div>';
					$buffer .= '<p>Total de registros: '.$resInit[0]['total'].'</p>';
					$buffer .= '<table class="table table-bordered">';
					$buffer .= '	<thead class="bg-secondary text-white">';
					$buffer .= '	<tr>';
					$buffer .= '		<th>Proveedor</th>';
					$buffer .= '		<th>Estatus</th>';
					$buffer .= '	</tr>';
					$buffer .= '	</thead>';
					$buffer .= '	<tbody>';
					if(count($arr) <= 0){
						$buffer .= '		<tr>';
						$buffer .= '		<td colspan="2" align="center" class="aviso">No se encontraron datos</td>';
						$buffer .= '		</tr>';

					}else{
						foreach($arr as $res){
							$proposito_actual = $res['kid_proveedor_logistico'];
							$cssEstatus = 'txt-';
							$descEstatus = '';
							switch ($res['estatus_proveedor']) {
								case '0':
									$cssEstatus .= $descEstatus = 'inactivo';
									$ccsTR = 'bg-inactivo txt-inactivo';
									break;
								case '1':
									$cssEstatus .= $descEstatus =  'activo';
									$ccsTR = '';
									break;
							}
							$buffer .= '<tr class="'.$ccsTR.'">';
							$buffer .= '	<td>'.$res['nombre_proveedor'].'</td>';
							$buffer .= '	<td><i class="fa fa-circle '.$cssEstatus.' onoff clickable" alt="'.$descEstatus.'" title="'.$descEstatus.'" idu="'.$res['kid_proveedor_logistico'].'" datascope="proveedor" valor="'.$res['estatus_proveedor'].'"></i></td>';
							$buffer .= '</tr>';
						}
					}
					$buffer .= '<tr><td colspan="2">
						<div class="form-row">
							<div class="col-4">
								<div class="form-group">
									<input type="text" class="form-control" datascope="proveedor" id="proveedorset" />
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<button class="btnAdd btn bg-primary" inputlinked="proveedorset"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
					</td></tr>';
					$buffer .= '</table>';
					$buffer .= '	</tbody>';
					$buffer .= '</table>';
					$buffer .= '<div class="fixed espaciador"></div>';
				}
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"query"=>$query);
			break;

		/*****************************************/
		/******** setEstatus  **********/
		/*****************************************/
		case 'setEstatus':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
			$valor = isset($_POST['valor']) && trim($_POST['valor']) != '' && is_numeric($_POST['valor'])?trim($_POST['valor']):'Sin especificar';
			$scope = isset($_POST['scope']) && trim($_POST['scope']) != ''?trim($_POST['scope']):'Sin especificar';
			if($id == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el id correctamente</div>";
				$data    = array();	
			}else if($valor == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el valor correctamente</div>";
				$data    = array();	
			}else if($scope == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el scope correctamente</div>";
				$data    = array();	
			}else{
				$prefijo = PREFIJO;
				
				if($valor == 1){
					$valor = 0;
				}else if($valor == 0){
					$valor = 1;
				}

				$success = FALSE;
				$error   = 'Ocurrió algún error';
				$data    = array();

				if($scope == 'proposito'){
					$myAdmin->conexion->update(PREFIJO.'cat_propositos',array('estatus_proposito'=>$valor)," WHERE kid_proposito=$id",'HTML');
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok');
				}else if($scope == 'resultado'){
					$myAdmin->conexion->update(PREFIJO.'cat_resultados',array('estatus_resultado'=>$valor)," WHERE kid_resultado=$id",'HTML');
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok');
				}else if($scope == 'proveedor'){
					$myAdmin->conexion->update(PREFIJO.'cat_proveedor_logistico',array('estatus_proveedor'=>$valor)," WHERE kid_proveedor_logistico=$id",'HTML');
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok');
				}
			}

			break;

		/*****************************************/
		/******** addOption  **********/
		/*****************************************/
		case 'addOption':
			
			$valor = isset($_POST['valor']) && trim($_POST['valor']) != ''?trim($_POST['valor']):'Sin especificar';
			$scope = isset($_POST['scope']) && trim($_POST['scope']) != ''?trim($_POST['scope']):'Sin especificar';
			if($valor == 'Sin especificar'){
				$success = FALSE;
				$error   = "Necesita indicar el valor correctamente";
				$data    = array();	
			}else if($scope == 'Sin especificar'){
				$success = FALSE;
				$error   = "Necesita indicar el scope correctamente";
				$data    = array();	
			}else{
				$prefijo = PREFIJO;
				
				$success = FALSE;
				$error   = 'Ocurrió algún error';
				$data    = array();

				if($scope == 'proposito'){
					$myAdmin->conexion->insert(PREFIJO.'cat_propositos',array('proposito'=>$valor),'TEXTO');
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok');
				}else if($scope == 'resultado'){
					$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
					if($id == 'Sin especificar'){
						$success = FALSE;
						$error   = "Necesita indicar el id correctamente";
						$data    = array();	
					}else{
						$myAdmin->conexion->insert(PREFIJO.'cat_resultados',array('fid_proposito'=>$id,'resultado'=>$valor),'TEXTO');
						$success = TRUE;
						$error   = '';
						$data    = array("mensaje"=>'Ok');

					}
				}else if($scope == 'proveedor'){
					$myAdmin->conexion->insert(PREFIJO.'cat_proveedor_logistico',array('nombre_proveedor'=>$valor),'TEXTO');
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok');
				}
			}

			break;

		/*****************************************/
		/******** getResultados  **********/
		/*****************************************/
		case 'getResultados':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';

				$query = "SELECT COUNT(*) as total FROM ".PREFIJO."cat_resultados WHERE fid_proposito='$id' AND estatus_resultado=1";
				$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($query));
				$continuar = $resInit[0]['total'] == 0? false : true;
				
				$buffer = '';
				if($continuar){
					$queryResultados = "SELECT * FROM ".PREFIJO."cat_resultados WHERE fid_proposito='$id' AND estatus_resultado=1 ORDER BY kid_resultado ASC";
					$resultados =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryResultados));
					$buffer .= '<div class="row"><div class="col-2"><h6>Resultado</h6></div><div class="col"><select id="resultados" class="form-control">
					<option value="0" selected="selected">SELECCIONA UNA OPCIÓN</option>';
					foreach($resultados as $resultado){
						$buffer .= '<option value="'.$resultado['kid_resultado'].'">'.$resultado['resultado'].'</option></td>';
					}
					$buffer .= '</select></div></div>';
					$buffer .= '<div class="fixed espaciador"></div>';
				}else{
					$buffer .= 'No se encontraron resultados para el propósito seleccionado';
				}
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"query"=>$query);
			break;
		/*****************************************/
		/******** guardarArchivo  **********/
		/*****************************************/
		case 'guardarArchivo':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
			$code = isset($_POST['code']) && trim($_POST['code']) != ''?trim($_POST['code']):'Sin especificar';

			$data = array();
			$now = date("Y-m-d H:i:s");
			$fechahora = date("Y-m-d_H-i-s");
			$nombreArchivo = "{$id}_{$code}_{$fechahora}";


			if(isset($_FILES)){  
			    #$error = false;
			    $files = array();

			    $uploaddir = FILE_PATH;
			    foreach($_FILES as $file){
			    	$extension = explode('.', $file['name']);
			    	$extension = end($extension);
			    	
			        if(move_uploaded_file($file['tmp_name'], $uploaddir.$nombreArchivo.'.'.$extension)){
			            $files[] = $uploaddir .$file['name'];

			            $datos['fecha_ultima_actualizacion'] = $now;
			            $datos['comprobante_pago'] = $nombreArchivo.'.'.$extension;
			            $datos['pago_validado'] = '0';
						if($myAdmin->conexion->update(PREFIJO.'registro_adicional',$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){
								$buffer = "<div class='bg-success'>Datos guardados </div>";
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>$buffer);
			    		}else{
			    			$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
			        }else{
			            $success = FALSE;
						$error .= 'Problema al subir el archivo';
						$data    = array();	
			        }
			    }
			   
			}else{
				$success = FALSE;
				$error   = 'No se indicaron archivos: '.count($_FILES);
				$data    = array();	
			}

			break;
		/*****************************************/
		/******** actualizarRegistro  **********/
		/*****************************************/
		case 'actualizarRegistro':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
			$estatus = isset($_POST['estatus']) && trim($_POST['estatus']) != ''?trim($_POST['estatus']):'Sin especificar';
			$modulo = isset($_POST['modulo']) && trim($_POST['modulo']) != ''?trim($_POST['modulo']):'Sin especificar';

			$datos = array();
			$now = date("Y-m-d H:i:s");
			$fechahora = date("Y-m-d_H-i-s");
			switch($modulo){
				case 'FIN':
					if($estatus == 1){//SE VALIDA COMPROBANTE DE PAGO
						//////////////////////////////////////
						$datos = array('estatus_registro'=>5);
						if($myAdmin->conexion->update(PREFIJO.'registros',$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){
							$datos = array('pago_validado'=>1);
							if($myAdmin->conexion->update(PREFIJO.'registro_adicional',$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>'');
							}else{
					    		$success = FALSE;
								$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
								$data    = array();	
				    		}
						}else{
				    		$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
			    		///////////////////////////////////////
					}else if($estatus == 2){//SE RECHAZA COMPROBANTE DE PAGO
						//////////////////////////////////////
						$datos = array('estatus_registro'=>3);/////CAMBIO ESTATUS PARA REGRESARLO
						if($myAdmin->conexion->update(PREFIJO.'registros',$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){
							$datos = array('pago_validado'=>2,'comprobante_pago'=>'');/////REGISTRO PAGO RECHAZADO  Y QUITO EL RECIBO!!!!
							if($myAdmin->conexion->update(PREFIJO.'registro_adicional',$datos,$condicion=" WHERE fid_registro='$id'",'TEXT',FALSE)){
								$success = TRUE;
								$error   = '';
								$data    = array("mensaje"=>'Ok',"codigo"=>'');
							}else{
					    		$success = FALSE;
								$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
								$data    = array();	
				    		}
						}else{
				    		$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
			    		///////////////////////////////////////
					}
					break;
				case 'LOG':
					if($estatus == 6){//ESTATUS EN ENVIO
						//////////////////////////////////////
						$datos = array('estatus_registro'=>6);
						if($myAdmin->conexion->update(PREFIJO.'registros',$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){
							$success = TRUE;
							$error   = '';
							$data    = array("mensaje"=>'Ok',"codigo"=>'');
						}else{
				    		$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
			    		///////////////////////////////////////
					}else if($estatus == 7){//ESTATUS COMPLETADO
						//////////////////////////////////////
						$datos = array('estatus_registro'=>7);
						if($myAdmin->conexion->update(PREFIJO.'registros',$datos,$condicion=" WHERE nip='$id'",'TEXT',FALSE)){
							$success = TRUE;
							$error   = '';
							$data    = array("mensaje"=>'Ok',"codigo"=>'');
						}else{
				    		$success = FALSE;
							$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
							$data    = array();	
			    		}
			    		///////////////////////////////////////
					}
					break;
				default:
					$success = FALSE;
					$error   = "No se indicó el módulo.";
					$data    = array();	
					break;
			}
					
			break;
		/*****************************************/
		/******** getInteracciones  **********/
		/*****************************************/
		case 'getInteracciones':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';

				$query = "SELECT COUNT(*) as total FROM ".PREFIJO."interacciones WHERE fid_registro='$id'";
				$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($query));
				$continuar = $resInit[0]['total'] == 0? false : true;
				$total = $resInit[0]['total'];
				
				$buffer = '';
				if($continuar){
					$queryinteracciones = "SELECT *,
							(SELECT proposito FROM {$prefijo}cat_propositos WHERE kid_proposito=fid_proposito) as proposito,
							(SELECT resultado FROM {$prefijo}cat_resultados WHERE kid_resultado=fid_resultado) as resultado,
							(SELECT usr_nombre FROM {$prefijo}usuarios WHERE kid_usr=fid_usr_inicio) as nombre_usuario_creador FROM {$prefijo}interacciones WHERE fid_registro='$id' ORDER BY kid_interaccion DESC";

					$interacciones =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryinteracciones));
					$conteo = count($interacciones);

					foreach($interacciones as $interaccion){
						$estatusInteraccion = 'abierto';
						$colorEstatus = ' bg-success text-white';
						$icon2show = 'edit';
						$buffer .= '<div>';
						$buffer .= '	<div class="interaccionFecha">'.formatoFechaTextual($interaccion['fecha_inicio_interaccion']).'</div>';
						$buffer .= '	<div class="interaccionFormat bg-verdeaguaclaro">';
						$buffer .= '		<div class="numeracion fleft">#'.$conteo.'</div>';
						$buffer .= '		<div class="botonesInteraccion fright">';

						#$buffer .= '			<a class="btn btn-primary text-white"><i class="fa fa-eye"></i></a>';
						if($interaccion['estatus_interaccion'] == 2){
							$estatusInteraccion = 'cerrado';
							$colorEstatus = ' bg-secondary text-white';
							$icon2show = 'eye';
						}
						$buffer .= '			<a class="btn '.$colorEstatus.' btnEditarInteraccion inlineElement" interaccion="'.$interaccion['kid_interaccion'].'" numerado="'.$conteo.'"><i class="fa fa-'.$icon2show.'"></i></a>';
						$buffer .= '			<div class="redondear estatusInteraccion versalitas setpadding5 inlineElement'.$colorEstatus.'">'.$estatusInteraccion.'</div>';

						$buffer .= '		</div><div class="fixed"></div>';

						$buffer .= '		<div class="innerContainer">';			
						#$buffer .= '			<div>Usuario:<span class="strong">'.$interaccion['nombre_usuario_creador'].'</span></div>';			
						$buffer .= '			<div><span class="strong">Propósito:</span> ';
						$buffer .= $interaccion['proposito']!=''?$interaccion['proposito']:'No registrado';
						$buffer .= '			</div>';
						$buffer .= '			<div><span class="strong">Resultado:</span> ';
						$buffer .= $interaccion['resultado']!=''?$interaccion['resultado']:'No registrado';
						$buffer .= '			</div>';
						$buffer .= '			<div><span class="strong">Notas:</span>'.$interaccion['nota'].'</div>';
						$buffer .= '		</div>';
						$buffer .= '	</div>';
						$buffer .= '</div>';
						$conteo--;
					}

				}else{
					$buffer .= '<div class="bg-info redondear text-white setpadding5">No se encontraron interacciones para el registro actual</div>';
				}
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$buffer,"query"=>$query,"total"=>$total);
			break;
		/*****************************************/
		/******** newInteraccion  **********/
		/*****************************************/
		case 'newInteraccion':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
			$kid_usr = isset($_POST['kid_usr']) && trim($_POST['kid_usr']) != '' && is_numeric($_POST['kid_usr'])?trim($_POST['kid_usr']):'Sin especificar';

			$id_usr_actual 	= $kid_usr;
			$datosInteraccion=array("fid_registro"=>$id,"fid_usr_inicio"=>$id_usr_actual,"fid_proposito"=>0,"fid_resultado"=>0,"fecha_inicio_interaccion"=>$now);

			if($myAdmin->conexion->insert(PREFIJO."interacciones",$datosInteraccion,'TEXTO')){
				$interaccionNum = $myAdmin->conexion->last_id();

				$query = "SELECT COUNT(*) as total FROM ".PREFIJO."interacciones WHERE fid_registro='$id'";
				$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($query));
				$continuar = $resInit[0]['total'] == 0? false : true;
				$total = $resInit[0]['total'];


				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>'',"interaccionNum"=>$interaccionNum,"total"=>$total);
			}else{
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
				$data    = array();	
			}

			break;
		/*****************************************/
		/******** getInteraccion  **********/
		/*****************************************/
		case 'getInteraccion':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';

			$buffer = '';
			$query = "SELECT *,
					(SELECT proposito FROM {$prefijo}cat_propositos WHERE kid_proposito=fid_proposito) as proposito,
					(SELECT resultado FROM {$prefijo}cat_resultados WHERE kid_resultado=fid_resultado) as resultado,
					(SELECT usr_nombre FROM {$prefijo}usuarios WHERE kid_usr=fid_usr_inicio) as nombre_usuario_creador FROM {$prefijo}interacciones WHERE kid_interaccion='$id'";
				
			if($resInteraccion = $myAdmin->conexion->query($query)){
				$interaccion =	$myAdmin->conexion->fetch($resInteraccion);
				$interaccion = $interaccion[0];
				$interaccion['fechaTextual'] = formatoFechaTextual($interaccion['fecha_inicio_interaccion']);
				$success = TRUE;
				$error   = '';
				$data    = array("mensaje"=>'Ok',"codigo"=>$interaccion,"query"=>$query);
			}else{
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
				$data    = array();	
			}
				
			break;
		/*****************************************/
		/******** getInteraccion  **********/
		/*****************************************/
		case 'cerrarinteraccion':
			$id = isset($_POST['id']) && trim($_POST['id']) != '' && is_numeric($_POST['id'])?trim($_POST['id']):'Sin especificar';
			$kid_usr = isset($_POST['kid_usr']) && trim($_POST['kid_usr']) != '' && is_numeric($_POST['kid_usr'])?trim($_POST['kid_usr']):'Sin especificar';
			$interaccion = isset($_POST['interaccion']) && trim($_POST['interaccion']) != '' && is_numeric($_POST['interaccion'])?trim($_POST['interaccion']):'Sin especificar';

			if($id == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Necesita indicar el id del registro</div>";
				$data    = array();	
			}else if($interaccion == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se especificó el id de interacción</div>";
				$data    = array();
			}else if($kid_usr == 'Sin especificar'){
				$success = FALSE;
				$error   = "<div class='bg-warning'>Error: No se especificó usuario que cierra la interacción</div>";
				$data    = array();
			}else{
				$id_usr_actual 	= $kid_usr;
				$datosInteraccion = array("fid_usr_fin"=>$id_usr_actual,"fecha_fin_interaccion"=>$now,"estatus_interaccion"=>2);
				if($myAdmin->conexion->update(PREFIJO.'interacciones',$datosInteraccion,$condicion=" WHERE kid_interaccion='$interaccion' AND fid_registro='$id'",'TEXT',FALSE)){
					$success = TRUE;
					$error   = '';
					$data    = array("mensaje"=>'Ok',"codigo"=>$interaccion);
				}else{
					$success = FALSE;
					$error   = "<div class='bg-warning'>Error:".$myAdmin->conexion->error."</div>";
					$data    = array();
				}
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