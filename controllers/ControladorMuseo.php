<?php

//Controlador de museos: listado y detalle
 
class ControladorMuseo {
   private $conexion;
   private $modelo_museo;
   private $modelo_favorito;
   
   public function __construct() {
       // Abrimos conexión a la base de datos
       $bd = new BaseDatos();
       $this->conexion = $bd->obtenerConexion();
       
       // Cargamos modelos
       require_once __DIR__ . '/../models/Museo.php';
       require_once __DIR__ . '/../models/Favorito.php';
       $this->modelo_museo = new Museo($this->conexion);
       $this->modelo_favorito = new Favorito($this->conexion);
   }
   
   
    //Muestra el listado principal de museos
    
   public function listar() {
       // Obtenemos TODOS los museos desde BD local 
       $museos = $this->modelo_museo->obtenerTodos();
       
       // IDs de favoritos si hay sesión
       $favoritos_ids = [];
       if (isset($_SESSION['usuario_id'])) {
           $favoritos_ids = $this->modelo_favorito
               ->obtenerApiIdsFavoritos((int)$_SESSION['usuario_id']);
       }
       
       // Cargamos la vista
       $titulo = 'Museos de Castilla y León';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/museos/listado.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   
   
    //Muestra el detalle de un museo
    
   public function detalle() {
       // Si no hay sesión, redirigimos al login
       if (!isset($_SESSION['usuario_id'])) {
           header('Location: ' . BASE_URL . '/index.php?accion=login');
           exit;
       }
       
       // ID del museo (API)
       $api_id = $_GET['id'] ?? null;
       if (!$api_id) {
           header('Location: ' . BASE_URL);
           exit;
       }
       
       // Búsqueda DIRECTA en BD local 
       $museo = $this->modelo_museo->obtenerPorApiId($api_id);
       
       // Si no se encuentra, mensaje simple
       if (!$museo) {
           $titulo = 'Museo no encontrado';
           require_once __DIR__ . '/../views/layout/header.php';
           echo '<p>Museo no encontrado.</p>';
           echo '<a href="' . BASE_URL . '">Volver</a>';
           require_once __DIR__ . '/../views/layout/footer.php';
           exit;
       }
       
       // Cargamos vista detalle
       $titulo = $museo['nombreentidad'] ?? 'Detalle del museo';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/museos/detalle.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
}
