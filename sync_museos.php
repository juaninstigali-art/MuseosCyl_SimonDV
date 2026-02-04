<?php

//Script de sincronización de museos - VERSIÓN FINAL CORREGIDA. Usa el campo "horario_de_apertura" directo del JSON
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/basedatos.php';

echo "========================================\n";
echo "  SINCRONIZACIÓN DE MUSEOS - MuseosCyL\n";
echo "========================================\n";
echo "Inicio: " . date('Y-m-d H:i:s') . "\n\n";

// Conexión a BD
try {
    $bd = new BaseDatos();
    $conexion = $bd->obtenerConexion();
} catch (Exception $e) {
    echo "ERROR: No se pudo conectar a la base de datos\n";
    echo $e->getMessage() . "\n";
    exit(1);
}


//Obtiene TODOS los museos de la API usando paginación
 
function obtenerTodosLosMuseos() {
    $todos_museos = [];
    $limite = 100;
    $offset = 0;
    $intentos = 0;
    $max_intentos = 5;
    
    echo "📡 Descargando museos de la API...\n";
    
    while ($intentos < $max_intentos) {
        $url = API_MUSEOS_URL . '?limit=' . $limite . '&offset=' . $offset;
        echo "   Petición #" . ($intentos + 1) . ": offset=$offset, limit=$limite... ";
        
        $json = @file_get_contents($url);
        
        if ($json === false) {
            echo " ERROR\n";
            break;
        }
        
        $datos = json_decode($json, true);
        $resultados = $datos['results'] ?? [];
        
        if (empty($resultados)) {
            echo "✓ Sin más resultados\n";
            break;
        }
        
        $todos_museos = array_merge($todos_museos, $resultados);
        echo "✓ " . count($resultados) . " museos descargados\n";
        
        if (count($resultados) < $limite) {
            echo "   (Última página alcanzada)\n";
            break;
        }
        
        $offset += $limite;
        $intentos++;
    }
    
    echo "\n Total descargado: " . count($todos_museos) . " museos\n\n";
    return $todos_museos;
}


