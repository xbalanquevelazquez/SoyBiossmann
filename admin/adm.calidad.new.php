<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {
/**
CREATE TABLE noticias(
	kid_noticia INT AUTO_INCREMENT,
	titulo TEXT NOT NULL,
	resumen TEXT NULL,
	fecha DATE NOT NULL,
	link VARCHAR(255) NULL,
	visible INT NOT NULL DEFAULT 1,
*/	
#print_r($_FILES);
#	die();
		
	/*---*/
	if(isset($_FILES['imagen']['name']) && $_FILES['imagen']['name'] != '') {
	$arrExts = array("jpg","gif","png","JPG","GIF","PNG","bmp","BMP");
	$extF = explode(".",$_FILES['imagen']['name']);
	$extension = end($extF);
		if(in_array($extension,$arrExts)){
			$filename = validarArchivo(APP_LOGO_PATH,convertirDatoSeguro($_FILES['imagen']['name']));
			if(copy($_FILES['imagen']['tmp_name'],APP_LOGO_PATH.$filename));else die('Ocurrió un error al copiar el archivo');
			generaThumbnail($filename,APP_LOGO_PATH);
			$_POST['res']['imagen'] = $filename;
		}else{
			//Formato de archivo no adminitdo
		}
	}
	/*---*/
	isset($_POST['res']['activo'])? $_POST['res']['activo'] = 1:$_POST['res']['activo'] = 0;
		
		if($myAdmin->conexion->insert(PREFIJO.'calidad_empresa',$_POST['res'],'HTML')){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
}
#print_r($res);
include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[titulo]','texto','"Título"');
	data[1] = Array('res[fecha]','texto','"Fecha"');
</script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	language : 'es',
	mode : "textareas",
	theme : "advanced",
	editor_selector : "mce",	
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,visualchars,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
	theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup",
	theme_advanced_buttons3 : "link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor,|,styleselect,formatselect",
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
<form method="post" onsubmit="return validarFormulario(data);" enctype="multipart/form-data">
<table class="form" cellspacing="0">
	<tr>
		<th><label for="res[nombre_empresa]">Nombre de la empresa</label></th>
		<td><input type="text" size="40" name="res[nombre_empresa]" id="nombre_empresa" /></td>
	</tr>
	<tr>
		<th><label for="res[orden]">Orden</label></th>
		<td><input type="text" maxlength="10" size="10" name="res[orden]" id="orden" /> 
		</td>
	</tr>
	<tr>
		<th><label for="res[activo]">Activo</label></th>
		<td>
			<input type="checkbox" checked="checked" name="res[activo]" value="1" /></td>
	</tr>
	<tr>
		<th><label for="imagen">Logotipo (175 x 175 pixeles)</label></th>
		<td><input type="file" name="imagen" value="" id="imagen" size="90" /></td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<script type="text/javascript">
	var obj = document.getElementById('nombre_empresa');
	obj.focus();
</script>


<?php include("plantillaFoot.inc.php"); ?>