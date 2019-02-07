<?php
define('VIEWABLE',TRUE);
include_once('libs/cnfg.php');


include_once("admin/cnf/configuracion.cnf.php");
include_once(LIB_PATH."functions.inc.php");
include_once(CLASS_PATH."mysql.class.php");
include_once(CLASS_PATH."page.class.php");
#$page = new Page($page);

$arrExcept = array('index');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php #if(!$index) echo $page->pageData['nombre']; ?> | Soy Biossmann</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,shrink-to-fit=no" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="robots" content="index, follow" />
    <meta name="description" content="<?php #echo $page->pageData['descripcion']!=''?$page->pageData['descripcion']:$page->pageData['nombre'].', Soy Biossmann.'?>" />
    <meta name="keywords" content="<?php #echo $page->pageData['keywords']!=''?$page->pageData['keywords']:', intranet, '.$page->pageData['nombre'] ?>" />
    <meta name="author" content="Biossmann TI" />
	<link rel="shortcut icon" href="<?php echo APP_URL; ?>img/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/basico.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/personalizacion.css" />
	<!-- <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/menu-horizontal.css" media="screen, handheld, print" /> -->
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/fontawesome-free-5.1.0-web/css/all.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/menu-horizontal.css" />

	<script type="text/javascript" src="<?php echo APP_URL; ?>js/popper.min.js"></script>
   	<script type="text/javascript" src="<?php echo APP_URL; ?>js/jquery-3.0.0.min.js"></script>
 	<script type="text/javascript" src="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/cssmenuh.js"></script>

	<script src="js/app.js"></script>           <!-- recommended location of your JavaScript code relative to other JS files -->
	</head>
