$(document).ready(function(){
	var bloqueAbierto = true;
	var bloqueXheight = ($('.bloqueX').outerHeight()+1)+'px';
	var nuevaAltura = bloqueXheight;
	//$('.bloqueX').css({height:bloqueXheight});
	
	$('.espacioBotones').click(function(){
		$('.bloqueX').css({height:bloqueXheight});
		if(bloqueAbierto){
			var tituloHeight = $('.bloqueX .titulo').outerHeight()+'px';
			nuevaAltura = tituloHeight;
			bloqueAbierto = false;
			$('.espacioBotones span').text('mostrar');
			$('.espacioBotones i.fa').removeClass('fa-chevron-up');
			$('.espacioBotones i.fa').addClass('fa-chevron-down');
		}else{
			nuevaAltura = bloqueXheight;
			bloqueAbierto = true;
			$('.espacioBotones span').text('ocultar');
			$('.espacioBotones i.fa').removeClass('fa-chevron-down');
			$('.espacioBotones i.fa').addClass('fa-chevron-up');
		}
		
		$('.bloqueX').css({height:nuevaAltura});
	});

});

