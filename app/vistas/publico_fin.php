<?php
/** Pie de página del sitio público con la información de contacto. */
$g = config('gimnasio');
?>
</main>

<footer class="pie">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <a class="marca mb-3" href="<?= url('index.php') ?>">
                    <img src="<?= asset('img/logo.svg') ?>" alt="" width="30" height="30">
                    <span>MaxisGym</span>
                </a>
                <p class="pie-texto">Pesas, cardio y clases grupales, con tu membresía y tus pagos siempre a la mano.</p>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h2 class="pie-titulo">Visítanos</h2>
                <ul class="pie-lista">
                    <li><i class="bi bi-geo-alt" aria-hidden="true"></i><?= e($g['direccion']) ?></li>
                    <?php foreach ($g['horario'] as $linea): ?>
                        <li><i class="bi bi-clock" aria-hidden="true"></i><?= e($linea) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h2 class="pie-titulo">Contacto</h2>
                <ul class="pie-lista">
                    <li><i class="bi bi-telephone" aria-hidden="true"></i>
                        <a href="tel:<?= e(preg_replace('/\s+/', '', $g['telefono'])) ?>"><?= e($g['telefono']) ?></a></li>
                    <li><i class="bi bi-envelope" aria-hidden="true"></i>
                        <a href="mailto:<?= e($g['email']) ?>"><?= e($g['email']) ?></a></li>
                    <li><i class="bi bi-chat-left-text" aria-hidden="true"></i>
                        <a href="<?= url('contacto.php') ?>">Envíanos un mensaje</a></li>
                </ul>
            </div>
        </div>
        <p class="pie-legal">© <?= date('Y') ?> MaxisGym. Proyecto académico, FIME – UANL.</p>
    </div>
</footer>

<script src="<?= asset('vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
