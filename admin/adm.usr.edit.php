<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['res'])) {
	if($_POST['res']['usr_psw'] == $_POST['re_usr_psw']){
		$psw_campo = '';
		$usr_psw = '';
		
		isset($_POST['res']['usr_activo'])? $_POST['res']['usr_activo'] = 1:$_POST['res']['usr_activo'] = 0;
		
		$usr_login = $_POST['res']['usr_login'];
		if($_POST['res']['usr_psw'] != ''){ $psw_campo = ",usr_psw=AES_ENCRYPT('".$_POST['res']['usr_psw']."',".AES_ENCRYPT.")"; }
		$usr_nombre = $_POST['res']['usr_nombre'];
		$usr_activo = $_POST['res']['usr_activo'];
		$usr_perfil = $_POST['res']['usr_perfil'];
		if(isset($_POST['r']['id']) && is_numeric($_POST['r']['id'])){
			$id = $_POST['r']['id'];
			$query = "UPDATE ".PREFIJO."usuarios SET usr_login='$usr_login',usr_nombre='$usr_nombre',usr_activo='$usr_activo',usr_perfil=$usr_perfil{$psw_campo} WHERE usr_id=$id";
			if($myAdmin->conexion->query($query)){
				header("Location: ".$_SERVER['PHP_SELF']."?$page$pager&msg=5");
			}else{
				die("Error: al insertar registro");
			}
		}else{
			die("Error: proporcionar id");
		}
	}	else{
		die("la contraseña no es igual en los dos campos");
	}	
}
/*
usr_id     | int(11)
usr_login  | varchar(50)
usr_psw    | blob
usr_nombre | varchar(255)
*/
include("plantilla.inc.php");
$usr = $myAdmin->obtenerUsuario($_GET['id']);
$usr = $usr[0];
?>
<script type="text/javascript" src="js/validacion_formulario.js"></script>
<script type="text/javascript">
	var data = Array();
	data[0] = Array('res[usr_login]','texto','"Usuario"');
	data[1] = Array('res[usr_nombre]','texto','"Nombre"');
</script>
<form method="post" onsubmit="return validarFormulario(data);">
<table class="form" cellspacing="0">
	<tr>
		<th><label for="res[usr_login]">Usuario</label></th>
		<td><input type="text" maxlength="150" size="45" name="res[usr_login]" value="<?php echo $usr['usr_login']; ?>" /></td>
	</tr>
	<tr>
		<th><label for="res[usr_psw]">Password</label></th>
		<td><input type="password" maxlength="150" size="45" name="res[usr_psw]" value="" /></td>
	</tr>
	<tr>
		<th><label for="re_usr_psw">Repetir password</label></th>
		<td><input type="password" maxlength="150" size="45" name="re_usr_psw" /><br /><span class="nota">Si no desea cambiar la contrase&ntilde;a deje los campos vac&iacute;os</span></td>
	</tr>
	<tr>
		<th><label for="res[usr_nombre]">Nombre</label></th>
		<td><input type="text" maxlength="150" size="45" name="res[usr_nombre]" value="<?php echo $usr['usr_nombre']; ?>" /></td>
	</tr>
	<tr>
		<th><label for="res[usr_activo]">Activo</label></th>
		<td><input type="checkbox" name="res[usr_activo]"  <?php echo $usr['usr_activo']==1?'checked="checked"':''; ?> /></td>
	</tr>
	<tr>
		<th><label for="res[usr_perfil]">Perfil</label></th>
		<td>
		<select name="res[usr_perfil]">
		<?php
		$queryT = "SELECT * FROM ".PREFIJO."perfil";
		$perfiles =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
		foreach($perfiles as $perf){
		?>
		<option value="<?php echo $perf['kid_perfil']; ?>" <?php echo $usr['usr_perfil']==$perf['kid_perfil']?'selected="selected"':''; ?>><?php echo $perf['nombre']; ?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th></th>
		<td><input type="hidden" value="<?php echo $_GET['id']; ?>" name="r[id]" /><input type="submit" value="Aceptar" /><button onclick="document.location.href='?<?php echo $page; ?><?php echo $pager ?>'">Cancelar</button></td>
	</tr>
</table>
</form>
<?php include("plantillaFoot.inc.php"); ?>