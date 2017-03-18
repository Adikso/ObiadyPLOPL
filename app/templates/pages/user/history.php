<?php
$this->layout('base/main', ['title' => 'Historia zamówień']);
?>

<div class="order-day">

    <div class="panel panel-default">
        <div class="panel-heading">Ustawienia wyświetlania</div>
        <div class="panel-body">
            <form method="POST" action="#" class="form-inline">

                Od: <input type="text" class="form-control datepicker" name="from" value="<?= $from ?>">
                Do: <input type="text" class="form-control datepicker" name="to" value="<?= $to ?>">
                <input class="btn btn-primary" type="submit" value="Wyświetl">
                <br><br>
                <div class="checkbox">
                    <label>
                        <input type="hidden" name="onlyordered" value="off">
                        <input type="checkbox" name="onlyordered" <?= insertIf('checked', $onlyordered); ?>>
                        Wyświetl tylko zamówione
                    </label>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-hover">
        <tr>
            <th nowrap="nowrap">Data</th>
            <th>Danie</th>
        </tr>
        <?php

        $lastday = date(0);

        foreach ($orders as $order):
            if ($order['type'] == "PIZZA"): ?>

                <tr>
                    <td><?= insertIf($order['date'], $order['date'] != $lastday) ?></td>
                    <td>
                        <span class="label" style="background-color: <?= getTypeColor($order['type']) ?>"><?= getTypeName($order['type']) ?></span>
                        <?= $order['description'] ?>
                        <?php if (isset($order['ingredients'])): ?>
                                (<?= $order['ingredients'] ?>)
                        <?php endif; ?>
                    </td>
                </tr>

            <?php else: ?>

                <tr>
                    <td><?= insertIf($order['date'], $order['date'] != $lastday) ?></td>
                    <td>
                        <span class="label" style="background-color: <?= getTypeColor($order['type']) ?>"><?= getTypeName($order['type']) ?></span>
                        <?= $order['description'] ?>
                    </td>
                </tr>

            <?php endif;

            $lastday = $order['date'];

        endforeach;
        ?>

    </table>
    <div class="panel panel-default">
        <div class="panel-body">
            Ilość zamówień: <?= $amount ?><br>
            Wydano: <?= ($amount * Payments::getCost()) ?>zł<br>
        </div>
    </div>

</div>