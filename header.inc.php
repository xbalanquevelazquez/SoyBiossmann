<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
?>
        <header class=" sticky-top">
            <nav class="navbar navbar-expand-lg navbar-light  biossHeader">
                    <a class="navbar-brand" href="<?php echo $page->siteURL; ?>"><img src="img/logo-soy-biossmann-fondo-blanco.png" alt="Soy Biossmann" /></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse menu_principal" id="navbarSupportedContent">
                        <?php echo $page->mostrarMenuPrincipal(); ?>
                    </div>
                </nav>
                <?php #if(logedin()){ include_once('bloques/login.php'); } ?>  
        </header>