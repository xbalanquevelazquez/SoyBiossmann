<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
?>
	<meta charset="UTF-8">
	<title><?php if(!$index) echo $page->pageData['nombre'].' | '; ?>Soy Biossmann</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,shrink-to-fit=no" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo $page->pageData['descripcion']!=''?$page->pageData['descripcion']:$page->pageData['nombre'].', Soy Biossmann.'?>" />
    <meta name="keywords" content="<?php echo $page->pageData['keywords']!=''?$page->pageData['keywords']:', intranet, '.$page->pageData['nombre'] ?>" />
    <meta name="author" content="Biossmann TI" />
	<link rel="shortcut icon" href="<?php echo APP_URL; ?>img/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/fontawesome-free-5.1.0-web/css/all.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/basico.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/personalizacion.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/menu-horizontal.css" />

	<script type="text/javascript" src="<?php echo APP_URL; ?>js/popper.min.js"></script>
   	<script type="text/javascript" src="<?php echo APP_URL; ?>js/jquery-3.0.0.min.js"></script>
 	<script type="text/javascript" src="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/cssmenuh.js"></script>
    <script type="text/javascript">
        var siteURL = '<?php echo $page->siteURL; ?>';
        <?php if($index){ ?>
        var esIndex = true;
        <?php }else{//if(!$index) ?>
        var esIndex  = false;
        var pageId = <?php echo $page->id; ?>;
        var pageAlias = '<?php echo $page->pageData['alias']; ?>';
        <?php } ?>
    </script>
	<script src="js/app.js"></script>           <!-- recommended location of your JavaScript code relative to other JS files -->