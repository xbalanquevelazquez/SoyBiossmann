<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {

#die(print_r($_POST));
/**
	kid_dato INT AUTO_INCREMENT,
	texto VARCHAR(255) NULL,
	fecha DATE NOT NULL,
	visible INT NOT NULL DEFAULT 1,
*/			
	isset($_POST['res']['visible'])? $_POST['res']['visible'] = 1:$_POST['res']['visible'] = 0;
			#$_POST['res']['fecha'] = date("Y-m-d H:i:s");
	if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		$id = $_POST['r']['id'];
		if($myAdmin->conexion->update('dato_reforma',$_POST['res'],"WHERE kid_dato=$id",'HTML')){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
	}else{
		die("Error: proporcionar id");
	}			

}
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM dato_reforma WHERE kid_dato='".$_GET['id']."'"));
$res = $res[0];
#print_r($res);
include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[fecha]','texto','"Fecha"');
//	data[1] = Array('res[texto]','texto','"Texto"');
	/*data[2] = Array('res[res_anio]','combo','"Año"');
	data[3] = Array('res[fk_clas_id]','combo','"Clasificación"');*/
</script>
<!--script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script-->
<!--script type="text/javascript">
tinyMCE.init({
	// General options
	language : 'es',
	mode : "textareas",
	theme : "advanced",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

	// Theme options
	//theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons1 : "save,newdocument,|,visualchars,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,image,cleanup,code,|,removeformat,visualaid,",
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons2 : "",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons3 : "",
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
</script-->
<form method="post" onsubmit="return validarFormulario(data);">
<table class="form" cellspacing="0">
	<input type="hidden" name="r[id]" value="<?php echo $res['kid_dato']; ?>" />
	<tr>
		<th><label for="res[fecha]">Fecha</label></th>
		<td><input type="text" maxlength="10" size="15" name="res[fecha]" id="fecha" value="<?php echo $res['fecha']; ?>" />
		<script type="text/javascript" src="js/browser.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>
		<script type="text/javascript">
			calendario1 = new dynCalendar({'obj':'fecha','callbackFunc':'2'});
		</script>	 
		</td>
	</tr>
	<tr>
		<th><label for="res[texto]">Texto</label></th>
		<td>
			<textarea name="res[texto]" rows="6" cols="45"><?php echo $res['texto']; ?></textarea>		</td>
	</tr>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" <?php echo $res['visible']==1?'checked="checked"':''; ?> name="res[visible]" value="1" />		</td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<script type="text/javascript">
	var obj = document.getElementById('fecha');
	obj.focus();
</script>
<?php } ?>
<?php include("plantillaFoot.inc.php"); ?>