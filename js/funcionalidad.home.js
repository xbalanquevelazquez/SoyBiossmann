function tog(v){return v?'addClass':'removeClass';}

var activarBusqueda = false;
var bannerActual = 1;
var bannerSiguiente = 2;
var bannerPasado;
var bannerTimer;
var numeroBanners;

$(document).ready(function(){

	numeroBanners = $("#contenedorBanners .banner").length;
	console.log('Banners: '+numeroBanners);

	$('.ixSubmit.clear').click(function(e){
		e.preventDefault();
		$(".detailBox").css({right:"-100%"});
		$(".resultsBox").css({height:'0px',opacity:0});
		$(".bloqueResultadoDirectorio").css({height:'0px'});
		setTimeout(function(){
			$(".resultsBox .container").html('');
		},1000);
	});

	$('.ixSubmit.search').click(function(e){
		e.preventDefault();
		activarResultados(true);
	});

	$('#busqueda').submit(function(e){
		e.preventDefault();
		activarResultados(true);
	});

	$("#nombre").keypress(function(e){
		var code = e.keyCode || e.which;
		if(code  == 13) {
			e.preventDefault();
			return false;
		}
	});
	$("#nombre").keyup(function(e){
	    e.preventDefault();
		var code = e.keyCode || e.which;
		if(code  == 13) {
			activarResultados(true);
	    }else{
			activarResultados();
		}
	}); // focus && blur
	
	activarResultados();

	$("#contenedorBanners .apuntador a").click(function(e){
		e.preventDefault();
		var numeroActual;
		numeroActual = $(this).parent().attr("id");
		numeroActual = numeroActual.split("bannLink");
		numeroActual = numeroActual[1];
		cambiarBanner(numeroActual);
	});
});
function activarLinksIniciales(){
	$(".columnaDer a").click(function(e){
		e.preventDefault();
		var letra = $(this).text();
		var seleccionado;

		$(".letra").each(function(){
			if($(this).text().toLowerCase() == letra){
				var margensuperior = $(this).offset();
				var posicionamiento = $(this).position();
				var nuevaPosicion = ($(".columnaIzq").scrollTop() + posicionamiento.top)-($(".resultsBox h3").innerHeight());
				$(".columnaIzq").animate({scrollTop: nuevaPosicion}, 500);
			}
		});
	});
}
function activarResultados(forzar){
	var contador = 0;
	$("#nombre").each(function(){
		var cadena = $(this).val();
		if(cadena != '' && cadena.length > 2){
			contador++;
		}
	});
	if(forzar==true) contador++;
	if(contador>0) {
		$(".resultsBox").css({height:"301px",background:"#FFF url(img/loader.gif) no-repeat center center"});
		$(".bloqueResultadoDirectorio").css({height:'301px'});
		$(".detailBox").css({right:"-100%"});
		busquedaResultados();
	}else{
		$(".detailBox").css({right:"-100%"});
		$(".resultsBox").css("height","0px");
		$(".resultsBox .container").html('');
	}
}
function busquedaResultados(){
	$(".resultsBox .container").html('');
	var myFormData = new FormData();
	myFormData.append('action','getListado');
	myFormData.append("nombre",$("#nombre").val());
	$.ajax({
		url: siteURL+"/webservice/acciones.php",
		type:"POST",
		processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
		contentType: false,
		data:myFormData,
		cache:false,
		dataType:"json",
		success: function(response){
			if(response.success){
				$(".resultsBox").css({background:'#FFF',opacity:1});
				$(".resultsBox .container").html(response.data.codigo);
				activarLinksIniciales();
				activarOndas();
			}else{
				$(".resultsBox .container").html(response.error).css("opacity",1);
			}

		}
	});
}
//jQuery time
function activarOndas(){
	var parent, ink, d, x, y;
	$(".personaBox").click(function(e){
		e.preventDefault();
		parent = $(this).parent();
		//create .ink element if it doesn't exist
		if(parent.find(".ink").length == 0)
			parent.prepend("<span class='ink'></span>");
			
		ink = parent.find(".ink");
		//incase of quick double clicks stop the previous animation
		ink.removeClass("animate");
		
		//set size of .ink
		if(!ink.height() && !ink.width())
		{
			//use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
			d = Math.max(parent.outerWidth(), parent.outerHeight());
			ink.css({height: d, width: d});
		}
		
		//get click coordinates
		//logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
		x = e.pageX - parent.offset().left - ink.width()/2;
		y = e.pageY - parent.offset().top - ink.height()/2;
		
		//set the position and add class .animate
		ink.css({top: y+'px', left: x+'px'}).addClass("animate");
		abrirDetalle($(this).parent().attr("id"));
	});
}
function abrirDetalle(id){
	//alert(id);
	var dataDetail = new FormData();
	dataDetail.append('action','getDetalle');
	dataDetail.append("id",id);
	$(".detailBox").css({right:"0%"});
	$.ajax({
		url: siteURL+"/webservice/acciones.php",
		type:"POST",
		processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
		contentType: false,
		data:dataDetail, 	
		cache:false,
		dataType:"html",
		success: function(data){
			$(".detailBox").html(data);
		}
	});
}
function cerrarDetalle(){
	$(".detailBox").css({right:"-100%"});
}
function iniciarCambioBanners(){
	bannerTimer = setInterval(siguienteBanner, 10000);
	console.log("iniciando banners...");
	//$(".banner").click(abrirEnlace);
}
function detenerCambioBanners(){
	window.clearInterval(bannerTimer);
}
function siguienteBanner(){
	var movimientoALaIzq = $('#contenedorBanners').width();
	$("#bann"+bannerActual).css({left:'-'+movimientoALaIzq+'px',zIndex:numeroBanners});
	bannerPasado=bannerActual;
	setTimeout(acomodarBanners, 400);
	if(bannerActual == numeroBanners){
		bannerActual = 1;
	}else{
		bannerActual++;
	}

	if(bannerSiguiente == numeroBanners){
		bannerSiguiente = 1;
	}else{
		bannerSiguiente++;
	}
}
function acomodarBanners(){
	var matriz = generarMatrizDeBanners(0);
	setTimeout(resetBanner, 1000);
}
function resetBanner(){
	$("#bann"+bannerPasado).css({left:"0px"});
}
function cambiarBanner(numero){
	detenerCambioBanners();
	var matriz = generarMatrizDeBanners(numero);

	bannerActual = numero;
	bannerSiguiente = matriz[1];
	iniciarCambioBanners();
}
function generarMatrizDeBanners(numero){
	var indiceBanner;

	if(numero == 0){
		indiceBanner=bannerActual;
	}else{
		indiceBanner=numero;
	}

	var arrBanners=new Array();

	for(i=1;i<=numeroBanners;i++){
		arrBanners.push(indiceBanner);
		if(indiceBanner==numeroBanners){
			indiceBanner=1;
		}else{
			indiceBanner++;
		}
	}
	
	for(i=0;i<numeroBanners;i++){
		$("#bann"+arrBanners[i]).css({zIndex:(numeroBanners-i)});
		$("#bannLink"+arrBanners[i]+" a").css({backgroundColor:"rgba(198,214,68,0.4)"});
	}

	$("#bannLink"+arrBanners[0]+" a").css({backgroundColor:"rgba(198,214,68,0.8)"});

	return arrBanners;
}
/*function abrirEnlace(e){
	e.preventDefault();
	var target = $(this).attr('data-target');
	if(target != undefined){
		document.location.href = target;
	}
}*/
iniciarCambioBanners();