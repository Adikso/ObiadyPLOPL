<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <?php if (Users::isLoggedIn()): ?>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Nawigacja</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            <?php endif ?>
            <a class="navbar-brand" href="/">
                <?= config('general.siteTitle') ?>
            </a>
        </div>
        <?php

        if (Debug::isDebugMode() && !Device::isMobile()): ?>
            <p class="navbar-text hide-mobile" style="color: #ff0000;">DEBUG</p>
        <?php endif;

        if (!config('general.enabled')): ?>
            <p class="navbar-text hide-mobile" style="color: #ff0000;">Wyłączona</p>
        <?php endif;

        if (Users::isLoggedIn()) {
            $this->insert('base/partials/header_menu');
        }
        ?>
    </div>
</nav>