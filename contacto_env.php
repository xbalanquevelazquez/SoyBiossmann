<?php
/*----------------------------------------------------------------------------
    @file: formulario.php
    @author: Saul Morales
    @since: 2006-08-21

      This script process feedback_form,
      sends a confirmation email to client and sends data to admin guy via email.

----------------------------------------------------------------------------*/
$now = date("Ymdhis");
$nowDate = date("Y-m-d h:i:s");
$nowDateSP = date("d/m/Y h:i:s");
$urlSuccess = '/gracias';
$urlReferer = $_SERVER['HTTP_REFERER'];

//BEGIN: email configuration
$adminMail          = "xxxx@xxxx.xxx.mx";#= "uecomar@segob.gob.mx"; //The guy who will receive the info to process it
$adminSubject       = "XXXXX [".$now."]";

$separationLine 		="----------------------------------------------------------------------";
//$forCostumersHeading    = $nowDateSP."\n\r\n\r";
$forCostumersHeading    = "";
$forCustomersName       = "XXXX";
$forCustomersMail       = $adminMail;
$forCustomersSubject    = "XXXXX contacto [".$now."]";
$forCustomersText		= "Este mensaje de confirmacion fue generado automaticamente";

$header  = "From: ".$forCustomersName." <".$forCustomersMail.">\n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
$header .= "Content-Transfer-Encoding: 8bit\n";
$header .= "X-Mailer: PHP v".phpversion();
//END: email configuration

//Avoid access if not posting
if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    header('location: '.$urlReferer);
    //die(' Forbidden - You are not authorized to view this page (1)');
    exit;
}

/*smorales: blocked until PHP is recompiled with GD2
$key=strtoupper(substr($_SESSION['key'],0,7));
$validationPhrase = strtoupper(substr($_POST['validationPhrase'],0,7));
if($validationPhrase!=$key)
{
    header("location: PRNW_form.php?error=1");
}
*/

// Host names from where the form is authorized
// to be posted from:
$authHosts = array("esmasa.techandarts.com","www.esmasa.com","esmasa.com","localhost");

//Where have we been posted from?
$fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));

//Test to see if the $fromArray used www to get here.
$wwwUsed = strpos($fromArray['host'], "www.");

//Make sure the form was posted from an approved host name.
if(!in_array(($wwwUsed === false ? $fromArray['host'] : substr(stristr($fromArray['host'], '.'), 1)), $authHosts))
{
    reportAbuse("Form was not posted from an approved host name");
    die(' Forbidden - You are not authorized to view this page (2)');
    exit;
}

// Attempt to defend against header injections:
$badStrings = array("content-type:",
"mime-version:",
"content-transfer-encoding:",
"multipart/mixed",
"charset=",
"bcc:",
"cc:");

