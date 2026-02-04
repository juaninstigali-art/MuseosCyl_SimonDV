<?php

// Gestiona la relación entre usuarios y museos
class Favorito {
   private $conexion; // Conexión a BD
   public $usuario_id; // ID del usuario
   public $museo_id;   // ID del museo (BD)
   public function __construct($conexion) {
       $this->conexion = $conexion;
   }

   // Lista los favoritos de un usuario
   public function listarPorUsuario(int $usuario_id): array {
       $sql = "SELECT m.*
               FROM favoritos f
               JOIN museos m ON m.id = f.museo_id
               WHERE f.usuario_id = :usuario_id
               ORDER BY m.nombre";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':usuario_id', $usuario_id);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   // Inserta un favorito (evita duplicados)
   public function agregar(): bool {
       $sql = "INSERT IGNORE INTO favoritos (usuario_id, museo_id)
               VALUES (:usuario_id, :museo_id)";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':usuario_id', $this->usuario_id);
       $stmt->bindParam(':museo_id', $this->museo_id);
       $stmt->execute();
       return $stmt->rowCount() > 0;
   }

   // Elimina un favorito
   public function eliminar(): bool {
       $sql = "DELETE FROM favoritos
               WHERE usuario_id = :usuario_id AND museo_id = :museo_id";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':usuario_id', $this->usuario_id);
       $stmt->bindParam(':museo_id', $this->museo_id);
       $stmt->execute();
       return $stmt->rowCount() > 0;
   }
   
   // Devuelve los api_id de los favoritos del usuario
   public function obtenerApiIdsFavoritos(int $usuario_id): array {
       $sql = "SELECT m.api_id
               FROM favoritos f
               JOIN museos m ON m.id = f.museo_id
               WHERE f.usuario_id = :usuario_id";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':usuario_id', $usuario_id);
       $stmt->execute();
       return array_column($stmt->fetchAll(), 'api_id');
   }
}