//Extrae la tipología del campo informacion_adicional
function extraerTipologia($info_adicional) {
    if (empty($info_adicional)) return null;
    
    if (preg_match('/Tipología del centro museístico:\s*([^\n]+)/i', $info_adicional, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

// Extrae y limpia el horario
function extraerHorario($museo) {
    // Buscar campo directo en el JSON
    $horario = $museo['horario_de_apertura'] ?? null;
    
    if (!$horario) {
        $horario = $museo['horario'] ?? null;
    }
    
    if (!$horario) {
        $horario = $museo['horarioapertura'] ?? null;
    }
    
    if (!$horario) {
        $horario = $museo['horario_apertura'] ?? null;
    }
    
    if ($horario) {
        // Limpiar HTML: quitar tags <ul>, <li>, <p>, etc.
        $horario = strip_tags($horario);
        // Limpiar espacios múltiples
        $horario = preg_replace('/\s+/', ' ', $horario);
        // Trim
        $horario = trim($horario);
        
        if (!empty($horario) && $horario !== '-') {
            return $horario;
        }
    }
    
    // Si no hay campo directo, buscar en informacion_adicional
    $info = $museo['informacion_adicional'] ?? '';
    
    if (!empty($info)) {
        if (preg_match('/Horario\s+de\s+apertura:\s*([^\n]+)/i', $info, $matches)) {
            $horario = trim($matches[1]);
            $horario = strip_tags($horario);
            if (!empty($horario) && $horario !== '-') {
                return $horario;
            }
        }
    }
    
    return null;
}


// Extrae la URL. Busca en varios campos posibles
function extraerUrl($museo) {
    // Probar campos directos
    $url = $museo['enlace_al_contenido'] ?? null;
    
    if (!$url) {
        $url = $museo['url'] ?? null;
    }
    
    if (!$url) {
        $url = $museo['web'] ?? null;
    }
    
    if (!$url) {
        $url = $museo['paginaweb'] ?? null;
    }
    
    if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    
    //Buscar en informacion_adicional
    $info = $museo['informacion_adicional'] ?? '';
    
    if (!empty($info)) {
        // Buscar URLs en el texto (http:// o https://)
        if (preg_match('/(https?:\/\/[^\s\<\>\"\']+)/i', $info, $matches)) {
            $url = trim($matches[1]);
            $url = rtrim($url, '.,;:)');
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }
    }
    
    return null;
}

//Obtener museos de la API
$museos = obtenerTodosLosMuseos();

if (empty($museos)) {
    echo " ERROR: No se pudieron obtener museos de la API\n";
    exit(1);
}

//Limpiar tabla museos_cache
echo "  Limpiando tabla museos_cache...\n";
try {
    $conexion->exec("TRUNCATE TABLE museos_cache");
    echo "   ✓ Tabla limpiada correctamente\n\n";
} catch (Exception $e) {
    echo "    ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

//Insertar museos en BD
echo " Insertando museos en base de datos...\n";

$sql = "INSERT INTO museos_cache 
        (api_id, nombre, localidad, tipologia, latitud, longitud, horario, url, informacion_adicional) 
        VALUES 
        (:api_id, :nombre, :localidad, :tipologia, :latitud, :longitud, :horario, :url, :info)";

$stmt = $conexion->prepare($sql);

$insertados = 0;
$errores = 0;
$omitidos = 0;
$con_horario = 0;
$con_url = 0;

foreach ($museos as $museo) {
    $api_id = $museo['identificador'] ?? null;
    
    if (!$api_id) {
        $omitidos++;
        continue;
    }
    
    $nombre = $museo['nombreentidad'] ?? 'Sin nombre';
    $localidad = $museo['localidad'] ?? null;
    $tipologia = extraerTipologia($museo['informacion_adicional'] ?? '');
    $latitud = $museo['posicion']['lat'] ?? null;
    $longitud = $museo['posicion']['lon'] ?? null;
    $horario = extraerHorario($museo);
    $url = extraerUrl($museo);
    $info = $museo['informacion_adicional'] ?? null;
    
    // Contadores para estadísticas
    if ($horario) $con_horario++;
    if ($url) $con_url++;
    
    try {
        $stmt->execute([
            ':api_id' => $api_id,
            ':nombre' => $nombre,
            ':localidad' => $localidad,
            ':tipologia' => $tipologia,
            ':latitud' => $latitud,
            ':longitud' => $longitud,
            ':horario' => $horario,
            ':url' => $url,
            ':info' => $info
        ]);
        $insertados++;
        
        if ($insertados % 25 == 0) {
            echo "   ✓ $insertados museos insertados...\n";
        }
        
    } catch (Exception $e) {
        $errores++;
        echo "     Error insertando museo $api_id: " . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "========================================\n";
echo "  RESUMEN DE SINCRONIZACIÓN\n";
echo "========================================\n";
echo "✓ Museos insertados: $insertados\n";
echo "✓ Con horario: $con_horario (" . round(($con_horario/$insertados)*100, 1) . "%)\n";
echo "✓ Con URL: $con_url (" . round(($con_url/$insertados)*100, 1) . "%)\n";
if ($errores > 0) {
    echo "  Errores: $errores\n";
}
if ($omitidos > 0) {
    echo "  Omitidos (sin ID): $omitidos\n";
}
echo "Fecha finalización: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n";

// Verificación final
$count = $conexion->query("SELECT COUNT(*) FROM museos_cache")->fetchColumn();
echo "\n Total de museos en museos_cache: $count\n";

// Muestra algunos ejemplos con horario y URL
echo "\n Ejemplos de museos con datos completos:\n";
$ejemplos = $conexion->query("
    SELECT nombre, horario, url 
    FROM museos_cache 
    WHERE horario IS NOT NULL OR url IS NOT NULL 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($ejemplos as $ej) {
    echo "\n   • " . $ej['nombre'] . "\n";
    if ($ej['horario']) {
        $horario_corto = substr($ej['horario'], 0, 80);
        echo "     Horario: " . $horario_corto . (strlen($ej['horario']) > 80 ? '...' : '') . "\n";
    }
    if ($ej['url']) {
        echo "     URL: " . $ej['url'] . "\n";
    }
}

echo "\n";
exit(0);
