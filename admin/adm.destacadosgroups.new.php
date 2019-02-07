<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {

/**

ob_start() buffer oitput 
ob_get_contents()

kid_link
titulo
grupo
link
icono
selector
visible

*/			
		$id = $_POST['r']['id'];
		$_POST['res']['titulo'] = utf8_decode($_POST['res']['titulo']);

		if($myAdmin->conexion->insert(PREFIJO.'grupo_destacados',$_POST['res'],'HTML')){
		
		}else{
			die("Error: al insertar registro");
		}

}else{
#if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
#$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."grupo_destacados WHERE kid_grupo='".$_GET['id']."'"));
#$res = $res[0];
#print_r($res);
#include("../destacados/plantilla.inc.php");
?>

<h2>Nuevo grupo</h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="r[id]" id="r[id]" value="" />
	<tr>
		<th><label for="res[titulo]">Titulo</label></th>
		<td><input type="text" maxlength="200" size="35" name="res[titulo]" id="res[titulo]" /></td>
	</tr>
	<tr>
		<th><label for="res[cantidad]">Cantidad de items a mostrar</label></th>
		<td><input type="text" maxlength="2" size="5" name="res[cantidad]" id="res[cantidad]" /></td>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" checked="checked" name="res[visible]" id="res[visible]" value="1" />		</td>
	</tr>
	<tr>
		<th></th>
		<td><a onclick="enviaGrupoAJAX('<?php echo WEB_PATH; ?>admin/adm.destacadosgroups.php')" class="btnForm">Aceptar</a><a onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadosgroups.php','','groupContenedor','GET');" class="btnForm">Cancelar</a></td>
	</tr>
</table>
</form>
<hr />
<script type="text/javascript">
	var obj = document.getElementById('res[titulo]');
	obj.focus();
</script>
<?php #}
} ?>
<?php #include("../destacados/plantillaFoot.inc.php"); ?>