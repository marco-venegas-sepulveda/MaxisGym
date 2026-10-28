<?php
/**
 * Encabezado y menú del sitio público.
 * Antes de incluirlo, la página define:
 *   $titulo  — título de la pestaña
 *   $activo  — 'inicio' | 'acerca' | 'planes' | 'contacto' (marca el menú)
 */
$titulo = $titulo ?? config('app_name');
$activo = $activo ?? '';
$menu = [
    'inicio'   => ['Inicio', 'index.php'],
    'acerca'   => ['Acerca de', 'acerca.php'],
    'planes'   => ['Planes', 'planes.php'],
    'contacto' => ['Contacto', 'contacto.php'],
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?></title>
    <link rel="icon" href="<?= asset('img/logo.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('vendor/bootstrap/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="sitio">
<a class="visually-hidden-focusable saltar" href="#contenido">Saltar al contenido</a>

<header class="barra">
    <nav class="navbar navbar-expand-lg navbar-dark" aria-label="Menú principal">
      <div class="container">
        <a class="marca" href="<?= url('index.php') ?>">
            <img src="<?= asset('img/logo.svg') ?>" alt="" width="34" height="34">
            <span>MaxisGym</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <?php foreach ($menu as $clave => [$texto, $ruta]): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $activo === $clave ? ' active' : '' ?>"
                           <?= $activo === $clave ? 'aria-current="page"' : '' ?>
                           href="<?= url($ruta) ?>"><?= e($texto) ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn btn-seguridad btn-sm px-3" href="<?= url('login.php') ?>">Iniciar sesión</a>
                </li>
            </ul>
        </div>
      </div>
    </nav>
</header>

<main id="contenido">
