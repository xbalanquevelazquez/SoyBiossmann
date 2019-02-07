<?php
// put full path to Smarty.class.php
require(LIB_PATH.'smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = TEMPLATE_PATH.'templates';
$smarty->compile_dir = TEMPLATE_PATH.'templates_c';
$smarty->cache_dir = TEMPLATE_PATH.'cache';
$smarty->config_dir = TEMPLATE_PATH.'configs';
?>

