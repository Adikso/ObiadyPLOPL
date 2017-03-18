<?php
$this->layout('base/main', ['title' => 'Dodawanie menu',
    'from' => $from,
    'to' => $to,
    'menu' => $menu]);

$datediff = strtotime($to) - strtotime($from);
$days = floor($datediff / (60 * 60 * 24));

?>

<?php $this->push('scripts') ?>
<script src="/js/pages/menu.js"></script>
<?php $this->end() ?>

<div id="removeAlert" class="alert alert-danger fade in" role="alert" style="display: none;">
    <h4>Czy na pewno chcesz usunąć pozycje menu?</h4>
    <p><b>Usuwasz zamówienie na dzień:</b> <span id="removeDayInfo"></span></p>
    <p>Usunięcie skutkuje <b>TRWAŁYM</b> wycofaniem wszystkich zamówień na <b>POWYŻSZY</b> dzień, zwróceniem pieniędzy i
        usunięciem pozycji</p>
    <form method="POST" action="#">
        <button id="confirm-delete" name="delete" type="submit" class="btn btn-danger">Tak, usuń i wycofaj zamówienia
        </button>
        <button id="confirm-hide" type="button" class="btn btn-default">Anuluj</button>
    </form>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Ustawienia wyświetlania</div>
    <div class="panel-body">
        <form method="POST" action="#" class="form-inline">
            Od: <input name="from" value="<?= $from; ?>" type="text" class="form-control datepicker" title="Od">
            Do: <input name="to" value="<?= $to; ?>" type="text" class="form-control datepicker" title="Do">
            <input class="btn btn-primary" type="submit" value="Wyświetl">
        </form>
    </div>
</div>
<form id="menuEditor" method="POST" action=""><?= csrfField(); ?></form>
<form id="stateChanger" method="POST" action="#">
    <input type="hidden" name="from" value="<?= $from ?>">
    <input type="hidden" name="to" value="<?= $to ?>">
</form>

<?php

for ($i = 0; $i < $days + 1; $i++):
    $time = date('Y-m-d', strtotime('+' . $i . ' day', strtotime($from)));
    $dtime = new DateTime($time);
    $dayname = getTranslatedDayName($dtime->format("l"));

    $existing = [];

    if (array_key_exists($time, $menu)) {
        $existing = $menu[$time];
    }
    ?>

    <div class="order-day">
        <div class="caption-full"><h4><a href="#"><?= $dayname ?></a> <?= date("d.m", strtotime($time)) ?></h4>

            <?php if (empty($existing)): ?>

                <span class="option"><span class="label label-danger">Mięsny</span><input form="menuEditor" type="text"
                                                                                          name="<?= $time ?>#meat"
                                                                                          class="form-control"></span>
                <span class="option"><span class="label label-success">Wega</span><input form="menuEditor" type="text"
                                                                                         name="<?= $time ?>#vege"
                                                                                         class="form-control"></span>

            <?php else:
                foreach ($existing as $dish): ?>
                    <span class="option">
                        <span class="label label-danger" style="background-color: <?= getTypeColor($dish["type"]) ?>;">
                            <?= getTypeName($dish["type"]) ?>
                        </span>
                            <input form="menuEditor" disabled="disabled" type="text"
                                   name="<?= $time ?>#<?= $dish["type"] ?>" value="<?= $dish['description'] ?>"
                                   class="form-control">
                    </span>
                    <?php
                endforeach;
            endif; ?>
            <div class="checkbox">
                <label>
                    <input form="menuEditor" type="checkbox" name="<?= $time ?>#pizza" value="Pizza"> Pizza
                </label>
            </div>

            <?php if (!empty($existing)): ?>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group" role="group">
                        <?php if ((!Rules::canOrder($existing[0]['date']) && !in_array($existing[0]['status'], ["UNLOCKED"])) || in_array($existing[0]['status'], ["LOCKED"])): ?>
                            <button form="stateChanger" value="<?= $existing[0]['date'] ?>" type="submit" name="enable"
                                    class="btn btn-default">
                                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Odblokuj
                            </button>
                        <?php elseif (Rules::canOrder($existing[0]['date']) || in_array($existing[0]['status'], ["UNLOCKED"])): ?>
                            <button form="stateChanger" value="<?= $existing[0]['date'] ?>" type="submit" name="disable"
                                    class="btn btn-default">
                                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Zablokuj
                            </button>
                        <?php endif; ?>

                        <button value="<?= $existing[0]['date'] ?>" type="submit" name="delete"
                                class="btn btn-default delete">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Usuń
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endfor; ?>

<button form="menuEditor" class="form-control btn-primary" type="input" name="add" value="true">Dodaj menu</button>