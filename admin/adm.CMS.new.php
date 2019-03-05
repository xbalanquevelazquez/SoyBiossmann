<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

$type = $data3;
$id  = $data4;
if($id != '' && is_numeric($id) && $id>0){
$resultado = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE kid_pagina=".$id)	);
$resultado = $resultado[0];
/*echo "<pre>";
print_r($resultado);
echo "</pre>";*/

switch($type){
	case 'inf':
		/* echo 'inf'; */
		$nivel = $resultado['nivel'];
		$padre = $resultado['padre'];
		$orden = $resultado['orden'];
		/*SUMAR A LOS DEMAS hacia arriba +1 a orden (incluyendo el actual)*/
		break;
	case 'sup':
		/*echo 'sup';*/
		$nivel = $resultado['nivel'];
		$padre = $resultado['padre'];
		$orden = $resultado['orden']+1;
		/*SUMAR A LOS DEMAS hacia arriba +1 a orden (excluyendo el actual)*/
		break;
	case 'child':
		/*echo 'child';*/
		$nivel = $resultado['nivel']+1;
		$padre = $resultado['kid_pagina'];
		/*query para saber que orden le toca (debe ser el máximo +1*/
		$totalChilds = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT MAX(orden) as max FROM ".PREFIJO."estructura WHERE padre=".$padre)	);
		$orden = $totalChilds[0]['max']+1;
		#!!!!!!!!!!!!!!$orden = $resultado['orden']+1;
		break;
}
?>
<script type="text/javascript" src="<?php echo ADMIN_URL ?>js/tinymce5/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_URL ?>js/tinymce5/jquery.tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
   	selector: 'textarea',
   	skin: "oxide-dark",
   	width: '100%',
    height: 450,
    //images_upload_url: '<?php echo ADMIN_URL ?>webservice/acciones.php',
   // automatic_uploads: true,
    images_upload_handler: function (blobInfo, success, failure) {
   			upload_with_tinymce(blobInfo, success, failure, '<?php echo APP_URL ?>webservice/acciones.php');
  },
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table directionality emoticons template paste'
    ],
   	//contextmenu: "link image imagetools table spellchecker",
   //	toolbar: "newdocument bold italic underline strikethrough alignleft aligncenter alignright alignjustify styleselect formatselect 	fontselect 	fontsizeselect cut copy paste bullist numlist outdent indent blockquote undo redo removeformat subscript superscript"
	toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor'
  
});
</script>
<script src="<?php echo ADMIN_URL ?>js/validacion_formulario.js" type="text/javascript"></script>
<table class="layout" summary="layout" width="100%">
	<tr>
		<td valign="top">
		<div id="workArea">
		<?php #if(isset($_GET['msg'])){
		#echo  mostrarMensaje($_GET['msg']);
		#} ?>
		<form method="POST" action="<?php echo CURRENT_SECCION; ?>save/new" name="formPagina">
			<input type="hidden" name="nivel" value="<?php echo $nivel; ?>" />
			<input type="hidden" name="padre" value="<?php echo $padre ;?>" />
			<input type="hidden" name="orden" value="<?php echo $orden; ?>" />
			<input type="hidden" name="type" value="<?php echo $type; ?>" />
			<input type="hidden" name="oldId" value="<?php echo $id; ?>" />

			<div class="row">
				<div class="col-1 label"><label for="titulo">Título:</label></div>
				<div class="col"><input name="titulo" id="titulo" type="text" value="" class="form-control setObligatorio" data-validation='texto' /></div>
			</div>
			<div class="row">
				<div class="col-1 label"><label for="alias">Alias:</label></div>
				<div class="col"><input name="alias" id="alias" type="text" value="" maxlength="40" class="form-control setObligatorio" data-validation='texto' /></div>
			</div>
			<div class="row">
				<div class="col-1 label"><label for="visible">Visible:</label></div>
				<div class="col"><input name="visible" id="visible" type="checkbox" checked="checked" value="1" class="makeIO" /></div>
			</div>
			<div class="row">
				<div class="col-1 label"><label for="publicado">Publicado:</label></div>
				<div class="col"><input name="publicado" id="publicado" type="checkbox" checked="checked" value="1" class="makeIO" /></div>
			</div>
			<?php
			$plantillas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla WHERE estatus=1"));
			?>
			<div class="row">
				<div class="col-1 label"><label for="plantilla">Plantilla:</label></div>
				<div class="col"><select name="plantilla" id="plantilla" class="form-control">
								<?php 
								#print_r($plantillas);
								foreach($plantillas as $plantilla){ 
									$selected = '';
									if($plantilla['filepath'] == 'subseccion'){ $selected = 'selected="selected"'; }
									$descipcionPlantilla = $plantilla['descripcion'];
									echo "<option value='{$plantilla['kid_plantilla']}' id='{$plantilla['filepath']}' $selected>{$descipcionPlantilla}</option>";
								 } ?>
								</select></div>
			</div>
			<div class="row">
				<div class="col-1 label"><label for="descripcion">Descripci&oacute;n:</label></div>
				<div class="col"><input name="descripcion" id="descripcion" type="text" value="" maxlength="255" class="form-control" /></div>
			</div>
			<div class="row">
				<div class="col-1 label"><label for="keywords">Keywords:</label></div>
				<div class="col"><input name="keywords" id="keywords" type="text" value="" maxlength="255" class="form-control" /></div>
			</div>
			<div class="row">
				<div class="col"><label for="contenido">Contenido:</label>
									<textarea name="contenido" id="contenido" rows="50" class="dynamicText"></textarea></div>
			</div>
			<div class="row">
				<div class="col-1"><input type="submit" name="btnGuardar" id="btnGuardar" value="Guardar" class="form-control btn btn-primary" /></div>
				<div class="col-1"><button class="btn btn-secondary" id="btnCancelar">Cancelar</button>	</div>
			</div>
		</form>
		</div>
		</td>
						  <!--td valign="top" width="220" class="backSubTools">
					<script type="text/javascript" language="javascript" src="<?php echo ADMIN_URL ?>js/x_ajax.js"></script>
				  	<div id="imgcont"></div>
					<div class="fixed"></div>
					<div id="filecont"></div>
					<script type="text/javascript" language="javascript">
								//page          datosURL    capa   tipoEnvio)
					 //ajax_getData('<?php echo APP_URL; ?>insImage.php','&do=true','imgcont','POST');
					 //ajax_getData('<?php echo APP_URL; ?>insFile.php','&do=true','filecont','POST');
					</script>
				  </td-->
	</tr>
</table>
<script type="text/javascript">
$(document).ready(function() {
  	$('#btnGuardar').click(function(e){
  		e.preventDefault();
  		if(validarFormulario(formCampos)){//obtengo el array de ejecutar la opcion .obligatorio en app.js
  			$('form[name=formPagina]').submit();
  		}
  	});
  	$('#btnCancelar').click(function(e){
  		document.location.href='<?php echo CURRENT_SECCION; ?>';
  	});
  	$('#titulo').on('keyup',function(e) {
  		$('#alias').val(makeIdentificador($('#titulo').val()));
  	});
  	$('#titulo').on('blur',function(e) {
  		$('#alias').val(makeIdentificador($('#titulo').val()));
  	});
});
</script>
<?php
}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}
?>