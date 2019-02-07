<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

include("plantilla.inc.php");

if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$id = $_GET['id'];
$resultado = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT * FROM ".PREFIJO."estructura WHERE kid_pagina=".$id)	);
$resultado = $resultado[0];

$plantilla = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla WHERE kid_plantilla=".$resultado['plantilla'])	);
$plantilla = $plantilla[0];
$plantillaUbicacion = $plantilla['filepath'];
$fileTemplate = $plantilla['filepath'].".tpl";
$plantillaName = $plantilla['nombre'];/**/


$contenido = $myAdmin->conexion->fetch(	$myAdmin->conexion->query("SELECT * FROM ".PREFIJO."contenido WHERE fid_estructura=".$id)	);
$contenido = $contenido[0];

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
						<?php if($resultado['visible']==1){	?>
						<a href="?est&change&invisible&fast&id=<?php echo $id ?>" class="btnVisible" title="Hacer invisible"></a>
						<?php }else{ ?>
						<a href="?est&change&visible&fast&id=<?php echo $id ?>" class="btnInvisible" title="Hacer visible"></a>
						<?php }  if($resultado['publicado']==1){ ?>
						<a href="?est&change&desactivar&fast&id=<?php echo $id ?>" class="btnActivo" title="Desactivar"></a>
						<?php }else{ ?>
						<a href="?est&change&activar&fast&id=<?php echo $id ?>" class="btnInactivo" title="Activar"></a>
						<?php }?>
						
						<div class="fixed"></div>
					</div>
		</td>
		<td valign="top">
		<div id="workArea">
		<?php if(isset($_GET['msg'])){
		echo  mostrarMensaje($_GET['msg']);
		} ?>
		<form method="POST" action="?est&save" name="formPagina" onsubmit="return validarFormulario(data);">
						<input name="id" type="hidden" value="<?php echo $resultado['kid_pagina']; ?>" />
						<table class="layout abierto" summary="layout">
							<tr>
								<td class="label"><label for="">T&iacute;tulo:</label></td>
								<td><input name="titulo" id="titulo" type="text" value="<?php echo $resultado['nombre']; ?>" size="43" /></td>
							</tr>
							<tr>
								<td class="label"><label for="">Alias:</label></td>
								<td><input name="alias" id="alias" type="text" maxlength="40" value="<?php echo $resultado['alias']; ?>" size="43" onblur="this.value=makeIdentificador(this.value)" /></td>
							</tr>
							<?php
							$plantillas = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."plantilla"));
							?>
							<tr>
								<td class="label"><label for="">Plantilla:</label></td>
							  <td><select name="plantilla">
								<?php foreach($plantillas as $plantilla){ ?>
								<option value="<?php echo $plantilla['kid_plantilla']; ?>" <?php echo $resultado['plantilla']==$plantilla['kid_plantilla']?"selected='selected'":""; ?> id="<?php echo $plantilla['filepath']; ?>&id=<?php echo $_GET['id'] ?>"><?php echo $plantilla['descripcion']; ?></option>
								<?php } ?>
								</select> [Original: <?php echo $plantillaName ?>]		
							  </td>
							</tr>
							<tr>
								<td class="label"><label for="descripcion">Descripción:</label></td>
								<td><input name="descripcion" type="text" value="<?php echo $resultado['descripcion']; ?>" size="80" maxlength="255" /></td>
							</tr>
							<tr>
								<td class="label"><label for="keywords">Keywords:</label></td>
								<td><input name="keywords" type="text" value="<?php echo $resultado['keywords']; ?>" size="80" maxlength="255" /></td>
							</tr>
                            
							<tr>
								<td colspan="2">
						<br />
						<label>Contenido:</label>
							<textarea name="contenido" rows="50" class="dynamicText">
							<?php echo $contenido['contenido']; ?>
							</textarea>
                            <br />
                            <input type="image" src="img/guardar.gif" />
								<div class="btnContainer fleft">
						
						<div class="fixed"></div>
						</div>
								</td>
							</tr>
						</table>
						
						</form>
		
		</div>
		</td>
						  <td valign="top" width="250" class="backSubTools">
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
}else{
	echo "<div class='aviso'>No indicó la página a editar</div>";
}
include("plantillaFoot.inc.php");
?>