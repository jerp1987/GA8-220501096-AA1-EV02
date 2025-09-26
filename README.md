# SECLICA - Sistema para Gestión de Citas y Servicios de Motocicletas

_Evidencia: GA8-220501096-AA1-EV01 - Integración de módulos SECLICA_  
_Tecnología en Análisis y Desarrollo de Software - ADSO_  
_Ficha: 2627071_  
_Aprendiz: Jhonn Edison Romero Peña_  
_Instructor: Ing. Andrés Rubiano Cucarían_  
_SENA - Centro de Servicios Financieros Regional Distrito Capital_

---

## Descripción del Proyecto

**SECLICA** es una aplicación web desarrollada como parte de la evidencia de integración de módulos, que permite la gestión de usuarios, clientes, empleados, citas, facturas y servicios de un taller de motocicletas.  
Incluye formularios para el registro y autenticación, panel administrativo, asignación/cancelación de citas y generación de reportes.

---

## Estructura del Proyecto

/
├── public/
│ ├── api/ # Endpoints PHP (REST)
│ └── site/
│ ├── frontend/ # Archivos HTML, CSS, JS, imágenes
│ └── .env # (opcional) configuración local
├── src/
│ ├── config/ # Conexión BD
│ └── helpers/ # Funciones auxiliares
├── sql/ # Script de la base de datos MySQL
├── composer.json # Dependencias PHP
└── README.md


---

## Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3 (Bootstrap), JavaScript (Vanilla)
- **Backend:** PHP 7+ (API REST)
- **Base de datos:** MySQL (phpMyAdmin)
- **Servidor local:** XAMPP para Windows
- **Versionamiento:** Git & GitHub

---

## Instalación y Ejecución Local

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/jerp1987/GA8-220501096-AA1-EV01.git


Configurar XAMPP y la base de datos:

Copia la carpeta del proyecto en C:\xampp\htdocs\.

Inicia Apache y MySQL desde XAMPP.

Importa el archivo SQL de /sql/secilica_db (3).sql en phpMyAdmin.

Configura los endpoints:

Asegúrate que las rutas /public/api/ sean accesibles desde tu navegador y Postman.

Abre la aplicación:

Ingresa a http://localhost/SECLICA/public/site/frontend/index.html en tu navegador.

Prueba el registro, login y gestión de citas según el rol de usuario.

Funcionalidades Principales

Autenticación de usuarios (cliente, empleado, administrador)

Gestión de usuarios: crear, editar, eliminar, filtrar por rol

Agendar y cancelar citas para motocicletas

Generación y visualización de facturas

Panel de administración con reportes y filtros

Módulo de mensajes de contacto

Pruebas funcionales vía Postman (API REST)

Capturas y Diagramas

Diagrama de clases UML

Diagrama entidad-relación (ER)

Diagrama de componentes

Flujos de usuario (diagramas de flujo)

Capturas de pantalla de los módulos

(Ver carpeta /docs/ o la sección correspondiente en la evidencia PDF)

Créditos y Licencia

Desarrollado por Jhonn Edison Romero Peña
Ficha 2627071
SENA - Centro de Servicios Financieros
Instructor: Ing. Andrés Rubiano Cucarían