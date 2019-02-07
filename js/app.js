$(document).ready(function(){
  console.log("Inicializado app");
  $("input, textarea, button").focus(function(){
    $(this).addClass("glow");
  });
  $("input, textarea, button").blur(function(){
    $(this).removeClass("glow");
  });
});