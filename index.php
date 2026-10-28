<?php
/**
 * Pantalla de Inicio.
 * Muestra la portada con los planes (leídos de la base de datos),
 * las ventajas del gimnasio y accesos a Planes y Contacto.
 */
require __DIR__ . '/app/bootstrap.php';
require ROOT_PATH . '/app/planes.php';

$planes = planes_activos();

$titulo = 'MaxisGym · Gimnasio';
$activo = 'inicio';
require ROOT_PATH . '/app/vistas/publico_inicio.php';
?>

<section class="portada">
    <div class="container portada-contenido">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <h1>Entrena fuerte. Nosotros llevamos la cuenta.</h1>
                <p class="portada-texto">
                    Pesas, cardio y clases grupales. Al inscribirte tienes tu propio panel
                    para ver tu membresía, tus pagos y los avisos del gimnasio.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="btn btn-seguridad btn-lg" href="<?= url('planes.php') ?>">Ver planes</a>
                    <a class="btn btn-contorno-claro btn-lg" href="<?= url('contacto.php') ?>">Escríbenos</a>
                </div>
            </div>
            <div class="col-lg-6">
                <?php if ($planes): ?>
                    <div class="rack">
                        <?php foreach (array_slice($planes, 0, 4) as $plan): ?>
                            <?= disco_html($plan, url('planes.php')) ?>
                        <?php endforeach; ?>
                    </div>
                    <p class="rack-nota">Entre más pesado el disco, más incluye el plan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="seccion">
    <div class="container">
        <h2>¿Por qué elegirnos?</h2>
        <p class="seccion-intro mb-5">Lo que encuentras en MaxisGym desde el primer día.</p>
        <div class="row gy-4 gx-lg-5">
            <div class="col-md-4">
                <div class="ventaja">
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    <h3>Horario amplio</h3>
                    <p>Abrimos de 6:00 a 22:00 entre semana y también sábados y domingos.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ventaja">
                    <i class="bi bi-people" aria-hidden="true"></i>
                    <h3>Entrenadores en piso</h3>
                    <p>Siempre hay alguien para corregir tu técnica o armarte una rutina.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ventaja">
                    <i class="bi bi-phone" aria-hidden="true"></i>
                    <h3>Tu membresía en línea</h3>
                    <p>Consulta cuándo vence tu plan y tu historial de pagos desde tu panel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="seccion seccion-oscura">
    <div class="container d-lg-flex align-items-end justify-content-between gap-5">
        <p class="frase">El mejor momento para empezar fue ayer. El segundo mejor es hoy.</p>
        <div class="mt-4 mt-lg-0 flex-shrink-0">
            <a class="btn btn-seguridad btn-lg" href="<?= url('planes.php') ?>">Conoce nuestros planes</a>
        </div>
    </div>
</section>

<?php require ROOT_PATH . '/app/vistas/publico_fin.php'; ?>
