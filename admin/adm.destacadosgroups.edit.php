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
	if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		$id = $_POST['r']['id'];
		$_POST['res']['titulo'] = utf8_decode($_POST['res']['titulo']);
		#print_r($_POST);
		if($myAdmin->conexion->update(PREFIJO.'grupo_destacados',$_POST['res'],"WHERE kid_grupo=$id",'HTML')){
				#header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
		}else{
			die("Error: al insertar registro");
		}
	}else{
		die("Error: proporcionar id");
	}			

}else{
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."grupo_destacados WHERE kid_grupo='".$_GET['id']."'"));
$res = $res[0];
#print_r($res);
#include("../destacados/plantilla.inc.php");
?>

<h2>Editar</h2>
<hr />
<form>
<table class="form" cellspacing="0">
	<input type="hidden" id="tipo" value="edit" />
	<input type="hidden" name="r[id]" id="r[id]" value="<?php echo $res['kid_grupo']; ?>" />
	<tr>
		<th><label for="res[titulo]">Titulo</label></th>
		<td><input type="text" maxlength="200" size="35" name="res[titulo]" id="res[titulo]" value="<?php echo $res['titulo']; ?>" /></td>
	</tr>
	<tr>
		<th><label for="res[titulo]">Cantidad de items a mostrar</label></th>
		<td><input type="text" maxlength="2" size="5" name="res[cantidad]" id="res[cantidad]" value="<?php echo $res['cantidad']; ?>" /></td>
	</tr>
	<tr>
		<th><label for="res[visible]">Visible</label></th>
		<td>
			<input type="checkbox" <?php echo $res['visible']==1?'checked="checked"':''; ?> name="res[visible]" id="res[visible]" value="1" />		</td>
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
<?php }
} ?>
<?php #include("../destacados/plantillaFoot.inc.php"); ?>