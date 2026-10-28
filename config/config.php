<?php
/**
 * Configuración general de MaxisGym.
 *
 * Los valores de abajo funcionan con Laragon recién instalado
 * (MySQL con usuario "root" sin contraseña).
 * Si tu equipo usa otros datos, NO edites este archivo: copia
 * config/config.local.example.php como config/config.local.php
 * y cambia ahí solo lo necesario (ese archivo no se sube a GitHub).
 */

$config = [
    // Nombre que aparece en títulos y correos
    'app_name' => 'MaxisGym',

    // true = muestra errores en pantalla (desarrollo). false = producción.
    'debug' => true,

    // Ruta base del sitio. null = se detecta sola
    // (funciona en http://localhost/maxisgym y en http://maxisgym.test).
    'base_url' => null,

    // Zona horaria del gimnasio
    'timezone' => 'America/Monterrey',

    // Conexión a MySQL
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'database' => 'maxisgym',
        'username' => 'root',
        'password' => '',
    ],

    // Correo saliente.
    // 'archivo' guarda cada correo en storage/correos/ (para pruebas en clase).
    // 'mail' usa la función mail() de PHP (requiere configurar SMTP en Laragon).
    'mail' => [
        'driver'     => 'archivo',
        'from_email' => 'no-responder@maxisgym.com',
        'from_name'  => 'MaxisGym',
    ],
];

// Sobrescribe con la configuración local, si existe
$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

return $config;
