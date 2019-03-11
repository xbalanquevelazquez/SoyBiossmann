<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pager = (isset($data2) && $data2 == 'pag')?'/pag/'.$data3:'';
$estructura = new Estructura();
$sites = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE nivel=0 ORDER BY kid_pagina"));

$getPage = '';
switch($data2){
	case 'new':
	case 'newsite':
	case 'newpage':
	case 'edit':
	case 'save':
	case 'delete':
	case 'cont':
		//MISMA DEFINICION DE PAGINA
		$getPage = $data2;
		break;

	case 'activar':
	case 'desactivar':
	case 'visible':
	case 'invisible':
	case 'up':
	case 'down':
		$getPage = 'change';
		break;
}
if($getPage!=''){
	include_once("adm.$data1.$getPage.php");
}else{
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
?>
<script type="text/javascript" src="<?php echo ADMIN_URL; ?>js/controlEstructura.js?<?php echo rand(10, 15); ?>"></script>
<div class="espaciador"></div>
<div class="fixed espaciador"></div>
<div class="row">
	<div class="col-1 subMenu" id="subMenu">
		<a href="<?php echo ADMIN_URL.$data1 ?>/newsite" class="btn btn-primary transicion"><i class="fa fa-globe-americas"></i> Crear Nuevo Sitio Web</a>
		<?php if(count($sites)!=0){ ?>
		<a href="<?php echo ADMIN_URL.$data1 ?>/newpage" class="btn btn-primary transicion"><i class="fas fa-file"></i> Crear p&aacute;gina web</a>
		<?php } ?>
	</div>
	<div class="col" id="workArea">
		<?php 
		foreach($sites as $site){
			$id = $site['kid_pagina'];
			
			$resultado = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."view_estructura where kid_pagina={$id} OR iniPadre={$id} OR lvl1={$id} OR lvl2={$id} OR lvl3={$id} OR lvl4={$id} OR lvl5={$id} OR lvl6={$id} OR lvl7={$id} OR lvl8={$id} OR lvl9={$id} OR lvl10={$id}"));
		?>
		<div class="mapsite"><?php $estructura->crearEstructura($resultado); ?></div>
		<?php }	?>
</div><!--//END: col contenido estructura -->
</div>
<div class="resultBoxRegistros"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('body').keypress(function(e){
		    alert(e.which);
		    if(e.which == 27){
		        // Close my modal window
		    }
		});
	});
</script>
<?php
}
?>