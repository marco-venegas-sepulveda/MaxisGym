# MaxisGym

Plataforma web de gestión administrativa para gimnasios.
Proyecto final de Programación Web (Laboratorio, grupo 238, FIME – UANL).

## Tecnologías

| Capa | Tecnología |
|---|---|
| Front-end | HTML5, CSS3, JavaScript (fetch/AJAX), Bootstrap 5.3, Bootstrap Icons |
| Back-end | PHP 8 (sin framework) con PDO |
| Base de datos | MySQL 8 |
| Servidor local | Laragon (Apache + MySQL + PHP) |
| Control de versiones | Git + GitHub |

Bootstrap, los íconos y las tipografías están dentro del proyecto (`assets/vendor`),
así que el sistema funciona sin conexión a internet.

## Instalación en Laragon

1. Clona el repositorio dentro de `C:\laragon\www\`:
   ```
   cd C:\laragon\www
   git clone <url-del-repositorio> maxisgym
   ```
2. Inicia Laragon y presiona **Start All** (Apache y MySQL).
3. Crea la base de datos. En la terminal de Laragon:
   ```
   cd C:\laragon\www\maxisgym
   mysql -u root < database\maxisgym.sql
   mysql -u root maxisgym < database\datos_demo.sql
   ```
   El segundo comando es opcional: carga socios, pagos y avisos de ejemplo.
4. Abre `http://localhost/maxisgym/diagnostico.php` y verifica que todo esté en **OK**.
5. Entra a `http://localhost/maxisgym/`.

Si tu MySQL tiene contraseña u otro usuario, copia `config/config.local.example.php`
como `config/config.local.php` y escribe ahí tus datos.

## Usuarios de prueba

Contraseña de todos: `password`

| Rol | Correo |
|---|---|
| Administrador | admin@maxisgym.com |
| Empleado (Recepción) | laura@example.com |
| Socio | juan@example.com |

## Estructura de carpetas

```
maxisgym/
├── app/            Código compartido (conexión, seguridad, funciones de ayuda)
├── assets/         CSS, JavaScript, imágenes y librerías locales
├── config/         Configuración (base de datos, zona horaria, correo)
├── database/       Scripts SQL (estructura y datos de demostración)
└── *.php           Páginas del sitio
```
