<?php
// Aquí mostramos los museos en tarjetas y ponemos filtros. Sacamos los tipos de museo desde "informacion_adicional" para rellenar el select
$tipos = [];
foreach ($museos as $m) {
   if (!empty($m['informacion_adicional'])) {
       if (preg_match('/Tipología del centro museístico:<\/strong>\s*([^<]+)/', $m['informacion_adicional'], $matches)) {
           $tipos[] = trim($matches[1]);
       }
   }
}
$tipos = array_values(array_unique($tipos));
sort($tipos);
?>
<style>
/* Estilos de la zona de filtros */
.filtros-contenedor {
   display: flex;
   flex-direction: column;
   align-items: center;
   margin-bottom: 35px;
}
.titulo-listado {
   font-family: 'Bebas Neue', sans-serif;
  font-weight: 800;
  text-shadow: 0 0 4px rgba(255,255,255,0.6), 0 0 8px rgba(255,200,80,0.4), 2px 2px 6px rgba(0,0,0,0.35);

   color: #D9831A;
   margin-bottom: 20px;
   text-align: center;
}
/* Buscador y select */
.buscador-input {
   width: 100%;
   max-width: 600px;
   padding: 12px;
   font-size: 16px;
   border: 2px solid #E12720;
   border-radius: 5px;
   margin-bottom: 15px;
}
.filtro-tipo-select {
   padding: 12px 20px;
   font-size: 16px;
   border: 2px solid #E12720;
   border-radius: 5px;
   background-color: #ffffff;
   margin-bottom: 15px;
}
.contador-museos {
   font-size: 16px;
   margin-bottom: 10px;
}
/* Grid de tarjetas */
.museos-grid {
   display: grid;
   grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
   gap: 25px;
}
/* Tarjetas de museos */
.museo-tarjeta {
   background-color: #ffffff;
   border: 2px solid #E12720;
   border-radius: 8px;
   padding: 20px;
   position: relative;
   transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.museo-tarjeta:hover {
   transform: scale(1.03);
   box-shadow: 0 0 0 2px #E12720, 0 8px 20px rgba(0, 0, 0, 0.15);
}
/* Título del museo */
.museo-tarjeta h3 {
   color: #b91d16;
   margin-bottom: 15px;
   padding-right: 40px;
   line-height: 1.3;
}
/* Botón de favoritos */
.boton-favorito {
   position: absolute;
   top: 15px;
   right: 15px;
   font-size: 26px;
   background: none;
   border: none;
   cursor: pointer;
   transition: transform 0.15s ease;
}
.boton-favorito:hover {
   transform: scale(2.0);
}
.es-favorito { color: #E12720; }
.no-favorito { color: #D9831A; }
/* Botón de ver detalle */
.boton-accion {
   display: inline-block;
   margin-top: 10px;
   padding: 8px 12px;
   background-color: #E12720;
   color: #ffffff;
   border-radius: 5px;
   font-size: 14px;
   transition: transform 0.15s ease, opacity 0.15s ease;
}
.boton-accion:hover {
   transform: scale(1.05);
   opacity: 0.95;
}
/* Mensaje cuando no hay resultados */
.sin-resultados {
   margin-top: 30px;
   padding: 20px;
   border: 2px solid #E12720;
   background: #ffffff;
   display: none;
   text-align: center;
}
</style>

<!-- Título + filtros -->
<div class="filtros-contenedor">
<h1 class="titulo-listado">Museos de Castilla y León</h1>

<!-- Buscador por nombre o localidad -->
 <label for="buscador"></label>
<input
       type="text"
       id="buscador"
       class="buscador-input"
       placeholder="Buscar por nombre o localidad..."
       onkeyup="filtrarMuseos()"
>

<!-- Filtro por tipo -->
 <h3>Filtrar por tipo Museo</h3>
<select id="filtro-tipo" class="filtro-tipo-select" onchange="filtrarMuseos()">
<option value="">Elegir tipo</option>
<?php foreach ($tipos as $t): ?>
<option value="<?php echo htmlspecialchars(strtolower($t)); ?>">
<?php echo htmlspecialchars($t); ?>
</option>
<?php endforeach; ?>
</select>

<!-- Contador de museos -->
<p class="contador-museos">
       Total de museos: <strong id="contador-total"><?php echo count($museos); ?></strong>
<span id="contador-filtrados"></span>
</p>
</div>

<!-- Grid de museos -->
<div class="museos-grid" id="museos-grid">
<?php foreach ($museos as $museo): ?>
<?php

   // Sacamos la tipología del museo (para mostrarla y para filtrar)
   $tipologia = '';
   if (!empty($museo['informacion_adicional'])) {
       if (preg_match('/Tipología del centro museístico:<\/strong>\s*([^<]+)/', $museo['informacion_adicional'], $matches)) {
           $tipologia = trim($matches[1]);
       }
   }
   ?>
<div class="museo-tarjeta"
       data-nombre="<?php echo strtolower($museo['nombreentidad'] ?? ''); ?>"
       data-localidad="<?php echo strtolower($museo['localidad'] ?? ''); ?>"
       data-tipo="<?php echo strtolower($tipologia); ?>">

<?php if (isset($_SESSION['usuario_id'])): ?>
<!-- Estrella de favoritos (solo si hay sesión) -->
<button
               class="boton-favorito <?php echo in_array($museo['identificador'], $favoritos_ids) ? 'es-favorito' : 'no-favorito'; ?>"
               onclick="toggleFavorito(this, <?php echo htmlspecialchars(json_encode($museo)); ?>)">
<?php echo in_array($museo['identificador'], $favoritos_ids) ? '★' : '☆'; ?>
</button>

<?php endif; ?>
<!-- Nombre del museo -->
<h3><?php echo htmlspecialchars($museo['nombreentidad'] ?? 'Sin nombre'); ?></h3>

<!-- Localidad -->
<?php if (!empty($museo['localidad'])): ?>
<p><strong>Localidad:</strong> <?php echo htmlspecialchars($museo['localidad']); ?></p>
<?php endif; ?>

<!-- Tipo (si existe) -->
<?php if (!empty($tipologia)): ?>
<p><strong>Tipo:</strong> <?php echo htmlspecialchars($tipologia); ?></p>
<?php endif; ?>

<!-- Ver detalle: si no hay sesión, llevamos al login -->
<?php if (isset($_SESSION['usuario_id'])): ?>
<a class="boton-accion"
              href="<?php echo BASE_URL; ?>/index.php?accion=detalle&id=<?php echo $museo['identificador']; ?>">
               Ver detalle
</a>
<?php else: ?>
<a class="boton-accion"
              href="<?php echo BASE_URL; ?>/index.php?accion=login">
               Ver detalle
</a>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>

<!-- Mensaje si no hay resultados -->
<div class="sin-resultados" id="sin-resultados">
<p>No se encontraron museos con ese criterio.</p>
</div>

<script>
// Filtra museos por texto y por tipo
function filtrarMuseos() {
   const texto = document.getElementById('buscador').value.toLowerCase();
   const tipo = document.getElementById('filtro-tipo').value.toLowerCase();
   const tarjetas = document.querySelectorAll('.museo-tarjeta');
   let visibles = 0;
   tarjetas.forEach(t => {
       const nombre = t.dataset.nombre;
       const localidad = t.dataset.localidad;
       const tipoMuseo = t.dataset.tipo;
       const coincideTexto = nombre.includes(texto) || localidad.includes(texto);
       const coincideTipo = !tipo || tipoMuseo.includes(tipo);
       if (coincideTexto && coincideTipo) {
           t.style.display = 'block';
           visibles++;
       } else {
           t.style.display = 'none';
       }
   });

   // Actualizamos contador
   document.getElementById('contador-filtrados').textContent =
       (texto || tipo) ? ` (mostrando ${visibles})` : '';

   // Mostramos/ocultamos el mensaje de sin resultados
   document.getElementById('sin-resultados').style.display =
       visibles === 0 ? 'block' : 'none';
}
</script>