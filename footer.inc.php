<?php 
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
?>
		<footer>
			<div class="biossFooter texto-blanco">
				<nav class="navbar">
					<ul>
						<li><a href="" class="resaltar">Cont&aacute;ctanos</a></li>
						<li><a href="">Mapa de sitio</a></li>
						<li><a href="">Reporta un problema en esta p&aacute;gina</a></li>
					</ul>
				</nav>
				<div class="plecas"></div>
			</div>
		</footer>
    <?php if($index){ ?>
    <script src="<?php echo APP_URL; ?>js/funcionalidad.home.js"></script>
    <?php } ?>