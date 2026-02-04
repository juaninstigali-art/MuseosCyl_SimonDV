<?php
// Controlador de favoritos

class ControladorFavorito {
   private $conexion;
   private $modelo_museo;
   private $modelo_favorito;
   
   public function __construct() {
       $bd = new BaseDatos();
       $this->conexion = $bd->obtenerConexion();

       require_once __DIR__ . '/../models/Museo.php';
       require_once __DIR__ . '/../models/Favorito.php';
       $this->modelo_museo = new Museo($this->conexion);
       $this->modelo_favorito = new Favorito($this->conexion);
   }

   public function listar() {
       if (!isset($_SESSION['usuario_id'])) {
           header('Location: ' . BASE_URL . '/index.php?accion=login');
           exit;
       }
       
       $usuario_id = (int)$_SESSION['usuario_id'];
       $favoritos = $this->modelo_favorito->listarPorUsuario($usuario_id);
       
       $titulo = 'Mis favoritos';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/usuarios/favoritos.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   
   public function agregar() {
       if (!isset($_SESSION['usuario_id'])) {
           echo json_encode(['ok' => false]);
           exit;
       }
       
       $datos = json_decode(file_get_contents('php://input'), true);
       $api_id = $datos['api_id'] ?? null;
       
       if (!$api_id) {
           echo json_encode(['ok' => false]);
           exit;
       }
       
       // Guardamos museo CON informacion_adicional
       $museo_id = $this->modelo_museo->guardarMuseoSiNoExiste([
           'api_id' => $api_id,
           'nombre' => $datos['nombre'] ?? '',
           'localidad' => $datos['localidad'] ?? '',
           'latitud' => $datos['latitud'] ?? null,
           'longitud' => $datos['longitud'] ?? null,
           'foto' => null,
           'horario' => $datos['horario'] ?? null,
           'url' => $datos['url'] ?? null,
           'informacion_adicional' => $datos['informacion_adicional'] ?? null  
       ]);
       
       $this->modelo_favorito->usuario_id = (int)$_SESSION['usuario_id'];
       $this->modelo_favorito->museo_id = (int)$museo_id;
       $ok = $this->modelo_favorito->agregar();
       
       echo json_encode(['ok' => $ok]);
       exit;
   }

   public function eliminar() {
       if (!isset($_SESSION['usuario_id'])) {
           echo json_encode(['ok' => false]);
           exit;
       }
       
       $datos = json_decode(file_get_contents('php://input'), true);
       $museo_id = $datos['museo_id'] ?? null;
       $api_id = $datos['api_id'] ?? null;
       
       if (!$museo_id && $api_id) {
           $museo = $this->modelo_museo->obtenerPorApiIdEnBD($api_id);
           $museo_id = $museo['id'] ?? null;
       }
       
       if (!$museo_id) {
           echo json_encode(['ok' => false]);
           exit;
       }
       
       $this->modelo_favorito->usuario_id = (int)$_SESSION['usuario_id'];
       $this->modelo_favorito->museo_id = (int)$museo_id;
       $ok = $this->modelo_favorito->eliminar();
       
       echo json_encode(['ok' => $ok]);
       exit;
   }
}