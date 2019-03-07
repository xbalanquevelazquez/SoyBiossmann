<?php 
function make_safe($char) {
    $ban = 1;
    $hack_sql = array("'", "*", "?", '/', "%", '"', ";", ":", "\\", "HAVING", "GROUP", "INSERT", "UNION", "DROP", "TABLE", "SET", "DELETE", "UPDATE", "SELECT", "MEMB_INFO", "MEMB__PWD", "MEMB___ID", "ALTER", "JAVASCRIPT", "ALERT", "SCRIPT", "FRAME", "ONMOUSE", "ONCLICK", "PROMPT");
    //$hack_xxs = array("JAVASCRIPT", "SCRIPT");
    $char1 = explode(" ", strtoupper($char));
    for ($i = 0; $i < count($char1); $i++) {
        if (in_array($char1[$i], $hack_sql)) {
            $ban = 0;
        }
    }
    return $ban;
}

function encuentraCoincidencia($arrCoincidencia, $texto){
    $buffer = '';
    $textoTemp = $texto;
    #echo ">>$textoTemp<<";
    //$inicial = TRUE;
    //if(stripos($texto, $coincidencia) !== FALSE){
        
        //$buffer .= separarPalabras($texto,$coincidencia);

   // }else{
        //$buffer .= $texto;
   // }
    foreach ($arrCoincidencia as $coincid) {
        if(stripos($textoTemp, $coincid) !== FALSE){
            //if($inicial){
                $textoTemp = separarPalabras($textoTemp,$coincid); $inicial = FALSE;   
            //}else{
            //    $textoTemp = separarPalabras($textoTemp,$coincid); 
            //}
        }else{
            $textoTemp = $textoTemp;
        }
    }
    
    return str_replace(array("[","]"), array("<span>","</span>"), $textoTemp);
}

function separarPalabras($texto,$coincidencia){
    $resultado = '';
    $posicionInicio = stripos($texto,$coincidencia);
    #echo "[$texto][$posicionInicio]";
    $longitud = strlen($coincidencia);
    $end='';
    //$first = $middle = $end = '';

    if($posicionInicio !== FALSE){
        $first = substr($texto, 0, $posicionInicio);
        #echo "|";
        $middle = substr($texto,$posicionInicio,$longitud);
        #echo "|";
        $end = substr($texto, ($posicionInicio + $longitud));
        #echo "]";
        #echo "($first | $middle | $end)";
        $resultado .= $first."[".$middle."]";
    }

    if(stripos($end,$coincidencia) !== FALSE){
        $resultado .= separarPalabras($end,$coincidencia);
    }else{
        $resultado .= $end;
    }
    return $resultado;
}

function normaliza ($ch){
    $originales = array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","ä","ë","ï","ö","ü","Ä","Ë","Ï","Ö","Ü");
    $modificada = array("a","e","i","o","u","n","A","E","I","O","U","N","a","e","i","o","u","A","E","I","O","U");
    return strtoupper(str_replace($originales, $modificada, $ch));
}


