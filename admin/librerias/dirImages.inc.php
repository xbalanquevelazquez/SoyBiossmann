<?php
function mostrarImagenesBan($path='banners'){
	$basefolder = $path;
	#echo URL_PUBLIC;
	$path = APP_PATH."webimgs/$basefolder/";
	
	if(!file_exists($path)) die($path."<br />No puede ver este folder desde este administrador de im&aacute;genes.");//Existe el folder
	
	$directorio=dir($path);
	$arrImageFormats = array("jpg","gif","bmp","png");
	$arrFolders = array();
	$arrImagenes = array();
	$arrNum = 0;
	
	#if($path != $basePath){ $arrFolders[] = "..";}

	while ($archivo = $directorio->read()){
		if($archivo != '.' && $archivo != '..'){
			//pathinfo #dirname #basename #extension
			$file = pathinfo($archivo);
			$tipo = filetype($path.'/'.$file['basename']);
			if($tipo == 'dir'){
					$arrFolders[] = $file['basename'];
			}else if($tipo == 'file'){
				if(in_array(strtolower($file['extension']),$arrImageFormats)){
					$arrImagenes[$arrNum]['nombre'] = $file['basename'];
					$arrImagenes[$arrNum]['extension'] = $file['extension'];
					$arrImagenes[$arrNum]['path'] = $path.$file['basename'];
					#$arrImagenes[]['extension'] = $file['extension'];
					$arrNum++;
				}
			}
	   }
	}
	
	$directorio->close();
	sort($arrFolders);
	sort($arrImagenes);
	
	return $arrImagenes;

}
?>