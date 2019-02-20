<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['cmp'])) {
	$pager = '';
	if(isset($data3) && $data3 == 'pag'){ $pager = '/pag/'.$data4; }
	#print_r($_POST);
	if($_POST['cmp']['usr_psw'] == $_POST['re_usr_psw']){

			isset($_POST['cmp']['usr_activo'])? $_POST['cmp']['usr_activo'] = 1:$_POST['cmp']['usr_activo'] = 0;
			$usr_login = $_POST['cmp']['usr_login'];
			$usr_psw = $_POST['cmp']['usr_psw'];
			$usr_nombre = htmlentities($_POST['cmp']['usr_nombre']);
			$usr_correo = $_POST['cmp']['usr_correo'];
			$usr_activo = $_POST['cmp']['usr_activo'];
			$usr_perfil = $_POST['cmp']['fid_perfil'];
			
			if($myAdmin->conexion->query("INSERT INTO ".PREFIJO."usuarios VALUES(NULL,'$usr_login',AES_ENCRYPT('$usr_psw','".AESCRYPT."'),'$usr_nombre','$usr_correo',$usr_activo,$usr_perfil)")){
				header("Location: ".APP_URL.$data1.$pager);
			}else{
				die("Error: al insertar registro");
			}	
	}else{
		echo "<div class='bg-warning'>la contrase&ntilde;a no es igual en los dos campos</div>";
	}		
}
/*
kid_usr     | int(11)
usr_login  	| varchar(50)
usr_psw    	| blob
usr_nombre 	| varchar(255)
usr_correo	| VARCHAR(255)
usr_activo	| INT(11)
fid_perfil	| INT(11)
*/
?>

<h3 class="text-muted">Nuevo usuario</h3>
<div class="fixed dobleespacio"></div>
<form method="POST">
	<div class="form-group">
	    <label for="cmp[usr_login]">Usuario</label>
	    <input type="text" class="form-control" id="cmp[usr_login]" name="cmp[usr_login]" data-validation="length alphanumeric" data-validation-length="4-15" value="<?php if(isset($_POST['cmp']['usr_login'])) echo $_POST['cmp']['usr_login'];?>">
	</div>
	<div class="form-row">
		<div class="col">
			<div class="form-group">
			    <label for="cmp[usr_psw]">Password</label>
			    <input type="password" class="form-control" id="cmp[usr_psw]" name="cmp[usr_psw]" data-validation="length alphanumeric" data-validation-length="min4">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
			    <label for="re_usr_psw">Repetir password</label>
			    <input type="password" class="form-control" id="re_usr_psw" name="re_usr_psw" data-validation="length alphanumeric" data-validation-length="min4">
			</div>
		</div>
	</div>
	<div class="form-group">
	    <label for="cmp[usr_nombre]">Nombre</label>
	    <input type="text" class="form-control" id="cmp[usr_nombre]" name="cmp[usr_nombre]" data-validation="length" data-validation-length="min4" value="<?php if(isset($_POST['cmp']['usr_nombre'])) echo $_POST['cmp']['usr_nombre'];?>">
	</div>
	<div class="form-group">
	    <label for="cmp[usr_correo]">Correo</label>
	    <input type="text" class="form-control" id="cmp[usr_correo]" name="cmp[usr_correo]" data-validation="email" value="<?php if(isset($_POST['cmp']['usr_correo'])) echo $_POST['cmp']['usr_correo'];?>">
	</div>	
	<div class="form-check">
	  	<input class="form-check-input" type="checkbox" id="cmp[usr_activo]" name="cmp[usr_activo]" <?php if((isset($_POST['cmp']['usr_activo']) && $_POST['cmp']['usr_activo'] == 1) || !isset($_POST['cmp']['usr_activo'])) echo 'checked="checked"';?> >
	  	<label class="form-check-label" for="cmp[usr_activo]">
	    Activo
	  	</label>
	</div>
	<div class="fixed espaciador"></div>
	<div class="form-group">
	    <label for="cmp[fid_perfil]">Perfil</label>
	    <select name="cmp[fid_perfil]" class="form-control">
		<?php
		$queryT = "SELECT * FROM ".PREFIJO."perfil";
		$perfiles =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
		foreach($perfiles as $perf){
		?>
		<option value="<?php echo $perf['kid_perfil']; ?>"><?php echo $perf['nombre_perfil']; ?></option>
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
</script>