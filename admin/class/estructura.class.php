<?php
class Estructura{
	var $estructura = array();
	var $numUnico = 0;
	
	function crearEstructura($estructura=array(),$consecutivo=0){
		if(empty($estructura)) {echo "No se especificaron datos para producir la estructura";return false;}#si no hay datos, no procesa
		$nivelanterior = 0;
		$this->numUnico ++;
		
		foreach($estructura as $key => $valor){
			if($valor['nivel']==0){
				$opcionesBox1 = '<div class="opciones" id="op'.$this->numUnico.'" style="display:none;">
								<div class="marco">';
								/*ACTIVAR/DESACTIVAR*/
								if($valor['publicado']==0){
									$opcionesBox1 .= '<a href="?est&change&activar&id='.$valor['kid_pagina'].'" title="Activar"><img src="img/ico/activo.gif" border="0" alt="Activar" /></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&desactivar&id='.$valor['kid_pagina'].'" title="Desactivar"><img src="img/ico/inactivo.gif" border="0" alt="Desactivar" /></a>';
								}
								/*EDITAR*/
								$opcionesBox1 .= '<a href="?est&edit&id='.$valor['kid_pagina'].'" title="Editar"><img src="img/ico/edit2.gif" border="0" alt="Editar" /></a>';
								/*VISIBLE/INVISIBLE*/
								if($valor['visible']==0){
									$opcionesBox1 .= '<a href="?est&change&visible&id='.$valor['kid_pagina'].'" title="Hacer visible"><img src="img/ico/visible.gif" border="0" alt="Hacer visible" /></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&invisible&id='.$valor['kid_pagina'].'" title="Hacer invisible"><img src="img/ico/invisible.gif" border="0" alt="Hacer invisible" /></a>';
								}
								/*BORRAR*/
								$opcionesBox1 .= '<a href="?est&delete&id='.$valor['kid_pagina'].'" title="Borrar" onclick="return confirmarBorrar();"><img src="img/ico/delete2.gif" border="0" alt="Borrar" /></a>';
								/*NUEVA*/
								$opcionesBox1 .= '<a href="?est&newpage&site='.$valor['kid_pagina'].'" id="w'.$this->numUnico.'" title="Nueva p&aacute;gina en este sitio"><img src="img/ico/webpage-new-small.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a>';
								/*UP*/
								$valor['orden']!=1?$opcionesBox1 .= '<a href="?est&change&up&id='.$valor['kid_pagina'].'" title="Subir un lugar"><img src="img/ico/up-page.gif" border="0" alt="Subir un lugar" /></a>':'';
								/*DOWN*/
								$valor['conteo']!=0?$opcionesBox1 .= '<a href="?est&change&down&id='.$valor['kid_pagina'].'" title="Bajar un lugar"><img src="img/ico/down-page.gif" border="0" alt="Bajar un lugar" /></a>':'';
								/*CERRAR*/
									$opcionesBox1 .='<a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><img src="img/ico/cerrar-sm.gif" border="0" alt="Cerrar opciones" /></a>
									<div class="fixed"></div>
								</div>
								</div>';
				$opcionesBox2 = '<div class="opciones boxNewPage" id="nw'.$this->numUnico.'" style="display:none;"></div>';
				echo "$opcionesBox1\n$opcionesBox2\n".'<div><img src="img/ico/website-small.gif" alt="Web site" />SITIO WEB: <a href="#'.$valor['kid_pagina'].'" id="'.$this->numUnico.'" title="$valor[clasificacion] $valor[nombre]" onclick="mostrarOpciones(this.id);return false;" class="mainSite">'.$valor["nombre"].'</a></div><ul>';
			
			}else{#no es nivel 0
				if($valor['conteo'] == 0) $cssClass=' class="end"'; else $cssClass='';
				if($valor['nivel'] == $nivelanterior){#es del mismo nivel
					echo "</li><li$cssClass>";
				}else if($valor['nivel'] > $nivelanterior){#es de mayor nivel
					echo "<ul><li$cssClass>";
				}else{#es de menor nivel
					$ciclos = $nivelanterior-$valor['nivel'];##necesario cerrar todos los niveles que hayan quedado abiertos
					for($i=0;$i < $ciclos;$i++){
						echo '</ul>';
					}
					echo "<li$cssClass>";
				}
				$opcionesBox1 = '<div class="opciones" id="op'.$this->numUnico.'" style="display:none;">
								<div class="marco">';
								/*ACTIVAR/DESACTIVAR*/
								if($valor['publicado']==0){
									$opcionesBox1 .= '<a href="?est&change&activar&id='.$valor['kid_pagina'].'" title="Activar"><img src="img/ico/activo.gif" border="0" alt="Activar" /></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&desactivar&id='.$valor['kid_pagina'].'" title="Desactivar"><img src="img/ico/inactivo.gif" border="0" alt="Desactivar" /></a>';
								}
								/*EDITAR*/
								$opcionesBox1 .= '<a href="?est&edit&id='.$valor['kid_pagina'].'" title="Editar"><img src="img/ico/edit2.gif" border="0" alt="Editar" /></a>';
								/*VISIBLE/INVISIBLE*/
								if($valor['visible']==0){
									$opcionesBox1 .= '<a href="?est&change&visible&id='.$valor['kid_pagina'].'" title="Hacer visible"><img src="img/ico/visible.gif" border="0" alt="Hacer visible" /></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&invisible&id='.$valor['kid_pagina'].'" title="Hacer invisible"><img src="img/ico/invisible.gif" border="0" alt="Hacer invisible" /></a>';
								}
								/*BORRAR*/
								$opcionesBox1 .= '<a href="?est&delete&id='.$valor['kid_pagina'].'" title="Borrar" onclick="return confirmarBorrar();"><img src="img/ico/delete2.gif" border="0" alt="Borrar" /></a>';
								/*NUEVA*/
								$opcionesBox1 .= '<a href="#" id="w'.$this->numUnico.'" title="Nueva p&aacute;gina en esta secci&oacute;n" onclick="mostrarNueva(this.id);return false;"><img src="img/ico/webpage-new-small.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a>';
								/*UP*/
								$valor['orden']!=1?$opcionesBox1 .= '<a href="?est&change&up&id='.$valor['kid_pagina'].'" title="Subir un lugar"><img src="img/ico/up-page.gif" border="0" alt="Subir un lugar" /></a>':'';
								/*DOWN*/
								$valor['conteo']!=0?$opcionesBox1 .= '<a href="?est&change&down&id='.$valor['kid_pagina'].'" title="Bajar un lugar"><img src="img/ico/down-page.gif" border="0" alt="Bajar un lugar" /></a>':'';
								/*CERRAR*/
									$opcionesBox1 .='<a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><img src="img/ico/cerrar-sm.gif" border="0" alt="Cerrar opciones" /></a>
									<div class="fixed"></div>
								</div>
								</div>';
				if($valor['nivel'] < 10){
					/*NUEVA*/
					$aumentoNivel='	<ul>
										<li class="end"><a href="?est&new&type=child&id='.$valor['kid_pagina'].'" title="Nueva p&aacute;gina en esta secci&oacute;n"><img src="img/ico/flecha.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a></li>
									</ul>';
				}else{
					$aumentoNivel='';
				}
				$opcionesBox2 = '<div class="opciones boxNewPage" id="nw'.$this->numUnico.'" style="display:none;">
								<div class="marco">
									<div class="fleft">
									<div class="mapsite mapicon">
									<ul>
										<li><a href="?est&new&type=inf&id='.$valor['kid_pagina'].'" title="Nueva p&aacute;gina en esta secci&oacute;n"><img src="img/ico/flecha.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a></li>
										<li>'.$valor["nombre"].' (p&aacute;gina actual)
										'.$aumentoNivel.'</li>
										<li><a href="?est&new&type=sup&id='.$valor['kid_pagina'].'" title="Nueva p&aacute;gina en esta secci&oacute;n"><img src="img/ico/flecha.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a></li>
									</ul>
									</div>
									</div>
									<div class="fleft">
									<a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><img src="img/ico/cerrar-sm.gif" border="0" alt="Cerrar opciones" /></a>									</div>
									<div class="fixed"></div>
								</div>
								</div>';
				$valor['publicado']==0?$cssLinkClass = "inactivo":$cssLinkClass = "";
				$valor['visible']==0?$cssDivClass = " invisible":$cssDivClass = "";
				$valor['publicado']==0?$imgPublic = "<img src='img/ico/node-inactivo.gif' />":$imgPublic = "<img src='img/ico/node-activo.gif' />";
				$valor['visible']==0?$imgVisible = "<img src='img/ico/node-invisible.gif' />":$imgVisible = "<img src='img/ico/node-visible.gif' />";
				echo "$opcionesBox1\n$opcionesBox2\n<div class='".$cssDivClass."'><a href='#".$valor['kid_pagina']."' id='".$this->numUnico."' title='$valor[clasificacion] $valor[nombre]' onclick='mostrarOpciones(this.id);return false;' class='".$cssLinkClass."'>$imgPublic $imgVisible $valor[nombre]</a></div>";
			}
			$nivelanterior = $valor['nivel'];
			$this->numUnico++;
		}
		//echo '</ul>';
		$ciclos = $nivelanterior;#necesario cerrar todos los niveles que hayan quedado abiertos
		for($i=0;$i <= $ciclos;$i++){
			echo '</ul>';
		}
		echo '<script type="text/javascript" language="javascript">
		var totalLinks = '.($this->numUnico--).';
		/*ocultarOpciones();*/
		</script>';
	}

}
?>