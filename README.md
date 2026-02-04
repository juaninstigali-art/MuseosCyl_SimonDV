# MuseosCyL

Proyecto intermodular desarrollado en 2º de Desarrollo de Aplicaciones Web (DAW).

La aplicación **MuseosCyL** es una web que permite consultar información sobre los museos de Castilla y León, obteniendo los datos desde la API pública de la Junta de Castilla y León y almacenándolos en una base de datos local para mejorar el rendimiento y la disponibilidad del sistema.

El proyecto está desarrollado en PHP siguiendo una estructura MVC sencilla y pensada a nivel alumno.

---

## Objetivo del proyecto

El objetivo principal del proyecto es:

- Mostrar un listado completo de museos de Castilla y León.

- Permitir a los usuarios registrarse e iniciar sesión.

- Guardar museos como favoritos.

- Consultar el detalle de cada museo.

- Reducir la dependencia directa de la API mediante el uso de base de datos local.

- Aplicar buenas prácticas básicas de seguridad y sostenibilidad.

---

## Tecnologías utilizadas

- **PHP** (lógica del servidor)

- **MySQL** (base de datos)

- **HTML / CSS** (estructura y estilos)

- **JavaScript** (interacciones y AJAX)

- **API de Museos de Castilla y León**

- **Google reCAPTCHA** (seguridad en formularios)

- **XAMPP** (entorno de desarrollo)

- **Git y GitHub** (control de versiones)

---

## Estructura del proyecto
 
museoscyl/
│
├── index.php                     → Punto de entrada y router principal
├── sync_museos.php               → Script para sincronizar/actualizar museos desde la API a la BD
├── museoscyl.sql                 → Script SQL para crear la base de datos y tablas
├── README.md                     → Documentación del proyecto
│
├── config/                       → Configuración del proyecto
│   ├── config.php                → Constantes globales (BASE_URL, API, nombre del proyecto, etc.)
│   ├── basedatos.php             → Conexión a MySQL con PDO
│
├── controllers/                  → Controladores (lógica)
│   ├── ControladorMuseo.php      → Listado, filtros y detalle de museos
│   ├── ControladorUsuario.php    → Registro, login y logout
│   └── ControladorFavorito.php   → Guardar/eliminar/listar favoritos
│
├── models/                       → Modelos (acceso a datos)
│   ├── Museo.php                 → Gestión de museos (API/BD según versión)
│   ├── Usuario.php               → Gestión de usuarios (registro/login)
│   └── Favorito.php              → Operaciones con la tabla de favoritos
│
├── views/                        → Vistas (pantallas)
│   ├── layout/
│   │   ├── header.php            → Cabecera común
│   │   ├── nav.php               → Menú superior con logo
│   │   └── footer.php            → Pie de página
│   │
│   ├── museos/
│   │   ├── listado.php           → Listado de museos + buscador/filtros
│   │   └── detalle.php           → Detalle del museo (horario, web y mapa)
│   │
│   └── usuarios/
│       ├── login.php             → Formulario de inicio de sesión
│       ├── registro.php          → Formulario de registro
│       └── favoritos.php         → Listado de museos favoritos
│
└── public/                       → Archivos públicos
├── img/
│   └── logo.png              → Logo del proyecto
└── js/
└── favoritos.js          → JS para gestionar favoritos (AJAX)

---
## Funcionamiento general
- La web carga los museos desde la **base de datos local**.
- La base de datos se rellena a partir de la **API oficial** mediante scripts de sincronización.
- Si la API deja de funcionar, la web sigue mostrando los datos almacenados.
- El usuario puede:
 - Registrarse.
 - Iniciar sesión.
 - Ver el listado de museos.
 - Filtrar museos por nombre, localidad y tipo.
 - Consultar el detalle de cada museo.
 - Guardar y eliminar museos favoritos.
---
## Seguridad aplicada
- Contraseñas almacenadas mediante **hash**.
- Validación de formularios en servidor.
- Uso de **Google reCAPTCHA** en login y registro para evitar bots.
- Claves sensibles protegidas mediante `.gitignore`.
---
## Sostenibilidad y optimización
- Se evita hacer llamadas constantes a la API.
- Los datos se almacenan en la base de datos local.
- Se limita el número de peticiones externas.
- Se mejora el rendimiento y se reduce el consumo de recursos.
- La web puede funcionar aunque la API no esté disponible temporalmente.
---
## Dificultades encontradas
- Problemas con la API (límites de resultados y estructura cambiante).
- Gestión de múltiples peticiones a la API.
- Configuración de reCAPTCHA y despliegue.
- Conflictos de Git durante el trabajo en equipo.
- Adaptación del proyecto para funcionar tanto en local como en servidor.
---
## Conclusiones
Este proyecto nos ha permitido aplicar de forma práctica los conocimientos adquiridos durante el curso, especialmente en PHP, bases de datos y arquitectura MVC. Hemos aprendido a organizar un proyecto real, trabajar en equipo y resolver problemas técnicos de forma progresiva.
---
## Líneas futuras
Como posibles mejoras futuras:
- Panel de administración.
- Mejora del diseño responsive.
- Paginación avanzada.
- Actualización automática de datos mediante tareas programadas.
- Sistema de roles de usuario.
- Cacheado avanzado.
---
## Autores
Proyecto realizado por alumnos de 2º de DAW como trabajo intermodular.