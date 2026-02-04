<?php
// Aquí definimos la estructura básica HTML y cargamos estilos y scripts generales
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<!-- Título dinámico: si existe $titulo lo usamos, si no usamos el nombre del proyecto -->
<title><?php echo isset($titulo) ? $titulo : NOMBRE_PROYECTO; ?></title>

<!-- Adaptación para móviles -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Script JavaScript para la gestión de favoritos -->
<script src="<?php echo BASE_URL; ?>/public/js/favoritos.js" defer></script>

<style>
       /* Estilos generales de la página */
       body {
           margin: 0;
           font-family: Arial, sans-serif;
           background-color: #f4f4f4;
       }
       /* Contenedor principal para centrar el contenido */
       .contenedor {
           max-width: 1200px;
           margin: auto;
           padding: 20px;
       }
       /* Estilos generales de enlaces */
       a {
           text-decoration: none;
           color: #E12720;
       }
</style>
</head>
<body>
<!-- Incluimos el menú de navegación -->
<?php require_once __DIR__ . '/nav.php'; ?>

<!-- Contenedor principal de las vistas -->
<div class="contenedor">