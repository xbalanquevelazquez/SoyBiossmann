<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['cmp'])) {
	$pager = '';
	if(isset($data3) && $data3 == 'pag'){ $pager = '/pag/'.$data4; }
	#print_r($_POST);
	
	$nombre_perfil = isset($_POST['cmp']['nombre_perfil']) && trim($_POST['cmp']['nombre_perfil']) != ''?trim($_POST['cmp']['nombre_perfil']):'Sin especificar';
	$permisos = isset($_POST['permisos']) && count($_POST['permisos']) >= 1?$_POST['permisos']:'Sin especificar';
	$seccion_inicial = isset($_POST['cmp']['seccion_inicial']) && trim($_POST['cmp']['seccion_inicial']) != ''?trim($_POST['cmp']['seccion_inicial']):'Sin especificar';

	if($nombre_perfil != 'Sin especificar'){
		if($permisos != 'Sin especificar'){
			if($seccion_inicial != 'Sin especificar'){
				
				if($newPerfil = $myAdmin->conexion->query("INSERT INTO ".PREFIJO."perfil VALUES(NULL,'$nombre_perfil','$seccion_inicial')")){
					$lastId = $myAdmin->conexion->last_id();
					$errorsTemp = '';

					for($perm = 0;$perm < count($permisos); $perm++){
						$currentPermiso = $permisos[$perm];
						if($myAdmin->conexion->query("INSERT INTO ".PREFIJO."permisos VALUES('$lastId','$currentPermiso')")){
							
						}else{
							$errorsTemp .= 'Error: '.$myAdmin->conexion->error.'</br>';
						}	
					}
					
					if($errorsTemp != ''){
						die($errorsTemp);
					}else{
						header("Location: ".ADMIN_URL.$data1.$pager);
					}

				}else{
					die("Error: al insertar registro");
				}	

			}else{
				echo "<div class='bg-warning'>No especificó sección inciial para el perfil</div>";
			}
		}else{
			echo "<div class='bg-warning'>No especificó permisos para el perfil</div>";
		}
	}else{
		echo "<div class='bg-warning'>No especificó nombre para el perfil</div>";
	}		
}
/*
crs_perfil
  kid_perfil INT(11) NOT NULL auto_increment,
  nombre_perfil VARCHAR(50) NOT NULL UNIQUE,
  seccion_inicial VARCHAR(50) NOT NULL,
crs_acciones
  kid_accion INT(11) NOT NULL auto_increment,
  acronimo_accion VARCHAR(5) NOT NULL UNIQUE,
  nombre_accion VARCHAR(50) NOT NULL UNIQUE,
  desc_accion VARCHAR(150),
crs_permisos
  fid_perfil INT(11) NOT NULL,
  fid_accion INT(11) NOT NULL
*/
?>

<h3 class="text-muted">Nuevo perfil</h3>
<div class="fixed dobleespacio"></div>
<form method="POST">
	<div class="form-group">
	    <label for="cmp[nombre_perfil]">Nombre del perfil</label>
	    <input type="text" class="form-control" id="cmp[nombre_perfil]" name="cmp[nombre_perfil]" data-validation="length" data-validation-length="min4" value="<?php if(isset($_POST['cmp']['nombre_perfil'])) echo $_POST['cmp']['nombre_perfil'];?>">
	</div>	
	<div class="fixed espaciador"></div>
	<div class="form-group">
	    <label for="permisos[]">Permisos (seleccione todos los permisos deseados)</label>
	    <select name="permisos[]" id="permisos" class="form-control" multiple>
		<?php
		$queryA = "SELECT * FROM ".PREFIJO."acciones";
		$acciones =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryA));
		foreach($acciones as $acc){
		?>
		<option value="<?php echo $acc['kid_accion']; ?>"><?php echo mb_convert_encoding($acc['nombre_accion'],'UTF-8'); ?></option>
		<?php
		}
		?>
		</select>
	</div>
	<div class="fixed espaciador"></div>
	<div class="form-group">
		<div class="messageBox"></div>
	    <label for="cmp[seccion_inicial]">Seccion inicial</label>
	    <div class="resultsBox">
	    	<select name="cmp[seccion_inicial]" class="form-control">
	    			<?php
	    			$queryT = "SELECT * FROM ".PREFIJO."perfil";
	    			$perfiles =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
	    			foreach($perfiles as $perf){
	    			?>
	    			<option value="<?php echo $perf['kid_perfil']; ?>"><?php echo mb_convert_encoding($perf['nombre_perfil'],'UTF-8'); ?></option>
	    			<?php
	    			}
	    			?>
	    			</select>
	    </div>
	</div>
	<input type="submit" value="Aceptar" class="btn btn-primary" /> 
	<a href="<?php echo ADMIN_URL.$data1; ?><?php echo $pager ?>" class="btn btn-secondary text-white">Cancelar</a>
</form>
<script type="text/javascript">
	var seccionInicial = false;
	$('#permisos').change(function(){
		var datos = $(this).val();
		if(datos != ''){
			getSeccionesPInicial(datos);
		}else{
		 	$(".resultsBox").html("<div class='bg-warning'>Necesita seleccionar al menos una sección</div>");
		}
	});
	function getSeccionesPInicial(datos){
		if(datos == ''){

		 	$(".resultsBox").html("<div class='bg-warning'>Necesita seleccionar al menos una sección</div>");
		}else{
			$(".resultsBox").html("<div class='bg-info'>Enviando...</div>");
			var envioData = new FormData();
			envioData.append("action",'getSeccionesPInicial');													
			envioData.append("secciones",datos);													
			$.ajax({
				url: "<?php echo APP_URL; ?>webservice/acciones.php",												
				type:"POST",
				processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
				contentType: false,
				data:envioData,
				cache:false,
				dataType:"json",
				success: function(respuesta){
					var texto = '';
					if(respuesta.success){
						$(".resultsBox").html('');
						texto = '';
					}else{
						texto = "<div class='bg-warning'>"+respuesta.error+"</div>";
					}
					$(".messageBox").html(texto);
					$(".resultsBox").html(respuesta.data.codigo);
				}
			});
		}
	}

	getSeccionesPInicial($('#permisos').val());
</script>