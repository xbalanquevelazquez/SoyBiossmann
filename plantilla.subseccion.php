<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
?>
		<section id="main">
		<?php $page->mostrarSubsecciones(); ?>
			<div class="renglonGutter"></div>

			<div class="row">
				<div class="col">
					<?php $page->mostrarBreadcrumb($otroNombre='',$separador='<i class="fa fa-caret-right"></i>'); ?>
					<h1><?php echo $page->pageData['nombre'] ?></h1>
				</div>
			</div>

			<div class="row no-gutters">
				<div class="columnaH gutter">
					
					<?php echo $page->contenido['contenido'];  ?>
					<!--div class="material bordeGris bg-azulgris texto-blanco" id="directorio">
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
					</div-->

				</div>
				<div class="columnaE gutter">
					<?php $page->mostrarMenuSecundario(); ?>
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

		<div class="row">
			<div class="col ultimaActualizacion">Última actualización: <?php echo formatFechaEspaniol($page->ultimaActualizacion); ?></div>
		</div>

		</section><!--//#main-->
