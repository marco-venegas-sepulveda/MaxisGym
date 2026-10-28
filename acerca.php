<?php
/**
 * Pantalla Acerca de: historia, misión, valores e instalaciones.
 * Las fotos se leen de assets/img/instalaciones/. Si una foto no existe,
 * se muestra un recuadro con ícono para que la página no se vea rota.
 */
require __DIR__ . '/app/bootstrap.php';

$instalaciones = [
    ['archivo' => 'pesas.jpg',  'nombre' => 'Área de pesas',     'icono' => 'bi-trophy'],
    ['archivo' => 'cardio.jpg', 'nombre' => 'Zona de cardio',    'icono' => 'bi-heart-pulse'],
    ['archivo' => 'clases.jpg', 'nombre' => 'Salón de clases',   'icono' => 'bi-people'],
];

$titulo = 'Acerca de · MaxisGym';
$activo = 'acerca';
require ROOT_PATH . '/app/vistas/publico_inicio.php';
?>

<section class="encabezado-pagina">
    <div class="container">
        <h1>Acerca de MaxisGym</h1>
        <p>Un gimnasio de barrio con equipo completo y un sistema que te deja ver tu progreso administrativo sin hacer fila.</p>
    </div>
</section>

<section class="seccion">
    <div class="container">
        <div class="row gy-5 gx-lg-5">
            <div class="col-lg-7 bloque-texto">
                <h2>Nuestra historia</h2>
                <p>
                    MaxisGym abrió como un pequeño cuarto de pesas cerca de Ciudad Universitaria.
                    Con los años crecimos a un gimnasio completo, pero el control de socios seguía
                    en libretas: pagos que se perdían y membresías que vencían sin que nadie se diera cuenta.
                </p>
                <p>
                    Por eso creamos esta plataforma. Hoy cada socio tiene su panel, el personal
                    registra pagos en segundos y la administración ve los ingresos del mes al momento.
                </p>

                <h2 class="mt-5">Misión</h2>
                <p>
                    Que cualquier persona pueda entrenar con constancia, con un espacio seguro,
                    personal que acompaña y procesos claros desde la inscripción.
                </p>
            </div>
            <div class="col-lg-5">
                <h2 class="h1">Valores</h2>
                <ul class="valores">
                    <li><strong>Constancia</strong>Celebramos venir seguido más que levantar mucho.</li>
                    <li><strong>Respeto</strong>Todos empezamos en algún punto; aquí nadie juzga.</li>
                    <li><strong>Transparencia</strong>Ves lo que pagas, cuándo vence y qué incluye tu plan.</li>
                    <li><strong>Seguridad</strong>Equipo en buen estado y orientación en cada ejercicio.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="seccion pt-0">
    <div class="container">
        <h2>Nuestras instalaciones</h2>
        <div class="row gy-4 mt-1">
            <?php foreach ($instalaciones as $lugar): ?>
                <?php $foto = 'img/instalaciones/' . $lugar['archivo']; ?>
                <div class="col-md-4">
                    <figure class="instalacion mb-0">
                        <?php if (is_file(ROOT_PATH . '/assets/' . $foto)): ?>
                            <img src="<?= asset($foto) ?>" alt="<?= e($lugar['nombre']) ?>">
                        <?php else: ?>
                            <div class="instalacion-sin-foto"><i class="bi <?= e($lugar['icono']) ?>" aria-hidden="true"></i></div>
                        <?php endif; ?>
                        <figcaption><?= e($lugar['nombre']) ?></figcaption>
                    </figure>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require ROOT_PATH . '/app/vistas/publico_fin.php'; ?>
