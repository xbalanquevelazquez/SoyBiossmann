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

	// EXPEDIENTE
	relacionPadreHijo();
	activaCIE10();
	accionesNota();
});


// EXPEDIENTE

function relacionPadreHijo(){
	console.log('relacionPadreHijo()');
	var fo = $('.nota-web-form');
	var foHijos = fo.find('.tiene-padre').each(function() {
		$(this).each(function() {
			var yo = $(this);
			var needed = String(yo.data('need'));
			var neededValue = Number(yo.data('nvalue'));
			if (isNaN(neededValue)) neededValue = 1;
			else if (neededValue === 0) neededValue = 1;
			var hijo = yo.parent().parent();
			var padre = fo.find('#' + needed + '_wf_id');
			hijo.addClass('soy-un-hijo needed-' + needed);
			console.log('neededValue: ' + neededValue);
			padre.addClass('soy-un-padre').find('select').attr('data-nvalue', neededValue).soyUnPadre();
		});
	});
}
function activaCIE10(){
	var esDiagnostico = document.location.href.indexOf('diagnostico') > -1 ? true : false;
	if (esDiagnostico){
		var superCie10 = Number($('#superCie10').val());
		var cie10Div = $('#cie10_wf_id');
		var cie10Div = $('#cie10_wf_id');

		if (isNaN(superCie10)) superCie10 = 0;
		if (superCie10 === 0){
			cie10Div.addClass('oculto');
		} else {
			cie10Div.removeClass('oculto');
		}
		$('#superCie10').change();
		$('#superCie10').soyCie10();
	}
}
function primeraNota(){
	console.log('primeraNota()');
	setTimeout(function(){
		var es = String($('.notas-navbar').find('.nota-medica:first').find('a').attr('href'));
		console.log('es ' + es);
		window.location = es;
		// es.find('a').click();
	},100);
}
function accionesNota(){
	var esAntecedentesGinecoObstetricos = document.location.href.indexOf('antecedentesGinecoObstetricos') > -1 ? true : false;
	var esPadecimientoActual = document.location.href.indexOf('padecimientoActual') > -1 ? true : false;
	if (esAntecedentesGinecoObstetricos){
		antecedentesGinecoObstetricos();
	} else if (esPadecimientoActual){
		accionesPadecimiento();
	} else {
		console.log('No, no lo es');
	}

}
// Funciones por nota
function antecedentesGinecoObstetricos(){

	console.log('antecedentesGinecoObstetricos()');
}
function accionesPadecimiento(){
	console.log('accionesPadecimiento()');
	var fecha1 = $('.nota-web-form').find('input#fecha1');
	var fecha2 = $('.nota-web-form').find('input#fecha2');
	var fecha3 = $('.nota-web-form').find('input#fecha3');
	var fecha4 = $('.nota-web-form').find('input#fecha4');

	var amenorreaDias1 = $('.nota-web-form').find('input#amenorreaDias1');
	var amenorreaDias2 = $('.nota-web-form').find('input#amenorreaDias2');
	var amenorreaDias3 = $('.nota-web-form').find('input#amenorreaDias3');

	fecha1.unbind('change').change(function() {
		var fecha_registro_fecha = String($('#fecha_registro_fecha').val());
		if (fecha_registro_fecha === ''){
			fecha_registro_fecha = new Date().toISOString().slice(0,10);
		}
		console.log('fecha [' + fecha_registro_fecha + ']');
		var vFecha1 = String(fecha1.val());
		var fechaDias1 = new Date(vFecha1).getTime();
		var fechaDias2 = new Date(fecha_registro_fecha).getTime();
		var difDias = (fechaDias2 - fechaDias1)/(1000*60*60*24);
		console.log('F= ' + fecha_registro_fecha + ' vs ' + vFecha1 + ' = ' + difDias);
		fecha2.val(vFecha1);
		fecha3.val(vFecha1);
		amenorreaDias1.val(difDias);
		amenorreaDias2.val(difDias);
		amenorreaDias3.val(difDias);
	});
	fecha2.attr('readonly', true);
	fecha3.attr('readonly', true);
	amenorreaDias1.attr('readonly', true);
	amenorreaDias2.attr('readonly', true);
	amenorreaDias3.attr('readonly', true);
}
function saltoSiguienteNota(){
	setTimeout(function(){
		var notaSiguiente = String($('a.la-nota-siguiente').attr('href'));
		if (notaSiguiente === 'undefined' || notaSiguiente === 'null'){
			notaSiguiente = '';
		}
		if (notaSiguiente !== ''){
			console.log('Clic siguiente ' + notaSiguiente);
			window.location = notaSiguiente;
		}
	},1000);
}

