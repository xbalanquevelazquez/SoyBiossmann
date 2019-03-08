<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $tituloDePagina.' | '.APP_NAME; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,shrink-to-fit=no" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="description" content="<?php echo $tituloDePagina; ?>" />
	<link rel="shortcut icon" href="<?php echo APP_URL; ?>admin/img/favicon-admin.ico"/>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo APP_URL; ?>css/basico.css?<?php echo rand(10, 15); ?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>css/adm.css?<?php echo rand(10, 15); ?>" />
    <!-- <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>css/designBasic.css?<?php echo rand(10, 15); ?>" /> -->
    <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>css/designEstructura.css?<?php echo rand(10, 15); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>css/fontawesome-free-5.7.2-web/css/all.css" />
	<!--script type="text/javascript" src="<?php echo APP_URL; ?>js/popper.min.js"></script-->
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/jquery-3.0.0.min.js"></script>
	<script type="text/javascript" src="<?php echo APP_URL; ?>js/libs/bootstrap-4.1.3/js/bootstrap.min.js"></script>
	<!--script type="text/javascript" src="<?php echo APP_URL; ?>js/cssmenuh.js"></script-->
    <script type="text/javascript" src="<?php echo ADMIN_URL; ?>js/app.js?<?php echo rand(10, 15); ?>"></script>
</head>
<body>
<div class="row">
	<div class="col">
		<div class=""><!-- container-fluid -->
		<?php 
		if($myAdmin->comprobarSesion()){ 
			include_once(ADMIN_PATH."main-menu.php");
		}
		?>
		<div class="setpadding20">	