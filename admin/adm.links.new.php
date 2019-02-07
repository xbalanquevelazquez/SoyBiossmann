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
		$_POST['res']['titulo'] = utf8_decode($_POST['res']['titulo']);
		if($myAdmin->conexion->insert(PREFIJO.'lista_links',$_POST['res'],'HTML')){

		}else{
			die("Error: al insertar registro");
		}

}else{
$grupoSel = isset($_GET['grupo'])?$_GET['grupo']:1;
?>

<h2>Nuevo link </h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="r[id]" id="r[id]" value="" />
	<tr>
		<th><label for="res[titulo]">T&iacute;tulo</label></th>
		<td><input type="text" maxlength="200" size="45" name="res[titulo]" id="res[titulo]" /></td>
	</tr>
	<tr>
		<th><label for="res[grupo]">Grupo</label></th>
		<td>
		<?php 
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_links";
		$groups =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
		#$grupoSel = $grupoSel;
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
		<td><input type="text" maxlength="200" size="45" name="res[link]" id="res[link]" /></td>
	</tr>
	<tr>
		<th><label for="res[icono]">&Iacute;cono</label></th>
		<td><select name="res[icono]" id="res[icono]">
			<?php $arrIconos = array('','externo','pdf');
			foreach($arrIconos as $ico){
			?>
			<option value="<?php echo $ico; ?>"><?php echo $ico; ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th><label for="res[posicion]">Posici&oacute;n</label></th>
		<td><input type="text" maxlength="200" size="5" name="res[posicion]" id="res[posicion]" /></td>
	</tr>
	<tr>
		<th><label for="res[selector]">Selector</label></th>
		<td><select name="res[selector]" id="res[selector]">
			<?php $arrSelectores = array('','redLink','blueLink');
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
		<td><a onclick="enviaAJAX('<?php echo WEB_PATH; ?>admin/adm.links.php')" class="btnForm">Aceptar</a><a onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.links.php','&grupo=<?php echo $grupoSel; ?>','elemContenedor','GET');" class="btnForm">Cancelar</a></td>
	</tr>
</table>
</form>
<hr />
<script type="text/javascript">
	var obj = document.getElementById('res[titulo]');
	obj.focus();
</script>
<?php } ?>
