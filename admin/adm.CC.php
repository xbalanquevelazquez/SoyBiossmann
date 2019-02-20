<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
$pager = (isset($data2) && $data2 == 'pag')?'/pag/'.$data3:'';

if(		$data2=='new'){ 	include_once("adm.$data1.new.php");}
else if($data2=='edit'){ 	include_once("adm.$data1.edit.php");}
else if($data2=='delete'){ 	include_once("adm.$data1.delete.php");}
else if($data2=='fast'){ 	include_once("adm.$data1.fast.php");}
else if($data2=='concluir'){ 	include_once("adm.$data1.concluir.php");}
else if($data2=='view'){ 	include_once("adm.$data1.view.php");}
else{
	if(isset($_GET['msg'])) echo mostrarMensaje($_GET['msg']);
?>
<div class="espaciador"></div>
<a href="<?php echo APP_URL.$data1 ?>/new<?php echo $pager ?>" class="btn btn-primary"><i class="fa fa-user-plus"></i> &nbsp;Nuevo registro</a>
<div class="fixed espaciador"></div>
<div class="form-row">
	<div class="col-2">
		<div class="form-group">
			<input type="text" class="form-control" id="terminoBusqueda">
		</div>
	</div>
	<div class="col2">
		<div class="form-group">
			<button class="btn btn-primary" id="btnSearch"><i class="fa fa-search"></i> &nbsp;Buscar registro</button>
		</div>
	</div>
</div>

<div class="resultBoxRegistros"></div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#terminoBusqueda').keyup(function(){
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
		$('[data-toggle="tooltip"]').tooltip();
	});
	function getRegistros(data,type='CC'){
		$(".resultBoxRegistros").html("<div class='bg-info'>Obteniendo resultados...</div>");
		var envioData = new FormData();
		envioData.append("action",'getRegistros');													
		envioData.append("id",data);
		envioData.append("type",type);
		$.ajax({
			url: "<?php echo APP_URL; ?>webservices/acciones.php",
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
		});
	}
</script>
<?php

}
?>