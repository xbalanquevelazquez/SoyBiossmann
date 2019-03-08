var imageCounter = 0;
var galeriaImagenes = new Array();//arr
var matrizFotos = new Array();
var cantidadFotos = 0;
var fotoPrev = 0;
var fotoActual = 1;
var fotoNext = 2;
$(document).ready(function(){
	//console.log('GALERIA:::');
	galeriaImagenes = $('.galeriaFoto');
	cantidadFotos = galeriaImagenes.length;
	console.log('NumFotos:'+cantidadFotos);
	matrizFotos[matrizFotos.length] = '';
	
	$('.galeriaFoto').each(function(){
		var parentObj = $(this).parent().parent();
		matrizFotos[matrizFotos.length] = parentObj;
		var anchoEnmarcado = parentObj.width();
		var anchoFoto = $(this).attr('width');
		var ajuste = (anchoEnmarcado - anchoFoto)/2;
		$(this).css({marginLeft:ajuste+'px'});
		if(imageCounter == cantidadFotos-1){
			moverAlPrev(parentObj);
		}else if(imageCounter == 0){
			moverAlCentro(parentObj);
		}else if(imageCounter == 1){
			moverAlNext(parentObj);
		}
		imageCounter++;
	});
	setFotoOrder();
	$('#btnPrevFoto').click(function(){
		prevFoto();
	});
	$('#btnViewFoto').click(function(){
		viewFoto();
	});
	$('#btnNextFoto').click(function(){
		nextFoto();
	});

	//console.log('__:'+$('.galeriaContainer .foto img').width());
});
function moverFueraDer(obj){
	obj.css({
		transform: 'scale(.45) rotate(6deg)',
		left: '191px',
		top: '120px',
		opacity: 0,
		zIndex: 1
	});
	obj.unbind('click').removeClass('clickeable');
}
function moverAlNext(obj){
	obj.css({
		transform: 'scale(.45) rotate(42deg)',
		left: '127px',
		top: '99px',
		opacity: 1,
		zIndex: 3
	});
	obj.find('img').addClass('desenfocar');
	obj.unbind('click');
	obj.addClass('clickeable').click(function(){
			nextFoto();
		});	
}
function moverAlCentro(obj){
	obj.css({
		transform: 'scale(1) rotate(5deg)',
		left: '33px',
		top: '68px',
		opacity: 1,
		zIndex: 5
	});
	obj.find('img').removeClass('desenfocar');
	obj.unbind('click');
	obj.addClass('clickeable').click(function(){
			viewFoto();
		});
}
function moverAlPrev(obj){
	obj.css({
		transform: 'scale(.45) rotate(-30deg)',
		left: '-66px',
		top: '93px',
		opacity: 1,
		zIndex: 4
	});
	obj.find('img').addClass('desenfocar');
	obj.unbind('click');
	obj.addClass('clickeable').click(function(){
			prevFoto();
		});
}
function moverFueraIzq(obj){
	obj.css({
		transform: 'scale(.45) rotate(-1deg)',
		left: '-120px',
		top: '118px',
		opacity: 0,
		zIndex: 1
	});
	obj.unbind('click').removeClass('clickeable');
}
function zoomInit(obj){
	obj.css({
		transform: 'scale(1) rotate(5deg)',
		left: '33px',
		top: '68px',
		opacity: 1,
		zIndex: 5
	});
	obj.find('img').removeClass('desenfocar');
	obj.unbind('click');

}
function zoomIn(obj){
	obj.css({
		transform: 'scale(2.5) rotate(0deg)',
		left: '33px',
		top: '68px',
		opacity: 1,
		zIndex: 10
	});
	var imgActual = obj.find('img');
	imgActual.removeClass('desenfocar');
	var anchoOriginal = imgActual.attr('original-width');
	var altoOriginal = imgActual.attr('original-height');
	var altoFijoMarco = 150;
	var altoFijoFoto = 168;
	var nuevoAnchoMarco = (anchoOriginal*altoFijoMarco)/altoOriginal;
	var nuevoAnchoFoto = (anchoOriginal*altoFijoFoto)/altoOriginal;
	console.log((anchoOriginal*altoFijoMarco)/altoOriginal);
	console.log(obj.find('img').attr('original-width'));
	obj.css({width:nuevoAnchoFoto+'px'});
	obj.find('.marco').css({width:nuevoAnchoMarco+'px'});
	imgActual.css({marginLeft:0});
	obj.unbind('click');
	obj.addClass('clickeable').click(function(){
			cerrarFoto(obj);
		});
}
function prevFoto(){
	var oldNext = fotoNext;
	moverFueraDer(matrizFotos[fotoNext]);
	moverAlNext(matrizFotos[fotoActual]);
	moverAlCentro(matrizFotos[fotoPrev]);
	//CAMBIO necesito ordenarlos antes
	fotoActual = fotoPrev;
	setFotoOrder();
	moverFueraIzq(matrizFotos[oldNext]);
	moverAlPrev(matrizFotos[fotoPrev]);//fotoPrev
	/*setTimeout(function(){
		moverFueraIzq(matrizFotos[oldNext]);
	},200);*/

}
function viewFoto(){
	var contenido = matrizFotos[fotoActual].html();
	$('#zoomFoto').html('<div class="foto transicion">'+contenido+'</div>');
	zoomInit($('#zoomFoto .foto'));
	setTimeout(function(){
		zoomIn($('#zoomFoto .foto'));
	},100);
}
function cerrarFoto(obj){
	var imgActual = obj.find('img');
	obj.css({width:'150px'});
	obj.find('.marco').css({width:'139px'});
	imgActual.css({marginLeft:'-33px'});
	zoomInit($('#zoomFoto .foto'));
	setTimeout(function(){
		$('#zoomFoto').html('');
	},550);
}
function nextFoto(){
	var oldPrev = fotoPrev;
	moverFueraIzq(matrizFotos[fotoPrev]);
	moverAlPrev(matrizFotos[fotoActual]);
	moverAlCentro(matrizFotos[fotoNext]);
	//CAMBIO necesito ordenarlos antes
	fotoActual = fotoNext;
	setFotoOrder();
	moverAlNext(matrizFotos[fotoNext]);//fotoPrev
	setTimeout(function(){
		moverFueraDer(matrizFotos[oldPrev]);
	},200);
}
function setFotoOrder(){
	fotoPrev = fotoActual-1;
	if(fotoPrev < 1){
		fotoPrev = cantidadFotos;
	}
	//fotoActual
	fotoNext = fotoActual+1;
	if(fotoNext > cantidadFotos){
		fotoNext = 1;
	}
	console.log('::::::::setOrder');
	console.log('next:'+fotoNext);
	console.log('actual:'+fotoActual);
	console.log('prev:'+fotoPrev);
}