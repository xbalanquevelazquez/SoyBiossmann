<?php
define('VIEWABLE',TRUE);
include_once('libs/cnfg.php');

define('VIEWABLE',TRUE);
include_once("admin/cnf/configuracion.cnf.php");
include_once(LIB_PATH."functions.inc.php");
include_once(CLASS_PATH."mysql.class.php");
include_once(CLASS_PATH."page.class.php");
$page = new Page($page);

$arrExcept = array('index');//los que si muestran col der aunque no tengan subsecciones

//print_r($_SESSION);
if(logedin()){
	#echo "LOGUEADO";
	$loguedIn = TRUE;
}else{
	#echo "NO LOGUEADO";
	$loguedIn = FALSE;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es-mx">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title><?php if(!$index) echo $page->pageData['nombre'].' | '; ?>Intranet Biossmann</title>
	<meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo $page->pageData['descripcion']!=''?$page->pageData['descripcion']:$page->pageData['nombre'].', Intranet Biossmann.'?>" />
    <meta name="keywords" content="<?php echo $page->pageData['keywords']!=''?$page->pageData['keywords']:', intranet, '.$page->pageData['nombre'] ?>" />
    <meta name="author" content="Biossmann TI" />
    <link rel="favourites icon" href="<?php echo $page->siteURL; ?>/img/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/uikit.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/uikit.gradient.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/components/form-select.gradient.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/basico.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/dirtel.css">


	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/menu-horizontal.css" media="screen, handheld, print" />
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/fixlinks.css" media="screen, handheld, print" />

	<?php if(!$loguedIn){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/intro.css">
	<?php } ?>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/uikit.min.js"></script>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/components/form-select.min.js"></script>
	<script src="<?php echo $page->siteURL; ?>/js/cryptojs/core.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/lib-typedarrays.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/x64-core.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/enc-utf16.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/enc-base64.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/md5.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/sha1.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/sha256.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/sha224.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/sha512.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/sha384.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/ripemd160.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/hmac.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pbkdf2.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/evpkdf.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/cipher-core.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/mode-cfb.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/mode-ctr.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/mode-ofb.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/mode-ecb.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pad-ansix923.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pad-iso10126.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pad-zeropadding.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pad-iso97971.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/pad-nopadding.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/rc4.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/rabbit.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/aes.js"></script>
    <script src="<?php echo $page->siteURL; ?>/js/cryptojs/tripledes.js"></script>
   

	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes" />
	<?php if($deviceType == 'phone'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $page->siteURL; ?>/css/mobile.css">
	<?php } ?>
		<script type="text/javascript">
			var siteURL = '<?php echo $page->siteURL; ?>';
			<?php if($index){ ?>
			var esIndex = true;
			<?php }else{//if($index) ?>
			var esIndex	 = false;
			var pageId = <?php echo $page->id; ?>;
			var pageAlias = '<?php echo $page->pageData['alias']; ?>';
			<?php } ?>
		</script>
		<?php if(!$index){ ?>
		<link type="text/css" rel="stylesheet" media="screen, handheld, print" href="<?php echo $page->siteURL; ?>/css/interna.css" />
		<?php } ?>
	<?php if($loguedIn){ ?>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/cssmenuh.js"></script>
    <script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/xhtml-external-links.js" charset="UTF-8" ></script>
    <script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/fix-external-links.js"></script>
    <?php } ?>
</head>
<body class="transicionLenta">
	<div class="divoculto nombretemp">
		<?php if(logedin()){ echo $_SESSION[SESSION_DATA_SET]['nombre']; } ?>
	</div>
	<div class="checker"></div>
	<section id="mainContainer" class="transicionLenta <?php echo $page->nivelSuperior; ?>">
		<?php include("header.php") ?>
		<section id="mainContent" class="transicionLenta">
	<?php if(logedin()){//ESTA LOGUEADO	
				if($index){ 
					include('home.php');
				}else{//no es index
					include('internas.php');
				}
		}else{//NO LOGUEADO	
			include("bloques/login-contexto.php");
		}
		?>
		</section>
		<?php if(logedin()){ include("footer.php"); } ?>
	</section>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/funcionalidad.js"></script>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/funcionalidad.login.js"></script>
	<?php if($loguedIn){ 
				  if($index){ ?>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/funcionalidad.home.js"></script>		
			<?php 
					}//if($index) 
		  }else{//NO LOGUEADO ANIMAR LOGIN
				?>
	<script type="text/javascript" src="<?php echo $page->siteURL; ?>/js/intro.js"></script>
	<?php } ?>
</body>
</html>
<?php $page->myAdmin->close(); ?>