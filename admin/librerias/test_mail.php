<?php
define('VIEWABLE'	,TRUE);
$thisserver = $_SERVER['SERVER_NAME'];

        define('WEB_PATH',          'https://soy.biossmann.com/');
        define('APP_PATH',          '/usr/local/apache/htdocs_ssl/');

        define('APP_URL',           WEB_PATH);
		define('SENDER_MAIL',		'noreply@biossmann.com');
		define('SENDER_PASS',		'');
		define('SENDER_MAIL_HOST',	'192.168.4.1');
		define('SENDER_MAIL_PORT',	'25');
		define('SENDER_MAIL_SECURE',  FALSE);
		define('SENDER_MAIL_NAME',	'SoyBiossmann');
		define('SAE_MAIL',	'xbalanque.velazquez@biossmann.com');

		define('CLASS_PATH',		APP_PATH.'admin/class/');
		define('LIB_PATH',			APP_PATH.'admin/librerias/');

		require_once(LIB_PATH.'PHPMailer/class.phpmailer.php');
		include_once(LIB_PATH."PHPMailer/class.smtp.php");
		include_once(CLASS_PATH."mail.class.php");#se crea un $mail


$dataMail = array(
            'sender_mail'=>SENDER_MAIL,
            'sender_name'=>SENDER_MAIL_NAME,
            'destinatarios' => array(SAE_MAIL),
#           'destinatarios_bcc' =>array('alfredo.zamarripa@mariestopes.org.mx', 'info@proteccionysalud.com'),
            'isHTML'=>TRUE,
            'titulo'=>utf8_encode('Mi prueba de correo'),
            'mensaje'=>'Prueba de correo electrónico',
            'alt_mensaje'=>''
        );
        $mail->debug(TRUE);
        if ($mail->send_mail($dataMail)){ 
            $success = TRUE;
            $error = '';
        } else { 
            $success = FALSE;
            $error = 'Error de envío:' . $mail->error;
        }

        echo "SUCCESS: $success <br /> ERROR: $error <br />";
?>