<?php
// Aquí mostramos opciones distintas si el usuario ha iniciado sesión o no
?>
<style>
   /* Barra superior: logo a la izquierda y menú a la derecha */
   nav{
       display: flex;
       justify-content: space-between;
       align-items: center;
       padding: 20px 28px;
       background: linear-gradient(90deg, #F2E205, #b91d16);
       border-bottom: 4px solid #D4AF37;
       box-shadow: 0 8px 20px rgba(0,0,0,0.12);
   }
   /* Zona del logo */
   .logo-nav{
       display: flex;
       align-items: center;
       gap: 16px;
   }
   /* Logo grande */
   .logo-nav img{
       height: 150px;
       width: auto;
       filter: drop-shadow(0 4px 10px rgba(0,0,0,0.35));
   }
    /* Efecto al pasar el ratón por el logo */
   .logo-nav img:hover{
       transform: scale(1.50) rotate(360deg);
       transition: transform 0.8s ease;
   }

   /* Texto al lado del logo */
   .logo-nav .texto{
        font-family: 'Bebas Neue', sans-serif;
        color: #ffffff;
        font-weight: bold;
        font-size: 22px;
        letter-spacing: 0.4px;
        text-shadow: 0 2px 6px rgba(0,0,0,0.35);
   }
   /* Zona del menú */
   .menu-nav{
       display: flex;
       align-items: center;
       gap: 20px;
       flex-wrap: wrap;
       justify-content: flex-end;
   }
   /* Enlaces y textos del menú */
   .menu-nav a,
   .menu-nav span{
       color: #ffffff;
       font-weight: bold;
       transition: transform 0.15s ease, opacity 0.15s ease;
   }
   /* Efecto al pasar el ratón por los enlaces */
   .menu-nav a:hover{
       transform: scale(1.07);
       opacity: 0.95;
       text-decoration: underline;
   }
   /* Saludo cuando hay sesión iniciada */
   .saludo{
       background: rgba(255,255,255,0.18);
       border: 1px solid rgba(212,175,55,0.7);
       padding: 8px 14px;
       border-radius: 999px;
       font-size: 14px;
       white-space: nowrap;
   }
   /* Adaptación a móvil: ponemos el menú en columna */
   @media (max-width: 768px){
       nav{
           flex-direction: column;
           gap: 12px;
           padding: 16px;
       }
       .logo-nav img{
           height: 70px;
       }
       .logo-nav .texto{
           font-size: 18px;
       }
   }
</style>
<nav>

<div class="logo-nav">
<!-- Al hacer clic en el logo volvemos al inicio -->
<a href="<?php echo BASE_URL; ?>/index.php" style="display:flex; align-items:center; gap:16px; text-decoration:none;">
<img src="<?php echo BASE_URL; ?>/public/img/logo.png" alt="Museos de Castilla y León">
<span class="texto">Museos de Castilla y León</span>
</a>
</div>
<div class="menu-nav">
<?php if (isset($_SESSION['usuario_nombre'])): ?>

<!-- Opciones cuando el usuario está logueado -->
<span class="saludo">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
<a href="<?php echo BASE_URL; ?>/index.php">Inicio</a>
<a href="<?php echo BASE_URL; ?>/index.php?accion=favoritos">Mis favoritos</a>
<a href="<?php echo BASE_URL; ?>/index.php?accion=logout">Cerrar sesión</a>

<?php else: ?>
<!-- Opciones cuando NO hay sesión -->
<a href="<?php echo BASE_URL; ?>/index.php">Inicio</a>
<a href="<?php echo BASE_URL; ?>/index.php?accion=login">Iniciar sesión</a>
<a href="<?php echo BASE_URL; ?>/index.php?accion=registro">Registrarse</a>
<?php endif; ?>
</div>
</nav>