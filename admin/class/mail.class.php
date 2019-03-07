<?php
class PHPMailSender{
	var $PHPMailer 		= false;
	var $host			= '';
	var $port			= '';
	var $SMPTSecure		= '';
	var $username		= '';
	var $password		= '';
	var $debug 			= FALSE;
	var $error 			= '';

	function __construct(){
		$this->PHPMailer = new PHPMailer();
	}
	function configMail($data = array('host'=>'','username'=>'','password'=>'','port'=>'','SMPTSecure'=>'')){
		if(isset($data['host'])){
			$this->host = $data['host'];
		}
		if(isset($data['port'])){
			$this->port = $data['port'];
		}
		if(isset($data['SMPTSecure'])){
			$this->SMPTSecure = $data['SMPTSecure'];
		}
		if(isset($data['username'])){
			$this->username = $data['username'];
		}
		if(isset($data['password'])){
			$this->password = $data['password'];
		}


		if($this->debug)	$this->PHPMailer->SMTPDebug = 2;             // Enable verbose debug output
		$this->PHPMailer->IsSMTP();                                      // Set mailer to use SMTP
		$this->PHPMailer->Host = $this->host;  // Specify main and backup SMTP servers
		$this->PHPMailer->SMTPAuth = $this->SMPTSecure;                               // Enable SMTP authentication
		$this->PHPMailer->Username = $this->username;                 // SMTP username
		$this->PHPMailer->Password = $this->password;                           // SMTP password
		#if($this->SMPTSecure != '')	$this->PHPMailer->SMTPSecure = $this->SMPTSecure;                            // Enable TLS encryption, `ssl` also accepted
		$this->PHPMailer->Port = $this->port;                                    // TCP port to connect to
	}
	function debug($activar){
		if($activar == TRUE){
			$this->debug = TRUE;
		}else{
			$this->debug = FALSE;
		}
	}
	function send_mail($data = array('sender_mail'=>'','sender_name'=>'','destinatarios' => array(),'isHTML'=>TRUE,'titulo'=>'','mensaje'=>'','alt_mensaje'=>'')){
		if($this->debug)	$this->PHPMailer->SMTPDebug = 2;
		try {
				$this->PHPMailer->setFrom($data['sender_mail'], utf8_decode($data['sender_name']));
				foreach($data['destinatarios'] as $destinatario){
					$this->PHPMailer->addAddress($destinatario); 
				}
				if (isset($data['destinatarios_bcc'])){
					if (count($data['destinatarios_bcc']) > 0){
						foreach($data['destinatarios_bcc'] as $destinatario){
							$this->PHPMailer->addBCC($destinatario); 
						}
					}
				}
				#$this->PHPMailer->addReplyTo('info@example.com', 'Information');
				#$this->PHPMailer->addCC('cc@example.com');
				#$this->PHPMailer->addBCC('bcc@example.com');

				//Attachments
				#$this->PHPMailer->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				#$this->PHPMailer->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				//$from = SENDER_FROM;
				//$fname = SENDER_FNAME;
				#$reply = SENDER_REPLY;
				//Content
				$this->PHPMailer->isHTML($data['isHTML']);                                  // Set email format to HTML
				$this->PHPMailer->Subject = utf8_decode($data['titulo']);
				$this->PHPMailer->Body    = utf8_decode($data['mensaje']);

				$this->PHPMailer->setFrom(SENDER_MAIL, utf8_decode(SENDER_MAIL_NAME));
				#$this->PHPMailer->addReplyTo($reply, utf8_decode($fname));

				if($data['alt_mensaje'] != ''){
					$this->PHPMailer->AltBody = $data['alt_mensaje'];
				}
				$this->PHPMailer->send();
				return TRUE;
			} catch (Exception $e) {
				$this->error = $this->PHPMailer->ErrorInfo;
				return FALSE;
			}
	}
}

$mail = new PHPMailSender();
$mail->configMail	(array(
						'host'=>SENDER_MAIL_HOST,
						'username'=>SENDER_MAIL,
						'password'=>SENDER_PASS,
						'port'=>SENDER_MAIL_PORT,
						'SMTPSecure'=>SENDER_MAIL_SECURE
						)
					);

?>
