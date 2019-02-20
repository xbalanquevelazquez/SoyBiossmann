<?php
class Notas extends BD{
	var $data1;
	var $data2;
	var $data3;
	var $data4;
	var $data5;
	var $data6;
	var $sqlNota = '--';
	var $webForm = '--';
	var $idEstudio = 0;
	var $extraInfo = '';
	var $infoNotas = '';
	var $estatusEstudio = 0;
	var $completadoNotas = false;
	var $estatusEstaNota = 0; // 1 = No existe, se tiene que crear, 2= Ya existe, no se ha cerrado, 3= Se ha cerrado y creado
	function Notas(){
		global $data1, $data2, $data3, $data4, $data5, $data6;

		$this->data1 = $data1;
		$this->data2 = $data2;
		$this->data3 = $data3;
		$this->data4 = $data4;
		$this->data5 = $data5;
		$this->data6 = $data6;
		$this->anterior = '';
		$this->actual = 'INTERROGATORIO';
		$this->siguiente = '';

		if ($this->data3 == ''){
			header('location:' . APP_URL . $this->data1);
		} else {
			if ($datosEstudio = BD::ExecuteUno('crs_cuestionario_medico', "fid_registro = $this->data3")){
				$this->idEstudio = $datosEstudio['kid_cuestionario'];
				$this->estatusEstudio = $datosEstudio['aprobado'];
				// edi('Ya hay estudio para ' . $this->data3);
			} else {
				// edi('No hay estudio para ' . $this->data3);
				$data = array(
					'fid_registro'=>$this->data3,
					'fid_usr'=>$_SESSION['site']['kid_usr'],
					'fecha_registro'=>"'" . dateMeFull(). "'",
				);
				$sql = prepareSQL($data, 'create', 'crs_cuestionario_medico');
				$doEstudio = BD::Insert($sql);
				if ($doEstudio){
					$this->idEstudio = BD::ultimoID('crs_cuestionario_medico', 'kid_cuestionario');
					// edi('Ya hay estudio: ' . $this->data3 . ' es el ' . $this->idEstudio);
				}
			}
			if ($datosNota = BD::ExecuteUno('crs_notas_exp', "tabla = '$this->data4'")){
				self::getNavegacion($datosNota);
				self::getNota();
			} else if ($this->data4 == ''){
				self::getMenuNotas();
				$this->webForm = self::getResumenEstudio();
			}
		}
		}
	function getMenuNotas(){
		$menu = '';
		$sql = "SELECT * FROM crs_notas_exp WHERE estatus = 1 ORDER BY orden ASC";
		// $menu = dis($sql);
		if ($todas = BD::Execute($sql)){
			$last = end($todas);
			$last = $last['ID'];
			$totalNotas = count($todas);
			$completadas = 0;
			$listadas = 0;
			foreach ($todas as $una) {
				$activo = $una['tabla'] == $this->data4 ? ' active nota-medica-activa' : '';
				$registrado = false;
				$notaConContenido = '';
				$borde = $una['ID'] != $last ? ' border-right' : '';
				$notaName = $una['nombre'];
				if ($datosEstaNota = self::getDatosNota($una['tabla'])){
					if ($datosEstaNota['fecha_termina'] != '0000-00-00 00:00:00'){
						$notaConContenido = ' nota-medica-cubierta';
						$completadas++;
					}
					$acron = $this->data1 . '/' . $this->data2 . '/' . $this->data3 . '/' . $una['tabla'];
					$menu .= '<li class="nav-item text-center align-middle nota-medica ' . $borde . $activo . $notaConContenido . '"><a class="nav-link" href="' . APP_URL . $acron . '">' . utf8_encode($notaName) . '</a></li>';
					$listadas++;
				} else if ($listadas == 0){
					$acron = $this->data1 . '/' . $this->data2 . '/' . $this->data3 . '/' . $una['tabla'];
					$menu .= '<li class="nav-item text-center align-middle nota-medica ' . $borde . $activo . '"><a class="nav-link" href="' . APP_URL . $acron . '">' . utf8_encode($notaName) . '</a></li>';
					$listadas++;
				} else {
					$menu .= '<li class="nav-item text-center align-middle nota-medica ' . $borde . $activo . $notaConContenido . '">
						<div class="false-nav-link">' . utf8_encode($notaName) . '</div>
					</li>';
					
				}
				
			}
			$this->infoNotas = $totalNotas . ' vs ' . $completadas;
			if ($totalNotas == $completadas){
				$this->completadoNotas = true;
			}
		}

		return $menu;
		}
	function getNota(){
		if ($this->data4 != ''){
			$functionName = $this->data4;
			if (method_exists('Notas', $functionName)){
				$modelo = self::$functionName();
				self::getSqlCreateTableDef($modelo);
				if ($datosEstudioNota = self::getDatosNota()){
					if ($datosEstudioNota['fecha_termina'] != '0000-00-00 00:00:00'){
						$this->estatusEstaNota = 3; // Se ha cerrado y creado
					} else {
						$this->estatusEstaNota = 2; // Ya existe, no se ha cerrado, 
					}
				} else {
					if (self::arrancaNota()){
						$this->estatusEstaNota = 1; // No existe, se tiene que crear
					} else {
					}
				}
				$campos = self::traducirCampos($modelo['dataset'], $datosEstudioNota);
				$this->webForm = self::getWebForm($campos, $datosEstudioNota);
			} else {
				edi('No existe la nota ' . $functionName);
			}
		}
		}
	function getPaciente(){
		if ($datosRegistro = BD::ExecuteUno('crs_registros', "nip = $this->data3")){
			$fecha = $datosRegistro['fecha_creacion'] != '0000-00-00 00:00:00' ? separaFecha($datosRegistro['fecha_creacion']) : separaFecha($datosRegistro['fecha_conclusion']);
			
			$datosRegistro['fecha'] = $fecha['fecha'] . ' ' . $fecha['hora'];
			return makeTemplate('_datos_paciente.html', $datosRegistro, 'expediente');
		} else {
			return di('No se encuentra un registro para ese NIP ' . $this->data3, 'p-3 card text-white bg-warning mb-3 font-weight-bold');
		}
		}
	function getNavegacion($datosNota){
		$this->actual = $datosNota['nombre'];
		$oPrevio = $datosNota['orden'] - 1;
		$oSiguiente = $datosNota['orden'] + 1;
		$this->siguiente = '&nbsp;';
		if ($oPrevio == 0){
			$this->anterior = '&nbsp;';
		} else {
			$datosAnterior = BD::ExecuteUno('crs_notas_exp' , "orden = $oPrevio");
			$link = APP_URL . $this->data1 . '/' . $this->data2 . '/' . $this->data3 . '/' . $datosAnterior['tabla'];
			$this->anterior = mkA($link, 'ANTERIOR', 'text-white mt-1 la-nota-nterior');
		}
		if ($datosSiguiente = BD::ExecuteUno('crs_notas_exp', "orden = $oSiguiente AND estatus = 1")){
			$link = APP_URL . $this->data1 . '/' . $this->data2 . '/' . $this->data3 . '/' . $datosSiguiente['tabla'];
			$this->siguiente = mkA($link, 'SIGUIENTE', 'text-white mt-1 la-nota-siguiente');
		}
		}
	function getResumenEstudio(){
		$datosEstudio = BD::ExecuteUno("crs_cuestionario_medico", "kid_cuestionario = $this->idEstudio");
		$tempalte = '_forma_final_mensaje';
		switch ($datosEstudio['aprobado']) {
			case 0: // En proceso
				// edi("idEstudio = $this->idEstudio");
				$pasa = true;
				$valoresBase = array('embarazada', 'pruebas', 'dias', 'retiroDiu', 'regular', 'indicacionPM');
				$valoresFactor = array('factorEmbarazo', 'factorDiu', 'factorSangrado', 'factorDolor', 'factorTratamiento', 'factorIndicacion');
				if ($notaInclusion = BD::ExecuteUno('crs_nota_criteriosInclusion', "idEstudio = $this->idEstudio")){
					foreach ($notaInclusion as $key => $value) {
						if (!is_numeric($key)){
							if (in_array($key, $valoresBase)){
								if ($value == 0) $pasa = false;
							} else if (in_array($key, $valoresFactor)){
								if ($value == 0) $pasa = false;
							}
						}
					}
				} else {
					$pasa = false;
				}
				$resultadoCriterio = $pasa ? 'PUEDE CONTINUAR CON EL PEDIDO' : 'NO ES CANDIDATA A CONTINUAR';
				$resultadoBg = $pasa ? 'success' : 'danger';
				$puedoCancelar = $pasa ? '' : ' oculto';
				$aprobado = $pasa ? 1 : 2;
				if ($this->completadoNotas){
					$data = array(
						'resultadoCriterio'=>$resultadoCriterio,
						'resultadoBg'=>$resultadoBg,
						'puedoCancelar'=>$puedoCancelar,
						'destino'=>APP_URL . 'webservices/notas.php',
						'idEstudio'=>$this->idEstudio,
						'aprobado'=>$aprobado,
					);
					$tempalte = '_forma_final';
				} else {
					$data = array(
						'mensaje'=>'Aun faltan notas por registrar, revisa el Menú para ver cuáles son las que faltan',
						'extraInfo'=>'',
					);
				}
				break;
			case 1: // Aprobado
				$data = array(
					'mensaje'=>'Este Expediente ya ha sido finalizado y APROBADO',
					'extraInfo'=>'',
				);
				break;
			case 2: // No aprobado'
				$data = array(
					'mensaje'=>'Este Expediente ya ha sido finalizado y RECHAZADO por los Criterios de inclusión',
					'extraInfo'=>'',
				);
				break;
			case 3: // Rechazado'
				$data = array(
					'mensaje'=>'Este Expediente ya ha sido finalizado y RECHAZADO por el médico:',
					'extraInfo'=>$datosEstudio['razon_no_candidata'],
				);
				break;
		}
		return makeTemplate($tempalte . '.html', $data, 'expediente');
		}
	// ---------------------------------------- Modelos ----------------------------------------
		// field => Nombre del campo en la DB: hemoglobina
		// label => Leyenda que acompaña al campo en la web form, generalmente antes del campo: Hemoglobina
		// type => Tipo de campo FLOAT
		// tmpltDiv => clase css del div contendor: un-campo
		// classInpt => clase del input del campo: text-center
		// postStr => texto que va posterior al campo: g/dl
		// need => Si se trata de un campo que debe aparecer sçolo cuando el campo indicado tiene información: nombreDeCampoAneterior
		function antecedentesHeredoFamiliares(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;

			$dataset = array();
			$dataset[] = array('field'=>'metabolicos', 		'label'=>'Metabólicos',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espMetabolicos', 	'label'=>'Detalle Metabólicos',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'metabolicos');
			$dataset[] = array('field'=>'infecciosos', 		'label'=>'Infecciosos',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espInfecciosos', 	'label'=>'Detalle Infecciosos',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'infecciosos');
			$dataset[] = array('field'=>'hemorragicos', 	'label'=>'Hemorrágicos',		'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espHemorragicos',	'label'=>'Detalle Hemorrágicos','type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'hemorragicos');
			$dataset[] = array('field'=>'oncologicos', 		'label'=>'Oncológicos',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espOncologicos', 	'label'=>'Detalle Oncológicos',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'oncologicos');
			$dataset[] = array('field'=>'tromboticos', 		'label'=>'Trombóticos',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espTromboticos', 	'label'=>'Detalle Trombóticos',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'tromboticos');

			$model['dataset'] = $dataset;
			return $model;
			}
		function antecedentesPersonalesNoPatologicos(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array(
				array('val'=>'1', 'name'=>'Diario'), 
				array('val'=>'2', 'name'=>'Semanal'), 
				array('val'=>'3', 'name'=>'Mensual')
			);
			$opciones2 = array(
				array('val'=>'1', 'name'=>'Más de 24 horas'), 
				array('val'=>'2', 'name'=>'Menos de 24 horas')
			);
			$opciones3 = array(
				array('val'=>0,'name'=>'Desconoce'),
				array('val'=>1,'name'=>'A'),
				array('val'=>2,'name'=>'B'),
				array('val'=>3,'name'=>'AB'),
				array('val'=>4,'name'=>'O'),
			);
			$opciones4 = array(
				array('val'=>0,'name'=>'Desconoce'),
				array('val'=>1,'name'=>'+'),
				array('val'=>2,'name'=>'-'),
			);

			$dataset = array();
			$dataset[] = array('field'=>'toxicomanias', 		'label'=>'Toxicomanías',		'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espToxicomanias', 		'label'=>'Detalle Toxicomanías','type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'toxicomanias');
			$dataset[] = array('field'=>'toxicoFrecuencia', 	'label'=>'Frecuencia',			'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row', 'postStr'=>'veces', 'need'=>'toxicomanias');
			$dataset[] = array('field'=>'toxicoTipoFrecuencia',	'label'=>'Tipo de frecuencia',	'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones1, 'opInicial'=>true, 'need'=>'toxicomanias');
			$dataset[] = array('field'=>'toxicoEdad', 			'label'=>'Edad de inicio',		'type'=>'NUMBER',	'tmpltDiv'=>'_form_row', 'postStr' => 'años', 'need'=>'toxicomanias');
			$dataset[] = array('field'=>'toxicoUltimoConsumo', 	'label'=>'Último consumo',		'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones2, 'opInicial'=>false, 'need'=>'toxicomanias');
			$dataset[] = array('field'=>'grupoSangineo', 		'label'=>'Grupo sanguíneo',		'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones3, 'opInicial'=>false);
			$dataset[] = array('field'=>'rh', 					'label'=>'RH',					'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones4, 'opInicial'=>false);

			$model['dataset'] = $dataset;
			return $model;
			}
		function antecedentesPersonalesPatologicos(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;

			$dataset = array();
			$dataset[] = array('field'=>'alergicos', 		'label'=>'Alérgicos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espAlergicos', 	'label'=>'Detalle Alérgicos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'alergicos');
			$dataset[] = array('field'=>'traumaticos', 		'label'=>'Traumáticos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espTraumaticos', 	'label'=>'Detalle Traumáticos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'traumaticos');
			$dataset[] = array('field'=>'transfusionales', 	'label'=>'Transfusionales',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espTransfusionales','label'=>'Detalle Transfusionales','type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'transfusionales');
			$dataset[] = array('field'=>'quirurgicos', 		'label'=>'Quirúrgicos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espQuirurgicos', 	'label'=>'Detalle Quirúrgicos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'quirurgicos');
			$dataset[] = array('field'=>'diabetes', 		'label'=>'Diabetes',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espDiabetes', 		'label'=>'Detalle Diabetes',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'diabetes');
			$dataset[] = array('field'=>'hipertension', 	'label'=>'Hipertensión',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espHipertension', 	'label'=>'Detalle Hipertensión',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'hipertension');
			$dataset[] = array('field'=>'infecciosos', 		'label'=>'Infecciosos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espInfecciosos', 	'label'=>'Detalle Infecciosos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'infecciosos');
			$dataset[] = array('field'=>'hemorragicos', 	'label'=>'Hemorrágicos',			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espHemorragicos', 	'label'=>'Detalle Hemorragicos',	'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'hemorragicos');
			$dataset[] = array('field'=>'oncologicos', 		'label'=>'Oncológicos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espOncologicos', 	'label'=>'Detalle Oncológicos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'oncologicos');
			$dataset[] = array('field'=>'tromboticos', 		'label'=>'Trombóticos',				'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'espTromboticos', 	'label'=>'Detalle Trombóticos',		'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'tromboticos');

			$model['dataset'] = $dataset;
			return $model;
			}
		function antecedentesGinecoObstetricos(){
			// Gestas -> Eespecial
			// Partos -> Eespecial
			// Abortos -> Eespecial
			// Embarazo ectópicos -> Eespecial
			// Embarazo molar -> Especial
			// Cesáreas -> Especial
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array(
				array('val'=>'1', 'name'=>'Regular'), 
				array('val'=>'2', 'name'=>'Irregular'),
			);
			$opciones2 = array(
				array('val'=>'1', 'name'=>'Leve = Sin Tx'),
				array('val'=>'2', 'name'=>'Moderada = Con AINE'), 
				array('val'=>'3', 'name'=>'Severa = incapacitante'), 
			);
			$opciones3 = array(
				array('val'=>'1', 'name'=>'<= 150 ml'), 
				array('val'=>'2', 'name'=>'> 150 ml'),
			);
			$opciones4 = array(
				array('val'=>'0', 'name'=>'No'),
				array('val'=>'1', 'name'=>'Anillo vaginal'),
				array('val'=>'2', 'name'=>'Condón femenino'),
				array('val'=>'3', 'name'=>'Condón masculino'),
				array('val'=>'4', 'name'=>'DIU/T de cobre'),
				array('val'=>'5', 'name'=>'DIU promoción'),
				array('val'=>'6', 'name'=>'Hormonal oral'),
				array('val'=>'7', 'name'=>'Inyectable mensual'),
				array('val'=>'8', 'name'=>'Inyectable bimestral'),
				array('val'=>'9', 'name'=>'Inyectable trimestral'),
				array('val'=>'10', 'name'=>'Salpingoclasia'),
				array('val'=>'11', 'name'=>'Métodos tradicionales/naturales'),
				array('val'=>'12', 'name'=>'Pastilla de anticoncepción de emergencia'),
				array('val'=>'13', 'name'=>'Parche transdérmico'),
				array('val'=>'14', 'name'=>'No sé/No recuerdo'),
				array('val'=>'16', 'name'=>'SIU Mirena'),
				array('val'=>'17', 'name'=>'Implante subdérmico'),
				array('val'=>'18', 'name'=>'Implante subdérmico promoción'),
				array('val'=>'15', 'name'=>'Otro'),
			);

			$dataset = array();
			$dataset[] = array('type'=>'STARTLINE');
			$dataset[] = array('field'=>'menarca', 		'label'=>'Menarca',				'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'ritmo',		'label'=>'Ritmo',				'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones1, 'opInicial'=>false);
			$dataset[] = array('field'=>'dismenorrea',	'label'=>'Dismenorrea',			'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones2, 'opInicial'=>false);
			$dataset[] = array('type'=>'ENDLINE');
			
			$dataset[] = array('type'=>'STARTLINE');
			$dataset[] = array('field'=>'gestas', 		'label'=>'Gestas',				'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'partos', 		'label'=>'Partos',				'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'abortos', 		'label'=>'Abortos',				'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('type'=>'ENDLINE');
			
			$dataset[] = array('type'=>'STARTLINE');
			$dataset[] = array('field'=>'ectopicos', 	'label'=>'Embarazo ectópicos',	'type'=>'NUMBER',	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'molar', 		'label'=>'Embarazo molar',		'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('field'=>'cesareas', 	'label'=>'Cesáreas',			'type'=>'NUMBER', 	'tmpltDiv'=>'_form_row');
			$dataset[] = array('type'=>'ENDLINE');

			$dataset[] = array('field'=>'sangrado',		'label'=>'Sangrado menstrual estimado',				'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones3, 'opInicial'=>false);
			$dataset[] = array('field'=>'metodoPF',		'label'=>'Método anticonceptivo en el último año',	'type'=>'LIST', 	'tmpltDiv'=>'_form_row', 'opciones'=>$opciones4, 'opInicial'=>false);
			$dataset[] = array('field'=>'otroPF', 		'label'=>'Otro método anticonceptivo',				'type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 'need'=>'metodoPF', 'needValue'=>15);

			$model['dataset'] = $dataset;
			return $model;
			}
		function padecimientoActual(){
			// Síntoma
			// Inicio
			// Evolución
			// Estado Actual
			// 1ª vez
			// Estado actual Subsecuente
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array(
				array('val'=>'1', 'name'=>'†'), 
				array('val'=>'2', 'name'=>'††'),
				array('val'=>'3', 'name'=>'†††'),
			);

			$dataset = array();
			$dataset[] = array('type'=>'HEADPADECE', 'tmpltDiv'=>'_form_row_esp0');
			$dataset[] = array('field'=>'fecha', 				'label'=>'Fecha',					'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp1');
			$dataset[] = array('field'=>'amenorreaDias', 		'label'=>'Amenorrea en días',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp2');
			$dataset[] = array('field'=>'aumentoSenos', 		'label'=>'Aumento de senos',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'dolorMamario', 		'label'=>'Dolor mamario',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'nausea', 				'label'=>'Náusea',					'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'vomitoMatinal', 		'label'=>'Vómito matinal',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'estrenimiento', 		'label'=>'Estreñimiento',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'distencionAbdominal', 	'label'=>'Distención abdominal',	'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'fatiga', 				'label'=>'Fatiga y sueño excesivo',	'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'poliuria', 			'label'=>'Poliuria',				'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'alteracionesGusto', 	'label'=>'Alteraciones del gusto, olfato y del apetito', 'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'flatulencia', 			'label'=>'Flatulencia',				'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'cambiosHumor', 		'label'=>'Cambios de humor',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'cefalea', 				'label'=>'Cefalea',					'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'lumbalgia', 			'label'=>'Lumbalgia',				'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'acne', 				'label'=>'Acné',					'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'descargaVaginal', 		'label'=>'Descarga vaginal',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp3', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'otrosSintomas', 		'label'=>'Otros síntomas',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp4');

			$model['dataset'] = $dataset;
			return $model;
			}
		function auxiliaresDiagnostico(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array(
				array('val'=>'1', 'name'=>'(+) Positivo'), 
				array('val'=>'2', 'name'=>'(-) Negativo'),
			);
			$dataset = array();
			$dataset[] = array('type'=>'HEADAUXILIAR', 		'tmpltDiv'=>'_form_row_esp0b');
			$dataset[] = array('field'=>'pruebaEmbarazo', 	'label'=>'Prueba embarazo',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp5', 'postStr'=>'+/-', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'fechaPrueba', 		'label'=>'Fecha prueba embarazo',	'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp6');
			
			$dataset[] = array('field'=>'fraccionBeta', 	'label'=>'Fracción beta',			'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp7', 'postStr'=>'mUI/ml');
			$dataset[] = array('field'=>'fechaFraccion', 	'label'=>'Fecha Fracción beta',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp6');
			
			$dataset[] = array('field'=>'ultrasonido', 		'label'=>'Ultrasonido',				'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp7', 'postStr'=>'semanas');
			$dataset[] = array('field'=>'fechaUltra', 		'label'=>'Fecha Ultrasonido',		'type'=>'SPECIAL', 'tmpltDiv'=>'_form_row_esp6');

			$model['dataset'] = $dataset;
			return $model;
			}
		function criteriosInclusion(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;

			$dataset = array();
			$dataset[] = array('field'=>'embarazada', 		'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Se encuentra embarazada, confirmado por una prueba inmunológica de embarazo el día de la inclusion en el estudio');
			$dataset[] = array('field'=>'pruebas', 			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Durante este embarazo ha realizado menos de 4 pruebas de embarazo');
			$dataset[] = array('field'=>'dias', 			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Está segura de tener ≤56 dias de embarazo de la FUR');
			$dataset[] = array('field'=>'retiroDiu', 		'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Se retiró un DIU/SIU o implante en las 8 semanas previas y no se sabia embarazada antes del retiro o en los 2 meses previos antes de su FUR');
			$dataset[] = array('field'=>'regular', 			'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Era regular mensualmente sin sangrado intermenstrual y no utilizaba anticoncepción hormonal durante ese tiempo');
			$dataset[] = array('field'=>'indicacionPM', 	'type'=>'BOOLEAN', 	'tmpltDiv'=>'_form_full_row', 'opInicial'=>true, 'ob'=>true, 'label'=>'Tiene indicación para aborto con mifepristona y misoprostol acorde al protocolo de MSI (excepto por examen pélvico y ultrasonido)');

			$dataset[] = array('type'=>'LABEL', 'label'=>'Están DESCARTADOS TODOS ESTOS factores de riesgo para embarazo ectópico:');
			$dataset[] = array('field'=>'factorEmbarazo', 	'type'=>'CHECK', 	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'Embarazo ectópico previo');
			$dataset[] = array('field'=>'factorDiu', 		'type'=>'CHECK', 	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'DIU in situ al diagnóstico del embarazo');
			$dataset[] = array('field'=>'factorSangrado', 	'type'=>'CHECK', 	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'Sangrado vaginal o manchado desde la FUR');
			$dataset[] = array('field'=>'factorDolor', 		'type'=>'CHECK', 	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'Dolor pélvico unilateral');
			$dataset[] = array('field'=>'factorTratamiento','type'=>'CHECK',	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'Tratamiento previo de enfermedad inflamatoria pélvica');
			$dataset[] = array('field'=>'factorIndicacion', 'type'=>'CHECK', 	'tmpltDiv'=>'_form_full_row_re', 'classDiv'=>'pl-3', 'label'=>'No tiene indicación de exámen pélvico o ultrasonido relacionado al aborto');


			$model['dataset'] = $dataset;
			return $model;
			}
		function diagnostico(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array();
			$opciones2 = array();
			$sql1 = "SELECT * FROM crs_cie10 WHERE padre = 0 AND estatus = 1 ORDER BY orden ASC";
			$sql2 = "SELECT * FROM crs_cie10 WHERE padre > 0 AND estatus = 1 ORDER BY orden ASC";
			if ($ciePadres = BD::Execute($sql1)){
				foreach ($ciePadres as $padre) {
					$opciones1[] = array('val'=>$padre['ID'], 'name'=>$padre['clave'] . ' - ' . utf8_encode($padre['detalle']));
				}
			}
			$opciones1[] = array('val'=>999, 'name'=>'Otro');
			if ($cieHijs = BD::Execute($sql2)){
				foreach ($cieHijs as $hijo) {
					$opciones2[] = array('val'=>$hijo['ID'], 'name'=>$hijo['clave'] . ' - ' . utf8_encode($hijo['detalle']), 'extradata'=>' data-p="' . $hijo['padre'] . '"');
				}
			}
			$dataset = array();
			$dataset[] = array('field'=>'diagnostico', 		'label'=>'Diagnóstico presuncional','type'=>'TEXTAREA', 'tmpltDiv'=>'_form_full_row', 	'ob'=>true);
			$dataset[] = array('field'=>'superCie10',		'label'=>'CIE10', 					'type'=>'LIST', 	'tmpltDiv'=>'_form_full_row', 	'ob'=>true,	'opciones'=>$opciones1, 'opInicial'=>true);
			$dataset[] = array('field'=>'cie10',			'label'=>'CIE10 Subcategoría',		'type'=>'LIST', 	'tmpltDiv'=>'_form_full_row', 	'ob'=>true,	'opciones'=>$opciones2, 'opInicial'=>true);
			$dataset[] = array('field'=>'espCie10',			'label'=>'Otro CIE10', 				'type'=>'TEXTAREA',	'tmpltDiv'=>'_form_full_row', 	'need'=>'superCie10', 'needValue'=>'999');
			$model['dataset'] = $dataset;
			$cie10HijoTemporal = mkSelect(0, 'cie10temp', $opciones2, 'cite10_hijo_temp oculto', 'cie10temp');
			$this->extraInfo = $cie10HijoTemporal;
			return $model;
			}
		function dictamenMedico(){
			$model = array();
			$model['name'] = __FUNCTION__;
			$model['table'] = 'crs_nota_' . __FUNCTION__;
			$model['parent'] = 'idEstudio';
			$model['cond'] = "";
			$model['type'] = 1;
			$opciones1 = array(array('val'=>'1', 'name'=>'20 a 34'), 		array('val'=>'2', 'name'=>'< 20 o > 34'));
			$opciones2 = array(array('val'=>'1', 'name'=>'--'), 			array('val'=>'2', 'name'=>'< 24 o > 30'));
			$opciones3 = array(array('val'=>'1', 'name'=>'Si'), 			array('val'=>'2', 'name'=>'No'));
			
			$opciones4 = array(array('val'=>'1', 'name'=>'1 a 3'), 			array('val'=>'2', 'name'=>'4 o más'));
			$opciones5 = array(array('val'=>'1', 'name'=>'Normal'), 		array('val'=>'2', 'name'=>'Complicado '));
			$opciones6 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones7 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones8 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'1 o más'));
			$opciones9 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones10 = array(array('val'=>'1', 'name'=>'2500 a 3999'), 	array('val'=>'2', 'name'=>'< 2500 > 3999'));
			$opciones11 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones12 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones13 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones14 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));

			$opciones15 = array(array('val'=>'1', 'name'=>'10 y más'), 		array('val'=>'2', 'name'=>'< 10'));
			$opciones16 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones17 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones18 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones19 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones20 = array(array('val'=>'1', 'name'=>'No'), 			array('val'=>'2', 'name'=>'Si'));
			$opciones21 = array(array('val'=>'1', 'name'=>'Dentro de rango'),array('val'=>'2', 'name'=>'Fuera de rango'));
			$opciones22 = array(array('val'=>'1', 'name'=>'No '), 			array('val'=>'2', 'name'=>'Refractaria o recurrente'));
			$opciones23 = array(array('val'=>'1', 'name'=>'Presente'), 		array('val'=>'2', 'name'=>'Ausente'));
			$opciones24 = array(array('val'=>'1', 'name'=>'--'), 			array('val'=>'2', 'name'=>'Precario'));
			$opciones25 = array(array('val'=>'1', 'name'=>'--'), 			array('val'=>'2', 'name'=>'Si '));

			$dataset = array();
			$dataset[] = array('type'=>'HEADICTAMEN1', 		'tmpltDiv'=>'_form_row_dictamen1');
			$dataset[] = array('field'=>'checkEdad', 		'label'=>'Edad (años)',						'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones1);
			$dataset[] = array('field'=>'checkIMC', 		'label'=>'IMC al inicio del embarazo',		'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones2);
			$dataset[] = array('field'=>'checkEmbarazo', 	'label'=>'Embarazo deseado',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones3);

			$dataset[] = array('type'=>'HEADICTAMEN2', 		'tmpltDiv'=>'_form_row_dictamen2');
			$dataset[] = array('field'=>'checkParidad', 	'label'=>'Paridad',							'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones4);
			$dataset[] = array('field'=>'checkParto', 		'label'=>'Parto anterior',					'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones5);
			$dataset[] = array('field'=>'checkHemorragia', 	'label'=>'Hemorragia obstétrica',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones6);
			$dataset[] = array('field'=>'checkAborto', 		'label'=>'Aborto previo complicado',		'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones7);
			$dataset[] = array('field'=>'checkCesaarea', 	'label'=>'Cesárea',							'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones8);
			$dataset[] = array('field'=>'checkPreeclampsia','label'=>'Preeclampsia-Eclampsia',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones9);
			$dataset[] = array('field'=>'checkHijosPeso', 	'label'=>'Hijos con peso al nacimiento',	'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones10);
			$dataset[] = array('field'=>'checkMuertes', 	'label'=>'Muertes Perinatales',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones11);
			$dataset[] = array('field'=>'checkHijosMalforma','label'=>'Hijos con malformaciones',		'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones12);
			$dataset[] = array('field'=>'checkCirugia', 	'label'=>'Cirugía genitourinaria ',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones13);
			$dataset[] = array('field'=>'checkTrombosis', 	'label'=>'Trombosis venosa',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones14);

			$dataset[] = array('type'=>'HEADICTAMEN3', 		'tmpltDiv'=>'_form_row_dictamen3');
			$dataset[] = array('field'=>'checkHemoglobina', 'label'=>'Hemoglobina en g.',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones15);
			$dataset[] = array('field'=>'checkToxicomanias','label'=>'Toxicomanías',					'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones16);
			$dataset[] = array('field'=>'checkDiabetes', 	'label'=>'Diabetes Mellitus',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones17);
			$dataset[] = array('field'=>'checkHipertension','label'=>'Hipertensión crónica',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones18);
			$dataset[] = array('field'=>'checkVIH', 		'label'=>'VIH +',							'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones19);
			$dataset[] = array('field'=>'checkVDRL', 		'label'=>'VDRL +',							'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones20);
			$dataset[] = array('field'=>'checkFondo', 		'label'=>'Fondo uterino (cms/mes)',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones21);
			$dataset[] = array('field'=>'checkInfeccion', 	'label'=>'Infección urinaria',				'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones22);
			$dataset[] = array('field'=>'checkRedes', 		'label'=>'Redes de apoyo',					'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones23);
			$dataset[] = array('field'=>'checkEstado', 		'label'=>'Estado socioeconómico ',			'type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones24);
			$dataset[] = array('field'=>'checkOtras', 		'label'=>'Otras condiciones de alto riesgo','type'=>'SPECIALCHECK', 'tmpltDiv'=>'_form_row_esp8', 'opciones'=>$opciones25);

			$dataset[] = array('field'=>'listHipertension', 	'label'=>'Hipertensión arterial sistémica', 						'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listProteinuria', 		'label'=>'Proteinuria > de 300 mg/l', 								'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listTira', 			'label'=>'Tira reactiva igual o mayor 30 mg/dl (1 +)', 				'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listCefalea', 			'label'=>'Cefalea intensa o alteraciones visuales o cerebrales persistentes', 'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listEpigastralgia', 	'label'=>'Epigastralgia', 											'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listGanancia', 		'label'=>'Ganancia excesiva de peso a lo esperado a edad gestacional', 	'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listRuptura', 			'label'=>'Ruptura prematura de membranas', 							'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listTemperatura', 		'label'=>'Temperatura < de 36 ó > 38', 								'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listSomnolencia', 		'label'=>'Somnolencia', 											'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listFR', 				'label'=>'FR > 20', 												'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listFC', 				'label'=>'FC igual o mayor a 90', 									'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listIndice', 			'label'=>'Índice de choque (FC/sistólica) > 0.8', 					'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listSangrado', 		'label'=>'Sangrado transvaginal', 									'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listTrabajSDG', 		'label'=>'Trabajo de parto en embarazo de 36 SDG o menos', 			'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listTrabajoCesarea', 	'label'=>'Trabajo de parto en una mujer con cesárea previa', 		'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listGlucemia', 		'label'=>'Glucemia > a 105 mg/dl', 									'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listHb', 				'label'=>'Hb < de 8 gr/100 ml', 									'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listCreatinina', 		'label'=>'Creatinina sérica > 1.2', 								'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listRh', 				'label'=>'Rh - Coombs +', 											'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listCardiopatia', 		'label'=>'Cardiopatía', 											'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listNefropatia', 		'label'=>'Nefropatía', 												'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listHepatopatia', 		'label'=>'Hepatopatía', 											'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listTrombosis', 		'label'=>'Trombosis venosa profunda', 								'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listVIH', 				'label'=>'VIH (linfopenia < 1500)', 								'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listDepresion', 		'label'=>'Depresión', 												'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('field'=>'listViolencia', 		'label'=>'Violencia', 												'type'=>'CHECK', 'tmpltDiv'=>'_form_row_esp9');
			$dataset[] = array('type'=>'FOOTDICTAMEN', 			'tmpltDiv'=>'_form_row_foot');

			$model['dataset'] = $dataset;
			return $model;
			}
	// ----------------------------------------- Tools -----------------------------------------
		function getSqlCreateTableDef($modelo){
			$campos = $modelo['dataset'];
			$tabla = $modelo['table'];
			$keys = '';
			$str = '';
			$str .= di("DROP TABLE IF EXISTS `$tabla`;");
			$str .= di("CREATE TABLE `" . $tabla . "` (");
			$str .= di("`ID` INT( 25 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,");
			$str .= di("`idEstudio` int(25) NOT NULL DEFAULT '0',");
			$str .= di("`fecha_registro` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',");
			$str .= di("`fecha_termina` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,");
			$str .= di("`fid_usr` int(25) NOT NULL DEFAULT '0',");
			$keys .= di("KEY `usr` (`fid_usr`), ");
			foreach ($campos as $campo){
				$str .= self::getTyped($campo);
			}
			$str .= $keys;
			$str .= di("KEY `estudio` (`idEstudio`)");
			$str .= di(") ENGINE = InnoDB DEFAULT CHARSET=utf8;");

			$this->sqlNota = $str;
			}
		function traducirCampos($campos, $datosEstudioNota, $meter = null){
			$traducidos = array();
			foreach ($campos as $campo) {
				$siPasa = true;
				$siMedioPasa = false;
				switch($campo['type']){
					case 'STARTLINE': $siPasa = false; break;
					case 'ENDLINE': $siPasa = false; break;
					case 'HEADPADECE': $siPasa = false; break;
					case 'HEADAUXILIAR': $siPasa = false; break;
					case 'HEADICTAMEN1': case 'HEADICTAMEN2': case 'HEADICTAMEN3': case 'FOOTDICTAMEN':
						$siPasa = false;
						$siMedioPasa = true;
						break;
					case 'LABEL':
						$campo['field'] = '';
						break;
				}
				if ($siPasa){
					$extraInpt = isset($campo['extraInpt']) ? ' ' . $campo['extraInpt'] : '';
					$value = $campo['type'] != 'LABEL' && $campo['type'] != 'MULTI' && $campo['type'] != 'LABEL-C' ? isset($datosEstudioNota[$campo['field']]) ? $datosEstudioNota[$campo['field']] : '' : '';
					switch ($campo['type']) {
						case 'value':
							# code...
							break;
					}
					if ($meter != null){
						if (isset($meter[$campo['field']])){
							$value = $meter[$campo['field']];
						}
					}
					$traducido = array(
						'type'=>strtolower($campo['type']),
						'tmpltDiv'=>isset($campo['tmpltDiv']) ? $campo['tmpltDiv'] : '_form_row',
						'nameStr'=>$campo['label'],
						'name'=>$campo['field'],
						'id'=>isset($campo['field']) ? $campo['field'] : '',
						'value'=>$value,
						'classInpt'=>isset($campo['classInpt']) ? $campo['classInpt'] : '',
						'classDiv'=>isset($campo['classDiv']) ? $campo['classDiv'] : '',
						'postStr'=>isset($campo['postStr']) ? $campo['postStr'] : '',
						'values'=>isset($campo['values']) ? $campo['values'] : '',
						'ob'=>isset($campo['ob']) ? $campo['ob'] : '',
						'need'=>isset($campo['need']) ? $campo['need'] : '',
						'needValue'=>isset($campo['needValue']) ? $campo['needValue'] : '',
						'default'=>isset($campo['default']) ? $campo['default'] : '',
						'fnName'=>isset($campo['fnName']) ? $campo['fnName'] : '',
						'opciones'=>isset($campo['opciones']) ? $campo['opciones'] : '',
						'opInicial'=>isset($campo['opInicial']) ? $campo['opInicial'] : '',
						);
					if ($campo['type'] == 'MULTI'){
						$traducido['datosEstudioNota'] = $datosEstudioNota;
					}
					$traducidos[] = $traducido;
				} else if ($campo['type'] == 'SPECIAL'){
					$traducidos[] = array('type'=>strtolower($campo['type']), 'tmpltDiv'=>$tmpltDiv);
				} else if ($siMedioPasa){
					$traducidos[] = array('type'=>strtolower($campo['type']), 'tmpltDiv'=>$campo['tmpltDiv']);
				} else {
					$traducidos[] = array('type'=>strtolower($campo['type']), 'tmpltDiv'=>'_form_tag');
				}
			}

			return $traducidos;
			}
		function getWebForm($datos, $datosEstudioNota){
			$webFormItems = '';
			foreach ($datos as $data) {
				// Los datos, valores y opciones
					$tmpltDiv = 	isset($data['tmpltDiv']) 	?	$data['tmpltDiv'] : '';
					$divId = 		isset($data['idDiv']) 		?	' id="' . $data['idDiv'] . '"' : '';
					$classDiv = 	isset($data['classDiv']) 	?	$data['classDiv'] : '';
					$classInpt = 	isset($data['classInpt']) 	?	$data['classInpt'] : '';
					$name = 		isset($data['name']) 		?	$data['name'] : '';
					$id = 			isset($data['id']) 			?	$data['id'] : '';
					$value = 		isset($data['value']) 		?	$data['value'] : '';
					$values = 		isset($data['values']) 		?	$data['values'] : '';
					$nameStr = 		isset($data['nameStr']) 	?	$data['nameStr'] . ' ' : '';
					$postStr = 		isset($data['postStr']) 	?	$data['postStr'] : '';
					$extraInpt = 	isset($data['extraInpt']) 	?	' ' . $data['extraInpt'] : '';
					$default =	 	isset($data['default']) 	?	$data['default'] : '';
					$fnName =	 	isset($data['fnName']) 		?	$data['fnName'] : '';
					$opciones =	 	isset($data['opciones']) 	?	$data['opciones'] : '';
					$opInicial =	isset($data['opInicial']) 	?	$data['opInicial'] : '';
					$ob =			isset($data['ob']) 			?	$data['ob'] : '';
					if ($value == '') $value = $default;
					$need = 		isset($data['need']) && $data['need'] != ''			?	' data-need="' . $data['need'] . '"' : '';
					$needValue = 	isset($data['needValue']) && $data['need'] != '' 	?	' data-nvalue="' . $data['needValue'] . '"' : '';
					$extraInpt .= $need . $needValue;
					$obligatorio = '';
					$asterisco = '';
					if ($need != '') {
						$classInpt .= ' tiene-padre';
						if ($datosEstudioNota[$data['need']] == 0){
							$tmpltDiv .= '_oculto';
						} else {
							$obligatorio = ' obligatorio';
							$asterisco = '*';
						}
					}
					if ($obligatorio == '' && $ob){
						$obligatorio = ' obligatorio';
						$asterisco = '*';
					}
					switch ($data['type']) {
						case 'check':
							$classInpt .= ' wf-input';
							break;
						default:
							$classInpt .= ' form-control wf-input';
							break;
					}
					
					$reverse = false;
					if (isset($data['reverse'])){
						$reverse = $data['reverse'];
					}
					if ($postStr != ''){
						$postStr = ' (' . $postStr . ')';
					}
				$datosItem = array(
					'id'=>$id,
					'nameStr'=>$nameStr,
					'obligatorio'=>$obligatorio,
					'asterisco'=>$asterisco,
					'postStr'=>$postStr,
					'classDiv'=>$classDiv,
					'wf_id'=>$id . '_wf_id',
					'tipo'=>$data['type'],
				);
				switch ($data['type']) {
					case 'number':
						if ($value == 0) $value = '';
						$extraInpt .= ' min="0"';
						$datosItem['input'] = mkInput($value, $id, $classInpt, 'number', $extraInpt, $name);
						break;
					case 'boolean':
						$data = array(array('val'=>0, 'name'=>'No'), array('val'=>1, 'name'=>'Si'));
						$input = mkSelect($value, $id, $data, $classInpt, $opInicial, $name, $extraInpt);
						$datosItem['input'] = $input;
						break;
					case 'check':
						$input = mkCheck($value, $id, $name, $nameStr, $classInpt, $extraInpt);
						$datosItem['input'] = $input;
						break;
					case 'list':
						$datosItem['input'] = mkSelect($value, $id, $opciones, $classInpt, $opInicial, $name, $extraInpt);
						break;
					case 'textarea':
						$datosItem['input'] = mkTextarea($value, $id, $classInpt, $name, $extraInpt);
						break;
					case 'label':
						$datosItem['input'] = '';
						$tmpltDiv = '_form_label_row';
						break;
					case 'startline':
						$datosItem['tag'] = '<div class="form-row">';
						break;
					case 'endline':
						$datosItem['tag'] = '</div>';
						break;
					case 'headpadece':
						$datosItem['tag'] = makeTemplate('_form_row_esp0.html', array(), 'expediente');
						break;
					case 'headauxiliar':
						$datosItem['tag'] = makeTemplate('_form_row_esp0b.html', array(), 'expediente');
						break;
					case 'headictamen1':
						$datosItem['tag'] = makeTemplate($tmpltDiv, array(), 'expediente');
						break;
					case 'headictamen2':
						$datosItem['tag'] = makeTemplate($tmpltDiv, array(), 'expediente');
						break;
					case 'headictamen3':
						$datosItem['tag'] = makeTemplate($tmpltDiv, array(), 'expediente');
						break;
					case 'footdictamen':
						$datosRegistro = BD::ExecuteUno('crs_registros', "nip = $this->data3");
						$paciente = $datosRegistro['nombre'] . ' ' . $datosRegistro['apellido_paterno'] . ' ' . $datosRegistro['apellido_materno'];
						$datosItem['paciente'] = $paciente;
						break;
					case 'special':
						switch ($tmpltDiv) {
							case '_form_row_esp1':
								$extraInpt .= ' style="max-width:150px; margin:auto"';
								$datosItem['input1'] = mkInput($datosEstudioNota[$id . '1'], $id . '1', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['input2'] = mkInput($datosEstudioNota[$id . '2'], $id . '2', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['input3'] = mkInput($datosEstudioNota[$id . '3'], $id . '3', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['input4'] = mkInput($datosEstudioNota[$id . '4'], $id . '4', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['tipo1'] = 'date';
								$datosItem['tipo2'] = 'date';
								$datosItem['tipo3'] = 'date';
								$datosItem['tipo4'] = 'date';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								$datosItem['wf_id4'] = $id . '4';
								break;
							case '_form_row_esp2':
								$extraInpt .= ' min="0" style="max-width:100px; margin:auto"';
								$datosItem['input1'] = mkInput($datosEstudioNota[$id . '1'], $id . '1', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['input2'] = mkInput($datosEstudioNota[$id . '2'], $id . '2', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['input3'] = mkInput($datosEstudioNota[$id . '3'], $id . '3', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['input4'] = mkInput($datosEstudioNota[$id . '4'], $id . '4', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['tipo1'] = 'number';
								$datosItem['tipo2'] = 'number';
								$datosItem['tipo3'] = 'number';
								$datosItem['tipo4'] = 'number';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								$datosItem['wf_id4'] = $id . '4';

								break;
							case '_form_row_esp3':
								$datosItem['input1'] = mkSelect($datosEstudioNota[$id . '1'], $id . '1', $opciones, $classInpt, true, $name . '1', $extraInpt);
								$datosItem['input2'] = mkSelect($datosEstudioNota[$id . '2'], $id . '2', $opciones, $classInpt, true, $name . '2', $extraInpt);
								$datosItem['input3'] = mkSelect($datosEstudioNota[$id . '3'], $id . '3', $opciones, $classInpt, true, $name . '3', $extraInpt);
								$datosItem['input4'] = mkSelect($datosEstudioNota[$id . '4'], $id . '4', $opciones, $classInpt, true, $name . '4', $extraInpt);
								$datosItem['tipo1'] = 'select';
								$datosItem['tipo2'] = 'select';
								$datosItem['tipo3'] = 'select';
								$datosItem['tipo4'] = 'select';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								$datosItem['wf_id4'] = $id . '4';

								break;
							case '_form_row_esp4':
								$datosItem['input1'] = mkTextarea($datosEstudioNota[$id . '1'], $id . '1', $classInpt, $name . '1', $extraInpt);
								$datosItem['input2'] = mkTextarea($datosEstudioNota[$id . '2'], $id . '2', $classInpt, $name . '2', $extraInpt);
								$datosItem['input3'] = mkTextarea($datosEstudioNota[$id . '3'], $id . '3', $classInpt, $name . '3', $extraInpt);
								$datosItem['input4'] = mkTextarea($datosEstudioNota[$id . '4'], $id . '4', $classInpt, $name . '4', $extraInpt);
								$datosItem['tipo1'] = 'text';
								$datosItem['tipo2'] = 'text';
								$datosItem['tipo3'] = 'text';
								$datosItem['tipo4'] = 'text';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								$datosItem['wf_id4'] = $id . '4';

								break;
							case '_form_row_esp5':
								$datosItem['input1'] = mkSelect($datosEstudioNota[$id . '1'], $id . '1', $opciones, $classInpt, true, $name . '1', $extraInpt);
								$datosItem['input2'] = mkSelect($datosEstudioNota[$id . '2'], $id . '2', $opciones, $classInpt, true, $name . '2', $extraInpt);
								$datosItem['input3'] = mkSelect($datosEstudioNota[$id . '3'], $id . '3', $opciones, $classInpt, true, $name . '3', $extraInpt);
								$datosItem['tipo1'] = 'select';
								$datosItem['tipo2'] = 'select';
								$datosItem['tipo3'] = 'select';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								break;
							case '_form_row_esp6':
								$datosItem['input1'] = mkInput($datosEstudioNota[$id . '1'], $id . '1', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['input2'] = mkInput($datosEstudioNota[$id . '2'], $id . '2', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['input3'] = mkInput($datosEstudioNota[$id . '3'], $id . '3', $classInpt, 'date', $extraInpt, $name . '1');
								$datosItem['tipo1'] = 'date';
								$datosItem['tipo2'] = 'date';
								$datosItem['tipo3'] = 'date';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								break;
							case '_form_row_esp7':
								$extraInpt .= ' min="0" style="max-width:100px; margin:auto"';
								$datosItem['input1'] = mkInput($datosEstudioNota[$id . '1'], $id . '1', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['input2'] = mkInput($datosEstudioNota[$id . '2'], $id . '2', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['input3'] = mkInput($datosEstudioNota[$id . '3'], $id . '3', $classInpt, 'number', $extraInpt, $name . '1');
								$datosItem['tipo1'] = 'number';
								$datosItem['tipo2'] = 'number';
								$datosItem['tipo3'] = 'number';
								$datosItem['wf_id1'] = $id . '1';
								$datosItem['wf_id2'] = $id . '2';
								$datosItem['wf_id3'] = $id . '3';
								break;
						}
						break;
					case 'specialcheck':
						$val1 = $opciones[0]['val'];
						$val2 = $opciones[1]['val'];
						$checked1 = '';
						$checked2 = '';
						if ($datosEstudioNota[$id] == 1){
							$checked1 = ' checked="checked"';
						}
						if ($datosEstudioNota[$id] == 2){
							$checked2 = ' checked="checked"';
						}
						$input1 = mkRadio($val1, $id . '_1', $name, $checked1);
						$input2 = mkRadio($val2, $id . '_2', $name, $checked2);
						$datosItem['input1'] = $input1;
						$datosItem['input2'] = $input2;
						$datosItem['label1'] = $opciones[0]['name'];
						$datosItem['label2'] = $opciones[1]['name'];
						break;
					default:
						$webFormItems .= di('*** ' . $nameStr . ' - ' . $data['type'] . ' ***');
						break;
				}
				$webFormItems .= makeTemplate($tmpltDiv . '.html', $datosItem, 'expediente');
			}
			$accionDestino = $datosEstudioNota['ID'] > 0 ? 'edit' : 'new';
			switch ($this->estatusEstaNota) {
				case 1: // No existía, recién se creo
				case 2: // Ya existe, no se ha cerrado
					$accionDestino = 'close';
					break;
				case 3: // Se ha cerrado y creado
					$accionDestino = 'update';
					break;
			}
			// edi('Aprobado ' . $this->estatusEstudio);
			$idEstudio = $datosEstudioNota['idEstudio'] > 0 ? $datosEstudioNota['idEstudio'] : $this->idEstudio;
			$cssAprobado = $this->estatusEstudio == 0 ? '' : ' oculto';
			$fecha_registro = explode(' ', $datosEstudioNota['fecha_registro']);
			$fecha_termina = explode(' ', $datosEstudioNota['fecha_termina']);
			$fecha_registro_fecha = $fecha_registro[0];
			$fecha_termina_fecha = $fecha_termina[0];

			$data = array(
				'webFormItems'=>$webFormItems,
				// 'destino'=>APP_URL . 'webservices/notas.php?data1=' . $this->data1 . '&data2=' . $this->data2 . '&data3=' . $this->data3 . '&data4=' . $this->data4 . '&data5=' . $accionDestino,
				'destino'=>APP_URL . 'webservices/notas.php',
				'regresa'=>APP_URL . $this->data1 . '/' . $this->data2 . '/' . $this->data3,
				'tabla'=>$this->data4,
				'idEstudio'=>$idEstudio,
				'action'=>$accionDestino,
				'cssAprobado'=>$cssAprobado,
				'fecha_registro_fecha'=>$fecha_registro_fecha,
				'fecha_termina_fecha'=>$fecha_termina_fecha,
			);
			$webForm = makeTemplate('_web_form.html', $data, 'expediente');
			return $webForm;
			}
		function getTyped($campo){
			$line = '';
			$divIzar = true;
			switch ($campo['type']) {
				case 'LABEL': case 'LABEL-C': $line = ''; break;
				case 'RADIO-LINEAL':$line = "`" . $campo['field'] . "` tinyint(3) NOT NULL DEFAULT '0',"; 		break;
				case 'BOOLEAN': case 'CHECK': $line = "`" . $campo['field'] . "` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1= Si, 0 = No',"; 		break;
				case 'SPECIALCHECK': $line = "`" . $campo['field'] . "` tinyint(1) NOT NULL DEFAULT '0',"; 		break;
				case 'LIST': case 'EXTRA-SELECT': $line = "`" . $campo['field'] . "` int(10) NOT NULL DEFAULT '0',"; 		break;
				case 'DATE': 		$line = "`" . $campo['field'] . "` DATE NOT NULL DEFAULT '0000-00-00',";break;
				case 'TEXTAREA': 	$line = '`' . $campo['field'] . '` text NOT NULL,'; 					break;
				case 'NUMBER': 		$line = "`" . $campo['field'] . "` int(5) NOT NULL DEFAULT '0',"; 		break;
				case 'FLOAT': case 'FLOAT-NO':	$line = "`" . $campo['field'] . "` float(5,2) NOT NULL DEFAULT '0',"; 	break;
				case 'TEXT': 		$line = "`" . $campo['field'] . "` varchar(250) NOT NULL,";				break;
				case 'MULTI':
					foreach ($campo['values'] as $uno) {
						$line .= self::getTyped($uno);
					}
					break;
				case 'SPECIAL':
					$divIzar = false;
					switch ($campo['tmpltDiv']) {
						case '_form_row_esp1':
							$line .= di("`" . $campo['field'] . "1` DATE NOT NULL DEFAULT '0000-00-00',");
							$line .= di("`" . $campo['field'] . "2` DATE NOT NULL DEFAULT '0000-00-00',");
							$line .= di("`" . $campo['field'] . "3` DATE NOT NULL DEFAULT '0000-00-00',");
							$line .= di("`" . $campo['field'] . "4` DATE NOT NULL DEFAULT '0000-00-00',");
							break;
						case '_form_row_esp2':
							$line .= di("`" . $campo['field'] . "1` int(5) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "2` int(5) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "3` int(5) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "4` int(5) NOT NULL DEFAULT '0',");
							break;
						case '_form_row_esp3':
							$line .= di("`" . $campo['field'] . "1` tinyint(2) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "2` tinyint(2) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "3` tinyint(2) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "4` tinyint(2) NOT NULL DEFAULT '0',");
							break;
						case '_form_row_esp4':
							$line .= di("`" . $campo['field'] . "1` TEXT NOT NULL,");
							$line .= di("`" . $campo['field'] . "2` TEXT NOT NULL,");
							$line .= di("`" . $campo['field'] . "3` TEXT NOT NULL,");
							$line .= di("`" . $campo['field'] . "4` TEXT NOT NULL,");
							break;
						case '_form_row_esp5':
							$line .= di("`" . $campo['field'] . "1` int(6) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "2` int(6) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "3` int(6) NOT NULL DEFAULT '0',");
							break;
						case '_form_row_esp6':
							$line .= di("`" . $campo['field'] . "1` DATE NOT NULL DEFAULT '0000-00-00',");
							$line .= di("`" . $campo['field'] . "2` DATE NOT NULL DEFAULT '0000-00-00',");
							$line .= di("`" . $campo['field'] . "3` DATE NOT NULL DEFAULT '0000-00-00',");
							break;
						case '_form_row_esp7':
							$line .= di("`" . $campo['field'] . "1` float(6,2) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "2` float(6,2) NOT NULL DEFAULT '0',");
							$line .= di("`" . $campo['field'] . "3` float(6,2) NOT NULL DEFAULT '0',");
							break;
						default:
							$line = '--------------- ' . $campo['tmpltDiv'] . ' ---------------';
							break;
					}
					break;
				case 'STARTLINE': case 'ENDLINE': case 'HEADPADECE': case 'HEADAUXILIAR': case 'HEADICTAMEN1': case 'HEADICTAMEN2': case 'HEADICTAMEN3':
					$line = '';
					break;
				default: $line = '--------------- ' . $campo['type'] . ' ---------------'; 	break;
			}

			if ($line != ''){
				if ($divIzar){
					return di($line);
				} else {
					return $line;
				}
			} else {
				return '';
			}
			}
		function getDatosNota($tabla = ''){
			$datos = array();
			$tablaNota = $tabla != '' ? 'crs_nota_' . $tabla : 'crs_nota_' . $this->data4;
			if ($datos = BD::ExecuteUno($tablaNota, "`idEstudio` = $this->idEstudio")){
				// printArray($datos);
				return $datos;
			} else {
				return false;
			}
			}
		function arrancaNota(){
			$tablaNota = 'crs_nota_' .$this->data4;
			$data = array(
				'fecha_registro'=>"'" . dateMeFull() . "'",
				'idEstudio' => $this->idEstudio,
			);
			$sql = prepareSQL($data, 'create', $tablaNota);
			$do = BD::Insert($sql);

			return $do;
			}
}
?>