<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }

if(isset($_POST['cmp'])) {
	#print_r($_POST);
	
	$pager = '';
	if(isset($data4) && $data4 == 'pag'){ $pager = '/pag/'.$data5; }


	$nombre_perfil = isset($_POST['cmp']['nombre_perfil']) && trim($_POST['cmp']['nombre_perfil']) != ''?trim($_POST['cmp']['nombre_perfil']):'Sin especificar';
	$permisos = isset($_POST['permisos']) && count($_POST['permisos']) >= 1?$_POST['permisos']:'Sin especificar';
	$seccion_inicial = isset($_POST['cmp']['seccion_inicial']) && trim($_POST['cmp']['seccion_inicial']) != ''?trim($_POST['cmp']['seccion_inicial']):'Sin especificar';
	$id = $data3;

	if($nombre_perfil != 'Sin especificar'){
		if($permisos != 'Sin especificar'){
			if($seccion_inicial != 'Sin especificar'){
				
				$update = array('nombre_perfil'=>$nombre_perfil,'seccion_inicial'=>$seccion_inicial);
				if($newPerfil = $myAdmin->conexion->update(PREFIJO.'perfil',$update,'WHERE kid_perfil='.$id,'')){

					#$myAdmin->conexion->query("UPDATE ".PREFIJO."perfil VALUES(NULL,'$nombre_perfil','$seccion_inicial')")){
					#$lastId = $myAdmin->conexion->last_id();
					$errorsTemp = '';

					#print_r($permisos);

					if($myAdmin->conexion->delete(PREFIJO."permisos","fid_perfil=$id")){
						for($perm = 0;$perm < count($permisos); $perm++){
							$currentPermiso = $permisos[$perm];
							
								if($myAdmin->conexion->insert(PREFIJO."permisos",array('fid_perfil'=>$id,'fid_accion'=>$currentPermiso))){
									
								}else{
									$errorsTemp .= 'Error: '.$myAdmin->conexion->error.'</br>';
								}	
							
						}
					}else{
						$errorsTemp .= 'Error: '.$myAdmin->conexion->error.'</br>';
					}
					
					if($errorsTemp != ''){
						die($errorsTemp);
					}else{
						header("Location: ".APP_URL.$data1.$pager);
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

if(isset($data3) && is_numeric($data3)) {
$id = $data3;
$queryPerfil = "SELECT * FROM ".PREFIJO."perfil WHERE kid_perfil=$data3";
$perfil =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryPerfil));
$perfil = $perfil[0];
#print_r($perfil);
#echo "<hr><pre>";
$queryPermisos = "SELECT fid_accion,(SELECT acronimo_accion FROM ".PREFIJO."acciones WHERE fid_accion=kid_accion) as acronimo FROM ".PREFIJO."permisos WHERE fid_perfil=$data3";
$permisos =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryPermisos));
$arrPermisosIds = array();
$arrPermisosAcron = array();
foreach($permisos as $permiso){
	$arrPermisosIds[] = $permiso['fid_accion'];
	$arrPermisosAcron[] = $permiso['acronimo'];
}
#print_r($arrPermisosAcron);
?>

<h3 class="text-muted">Editar perfil</h3>
<div class="fixed dobleespacio"></div>
<form method="POST">
	<div class="form-group">
	    <label for="cmp[nombre_perfil]">Nombre del perfil</label>
	    <input type="text" class="form-control" id="cmp[nombre_perfil]" name="cmp[nombre_perfil]" data-validation="length" data-validation-length="min4" value="<?php if(isset($perfil['nombre_perfil'])) echo $perfil['nombre_perfil'];?>">
	</div>	
	<div class="fixed espaciador"></div>
	<div class="form-group">
	    <label for="permisos[]">Permisos (seleccione todos los permisos deseados)</label>
	    <select name="permisos[]" id="permisos" class="form-control" multiple>
		<?php
		$queryA = "SELECT * FROM ".PREFIJO."acciones";
		$acciones =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryA));
		foreach($acciones as $acc){
			$selected = '';
			if(in_array($acc['kid_accion'], $arrPermisosIds)) $selected = 'selected="selected"';
		?>
		<option value="<?php echo $acc['kid_accion']; ?>" <?php echo $selected; ?>><?php echo utf8_encode($acc['nombre_accion']); ?></option>
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
	    	
	    </div>
	</div>
	<input type="submit" value="Aceptar" class="btn btn-primary" /> 
	<a href="<?php echo APP_URL.$data1; ?><?php echo $pager ?>" class="btn btn-secondary text-white">Cancelar</a>
</form>
<script type="text/javascript">
	var seccionInicial = false;
	$.validate({
		lang: 'es'
	});

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
			envioData.append("seccion_inicial",'<?php echo $perfil['seccion_inicial']; ?>');													
			$.ajax({
				url: "<?php echo APP_URL; ?>webservices/acciones.php",												
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

<?php } ?>