// Loop through each POST'ed value and test if it contains
// one of the $badStrings:
foreach($_POST as $k => $v)
{
    if ($k!="rblCampaign" && $k!="chkAccept")
    {
        foreach($badStrings as $v2)
        {
            if(strpos(strtolower($v), $v2) !== false)
            {
                reportAbuse($v);
                die('Form processing cancelled: string
                (`'.$v.'`) contains text portions that
                are potentially harmful to this server.<br />Your input
                has not been sent! Please use your browser\'s
                `back`-button to return to the previous page and try
                rephrasing your input.');
                exit;
            }
        }
    }
}

// Made it past spammer test, free up some memory
// and continuing the rest of script:
unset($k, $v, $v2, $badStrings, $authHosts, $fromArray, $wwwUsed);

if (!isset($_POST["nombreContacto"]) || !isset($_POST["mail"]) || !isset($_POST["comentarios"]))
{ //Avoid access if not receiving required fields
    header('location: '.$urlReferer);
    //die("Error. Needed required fields");
}

//Let's proccess common fields
$nombreCiudadano        = "";
$correoCiudadano        = "";
$comentarioCiudadano    = "";

if (isset($_POST["nombreContacto"]))        $nombreCiudadano        = $_POST["nombreContacto"];
if (isset($_POST["mail"]))             	    $correoCiudadano        = $_POST["mail"];
if (isset($_POST["comentarios"]))      		$comentarioCiudadano	= $_POST["comentarios"];
if (isset($_POST["tema"]))        			$tema					= $_POST["tema"];


//BEGIN: build Content
$formData = "";
$formData.= "Fecha: [".$nowDateSP."]\n";
$formData.= "Nombre: [".$nombreCiudadano."]\n";
$formData.= "E-Mail: [".$correoCiudadano."]\n";
$formData.= "Tema: [".$tema."]\n";
$formData.= "Comentario: [".$comentarioCiudadano."]\n";

$env = '';
$env .= "\n-------- Env Report --------\n";
$env .= "REMOTE_HOST: ".getenv("REMOTE_HOST")."\n";
$env .= "REMOTE_ADDR: ".getenv("REMOTE_ADDR")."\n";
$env .= "REMOTE_USER: ".getenv("REMOTE_USER")."\n";
$env .= "HTTP_USER_AGENT: ".getenv("HTTP_USER_AGENT")."\n";

//BEGIN: sending customer confirmation mail--------------------------------------
$Name = "";

if (isset($nombreCiudadano) && !empty($nombreCiudadano))
{
    $nombreCiudadano  = Valid_Input($nombreCiudadano);
}

$correoCiudadano   = Valid_Email($correoCiudadano);

$forCustomersText = $forCostumersHeading."Estimado(a) ".$nombreCiudadano.",\n\nEn el sitio web de ESMASA http://www.esmasa.com\nhemos recibido el siguiente mensaje,\nmismo que sera leido y atendido a la brevedad:\n".$separationLine."\n".$formData."\n".$separationLine."\n".$forCustomersText;
if (mail($correoCiudadano,$forCustomersSubject,$forCustomersText,$header))
{
    $customersConfirmation = "";
} else {
    $customersConfirmation = "error";
}
//END: sending customer confirmation mail--------------------------------------


//BEGIN: sending form data to admin guy------------------------------------------
if (true)
{
	$formData.= "Env: [".$env."]\n\n";
}
if (mail($adminMail,$adminSubject,$formData,$header))
{
    $adminConfirmation = "error";
} else {
    $adminConfirmation = "error";
}
//END: sending form data to admin guy------------------------------------------

//log if something was wrong
if (!empty($customersConfirmation) || !empty($adminConfirmation))
{
    if (!empty($customersConfirmation) && !empty($adminConfirmation))
    {
        logErrors($adminSubject.": Confirmation and form data mails couldn't be sent");
    } else if (!empty($customersConfirmation)) {
        logErrors($adminSubject.": Confirmation mail couldn't be sent");
    } else if (!empty($adminConfirmation)) {
        logErrors($adminSubject.": Form data mail couldn't be sent");
    }
}


header('location: '.$urlSuccess);

function Valid_Input($data)
{
   list($data) = preg_split('/\r|\n|%0A|%0D|0x0A|0x0D/i',ltrim($data));
   return $data;
}

function Valid_Email($data)
{
   global $urlReferer;

   $pattern = '/^([0-9a-z]+[-._+&])*[0-9a-z]+@([-0-9a-z]+[.])+[a-z]{2,6}$/i';
   if (preg_match($pattern,$data)) {
      return $data;
   }
   else {
      header('location: '.$urlReferer);
      return false;
   }
}

function reportAbuse($value)
{
    global $forCustomersName,$forCustomersMail;

    $report_to = 'xvelazquez@segob.gob.mx';
    $name = $forCustomersName;
    $mail = $forCustomersMail;

    // replace this with your own get_ip function...
    $ip = (empty($_SERVER['REMOTE_ADDR'])) ? 'empty'
    : $_SERVER['REMOTE_ADDR'];
    $rf = (empty($_SERVER['HTTP_REFERER'])) ? 'empty'
    : $_SERVER['HTTP_REFERER'];
    $ua = (empty($_SERVER['HTTP_USER_AGENT'])) ? 'empty'
    : $_SERVER['HTTP_USER_AGENT'];
    $ru = (empty($_SERVER['REQUEST_URI'])) ? 'empty'
    : $_SERVER['REQUEST_URI'];
    $rm = (empty($_SERVER['REQUEST_METHOD'])) ? 'empty'
    : $_SERVER['REQUEST_METHOD'];

    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/plain; charset=iso-8859-1\n";
    $headers .= "X-Priority: 1\n";
    $headers .= "X-MSMail-Priority: Normal\n";
    $headers .= "X-Mailer: php\n";
    $headers .= "From: \"".$name."\" <".$mail.">\r\n\r\n";

    @mail
    (
    $report_to
    ,"[ABUSE] mailinjection @ " .
    $_SERVER['HTTP_HOST'] . " by " . $ip
    ,"Stopped possible mail-injection @ " .
    $_SERVER['HTTP_HOST'] . " by " . $ip .
    " (" . date('d/m/Y H:i:s') . ")\r\n\r\n" .
    "*** IP/HOST\r\n" . $ip . "\r\n\r\n" .
    "*** USER AGENT\r\n" . $ua . "\r\n\r\n" .
    "*** REFERER\r\n" . $rf . "\r\n\r\n" .
    "*** REQUEST URI\r\n" . $ru . "\r\n\r\n" .
    "*** REQUEST METHOD\r\n" . $rm . "\r\n\r\n" .
    "*** SUSPECT\r\n--\r\n" . $value . "\r\n--"
    ,$headers
    );

}

function logErrors($error="")
{

    $logText = "ERROR ". $_SERVER['HTTP_HOST'] . " (" . date('d/m/Y H:i:s') . ") - ".$error."\r\n";

    $file = "contacto_error.log";
    if (!file_exists($file)){
       touch ($file);
       $handle = fopen ($file, 'a'); // Let's open for read and write
    }
    else{
       $handle = fopen ($file, 'a'); // Let's open for read and write
    }
    fwrite ($handle, $logText); // Write error
    fclose ($handle); // Done

    return true;
}
?>