<?php

// Extrae SOLO la tipología principal (una palabra)
?>
<style>
    .museos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }

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

    .museo-tarjeta h3 {
        color: #E12720;
        margin-bottom: 10px;
    }
    
    .museo-tarjeta p {
        margin: 8px 0;
        line-height: 1.4;
    }

    .botones {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .boton-accion {
        padding: 10px 14px;
        background-color: #E12720;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .boton-accion:hover {
        opacity: 0.9;
    }
</style>

<h1>Mis Museos Favoritos</h1>

<?php if (empty($favoritos)): ?>
    <p>No tienes museos guardados.</p>
<?php else: ?>
    <p>Tienes <?php echo count($favoritos); ?> museo(s) guardado(s).</p>
    
    <div class="museos-grid">
    <?php foreach ($favoritos as $fav): ?>
        <?php 
        // Extraer tipología usando el MISMO método que listado.php
        $tipologia = '';
        if (!empty($fav['informacion_adicional'])) {
            // Este es el regex EXACTO de listado.php
            if (preg_match('/Tipología del centro museístico:<\/strong>\s*([^<]+)/', $fav['informacion_adicional'], $matches)) {
                $tipologia = trim($matches[1]);
            }
        }
        ?>
        <div class="museo-tarjeta">
            <!-- Nombre del museo -->
            <h3><?php echo htmlspecialchars($fav['nombre'] ?? 'Sin nombre'); ?></h3>
            
            <!-- Localidad -->
            <?php if (!empty($fav['localidad'])): ?>
                <p><strong>Localidad:</strong> <?php echo htmlspecialchars($fav['localidad']); ?></p>
            <?php endif; ?>
            
            <!-- Tipo (SOLO la categoría: Arqueología, Especializado, etc.) -->
            <?php if (!empty($tipologia)): ?>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($tipologia); ?></p>
            <?php endif; ?>
            
            <div class="botones">
                <!-- Ver detalle del museo -->
                <a class="boton-accion"
                   href="<?php echo BASE_URL; ?>/index.php?accion=detalle&id=<?php echo htmlspecialchars($fav['api_id']); ?>">
                    Ver detalle
                </a>
                
                <!-- Eliminar favorito -->
                <button class="boton-accion" onclick="eliminarFavorito(<?php echo (int)$fav['id']; ?>)">
                    Eliminar
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
// Eliminamos un favorito por su ID
function eliminarFavorito(museoId) {
    if (!confirm('¿Seguro que quieres eliminar este favorito?')) {
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>/index.php?accion=eliminar_favorito', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ museo_id: museoId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            window.location.reload();
        } else {
            alert('Error al eliminar el favorito');
        }
    })
    .catch(() => alert('Error al eliminar el favorito'));
}
</script>
