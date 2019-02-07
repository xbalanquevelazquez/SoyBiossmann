<?php
define("VIEWABLE",true);
include('admin/cnf/configuracion.cnf.php');

function mostrarBanners($id){
		include('admin/cnf/configuracion.cnf.php');
		include(CLASS_PATH."mysql.class.php");
		$myAdmin = new Conexion();
		$myAdmin->conectar($host,$usr,$psw,$db);
		$queryGroup = "SELECT * FROM ".PREFIJO."grupo_banners WHERE identificador='$id' AND visible=1";
		$group =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryGroup));
		if(count($group)==1) $continuar = true; else $continuar = false;
		if($continuar){
		$grupoSel = $group[0]['kid_grupo'];
		
		$queryT = "SELECT COUNT(*) as total FROM ".PREFIJO."banners WHERE grupo='$grupoSel'";
		$resInit =	$myAdmin->conexion->fetch($myAdmin->conexion->query($queryT));
		if($resInit[0]['total'] == 0) $continuar = false; else $continuar = true;
			
		if($continuar){
				$query = $myAdmin->conexion->query("SELECT * FROM ".PREFIJO."banners WHERE grupo=$grupoSel AND visible=1 ORDER BY posicion ASC");#LIMIT ".$paginacion['LIMIT']
		}
				
			$arr = $myAdmin->conexion->fetch($query);
			/*?><div class="listaBanners listaColor<?php echo $group[0]['selector']; ?>"><!--div class="titulo"><?php echo $group[0]['titulo']; ?></div--><?php
			foreach($arr as $reg){
					?>
					<?php if($reg['link']!=''){ ?>
						<a href="<?php echo $reg['link']; ?>" title="<?php echo $reg['alt']; ?>" target="_blank"><?php } ?>
					<?php if($reg['img']!='') {?>
							<img src="<?php echo WEB_PATH."webimgs/banners/".$reg['img']; ?>" alt="<?php echo $reg['alt']; ?>" />
						<?php } ?>
					<?php if($reg['link']!=''){ ?>
						</a>
					<?php } ?>
		<?php  }//no imprimir no hay registros ?>
		<div class="fixed"></div></div>
		<?php */
		}//no está el grupo 
		return $arr;
}



	if(!isset($_POST['id']))exit;
	sleep(1);
	$random = 0;
	#$path 	= '../images/banners/banners_'.$_POST['id'].'/';	
	$pathImg= WEB_PATH.'webimgs/banners/';
	$alt	= 'banner';		//
	$url	= false;
	$target	= false;
	$porcent= 100;
	$html	= '';	
	srand((float) microtime() * 10000000);
	
	if(isset($_POST['id']))
	{	
		#$data=file($path.'banners.txt');
		
		$data = mostrarBanners('banners-rotatorios');
		$tNum=0;	//Numero de imagenes
		foreach ($data as $idx => $dat)
		{	#if(trim($dat)=='')continue; //Linea en blanco
			#if($dat{0}=='#')continue; 	//Comentario						
			#$tmp=explode('|',$dat);
			$aImg[$tNum]['src']=$pathImg.$dat['img'];
			$aImg[$tNum]['alt']=$dat['alt'];
			$aImg[$tNum]['porcent']=100;			
			$aImg[$tNum]['url']=$dat['link'];
			$aImg[$tNum]['target']='_blank';
			$aImg[$tNum]['js']=false;
			$tNum++;		
		}

		$IMG  = array(); 		//Vector de ponderacion
		$total= 0;				//Tamaño del vector
		for($a= 0; $a<$tNum; $a++)
		{	$total+= round($aImg[$a]['porcent']/$tNum);
			$IMG   = array_pad($IMG, $total, $a);
		}
		
		if($random == 1){
			//Mezcla el arreglo
			shuffle($IMG);					//Mezcla aleatoriamente el arreglo
			$nImg=$IMG[rand(0,$total-1)];	//Obtinene la imagen aleatoria
		}else{
			//graba la secuencia para mostrar todos los banners uno detrás de otro
			session_start();
			if(!isset($_SESSION["banner"])){
				$_SESSION["banner"] = 0;
			}
			if($_SESSION["banner"] >= ($tNum-1)){
				$_SESSION["banner"] = 0;
			}else{
				$_SESSION["banner"] += 1;
			}
			$nImg = $_SESSION["banner"];

		}
		$html.=$aImg[$nImg]['js']?'<script type="text/javascript">'.$aImg[$nImg]['js'].'alert();'.'</script>':'';
		$html.='<div style="margin-bottom:4px">';
		$html.=$aImg[$nImg]['url']?'<a href="'.$aImg[$nImg]['url'].'" '.($aImg[$nImg]['js']?$aImg[$nImg]['js']:'').' '.($aImg[$nImg]['target']?'target="'.$aImg[$nImg]['target'].'"':'').'>':'';
		$html.='<img src="'.$aImg[$nImg]['src'].'" alt="'.$aImg[$nImg]['alt'].'" title="'.$aImg[$nImg]['alt'].'" border="0">';
		$html.=$aImg[$nImg]['url']?'</a>':'';
		$html.='</div>';
		
	}
	else{	//Solo toma la imagen aleatoriamente
			$dir  = dir($path);
			while($file=$dir->read()) 
			{ if($file=="." || $file=="..")continue;
			  if(is_file($dir->path.$file))if(eregi( "(.)(gif|jpg|png)", $file) )$IMG[]=$file;	
			}
			$dir->close();
			$srcImg=$IMG[rand(0,count($IMG)-1)];
			$html='<img src="'.$pathImg.$srcImg.'" alt="'.$alt.'" border="0">';
	}	
	echo $html;		
?>