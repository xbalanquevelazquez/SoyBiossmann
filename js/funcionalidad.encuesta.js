	var svg = d3.select("#graphOpina")
	.append("svg")
	.append("g")

	svg.append("g")
		.attr("class", "slices");
	svg.append("g")
		.attr("class", "labelName");
	svg.append("g")
		.attr("class", "labelValue");
	svg.append("g")
		.attr("class", "lines");

	var width = 216,
	    height = 150,
		radius = Math.min(width, height) / 2;

	var pie = d3.layout.pie()
		.sort(null)
		.value(function(d) {
			return d.value;
		});

	var arc = d3.svg.arc()
		.outerRadius(radius * 0.85)
		.innerRadius(radius * 0.25);

	var outerArc = d3.svg.arc()
		.innerRadius(radius * 0.9)
		.outerRadius(radius * 0.9);

	var legendRectSize = (radius * 0.05);
	var legendSpacing = radius * 0.02;

	var div = d3.select("body").append("div").attr("class", "toolTip");	

	svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

	var key = function(d){ return d.data.label; };
	var color = d3.scale.ordinal()
						.domain([
								"Si",
								"Si, pero podria mejorarse",
								"No"
          						])
						.range([
								'rgb(194,212,0)',
								'rgb(246,132,41)',
								'rgb(193,39,186)'
								]);
$(document).ready(function(){
						

	//:::::::::::::::::::::::::::::::::: INIT ::::::::::::::::::::::::::::::::
	getData();
	$('#enviarComentarios').click(function(e){
		e.preventDefault();
		votarEncuesta();
	});
	$('.encuestaOpcion').prop('checked',false);
	$('#opiniontext').val('');
});
function votarEncuesta(){
	//var valor = $('.encuestaOpcion:checked').val();
	var envioDatos = new FormData();
	envioDatos.append('action','votarEncuesta');
	envioDatos.append("valor",$('.encuestaOpcion:checked').val());
	envioDatos.append("comentario",$('#opiniontext').val());
	$.ajax({
		url: siteURL+"/webservice/acciones.php",
		type:"POST",
		processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
		contentType: false,
		data:envioDatos, 	
		cache:false,
		dataType:"json",
		success: function(response){
			if(response.success){
				$('#graphOpina').css({opacity:1,height:'150px'});
				$(".encuestaHome").html(response.data.mensaje);
				getData();
			}else{
				$(".encuestaHome").html(response.error);
			}
		}
	});
}
function change(data) {

	/* ------- PIE SLICES -------*/
	var slice = svg.select(".slices").selectAll("path.slice")
		.data(pie(data), key);

	slice.enter()
		.insert("path")
		.style("fill", function(d) { return color(d.data.label); })
		.attr("class", "slice");

	slice
		.transition().duration(1000)
		.attrTween("d", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				return arc(interpolate(t));
			};
		})
	slice
        .on("mousemove", function(d){
            div.style("left", d3.event.pageX+10+"px");
            div.style("top", d3.event.pageY-25+"px");
            div.style("display", "inline-block");
            div.html((d.data.label)+"<br>"+(d.data.percent)+'%');
        });
    slice
        .on("mouseout", function(d){
            div.style("display", "none");
        });
    slice.on("click", function(d){
    	
    /*	var valorDireccion = $("#direccion option:selected").val();
    	var valorArea = $("#comboAreas option:selected").val();
		var valorFamilia = $("#comboFamilias option:selected").val();
		var valorResponsabilidad = $("#comboResponsabilidades option:selected").val();
    	buscar('',1,valorDireccion,valorArea,d.data.estatus,valorFamilia,valorResponsabilidad);*/

    });

	slice.exit()
		.remove();



	/* ------- TEXT LABELS -------*/

	/*var updateLabels =  svg.select(".labelName")
		.selectAll("text")
		.data(pie(data), key);

	updateLabels
		.text(function(d) {
			return (d.data.label+": "+d.data.percent+'%');
		});

	var text = svg.select(".labelName")
		.selectAll("text")
		.data(pie(data), key);

	text.enter()
		.append("text")
		.attr("dy", ".35em")
		.text(function(d) {
			return (d.data.label+": "+d.data.percent+'%');
		});
	
	function midAngle(d){
		return d.startAngle + (d.endAngle - d.startAngle)/2;
	}

	text.transition().duration(1000)
		.attrTween("transform", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);

			return function(t) {
				var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
				return "translate("+ pos +")";
			};
		})
		.styleTween("text-anchor", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				return midAngle(d2) < Math.PI ? "start":"end";
			};
		});

	

	text.exit()
		.remove();
*/
	/* ----------------------LEGENDS OUT------------------ */
/*	var titulosTabla = ['','Dimensión','Cantidad','Porcentaje'];

	var removing = d3.select("#legends")
		.selectAll('table').remove();

	var legendsContainer = d3.select("#legends");//color.domain()


    legendsContainer
        .append('table').attr('class','tableLegends');

    var table = legendsContainer.select('table');

    table.append('thead').append('tr');
    table.append('tbody');

    var thead = table.select("thead").select("tr").selectAll("th").data(titulosTabla);

    thead.enter()
    	.append('th')
    	.text(function(d) { return d; });

    thead.exit()
		.remove();

    var tr = table.select('tbody').selectAll('tr')
    .data(pie(data), key);


    tr.enter()
    .append('tr');

    tr.append('td')
    	.attr('class','grafico')
    		.append('div')
	    	.attr('class','cuadrito')
	        .style('background', function(m) { return m.data.color; } );
    tr.append('td')
    	.attr('class','desc')
    		.html(function(m) { return m.data.label; });
	tr.append('td')
		.attr('class','num')
    		.html(function(m) { return m.data.value; });
	tr.append('td')
		.attr('class','perc')
    		.html(function(m) { return m.data.percent+' %'; });

	tr.exit()
		.remove();

*/
	/* ------- SLICE TO TEXT POLYLINES -------*/

	var polyline = svg.select(".lines").selectAll("polyline")
		.data(pie(data), key);
	
	polyline.enter()
		.append("polyline");

	polyline.transition().duration(1000)
		.attrTween("points", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				/*var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				//pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
				return [arc.centroid(d2), outerArc.centroid(d2), pos];*/
			};			
		});
	
	polyline.exit()
		.remove();
};
function getData(){
	var envioData = new FormData();
	envioData.append("action",'getGraph');
   	$.ajax({
       	url: siteURL+"webservice/acciones.php",
       	type:"POST",
        processData: false,//tanto processData como contentType deben estar en false para que funcione FormData
        contentType: false,
        data:envioData,
        cache:false,
        dataType:"json",
        success: function(respuesta){
	          	if(respuesta.success){
						change(respuesta.data.info);
				}else{
				       	texto = respuesta.error;
				       	$(".encuestaHome").append(texto);
				}
			}
		});

}