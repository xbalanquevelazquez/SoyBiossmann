<?php
$permisos = $myAdmin->obtenerUsr('permisos');
$secciones = $myAdmin->obtenerUsr('secciones');
?>
		<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
		  <a class="navbar-brand" href="<?php echo APP_URL; ?>">CRS</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav mr-auto">
		      	<?php
				for($s=0; $s < count($secciones); $s++){
					$activo = '';
					$acron = $secciones[$s]['acronimo'];
					$seccName = utf8_encode($secciones[$s]['nombre']);
					if($acron != 'ADMIN'){//Saltamos la opción de ADMIN porque es un permiso global del sistema
						// edi($data1 . ' vs ' . $acron);
						if($data1 == $acron){
							$activo = ' active';
							$tituloDePagina = $seccName;
						}
						if(in_array($acron,$permisos)){
							echo '<li class="nav-item '.$activo.'"><a class="nav-link" href="'.APP_URL.$acron.'">'.$seccName.'</a></li>';
						}
					}
				}
				?>
		    </ul>
		    <button class="btn btn-dark text-white sm"><?php echo $myAdmin->obtenerUsr('nombre') ?></button>
		    <button type="button" class="btn btn-danger btnSalir">Salir</button>

		  </div>
		</nav>
<div class="fixed espaciar"></div>