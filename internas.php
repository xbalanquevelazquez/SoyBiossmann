      <div class="renglon">
        <div class="columna3D">
          <div class="renglon espaciar seccionConInfo material">
           <?php echo $page->mostrarBreadcrumb('','>'); ?>
            <div class="columnaA material">
              <div class="toolSet">
                <span title="Disminuir tama�o de texto" class="fontControl reduceFont"><i class="fa fa-minus-square"></i></span>
                <span title="Tama�o de texto original" class="fontControl resetFont"><i class="fa fa-font"></i></span>
                <span title="Incrementar tama�o de texto" class="fontControl increaseFont"><i class="fa fa-plus-square"></i></span>
              </div>
              <a name="contenidoskip" id="contenidoskip"></a>
              <div id="contenidoBox">
                <h3 class="tituloSeccion"><?php echo $page->pageData['nombre']; ?></h3>
                <div class="contenido"><?php echo $page->contenido['contenido']; ?></div>
              </div><!--//#contenidoBox-->
              
              <div class="fixed">&nbsp;</div>
              <?php if(!$page->p404){ //ULTIMA ACTUALIZACI�N ?>
              <div class="autor_nota"> �ltima modificaci�n:<br>
                <?php echo formatFechaEspaniol($page->ultimaActualizacion); ?> por <?php echo $page->contenido['nombre_responsable']; ?>
              </div>
              <?php } ?>
              
              <div class="fixed"></div>
              
            </div>
            <div class="fixed"></div>
          </div>
          
          <?php if(!$page->p404){ //COMENTARIOS ?>
          <div class="renglon">
            <div class="columnaA material grisClaro">
              <h3 class="tituloSeccion grisOscuro-degradado texto-blanco ">Comentarios</h3>
              <div class="renglon paddingModule ">
                <div class="renglon nota">Com�ntanos que te ha parecido la informaci�n de esta p�gina, si te gustar�a ver algo m�s, o si te es de utilidad lo que mostramos aqu�.</div>
                
                <hr>

                  <div>
                    <textarea id="miscomentarios" cols="78" rows="3" style="100%"></textarea>
                  </div>
                  <div class="rightAlign paddingModule">
                    <button class="btnEnviarComment btnD1"> Enviar</button>
                  </div>
                
                <hr class="separador" />

                <div id="comentarios">

                </div><!--//END #comentarios-->

                <div class="fixed"></div>
          
              </div>
            </div>
            <div class="fixed"></div>
          </div>
          <?php } ?>


        </div>
        <div class="columnaD margenIzq">
          
          <?php echo $page->mostrarMenuSecundario(); ?>
          
          <div class="renglon espaciar overcontent material">
            <h3 class="tituloSeccion grisOscuro texto-blanco">Avisos <span>Comunicaci�n interna</span></h3>
            <div class="columnaA paddingModuleThird rightAlign listadoNoticias">
              <ul>
                <li class="material leftAlign">
                  <a href="">
                    <div class="titulo">8 de mayo, 2017</div>
                    <div class="texto">La semana del 7 al 11 de agosto se llevar� a cabo la auditor�a interna de calidad.</div>
                  </a>
                </li>
                <li class="material leftAlign">
                  <a href="">
                    <div class="titulo">9 de mayo, 2017</div>
                    <div class="texto">Acomp��anos a festejar a quienes cumplen a�os en Abril, este viernes 26 de mayo.</div>
                  </a>
            
                </li>
              </ul>
              <div class="material btn grisOscuro texto-blanco"><i class="fa fa-ellipsis-h"></i></div>
            </div>
          </div>
          <div class="fixed"></div>

          <?php if(!$page->p404){//CALIFICACI�N ?>
          <div class="renglon material espaciar overcontent">
            <div class="columnaA">
              <h3 class="tituloSeccion morado texto-blanco reducirPuntaje centerAlign">Califica este contenido</h3>
              <div class="paddingModule califBlock">
                
                <?php 
                $calificador = $page->generaEstrellas();
                //echo $calificador['success'];
                $calificacionSuccess = $calificador['success'];
                $habilitarCalificacion = $calificador['habilitarCalificacion'];
                $miCalificacion = $calificador['miCalificacion'];
                echo $calificacion = $calificador['calificacion'];
                $votos = $calificador['votos'];
                ?>
                 
                <script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/funcionalidad.calificacion.js"></script>
                <script type="text/javascript">
                  generarEstrellas('<?php echo $miCalificacion; ?>','<?php echo $calificacion; ?>','<?php echo $votos; ?>','<?php echo $habilitarCalificacion; ?>');
                </script>
                  <?php if($habilitarCalificacion){ ?>
                  <style>
                    .calificador li{cursor:pointer;}
                  </style>
                  <?php } ?>
              </div>
            </div>
          </div>
          <?php }///CALIFICACION END ?>



        </div>
        <div class="fixed"></div>
      </div>
 
      <div class="fixed"></div>

      <script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/funcionalidad.interna.js"></script>