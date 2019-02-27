<?php
if(!defined('VIEWABLE')){ header('HTTP/1.0 404 Not Found'); exit; }
if(		$page3=='new'){ 		include_once("adm.est.cont.new.php");}
else if($page3=='edit'){ 		include_once("adm.est.cont.edit.php");}
else if($page3=='save'){ 	include_once("adm.est.cont.save.php");}
else if($page3=='del'){ 	include_once("adm.est.cont.delete.php");}
else{
	include("plantilla.inc.php");
?>
<?php
	include("plantillaFoot.inc.php");
}
?>