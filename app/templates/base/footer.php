<div class="device-xs visible-xs"></div>
<div class="device-sm visible-sm"></div>
<div class="device-md visible-md"></div>
<div class="device-lg visible-lg"></div>

<script src="/js/vendor/bootstrap-datepicker.js"></script>
<script src="/js/interface.js"></script>

<?= insertIf($scripts, isset($scripts)) ?>


<?php if (!is_null(user()) && user()->role === Roles::Admin || Debug::isDebugMode()): ?>
    <style><?= Debug::$debugBarRenderer->dumpCssAssets() ?></style>
    <script><?= Debug::$debugBarRenderer->dumpJsAssets() ?></script>
<?php endif; ?>

<?php

if (config('other.google-analytics.enable')) {
    $this->insert('base/partials/googleanalytics');
}

if (!is_null(user()) && user()->role === Roles::Admin || Debug::isDebugMode()){
    echo Debug::$debugBarRenderer->render();
}
?>

</body>

</html>