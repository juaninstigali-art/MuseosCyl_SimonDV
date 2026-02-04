<?php
// Controlador de usuarios: login, registro y logout
class ControladorUsuario {
   private $conexion;       // Conexión BD
   private $modelo_usuario; // Modelo Usuario
   public function __construct() {
       // Abrimos conexión a BD
       $bd = new BaseDatos();
       $this->conexion = $bd->obtenerConexion();
       // Cargamos el modelo
       require_once __DIR__ . '/../models/Usuario.php';
       $this->modelo_usuario = new Usuario($this->conexion);
   }
   // Muestra el formulario de login
   public function mostrarLogin() {
       $titulo = 'Iniciar sesión';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/usuarios/login.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   // Procesa el login
   public function procesarLogin() {
       // VERIFICAR reCAPTCHA
       $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
       if (!verificarRecaptcha($recaptcha_response)) {
           $error = 'Por favor, completa el captcha de seguridad';
           $titulo = 'Iniciar sesión';
           require_once __DIR__ . '/../views/layout/header.php';
           require_once __DIR__ . '/../views/usuarios/login.php';
           require_once __DIR__ . '/../views/layout/footer.php';
           return;
       }
       
       // Recogemos datos
       $email = $_POST['email'] ?? '';
       $password = $_POST['password'] ?? '';
       // Intentamos login en el modelo
       $usuario = $this->modelo_usuario->login($email, $password);
       if ($usuario) {
           // Guardamos sesión
           $_SESSION['usuario_id'] = $usuario['id'];
           $_SESSION['usuario_nombre'] = $usuario['nombre'];
           // Volvemos al inicio
           header('Location: ' . BASE_URL);
           exit;
       }
       // Si falla, mostramos error
       $error = 'Credenciales incorrectas';
       $titulo = 'Iniciar sesión';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/usuarios/login.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   // Muestra el formulario de registro
   public function mostrarRegistro() {
       $titulo = 'Registro';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/usuarios/registro.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   // Procesa el registro
   public function procesarRegistro() {
       // VERIFICAR reCAPTCHA
       $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
       if (!verificarRecaptcha($recaptcha_response)) {
           $error = 'Por favor, completa el captcha de seguridad';
           $titulo = 'Registro';
           require_once __DIR__ . '/../views/layout/header.php';
           require_once __DIR__ . '/../views/usuarios/registro.php';
           require_once __DIR__ . '/../views/layout/footer.php';
           return;
       }
       
       // Recogemos datos del formulario
       $nombre = $_POST['nombre'] ?? '';
       $email = $_POST['email'] ?? '';
       $password = $_POST['password'] ?? '';
       $password_confirm = $_POST['password_confirm'] ?? '';
       // Validación simple: contraseñas iguales
       if ($password !== $password_confirm) {
           $error = 'Las contraseñas no coinciden';
           $titulo = 'Registro';
           require_once __DIR__ . '/../views/layout/header.php';
           require_once __DIR__ . '/../views/usuarios/registro.php';
           require_once __DIR__ . '/../views/layout/footer.php';
           return;
       }
       // Registro en el modelo
       $ok = $this->modelo_usuario->registrar($nombre, $email, $password);
       if ($ok) {
           // Si registra bien, mandamos al login
           header('Location: ' . BASE_URL . '/index.php?accion=login');
           exit;
       }
       // Si falla, mostramos error (email repetido o datos inválidos)
       $error = 'No se pudo registrar (email repetido o datos inválidos)';
       $titulo = 'Registro';
       require_once __DIR__ . '/../views/layout/header.php';
       require_once __DIR__ . '/../views/usuarios/registro.php';
       require_once __DIR__ . '/../views/layout/footer.php';
   }
   // Cierra sesión
   public function cerrarSesion() {
       session_destroy();
       header('Location: ' . BASE_URL);
       exit;
   }
}