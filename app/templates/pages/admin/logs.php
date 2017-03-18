<?php
$this->layout('base/main', ['title' => 'Logi']);
?>

<div class="order-day">
    <?php

    if(!empty($content)): ?>
        <div class="well well-lg"><?= $content ?></div>
    <?php endif; ?>
    <?php if (!empty($parsed)): ?>
    <div class="table-responsive">
        <table class="table">
            <tr><th>Data</th><th>Akcja</th><th>Opis</th></tr>
            <?php foreach($parsed as $log): ?>
                <tr><td><?= $log['date'] ?></td><td><?= $log['type'] ?></td><td><?= $this->e($log['data']) // :) ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="btn-group">
        <form method="GET" action="#" class="form-inline">
            <input type="text" class="form-control datepicker" name="file" value="<?= date('Y-m-d'); ?>">
            <input class="btn btn-primary" type="submit" value="Wyświetl">
        </form><br>
        <table class="table">
            <tr><td><a href="?file=<?= date('Y-m-d'); ?>.log" style="font-weight: bold;">Dzisiaj: <?= date('Y-m-d'); ?>.log</a></td></tr>
            <?php foreach ($files as $value):
                if($value == ".htaccess"){
                    continue;
                }
            ?>

                <tr><td><a href="?file=<?= $value ?>"><?= $value ?></a></td></tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

