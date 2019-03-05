<?php
define('VIEWABLE',TRUE);
include_once("admin/cnf/configuracion.cnf.php");
include_once(CLASS_PATH."page.class.php");
$page = new Page($page);
define('CURRENT_SECCION',APP_URL.$data1.'/');
/*echo "<pre>";
print_r($page);
echo "</pre>";*/
$arrExcept = array('index');
$main = array();
$headerData = array();
$plantillaData = array();
$footerData = array();
/***************** DATOS DE MAIN ******************/
$main['pageNombre'] = !$index? $page->pageData['nombre'].' | ':'';
$main['pageDescripcion'] = $page->pageData['descripcion']!=''?$page->pageData['descripcion']:$page->pageData['nombre'].', Soy Biossmann.';
$main['pageKeywords'] = $page->pageData['keywords']!=''?$page->pageData['keywords']:'intranet, '.$page->pageData['nombre'];
$main['APP_URL'] = APP_URL;
$main['extraScripts'] = '';
if($index){
	$main['extraScripts'] = '<link rel="stylesheet" type="text/css" href="'.APP_URL.'css/dirtel.css" />';
}
$main['siteURL'] = $page->siteURL;
$main['esIndex'] = $index?'true':'false';
$main['pageId'] = $page->id;
$main['pageAlias'] = $page->pageData['alias'];
$main['headerCode'] = '';
$main['contentCode'] = '';
$main['footerCode'] = '';
/**************************************************/
/*
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php include_once(APP_PATH."includes.inc.php"); ?>
</head>
<body>
<?php*/
/***************** DATOS DE HEADER ******************/
$headerData['loginBlock'] = ''; //if(logedin()){ include_once('bloques/login.php'); }
$headerData['menuPrincipal'] = $page->getMenuPrincipal();
$headerData['siteURL'] = $page->siteURL;
/**************************************************/
$main['headerCode'] = makeTemplate('header.html', $headerData, 'site');
#include_once(APP_PATH."header.inc.php");
 /*
?>
	<section id="mainContainer" class="container-fluid">
<?php*/
/***************** DATOS DE PLANTILLA ******************/
$plantilla_filepath = 'home';
$plantilla_nombre = 'plantilla';
$plantillaData['APP_URL'] = APP_URL;
if(isset($page->pageData['plantilla_filepath']) && $page->pageData['plantilla_filepath'] != ''){
	$plantilla_filepath = $page->pageData['plantilla_filepath'];
}else{
	$plantilla_filepath = '404';
}
$plantilla_nombre .= '.'.$plantilla_filepath.'.html';
switch($plantilla_filepath) {
	case 'home':
		//VARS
		break;
	case 'subseccion':
	case 'sinsubsecciones':
	case 'landingpage':
		//VARS
		$plantillaData['menuSecundario'] = '';
		#$plantillaData['subsecciones'] = $plantilla_filepath=='sinsubsecciones'?'':$page->mostrarSubsecciones();//NO MOSTRAR EN SINSUBSECCIONES
		
		if($plantilla_filepath=='landingpage'){//Mostrar subsecciones en area de contenido
			$plantillaData['contenido'] = $page->mostrarSubsecciones();
		}else{//MOSTRAR CONTENIDO EN CUALQUIER OTRO CASO
			if($page->page=='calidad'){//MOSTRAR MODULO DE CALIDAD SI ES LA SECCION
				include(APP_PATH.'bloques/module.calidad.php');
				$plantillaData['contenido'] = $buffer;
			}else{
				$plantillaData['contenido'] = replaceStylesDefs(replaceDirImages($page->contenido['contenido']));	
			}
			//$plantillaData['menuSecundario'] .= $page->mostrarSubsecciones();//MUESTRO SUBSECCIONES EN MENU, SI NO ES LANDINGPAGE, solo niveles superiores a 1
			if($page->pageData['nivel'] > 1){
				$plantillaData['menuSecundario'] .= $page->mostrarMenuSecundario();
			}
		}
		if($page->page=='calidad'){//NO MUESTRO ULTIMA ACT EN CALIDAD PORQUE LA INFO ES EXTERBNA
			$plantillaData['ultimaActualizacion'] = '';
			$plantillaData['menuSecundario'] .= makeTemplate('banners_iso.html', array('siteURL' => $page->siteURL), 'site');
		}else{
			$plantillaData['ultimaActualizacion'] = "Última actualización: ".formatFechaEspaniol($page->ultimaActualizacion);
		}

		if($plantilla_filepath=='sinsubsecciones' || $plantilla_filepath=='landingpage'){//FORZAR PLANTILLA SUBSECCIONES, PERO SIN DATOS PARA SUBSECCIONES
			$plantilla_nombre = 'plantilla.subseccion.html';
		}
		break;
	
	default:
		# code...
		break;
}
/*PROCESA INFORMACION DE TITULAR/IMAGEN*/
#echo $page->contenido['imagen_seccion'];
$titularData = array();
$titularPage = 'page.titular.normal.html';
if($page->contenido['imagen_seccion'] != ''){
	$titularPage = 'page.titular.imagen.html';
	$titularData['imagenSeccion'] = $page->contenido['imagen_seccion'];
	$titularData['siteURL'] = $page->siteURL;
}
$titularData['pageNombre'] = $page->pageData['nombre'];

if($page->page=='calidad'){
	include(APP_PATH.'bloques/module.calidad.breadcrumb.php');
	$plantillaData['breadcrumb'] = $bufferBread;
}else{
	$plantillaData['breadcrumb'] = $page->mostrarBreadcrumb($otroNombre='',$separador='<i class="fa fa-caret-right"></i>');
}
$titularCode = $plantillaData['titular'] = makeTemplate($titularPage, $titularData, 'site');

/**************************************************/
$main['contentCode'] = makeTemplate($plantilla_nombre, $plantillaData, 'site');
#include_once(APP_PATH.'plantilla.'.$plantilla_filepath.'.php');
/***************** DATOS DE FOOTER ******************/
$footer['script'] = $index?'<script src="'.APP_URL.'js/funcionalidad.home.js"></script>':'';
/**************************************************/
$main['footerCode'] = makeTemplate('footer.html', $footer, 'site');
#include_once(APP_PATH."footer.inc.php");
//DESPLIEGA CODIGO
echo makeTemplate('main.html', $main, 'site');
/*
?>
	</section><!--//#mainContainer-->
</body>
</html>
*/ ?>