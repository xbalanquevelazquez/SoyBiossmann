function validarFormulario(data){
			var campos = Array();
			var error = Array();
			var lastErrorId = '';
			campos = data;
		
			for (i=0; i<campos.length; i++){
				error[i] = validarCampo(campos[i][0],campos[i][1],campos[i][2]);
			}
			
			var cantidadErrores = 0;
			var mensajeError = '';
			for(j=0; j<error.length; j++){
				if(error[j] != 'ok') { 
				cantidadErrores++;
				mensajeError +=  error[j]+'\n';
				}
			}

			if (cantidadErrores>0){
				alert(acentos(mensajeError));
				return false;
			} else {
				return true;
			}
}
		
function validarCampo(idCampo,tipoValidacion,nombreCampo,numeroCampos){
			
			if (tipoValidacion != 'radio'){
				var idObject = document.getElementById(idCampo);
				var idContenido = idObject.value;
			}
			//-----------------------------------------------------------------
			//-----------------------------------------------------------------			
			if (tipoValidacion == 'texto'){
				if (idContenido == ''){
					var mensaje = 'El campo '+nombreCampo+' no puede quedar vac�o\n';
					lastErrorId = idCampo;
					return mensaje;
				} else {
					var mensaje = 'ok';
					return mensaje;
				}
			} else if(tipoValidacion == 'numero'){
				if (idContenido == ''){
					var mensaje = 'El campo '+nombreCampo+' no puede quedar vac�o\n';
					lastErrorId = idCampo;
					return mensaje;
				} else {
					if (isNaN(idContenido)){
						var mensaje = 'El campo '+nombreCampo+' debe contener un n�mero\n';
						lastErrorId = idCampo;
						return mensaje;
					} else {
						var mensaje = 'ok';
						return mensaje;
					}
				}
			} else if(tipoValidacion == 'combo'){
				var seleccion = idObject.options[idObject.selectedIndex].value;
				if(seleccion == 0){
					var mensaje = 'Debe elegir una opci�n en el campo '+nombreCampo+'\n';
					lastErrorId = idCampo;
					return mensaje;
				} else {
					var mensaje = 'ok';
					return mensaje;
				}
			} else if(tipoValidacion == 'radio') {
				var valorRadio = false;
				var valorNegativo = false;
				switch(idCampo){
					/*
					case 'radgenero':
						valorRadio = radgenero;
						valorNegativo = false; //no aplica
					break;
					case 'radp1':
						valorRadio = radp1;
						valorNegativo = radp1NO;
					break;
					case 'radp2':
						valorRadio = radp2;
						valorNegativo = radp2NO;
					break;
					case 'radp3':
						valorRadio = radp3;
						valorNegativo = radp3NO;
					break;
					case 'radp4':
						valorRadio = radp4;
						valorNegativo = radp4NO;
					break;
					case 'radp5':
						valorRadio = radp5;
						valorNegativo = radp5NO;
					break;
					case 'radp6':
						valorRadio = radp6;
						valorNegativo = radp6NO;
					break;
					case 'radp7':
						valorRadio = radp7;
						valorNegativo = radp7NO;
					break;
					*/
				}
				if(valorRadio){
					if(valorNegativo){
						var mensaje = 'Debe ACEPTAR el campo '+nombreCampo+' para poder continuar\n';
						lastErrorId = idCampo;
						return mensaje;
					}else{
						var mensaje = 'ok';
						return mensaje;
					}	
				}else{
					var mensaje = 'Debe elegir una opci�n en el campo '+nombreCampo+'\n';
					lastErrorId = idCampo;
					return mensaje;
				}
			} else if(tipoValidacion == 'email'){
					var regexpX = /^[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*@[A-Za-z0-9-_]+(\.[A-Za-z0-9-_]+)+$/;
					if (regexpX.test(idContenido)){
						var mensaje = 'ok';
						return mensaje;
					}else{
						var mensaje = 'Ingrese una direcci�n de correo v�lida\n';
						lastErrorId = idCampo;
						return mensaje;
					}
				}
}
function reemplazarString(indice){
			indiceObjeto = document.getElementById(indice);
			indiceValor = indiceObjeto.value;
			

			var nuevoValor = indiceValor.toLowerCase(indiceValor);//todo a min�sculas
			nuevoValor = nuevoValor.replace(/�/g, "a");//cambiar caracteres especiales (acentos y �)
			nuevoValor = nuevoValor.replace(/�/g, "e");
			nuevoValor = nuevoValor.replace(/�/g, "i");
			nuevoValor = nuevoValor.replace(/�/g, "o");
			nuevoValor = nuevoValor.replace(/�/g, "u");
			nuevoValor = nuevoValor.replace(/�/g, "ni");
			nuevoValor = nuevoValor.replace(/'/g, "");
			nuevoValor = nuevoValor.replace(/�/g, "");

			nuevoValor = nuevoValor.replace(/\s/g, "-");//cambiar espacios por guiones
			
			
			indiceObjeto.value=nuevoValor;
}
function acentos(texto){
			var nuevoValor = texto;
			nuevoValor = nuevoValor.replace(/�/g, "\u00e1");
			nuevoValor = nuevoValor.replace(/�/g, "\u00e9");
			nuevoValor = nuevoValor.replace(/�/g, "\u00ed");
			nuevoValor = nuevoValor.replace(/�/g, "\u00f3");
			nuevoValor = nuevoValor.replace(/�/g, "\u00fa");
			nuevoValor = nuevoValor.replace(/�/g, "\u00f1");
		
			nuevoValor = nuevoValor.replace(/�/g, "\u00c1");//cambiar caracteres especiales (acentos y �)
			nuevoValor = nuevoValor.replace(/�/g, "\u00c9");
			nuevoValor = nuevoValor.replace(/�/g, "\u00cd");
			nuevoValor = nuevoValor.replace(/�/g, "\u00d3");
			nuevoValor = nuevoValor.replace(/�/g, "\u00da");
			nuevoValor = nuevoValor.replace(/�/g, "\u00d1");
			return nuevoValor;
}
function mayusculas(id){
			var indiceObjeto = document.getElementById(id);
			var indiceValor = indiceObjeto.value;
			
			var nuevoValor = indiceValor.toUpperCase(indiceValor);
			indiceObjeto.value = nuevoValor;
}