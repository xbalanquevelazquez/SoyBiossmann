<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {

/**

ob_start() buffer oitput 
ob_get_contents()

kid_banner      | int(11)
foto            | varchar(255)
grupo           | int(11)
link            | varchar(255)
titulo          | varchar(255)
caracteristicas | text
slogan          | text
lista           | text
posicion        | int(11)
visible         | int(11)

*/			
	#if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		//$_POST['res']['alt'] = utf8_decode($_POST['res']['alt']);
		$id = $_POST['r']['id'];
		$_POST['res']['link'] = str_replace("-amp-","&",$_POST['res']['link']);
		$_POST['res']['titulo'] = htmlentities($_POST['res']['titulo']);
		$_POST['res']['link'] = htmlentities($_POST['res']['link']);
		$_POST['res']['caracteristicas'] = htmlentities($_POST['res']['caracteristicas']);
		$_POST['res']['slogan'] = htmlentities($_POST['res']['slogan']);
		/*print_r($_POST);
		die();*/
		if($myAdmin->conexion->insert(PREFIJO.'flashban',$_POST['res'],'TXT')){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
	#}else{
		#die("Error: proporcionar id");
	#}			

}else{
$grupoSel = isset($_GET['grupo'])?$_GET['grupo']:1;
include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[titulo]','texto','"Título"');
	data[1] = Array('res[caracteristicas]','texto','"Fecha"');
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
</script>
<form method="post" onsubmit="return validarFormulario(data);">
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="r[id]" id="r[id]" value="" />
	<tr>
		<th><label for="res[foto]">Imagen</label></th>
		<?php 
		$imagenes = mostrarImagenesBan('flash');
		?>		<td><select name="res[foto]" id="res[foto]">
			<?php #$arrIconos = array('','externo','pdf');
			foreach($imagenes as $img){
			?>
			<option value="<?php echo $img['nombre']; ?>"><?php echo $img['nombre']; ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="res[grupo]">Grupo</label></th>
		<td>
		<?php 
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_flashban WHERE visible=1";
		$groups =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
		#$grupoSel = $res['grupo'];
		?>
		<select name="res[grupo]" id="res[grupo]">
		<?php foreach($groups as $grupo){ ?>
		<option value="<?php echo $grupo['kid_grupo']; ?>" <?php echo $grupoSel==$grupo['kid_grupo']?'selected="selected"':'' ?>><?php echo $grupo['titulo']; ?></option>
		<?php } ?>
		</select>
		</td>
	</tr>
	<tr>
		<th><label for="res[link]">Link</label></th>
		<td><input type="text" size="45" name="res[link]" id="res[link]" onchange="this.value=protectLink(this.value);"/></td>
	</tr>
	<tr>
		<th><label for="res[titulo]">T&iacute;tulo</label></th>
		<td><input type="text" maxlength="250" size="45" name="res[titulo]" id="res[titulo]" /></td>
	</tr>
	<tr>
		<th><label for="res[caracteristicas]">Caracter&iacute;sticas</label></th>
		<td><!--input type="text" maxlength="250" size="45" name="res[caracteristicas]" id="res[caracteristicas]" /-->
        <textarea name="res[caracteristicas]" id="res[caracteristicas]" cols="40" rows="4"></textarea>
        </td>
	</tr>
	<tr>
		<th><label for="res[slogan]">Slogan</label></th>
		<td><input type="text" maxlength="250" size="45" name="res[slogan]" id="res[slogan]" /></td>
	</tr>
	<tr>
		<th><label for="res[lista]">Lista</label></th>
		<td><!--input type="text" maxlength="250" size="45" name="res[lista]" id="res[lista]" /-->
        <textarea name="res[lista]" id="res[lista]" cols="40" rows="4" class="mce"></textarea>
        
        </td>
	</tr>
	<tr>
		<th><label for="res[posicion]">Posici&oacute;n</label></th>
		<td><input type="text" maxlength="200" size="5" name="res[posicion]" id="res[posicion]" /></td>
	</tr>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" checked="checked" name="res[visible]" id="res[visible]" value="1" />		</td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<hr />
<script type="text/javascript">
	var obj = document.getElementById('res[foto]');
	obj.focus();
</script>

<?php 
} ?>
<?php include("plantillaFoot.inc.php"); ?>