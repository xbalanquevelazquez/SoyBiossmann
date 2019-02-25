$(document).ready(function(){
	console.log('¡App lista!');
	$("input, textarea, button").focus(function(){
		$(this).addClass("glow");
	});
	$("input, textarea, button").blur(function(){
		$(this).removeClass("glow");
	});
	$('.btnSalir').click(function(){
		document.location.href = siteURL+'SALIR';
	});
});

$.fn.Algo = function(){
	alert('algo');
};