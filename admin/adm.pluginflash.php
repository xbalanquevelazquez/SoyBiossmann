<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
include("plantilla.inc.php");
?>
<h1>Visualizador de productos en Flash</h1>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/flash/x_ajax.js" charset="iso-8859-1"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/flash/validacion_formulario.js" charset="iso-8859-1"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/flash/enviaAJAX.js" charset="iso-8859-1"></script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/libs.js" charset="iso-8859-1"></script>
<script type="text/javascript">
	function confirmar(vars){
		var textoMensaje = 'Esta acción borrará la información, ¿desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.flash.php',vars,'elemContenedor','GET');
			//return true;
		}else{
			return false;
		}
	}
</script>
<script type="text/javascript">
	function confirmarGroup(vars){
		var textoMensaje = 'Esta acción borrará la información, ¿desea continuar?';
		if(confirm(textoMensaje)){
			ajax_getData('<?php echo WEB_PATH; ?>admin/adm.flashgroups.php',vars,'groupContenedor','GET');
			//return true;
		}else{
			return false;
		}
	}
</script>
<script type="text/javascript" src="<?php echo WEB_PATH; ?>admin/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
function prueba(){
	alert('prueba');
}
</script>
<script type="text/javascript">
inicializarMCE = function(){
	tinyMCE.init({
	// General options
	language : 'es',
	mode : "textareas",
	theme : "advanced",
	editor_selector : "mce",	
	//plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	plugins : "safari,spellchecker,style,save,contextmenu,paste,noneditable,nonbreaking",

	// Theme options
	/*theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,visualchars,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
	theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup",
	theme_advanced_buttons3 : "link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor,|,styleselect,formatselect",
	*/
	theme_advanced_buttons1 : "newdocument,|,cut,copy,pastetext,bullist,|,undo,redo,removeformat,visualaid,cleanup,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
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
}
inicializarMCE();
</script>
<script type="text/javascript">
/*alert('x');
		tinyMCE.execCommand('mceAddControl', true, 'res[lista]'); */
		</script>
<table>
	<tr>
		<td width="55%" valign="top"><div id="elemContenedor"></div></td>
		<td width="15"></td>
		<td width="35%" valign="top">
<div class="box">
<h2>Admin. grupos de banners flash </h2>
<div id="groupContenedor"></div>
</div>
</td>
	</tr>
</table>
<script type="text/javascript">
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.flash.php','','elemContenedor','GET');
</script>
<script type="text/javascript">
ajax_getData('<?php echo WEB_PATH; ?>admin/adm.flashgroups.php','','groupContenedor','GET');
</script>

<?php
include("plantillaFoot.inc.php");
?>