<?php
/** Cierre del layout de panel. $scripts (opcional): lista de archivos JS de assets/js a incluir. */
$scripts = $scripts ?? [];
?>
        </main>
    </div>
</div>

<script src="<?= asset('vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
<?php foreach ($scripts as $js): ?>
    <script src="<?= asset('js/' . $js) ?>"></script>
<?php endforeach; ?>
</body>
</html>
