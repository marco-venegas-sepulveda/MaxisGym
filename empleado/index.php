<?php
/**
 * Dashboard del empleado (recepción).
 * Accesos a Registrar pagos, Alta de socios, Consultar información y Avisos,
 * y la lista de membresías que vencen en los próximos 7 días.
 */
require __DIR__ . '/../app/bootstrap.php';
require_once ROOT_PATH . '/app/menu.php';
require_once ROOT_PATH . '/app/membresias.php';

$yo = requiere_permiso(...PERMISOS_RECEPCION);

$porVencer = membresias_por_vencer(7);

$titulo = 'Panel de recepción';
require ROOT_PATH . '/app/vistas/panel_inicio.php';
?>

<p class="text-body-secondary mb-4">Hola, <?= e($yo['nombre']) ?>. Esto es lo que tienes a la mano en recepción.</p>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="atajos atajos-2">
            <?php if (tiene_permiso('pagos.registrar')): ?>
                <a class="atajo" href="<?= url('empleado/pagos.php') ?>">
                    <i class="bi bi-cash-coin" aria-hidden="true"></i>
                    <span><strong>Registrar pagos</strong>Cobra una renovación y ve los pagos recientes.</span>
                </a>
            <?php endif; ?>
            <?php if (tiene_permiso('socios.alta')): ?>
                <a class="atajo" href="<?= url('empleado/alta_socio.php') ?>">
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                    <span><strong>Alta de socios</strong>Inscribe a un socio nuevo con su plan y su primer pago.</span>
                </a>
            <?php endif; ?>
            <?php if (tiene_permiso('socios.consultar')): ?>
                <a class="atajo" href="<?= url('empleado/consultar.php') ?>">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <span><strong>Consultar información</strong>Busca socios por nombre o ID y ve su plan activo.</span>
                </a>
            <?php endif; ?>
            <?php if (tiene_permiso('anuncios.publicar')): ?>
                <a class="atajo" href="<?= url('empleado/anuncios.php') ?>">
                    <i class="bi bi-megaphone" aria-hidden="true"></i>
                    <span><strong>Avisos</strong>Publica avisos del sistema para socios o personal.</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-xl-6">
        <h2 class="h4 mb-3">Membresías por vencer <span class="text-body-secondary fw-normal fs-6">próximos 7 días</span></h2>
        <div class="tarjeta-tabla">
            <?php if (!$porVencer): ?>
                <p class="p-3 mb-0 text-body-secondary">Ninguna membresía vence en los próximos 7 días.</p>
            <?php else: ?>
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Socio</th><th>Plan</th><th>Vence</th></tr></thead>
                    <tbody>
                    <?php foreach ($porVencer as $m): ?>
                        <tr>
                            <td><?= e($m['nombre'] . ' ' . $m['apellido']) ?><span class="d-block small text-body-secondary"><?= e($m['telefono']) ?></span></td>
                            <td><?= e($m['nombre_plan']) ?></td>
                            <td>
                                <?= e(fecha($m['fecha_fin'])) ?>
                                <span class="d-block small <?= $m['dias_restantes'] <= 1 ? 'text-danger fw-semibold' : 'text-body-secondary' ?>"><?= e(texto_dias((int) $m['dias_restantes'])) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/app/vistas/panel_fin.php'; ?>
