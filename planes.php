<?php
/**
 * Pantalla de Planes y Precios (información dinámica desde la base de datos).
 * Cada plan se muestra como disco olímpico con sus beneficios, y abajo una
 * tabla comparativa armada con las tablas planes, beneficios y plan_beneficio.
 */
require __DIR__ . '/app/bootstrap.php';
require ROOT_PATH . '/app/planes.php';

$planes = planes_activos();
$beneficios = beneficios_de_planes_activos();

// Para la tabla: ¿el plan X incluye el beneficio Y?
$incluye = [];
foreach ($planes as $plan) {
    foreach ($plan['beneficios'] as $b) {
        $incluye[$plan['id']][$b['id']] = true;
    }
}

$titulo = 'Planes y precios · MaxisGym';
$activo = 'planes';
require ROOT_PATH . '/app/vistas/publico_inicio.php';
?>

<section class="encabezado-pagina">
    <div class="container">
        <h1>Nuestros planes</h1>
        <p>Elige el que va con tu ritmo. Te inscribes en recepción y desde ese día tu panel muestra tu membresía.</p>
    </div>
</section>

<section class="seccion">
    <div class="container">
        <?php if (!$planes): ?>
            <p class="seccion-intro">Por el momento no hay planes publicados. Pregunta en recepción.</p>
        <?php else: ?>
            <div class="row gy-5 justify-content-center">
                <?php foreach ($planes as $plan): ?>
                    <article class="col-sm-6 col-lg-<?= count($planes) >= 4 ? 3 : 4 ?> plan">
                        <?= disco_html($plan) ?>
                        <div class="plan-detalle">
                            <h2 class="h3 mb-2"><?= e($plan['nombre_plan']) ?></h2>
                            <?php if ($plan['descripcion']): ?>
                                <p><?= e($plan['descripcion']) ?></p>
                            <?php endif; ?>
                            <?php if ($plan['beneficios']): ?>
                                <ul class="plan-beneficios">
                                    <?php foreach ($plan['beneficios'] as $b): ?>
                                        <li><i class="bi bi-check-lg" aria-hidden="true"></i><?= e($b['descripcion']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($planes && $beneficios): ?>
<section class="seccion pt-0">
    <div class="container">
        <h2>Comparación de planes</h2>
        <p class="seccion-intro mb-4">Precio, duración y lo que incluye cada plan.</p>
        <div class="table-responsive">
            <table class="table tabla-comparativa mb-0">
                <thead>
                    <tr>
                        <th scope="col">Incluye</th>
                        <?php foreach ($planes as $plan): ?>
                            <th scope="col" class="text-center"><?= e($plan['nombre_plan']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Precio</th>
                        <?php foreach ($planes as $plan): ?>
                            <td class="text-center fw-semibold">
                                <span class="marca-plan disco-<?= e($plan['color']) ?>" aria-hidden="true"></span><?= e(dinero($plan['precio'])) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th scope="row">Duración</th>
                        <?php foreach ($planes as $plan): ?>
                            <td class="text-center"><?= e(duracion_texto((int) $plan['duracion_dias'])) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($beneficios as $b): ?>
                        <tr>
                            <th scope="row" class="fw-normal"><?= e($b['descripcion']) ?></th>
                            <?php foreach ($planes as $plan): ?>
                                <?php if (!empty($incluye[$plan['id']][$b['id']])): ?>
                                    <td class="text-center si"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span class="visually-hidden">Sí</span></td>
                                <?php else: ?>
                                    <td class="text-center no"><i class="bi bi-dash-lg" aria-hidden="true"></i><span class="visually-hidden">No</span></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require ROOT_PATH . '/app/vistas/publico_fin.php'; ?>
