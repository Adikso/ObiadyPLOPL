<?php
$this->layout('base/main', ['title' => 'Lista klas']);
?>

<div class="panel panel-default">
    <div class="panel-heading">Statystyki klas</div>
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item"><b>Aktywni w ciągu ostatniego miesiąca i tygodnia:</b> <?= $activeUsers ?>/<?= $allUsers ?> (<?= intval($activeUsers * 100 / $allUsers) ?>%) i <?= $activeWeek ?>/<?= $allUsers ?> (<?= intval($activeWeek * 100 / $allUsers) ?>%)</li>
            <li class="list-group-item"><b>Aktywowanych użytkowników:</b> <?= $activatedUsers ?>/<?= $allUsers ?> (<?= intval($activatedUsers * 100 / $allUsers) ?>%)</li>
            <li class="list-group-item"><b>Złożonych zamówień:</b> <?= $ordersAmount ?></li>
            <li class="list-group-item"><b>Wydane pieniądze:</b> <?= ($ordersAmount * Payments::getCost()) ?> zł (śr. <?= intval($ordersAmount * Payments::getCost() / $allUsers) ?> zł / os) => (śr. <?= intval($ordersAmount / $allUsers) ?> zamówień / os)</li>
        </ul>
    </div>
</div>

<div class="order-day table-responsive">
    <table class="table">
        <tr>
            <th>Klasa</th>
            <th>Rocznik</th>
            <th>Opcje</th>
            <th>E-mail</th>
        </tr>
        <?php

        foreach ($classes as $class): ?>
            <tr>
                <td>
                    <a href='<?= route('class::manage', ['id' => $class->id]) ?>'><?= $class->getName() ?></a>
                </td>
                <td>
                    <?= $class->year ?>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default user-manage-option" title="Edytuj klasę">
                            <a href="<?= route('class::manage', ['id' => $class->id]) ?>">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                        </button>
                        <button type="button" class="btn btn-default user-manage-option" title="Lista zamówień">
                            <a href="<?= route('class::orders', ['id' => $class->id]) ?>">
                                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                            </a>
                        </button>
                        <button type="button" class="btn btn-default user-manage-option" title="Finanse">
                            <a href="<?= route('class::money', ['id' => $class->id]) ?>">
                                <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                            </a>
                        </button>
                        <button type="button" class="btn btn-default user-manage-option" title="Wyślij wiadomość">
                            <a href="<?= route('class::manage::messenger', ['id' => $class->id]) ?>">
                                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                            </a>
                        </button>
                    </div>
                </td>
                <td>
                    <?= $class->email ?>
                </td>
            </tr>
            <?php
        endforeach;
        ?>
    </table>
</div>