$.fn.soyUnPadre = function(){
	$(this).each(function() {
		$(this).unbind('chage').change(function() {
			var yo = $(this);
			var id = String(yo.attr('id'));
			var val = Number(yo.val());
			var hijo = $('.needed-' + id);
			var needed = Number(yo.data('nvalue'));
			if (isNaN(needed)) needed = 1;
			if (isNaN(val)) val = 0;
			if (val == needed){
				hijo.removeClass('oculto').find('textarea').focus();
				hijo.find('label').find('span').html('*').addClass('obligatorio');
			} else {
				hijo.addClass('oculto').find('textarea').val('');
				hijo.find('label').find('span').html('').removeClass('obligatorio');
			}
		});
	});

	return this;
};
$.fn.soyCie10 = function(){
	$(this).each(function() {
		$(this).unbind('chage').change(function() {
			var yo = $(this);
			var val = Number(yo.val());
			if (isNaN(val)) val = 0;
			var cie10Div = $('#cie10_wf_id');
			var cie10hija = cie10Div.find('select');
			console.log(' -- CIe10 es ' + val);
			if (val == 999){
				cie10Div.find('select').html('');
				cie10Div.addClass('oculto');
			} else if (val > 0){
				cie10Div.removeClass('oculto');
				var listaTemp = $('#cie10temp').clone(true);
				listaTemp.find('option').each(function() {
					var opt = $(this);
					var padre = Number(opt.data('p'));
					if (isNaN(padre)) padre = 666;
					if (padre != val && padre != 666){
						opt.remove();
					}
					console.log(' --- ' + padre);
				});
				cie10hija.html(listaTemp.html());
				listaTemp.remove();
			} else {
				cie10Div.find('select').html('');
				cie10Div.addClass('oculto');
			}

		});
	});

	return this;
};
$.fn.btnGuardarNota = function(){
	$(this).each(function() {
		$(this).unbind('click').click(function() {
			var yo = $(this);
			var destino = String($('#destino').val());
			var tabla = String($('#tabla').val());
			var idEstudio = String($('#idEstudio').val());
			var action = String($('#action').val());
			var fo = $('.nota-web-form');
			var datos = {};
			var alerta;
			datos['tabla'] = tabla;
			datos['idEstudio'] = idEstudio;
			datos['action'] = action;
			fo.find('div.wf-item').each(function(){
				var dato = $(this);
				var span = dato.find('div.form-group');
				var inpt = dato.find('input.wf-input');
				var area = dato.find('textarea.wf-input');
				var selct = dato.find('select.wf-input');
				var tipo = String(dato.data('tipo'));
				var campo = String(dato.attr('id').replace('_wf_id', ''));
				var valor, vacio = false;
				// console.log(' -- tipo:' + tipo + ', campo:' + campo);
				switch(tipo){
					case 'number':
						valor = Number(inpt.val())
						if (isNaN(valor)){
							valor = 0;
							vacio = true;
						}
						break;
					case 'select': case 'boolean': case 'list':
						valor = Number(selct.val());
						if (isNaN(valor)){
							vacio = true;
						} 
						break;
					case 'text': case 'textarea':
						valor = String(area.val());
						if (valor === 'undefined') valor = '';
						if (valor === ''){
							vacio = true;
						}
						break;
					case 'hidden':
						valor = inpt.val();
						campo = inpt.attr('id');
						break;
					case 'fecha': case 'date':
						valor = inpt.val();
						campo = inpt.attr('id');
						break;
					case 'hora':
						valor = dato.find('select:first').val() + ':' + dato.find('select:last').val();
						campo = dato.data('c');
						break;
					case 'area':
						valor = area.val();
						campo = area.attr('id');
						break;
					case 'check':
						valor = inpt.is(':checked') ? 1 : 0;
						campo = inpt.attr('id');
						// console.log(' -- campo:' + campo + ', valor:' + valor);
						break;
					case 'listado':
						valor = '';
						campo = dato.data('campo');
						dato.find('input[type=checkbox]').each(function() {
							if ($(this).is(':checked')){
								valor += $(this).attr('id') + '&';
							}
						});
						break;
					case 'specialcheck':
						valor = Number(dato.find('input[name=' + campo + ']:checked').attr('value'));
						if (isNaN(valor)) valor = 0;
						// console.log(' -- tipo:' + tipo + ' :: ' + campo + ' => ' + valor);
						break;
				}
				// console.log(' -- tipo:' + tipo + ' :: ' + campo + ' => ' + valor);
				var obligatorio = String(dato.find('span.obligatorio').html()) == '*' ? true : false;
				span.removeClass('rojo-alerta');
				if (obligatorio){
					if (vacio){
						alerta = true;
						span.addClass('rojo-alerta');
						// console.log(' -- Alerta!! ' + campo + ' - [' + valor + ']');
					}
				}
				datos[campo] = valor;
			});
			if (alerta){
				var texto = "<div class='bg-warning'>Faltan campos por llenar</div>";
				$("#estatus").html(texto);
				$("#estatus div").delay(3000).fadeOut();
			} else {
				console.log(' -- url:' + destino);
				console.log(' -- tabla:' + tabla);
				console.log(' -- idEstudio:' + idEstudio);
				console.log(datos);
				$.ajax({
					url: destino,
					type: 'POST',
					dataType: 'json',
					data: datos,
				})
				.done(function(respuesta) {
					console.log("success");
					console.log(respuesta);
					var texto = '';
					if(respuesta.success){
						$("#estatus").html('');
						if (respuesta.data.alerta == 'final' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							setTimeout(function(){
								window.location = respuesta.data.salto;
							},1000);
						} else if (respuesta.data.alerta == '' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							saltoSiguienteNota();
							// setTimeout(function(){
							// 	var notaSiguiente = String($('a.la-nota-siguiente').attr('href'));
							// 	if (notaSiguiente === 'undefined' || notaSiguiente === 'null'){
							// 		notaSiguiente = '';
							// 	}
							// 	if (notaSiguiente !== ''){
							// 		console.log('Clic siguiente ' + notaSiguiente);
							// 		window.location = notaSiguiente;
							// 	}
							// },1000);
						} else if (respuesta.data.alerta == 'ok-cambio'){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							var sigueNotaMenu = String($('li.nota-medica-activa').next('li.nav-item').find('.false-nav-link').html());
							if (sigueNotaMenu !== ''){
								saltoSiguienteNota();
							}
							console.log('[' + sigueNotaMenu + ']');
						} else {
							texto = "<div class='bg-warning'>" + respuesta.data.alerta + "</div>";
							$("#estatus").html(texto);
						}
					} else{
						texto = "<div class='bg-warning'>" + respuesta.error + "</div>";
						$("#estatus").html(texto);
					}
					$("#estatus").html(respuesta.data.codigo);
					$("#estatus div").delay(3000).fadeOut();
				})
				.fail(function() {
					console.log("error");
				});
			}
		});
	});

	return this;
};
$.fn.btnTerminarPedido = function(){
	$(this).each(function() {
		$(this).unbind('click').click(function() {
			var yo = $(this);
			var destino = String($('#destino').val());
			var idEstudio = Number($('#idEstudio').val());
			var aprobado = Number($('#aprobado').val());
			var action = 'terminar';
			var datos = { idEstudio: idEstudio, action: action, aprobado: aprobado };
			if (confirm('¿DESEAS TERMINAR ESTE REGISTRO?')){
				console.log(' -- url:' + destino);
				console.log(' -- idEstudio:' + idEstudio);
				console.log(datos);
				$.ajax({
					url: destino,
					type: 'POST',
					dataType: 'json',
					data: datos,
				})
				.done(function(respuesta) {
					console.log("success");
					console.log(respuesta);
					var texto = '';
					if(respuesta.success){
						$("#estatus").html('');
						if (respuesta.data.alerta == '' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							setTimeout(function(){
								location.reload();
							},1000);
						} else {
							texto = "<div class='bg-warning'>" + respuesta.data.alerta + "</div>";
							$("#estatus").html(texto);
						}
					} else{
						texto = "<div class='bg-warning'>" + respuesta.error + "</div>";
						$("#estatus").html(texto);
					}
					$("#estatus").html(respuesta.data.codigo);
					$("#estatus div").delay(3000).fadeOut();
				})
				.fail(function() {
					console.log("error");
				});
			}
		});
	});

	return this;
};
$.fn.btnCancelarPedido = function(){
	$(this).each(function() {
		$(this).unbind('click').click(function() {
			var yo = $(this);
			var dato = yo.parent().parent();
			var destino = String($('#destino').val());
			var idEstudio = Number($('#idEstudio').val());
			var action = 'cancelar';
			var razon_no_candidata = String($('#razon_no_candidata').val());
			var datos = { razon_no_candidata: razon_no_candidata, idEstudio: idEstudio, action: action};
			var alerta;
			var span = dato.find('div.form-group');

			if (razon_no_candidata === ''){
				alerta = true;
				span.addClass('rojo-alerta');
				console.log(' -- Alerta!!  - [' + razon_no_candidata + ']');
			} else {
				span.removeClass('rojo-alerta');
			}
			if (alerta){
				var texto = "<div class='bg-warning'>Faltan campos por llenar</div>";
				$("#estatus").html(texto);
				$("#estatus div").delay(3000).fadeOut();
			} else {
				console.log(' -- url:' + destino);
				console.log(' -- idEstudio:' + idEstudio);
				console.log(datos);
				$.ajax({
					url: destino,
					type: 'POST',
					dataType: 'json',
					data: datos,
				})
				.done(function(respuesta) {
					console.log("success");
					console.log(respuesta);
					var texto = '';
					if(respuesta.success){
						$("#estatus").html('');
						if (respuesta.data.alerta == '' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							setTimeout(function(){
								location.reload();
							},1000);
						} else {
							texto = "<div class='bg-warning'>" + respuesta.data.alerta + "</div>";
							$("#estatus").html(texto);
						}
					} else{
						texto = "<div class='bg-warning'>" + respuesta.error + "</div>";
						$("#estatus").html(texto);
					}
					$("#estatus").html(respuesta.data.codigo);
					$("#estatus div").delay(3000).fadeOut();
				})
				.fail(function() {
					console.log("error");
				});
			}
		});
	});

	return this;
};
$.fn.btnTerminarPedido = function(){
	$(this).each(function() {
		$(this).unbind('click').click(function() {
			var yo = $(this);
			var destino = String($('#destino').val());
			var idEstudio = Number($('#idEstudio').val());
			var aprobado = Number($('#aprobado').val());
			var action = 'terminar';
			var datos = { idEstudio: idEstudio, action: action, aprobado: aprobado };
			if (confirm('¿DESEAS TERMINAR ESTE REGISTRO?')){
				console.log(' -- url:' + destino);
				console.log(' -- idEstudio:' + idEstudio);
				console.log(datos);
				$.ajax({
					url: destino,
					type: 'POST',
					dataType: 'json',
					data: datos,
				})
				.done(function(respuesta) {
					console.log("success");
					console.log(respuesta);
					var texto = '';
					if(respuesta.success){
						$("#estatus").html('');
						if (respuesta.data.alerta == '' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							setTimeout(function(){
								location.reload();
							},1000);
						} else {
							texto = "<div class='bg-warning'>" + respuesta.data.alerta + "</div>";
							$("#estatus").html(texto);
						}
					} else{
						texto = "<div class='bg-warning'>" + respuesta.error + "</div>";
						$("#estatus").html(texto);
					}
					$("#estatus").html(respuesta.data.codigo);
					$("#estatus div").delay(3000).fadeOut();
				})
				.fail(function() {
					console.log("error");
				});
			}
		});
	});

	return this;
};
$.fn.btnCancelarPedido = function(){
	$(this).each(function() {
		$(this).unbind('click').click(function() {
			var yo = $(this);
			var dato = yo.parent().parent();
			var destino = String($('#destino').val());
			var idEstudio = Number($('#idEstudio').val());
			var action = 'cancelar';
			var razon_no_candidata = String($('#razon_no_candidata').val());
			var datos = { razon_no_candidata: razon_no_candidata, idEstudio: idEstudio, action: action};
			var alerta;
			var span = dato.find('div.form-group');

			if (razon_no_candidata === ''){
				alerta = true;
				span.addClass('rojo-alerta');
				console.log(' -- Alerta!!  - [' + razon_no_candidata + ']');
			} else {
				span.removeClass('rojo-alerta');
			}
			if (alerta){
				var texto = "<div class='bg-warning'>Faltan campos por llenar</div>";
				$("#estatus").html(texto);
				$("#estatus div").delay(3000).fadeOut();
			} else {
				console.log(' -- url:' + destino);
				console.log(' -- idEstudio:' + idEstudio);
				console.log(datos);
				$.ajax({
					url: destino,
					type: 'POST',
					dataType: 'json',
					data: datos,
				})
				.done(function(respuesta) {
					console.log("success");
					console.log(respuesta);
					var texto = '';
					if(respuesta.success){
						$("#estatus").html('');
						if (respuesta.data.alerta == '' ){
							texto = "<div class='bg-warning'>" + respuesta.buffer + "</div>";
							$("#estatus").html(texto);
							setTimeout(function(){
								location.reload();
							},1000);
						} else {
							texto = "<div class='bg-warning'>" + respuesta.data.alerta + "</div>";
							$("#estatus").html(texto);
						}
					} else{
						texto = "<div class='bg-warning'>" + respuesta.error + "</div>";
						$("#estatus").html(texto);
					}
					$("#estatus").html(respuesta.data.codigo);
					$("#estatus div").delay(3000).fadeOut();
				})
				.fail(function() {
					console.log("error");
				});
			}
		});
	});

	return this;
};
