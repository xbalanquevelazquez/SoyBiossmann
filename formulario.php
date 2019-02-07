<script src="js/validarFormulario.js" type="text/javascript" language="javascript"></script>
<form action="contacto_env.php" method="post" name="form" id="form" onsubmit="return validarFormulario('form',1)" accept-charset="ISO-8859-1">
  <div class="contact_email">
    <label for="contact_name"> <span class="alerta">*</span>&nbsp;Escriba su nombre y apellidos: </label>
    <br />
    <input type="text" name="nombreContacto" id="nombreContacto" size="30" class="inputbox" value="" />
    <br />
    <label id="contact_emailmsg" for="contact_email"> <span class="alerta">*</span>&nbsp;Direcci&oacute;n de e-mail: </label>
    <br />
    <input type="text" id="mail" name="mail" size="30" value="" class="inputbox required validate-email" maxlength="100" />
    <br />
    <label for="contact_subject"> &nbsp;Tema del mensaje: </label>
    <br />
    <input type="text" name="tema" id="tema" size="30" class="inputbox" value="" />
    <br />
    <br />
    <label id="contact_textmsg" for="contact_text"> <span class="alerta">*</span>&nbsp;Escriba su mensaje: </label>
    <br />
    <textarea cols="50" rows="10" name="comentarios" id="comentarios" class="inputbox required"></textarea>
    <br />
    <br />
    <input name="submit" type="submit" />
  </div>
</form>
