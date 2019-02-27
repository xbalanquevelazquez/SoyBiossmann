<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

#include("plantilla.inc.php");

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
<script type="text/javascript">
	/*var data = Array();
	data[data.length] = Array('titulo','texto','"Título"');
	data[data.length] = Array('alias','texto','"Alias"');*/
	/*data[2] = Array('res[res_anio]','combo','"Año"');
	data[3] = Array('res[fk_clas_id]','combo','"Clasificación"');*/
</script>
<table class="layout" summary="layout" width="100%">
	<tr>
		<td valign="top">
		<div id="workArea">
		<?php #if(isset($_GET['msg'])){
		#echo  mostrarMensaje($_GET['msg']);
		#} ?>
		<form method="POST" action="<?php echo ADMIN_URL.$data1 ?>/save/new" name="formPagina" id="fckForm">
							<input type="hidden" name="nivel" value="<?php echo $nivel; ?>" />
							<input type="hidden" name="padre" value="<?php echo $padre ;?>" />
							<input type="hidden" name="orden" value="<?php echo $orden; ?>" />
							<input type="hidden" name="type" value="<?php echo $type; ?>" />
							<input type="hidden" name="oldId" value="<?php echo $id; ?>" />

			<div class="row">
				<div class="col-1"></div>
				<div class="col"></div>
			</div>
			
						<table class="layout abierto" summary="layout">
							<tr>
								<td class="label"><label for="titulo">T&iacute;tulo:</label></td>
								<td><input name="titulo" id="titulo" type="text" value="" class="form-control" /></td>
							</tr>
							<tr>
								<td class="label"><label for="alias">Alias:</label></td>
								<td><input name="alias" id="alias" type="text" value="" maxlength="40" onchange="this.value=makeIdentificador(this.value)" class="form-control" /></td>
							</tr>
							<tr>
								<td class="label"><label for="visible">Visible:</label></td>
								<td><input name="visible" id="visible" type="checkbox" checked="checked" value="1" class="makeIO" />
									<!--div class="ioCheck ioON" estatus="0">
									    <div><span>&nbsp;</span></div>
									    <span class="onLabel">ON</span>
									    <span>OFF</span>
									</div-->
								</td>
							</tr>
							<tr>
								<td class="label"><label for="publicado">Publicado:</label></td>
								<td><input name="publicado" id="publicado" type="checkbox" checked="checked" value="1" class="makeIO" /></td>
							</tr>
							<?php
							$plantillas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla WHERE estatus=1"));
							?>
							<tr>
								<td class="label"><label for="plantilla">Plantilla:</label></td>
								<td><select name="plantilla" id="plantilla" class="form-control">
								<?php 
								#print_r($plantillas);
								foreach($plantillas as $plantilla){ 
									$selected = '';
									if($plantilla['filepath'] == 'subseccion'){ $selected = 'selected="selected"'; }
									$descipcionPlantilla = utf8_encode($plantilla['descripcion']);
									echo "<option value='{$plantilla['kid_plantilla']}' id='{$plantilla['filepath']}' $selected>{$descipcionPlantilla}</option>";
								 } ?>
								</select>
								</td>
							</tr>
							<tr>
								<td class="label"><label for="descripcion">Descripci&oacute;n:</label></td>
								<td><input name="descripcion" id="descripcion" type="text" value="" maxlength="255" class="form-control" /></td>
							</tr>
							<tr>
								<td class="label"><label for="keywords">Keywords:</label></td>
								<td><input name="keywords" id="keywords" type="text" value="" maxlength="255" class="form-control" /></td>
							</tr>
                            
							<tr>
								<td colspan="2">
									<br />
									<label for="contenido">Contenido:</label>
									<textarea name="contenido" id="contenido" rows="50" class="dynamicText"></textarea>
									<div class="btnContainer fleft"><br />
                            		<input type="submit" name="btnGuardar" id="btnGuardar" value="Guardar" class="form-control btn btn-primary" />
                            		<button class="btn btn-secondary" id="btnCancelar">Cancelar</button>	
									<div class="fixed"></div>
									</div>
								</td>
							</tr>
						</table>
						</form>
		</div>
		</td>
						  <td valign="top" width="220" class="backSubTools">
					<script type="text/javascript" language="javascript" src="<?php echo ADMIN_URL ?>js/x_ajax.js"></script>
				  	<div id="imgcont"></div>
					<div class="fixed"></div>
					<div id="filecont"></div>
					<script type="text/javascript" language="javascript">
								//page          datosURL    capa   tipoEnvio)
					 ajax_getData('<?php echo APP_URL; ?>insImage.php','&do=true','imgcont','POST');
					 ajax_getData('<?php echo APP_URL; ?>insFile.php','&do=true','filecont','POST');
					</script>
				  </td>
	</tr>
</table>
<script type="text/javascript">
$(document).ready(function() {
  	$('#btnGuardar').click(function(e){
  		e.preventDefault();
  		alert(validarFormulario(data));
  		return validarFormulario(data);
  	});
  	$('#btnCancelar').click(function(e){
  		document.location.href='<?php echo CURRENT_SECCION; ?>';
  	});
});

</script>
<?php
}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}
#include("plantillaFoot.inc.php");
?>