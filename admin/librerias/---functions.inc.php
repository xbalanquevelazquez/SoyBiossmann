<?php
function crearAdmin(){
	#CONFIGURACIÓN DE LA CLASE
	$myadm = new Admin();
	$myadm->debug=1;
	$myadm->libpath = 'cnf/';
	return $myadm;
}

function comprobarCampos($arrValues = array()){
	if(count($arrValues) <= 0){
		mensaje("Proporcione en una matriz los campos a verificar");
		return false;
	}
	#pongo los keys del POST en una matriz
	$arrPostKeys = array();
	foreach($_POST as $key => $value){
		$arrPostKeys[] = $key;
	}
	$mensajeInt = ''; 
	foreach($arrValues as $key){
		if(!in_array($key,$arrPostKeys)){
			if($mensajeInt != '') $mensajeInt .= ", ";
			$mensajeInt .= "Falta el campo [$key]";
		}
	}
	if($mensajeInt != '') mensaje($mensajeInt); else return true;
}

function adapta_folio($num,$cons){
	$strlen = strlen($num);
	$z = '';
	if($strlen < $cons){ 
		$znum = $cons-$strlen;
		for($i = 1;$i <= $znum;$i++){
			$z .= '0';
		} 
	}
	return $z.$num;
}
function convertirNombreArchivo($filename){
	$filename = strtolower(utf8_decode_seguro($filename));
	$arrSearch = array(' ','á','é','í','ó','ú','ñ');
	$arrReplace = array('-','a','e','i','o','u','n');
	return str_replace($arrSearch,$arrReplace,$filename);
}
function convertirDatoSeguro($input){
	return addslashes(convertirNombreArchivo($input));
}

define("UTF_8", 1); 
define("ASCII", 2); 
define("ISO_8859_1", 3); 
function codificacion($texto){ 
     $c = 0; 
     $ascii = true; 
     for ($i = 0;$i<strlen($texto);$i++) { 
         $byte = ord($texto[$i]); 
         if ($c>0) { 
             if (($byte>>6) != 0x2) { 
                 return ISO_8859_1; 
             } else { 
                 $c--; 
             } 
         } elseif ($byte&0x80) { 
             $ascii = false; 
             if (($byte>>5) == 0x6) { 
                 $c = 1; 
             } elseif (($byte>>4) == 0xE) { 
                 $c = 2; 
             } elseif (($byte>>3) == 0x14) { 
                 $c = 3; 
             } else { 
                 return ISO_8859_1; 
             } 
         } 
     } 
	return ($ascii) ? ASCII : UTF_8; 
} 

function utf8_decode_seguro($texto){ 
	return (codificacion($texto)==ISO_8859_1) ? $texto : utf8_decode($texto); 
}

function validarArchivo($filepath,$filename,$num = 1){
	$extension = explode(".",$filename);
	$ext = array_pop($extension);
	#$extension = array_merge($extension);
	#echo $extension.' | '.$ext.'  ----->   ';
	$r = '';
	foreach($extension as $seg){
		$r .= $seg;
	}
	
	$r .= "_$num.$ext";
	#echo "r:: $filepath  ---> ".$r.'<br />';
	if(file_exists($filepath.$r)){#existe
		return validarArchivo($filepath,$filename,$num+1);
	}else{#no existe, se puede emplear
		return $r;
	}
	
}

function mensaje($texto){
	echo "&r=".$texto;
}

function clean($var){
	return str_replace("#","",str_replace(";","",addslashes(trim($var))));
}

