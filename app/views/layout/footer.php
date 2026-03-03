</main>
<footer class="site-footer">
    <div class="container">
        <p>Green Air &copy; <?= date('Y') ?> — Mapeamento colaborativo de árvores urbanas.</p>
    </div>
</footer>
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
<?php if (isset($extraScripts)): foreach ((array)$extraScripts as $s): ?>
<script src="<?= htmlspecialchars($s) ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>
