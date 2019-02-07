<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {
		if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
		$id = $_POST['r']['id'];
			
			if(isset($_POST['res']['accesos'])){
				$tempacc = 0;
				foreach($_POST['res']['accesos'] as $acc){
					$tempacc += pow(2,$acc);
				}
				$_POST['res']['bit_acceso'] = $tempacc;
			}else{/*No tiene accesos*/
				$_POST['res']['bit_acceso'] = '0';
			}
			unset($_POST['res']['accesos']);
			if($myAdmin->conexion->update(PREFIJO."perfil",$_POST['res'],"WHERE kid_perfil=$id",'HTML')){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=1");
			}else{
				die("Error: al insertar registro");
			}
		}else{
			die("Error: proporcionar id");
		}	
}
	/*
CREATE TABLE ".PREFIJO."perfil(
	kid_perfil INT AUTO_INCREMENT,
	nombre VARCHAR(255) NOT NULL,
	bit_acceso INT NOT NULL,
	*/
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
$res = $myAdmin->conexion->fetch($myAdmin->conexion->query("SELECT * FROM ".PREFIJO."perfil WHERE kid_perfil='".$_GET['id']."'"));
$res = $res[0];
include("plantilla.inc.php");
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[nombre]','texto','"Nombre"');
	//data[1] = Array('res[autor]','texto','"Autor"');
	/*data[2] = Array('res[res_anio]','combo','"Año"');
	data[3] = Array('res[fk_clas_id]','combo','"Clasificación"');*/
</script>

<form method="post" enctype="multipart/form-data" onsubmit="return validarFormulario(data);">
			<?php 
			#print_r(obtenerPermisos($res['bit_acceso']));
			if($res['bit_acceso']==0){
				$permisosUsr = array();
				$mensaje = '<span style="color:#990000">Perfil de &quot;Administrador principal&quot;</span>';
			}else{
				$permisosUsr = obtenerPermisos($res['bit_acceso']);
				$mensaje = '';
			}
			 ?>
<table class="form" cellspacing="0">
	<input type="hidden" name="r[id]" value="<?php echo $res['kid_perfil']; ?>" />
	<tr>
		<th><label for="res[titulo]">Nombre</label></th>
		<td><input type="text" maxlength="150" size="45" name="res[nombre]" id="ini" value="<?php echo $res['nombre']; ?>" /></td>
	</tr>
	<tr>
		<td colspan="3"><?php echo $mensaje; ?></td>
	</tr>
	<tr>
		<th><label for="res[visible]">Accesos</label></th>
		<td>
			<table>
			<?php
			$secciones = mostrarSecciones();
			foreach($secciones as $secc){
			?>
				<tr>
					<td><input type="checkbox" name="res[accesos][]" <?php echo in_array($secc['kid_seccion'],$permisosUsr)?'checked="checked"':''; ?> value="<?php echo $secc['kid_seccion']; ?>" /></td><td><div class="imgAcceso <?php echo $secc['image']; ?>"></div> <?php echo $secc['nombre']; ?></td>
				</tr>
			<?php
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<th></th>
		<td><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<script type="text/javascript">
	var obj = document.getElementById('ini');
	obj.focus();
</script>
<?php 
	include("plantillaFoot.inc.php");
}
?>