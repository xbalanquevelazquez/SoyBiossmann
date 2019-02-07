<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo SYS_NAME; ?></title>
<link href="css/designBasic.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
<?php if($page=='login'){ ?>
<link href="css/designLogin.css" type="text/css" rel="stylesheet" />
<?php }else if($page=='est'){ ?>
<link href="css/designEstructura.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/controlEstructura.js"></script>
<?php } ?>
<?php if($page2 == 'edit' || $page2 == 'new' || $page2 == 'newpage'){ ?>
<link href="css/designTemplateSmarty.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/x_ajax.js"></script>
<?php } ?>
<script type="text/javascript" src="js/libs.js"></script>
</head>

<body>
	<div id="mainContainer">
		<table class="layout" summary="layout" width="100%">
			<tr>
				<td class="anchoMax"><img src="img/spacer.gif" width="780" height="1" /></td>
			</tr>
			<tr>
				<td><?php include("header.php"); ?></td>
			</tr>
			<?php if($page!='login'){ ?>
			<tr>
				<td><?php include('main-menu.php'); ?></td>
			</tr>
			<?php } ?>
			<tr>
			
				<td>
<?php
if(!isset($_POST['accion'])){
?> 
<div class="resultsBox"></div> 
<?php
}
?>
<a name="top" id="anchorTop"></a>
				<?php if($page!='est'){ ?>
				<div id="contentContainer">
				<?php } ?>