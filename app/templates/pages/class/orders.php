<?php
$this->layout('base/main', ['title' => 'Zamówienia klasy']);
?>

<?php $this->push('scripts') ?>
    <script src="/js/pages/orders.js"></script>
<?php $this->end() ?>

<div class="panel panel-default" id="display-settings">
    <div class="panel-heading">Ustawienia wyświetlania</div>
    <div class="panel-body">
        <form method="POST" action="#" class="form-inline">

            Od:
            <div class="input-group">
                <input type="text" class="form-control datepicker" name="from" value="<?= $this->e($from) ?>">
                <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" value="Wyświetl">
                </span>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" id="export-options">
    <div class="panel-body">
        Wyświetl jako:
        <a id="print-button">Drukuj/PDF</a>,

        <?php if (Pages::getCurrentId() === 'class::orders'): ?>
            <a href="<?= route('class::orders::format', ['id' => $classId, 'format' => 'csv', 'from' => $from]) ?>">CSV</a>
        <?php elseif (Pages::getCurrentId() === 'user::class::orders'): ?>
            <a href="<?= route('user::class::orders::format', ['format' => 'csv', 'from' => $from]) ?>">CSV</a>
        <?php endif; ?>
    </div>
</div>

<?php foreach ($orders as $date => $day) {
    $dtime = new DateTime($date);
    $dayname = getTranslatedDayName($dtime->format("l"));

    ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= $dayname ?> (<?= $date ?>)
            <span style="float: right;"><?= sizeof($day) ?> zamówień</span>
        </div>

        <div class="panel-body">
            <table class="table">
                <tr>
                    <th style="width: 150px;">Zamawiający</th>
                    <th>Danie</th>
                    <th style="text-align: right;">Typ</th>
                </tr>

                <?php

                foreach ($day as $order):
                    if ($order['type'] == "PIZZA"):

                        if (user()->role === Roles::Admin): ?>
                            <tr>
                                <td>
                                    <a href="<?= route('profile', ['id' => $order['id']]) ?>">
                                        <?= $order['firstname'] ?> <?= $order['secondname'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $order['description'] ?> (<?= $order['ingredients'] ?>)
                                </td>
                                <td style="text-align: right;">
                        <span style="color: <?= getTypeColor($order['type']) ?>;">
                            <?= getTypeName($order['type']) ?>
                        </span>
                                </td>
                            </tr>

                        <?php else: ?>
                            <tr>
                                <td>
                                    <?= $order['firstname'] ?> <?= $order['secondname'] ?>
                                </td>
                                <td>
                                    <?= $order['description'] ?> (<?= $order['ingredients'] ?>)
                                </td>
                                <td style="text-align: right;">
                        <span style="color: <?= getTypeColor($order['type']) ?>;">
                            <?= getTypeName($order['type']) ?>
                        </span>
                                </td>
                            </tr>

                            <?php
                        endif;

                    else:

                        if (user()->role === Roles::Admin): ?>
                            <tr>
                                <td>
                                    <a href="<?= route('profile', ['id' => $order['id']]) ?>">
                                        <?= $order['firstname'] ?> <?= $order['secondname'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $order['description'] ?>
                                </td>
                                <td style="text-align: right;">
                                    <span style="color: <?= getTypeColor($order['type']) ?>;"><?= getTypeName($order['type']) ?></span>
                                </td>
                            </tr>

                            <?php
                        else: ?>
                            <tr>
                                <td>
                                    <?= $order['firstname'] ?> <?= $order['secondname'] ?>
                                </td>
                                <td>
                                    <?= $order['description'] ?>
                                </td>
                                <td style="text-align: right;">
                                    <span style="color: <?= getTypeColor($order['type']) ?>;"><?= getTypeName($order['type']) ?></span>
                                </td>
                            </tr>

                            <?php
                        endif;

                    endif;
                endforeach; ?>

            </table>
        </div>
    </div>

    <?php
}

?>