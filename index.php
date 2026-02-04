<?php

// Iniciamos la sesión para poder usar login y datos del usuario
session_start();

// Cargamos la configuración general y la conexión a la base de datos
require_once __DIR__ . '/config/config.php';

require_once __DIR__ . '/config/basedatos.php';

// Recogemos la acción que viene por la URL. Si no viene ninguna, entramos al inicio
$accion = $_GET['accion'] ?? 'inicio';
// Enrutador principal del proyecto. Según la acción, llamamos al controlador correspondiente
switch ($accion) {

   // MUSEOS
   case 'inicio':
   case 'museos':

       // Mostramos el listado de museos
       require_once __DIR__ . '/controllers/ControladorMuseo.php';
       $controlador = new ControladorMuseo();
       $controlador->listar();
       break;
   case 'detalle':
       // Mostramos el detalle de un museo concreto
       require_once __DIR__ . '/controllers/ControladorMuseo.php';
       $controlador = new ControladorMuseo();
       $controlador->detalle();
       break;

   // USUARIOS 
   case 'login':
       // Mostramos el formulario de login
       require_once __DIR__ . '/controllers/ControladorUsuario.php';
       $controlador = new ControladorUsuario();
       $controlador->mostrarLogin();
       break;

   case 'login_post':
       // Procesamos los datos del login
       require_once __DIR__ . '/controllers/ControladorUsuario.php';
       $controlador = new ControladorUsuario();
       $controlador->procesarLogin();
       break;

   case 'registro':
       // Mostramos el formulario de registro
       require_once __DIR__ . '/controllers/ControladorUsuario.php';
       $controlador = new ControladorUsuario();
       $controlador->mostrarRegistro();
       break;

   case 'registro_post':
       // Procesamos los datos del registro
       require_once __DIR__ . '/controllers/ControladorUsuario.php';
       $controlador = new ControladorUsuario();
       $controlador->procesarRegistro();
       break;

   case 'logout':
       // Cerramos la sesión del usuario
       require_once __DIR__ . '/controllers/ControladorUsuario.php';
       $controlador = new ControladorUsuario();
       $controlador->cerrarSesion();
       break;

   // FAVORITOS
   case 'favoritos':
       // Mostramos los museos favoritos del usuario
       require_once __DIR__ . '/controllers/ControladorFavorito.php';
       $controlador = new ControladorFavorito();
       $controlador->listar();
       break;

   case 'agregar_favorito':
       // Añadimos un museo a favoritos (AJAX)
       require_once __DIR__ . '/controllers/ControladorFavorito.php';
       $controlador = new ControladorFavorito();
       $controlador->agregar();
       break;
       
   case 'eliminar_favorito':
       // Eliminamos un museo de favoritos (AJAX)
       require_once __DIR__ . '/controllers/ControladorFavorito.php';
       $controlador = new ControladorFavorito();
       $controlador->eliminar();
       break;

   // ACCIÓN NO VÁLIDA 
      default:
       // Si la acción no existe, mostramos error 404
       http_response_code(404);
       echo "<h2>Error 404</h2>";
       echo "<p>La página solicitada no existe.</p>";
       echo "<a href='" . BASE_URL . "'>Volver al inicio</a>";
       break;
}