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

		if($myAdmin->conexion->insert(PREFIJO.'grupo_banners',$_POST['res'],'HTML')){
		
		}else{
			die("Error: al insertar registro");
		}

}else{
#if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
#$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."grupo_banners WHERE kid_grupo='".$_GET['id']."'"));
#$res = $res[0];
#print_r($res);
#include("../banners/plantilla.inc.php");
?>

<h2>Nuevo grupo</h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="r[id]" id="r[id]" value="" />
	<tr>
		<th><label for="res[identificador]">Identificador</label></th>
		<td><input type="text" maxlength="200" size="35" name="res[identificador]" id="res[identificador]" onchange="this.value=makeIdentificador(this.value)" /></td>
	</tr>
	<tr>
		<th><label for="res[titulo]">Titulo</label></th>
		<td><input type="text" maxlength="200" size="35" name="res[titulo]" id="res[titulo]" /></td>
	</tr>
	<tr>
		<th><label for="res[selector]">Selector</label></th>
		<td><select name="res[selector]" id="res[selector]">
			<?php $arrSelectores = array('','gris','azul');
			foreach($arrSelectores as $sel){
			?>
			<option value="<?php echo $sel; ?>"><?php echo $sel; ?></option>
			<?php } ?>
			</select>
	</tr>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" checked="checked" name="res[visible]" id="res[visible]" value="1" />		</td>
	</tr>
	<tr>
		<th></th>
		<td><a onclick="enviaGrupoAJAX('<?php echo WEB_PATH; ?>admin/adm.bannergroups.php')" class="btnForm">Aceptar</a><a onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.bannergroups.php','','groupContenedor','GET');" class="btnForm">Cancelar</a></td>
	</tr>
</table>
</form>
<hr />
<script type="text/javascript">
	var obj = document.getElementById('res[identificador]');
	obj.focus();
</script>
<?php #}
} ?>
<?php #include("../banners/plantillaFoot.inc.php"); ?>