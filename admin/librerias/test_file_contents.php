<?php
error_reporting(E_ERROR); ini_set('display_errors', 1);
$pagina_inicio = file_get_contents('https://universidadbiossmann.com/webservice/_xws/_ws_login.php');
echo $pagina_inicio;
?>