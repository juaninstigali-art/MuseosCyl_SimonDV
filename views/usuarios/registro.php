<?php
// Mostramos el formulario para crear una cuenta con validación de contraseña doble

?>
<style>

   /* Contenedor que centra el formulario en la pantalla */
   .zona-registro {
       display: flex;
       justify-content: center;
       align-items: center;
       min-height: 70vh;
   }
   /* Caja del formulario */
   .caja-formulario {
       background-color: #ffffff;
       border: 2px solid #E12720;
       border-radius: 8px;
       padding: 25px;
       width: 100%;
       max-width: 450px;
       box-sizing: border-box;
   }
   /* Título */
   .caja-formulario h1 {
       color: #E12720;
       text-align: center;
       margin-bottom: 20px;
   }
   /* Campos del formulario */
   .caja-formulario input {
       width: 100%;
       padding: 12px;
       margin-bottom: 15px;
       border: 1px solid #ccc;
       border-radius: 5px;
       font-size: 15px;
       box-sizing: border-box;
   }
   /* Botón principal */
   .boton {
       width: 100%;
       padding: 12px;
       background-color: #E12720;
       color: #ffffff;
       border: none;
       border-radius: 5px;
       cursor: pointer;
       font-size: 15px;
   }
   .boton:hover {
       opacity: 0.9;
   }
   /* Mensaje de error */
   .error {
       color: #E12720;
       text-align: center;
       margin-bottom: 15px;
       font-weight: bold;
   }
   /* Enlace inferior */
   .enlace {
       display: block;
       text-align: center;
       margin-top: 15px;
       color: #E12720;
       font-weight: bold;
   }
   .enlace:hover {
       text-decoration: underline;
   }
   
   /* RECAPTCHA */
   .recaptcha-contenedor {
       display: flex;
       justify-content: center;
       align-items: center;
       margin: 20px 0;
       width: 100%;
   }
   
</style>
<div class="zona-registro">
<div class="caja-formulario">
<h1>Registro</h1>
<!-- Mostramos error si algo falla -->
<?php if (!empty($error)): ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Formulario de registro -->
<form action="<?php echo BASE_URL; ?>/index.php?accion=registro_post" method="POST">

<!-- Nombre del usuario -->
<input type="text" name="nombre"  placeholder="Nombre" required>

<!-- Correo electrónico -->
<input type="email" name="email" placeholder="Correo electrónico" required>

<!-- Contraseña -->
<input type="password" name="password" placeholder="Contraseña" required>

<!-- Confirmación de contraseña -->
<input type="password" name="password_confirm" placeholder="Repetir contraseña" required>

<!-- reCAPTCHA v2 -->
<div class="recaptcha-contenedor">
    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
</div>

<!-- Botón de envío -->
<button type="submit" class="boton">Crear cuenta</button>
</form>

<!-- Enlace para volver al login -->
<a class="enlace" href="<?php echo BASE_URL; ?>/index.php?accion=login">
           Ya tengo cuenta
</a>
</div>
</div>

<!-- Script de Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>