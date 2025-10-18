# SECLICA - Sistema para Gestión de Citas y Servicios de Motocicletas

_Evidencia: GA8-220501096-AA1-EV01 / EV02 - Integración de módulos SECLICA_  
_Tecnología en Análisis y Desarrollo de Software - ADSO_  
_Ficha: 2627071_  
_Aprendiz: Jhonn Edison Romero Peña_  
_Instructor: Ing. Andrés Rubiano Cucarían_  
_SENA - Centro de Servicios Financieros Regional Distrito Capital_

---

## Descripción del Proyecto

**SECLICA** es una aplicación web orientada a la gestión integral de un taller de motocicletas. Permite registrar y autenticar usuarios (clientes, empleados, administradores), gestionar citas de servicio, administrar facturas, reportes y la comunicación cliente-taller.  
El sistema fue desarrollado como evidencia integradora de competencias para el programa ADSO, con enfoque en arquitectura modular y pruebas híbridas (Web y API RESTful).

---

## Estructura del Proyecto

/
├── public/
│ ├── api/ # Endpoints PHP (REST)
│ └── site/
│ └── frontend/ # HTML, CSS, JS, imágenes
├── src/
│ ├── config/ # Configuración BD y constantes
│ └── helpers/ # Funciones auxiliares PHP
├── sql/ # Script de la base de datos MySQL
├── docs/ # Evidencias, diagramas y capturas
│ └── API_SECLICA.postman_collection.json # Colección Postman de la API
├── composer.json # Dependencias PHP (si aplica)
└── README.md

yaml
Copiar código

---

## Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3 (Bootstrap/Tailwind), JavaScript (Vanilla)
- **Backend:** PHP 7+ (API REST)
- **Base de datos:** MySQL (phpMyAdmin)
- **Servidor local:** XAMPP para Windows
- **Versionamiento:** Git & GitHub

---

## Instalación y Ejecución Local

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/jerp1987/GA8-220501096-AA1-EV02.git
Configurar XAMPP y la base de datos:

Copia la carpeta del proyecto en C:\xampp\htdocs\SECLICA\.

Inicia Apache y MySQL desde XAMPP.

Importa el archivo /sql/secilica_db (3).sql en phpMyAdmin.

Configurar variables de conexión:

Revisa y ajusta las credenciales de conexión a la base de datos en /src/config/conexion.php.

Verifica rutas de endpoints:

Los servicios PHP deben estar accesibles en /public/api/ (usa navegador y/o Postman para probar).

Abrir la aplicación web:

Ingresa a: http://localhost/SECLICA/public/site/frontend/index.html

Pruebas de API (Postman):

Consulta y prueba los endpoints desde la colección Postman adjunta (ver /docs/API_SECLICA.postman_collection.json).

Funcionalidades Principales
Registro y autenticación de usuarios (cliente, empleado, administrador).

Gestión de usuarios: crear, editar, eliminar y filtrar por rol desde el panel administrador.

Agendar y cancelar citas de motocicleta.

Generación y visualización de facturas (con impresión y envío por correo).

Panel administrativo con reportes, filtros avanzados y control de acceso por roles.

Módulo de mensajes de contacto: registro y gestión de mensajes enviados por clientes.

Pruebas funcionales vía Postman (API REST híbrida compatible con front local).

Colección Postman (Pruebas API)
Se incluye la colección API_SECLICA.postman_collection.json para pruebas y validación de la API:

Archivo: /docs/API_SECLICA.postman_collection.json

Cómo usar:

Abre Postman y selecciona “Importar”.

Selecciona el archivo de la colección y cárgalo.

Configura el entorno (localhost, puerto, etc.) según tu servidor local.

Ejecuta las pruebas de los endpoints (registro, login, CRUD, agendamiento, reportes, etc.).

Ventajas:
Esto facilita la validación de todas las funcionalidades del sistema, tanto desde la web como desde la API, y permite replicar todas las pruebas realizadas durante la evidencia.

Resumen de Endpoints (REST API)
Método	Endpoint	Descripción principal
POST	/api/login.php	Login de usuario
POST	/api/logout.php	Cerrar sesión
POST	/api/registrar.php	Registro de usuario
GET/POST	/api/usuarios.php	CRUD usuarios (listar, crear, editar, eliminar)
GET/POST	/api/agendar_cita.php	Agendar cita
POST	/api/cancelar_cita.php	Cancelar cita
GET	/api/citas.php	Listar citas
GET/POST	/api/generar_factura.php	Generar factura
GET	/api/factura.php?id=XX	Visualizar/imprimir factura
GET	/api/reportes.php	Reportes exportables (usuarios, facturas, citas)
POST	/api/contacto.php	Enviar mensaje desde contacto
GET/POST	/api/mensajes_contacto.php	Listar mensajes de contacto
POST	/api/responder_contacto.php	Responder mensaje de contacto

Para los detalles de cada petición (body, parámetros, ejemplos de request y response), consulta la colección de Postman incluida.

Seguridad y Buenas Prácticas
Las rutas administrativas y endpoints sensibles están protegidos por validación de sesión/rol.

El proyecto no incluye archivos de prueba ni logs en producción (test.php, debug.log, etc. deben eliminarse antes de publicar).

Las contraseñas de usuarios se almacenan con hash seguro.

Todas las peticiones API sanitizan los datos y usan consultas preparadas (PDO).

No compartas archivos de configuración con credenciales reales. Usa archivos .env o ajusta permisos en producción.

Documentación y Evidencias
Capturas de pantalla: Módulos web, flujos y pruebas Postman (ver /docs/ o carpeta compartida en la evidencia).

Diagramas:

Clases UML

Entidad-Relación (ER)

Componentes y flujo de usuario

Guía de usuario (instrucciones paso a paso incluidas en el documento Word/PDF de evidencia).

Colección Postman con ejemplos de uso de cada endpoint.

Recursos y Referencias
Repositorio principal en GitHub

Guía de aprendizaje GA8 (SENA SofiaPlus)

SENA Zajuna - SofiaPlus

[Material “Construcción aplicación web” SENA]

Evidencias y documentación adicional: ver /docs/ y archivo Word/PDF anexo

Créditos
Desarrollado por:
Jhonn Edison Romero Peña
Ficha: 2627071
SENA - Centro de Servicios Financieros
Instructor: Ing. Andrés Rubiano Cucarían