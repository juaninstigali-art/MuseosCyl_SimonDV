<?php
// Pie de página del proyecto
?>
</div> <!-- cierre del panel principal -->
</div> <!-- cierre del contenedor general -->
<style>
   /* Estilos generales del footer */
   footer {
       position: relative;
       margin-top: 40px;
       padding: 30px 15px 22px 15px;
       /* Degradado igual al del header */
       background: linear-gradient(90deg, #F2E205, #b91d16);
       /* Línea superior decorativa */
       border-top: 4px solid #D4AF37;
       /* Sombra para dar profundidad */
       box-shadow: 0 -8px 20px rgba(0,0,0,0.12);
       text-align: center;
       color: #ffffff;
       font-weight: bold;
   }
   /* Efecto visual de onda en la parte superior */
   footer::before {
       content: "";
       position: absolute;
       top: -18px;
       left: 0;
       width: 100%;
       height: 18px;
       background: rgba(255,255,255,0.35);
       border-top-left-radius: 30px;
       border-top-right-radius: 30px;
   }
   /* Título principal del footer */
   .footer-titulo {
       font-size: 18px;
       margin: 0 0 6px 0;
       text-shadow: 0 2px 6px rgba(0,0,0,0.35);
   }
   /* Texto secundario del footer */
   .footer-subtitulo {
       font-size: 13px;
       margin: 0;
       opacity: 0.95;
       text-shadow: 0 2px 6px rgba(0,0,0,0.35);
   }
   /* Contenedor de enlaces */
   .footer-enlaces {
       margin-top: 10px;
   }
   /* Estilos de los enlaces */
   .footer-enlaces a {
       color: #ffffff;
       text-decoration: none;
       margin: 0 10px;
       display: inline-block;
       transition: transform 0.15s ease, opacity 0.15s ease;
   }
   /* Efecto hover en enlaces */
   .footer-enlaces a:hover {
       transform: scale(1.05);
       opacity: 0.95;
       text-decoration: underline;
   }
   /* Ajustes para móviles */
   @media (max-width: 600px){
       footer { padding: 26px 12px 18px 12px; }
       .footer-titulo { font-size: 16px; }
   }
</style>
<footer>

<!-- Nombre del proyecto -->
<p class="footer-titulo">MuseosCyL · Proyecto SIMOND.V</p>

<!-- Tipo de proyecto -->
<p class="footer-subtitulo">PROYECTO INTERMODULAR DWN</p>

<!-- Enlaces de navegación -->
<div class="footer-enlaces">
<a href="<?php echo BASE_URL; ?>/index.php">Inicio</a>
<a href="<?php echo BASE_URL; ?>/index.php?accion=favoritos">Mis favoritos</a>
</div>

<!-- Año dinámico -->
<p class="footer-subtitulo" style="margin-top: 12px;">
       © <?php echo date('Y'); ?> MuseosCyL
</p>
</footer>
</body>
</html>