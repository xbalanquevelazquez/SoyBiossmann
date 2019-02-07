<?php
//Validar que este archivo sea cargado por un Include y no directamente
date_default_timezone_set('America/Mexico_City');
if(!defined('VIEWABLE'))
{ 	
	header('HTTP/1.0 404 Not Found');
	exit;
}else{
	
	//die('died');//global $host;
	
	$usr	= 'apps';
	$psw	= '4pPd_32X';
	$host	= 'localhost';
	$db		= 'apps';


	define('HOST',	$host);
	define('USR',	$usr);
	define('PSW',	$psw);
	define('DB',	$db);
	//die($host);
	
	if(!defined('SYS_NAME')){
		define('SYS_NAME','Hydrak | Administración de contenidos');
		
		define('WEB_PATH','https://apps.biossmann.com/');//
		define('APP_PATH','/usr/local/apache/htdocs_apps/');#
		define('CLASS_PATH',APP_PATH.'admin/class/');
		define('LIB_PATH',APP_PATH.'admin/librerias/');
		define('CONF_PATH',APP_PATH.'admin/cnf/');
		define('FILE_PATH',APP_PATH.'webfiles/pdf/');
		define('WEB_FILE_PATH',WEB_PATH.'webfiles/pdf/');
		define('TEMPLATE_PATH',APP_PATH.'admin/templatesdir/');
		define('NEWS_PATH',APP_PATH.'webimgs/noticias/');
		define('WEB_NEWS_PATH',WEB_PATH.'webimgs/noticias/');
		define('BANNER_PATH',APP_PATH.'webimgs/banners/');
		define('WEB_BANNER_PATH',WEB_PATH.'webimgs/banners/');
		
		define('PREFIJO','cms_');
		define('BORRADO',1);
		
		define('DEFAULT_DATE','2017-10-01 00:00:00');
		define('SITE','index');
		
	}
}
?>