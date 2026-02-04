<?php

// Gestiona login y registro
class Usuario {
   private $conexion; // Conexión a BD
   public function __construct($conexion) {
       $this->conexion = $conexion;
   }

   // Comprueba login
   public function login(string $email, string $password) {
       $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':email', $email);
       $stmt->execute();
       $usuario = $stmt->fetch();
       if ($usuario && password_verify($password, $usuario['password'])) {
           return $usuario;
       }
       return false;
   }

   // Registra un nuevo usuario
   public function registrar(string $nombre, string $email, string $password): bool {

       // Encriptamos la contraseña
       $hash = password_hash($password, PASSWORD_DEFAULT);
       $sql = "INSERT INTO usuarios (nombre, email, password)
               VALUES (:nombre, :email, :password)";
       $stmt = $this->conexion->prepare($sql);
       $stmt->bindParam(':nombre', $nombre);
       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':password', $hash);
       try {
           return $stmt->execute();
       } catch (PDOException $e) {
        
           // Error típico: email duplicado
           return false;
       }
   }
}