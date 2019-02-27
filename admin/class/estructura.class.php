<?php
class Estructura{
	var $estructura = array();
	var $numUnico = 0;
	var $admin_path = ADMIN_URL;
	
	function crearEstructura($estructura=array(),$consecutivo=0){
		if(empty($estructura)) {echo "No se especificaron datos para producir la estructura";return false;}#si no hay datos, no procesa
		$nivelanterior = 0;
		$this->numUnico ++;
		
		foreach($estructura as $key => $valor){
			$id = $valor['kid_pagina'];
			$opcionesBox1 = '';
			$opcionesBox2 = '';
			if($valor['nivel']==0){
				$opcionesBox1 = '<div class="opciones" id="op'.$this->numUnico.'" style="display:none;" option-block="'.$id.'">
								<div class="marco">';
								/*ACTIVAR/DESACTIVAR*/
								$colorCSS = 'colorGrisClaro';
								if($valor['publicado']==0){
									$link = 'activar';
									$title = 'Activar';
									$icon = 'circle';
								}else{
									$link = 'desactivar';
									$title = 'Desactivar';
									$icon = 'minus-circle';
								}
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*EDITAR*/
								$link = 'edit';
								$title = 'Editar';
								$icon = 'pen';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*VISIBLE/INVISIBLE*/
								if($valor['visible']==0){
									$link = 'visible';
									$title = 'Hacer visible';
									$icon = 'eye';
								}else{
									$link = 'invisible';
									$title = 'Hacer invisible';
									$icon = 'eye-slash';
								}
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*BORRAR*/
								$link = 'delete';
								$title = 'Borrar';
								$icon = 'eraser';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								#$opcionesBox1 .= '<a href="?est&delete&id='.$valor['kid_pagina'].'" title="Borrar" onclick="return confirmarBorrar();"><i class="fa fa-eraser colorRojo"></i></a>';
								/*NUEVA*/
								$link = 'newpage';
								$title = 'Nueva p&aacute;gina en este sitio';
								$icon = 'file';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								#$opcionesBox1 .= '<a href="?est&newpage&site='.$valor['kid_pagina'].'" id="w'.$this->numUnico.'" title="Nueva p&aacute;gina en este sitio"><i class="fa fa-file colorVerde"></i></a>';
								/*UP*/
								if($valor['orden']!=1){
									$link = 'up';
									$title = 'Subir un lugar';
									$icon = 'chevron-circle-up';
									$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
									#?$opcionesBox1 .= '<a href="?est&change&up&id='.$valor['kid_pagina'].'" title="Subir un lugar"><i class="fa fa-chevron-circle-up colorVerde"></i></a>':'';
								}
								/*DOWN*/
								if($valor['conteo']!=0){
									$link = 'down';
									$title = 'Bajar un lugar';
									$icon = 'chevron-circle-down';
									$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
									#?$opcionesBox1 .= '<a href="?est&change&down&id='.$valor['kid_pagina'].'" title="Bajar un lugar"><i class="fa fa-chevron-circle-down colorRojo"></i></a>':'';
								}
								/*CERRAR*/
								$link = 'btnCerrar';
								$title = 'Cerrar opciones';
								$icon = 'times';
								$btnCSS = 'btnCerrar';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS,$btnCSS);
								#	$opcionesBox1 .='<a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><i class="fa fa-window-close colorRojo"></i></a>
								$opcionesBox1 .= '<div class="fixed"></div>
								</div>
								</div>';
				$opcionesBox2 = '<div class="opciones boxNewPage" id="nw'.$this->numUnico.'" style="display:none;" option-2-block="'.$id.'"></div>';
				echo "$opcionesBox1\n$opcionesBox2\n".'<div><i class="fa fa-globe-americas colorMorado"></i> SITIO WEB: <a href="#'.$id.'" id="'.$this->numUnico.'" title="´'.$valor['clasificacion'].' '.$valor['nombre'].'" class="mainSite btnPagina clickeable">'.$valor["nombre"].'</a></div>';
			
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
				$opcionesBox1 = '<div class="opciones" id="op'.$this->numUnico.'" option-block="'.$id.'" style="display:none;">
								<div class="marco">';
								/*ACTIVAR/DESACTIVAR*/
								$colorCSS = 'colorGrisClaro';
								if($valor['publicado']==0){
									$link = 'activar';
									$title = 'Activar';
									$icon = 'circle';
								}else{
									$link = 'desactivar';
									$title = 'Desactivar';
									$icon = 'minus-circle';
								}
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*if($valor['publicado']==0){
									$opcionesBox1 .= '<a href="?est&change&activar&id='.$valor['kid_pagina'].'" title="Activar"><i class="fa fa-circle colorVerde"></i></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&desactivar&id='.$valor['kid_pagina'].'" title="Desactivar"><i class="fa fa-minus-circle colorGris"></i></a>';
								}*/
								/*EDITAR*/
								$link = 'edit';
								$title = 'Editar';
								$icon = 'pen';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								#$opcionesBox1 .= '<a href="?est&edit&id='.$valor['kid_pagina'].'" title="Editar"><i class="fa fa-pen colorAzul"></i></a>';
								/*VISIBLE/INVISIBLE*/
								if($valor['visible']==0){
									$link = 'visible';
									$title = 'Hacer visible';
									$icon = 'eye';
								}else{
									$link = 'invisible';
									$title = 'Hacer invisible';
									$icon = 'eye-slash';
								}
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*if($valor['visible']==0){
									$opcionesBox1 .= '<a href="?est&change&visible&id='.$valor['kid_pagina'].'" title="Hacer visible"><i class="fa fa-eye colorAzul"></i></a>';
								}else{
									$opcionesBox1 .= '<a href="?est&change&invisible&id='.$valor['kid_pagina'].'" title="Hacer invisible"><i class="fa fa-eye-slash colorGris"></i></a>';
								}*/
								/*BORRAR*/
								$link = 'delete';
								$title = 'Borrar';
								$icon = 'eraser';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*$opcionesBox1 .= '<a href="?est&delete&id='.$valor['kid_pagina'].'" title="Borrar" onclick="return confirmarBorrar();"><i class="fa fa-eraser colorRojo"></i></a>';*/
								/*NUEVA*/
								$link = 'newpage';
								$title = 'Nueva p&aacute;gina en esta secci&oacute;n';
								$icon = 'file';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
								/*$opcionesBox1 .= '<a href="#" id="w'.$this->numUnico.'" title="Nueva p&aacute;gina en esta secci&oacute;n" onclick="mostrarNueva(this.id);return false;"><i class="fa fa-file colorVerde"></i></a>';*/
								/*UP*/
								if($valor['orden']!=1){
									$link = 'up';
									$title = 'Subir un lugar';
									$icon = 'chevron-circle-up';
									$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
									#?$opcionesBox1 .= '<a href="?est&change&up&id='.$valor['kid_pagina'].'" title="Subir un lugar"><i class="fa fa-chevron-circle-up colorVerde"></i></a>':'';
								}
								#$valor['orden']!=1?$opcionesBox1 .= '<a href="?est&change&up&id='.$valor['kid_pagina'].'" title="Subir un lugar"><i class="fa fa-chevron-circle-up colorVerde"></i></a>':'';
								/*DOWN*/
								if($valor['conteo']!=0){
									$link = 'down';
									$title = 'Bajar un lugar';
									$icon = 'chevron-circle-down';
									$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS);
									#?$opcionesBox1 .= '<a href="?est&change&down&id='.$valor['kid_pagina'].'" title="Bajar un lugar"><i class="fa fa-chevron-circle-down colorRojo"></i></a>':'';
								}
								#$valor['conteo']!=0?$opcionesBox1 .= '<a href="?est&change&down&id='.$valor['kid_pagina'].'" title="Bajar un lugar"><i class="fa fa-chevron-circle-down colorRojo"></i></a>':'';
								/*CERRAR*/
								$link = 'btnCerrar';
								$title = 'Cerrar opciones';
								$icon = 'times';
								$btnCSS = 'btnCerrar';
								$opcionesBox1 .= self::crearBoton($id,$link,$title,$icon,$colorCSS,$btnCSS);
									#$opcionesBox1 .='<a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><i class="fa fa-window-close colorRojo"></i></a>
								$opcionesBox1 .=	'<div class="fixed"></div></div></div>';
				if($valor['nivel'] < 10){
					/*NUEVA*/
					$aumentoNivel='<ul><li class="end"><a href="?est&new&type=child&id='.$id.'" title="Nueva p&aacute;gina en esta secci&oacute;n"><i class="fa fa-arrow-left colorVerde"></i></li></ul>';
				}else{
					$aumentoNivel='';
				}

				$linkNewPage = CURRENT_SECCION."new/";
				$opcionesBox2 = makeTemplate('_bloque_newPage.html', $datos = array('id'=>$id,'nombre'=>$valor["nombre"], 'link'=>$linkNewPage), 'admin');
				
				/*
				$opcionesBox2 = '<div class="opciones boxNewPage" id="nw'.$this->numUnico.'" style="display:none;" option-2-block="'.$id.'"><div class="fleft">SOY !';
				$opcionesBox2 .= '<div class="mapicon">';
				$opcionesBox2 .= '<ul><li><a href="?est&new&type=inf&id='.$id.'" title="Nueva p&aacute;gina en esta secci&oacute;n"><i class="fa fa-arrow-left colorVerde"></i></li><li>'.$valor["nombre"].' (p&aacute;gina actual)'.$aumentoNivel.'</li><li><a href="?est&new&type=sup&id='.$id.'" title="Nueva p&aacute;gina en esta secci&oacute;n"><i class="fa fa-arrow-left colorVerde"></i></a></li></ul>';
				$opcionesBox2 .= '</div></div><div class="fleft"><a href="#" title="Cerrar opciones" class="btnCerrar" onclick="ocultarOpciones();return false;"><i class="fa fa-window-close colorRojo"></i></a></div><div class="fixed"></div></div>';
				*/

				/*$valor['publicado']==0?$cssLinkClass = "inactivo":$cssLinkClass = "";
				$valor['visible']==0?$cssDivClass = " invisible":$cssDivClass = "";
				$valor['publicado']==0?$imgPublic = "<i class='fa fa-minus-circle colorGris'></i>":$imgPublic = "<i class='fa fa-circle colorVerde'></i>";
				$valor['visible']==0?$imgVisible = "<i class='fa fa-eye-slash colorGris'></i>":$imgVisible = "<i class='fa fa-eye colorAzul'></i>";*/
				$publicado = $valor['publicado']==0?FALSE:TRUE;
				$visible = $valor['visible']==0?FALSE:TRUE;
				$clasificacion = $valor['clasificacion'];
				$nombre = $valor['nombre'];
				echo self::crearNodo($id,$publicado,$visible,$clasificacion,$nombre,$opcionesBox=array(1=>$opcionesBox1,2=>$opcionesBox2));
				

				#echo "{$opcionesBox1}\n{$opcionesBox2}\n<div class='".$cssDivClass."'><a href='#".$valor['kid_pagina']."' id='".$this->numUnico."' title='{$valor['clasificacion']} {$valor['nombre']}' onclick='mostrarOpciones(this.id);return false;' class='".$cssLinkClass."'><span class='miniOpcion'>$imgPublic $imgVisible</span> {$valor['nombre']}</a></div>";
			}
			$nivelanterior = $valor['nivel'];
			$this->numUnico++;
		}
		//echo '</ul>';
		$ciclos = $nivelanterior;#necesario cerrar todos los niveles que hayan quedado abiertos
		for($i=0;$i <= $ciclos;$i++){
			echo '</ul>';
		}
		
	}
	function crearBoton($id,$link,$title,$icon,$colorCSS,$btnCSS=''){
		return "<a href='{$this->admin_path}CMS/{$link}/{$id}' title='{$title}' class='{$btnCSS}' data-id='{$id}'><i class='fa fa-{$icon} {$colorCSS}'></i></a>";
	}
	function crearNodo($id,$publicado,$visible,$clasificacion,$nombre,$opcionesBox=array(1=>'',2=>'')){
		$cssLinkClass = '';
		$cssDivClass = '';
		$iconPublicado = 'circle';
		$colorPublicado = 'colorVerde';
		$iconVisible = 'eye';
		$colorVisible = 'colorAzul';
		if(!$publicado){//NO PUBLICADO
			$cssLinkClass = "inactivo";
			$iconPublicado = 'minus-circle';
			$colorPublicado = 'colorGris';
		}
		$imgPublicado = "<i class='fa fa-{$iconPublicado} {$colorPublicado}'></i>";
		if(!$visible){//NO VISIBLE/NO PÚBLICO
			$cssDivClass = " novisible";
			$iconVisible = 'eye-slash';
			$colorVisible = 'colorGris';
		}
		$imgVisible = "<i class='fa fa-{$iconVisible} {$colorVisible}'></i>";
		return "{$opcionesBox[1]}\n{$opcionesBox[2]}\n<div class='".$cssDivClass."'><a id='".$id."' title='{$clasificacion} {$nombre}' class='btnPagina clickeable ".$cssLinkClass."'><span class='miniOpcion'>$imgPublicado $imgVisible</span> {$nombre}</a></div>";
	}
}
?>