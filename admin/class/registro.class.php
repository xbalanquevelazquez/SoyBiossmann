<?php
class Registro{
	var $registro 			= array();
	var $myAdmin 			= '';
	var $conexion 			= '';
	var $mailsender			= '';
	var $alerta 			= '';
	var $alertaPlain		= '';
	var $diasTranscurridos 	= '';
	var $infoFaltante 		= 0;
	var $estadosRestringidos = array();
	var $municipiosRestringidos = array();
	var	$nip 				= 0;
	var	$nombre 			= '';
	var	$apellido_paterno 	= '';
	var	$apellido_materno	= '';
	var	$cp 				= '';
	var	$fid_entidad		= '99';
	var	$fid_municipio		= '999';
	var	$fid_localidad		= '0';
	var	$fecha_nacimiento	= '';
	var	$fum				= '';
	var	$correo				= '';
	var $lada 				= '';
	var $telefono			= '';
	var $estatus_registro	= '';
	var $proposito			= 0;
	var $resultado			= 0;
	var $edad 				= '';
	var $puntosNSE			= 0;
	var $code				= '';
	function Registro(){
	}
	function setRegistro($data){
		$this->alerta = '';
		$this->diasTranscurridos = '';
		$this->registro = $data;
		$this->getEstadosRestringidos();
		$this->getMunicipiosRestringidos();

		$this->nip 				= $data['nip'];
		$this->nombre 			= $data['nombre'];
		$this->apellido_paterno = $data['apellido_paterno'];
		$this->apellido_materno	= $data['apellido_materno'];
		$this->cp 				= $data['cp'];
		$this->fid_entidad		= $data['fid_entidad'];
		$this->fid_municipio	= $data['fid_municipio'];
		$this->fid_localidad	= $data['fid_localidad'];
		$this->fid_localidad	= $data['fid_localidad'];
		$this->fecha_nacimiento	= $data['fecha_nacimiento'];
		$this->fum				= $data['fum'];
		$this->correo			= $data['correo'];
		$this->lada				= $data['lada'];
		$this->telefono			= $data['telefono'];
		$this->estatus_registro	= $data['estatus_registro'];
		$this->code				= $data['code'];
	}
	function setAdmin($admin){
		$this->myAdmin = $admin;
	}
	function setConexion($conn){
		$this->conexion = $conn;
	}
	function validarDatos(){
		$this->infoFaltante = 0;
		$this->alerta = '';
		$this->alertaPlain = '';
		$nip 				= $this->registro['nip'];
		$nombre 			= $this->registro['nombre'];
		$apellido_paterno 	= $this->registro['apellido_paterno'];
		$apellido_materno	= $this->registro['apellido_materno'];
		$cp 				= $this->registro['cp'];
		$fid_entidad		= $this->registro['fid_entidad'];
		$fid_municipio		= $this->registro['fid_municipio'];
		$fid_localidad		= $this->registro['fid_localidad'];
		$fid_localidad		= $this->registro['fid_localidad'];
		$fecha_nacimiento	= $this->registro['fecha_nacimiento'];
		$fum				= $this->registro['fum'];
		$correo				= $this->registro['correo'];
		$lada				= $this->registro['lada'];
		$telefono			= $this->registro['telefono'];

		if($nombre == ''){ $this->infoFaltante++; }
		if($apellido_paterno == ''){ $this->infoFaltante++; }
		if($apellido_materno == ''){ $this->infoFaltante++; }
		if($cp == '' || strlen($cp)<=4){ $this->infoFaltante++; }
		if($fid_entidad == '99'){ $this->infoFaltante++; }
		if($fid_municipio == '999'){ $this->infoFaltante++; }
		if($fid_localidad == '0'){ $this->infoFaltante++; }
		if($fecha_nacimiento == ''){ $this->infoFaltante++; }
		if($fum == ''){ $this->infoFaltante++; }
		if($correo == ''){ $this->infoFaltante++; }
		if($lada == ''){ $this->infoFaltante++; }
		if($telefono == ''){ $this->infoFaltante++; }

		//VALIDA QUE NO FALTE INFORMACIÓN
		if($this->infoFaltante > 0){
			$s = $this->infoFaltante>1?'s':'';
			$this->alerta .="<span class=restriccion>Faltan $this->infoFaltante dato$s que completar</span>";
			$this->alertaPlain .= "Faltan $this->infoFaltante dato$s que completar";
		}
		//VALIDAR ESTADO Y MUNICIPIO RESTRINGIDO
		if($fid_entidad != '99'){
			if(in_array($fid_entidad, $this->estadosRestringidos)){
				$this->alerta .= "<span class=restriccion>Entidad Federativa con alerta</span>";
				$this->alertaPlain .= "Entidad Federativa con alerta";
			}
			if($fid_municipio != '999'){
				if(in_array($fid_entidad.'-'.$fid_municipio, $this->municipiosRestringidos)){
					$this->alerta .= "<span class=restriccion>Municipio con alerta</span><br/>";
					$this->alertaPlain .= "Municipio con alerta";
				}
			}
		}
		//VALIDAR DIAS TRANSCURRIDOS
		if($fum != ''){
			$diasTranscurridosCalc = calculaDiasTranscurridos($fum);
			$this->diasTranscurridos .= "Días transcurridos $diasTranscurridosCalc <br/>";
			if($diasTranscurridosCalc > 56){
				$this->alerta .= "<span class=restriccion>Han transcurrido más de 56 días desde la FUM</span>";
			}
		}
		//CALCULA LA EDAD
		if($fecha_nacimiento != ''){
			$aniosCalc = calculaEdad($fecha_nacimiento);
			$this->edad = "$aniosCalc años <br/>";
		}

		
		return $this->alerta;
	}
	function getEstadosRestringidos(){
		$queryEstadosRestringidos = "SELECT * FROM crs_cat_entidades_federativas WHERE estatus_entidad=2";
		$resEstadosRestringidos = $this->conexion->fetch($this->conexion->query($queryEstadosRestringidos));
		$estadosRestringidos = array();
		foreach($resEstadosRestringidos as $estRes){
			$estadosRestringidos[] = $estRes['kid_entidad'];
		}
		$this->estadosRestringidos = $estadosRestringidos;
	}
	function getMunicipiosRestringidos(){
		$queryMunicipiosRestringidos = "SELECT * FROM crs_cat_municipios WHERE estatus_municipio=2";
		$resMunicipiosRestringidos = $this->conexion->fetch($this->conexion->query($queryMunicipiosRestringidos));
		$municipiosRestringidos = array();
		foreach($resMunicipiosRestringidos as $munRes){
			$municipiosRestringidos[] = $munRes['fid_entidad'].'-'.$munRes['cat_key'];
		}
		$this->municipiosRestringidos = $municipiosRestringidos;
	}
	function validarInteraccion(){
		$proposito = $this->proposito;
		$resultado = $this->resultado;
		//VALIDAR PROPOSITO Y RESULTADO
		if($proposito == 0){
			$this->alerta .= "<span class=restriccion>No ha seleccionado un propósito de la interacción</span>";
		}
		if($resultado == 0){
			$this->alerta .= "<span class=restriccion>No ha seleccionado un resultado de la interacción</span>";
		}
	}
		// Las funciones que utilizo con los NSE

