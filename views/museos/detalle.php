<?php
// Preparamos algunos datos antes de mostrarlos (horario y web). Probamos varios campos porque la API no siempre usa el mismo nombre

$horario = $museo['horario'] ?? '';
if (!$horario) $horario = $museo['horarioapertura'] ?? '';
if (!$horario) $horario = $museo['horario_apertura'] ?? '';
if (!$horario) $horario = $museo['horario_de_apertura'] ?? '';

// Si sigue vacío, intentamos sacarlo de informacion_adicional (suele venir en HTML)
if (!$horario && !empty($museo['informacion_adicional'])) {
   // Caso típico cuando viene con etiqueta <strong>
   if (preg_match('/Horario[^:]*:\s*<\/strong>\s*([^<]+)/i', $museo['informacion_adicional'], $matches)) {
       $horario = trim($matches[1]);
   }
   
   // Variante por si no viene con <strong>
   if (!$horario && preg_match('/Horario[^:]*:\s*([^<]+)/i', $museo['informacion_adicional'], $matches2)) {
       $horario = trim($matches2[1]);
   }
}

// Igual que con el horario, probamos varias claves para la URL
$url = $museo['url'] ?? '';
if (!$url) $url = $museo['web'] ?? '';
if (!$url) $url = $museo['paginaweb'] ?? '';
if (!$url) $url = $museo['enlace_al_contenido'] ?? '';

?>
<style>
   /* Caja principal del detalle */
   .detalle-museo {
       background-color: #ffffff;
       border: 2px solid #E12720;
       border-radius: 10px;
       padding: 25px;
   }
   
   /* Título del museo */
   .detalle-museo h1 {
       color: #b91d16;
       margin-bottom: 15px;
   }
   
   /* Texto general */
   .detalle-museo p {
       margin: 10px 0;
       line-height: 1.4;
   }
   
   /* Enlaces */
   .detalle-museo a {
       color: #b91d16;
       font-weight: bold;
   }
   
   /* Contenedor del mapa */
   .bloque-mapa {
       margin-top: 18px;
       border: 2px solid #E12720;
       border-radius: 10px;
       overflow: hidden;
   }
</style>

<div class="detalle-museo">
    <!-- Nombre del museo -->
    <h1><?php echo htmlspecialchars($museo['nombreentidad'] ?? 'Museo'); ?></h1>
    
    <!-- Localidad -->
    <?php if (!empty($museo['localidad'])): ?>
        <p><strong>Localidad:</strong> <?php echo htmlspecialchars($museo['localidad']); ?></p>
    <?php endif; ?>
    
    <!-- Horario -->
    <p>
        <strong>Horario:</strong>
        <?php echo $horario ? htmlspecialchars($horario) : 'No disponible'; ?>
    </p>
    
    <!-- Web oficial -->
    <?php if (!empty($url)): ?>
        <p>
            <strong>Web oficial:</strong>
            <a href="<?php echo htmlspecialchars($url); ?>" target="_blank">
                Abrir página del museo
            </a>
        </p>
    <?php endif; ?>
    
    <!-- Mapa con OpenStreetMap -->
    <?php if (isset($museo['posicion']['lat'], $museo['posicion']['lon'])): ?>
        <h3>Ubicación</h3>
        <div class="bloque-mapa">
            <iframe
                width="100%"
                height="350"
                frameborder="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=<?php
                    echo ($museo['posicion']['lon'] - 0.01) . ',' .
                         ($museo['posicion']['lat'] - 0.01) . ',' .
                         ($museo['posicion']['lon'] + 0.01) . ',' .
                         ($museo['posicion']['lat'] + 0.01);
                ?>&marker=<?php
                    echo $museo['posicion']['lat'] . ',' . $museo['posicion']['lon'];
                ?>">
            </iframe>
        </div>
    <?php endif; ?>
    
    <!-- Enlace para volver al listado -->
    <br>
    <a href="<?php echo BASE_URL; ?>/index.php">← Volver al listado</a>
</div>
