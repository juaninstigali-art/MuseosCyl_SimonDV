<?php
// Aquí mostramos el formulario para iniciar sesión
?>
<style>
   /* Contenedor para centrar el formulario en la pantalla */
   .zona-login {
       display: flex;
       justify-content: center;
       align-items: center;
       min-height: 70vh;
   }
   /* Caja donde va el formulario */
   .caja-formulario {
       background-color: #ffffff;
       border: 2px solid #E12720;
       border-radius: 8px;
       padding: 25px;
       width: 100%;
       max-width: 450px;
       box-sizing: border-box;
   }
   /* Título del formulario */
   .caja-formulario h1 {
       color: #E12720;
       margin: 0 0 20px 0;
       text-align: center;
   }
   /* Inputs */
   .caja-formulario input {
       width: 100%;
       padding: 12px;
       margin-bottom: 15px;
       border: 1px solid #ccc;
       border-radius: 5px;
       font-size: 15px;
       box-sizing: border-box;
   }
   /* Botón de enviar */
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
   /* Mensaje de error (si falla el login) */
   .error {
       color: #E12720;
       text-align: center;
       margin-bottom: 15px;
       font-weight: bold;
   }
   /* Enlace al registro */
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
<div class="zona-login">
<div class="caja-formulario">
<h1>Iniciar sesión</h1>

<!-- Si hay error, lo mostramos -->
<?php if (!empty($error)): ?>
<div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Formulario de login -->
<form action="<?php echo BASE_URL; ?>/index.php?accion=login_post" method="POST">
<input type="email" name="email" placeholder="Correo electrónico" required>
<input type="password" name="password" placeholder="Contraseña" required>

<!-- reCAPTCHA v2 -->
<div class="recaptcha-contenedor">
    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
</div>

<button type="submit" class="boton">Entrar</button>
</form>

<!-- Enlace para ir al registro -->
<a class="enlace" href="<?php echo BASE_URL; ?>/index.php?accion=registro">Crear cuenta</a>
</div>
</div>

<!-- Script de Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>