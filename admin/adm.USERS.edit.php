<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }


if(isset($_POST['cmp'])) {
	#print_r($_POST);
	$datos = array();
	$msg = '';
	$pager = '';
	if(isset($data4) && $data4 == 'pag'){ $pager = '/pag/'.$data5; }

	$kid_usr = (isset($_POST['cmp']['kid_usr']) && $_POST['cmp']['kid_usr']>0 && is_numeric($_POST['cmp']['kid_usr']))? $_POST['cmp']['kid_usr']: 0;
	if($kid_usr != 0){
		$datos['usr_login'] 	= $_POST['cmp']['usr_login'];
		$datos['usr_nombre'] 	= htmlentities($_POST['cmp']['usr_nombre']);
		$datos['usr_correo'] 	= $_POST['cmp']['usr_correo'];
		$datos['usr_activo'] 	= isset($_POST['cmp']['usr_activo'])? $_POST['cmp']['usr_activo'] = 1:$_POST['cmp']['usr_activo'] = 0;
		$datos['fid_perfil'] 	= $_POST['cmp']['fid_perfil'];
		if(isset($_POST['cmp']['usr_psw']) && $_POST['cmp']['usr_psw'] != ''){
			if($_POST['cmp']['usr_psw'] == $_POST['re_usr_psw']){
				$usr_psw = $_POST['cmp']['usr_psw'];
				#$datos['usr_psw'] 		= "AES_ENCRYPT('$usr_psw','".AESCRYPT."')";
				if(!$myAdmin->conexion->query("UPDATE ".PREFIJO."usuarios SET usr_psw=AES_ENCRYPT('$usr_psw','".AESCRYPT."') WHERE kid_usr='$kid_usr'")){
					$msg = '/msgErrUpdatePass';
				}
			}else{
				#echo $_POST['cmp']['usr_psw']." != ".$_POST['re_usr_psw'];
				#die();
				$msg = '/msgErrPass';
			}	
		}
		/*print_r($_POST);
		echo "<hr>";
		print_r($datos);
		die();*/
		if($myAdmin->conexion->update(PREFIJO."usuarios",$datos,"WHERE kid_usr='$kid_usr'",'')){
			header("Location: ".APP_URL.$data1.$pager.$msg);
		}else{
			die("Error: al actualizar registro");
		}		
	}else{
		echo "<div class='bg-warning'>Error en el id</div>";
	}

	#'$usr_login',AES_ENCRYPT('$usr_psw','".AESCRYPT."'),'$usr_nombre','$usr_correo',$usr_activo,$usr_perfil)"

}
/*
usr_id     | int(11)
usr_login  | varchar(50)
usr_psw    | blob
usr_nombre | varchar(255)
*/
#include("plantilla.inc.php");
$usr = $myAdmin->obtenerUsuario($data3);
$usr = $usr[0];
?>


<h3 class="text-muted">Editar usuario</h3>
<div class="fixed dobleespacio"></div>
<form method="POST" id="formsubmit">
	<input type="hidden" name="cmp[kid_usr]" value="<?php echo $usr['kid_usr']; ?>">
	<div class="form-group">
	    <label for="cmp[usr_login]">Usuario</label>
	    <input type="text" class="form-control" id="cmp[usr_login]" name="cmp[usr_login]" data-validation="length alphanumeric" data-validation-length="4-15" value="<?php echo $usr['usr_login']; ?>">
	</div>
	<div class="form-row">
		<div class="col">
			<div class="form-group">
			    <label for="cmp[usr_psw]">Actualizar Password</label>
			    <input type="password" class="form-control" id="cmp[usr_psw]" name="cmp[usr_psw]">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
			    <label for="re_usr_psw">Repetir password</label>
			    <input type="password" class="form-control" id="re_usr_psw" name="re_usr_psw">
			</div>
		</div>
	</div>
	<div class="form-group">
	    <label for="cmp[usr_nombre]">Nombre</label>
	    <input type="text" class="form-control" id="cmp[usr_nombre]" name="cmp[usr_nombre]" data-validation="length" data-validation-length="min4" value="<?php echo $usr['usr_nombre']; ?>">
	</div>
	<div class="form-group">
	    <label for="cmp[usr_correo]">Correo</label>
	    <input type="text" class="form-control" id="cmp[usr_correo]" name="cmp[usr_correo]" data-validation="email" value="<?php echo $usr['usr_correo'];?>">
	</div>	
	<div class="form-check">
	  	<input class="form-check-input" type="checkbox" id="cmp[usr_activo]" name="cmp[usr_activo]" <?php if((isset($usr['usr_activo']) && $usr['usr_activo'] == 1) || !isset($usr['usr_activo'])) echo 'checked="checked"';?> >
	  	<label class="form-check-label" for="cmp[usr_activo]">
	    Activo
	  	</label>
	</div>
	<div class="fixed espaciador"></div>
	<div class="form-group">
	    <label for="cmp[usr_perfil]">Perfil</label>
	    <select name="cmp[fid_perfil]" class="form-control">
		<?php
		$queryT = "SELECT * FROM ".PREFIJO."perfil";
		$perfiles =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
		foreach($perfiles as $perf){
				$selected = '';
				if($perf['kid_perfil'] == $usr['fid_perfil']) $selected = ' selected="selected"';
		?>
		<option value="<?php echo $perf['kid_perfil']; ?>"<?php echo $selected; ?>><?php echo $perf['nombre_perfil']; ?></option>
		<?php
		}
		?>
		</select>
	</div>
	<input type="submit" value="Aceptar" class="btn btn-primary" /> 
	<a href="<?php echo APP_URL.$data1; ?><?php echo $pager ?>" class="btn btn-secondary text-white">Cancelar</a>
</form>
<script type="text/javascript">
	$.validate({
		lang: 'es'
	});
	$('#formsubmit').submit(function(e){
		//alert('enviando');
	});
</script>