<?php
/**
 * Cerrar sesión.
 * Sólo acepta POST con token CSRF (el botón "Cerrar sesión" es un formulario),
 * para que otro sitio no pueda cerrar tu sesión con un simple enlace.
 */
require __DIR__ . '/app/bootstrap.php';

if (!es_post()) {
    redirigir('index.php');
}

csrf_verificar();
cerrar_sesion();

// La sesión anterior se destruyó; se abre una nueva (con id nuevo) sólo para el mensaje
session_id(session_create_id());
session_start();
flash('success', 'Cerraste sesión correctamente.');
redirigir('login.php');