<body>
	<section id="mainContainer container-fluid">
		<header class=" sticky-top">
			<nav class="navbar navbar-expand-lg navbar-light  biossHeader">
					<a class="navbar-brand" href="<?php echo APP_URL; ?>"><img src="img/logo-soy-biossmann.png" alt="Soy Biossmann" /></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse menu_principal" id="navbarSupportedContent">
					 	<ul class="navbar-nav mr-auto menu" id="menunav">
							<li class="active"><a class="nav-link" href="">Nuestros servicios</a>
								<ul>
									<li><a href="">SAP y Bases de datos</a></li>
									<li><a href="">Proyectos y Gobierno de TI</a></li>
									<li><a href="">Seguridad e Infraestructura</a></li>
									<li><a href="">Mesa de Ayuda</a></li>
								</ul>
							</li>
							<li class=""><a class="nav-link" href="">Apps y Tableros de Control</a>
								<ul>
									<li><a href="">Fichas técnicas de Apps Biossmann</a></li>
									<li><a href="">Reportes de QlikView</a></li>
								</ul>
							</li>
							<li class=""><a class="nav-link" href="">Preguntas Frecuentes</a>
								<ul>
									<li><a href="">Temas informativos</a></li>
									<li><a href="">Tips de seguridad</a></li>
									<li><a href="">Reportes de vulnerabilidades conocidas</a></li>
									<li><a href="">Software libre</a></li>
								</ul>
							</li>
							<li class=""><a class="nav-link" href="">Mesa de ayuda</a>
								<ul>
									<li><a href="">Levantar ticket</a></li>
									<li><a href="">Estatus de tu ticket</a></li>
									<li><a href="">Estadística de servicio</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
		</header>
		<section id="main">
			<div class="renglonGutter"></div>

			<div class="row no-gutters">
				<div class="col-9 gutter">

					<div class="material bordeGris bg-azulgris texto-blanco" id="directorio">
						<label for="nombre">Directorio biossmann:</label>
						<br />
					    <input type="text" name="nombre" id="nombre" placeholder="Nombre, apellido, correo o celular:" />
					    <button class="btn ixSubmit search texto-blanco bg-verdeagua"><i class="fa fa-search"></i></button>
					    <button class="btn ixSubmit clear bg-grismedio texto-grisclaro"><i class="fa fa-times"></i></button>
					</div>
				          					
					<div class="renglonGutter"></div>

					<div class="row no-gutters">
						<div class="col-8 gutter">
							<div class="material bordeGris aleft" id="contenedorBanners">
								<button class="material btn bg-azulgris texto-blanco positionLeft positionBottom">Cómo protegerte</button>
								<div class="apuntador">
		           					<ul>
			                            <li id="bannLink1"><a href="" style="background-color: rgba(206, 231, 2, 0.4);">&nbsp;</a></li>
			                            <li id="bannLink2"><a href="" style="background-color: rgba(206, 231, 2, 0.8);">&nbsp;</a></li>
			                            <li id="bannLink3"><a href="" style="background-color: rgba(206, 231, 2, 0.4);">&nbsp;</a></li>
		                          	</ul> 
		          				</div>
								<div class="banner">
									<img src="img/banner/bann-malware.png" alt="Evita descargar programas de sitios desconocidos" />
								</div>
							</div>
						</div>
						<div class="col-4 gutter mainSections">
							<div class="material bordeGris">
								<a href="">
									<div><span class="strong">SAP</span><span class="break"></span> y Bases de datos</div><div class="icon"><i class="fa fa-cogs"></i></div>
								</a>
							</div>
							<div class="renglonGutter"></div>
							<div class="material bordeGris">
								<a href="">
									<div>Proyectos y <span class="strong"><span class="break"></span>Gobierno de TI</span></div><div class="icon"><i class="fa fa-chess-knight"></i></div>
								</a>
							</div>
							<div class="renglonGutter"></div>
							<div class="material bordeGris">
								<a href="">
									<div><span class="strong">Seguridad</span> e<span class="break"></span> Infraestructura</div><div class="icon"><i class="fa fa-shield-alt"></i></div>
								</a>
							</div>
						</div>
					</div>
					<div class="renglonGutter"></div>

					<div class="row no-gutters toolsSection">
						<div class="col-8 gutter">
							
							<div class="row no-gutters">
								<div class="col gutter">
									<div class="material bordeGris bg-azulgris"><a href="">
										<div class="img"><img src="img/img-prosperidad.png" alt="prosperidad"></div>
										<div class="texto">Tableros de control</div>
									</a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-azulgris"><a href="">
										<div class="ico-restringido-float"><i class="fa fa-key"></i></div>
										<div class="img"><img src="img/img-innovacion.png" alt="innovacion"></div>
										<div class="texto">Apps biossmann</div>
									</a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-azulgris"><a href="">
										<div class="img"><img src="img/img-pasion.png" alt="pasion"></div>
										<div class="texto">Preguntas frecuentes</div>
									</a></div>
								</div>
							</div>
							<div class="renglonGutter"></div>
							<div class="row no-gutters">
								<div class="col gutter">
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-universidad-biossmann.png" alt=""></a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-portal-biossmann.png" alt=""></a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-intranet.png" alt=""></a></div>
								</div>
							</div>
						</div>
						<div class="col-4 gutter">
							
							<div class="material bordeGris mesaayudaSection">
								<div class="barraTitulo texto-blanco bg-azulgris"><i class="fa fa-headset shadowed"></i> Mesa de <span class="strong">Ayuda</span></div>
								<div class="innerContent bg-gris13 bordeGris">
									<ul class="optionsButtons">
										<li><a href=""><img src="img/ico-new-ticket.png" alt="">Levantar ticket</a></li>
										<li><a href=""><img src="img/ico-search-ticket.png" alt="">Estatus de tu ticket</a></li>
										<li><a href=""><img src="img/ico-statistics-ticket.png" alt="">Estadística de servicio</a></li>
									</ul>
								</div>
							</div>

						</div>
					</div>

				</div>
				<div class="col-3 gutter">
					<div class="material bordeGris">
						<div class="barraTitulo texto-blanco bg-azulgris ico ico-valentia">Servicios destacados</div>
						<div class="innerContent">
							<ul class="optionsButtons">
								<li><a href="">Solicita una VPN</a></li>
								<li><a href="">Solicita o reporta equipos</a></li>
								<li><a href="">Desbloqueo de páginas web</a></li>
								<li><a href="">Solicitud de acceso a SAP</a></li>
								<li><a href="">Tickets de servicio</a></li>
							</ul>
						</div>
					</div>
					<div class="renglonGutter"></div>
					<div class="material bordeGris bg-gris13">
						<div class="barraTitulo texto-blanco  bg-azulgris ico ico-compromiso">Opina</div>
						<div class="innerContent encuestaHome">
							<p>¿Te parece útil la información de este sitio web?</p>
							<div class="opciones">
								<div><input type="radio" name="opinion" id="op1"> <label for="op1">Sí</label></div>
								<div><input type="radio" name="opinion" id="op2"> <label for="op2">Sí, pero podría mejorarse</label></div>
								<div><input type="radio" name="opinion" id="op3"> <label for="op3">No</label></div>
							</div>
							<div>Comentarios</div>
							<textarea name="opiniontext" id="opiniontext" rows="3"></textarea>
							<div class="aright">
								<button id="enviarComentarios" class="material btn bg-verdeagua texto-blanco">Enviar</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</section><!--//#main-->
		<footer>
			<div class="biossFooter texto-blanco">
				<nav class="navbar">
					<ul>
						<li><a href="" class="resaltar">Contáctanos</a></li>
						<li><a href="">Mapa de sitio</a></li>
						<li><a href="">Reporta un problema en esta página</a></li>
					</ul>
				</nav>
				<div class="plecas"></div>
			</div>
		</footer>
	</section><!--//#mainContainer-->
	<script>
	$(document).ready(function(){
	});
	</script>
</body>
</html>