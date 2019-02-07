      <div class="renglon renglonDirectorioH transicionLenta"><!-- overcontent -->
        <div class="columna columnaA fondoGrisOscuro searchBox" id="directorio">
          <h2>Encontrar a:</h2>
          

          <form id="busqueda" action="#">
            <div class="inputsContainer">
              
              <div class="uk-form">
                <input type="text" name="nombre" id="nombre" placeholder="Nombre, apellido, correo o celular:" class="clearable" />
              </div>
              
              
              <div class="uk-button uk-form-select relative" data-uk-form-select>
                <span class="dataSelectEmpresas"></span>
                <span class="spacer"></span>
                <i class="uk-icon-caret-down"></i>
                <?php 
                $queryEmpresas = 'SELECT company FROM empleados GROUP BY company';
                $resultadoEmpresas = mysql_query($queryEmpresas,$conn) or die(mysql_error());
                ?>
                <select name="empresa" id="empresa">
                  <option value="%">En todas las empresas</option>
                  <?php 
                  while ($empresa = mysql_fetch_assoc($resultadoEmpresas)) {
                  ?>
                  <option value="<?php echo $empresa['company']; ?>"><?php echo $empresa['company']; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="submitsBtns">
              <button class="btn ixSubmit search"><i class="fa fa-search"></i></button>
              <button class="btn ixSubmit clear"><i class="fa fa-times"></i></button>
            </div>
          </form>


          <div class="fixed"></div>
        </div><!--#directorio -->
        <div class="resultsBox transicionLenta columna5F">
          <div class="container transicionLenta">
          </div>
        </div>
        <!--//.resultsBox-->

        <div class="detailBox transicionLenta columnaC">
          <div class="container transicionLenta">
          </div>
        </div>
        <!--//.detailBox-->
        

        <div class="fixed"></div>
      </div>

      <div class="fixed"></div>

      <div class="renglon renglonBannerPrincipalH transicionLenta">

        <div class="columna columnaC margenDer material">
          <div class="moduloInfo">
              <ul class="listadoBotones">
                <li><a href=""><div class="icon-organigrama"></div><div class="texto">Estructura organizacional</div></a></li>
                <li><a href=""><div class="icon-calidad"></div><div class="texto">Calidad</div></a></li>
                <li><a href=""><div class="icon-areas"></div><div class="texto">Información por áreas</div></a></li>
              </ul>
          </div>
        </div>


        <div class="columna columna2C margenIzq rightAlign material" id="contenedorBanners">
          
          <?php 
          $banners = $page->obtenerBanners('banners-inicio');
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
              <?php } ?>
        </div>

      </div>

      <div class="fixed"></div>

      <div class="renglon renglonBannersSecundariosH transicionLenta">

        <div class="columna columnaC material margenDer"><a href=""><img src="img/banner/derechos-universales.png" alt="Derechos Universales de los pacientes"></a></div>
        <div class="columna columnaC material margenDer"><a href="https://universidadbiossmann.com/"><img src="img/banner/universidad-biossmann-01.png" alt="Universidad Digital Biossmann"></a></div>
        <div class="columna columnaC material"><a href="#"><img src="img/banner/manual-calidad.png" alt="Manual de Calidad Medicus"></a></div>
        
        <div class="fixed"></div>

      </div>
      
      <div class="fixed"></div>
