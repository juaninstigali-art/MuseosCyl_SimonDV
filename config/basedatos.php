<?php

// Conexión a la base de datos
class BaseDatos {

   // Datos de conexión
   private $host = 'localhost';
   private $nombre_bd = 'museoscyl';
   private $usuario = 'root';
   private $contrasena = '';
   private $charset = 'utf8mb4';
   private $conexion = null; // Guardamos la conexión

   // Devuelve la conexión PDO
   public function obtenerConexion() {

       // Si ya existe conexión, la reutilizamos
       if ($this->conexion !== null) {
           return $this->conexion;
       }
       try {
           // Cadena de conexión
           $dsn = "mysql:host={$this->host};dbname={$this->nombre_bd};charset={$this->charset}";

           // Creamos PDO
           $this->conexion = new PDO($dsn, $this->usuario, $this->contrasena);

           // Configuración básica
           $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
       } catch (PDOException $e) {
        
           // Error claro si falla la conexión
           die('Error de conexión con la base de datos');
       }
       return $this->conexion;
   }
}