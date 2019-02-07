<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {

/**

ob_start() buffer oitput 
ob_get_contents()

kid_destacados
titulo
grupo
link
icono
selector
visible

*/			
	#if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		$_POST['res']['titulo'] = utf8_decode($_POST['res']['titulo']);
		$_POST['res']['link'] = str_replace("-amp-","&",$_POST['res']['link']);
		$id = $_POST['r']['id'];
		if($myAdmin->conexion->insert(PREFIJO.'destacados',$_POST['res'],'HTML')){
				#header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
	#}else{
		#die("Error: proporcionar id");
	#}			

}else{
#if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$grupoSel = isset($_GET['grupo'])?$_GET['grupo']:1;
#print_r($res);
#include("../destacados/plantilla.inc.php");
?>

<h2>Nuevo</h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="new" />
	<input type="hidden" name="r[id]" id="r[id]" value="" />
	<tr>
		<th><label for="res[img]">Imagen</label></th>
		<?php 
		$imagenes = mostrarImagenesBan('destacadas');
		?>		<td><select name="res[img]" id="res[img]">
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
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_destacados";
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
		<th><label for="res[titulo]">Título</label></th>
		<td><input type="text" maxlength="200" size="45" name="res[titulo]" id="res[titulo]" /></td>
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
		<td><a onclick="enviaAJAX('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php')" class="btnForm">Aceptar</a><a onclick="ajax_getData('<?php echo WEB_PATH; ?>admin/adm.destacadositem.php','&grupo=<?php echo $grupoSel; ?>','elemContenedor','GET');" class="btnForm">Cancelar</a></td>
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