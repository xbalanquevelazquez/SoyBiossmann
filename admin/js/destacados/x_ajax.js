function ajax_setData(capa,text)
{	
	if (document.getElementById)
	{	
		document.getElementById(capa).innerHTML = text;
	}
	else if (document.all)
	{	x = document.all[capa];
		x.innerHTML = text;
	}	
	else if (document.layers)
	{	x = document.layers[capa];
		x.document.open();
		x.document.write(text);
		x.document.close();
	}
}
function ajax_getData(page,datosURL,capa,tipoEnvio)
{       var xmlHttp=false;
		if(typeof(XMLHttpRequest)!='undefined')
		{	try { xmlHttp = new XMLHttpRequest();  } 
			catch(exepcion){  }
        }    
		else
		{	try {  xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');  } 
			catch(excepcion){  xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');  }
        }
		var url = '';
		//------------GET--------------
		if(tipoEnvio=='GET'){ 
				url = '&'+datosURL; //debe iniciar con &
				
				try{ xmlHttp.open('GET',page+'?hash=' + Math.random() * 123456789 + url,true ); 
					 xmlHttp.onreadystatechange=function() 
					 { switch(xmlHttp.readyState)
						{ 	case 1: ajax_setData(capa,'<img src="../frog/plugins/lista_links/img/loader.gif" border="0" />'); break;
							case 4: ajax_setData(capa,xmlHttp.responseText); break;				
						}		
					 }
				   }
				catch(excepcion){  return false;    }
				xmlHttp.send(null);
       	//------------POST-------------
		}else if(tipoEnvio=='POST'){
				try{ xmlHttp.open('POST',page+'?hash=' + Math.random() * 123456789,true ); 
					 xmlHttp.onreadystatechange=function() 
					 { switch(xmlHttp.readyState)
						{ 	case 1: ajax_setData(capa,'<img src="../frog/plugins/lista_links/img/loader.gif" border="0" />'); break;
							case 4: ajax_setData(capa,xmlHttp.responseText); break;				
						}		
					 }
				   }
				catch(excepcion){  return false;    }
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.send(datosURL);
		}
		
		return true;      
}