	// Obtiene los datos del POST del formulario de arriba, cuantifica los puntos por respuesta y el resultado determina el NSE
	function setNSE($inputDatos = array('r1'=>0,'r2'=>0,'r3'=>0,'r4'=>0,'r5'=>0,'r6'=>0)){
		global $page;
		#self::getCall();
		$resp = 'nel';
		$datos = '';
		$info = 'No se pudo registrar el dato';
		$puntos = 0;
		$nse = 0;
		$q1 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>0),
			array('n'=>3, 'val'=>10),
			array('n'=>4, 'val'=>22),
			array('n'=>5, 'val'=>23),
			array('n'=>6, 'val'=>31),
			array('n'=>7, 'val'=>35),
			array('n'=>8, 'val'=>43),
			array('n'=>9, 'val'=>59),
			array('n'=>10, 'val'=>73),
			array('n'=>11, 'val'=>101)
			);
		$q2 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>24),
			array('n'=>3, 'val'=>47)
			);
		$q3 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>18),
			array('n'=>3, 'val'=>37)
			);
		$q4 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>31)
			);
		$q5 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>15),
			array('n'=>3, 'val'=>31),
			array('n'=>4, 'val'=>46),
			array('n'=>5, 'val'=>61)
			);
		$q6 = array(
			array('n'=>1, 'val'=>0),
			array('n'=>2, 'val'=>6),
			array('n'=>3, 'val'=>12),
			array('n'=>4, 'val'=>17),
			array('n'=>5, 'val'=>23)
			);
		//print_r($inputDatos);
		$puntos += self::getNsePuntos($q1, $inputDatos['pregunta_1']);
		$puntos += self::getNsePuntos($q2, $inputDatos['pregunta_2']);
		$puntos += self::getNsePuntos($q3, $inputDatos['pregunta_3']);
		$puntos += self::getNsePuntos($q4, $inputDatos['pregunta_4']);
		$puntos += self::getNsePuntos($q5, $inputDatos['pregunta_5']);
		$puntos += self::getNsePuntos($q6, $inputDatos['pregunta_6']);
		if ($puntos >= 205){
			// 1 => A/B
			$nse = 1;
		} else if ($puntos >= 166 && $puntos <= 204){
			// 2 => C+
			$nse = 2;
		} else if ($puntos >= 136 && $puntos <= 165){
			// 3 => C
			$nse = 3;
		} else if ($puntos >= 112 && $puntos <= 135){
			// 4 => C-
			$nse = 4;
		} else if ($puntos >= 90 && $puntos <= 111){
			// 5 => D+
			$nse = 5;
		} else if ($puntos >= 48 && $puntos <= 89){
			// 6 => D
			$nse = 6;
		} else if ($puntos <= 47){
			// 7 => 
			$nse = 7;
		}
		$this->puntosNSE = $puntos;
		
		return $nse;
		}
	// Para simplificar el buscar el puntaje correspindente a cada pregunta, mejor hice esta función
	function getNsePuntos($lista, $valor){
		$puntos = 0;
		foreach ($lista as $item) {
			if ($valor == $item['n']){
				$puntos = $item['val'];
				break;
			}
		}
		return $puntos;
		}
	// Estos son los NSE que utilizamos, o el valor numérico (identificador) que le corresponde a cada uno dentro en la base de datos
	// Ej.: los níveles A y B tienen el mismo valor numérico o identificador
	function getNSEvalues(){
		return array(
			array('val'=>1, 'name'=>'A/B'),
			array('val'=>2, 'name'=>'C+'),
			array('val'=>3, 'name'=>'C'),
			array('val'=>4, 'name'=>'C-'),
			array('val'=>5, 'name'=>'D+'),
			array('val'=>6, 'name'=>'D'),
			array('val'=>7, 'name'=>'E')
		);
		}
	// Y esta la uso sólo para obtener rápido el NSE a partir de un valor númerico
	function getNSE($n, $default = ''){
		$nse = $default;
		$lista = self::getNSEvalues();
		foreach ($lista as $item) {
			if ($item['val'] == $n){
				$nse = $item['name'];
			}
		}

		return $nse;
		}
	function validaNSE($datos = array('q1'=>0,'q2'=>0,'q3'=>0,'q4'=>0,'q5'=>0,'q6'=>0)){
		$i = 1;
		$alerta = '';
		$nse = '';
		#print_r($datos);
		foreach ($datos as $dato => $value) {
			if(strpos($dato,'pregunta') === FALSE){

			}else{
				if($value == 0){
					$alerta .= "<span class=restriccion>Falta que complete la pregunta $i del NSE.</span>";
				}
				$i++;
			}
		}
		if($alerta == ''){
			$nse =  self::setNSE($datos);
		}

		return $datos = array('NSE'=>$nse,'alerta'=>$alerta);
		
	}
	function setNSElevels($nameOfMax){
		$value = 0;
		switch($nameOfMax){
			case 'A/B':
				$value = 500;
				break;
			case 'C+':
				$value = 204;
				break;
			case 'C':
				$value = 165;
				break;
			case 'C-':
				$value = 135;
				break;
			case 'D+':
				$value = 111;
				break;
			case 'D':
				$value = 89;
				break;
			case 'E':
				$value = 47;
				break;
			default:
				$value = 0;
				break;
		}
		return $value;
	}
	function aplicaSubsidio(){
		$resultado = '';
		$maxPuntosPSubsidio = $this->setNSElevels($nameOfMax=NSE_MINIMO_SUBSIDIO);
		if($this->puntosNSE <= $maxPuntosPSubsidio){
			$resultado = '<span class="bg-success text-white setpadding5 redondear">Aplicable para subsidio</span>';
		}else{
			$resultado = '<span class="restriccion setpadding5">No aplicable para subsidio</span>';
		}
		return $resultado;
	}
	function cierraExpedienteMedico($nip, $correo){
		$data = array("success"=>false,"error"=>'');
		//Genera código
		$codigo = generarCodigo();
		//Cambia estatus
		$datos = array("estatus_registro"=>3,"code"=>$codigo);
		
		if($this->myAdmin->conexion->update(PREFIJO.'registros',$datos,$condicion=" WHERE nip='{$nip}'",'TEXT',FALSE)){
			//Envía correo
			$enlace = EXTERNAL_APP_URL."VALIDA/{$nip}/{$codigo}/";
			$dataMail = array(
						'sender_mail'=>SENDER_MAIL,
						'sender_name'=>SENDER_MAIL_NAME,
						'destinatarios' => array($correo),
						'isHTML'=>TRUE,
						'titulo'=>'Información importante',
						'mensaje'=>'<p>Por favor complete la información que encontrará en el siguiente enlace:</p><p><a href="'.$enlace.'">'.$enlace.'</a></p>',
						'alt_mensaje'=>''
					);

			if($this->mailsender->send_mail($dataMail)){ 
				$data["success"] 	= true;
				$data["error"]		= '';
			}else{ 
				$data["success"] 	= false;
				$data["error"]		= 'Error de envío:'.$this->mailsender->error;
			}
		}else{
			$data["success"] 	= false;
			$data["error"]		= $this->myAdmin->conexion->error;
		}
		return $data;
	}
	function avisoRechazoComprobante($razones){
		$data = array("success"=>false,"error"=>'');
		$enlace = EXTERNAL_APP_URL."VALIDA/{$this->nip}/{$this->code}/";
		$dataMail = array(
						'sender_mail'=>SENDER_MAIL,
						'sender_name'=>SENDER_MAIL_NAME,
						'destinatarios' => array($this->correo),
						'isHTML'=>TRUE,
						'titulo'=>'Información importante',
						'mensaje'=>'<p>Su comprobante de pago fue rechazado por las siguientes razones:<br>'.$razones.'<br>Por favor vuelva a subir el comprobante con la corrección necesaria, debe hacerlo a través del siguiente enlace:</p><p><a href="'.$enlace.'">'.$enlace.'</a></p>',
						'alt_mensaje'=>''
					);
		#die("$razones | $enlace | {$this->correo}");
			if($this->mailsender->send_mail($dataMail)){ 
				$data["success"] 	= true;
				$data["error"]		= '';
			}else{ 
				$data["success"] 	= false;
				$data["error"]		= 'Error de envío:'.$this->mailsender->error;
			}
		return $data;
	}
	function getEstatus(){
		$estatus = '';
		switch ($this->estatus_registro) {
			case '1':
				$estatus = "Se generó registro, no se han completado los datos básicos.";
				break;
			case '2':
				$estatus = "Se completaron los datos básicos, pendiente de ser llenado el expediente médico.";
				break;
			case '3':
				$estatus = "Se completó el expediente médico, pendientes de ser llenados los datos de envío, consentimiento informado y comprobante de pago.";
				break;
			case '4':
				$estatus = "Completados datos de envío, aceptado consentimiento informado y se subió un comprobante de pago, pendiente de ser validado el comprobante de pago.";
				break;
			case '5':
				$estatus = "Validado el comprobante de pago, pendiente de ser procesado el envío.";
				break;
			case '6':
				$estatus = "En proceso de envío, pendiente de ser entregado.";
				break;
			case '7':
				$estatus = "Concluido, el envío ya fue entregado.";
				break;
			case '8':
				$estatus = "Cancelado.";
				break;
			case '9':
				$estatus = "Rechazado.";
				break;
			
			default:
				
				break;
		}
		return $estatus;
	}
	function cambiarEstatus($id_registro,$estatus_actual,$estatus_nuevo,$desc=''){

		$now = date("Y-m-d H:i:s");
		switch ($estatus_actual) {
			case 0:
				//registro nuevo
				$datos = array();
				$datos['fid_registro'] = $id_registro;
				$datos['fid_estatus'] = $estatus_nuevo;//debe ser 1
				$datos['fecha_inicio_estatus'] = $now;
				if(!$this->conexion->insert(PREFIJO.'estatus',$datos,'TEXT',FALSE)){
					die("Error al generar registro de estatus.");
				}
				break;
			case 1:
				//1 -> registro CC concluido pasa a MED -> 2
			case 2:
				//2 -> registro MED concluido pasa a VALIDA -> 3
			case 3:
				//3 -> registro VALIDA concluido pasa a FIN -> 4
			case 4:
				//4 -> registro FIN concluido pasa a LOG -> 5  ||  RECHAZADO VALIDA -> 3
			case 5:
				//5 -> registro LOG concluido pasa a en envio -> 6  ||  concluido  -> 7
			case 6:
				//6 -> en envio pasa a concluido -> 7  
				$datos = array();
				$datos['fecha_termino_estatus'] = $now;
				if($desc != ''){
					$datos['texto'] = $desc;
				}
				if(!$this->conexion->update(PREFIJO.'estatus',$datos,$condicion=" WHERE fid_registro=$id_registro AND fid_estatus=$estatus_actual ORDER BY kid_estatus DESC LIMIT 1",'TEXT',FALSE)){
					die("Error al actualizar registro de estatus.");
				}
				
				$datos = array();
				$datos['fid_registro'] = $id_registro;
				$datos['fid_estatus'] = $estatus_nuevo;
				$datos['fecha_inicio_estatus'] = $now;
				if($estatus_nuevo == 7 || $estatus_nuevo == 8 || $estatus_nuevo == 9){//CONCLUIDO
					$datos['fecha_termino_estatus'] = $now;
				}
				if(!$this->conexion->insert(PREFIJO.'estatus',$datos,'TEXT',FALSE)){
					die("Error al generar registro de estatus. ID:$id_registro | $estatus_actual -> $estatus_nuevo");
				}
				
				break;

			default:
				
				break;
		}
	}
}
$admRegistro = new Registro();
$admRegistro->setAdmin($myAdmin);
$admRegistro->setConexion($myAdmin->conexion);
#global $mail;//viene del archiv ocnfg.crs.php
$admRegistro->mailsender = $mail;
?>