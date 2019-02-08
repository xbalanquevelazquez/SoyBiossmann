<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
?>
		<section id="main">
			<div class="renglonGutter"></div>

			<div class="row no-gutters">
				<div class="columnaD gutter" id="primeraColumna">

					<div class="material bordeGris bg-azulgris texto-blanco" id="directorio">
						<label for="nombre">Directorio biossmann:</label>
						<br />
					    <input type="text" name="nombre" id="nombre" placeholder="Nombre, apellido, correo o celular:" />
					    <button class="btn ixSubmit search texto-blanco bg-verdeagua"><i class="fa fa-search"></i></button>
					    <button class="btn ixSubmit clear bg-grismedio texto-grisclaro"><i class="fa fa-times"></i></button>
					</div>
				          					
					<div class="renglonGutter"></div>

					<div class="row no-gutters">
						<div class="col">
							<!--div class="material bordeGris aleft" id="contenedorBanners">
								<button class="material btn bg-azulgris texto-blanco positionLeft positionTop">Consulta el... </button>
								<div class="apuntador">
		           					<ul>
			                            <li id="bannLink1"><a href="" style="background-color: rgba(206, 231, 2, 0.4);">&nbsp;</a></li>
			                            <li id="bannLink2"><a href="" style="background-color: rgba(206, 231, 2, 0.8);">&nbsp;</a></li>
			                            <li id="bannLink3"><a href="" style="background-color: rgba(206, 231, 2, 0.4);">&nbsp;</a></li>
		                          	</ul> 
		          				</div>
								<div class="banner">
									<img src="img/banner/bann-dias-festivos.png" alt="Calendario de días festivos" />
								</div>
							</div-->
		<div class="columna columna2C margenIzq rightAlign material" id="contenedorBanners">
          
          <div class="apuntador" style="">
            <ul>
                <li id="bannLink1"><a href="" style="background-color: rgba(0, 0, 0, 0.4);">&nbsp;</a></li>
                <li id="bannLink2"><a href="" style="background-color: rgba(0, 0, 0, 0.4);">&nbsp;</a></li>
                <li id="bannLink3"><a href="" style="background-color: rgba(0, 0, 0, 0.8);">&nbsp;</a></li>
            </ul> 
          </div>
          <div class="banner transicion 3 naranja" id="bann3">
            <a href="/talento-humano"><!--banner link-->
              <div class="img">
                <img src="<?php echo APP_URL; ?>img/banner/bann-valor-innovacion.png" alt="Valores Biossmann">
              </div>
              <div class="texto">
                Banner de prueba                <br>
                <span>Grupo Biossmann palabras palabras Grupo Biossmann palabras palabras Grupo Biossmann palabras palabras Grupo Biossmann palabras palabras Grupo Biossmann pn pax ax ax axxx</span>
              </div>
            </a><!--//banner link-->
          </div>
          <div class="banner transicion 2 verde" id="bann2">
            <a href="#"><!--banner link-->
              <div class="img">
                <img src="<?php echo APP_URL; ?>img/banner/bann-valor-pasion.png" alt="Valores Biossmann">
              </div>
              <div class="texto">
                Felicidades a todos los que cumplen años!                <br>
                <span>Mayo, 2017</span>
              </div>
            </a><!--//banner link-->
          </div>
          <div class="banner transicion 1 morado" id="bann1">
            <a href="/corporativo"><!--banner link-->
              <div class="img">
                <img src="<?php echo APP_URL; ?>img/banner/bann-dias-festivos.png" alt="Calendario de días festivos">
              </div>
              <div class="texto">
                Nuestro trabajo tiene sentido                <br>
                <span>Grupo Biossmann</span>
              </div>
            </a><!--//banner link-->
          </div>
        </div>

							        <!--div class="columna columna2C margenIzq rightAlign material" id="contenedorBanners">
          
          <?php 
         /* $banners = $page->obtenerBanners('banners-inicio');
          $zTop = $banners['count']+3;
         // echo $banners['query']."<br>";
         // echo $banners['count']."<br>";
         // print_r($banners);
           ?>
          <div class="apuntador" style="z-index: <?php  echo $zTop+1; ?>">
            <ul>
              <?php 
              for($b=0;$b<$banners['count'];$b++){
                $style = '';
                if($b==0) $style = 'style="background-color: rgba(0,0,0,0.8)"';
              ?>
              <li id="bannLink<?php  echo $b+1; ?>"><a href="" <?php  echo $style; ?>>&nbsp;</a></li>
              <?php } ?>
            </ul> 
          </div>
              <?php 
              for($b=0;$b<$banners['count'];$b++){
                $style = '';
                if($b==0) $style = 'style="background-color: rgba(0,0,0,0.8)"';
                $bannerColorData = getColor($banners['data'][$b]['color']);
              ?>
          <div class="banner transicion <?php echo $banners['data'][$b]['color'].' '.$bannerColorData['colorName']; ?>" id="bann<?php  echo $b+1; ?>" style="z-index: <?php  echo $zTop-$b; ?>" >
            <a href="<?php echo $banners['data'][$b]['link']; ?>"><!--banner link-->
              <div class="img">
                <img src="<?php  echo WEB_BANNER_PATH.$banners['data'][$b]['img']; ?>" />
              </div>
              <div class="texto">
                <?php  echo $banners['data'][$b]['titulo']; ?>
                <br />
                <span><?php  echo $banners['data'][$b]['alt']; ?></span>
              </div>
            </a><!--//banner link-->
          </div>
              <?php }*/ ?>
        </div-->


						</div>
					</div>

					<div class="renglonGutter"></div>

					<div class="row no-gutters toolsSection">
						<div class="col">
							
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
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-transformacion.png" alt=""></a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-universidad-biossmann.png" alt=""></a></div>
								</div>
								<div class="col gutter">
									<div class="material bordeGris bg-blanco"><a href="" class="acenter"><img src="img/img-portal-biossmann.png" alt=""></a></div>
								</div>
							</div>

							<div class="renglonGutter"></div>

						</div>
					</div>

				</div><!--//#primeraColumna-->

				<div class="columnaE gutter" id="segundaColumna">
					<div class="material bordeGris mesaayudaSection">
						<div class="barraTitulo texto-blanco bg-azulgris"><i class="fa fa-headset shadowed"></i> Solicita <span class="strong">Ayuda</span></div>
						<div class="innerContent bg-gris13 bordeGris hidden">
							<ul class="optionsButtons">
								<li><a href=""><img src="img/ico-new-ticket.png" alt="">Levantar ticket</a></li>
								<li><a href=""><img src="img/ico-search-ticket.png" alt="">Estatus de tu ticket</a></li>
								<!--li><a href=""><img src="img/ico-statistics-ticket.png" alt="">Estadística de servicio</a></li-->
							</ul>
						</div>
					</div>

					<div class="renglonGutter"></div>

					<div class="row no-gutters">
						<div class="col gutter mainSections">
							<div class="material bordeGris">
								<a href="">
									<div class="unaLinea"><span class="strong">Biossmann</span> </div><div class="icon"><i class="fa fa-cogs"></i></div>
								</a>
							</div>
							<div class="renglonGutter"></div>
							<div class="material bordeGris">
								<a href="">
									<div><span class="strong">Políticas</span><span class="break"></span>y Procedimientos</div><div class="icon"><i class="fa fa-chess-knight"></i></div>
								</a>
							</div>
							<div class="renglonGutter"></div>
							<div class="material bordeGris">
								<a href="">
									<div><span class="strong">Productos</span> <span class="break"></span> y Servicios</div><div class="icon"><i class="fa fa-shield-alt"></i></div>
								</a>
							</div>
						</div>
					</div>

					<div class="renglonGutter"></div>

					<div class="row no-gutters">
						<div class="col">
							<div class="material bordeGris">
								<a href=""><img src="img/img-galeria-biossmann.png" alt="Galería Biossmann"></a>
							</div>
						</div>
					</div>					
				</div><!--//#segundaColumna-->

				<div class="columnaF gutter" id="terceraColumna">
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
							<textarea name="opiniontext" id="opiniontext" rows="2"></textarea>
							<div class="aright">
								<button id="enviarComentarios" class="material btn bg-verdeagua texto-blanco">Enviar</button>
							</div>
						</div>
					</div><!--//.material > Opina-->
					<div class="renglonGutter"></div>
					<div class="material bordeGris">
						<div class="barraTitulo texto-blanco bg-azulgris ico ico-valentia">Portales de interés</div>
						<div class="innerContent">
							<ul class="optionsButtons btnsBlancos">
								<li><a href="">Federación Mexicana de Anestesiología</a></li>
								<li><a href="">Colegio de Anestesiología de México</a></li>
								<li><a href="">Instituto Mexicano del Seguro Social</a></li>
								<li><a href="">Diario Oficial de la Federación</a></li>
							</ul>
						</div>
					</div><!--//.material > Portales de interés-->
				</div><!--//#terceraColumna-->
			</div>

		</section><!--//#main-->
