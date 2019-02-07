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
	if($_POST['opcion']=='file'){
		$filename = validarArchivo(FILE_PATH,convertirDatoSeguro($_FILES['linkF']['name']));
		$_POST['res']['link']= "webfiles/pdf/".$filename;
		if(copy($_FILES['linkF']['tmp_name'],FILE_PATH.$filename));else die('Ocurrió un error al copiar el archivo');
	}else if($_POST['opcion']=='url'){
		$_POST['res']['link']=$_POST['linkU'];
	}
	
	/*---*/
	if(isset($_FILES['imagen']['name']) && $_FILES['imagen']['name'] != '') {
	$arrExts = array("jpg","gif","png","JPG","GIF","PNG","bmp","BMP");
	$extension = end(explode(".",$_FILES['imagen']['name']));
		if(in_array($extension,$arrExts)){
			$filename = validarArchivo(NEWS_PATH,convertirDatoSeguro($_FILES['imagen']['name']));
			if(copy($_FILES['imagen']['tmp_name'],NEWS_PATH.$filename));else die('Ocurrió un error al copiar el archivo');
			generaThumbnail($filename,NEWS_PATH);
			$_POST['res']['imagen'] = $filename;
		}else{
			//Formato de archivo no adminitdo
		}
	}
	/*---*/
	isset($_POST['res']['visible'])? $_POST['res']['visible'] = 1:$_POST['res']['visible'] = 0;
		if($myAdmin->conexion->insert(PREFIJO.'noticias',$_POST['res'],'HTML')){
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
		<th><label for="res[titulo]">T&iacute;tulo</label></th>
		<td><input type="text" size="135" name="res[titulo]" id="titulo" /></td>
	</tr>
	<tr>
		<th><label for="res[fecha]">Fecha</label></th>
		<td><input type="text" maxlength="10" size="15" name="res[fecha]" id="fecha" />
		<script type="text/javascript" src="js/browser.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>
		<script type="text/javascript">
			calendario1 = new dynCalendar({'obj':'fecha','callbackFunc':'2'});
		</script>	 
		</td>
	</tr>
	<tr>
		<th><label for="res[resumen]">Resumen</label></th>
		<td><textarea name="res[resumen]" rows="5" cols="135"></textarea>		</td>
	</tr>
	<tr>
		<th><label for="res[texto]">Texto de la nota</label></th>
		<td><textarea class="mce" name="res[texto]" rows="12" cols="135"></textarea>		</td>
	</tr>
	<tr>
		<th><label>Link</label></th>
		<td><input type="radio" name="opcion" value="url" checked="checked" onclick="change('url')" />URL <input type="radio" name="opcion" value="file" onclick="change('file')" />Archivo</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div id="contenedorUrl"><input type="text" name="linkU" value="" id="linkUrl" size="90" /></div>
			<div id="contenedorFile"><input type="file" name="linkF" value="" id="linkFile" size="90" /></div>
		</td>
	</tr>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" checked="checked" name="res[visible]" value="1" /></td>
	</tr>
	<tr>
		<th><label for="imagen">Imagen</label></th>
		<td><input type="file" name="imagen" value="" id="imagen" size="90" /></td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<script type="text/javascript">
	var obj = document.getElementById('titulo');
	obj.focus();
</script>
<script>
	var objUrl=document.getElementById('contenedorUrl');
	var objFile=document.getElementById('contenedorFile');
	function ocultarDiv(obj){
		obj.style.display='none';
	}
	function mostrarDiv(obj){
		obj.style.display='block';
	}
	function change(type){
		if(type=='url'){
			ocultarDiv(objFile);
			mostrarDiv(objUrl);
		}else if(type=='file'){
			ocultarDiv(objUrl);
			mostrarDiv(objFile);
		}
	}
	change('url');
</script>

<?php include("plantillaFoot.inc.php"); ?>