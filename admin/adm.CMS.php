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
/*if(		$data2=='new'){ 	include_once("adm.$data1.new.php");}
else if($data2=='edit'){ 	include_once("adm.$data1.edit.php");}
else if($data2=='delete'){ 	include_once("adm.$data1.delete.php");}
else if($data2=='fast'){ 	include_once("adm.$data1.fast.php");}
else if($data2=='concluir'){ 	include_once("adm.$data1.concluir.php");}
else if($data2=='view'){ 	include_once("adm.$data1.view.php");}
else if($data2=='desactivar'){ 	include_once("adm.$data1.change.php");}
else if($data2=='activar'){ 	include_once("adm.$data1.change.php");}
else if($data2=='visible'){ 	include_once("adm.$data1.change.php");}
else if($data2=='invisible'){ 	include_once("adm.$data1.change.php");}
else if($data2=='up'){ 	include_once("adm.$data1.change.php");}
else if($data2=='down'){ 	include_once("adm.$data1.change.php");*/
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
		/*$('#terminoBusqueda').keyup(function(){
			var currentValue = $(this).val();
			currentValue = currentValue.trim();
			if(currentValue != ''){
				getRegistros(currentValue);
			}else{
				$(".resultBoxRegistros").html('');
			}
			
		});
		$('#btnSearch').click(function(){
			var currentValue = $('#terminoBusqueda').val();
			if(currentValue != ''){
				getRegistros(currentValue);
			}else{
				$(".resultBoxRegistros").html('');
			}
		});
		$('[data-toggle="tooltip"]').tooltip();*/
	});
	function getRegistros(data,type='CC'){
		/*$(".resultBoxRegistros").html("<div class='bg-info'>Obteniendo resultados...</div>");
		var envioData = new FormData();
		envioData.append("action",'getRegistros');													
		envioData.append("id",data);
		envioData.append("type",type);
		$.ajax({
			url: "<?php echo ADMIN_URL; ?>webservices/acciones.php",
			type:"POST",
			processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
			contentType: false,
			data:envioData,
			cache:false,
			dataType:"json",
			success: function(respuesta){
				var texto = '';
				if(respuesta.success){
					$(".resultBoxRegistros").html('');
					texto = '';
				}else{
					texto = "<div class='bg-warning'>"+respuesta.error+"</div>";
				}
				$(".messageBox").html(texto);
				$(".resultBoxRegistros").html(respuesta.data.codigo);
				$('[data-toggle="tooltip"]').tooltip();
			}
		});*/
	}
</script>
<?php

}
?>