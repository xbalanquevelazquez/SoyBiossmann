<?php
define('VIEWABLE',TRUE);
include_once("admin/cnf/configuracion.cnf.php");
include_once(CLASS_PATH."page.class.php");
$page = new Page($page);
$arrExcept = array('index');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php include_once(APP_PATH."includes.inc.php"); ?>
</head>
<body>
<?php include_once(APP_PATH."header.inc.php"); ?>
	<section id="mainContainer" class="container-fluid">
<?php
#INCLUIR PLANTILLA
$plantilla_filepath = 'home';
if(isset($page->pageData['plantilla_filepath']) && $page->pageData['plantilla_filepath'] != '' && file_exists(APP_PATH.'plantilla.'.$page->pageData['plantilla_filepath'].'.php')){
	$plantilla_filepath = $page->pageData['plantilla_filepath'];
}
include_once(APP_PATH.'plantilla.'.$plantilla_filepath.'.php');

include_once(APP_PATH."footer.inc.php");
?>
	</section><!--//#mainContainer-->
	<script type="text/javascript">
	$(document).ready(function(){
		console.log('SITE ready!');
	});
	</script>
</body>
</html>