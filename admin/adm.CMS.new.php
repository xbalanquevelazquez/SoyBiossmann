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
      'save table contextmenu directionality emoticons template paste textcolor'
    ],
   	//contextmenu: "link image imagetools table spellchecker",
   //	toolbar: "newdocument bold italic underline strikethrough alignleft aligncenter alignright alignjustify styleselect formatselect 	fontselect 	fontsizeselect cut copy paste bullist numlist outdent indent blockquote undo redo removeformat subscript superscript"
toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
  
});
/* tinymce.init({
    selector: '#contenido'
  });	
tinyMCE.init({
	// General options
	language : 'es',
	
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

	// Theme options
	//theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,visualchars",
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
	theme_advanced_buttons3 : "link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor,|,media",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup",
	//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	//theme_advanced_buttons4 : 
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Example content CSS (should be your site CSS)
	content_css : "<?php echo ADMIN_URL ?>css/contenido.css",

	// Drop lists for link/image/media/template dialogs
	/*template_external_list_url : "<?php echo ADMIN_URL ?>js/template_list.js",
	external_link_list_url : "<?php echo ADMIN_URL ?>js/link_list.js",
	external_image_list_url : "<?php echo ADMIN_URL ?>js/image_list.js",
	media_external_list_url : "<?php echo ADMIN_URL ?>js/media_list.js",* /

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});*/
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
		<td id="botonesVert">
			<div class="botones">
						<a href="?est"><img src="<?php echo ADMIN_URL ?>img/ico/cancel.gif" border="0" alt="Cancelar" /></a>						
						<div class="fixed"></div>
					</div>
		</td>
		<td valign="top">
		<div id="workArea">
		<?php #if(isset($_GET['msg'])){
		#echo  mostrarMensaje($_GET['msg']);
		#} ?>
		<form method="POST" action="<?php echo ADMIN_URL.$data1 ?>/save/new" name="formPagina" onsubmit="return validarFormulario(data);" id="fckForm">
							<input type="hidden" name="nivel" value="<?php echo $nivel; ?>" />
							<input type="hidden" name="padre" value="<?php echo $padre ;?>" />
							<input type="hidden" name="orden" value="<?php echo $orden; ?>" />
							<input type="hidden" name="type" value="<?php echo $type; ?>" />
							<input type="hidden" name="oldId" value="<?php echo $id; ?>" />
						<table class="layout abierto" summary="layout">
							<tr>
								<td class="label"><label for="titulo">T&iacute;tulo:</label></td>
								<td><input name="titulo" id="titulo" type="text" value="" size="43" /></td>
							</tr>
							<tr>
								<td class="label"><label for="alias">Alias:</label></td>
								<td><input name="alias" id="alias" type="text" value="" size="43" maxlength="40" onchange="this.value=makeIdentificador(this.value)" /></td>
							</tr>
							<tr>
								<td class="label"><label for="visible">Visible:</label></td>
								<td><input name="visible" id="visible" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<tr>
								<td class="label"><label for="publicado">Publicado:</label></td>
								<td><input name="publicado" id="publicado" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<?php
							$plantillas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla WHERE estatus=1"));
							?>
							<tr>
								<td class="label"><label for="plantilla">Plantilla:</label></td>
								<td><select name="plantilla" id="plantilla">
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
								<td><input name="descripcion" id="descripcion" type="text" value="" size="80" maxlength="255" /></td>
							</tr>
							<tr>
								<td class="label"><label for="keywords">Keywords:</label></td>
								<td><input name="keywords" id="keywords" type="text" value="" size="80" maxlength="255" /></td>
							</tr>
                            
							<tr>
								<td colspan="2">
									<br />
									<label for="contenido">Contenido:</label>
									<textarea name="contenido" id="contenido" rows="50" class="dynamicText"></textarea>
									<div class="btnContainer fleft"><br />
                            		<input type="submit" name="btnGuardar" id="btnGuardar" value="Guardar" class="form-control btn btn-primary" />
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
$().ready(function() {
   	$('textarea#contenido').tinymce({
      	script_url : '<?php echo ADMIN_URL ?>js/tinymce5/jquery.tinymce.min.js',
      	theme : "advanced"
      	
 	});
});

</script>
<?php
}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}
#include("plantillaFoot.inc.php");
?>