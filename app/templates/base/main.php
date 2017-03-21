<?php
$this->insert('base/header', ['title' => (isset($title) ? $this->e($title) : null),
    'styles' => $this->section('styles'),
]);
?>
    <body>

<?php
Users::getCurrentUser();
$this->insert('base/navbar');
?>

    <div class="container">
        <div class="row">
            <div class="col-md-3" id="menu-left">

                <?php

                if (!Users::isLoggedIn()) {
                    $this->insert('base/partials/login_panel');
                } else {
                    $this->insert('base/partials/side_menu');
                }

                ?>

                <div class="list-group">

                    <?php
                    foreach ($messages as $id => $message):
                        $url = insertIf('href="' . $message->url . '" ', !is_null($message->url)); ?>
                        <a <?= $url ?> class="list-group-item"><?= $message->description ?></a>

                    <?php endforeach; ?>

                </div>
            </div>
            <div class="col-md-9">

                <?php

                foreach (Alerts::getAlerts() as $alert): ?>

                    <div class="alert alert-<?= $alert->type ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><?= $alert->title . insertIf(':', isset($alert->title)) ?> </strong><?= $alert->message ?>
                    </div>

                <?php endforeach; ?>

                <?= $this->section('content') ?>

            </div>

        </div>

        <footer class="footer text-muted text-right">
            <a href="https://github.com/Adikso/ObiadyPLOPL" class="text-muted">
                <i class="fa fa-github" aria-hidden="true"></i> Źródło
            </a>
            &copy; 2017 zambrzycki.net
        </footer>

    </div>
<?php
$this->insert('base/footer', ['scripts' => $this->section('scripts')]);