function wsComprobarLogin($usuario,$password){
    $url = WS_PROTOCOL.WS_DOMAIN.WS_LOGIN.'_ws_login.php';
    $location = WS_DOMAIN;
   
    $postdata = 'username='.$usuario.'&password='.$password.'&type=checkpass';
   
    if($response = curl_get_contents($url, $postdata, $location)){ //$array = json_decode($json,true);
        return $response;
    }else{
        $error = error_get_last();
        echo "Error en get contents: ". $error['message'];
    }
}
function wsGetMoodleUserData($id){
    $url = WS_PROTOCOL.WS_DOMAIN.WS_LOGIN.'_ws_userdata.php';
    $location = WS_DOMAIN;
   
    $postdata = 'user='.$id;
   
    if($response = curl_get_contents($url, $postdata, $location)){ //$array = json_decode($json,true);
        return $response;
    }else{
        $error = error_get_last();
        echo "Error en get contents: ". $error['message'];
    }
}
function ssl_encrypt($string){
    $password = ENCRYPT_KEY;
    $method = ENCRYPT_METHOD;
    return $encrypted = openssl_encrypt($string, $method, $password);
}
function ssl_decrypt($encrypted){
    $password = ENCRYPT_KEY;
    $method = ENCRYPT_METHOD;
    return $decrypted = openssl_decrypt($encrypted, $method, $password);
}
function curl_get_contents($url, $postdata, $location)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Connection: close','Host: '.$location));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec ($ch);

    curl_close ($ch);
    return $data;
}
function convertirNombreArchivo($filename){
    $filename = strtolower(utf8_decode_seguro($filename));
    $arrSearch = array(' ','á','é','í','ó','ú','ñ');
    $arrReplace = array('-','a','e','i','o','u','n');
    return str_replace($arrSearch,$arrReplace,$filename);
}
function convertirLatino($text){
    $text = utf8_decode_seguro($text);
    $arrSearch = array('Á','É','Í','Ó','Ú','Ñ','á','é','í','ó','ú','ñ');
    $arrReplace = array('A','E','I','O','U','N','a','e','i','o','u','n');
    return str_replace($arrSearch,$arrReplace,$text);
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
function formatDate($date){
    $fecha = explode("-",$date);
    switch($fecha[1]){
        case '01':
            $mes = 'enero';
            break;
        case '02':
            $mes = 'febrero';
            break;
        case '03':
            $mes = 'marzo';
            break;
        case '04':
            $mes = 'abril';
            break;
        case '05':
            $mes = 'mayo';
            break;
        case '06':
            $mes = 'junio';
            break;
        case '07':
            $mes = 'julio';
            break;
        case '08':
            $mes = 'agosto';
            break;
        case '09':
            $mes = 'septiembre';
            break;
        case '10':
            $mes = 'octubre';
            break;
        case '11':
            $mes = 'noviembre';
            break;
        case '12':
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
function calculaDiasTranscurridos($date1,$date2){
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%a');
}
function formatoFechaTextual($date){
    return formatFechaEspaniol(date_format(date_create($date),"l, d \d\\e F \d\\e Y, \a \l\a\s g:i a"));
}
function generarCodigo(){
    return $codigo = chr(rand(65,90)) . chr(rand(65,90)) . rand(1,9) . chr(rand(65,90)) . chr(rand(65,90));
}
function comprobarCodigoDuplicado($codigo,$arrCodigos){
    if(in_array($codigo, $arrCodigos)){
        $codigo = comprobarCodigoDuplicado(generarCodigo(),$arrCodigos);
    }
    return $codigo;
}
function makeTemplate($plantilla, $datos, $dir = '', $rutaEspecial = ''){
        $html = '';
        if ($dir != '') $dir = $dir . '/';
        if ($rutaEspecial != ''){
            $file = $rutaEspecial . $plantilla;
        } else {
            $file = TEMPLATE_PATH . $dir . $plantilla;
        }
        if (file_exists($file)){
            $itModel = file_get_contents($file);
            foreach ($datos as $key => $value) {
                $re_str = '{$' . $key . '}';
                $re_value = $value;
                if (is_array($re_value)) $re_value = '';
                $itModel = str_replace($re_str, $re_value, $itModel);
            }
            $html .= $itModel;
        } else $html .= 'No existe el archivo: ' . $file;

    return $html;
}
function replaceDirImages($string){
    $find = array('../../../../webimgs/','../../webimgs/','../http');
    $replace = array(WEB_IMG_PATH,WEB_IMG_PATH,'http');
    return str_replace($find, $replace, $string);
}
function replaceStylesDefs($string){
    $find = array('<strong>','</strong>','<em>','</em>');
    $replace = array('<span class="strong">','</span>','<span class="italic">','</span>');
    return str_replace($find, $replace, $string);
}
function normalizarNuke($string){
    $find = array('Do*****ento','do*****ento','*****ple');
    $replace = array('Documento','documento','cumple');
    return str_replace($find, $replace, $string);
}
function getEstatusColor($num){

        /**************************
        1 = Si
        2 = Si, pero podria mejorarse
        3 = No
        ***************************/

        switch($num){

            case '1':
                $color = '194,212,0';
                break;

            case '2':
                $color = '246,132,41';
                break;

            case '3':
                $color = '193,39,186';
                break;

            case '-':
            case '':
            case NULL:
            default:
                $color = '191,191,191';
                break;
        }
    return $color;
}
function getLabelXOpcion($num){

        /**************************
        1 = Si
        2 = Si, pero podria mejorarse
        3 = No
        ***************************/

        switch($num){
            case '1':
                $label = "Si";
                break;

            case '2':
                $label = "Si, pero podria mejorarse";
                break;

            case '3':
                $label = "No";
                break;

            case '-':
            case '':
            case NULL:
            default:
                $label = "erroneo";
                break;
        }
    return $label;
}

function validar($string,$kind,$nombreCampo){
    $error = '';
    if(trim($string) != ''){
        switch($kind) {
            case 'normal':
            break;
            case 'text':
                if(!preg_match("/^[a-zA-Z ]*$/",$string)) {
                  $error .= "Sólo se permiten letras y espacios en el campo '$nombreCampo'. ";
                }
                break;
            case 'mail':
                if(!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                  $error .= "Formato de correo incorrecto en el campo '$nombreCampo'. ";
                }
                break;
            case 'number':
                if(!is_numeric($string)){
                    $error .= "Debe indicar un número en el campo '$nombreCampo'. ";
                }
                break;
            default:
                $error .= "Indique el tipo de validación para el campo '$nombreCampo'. ";
                break;
        }
    }else{
        $error .= "El campo '$nombreCampo' está vacío. ";
    }
    return $error;
}
function enviarMensaje($tituloMensaje, $templateName, $datosMensaje){
    global $mail;
    $mensaje = makeTemplate($templateName, $datosMensaje, 'correos');

    // Envía el correo
        $dataMail = array(
            'sender_mail'=>SENDER_MAIL,
            'sender_name'=>SENDER_MAIL_NAME,
            'destinatarios' => array(SAE_MAIL),
#           'destinatarios_bcc' =>array('alfredo.zamarripa@mariestopes.org.mx', 'info@proteccionysalud.com'),
            'isHTML'=>TRUE,
            'titulo'=>utf8_encode($tituloMensaje),
            'mensaje'=>$mensaje,
            'alt_mensaje'=>'',
        );
        $mail->debug(FALSE);
        if ($mail->send_mail($dataMail)){ 
            $success = TRUE;
            $error = '';
        } else { 
            $success = FALSE;
            $error = 'Error de envío:' . $mail->error;
        }
    return array('success'=>$success,'error'=>$error);
}
?>