function mostrarMensaje($t){
	$m="";
	switch($t){
		case 'k0':
			$m = "El registro se actualizó correctamente";
		break;
		case 'k1':
			$m = "El registro se actualizó correctamente";
		break;
		case 'k2':
			$m = "El registro se eliminó correctamente";
		break;
		case 'k5':
			$m = "La página se eliminó correctamente";
		break;
		/**/
		case 'e0':
			$m = "Ocurrió un problema al actualizar el registro";
		break;
		case 'e1':
			$m = "Ocurrió un problema al actualizar el registro";
		break;
		case 'e2':
			$m = "Ocurrió un problema al borrar el registro";
		break;
		case 'e5':
			$m = "Ocurrió un problema al borrar la página";
		break;
		case 'e6':
			$m = "Ocurrió un problema al borrar el contenido";
		break;
	}
	return "<div class='aviso'>$m</div><br />";
}
function obtenerUbicaciones($path_file){
	/*Obtengo el archivo de plantilla a analizar*/
	$archivo = file_get_contents($path_file);
	/*Busco las variables de ubicación con {$_  } */
	preg_match_all("#{+[\$]{1,1}[_]+.*}#", $archivo, $matches);
	$ubicaciones = array();
	/*Limpio por si hay variables repetidas */
	foreach($matches[0] as $valor){
		$wordKey = str_replace('}',"",str_replace('{$_',"",$valor));
		if(!in_array($wordKey,$ubicaciones)){
			array_push($ubicaciones,$wordKey);
		}
	}
	/*Array con los resultados $ubicaciones*/
	return $ubicaciones;
}
function compararFecha($fecha1,$fecha2,$formatoResultado='segundos'){//introducir fechas
		$f1 = strtotime($fecha1);
		$f2 = strtotime($fecha2);
		$resultado = $f2-$f1;
		switch($formatoResultado)
		{
			case 'segundos':
				//el resultado ya està en segundos
			break;
			case 'minutos':
				$resultado = floor($resultado/60);
			break;
			case 'horas':
				$resultado = floor($resultado/(60*60));
			break;
			case 'dias':
				$resultado = floor($resultado/(60*60*24));
			break;
		}
		#return abs($resultado);
		return $resultado;
}
function estadoPublicacion($fechaIni,$fechaFin){
	if($fechaIni == ''){
		$fechaIni == '0000-00-00 00:00:00';
	}
	if($fechaFin == '0000-00-00 00:00:00' || $fechaFin == ''){
		$fechaFin = (date("Y")+10)."-01-00 00:00:00";
	}
	$pointIni = compararFecha(date("Y-m-d H:i:s"),$fechaIni,'minutos');
	$pointFin = compararFecha(date("Y-m-d H:i:s"),$fechaFin,'minutos');
	if($pointIni <= 0 && $pointFin >= 0){//publicado
		return "publicado";
	}else if($pointIni > 0){
		return "pendiente";
	}else if($pointFin < 0){
		return "vencido";
	}
}
function formatDate($date){
	$fecha = explode("-",$date);
	switch($fecha[1]){
		case 01:
			$mes = 'enero';
			break;
		case 02:
			$mes = 'febrero';
			break;
		case 03:
			$mes = 'marzo';
			break;
		case 04:
			$mes = 'abril';
			break;
		case 05:
			$mes = 'mayo';
			break;
		case 06:
			$mes = 'junio';
			break;
		case 07:
			$mes = 'julio';
			break;
		case 08:
			$mes = 'agosto';
			break;
		case 09:
			$mes = 'septiembre';
			break;
		case 10:
			$mes = 'octubre';
			break;
		case 11:
			$mes = 'noviembre';
			break;
		case 12:
			$mes = 'diciembre';
			break;
	}
	
	$res = "{$fecha[2]} de $mes de {$fecha[0]}";
	return $res;
}
function formatHora($time){
	$horaMinutoSeg = explode(':',$time);
	return $hora = $horaMinutoSeg[0].':'.$horaMinutoSeg[1].' hrs.';
}
function formatFechaEspaniol($input){
	$find = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	$replace = array('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo');

	$input = str_replace($find, $replace, $input);

	$find = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$replace = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	$input = str_replace($find, $replace, $input);

	return $input;
}


function crearArchivo($nombre,$contenido){
	$archivo = fopen($nombre,"w+");   
	if($archivo == false){   
	  die("No se ha podido crear el archivo.");   
	} 
	fwrite($archivo, '1');
	#fwrite($archivo, '23');
	fclose($archivo);
}

function generaThumbnail($foto,$path){
	$normales   = $path;
	$thumbnails = $path."thumb/";
	
	$newWidth = 200;
	
	$originalSize = getimagesize($normales.$foto);
	$originalWidth = $originalSize[0];
	
	if ($originalWidth < $newWidth) $newWidth = $originalWidth;
	
	if (!file_exists($thumbnails.$foto))
	{
		include(CLASS_PATH."resize.class.php");
		$thumb = new Thumbnail($normales.$foto);
		$thumb->size_width($newWidth);
		$thumb->jpeg_quality(85);
		$thumb->save($thumbnails.$foto);
	}
}
function getColor($desc){
	switch ($desc) {
		case 'morado':
		case '1':
			$numberColor = 1;
			$colorName = 'morado';
			$colorFondo = '#88448d';//904595
			$colorTexto = '#FFF';
			break;
		
		case 'verde':
		case '2':
			$numberColor = 2;
			$colorName = 'verde';
			$colorFondo = '#c5d544';
			$colorTexto = '#FFF';
			break;
		
		case 'naranja':
		case '3':
			$numberColor = 3;
			$colorName = 'naranja';
			$colorFondo = '#e35003';//df8d2e
			$colorTexto = '#FFF';
			break;
		
		case 'aqua':
		case '4':
			$numberColor = 4;
			$colorName = 'aqua';
			$colorFondo = '#7bbbb7';//6bb2af
			$colorTexto = '#FFF';
			break;
		
		case 'gris':
		case '5':
			$numberColor = 5;
			$colorName = 'gris';
			$colorFondo = '#949aa0';//959a9f
			$colorTexto = '#FFF';
			break;
		
		case 'grisOscuro':
		case '6':
			$numberColor = 6;
			$colorName = 'grisOscuro';
			$colorFondo = '#59585a';
			$colorTexto = '#FFF';
			break;
		
		default:
			$numberColor = 0;
			$colorName = 'undefined';
			$colorFondo = '#5a5959';
			$colorTexto = '#FFF';
			break;
	}
	return array('numberColor'=>$numberColor,'colorName'=>$colorName,'colorFondo'=>$colorFondo,'colorTexto'=>$colorTexto);
}
//echo compararFecha('2008-03-22 21:46:00','2008-03-21 21:45:00','dias').'<br>';
?>