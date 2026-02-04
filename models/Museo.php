<?php

 // Modelo Museo - Versión BD Local FINAL
class Museo {
   private $conexion;
   
   public function __construct($conexion) {
       $this->conexion = $conexion;
   }

   public function obtenerTodos(): array {
       $sql = "SELECT * FROM museos_cache ORDER BY nombre ASC";
       $stmt = $this->conexion->query($sql);
       $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
       return $this->adaptarFormatoParaVistas($resultados);
   }
   
   public function obtenerPorApiId(string $api_id) {
       $sql = "SELECT * FROM museos_cache WHERE api_id = :api_id LIMIT 1";
       $stmt = $this->conexion->prepare($sql);
       $stmt->execute([':api_id' => $api_id]);
       $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
       
       if (!$resultado) {
           return null;
       }
       
       return $this->adaptarMuseoParaVista($resultado);
   }
   
   public function obtenerEstadisticas(): array {
       $stats = [];
       
       $stmt = $this->conexion->query("SELECT COUNT(*) as total FROM museos_cache");
       $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
       
       $stmt = $this->conexion->query("
           SELECT tipologia, COUNT(*) as cantidad 
           FROM museos_cache 
           WHERE tipologia IS NOT NULL 
           GROUP BY tipologia 
           ORDER BY cantidad DESC
       ");
       $stats['por_tipologia'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
       $stmt = $this->conexion->query("
           SELECT localidad, COUNT(*) as cantidad 
           FROM museos_cache 
           WHERE localidad IS NOT NULL 
           GROUP BY localidad 
           ORDER BY cantidad DESC 
           LIMIT 10
       ");
       $stats['top_localidades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
       $stmt = $this->conexion->query("
           SELECT MAX(ultima_actualizacion) as fecha 
           FROM museos_cache
       ");
       $stats['ultima_actualizacion'] = $stmt->fetch(PDO::FETCH_ASSOC)['fecha'];
       
       return $stats;
   }

   private function adaptarFormatoParaVistas(array $museos): array {
       $adaptados = [];
       
       foreach ($museos as $museo) {
           $adaptados[] = $this->adaptarMuseoParaVista($museo);
       }
       
       return $adaptados;
   }
   
   private function adaptarMuseoParaVista(array $museo): array {
       return [
           'identificador' => $museo['api_id'],
           'nombreentidad' => $museo['nombre'],
           'localidad' => $museo['localidad'],
           'posicion' => [
               'lat' => $museo['latitud'],
               'lon' => $museo['longitud']
           ],
           'horario' => $museo['horario'],
           'horarioapertura' => $museo['horario'],
           'horario_apertura' => $museo['horario'],
           'horario_de_apertura' => $museo['horario'],
           'url' => $museo['url'],
           'web' => $museo['url'],
           'paginaweb' => $museo['url'],
           'enlace_al_contenido' => $museo['url'],
           'informacion_adicional' => $museo['informacion_adicional']
       ];
   }

   public function obtenerPorApiIdEnBD(string $api_id) {
       $sql = "SELECT * FROM museos WHERE api_id = :api_id LIMIT 1";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':api_id', $api_id);
       $stmt->execute();
       return $stmt->fetch();
   }

    //Guarda museo en tabla museos (favoritos). Ahora incluye informacion_adicional para extraer tipología
    public function guardarMuseoSiNoExiste(array $museo) {
       if (empty($museo['api_id'])) {
           return false;
       }

       $existente = $this->obtenerPorApiIdEnBD($museo['api_id']);
       if ($existente) {
           return $existente['id'];
       }

       $sql = "INSERT INTO museos (api_id, nombre, localidad, latitud, longitud, foto, horario, url, informacion_adicional)
               VALUES (:api_id, :nombre, :localidad, :latitud, :longitud, :foto, :horario, :url, :info)";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':api_id', $museo['api_id']);
       $stmt->bindParam(':nombre', $museo['nombre']);
       $stmt->bindParam(':localidad', $museo['localidad']);
       $stmt->bindParam(':latitud', $museo['latitud']);
       $stmt->bindParam(':longitud', $museo['longitud']);
       $stmt->bindParam(':foto', $museo['foto']);
       $stmt->bindParam(':horario', $museo['horario']);
       $stmt->bindParam(':url', $museo['url']);
       $stmt->bindParam(':info', $museo['informacion_adicional']);
       
       if ($stmt->execute()) {
           return $this->conexion->lastInsertId();
       }
       return false;
   }
   
   public function obtenerTodosDesdeAPI(): array {
       return $this->obtenerTodos();
   }
}