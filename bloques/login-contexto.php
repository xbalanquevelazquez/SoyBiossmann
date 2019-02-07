<?php 
/*if(logedin()){
	$nombre = isset($_SESSION[SESSION_DATA_SET]['nombre'])?$_SESSION[SESSION_DATA_SET]['nombre']:'';
	$apellidos = isset($_SESSION[SESSION_DATA_SET]['apellidos'])?$_SESSION[SESSION_DATA_SET]['apellidos']:'';
	$userpicture = isset($_SESSION[SESSION_DATA_SET]['foto'])?$_SESSION[SESSION_DATA_SET]['foto']:WWW_HOST.'/'.HOME_DIR.'/img/foto-perfil.png';
?>
<div id="perfilContexto" class="transicionLenta">
<?php  ?>
	<div class="nombre transicionLenta mostrar"><?php echo $nombre; ?></div>
	<div class="foto transicionLenta mostrar" style="background-image:url('<?php echo $userpicture; ?>');"></div>
	<div class="icon-llave transicionLenta ocultar"></div>
	<div class="fixed"></div>
</div>
<div id="detallePerfil" class="transicion">
	<div class="slant"></div>
	<div class="contenedorBloque transicion">
		<div class="fotoDetail">
			<div class="foto" style="background-image:url('<?php echo $userpicture; ?>');"></div>
			<button class="cambiarFoto">Cambiar</button>
		</div>
		<div class="opciones">
			<div class="nombre"><?php echo $nombre.' '.$apellidos; ?></div>
			<div>
				<button class="configuracion"><i class="fa fa-cog"></i>Configuración</button>
			</div>
			<div>
				<button class="salir"><i class="fa fa-unlock-alt"></i>Salir</button>
			</div>
		</div>

	</div>
</div>
<?php
}else{*/
?>
	<!-- FOTO PERFIL -->
	<div id="perfil" class="transicionLenta contexto">
		<div class="foto transicionLenta mostrar"></div><!--css back-img foto $userpicture-->
		<div class="nombre transicionLenta mostrar"></div><!--nombre-->
		<div class="fixed"></div>
	</div>
	<!-- //END FOTO PERFIL -->
<div id="detallePerfilContexto" class="transicion">
	<div class="slant"></div>
	<div class="contenedorBloque transicion">
		<div class='mensajeLogin'></div>
		<form class="loginForm">
				<div class="uk-form-icon">
					<i class="uk-icon-user"></i>
					<input type="text" id="usuario" name="usuario" placeholder="Usuario">
				</div>
				<div class="fixed"></div>
				<div class="uk-form-icon">
					<i class="icon-llave"></i>
					<input type="password" id="password" name="password" placeholder="Contraseña">
				</div>
			<button class="ingresar">Ingresar</button>
		</form>
	</div>
</div>

<?php
//}