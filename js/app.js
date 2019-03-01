$(document).ready(function(){
  var colorBordeActual = '';
  var roundnessBordeActual = '';
  $("input, textarea, button").focus(function(){
    $(this).addClass("glow");
  });
  $("input, textarea, button").blur(function(){
    $(this).removeClass("glow");
  });
  $(".efectoRealce").hover( 
  		function(){
  			colorBordeActual = $(this).css("border-color");
  			roundnessBordeActual = $(this).css("border-radius");
  			//console.log(colorBordeActual);
  			//In
  			$(this).css({
  				borderRadius: '1px',
    			boxShadow: '0 3px 6px -1px rgba(0, 0, 0, 0.5)',
				top:'-1px',
				left:'-1px',
				borderColor:'#1e8795'
			});
  		}, function(){
  			//Out
  			$(this).css({
  				borderRadius: roundnessBordeActual,
    			boxShadow: '0 0px 0px 0px rgba(0, 0, 0, 0)',
				top:'0px',
				left:'0px',
				borderColor: colorBordeActual
			});
  		});
  console.log("Intranet Biossmann ready!!!");
});