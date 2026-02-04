<?php
// Cargamos las claves del reCAPTCHA (este archivo está en .gitignore)
require_once __DIR__ . '/recaptcha.php';
// Ruta física del proyecto
define('BASE_PATH', __DIR__ . '/..');
// URL base del proyecto (ajusta si cambia la carpeta)
define('BASE_URL', 'http://localhost/museoscyl');
// Nombre de la aplicación
define('NOMBRE_PROYECTO', 'MuseosCyL');
// URL de la API de museos de Castilla y León
define(
  'API_MUSEOS_URL','https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/museos/records'
);
// Función para verificar reCAPTCHA v2
function verificarRecaptcha($response) {
   // Si no hay respuesta, retornar false
   if (empty($response)) {
       error_log("reCAPTCHA: Sin respuesta del usuario");
       return false;
   }
   // URL de verificación de Google
   $url = 'https://www.google.com/recaptcha/api/siteverify';
   // Datos a enviar
   $data = [
       'secret' => RECAPTCHA_SECRET_KEY,
       'response' => $response
   ];
   // Configurar la petición POST con TIMEOUT
   $options = [
       'http' => [
           'method' => 'POST',
           'header' => 'Content-type: application/x-www-form-urlencoded',
           'content' => http_build_query($data),
           'timeout' => 5  // SEGUNDOS
       ]
   ];
   try {
       // Crear contexto y hacer la petición
       $context = stream_context_create($options);
       $result = @file_get_contents($url, false, $context);
       // Si no hay respuesta de Google, registrar error
       if ($result === false) {
           error_log("reCAPTCHA: Error al conectar con Google (timeout o sin conexión)");
           // En caso de error de conexión, se deniega acceso por seguridad
           return false;
       }
       // Decodificar la respuesta JSON
       $resultJson = json_decode($result);
       // Verificar que la respuesta sea válida
       if (!isset($resultJson->success)) {
           error_log("reCAPTCHA: Respuesta inválida de Google");
           return false;
       }
       // Log del resultado para debug
       if (!$resultJson->success) {
           $errores = isset($resultJson->{'error-codes'}) ? implode(', ', $resultJson->{'error-codes'}) : 'desconocido';
           error_log("reCAPTCHA: Verificación fallida - Errores: " . $errores);
       }
       // Retornar true si la verificación fue exitosa
       return $resultJson->success === true;
   } catch (Exception $e) {
       // Si hay error en la comunicación con Google, registrar y retornar false
       error_log("reCAPTCHA: Excepción capturada - " . $e->getMessage());
       return false;
   }
}