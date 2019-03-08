<?php
$index = TRUE;

$siteURL = WEB_PATH;

if(!is_index()) { $page = $data1; $index = FALSE; }

function is_index(){
	global $data1;
	if($data1 == 'index') return true;else return false;
}

class Page{
	var $page = 'index';
	var $id = 0;
	var $siteURL = '';
	var $myAdmin = '';
	var $pageData = array();
	var $contenido = array();
	var $ultimaActualizacion = '';
	var $p404 = FALSE;
	var $withSubs = FALSE;
	var $nivelSuperior = '';
	var $calificacion = '-';
	var $miCalificacion = 0;
	var $numero_votos = 0;
	var $comentarios = array();
	var $estructura = array();
	
	function __construct($page){
		global $siteURL;
		
		$this->page = convertirDatoSeguro($page);
		$this->siteURL = $siteURL;
		$this->myAdmin = new Conexion();
		$this->myAdmin->debug=TRUE;
		$this->myAdmin->conectar(BD_HOST,DB_USER,DB_PSW,DB_NAME);


		//OBTIENE ESTRUCTURA DEL SITIO
		$queryEst = "SELECT * FROM ".PREFIJO."view_estructura ORDER BY clasificacion ASC";
		$resultadoEst = $this->myAdmin->query($queryEst);
		$procesadoEst = $this->myAdmin->fetch($resultadoEst);
		$this->estructura = $procesadoEst;

		//OBTIENE DATOS DE LA PAGINA
		$query = "SELECT *,
					(SELECT filepath FROM ".PREFIJO."plantilla WHERE kid_plantilla=plantilla) as plantilla_filepath  FROM ".PREFIJO."view_estructura WHERE alias='".$this->page."' AND publicado=1";
		$resultado = $this->myAdmin->query($query);
		$procesado = $this->myAdmin->fetch($resultado);
		
		if($procesado == null && $this->page=='index') die('No hay un sitio instalado aún.');

		if(count($procesado) > 0){
			$this->pageData=$procesado[0];

			$this->obtenerNivelSuperior();

			$this->id = $this->pageData['kid_pagina'];

			$query = "SELECT * FROM ".PREFIJO."contenido WHERE fid_estructura={$this->id}";

			$resultado = $this->myAdmin->query($query);
			$procesado = $this->myAdmin->fetch($resultado);
			$this->contenido = $procesado[0];

			$this->contenido['contenido'] = str_replace("../webimgs","webimgs",html_entity_decode($this->contenido['contenido']));

			if($this->contenido['fecha_modificacion'] != NULL){
				$this->ultimaActualizacion = date('l d \d\e F \d\e Y \a \l\a\s H:i:s',strtotime($this->contenido['fecha_modificacion']));
			}else{
				$this->ultimaActualizacion = date('l d \d\e F \d\e Y \a \l\a\s H:i:s',strtotime($this->contenido['fecha_alta']));
			}

			$this->establecerPageRank();
		}else{
			#echo "NO EXISTE";
			$this->id = 99999;
			$this->p404 = TRUE;
			$this->pageData = array("nombre"=>"P&aacute;gina no encontrada","plantilla"=>1,"alias"=>'404',"descripcion"=>"No existe la página","keywords"=>"404,página no encontrada");
			$this->contenido = array("contenido"=>"La p&aacute;gina que esta intentando ver no existe.","nombre_responsable"=>"Administrador de Soy Biossmann");
		}
		$this->withSubs = $this->getSubsecciones();
	}
	function mostrarDestacados($idPage,$parentPage,$grandparentPage){
		#0 = heredar
		#999 = no mostrar
		
		$qry = $this->myAdmin->fetch($this->myAdmin->query("SELECT grupodestacados FROM ".PREFIJO."estructura WHERE kid_pagina=$idPage AND visible=1"));
		
		if(count($qry)>0){
			$grupoDestacados = end(end($qry));
			if($grupoDestacados == 0) {
				$idPage = $parentPage;
				$grupoDestacados = end(end($this->myAdmin->fetch($this->myAdmin->query("SELECT grupodestacados FROM ".PREFIJO."estructura WHERE kid_pagina=$idPage AND visible=1"))));
				if($grupoDestacados == 0) {
					$idPage = $grandparentPage;
					$grupoDestacados = end(end($this->myAdmin->fetch($this->myAdmin->query("SELECT grupodestacados FROM ".PREFIJO."estructura WHERE kid_pagina=$idPage AND visible=1"))));
					if($grupoDestacados == 0) {
						$grupoDestacados = 999; #el nivel superior hereda, por lo que no se muesta nada
					}
				}
			}
		}else{
			$grupoDestacados=999;
		}
		
		
		if($grupoDestacados != 999){
			$dataGrupo = end($this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."grupo_destacados WHERE kid_grupo=$grupoDestacados AND visible=1")));
			if(count($dataGrupo) > 1){
			
			#fijo en 2 por posicion: $dataProductos = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."destacados WHERE grupo=$grupoDestacados AND visible=1 ORDER BY posicion ASC LIMIT ".$dataGrupo['cantidad']));
			$dataProductos = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."destacados WHERE grupo=$grupoDestacados AND visible=1"));
			
			#echo $dataGrupo['titulo'];
			if($dataGrupo['titulo']!='Cat&aacute;logos'){
			?>
            <h2>Productos destacados</h2>
            	<?php 
			}
				#foreach($dataProductos as $product){ 
				shuffle($dataProductos);
				if(count($dataProductos) < $dataGrupo['cantidad']) $dataGrupo['cantidad'] = count($dataProductos);
				for($i=0;$i<$dataGrupo['cantidad'];$i++){
					$product = $dataProductos[$i];
				?>
                <div class="shadow">
                    <div class="foto">
                        <div class="thumb">
                            <?php if($product['link'] != '') {?>
                            <a href="<?php echo $product['link']; ?>">
                            <?php } ?>
                            <img width="145" style="background:#f5f5f5" alt="<?php echo $product['titulo'] ?>" title="<?php echo $product['titulo'] ?>" src="<?php echo WEB_PATH."webimgs/destacadas/".$product['img'] ?>" />
                            <?php if($product['link'] != '') {?>
                            </a>
                            <?php } ?>
                        </div>
                        <div class="desc">
                            <img src="img/mega-bullet.gif" /> <?php echo $product['titulo'] ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php
			}
		
		}
	}
	function getMenuPrincipal(){
		$buffer = '';
		$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre=1 AND visible=1 AND publicado=1 ORDER BY clasificacion"));
		$buffer .= '<ul id="menunav" class="navbar-nav mr-auto menu">';
		foreach($menu as $elem){
			$childrens='';
			$menuSub = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre={$elem['kid_pagina']} AND visible=1 AND publicado=1 ORDER BY clasificacion"));
			if(count($menuSub)>0){ $childrens.="<ul>"; }
			foreach($menuSub as $elemSub){
				$actual = '';
				if($elemSub['alias'] == $this->nivelSuperior) $actual = ' active';

				$childrens.= '<li class="'.$actual.'"><a href="'.$this->siteURL.$elemSub['alias'].'">'.$elemSub['nombre'].'</a> </li>';
			}
			if(count($menuSub)>0){ $childrens.="</ul>"; }

			$actualMain = '';
			if($elem['alias'] == $this->nivelSuperior) $actualMain = ' active';

				$buffer .= "<li class='{$elem['selector']} {$actualMain}'><a class='nav-link' href='{$this->siteURL}{$elem['alias']}'>{$elem['nombre']}</a> {$childrens}</li>";
		}
		$buffer .= '</ul>';
		return $buffer;
	}
	function mostrarSubsecciones(){
		$buffer = '';
		if(!$this->p404){
			$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre={$this->id} AND publicado=1 ORDER BY clasificacion ASC"));
			if(count($menu)>0){
				$buffer .= "<div class='listaLinks listaColorazul material bordeGris'>";
				$buffer .= "<h2>{$this->pageData['nombre']}</h2>";
				$buffer .= "<ul class='bold'>";
				foreach($menu as $elem){
					$desc = '';
					if($elem['descripcion'] != ''){ $desc = "<span class='desc'>{$elem['descripcion']}</span>"; }
					$buffer .= "<li><a href='{$this->siteURL}/{$elem['alias']}'>{$elem['nombre']} $desc</a></li>";
				}
				$buffer .= "</ul>";
				$buffer .= "</div>";
			}
		}
		return $buffer;
	}
	function mostrarMenuSecundario(){
		$buffer = "";
		$firstChild = 1;
		if(!$this->p404){
			$menu = array();
			$menuEncabezado = array();
			$idPadre = -1;

			foreach ($this->estructura as $nodo) {
				//COMPROBAR ESTE ACTIVAS Y VISIBLES
				if($nodo['visible'] != 1 || $nodo['publicado'] != 1) continue;//NO VISIBLE || NO PUBLICADO
				if($this->pageData['padre'] == $nodo['kid_pagina']){//PADRE
					//$nodo['kid_pagina']['definicion'] = 'padre';
					$menuEncabezado[] = $nodo;
				}
				if($this->pageData['alias'] == $nodo['alias']){//ACTUAL
					$idPadre = $nodo['kid_pagina'];
					$nodo['definicion'] = 'actual';
					$menu[] = $nodo;
					//echo ">>> [ID: ".$idPadre."] ";
				}else 
				if($this->pageData['padre'] == $nodo['padre']){//HERMANOS
					$nodo['definicion'] = 'hermano';
					$menu[] = $nodo;
					//echo "*";
				}else 
				if($idPadre == $nodo['padre']){//HIJOS	
					$nodo['definicion'] = 'hijo';
					$menu[] = $nodo;
					//echo "------ ";		
				} 	
			}
			if(count($menu)>0){
				$buffer .= "<div class='renglon material margenPosterior'>";
            	$buffer .= "<div class='bloque_subMenu'>";
            		$buffer .= "<h3>{$menuEncabezado[0]['nombre']}</h3>";
				$buffer .= "<ul>";
				foreach($menu as $elem){
					$elemActual = $elem['definicion']=='actual'?' selected':'';
					$elemHijo = $elem['definicion']=='hijo'?'indentLevel':'';
					if($elemHijo != '' && $firstChild==1){ $elemHijo .= ' firstChild'; $firstChild=0; }
					$buffer .= "<li class='{$elemHijo}{$elemActual}'>";
					$setLink = FALSE;
					if($elem['definicion']!='actual'){ $setLink = TRUE; }
					if($setLink){
						$buffer .= "<a href='{$this->siteURL}/{$elem['alias']}'>";
					}
					$buffer .= "{$elem['nombre']}";
					if($setLink){ $buffer .= "</a>"; }
					$buffer .= "</li>";
				}
				$buffer .= "</ul>";
				$buffer .= "</div>";
         	 	$buffer .= "</div>";
			}
		}
		return $buffer;
	}
	function mostrarMenuSecundario_OLD(){
		if(!$this->p404){
			//$menu = 
			
			if($this->pageData['nivel'] == 1){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre={$this->id} AND visible=1"));
			}else if($this->pageData['nivel'] == 2){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 3){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 4){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 5){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 6){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']},{$this->pageData['lvl4']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 7){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']},{$this->pageData['lvl4']},{$this->pageData['lvl5']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 8){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']},{$this->pageData['lvl4']},{$this->pageData['lvl5']},{$this->pageData['lvl6']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 9){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']},{$this->pageData['lvl4']},{$this->pageData['lvl5']},{$this->pageData['lvl6']},{$this->pageData['lvl7']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 10){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']},{$this->pageData['lvl2']},{$this->pageData['lvl3']},{$this->pageData['lvl4']},{$this->pageData['lvl5']},{$this->pageData['lvl6']},{$this->pageData['lvl7']},{$this->pageData['lvl8']}) AND visible=1"));
			}
			
			if(count($menu)>0){
				?><h3><?php
               		if($this->pageData['nivel'] == 1){
						echo $this->pageData['nombre'];
					}else if($this->pageData['nivel'] == 2){
						echo  $this->pageData['nombrePadre'];
					}else if($this->pageData['nivel'] == 3){
						echo  $this->pageData['nombreAbuelo'];
					}
				?></h3>
				<ul><?php
				?><?php
				foreach($menu as $elem){
					?><li<?php if($elem['nivel']>=3) echo ' class="indentLevel"'?>>
                    <?php # print_r($elem); ?>
                    <?php if($elem['alias'] != $this->pageData['alias']){ ?>
						<a href="<?php echo $this->siteURL.'/'.$elem['alias']; ?>"<?php if($elem['kid_pagina'] == $this->pageData['iniPadre']){ echo ' class="selected"';} ?>><?php echo $elem['nombre']; ?></a>
                    <?php }else{ ?>
						<a class="selected <?php if($this->pageData['nivel']>=3) echo "selnivel3" ?>"><?php echo $elem['nombre']; ?></a>
                    <?php } ?>
					</li>
					<?php
				}
				?></ul>
				<?php
			}
		}
	}
	function mostrarMenuSecundarioORIGINAL_conservar(){
		if(!$this->p404){
			if($this->pageData['nivel'] == 1){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre={$this->id} AND visible=1"));
			}else if($this->pageData['nivel'] == 2){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']}) AND visible=1"));
			}else if($this->pageData['nivel'] == 3){
				$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre IN({$this->id},{$this->pageData['iniPadre']},{$this->pageData['lvl1']}) AND visible=1"));
			}
			
			if(count($menu)>0){
				?><h3><?php
               		if($this->pageData['nivel'] == 1){
						echo $this->pageData['nombre'];
					}else if($this->pageData['nivel'] == 2){
						echo  $this->pageData['nombrePadre'];
					}else if($this->pageData['nivel'] == 3){
						echo  $this->pageData['nombreAbuelo'];
					}
				?></h3>
				<ul><?php
				?><?php
				foreach($menu as $elem){
					?><li<?php if($elem['nivel']>=3) echo ' class="indentLevel"'?>>
                    <?php # print_r($elem); ?>
                    <?php if($elem['alias'] != $this->pageData['alias']){ ?>
						<a href="<?php echo $this->siteURL.'/'.$elem['alias']; ?>"<?php if($elem['kid_pagina'] == $this->pageData['iniPadre']){ echo ' class="selected"';} ?>><?php echo $elem['nombre']; ?></a>
                    <?php }else{ ?>
						<a class="selected <?php if($this->pageData['nivel']>=3) echo "selnivel3" ?>"><?php echo $elem['nombre']; ?></a>
                    <?php } ?>
					</li>
					<?php
				}
				?></ul>
				<?php
			}
		}
	}
	function getSubsecciones(){
		if(!$this->p404){
			$menu = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE iniPadre={$this->id} AND visible=1"));
			if(count($menu)>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	function mostrarBreadcrumb($otroNombre='',$separador='|'){
		$buffer = '';
		if(!$this->p404){

			$qBread = "SELECT * FROM ".PREFIJO."view_estructura WHERE kid_pagina={$this->id}";
			$resBread = $this->myAdmin->query($qBread);
			$fetchBread = $this->myAdmin->fetch($resBread);
			$bread = $fetchBread[0];
			$arrLevels = array();
			//$arrLevels[] = $bread['padre'];
			if($bread['lvl10']!=''){$arrLevels[]=$bread['lvl10'];}
            if($bread['lvl9']!=''){$arrLevels[]=$bread['lvl9'];}
            if($bread['lvl8']!=''){$arrLevels[]=$bread['lvl8'];}
            if($bread['lvl7']!=''){$arrLevels[]=$bread['lvl7'];}
            if($bread['lvl6']!=''){$arrLevels[]=$bread['lvl6'];}
            if($bread['lvl5']!=''){$arrLevels[]=$bread['lvl5'];}
            if($bread['lvl4']!=''){$arrLevels[]=$bread['lvl4'];}
            if($bread['lvl3']!=''){$arrLevels[]=$bread['lvl3'];}
            if($bread['lvl2']!=''){$arrLevels[]=$bread['lvl2'];}
			if($bread['lvl1']!=''){$arrLevels[]=$bread['lvl1'];}
			if($bread['padre']!=''){$arrLevels[]=$bread['padre'];}
            //echo "<pre>";
			//print_r($arrLevels);
			$buffer .= "<div class='breadcrumb right'>";
			$buffer .= "<a href='".APP_URL."'>Inicio</a>";
			#print_r($arrLevels);
			foreach($arrLevels as $elem){
				#echo "--$elem--";
				if($elem == NULL || $elem == 0 || $elem == 1) continue;
				$tempQry = "SELECT nombre,alias FROM ".PREFIJO."estructura WHERE kid_pagina={$elem}";
				$tempRes = $this->myAdmin->query($tempQry);
				$tempFetch = $this->myAdmin->fetch($tempRes);
				$pageName = $tempFetch[0];
				
				$buffer .= "<span class='separador'>{$separador}</span> <a href='{$this->siteURL}/{$pageName['alias']}'>{$pageName['nombre']}</a>";
				
			}
			$nombrePaginaActual = $otroNombre!=''?$otroNombre:$this->pageData['nombre'];
			$buffer .= "<span class='separador'>{$separador}</span> {$nombrePaginaActual}</div>";
		}
		return $buffer;
	}
	function mostrarLista($id){
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_links WHERE identificador='$id' AND visible=1";
		$group =	$this->myAdmin->fetch($this->myAdmin->query($queryGroup));
		#print_r($group);
		if(count($group)==1) $continuar = true; else $continuar = false;
		if($continuar){
			$grupoSel = $group[0]['kid_grupo'];
			
			$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."lista_links WHERE grupo='$grupoSel'";
			$resInit =	$this->myAdmin->fetch($this->myAdmin->query($queryT));
			#echo $queryT;
			if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
				
			if($continuar){
				$query = $this->myAdmin->query("SELECT * FROM ".PREFIJO."lista_links WHERE grupo=$grupoSel AND visible=1 ORDER BY posicion ASC");#LIMIT ".$paginacion['LIMIT']
			#echo $query;
			$arr = $this->myAdmin->fetch($query);
			?><div class="listaLinks listaColor<?php echo $group[0]['selector']; ?>"><div class="titulo"><?php echo $group[0]['titulo']; ?></div><ul><?php
			foreach($arr as $reg){
			?>
				<li class="<?php echo $reg['selector']; ?>">
					<?php if($reg['link']!=''){ ?><a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['titulo']; ?>" <?php if($reg['icono']!='') {?>target="_blank"<?php } ?>><?php } ?>
					<?php echo html_entity_decode($reg['titulo']); ?>
					<?php if($reg['icono']!='') {?><img src="<?php echo WEB_PATH."img/iconos/".$reg['icono'].".gif"; ?>" alt="<?php echo $reg['icono']; ?>" /><?php } ?>
					<?php if($reg['link']!=''){ ?></a><?php } ?>
				</li>
			<?php  }//no imprimir no hay registros ?>
			</ul><div class="fixed"></div></div>
		<?php }//no hay banners
		}//no está el grupo 
	}
	
	function mostrarBanners($id){
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_banners WHERE identificador='$id' AND visible=1";
		$group =	$this->myAdmin->fetch($this->myAdmin->query($queryGroup));
		if(count($group)==1) $continuar = true; else $continuar = false;
		if($continuar){
		$grupoSel = $group[0]['kid_grupo'];
		
		$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."banners WHERE grupo='$grupoSel'";
		$resInit =	$this->myAdmin->fetch($this->myAdmin->query($queryT));
		if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
			
		if($continuar){
				#$paginacion = $this->myAdmin->paginar($queryT,10,10,"$vars");
				
				$query = $this->myAdmin->query("SELECT * FROM ".PREFIJO."banners WHERE grupo=$grupoSel AND visible=1 ORDER BY posicion ASC");#LIMIT ".$paginacion['LIMIT']
		}
				
			$arr = $this->myAdmin->fetch($query);
			?><div class="listaBanners listaColor<?php echo $group[0]['selector']; ?>"><div class="titulo"><?php echo $group[0]['titulo']; ?></div><?php
			foreach($arr as $reg){
					?>
					<?php if($reg['link']!=''){ ?>
						<a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['alt']; ?>" target="_blank"><?php } ?>
					<?php if($reg['img']!='') {?>
							<img src="<?php echo WEB_PATH."webimgs/banners/".$reg['img']; ?>" alt="<?php echo $reg['alt']; ?>" />
						<?php } ?>
					<?php if($reg['link']!=''){ ?>
						</a>
					<?php } ?>
		<?php  }//no imprimir no hay registros ?>
		<div class="fixed"></div></div>
		<?php }//no está el grupo 
	}
	function obtenerBanners($identificador){
		$datos = array();
		$datos['error'] = '';
		$banners = array();
		if($identificador == ''){
			$datos['error'] = "Falta indicar el identificador";
		}
		$query = "SELECT * FROM cms_banners WHERE (SELECT identificador FROM cms_grupo_banners WHERE grupo = kid_grupo)='$identificador' AND visible=1 ORDER BY posicion ASC, kid_banner ASC";
		$banners = $this->myAdmin->fetch($this->myAdmin->query($query));
		$datos['count'] = count($banners);
		$datos['query'] = $query;
		$datos['data'] = $banners;
		return $datos;
	}
	function mostrarMapaDeSitio(){
		$arrExcept = array("'sala-de-prensa-detalle'","'mapa-de-sitio'","'buscar'","'gracias'");
		$arrExcept = implode(",",$arrExcept);
		$numUnico = 0;
		$estructura = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."view_estructura WHERE publicado=1 AND alias NOT IN ($arrExcept)"));
		
		if(empty($estructura)) {echo "No se especificaron datos para mostrar el mapa de sitio";return false;}#si no hay datos, no procesa
		$nivelanterior = 0;
		
		foreach($estructura as $key => $valor){
			if($valor['nivel']==0){
				echo '<div><a href="'.WEB_PATH.'" class="mainSite">'.$valor["nombre"].'</a></div><ul>';
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
				if($valor['nivel'] < 10){
					/*NUEVA*/
					$aumentoNivel='	<ul>
										<li class="end"><a href="?est&new&type=child&id='.$valor['numero'].'" title="Nueva p&aacute;gina en esta secci&oacute;n"><img src="img/ico/flecha.gif" border="0" alt="Nueva p&aacute;gina en esta secci&oacute;n" /></a></li>
									</ul>';
				}else{
					$aumentoNivel='';
				}
				
				echo "<div><a href='".$this->siteURL.'/'.$valor['alias']."' >$valor[nombre]</a></div>";
			}
			$nivelanterior = $valor['nivel'];
			$numUnico++;
		}
		//echo '</ul>';
		$ciclos = $nivelanterior;#necesario cerrar todos los niveles que hayan quedado abiertos
		for($i=0;$i <= $ciclos;$i++){
			echo '</ul>';
		}
		
	}
	function mostrarNoticia($id=0){
	include(LIB_PATH."functions.inc.php");
		if($id==0){//es noticia principal
			$noticia = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."noticias WHERE visible=1 ORDER BY fecha DESC, kid_noticia DESC LIMIT 1"));
			if(count($noticia)>0){
				foreach($noticia as $elem){
				?>
<div class="bloque bloqueNoticias">
	<div class="titulo"><div class="cuadro"></div></div>
		<table class="layout" summary="layout" width="100%">
		 	<tr>
				<?php if($elem['imagen'] != ''){ ?>
				<td class="celdaImagen" valign="top"><img src="<?php echo WEB_NEWS_PATH."thumb/".$elem['imagen']; ?>" width="200" alt="<?php echo $elem['titulo'] ?>" /></td>
				<?php } ?>
				<td valign="top">
				<div class="textoInterno">
					<h3><?php echo $elem['titulo'] ?></h3>
					<span style="color:#555"><?php echo formatDate($elem['fecha']); ?></span><br /><br />
					 <?php echo $elem['resumen'] ?>
				  <div class="fixed"></div>
				  <div class="fright btnVerMas"><a href="<?php echo $this->siteURL.'/'; ?>sala-de-prensa-detalle/id/<?php echo $elem['kid_noticia'] ?>"></a></div>
				  <div class="fixed"></div>
			   </div>
				</td>
			</tr>
		</table>
  <div class="fixed"></div>
</div>
				<?php
				}//end foreach
			}//end si hay noticia
		}else{//end es noticia principal
			if(is_numeric($id) && $id>0){
				$noticia = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."noticias WHERE visible=1 AND kid_noticia=$id LIMIT 1"));
				if(count($noticia)>0){
					foreach($noticia as $elem){
					echo $this->mostrarBreadcrumb($elem['titulo']);
						?>
						<h1><?php echo $elem['titulo']; ?></h1>
						<span style="color:#999"><?php echo formatDate($elem['fecha']); ?></span><br /><br />
						<?php if($elem['imagen'] != ''){ ?>
						<div style="float:right;padding:0px 0px 15px 15px;"><img src="<?php echo WEB_NEWS_PATH."thumb/".$elem['imagen']; ?>" width="200" alt="<?php echo $elem['titulo'] ?>" style="border-bottom:4px solid #0168b3" /></div>
						<?php } ?>
						<?php echo html_entity_decode($elem['texto']); ?>
						<?php if($elem['link'] != ''){ ?>
						<a href="<?php echo $elem['link']; ?>" target="_blank">Más información</a>
						<?php
						}//hay link
					}//end foreach
				}//end if si hay noticia

			}//end is numeric
		}
	}//end function
	function mostrarNoticias(){
	include(LIB_PATH."functions.inc.php");
	include(CLASS_PATH."paginacionB.class.php");
	$vars = "page={$this->page}";
	$pager = isset($_GET['paginadespliegue'])?'&paginadespliegue='.$_GET['paginadespliegue']:'';
	$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."noticias WHERE visible=1";
	$resInit =	$this->myAdmin->fetch($this->myAdmin->query($queryT));
	if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
		if($continuar){
			$paginacion = new PaginacionB();
			$paginacion = $paginacion->paginar($queryT,10,10,"$vars",$this->myAdmin);
			$noticias = $this->myAdmin->fetch($this->myAdmin->query("SELECT * FROM ".PREFIJO."noticias WHERE visible=1 ORDER BY fecha DESC, kid_noticia DESC LIMIT ".$paginacion['LIMIT']));
			foreach($noticias as $elem){
				?>
				<div style="border:1px solid #EEE;border-bottom-color:#DDD;">
					<div style="background:#0168b3;color:#FFF;font-weight:bold;font-size:1.2em;line-height:1.5em;padding:3px 3px 6px 8px"><?php echo $elem['titulo'] ?></div>
					<div style="padding:2px 8px">
						<br /><span style="color:#999"><?php echo formatDate($elem['fecha']); ?></span><br /><br />
						<?php if($elem['imagen'] != ''){ ?>
						<div style="float:right;padding:0px 0px 15px 15px;"><img src="<?php echo WEB_NEWS_PATH."thumb/".$elem['imagen']; ?>" width="120" alt="<?php echo $elem['titulo'] ?>" /></div>
						<?php } ?>
						<div style="text-align:justify"><?php echo html_entity_decode($elem['resumen']); ?></div>
						<br />
						<a href="<?php echo $this->siteURL.'/'; ?>sala-de-prensa-detalle/id/<?php echo $elem['kid_noticia']; ?>">Ver más</a>
					</div>
					<div class="fixed"></div>
				</div>
				<?php
			}//end foreach
			echo isset($paginacion['HTML'])?"<br /><div style='text-align:center;'>{$paginacion['HTML']}</div>":'';
		}//end if si hay noticia
	}//end function
	function obtenerNivelSuperior(){
		switch($this->pageData['nivel']){

			case '0':
			case 'default':
				$this->nivelSuperior = $this->pageData['alias'];//index
			break;

			case '1':
				$this->nivelSuperior = $this->pageData['alias'];
			break;

			case '2':
				$nivelSupOk = $this->pageData['padre'];
				$query = "SELECT alias FROM ".PREFIJO."estructura WHERE kid_pagina='$nivelSupOk'";
				$resultado = $this->myAdmin->query($query);
				$procesado = $this->myAdmin->fetch($resultado);
				$dato=end($procesado); 
				$this->nivelSuperior = $dato['alias'];			
			break;

			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '10':
				$nivelSupA = $this->pageData['nivel'] - 2;
				$nivelSup = 'lvl'.$nivelSupA;
				$nivelSupOk = $this->pageData[$nivelSup];
				$query = "SELECT alias FROM ".PREFIJO."estructura WHERE kid_pagina='$nivelSupOk'";
				$resultado = $this->myAdmin->query($query);
				$procesado = $this->myAdmin->fetch($resultado);
				$dato=end($procesado); 
				$this->nivelSuperior = $dato['alias'];
			break;
		}
		return $this->nivelSuperior;
	}
	function establecerPageRank(){
			if($pageRank = $this->obtenerPageRanking($this->id)){
				$this->calificacion = $pageRank[0]['promedio'];
				$this->numero_votos = $pageRank[0]['total'];
			}

			$kid_usr = 0;
			if(isset($_SESSION[SESSION_DATA_SET]['kid_usr'])) $kid_usr = $_SESSION[SESSION_DATA_SET]['kid_usr'];
			if($miCalificacion = $this->obtenerMiPageRank($this->id,$kid_usr)){
				$this->miCalificacion = $miCalificacion;
			}
	}
	function obtenerPageRanking($pageId){
		if(isset($pageId) && $pageId != '' && is_numeric($pageId)){
			$queryCalificacion = "SELECT fid_pagina,COUNT(*) as total,ROUND(AVG(calificacion),1) as promedio FROM ".PREFIJO."page_rank WHERE fid_pagina=$pageId";
			$resultadoCalificacion = $this->myAdmin->query($queryCalificacion);
			$procesadoCalificacion = $this->myAdmin->fetch($resultadoCalificacion);
			return $procesadoCalificacion;
		}else{
			return false;
		}
	}
	function obtenerMiPageRank($pageId,$kid_usr){
		if(isset($pageId) && $pageId != '' && is_numeric($pageId)){
			if(isset($pageId) && $pageId != '' && is_numeric($pageId)){
				$queryMiCalificacion = "SELECT * FROM ".PREFIJO."page_rank WHERE fid_pagina={$pageId} AND fid_usr={$kid_usr}";
				$resultadoMiCalificacion = $this->myAdmin->query($queryMiCalificacion);
				$procesadoMiCalificacion = $this->myAdmin->fetch($resultadoMiCalificacion);
				if(count($procesadoMiCalificacion)>0){//Si hay informacion
					return $resultado = $procesadoMiCalificacion[0]['calificacion'];
				}else{
					return 0;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	function generaEstrellas(){
	    $this->establecerPageRank();
	    if(isset($_SESSION[SESSION_DATA_SET]['kid_usr'])){
	    	$kid_usr = $_SESSION[SESSION_DATA_SET]['kid_usr'];
	    }else{
	    	return array("success"=>FALSE,"datos"=>'Error, no hay id de usuario',"miCalificacion"=>0,"calificacion"=>0,"votos"=>0,"habilitarCalificacion"=>FALSE);
	    }
	    $habilitarCalificacion = FALSE;
	                      
	    if($this->miCalificacion == 0){//No he calificado puedo calificar
	        $habilitarCalificacion = TRUE;
	    }

	    return array("success"=>TRUE,"datos"=>'',"miCalificacion"=>$this->miCalificacion,"calificacion"=>$this->calificacion,"votos"=>$this->numero_votos,"habilitarCalificacion"=>$habilitarCalificacion);

	}
}
?>