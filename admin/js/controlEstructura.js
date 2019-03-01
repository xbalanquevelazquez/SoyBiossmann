function mostrarOpciones(id){
	ocultarOpciones();
	$('.opciones[option-block='+id+']').fadeIn();
}
function mostrarNueva(id){
	ocultarOpciones();
	$('.boxNewPage[option-2-block='+id+']').fadeIn();
}
function ocultarOpciones(){
	$('.opciones').hide();
}
function confirmarBorrar(){
	var wcnf = confirm("¿Está seguro de querer borrar la página?\nTodas las páginas contenidas en ésta se borrarán también, junto con los contenidos asociados.");
	if(wcnf){
		return true;
	}else{
		return false;
	}
}
function activarEnlaces(){
	$('.opciones a').click(function(e){
		if($(this).children('i.fa-file').length > 0){ //tiene icono de file
			e.preventDefault();
			mostrarNueva($(this).attr('data-id'));
		}
		if($(this).children('i.fa-times').length > 0){ //tiene ícono de cerrar
			e.preventDefault();
			ocultarOpciones();
		}
		if($(this).children('i.fa-eraser').length > 0){ //tiene ícono de cerrar
			e.preventDefault();
			confirmarBorrar();
		}
	});
	$('.btnPagina').click(function(e){
		e.preventDefault();
		mostrarOpciones($(this).attr('id'));
	});
}
$(document).ready(function(){
	activarEnlaces();
	$('body').keyup(function(e){
	    if(e.which == 27){//apretando ESC
	    	ocultarOpciones();
	    }
	});
});
