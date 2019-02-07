<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

include("plantilla.inc.php");

$sites = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE nivel=0")	);
$siteTarget=isset($_GET['site'])?$_GET['site']:'';
/*echo "<pre>";
print_r($arrProcesados);
echo "</pre>";*/

?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	language : 'es',
	mode : "textareas",
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
	content_css : "css/contenido.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});
</script>
<script src="js/validacion_formulario.js" type="text/javascript"></script>
<script type="text/javascript">
	var data = Array();
	data[data.length] = Array('titulo','texto','"Título"');
	data[data.length] = Array('alias','texto','"Alias"');
	/*data[2] = Array('res[res_anio]','combo','"Año"');
	data[3] = Array('res[fk_clas_id]','combo','"Clasificación"');*/
</script>
<table class="layout" summary="layout" width="100%">
	<tr>
		<td id="botonesVert">
			<div class="botones">
						<a href="?est"><img src="img/ico/cancel.gif" border="0" alt="Cancelar" /></a>						
						<div class="fixed"></div>
					</div>
		</td>
		<td valign="top">
		<div id="workArea">
		<?php if(isset($_GET['msg'])){
		echo  mostrarMensaje($_GET['msg']);
		} ?>
		<form method="POST" action="?est&save&new" name="formPagina" onsubmit="return validarFormulario(data);">
						<table class="layout abierto" summary="layout">
							<input type="hidden" name="nivel" value="1" />
							<input name="newpage" type="hidden" value="1">
							<tr>
								<td><label for="sitio">Sitio:</label></td>
								<td>
								<select name="padre">
								<?php foreach($sites as $site) {?>
								<option value="<?php echo $site['kid_pagina'] ?>" <?php echo $siteTarget==$site['kid_pagina']?'selected="selected"':''; ?>><?php echo $site['nombre'] ?></option>
								<?php } ?>
								</select>
								</td>
							</tr>
							<tr>
								<td class="label"><label for="">Título:</label></td>
								<td><input name="titulo" id="titulo" type="text" value="" size="43" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Alias:</label></td>
								<td><input name="alias" id="alias" type="text" value="" size="43" maxlength="40" onchange="this.value=makeIdentificador(this.value)" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Visible:</label></td>
								<td><input name="visible" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Publicado:</label></td>
								<td><input name="publicado" type="checkbox" checked="checked" value="1" /></td>
							</tr>
							<?php
							$plantillas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla"));
							?>
							<tr>
								<td class="label"><label for="">Plantilla:</label></td>
								<td><select name="plantilla">
								<?php foreach($plantillas as $plantilla){ ?>
								<option value="<?php echo $plantilla['kid_plantilla']; ?>" id="<?php echo $plantilla['filepath']; ?>"><?php echo $plantilla['descripcion']; ?></option>
								<?php } ?>
								</select>
								</td>
							</tr>
							<tr>
								<td class="label"><label for="descripcion">Descripción:</label></td>
								<td><input name="descripcion" type="text" value="" size="80" maxlength="255" /></td>
							</tr>
							<tr>
								<td class="label"><label for="keywords">Keywords:</label></td>
								<td><input name="keywords" type="text" value="" size="80" maxlength="255" /></td>
							</tr>
                            
							<tr>
								<td colspan="2">
						<br />
						<label>Contenido:</label>
							<textarea name="contenido" rows="50" class="dynamicText">
							
							</textarea>


								<div class="btnContainer fleft">
						                            <br />
                            <input type="image" src="img/guardar.gif" />

						<div class="fixed"></div>
						</div>
								</td>
							</tr>
						</table>
						</form>
		</div>
		</td>
						  <td valign="top" width="220" class="backSubTools">
					<script type="text/javascript" language="javascript" src="js/x_ajax.js"></script>
				  	<div id="imgcont"></div>
					<div class="fixed"></div>
					<div id="filecont"></div>
					<script type="text/javascript" language="javascript">
								//page          datosURL    capa   tipoEnvio)
					  ajax_getData('<?php echo WEB_PATH; ?>insImage.php','&do=true','imgcont','POST');
					  ajax_getData('<?php echo WEB_PATH; ?>insFile.php','&do=true','filecont','POST');
					</script>
				  </td>
	</tr>
</table>
<?php
/*}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}*/
include("plantillaFoot.inc.php");
?>