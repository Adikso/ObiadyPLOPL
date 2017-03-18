<?php
$this->layout('base/main', ['title' => 'Zamówienia klas']);
?>

<div class="order-day">

    <div class="panel panel-default">
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
    <div class="order-day table-responsive">
        <table class="table table-striped">

            <tr style="text-align: center;">
                <th></th>
                <th colspan="2" style="text-align: center;">Poniedziałek</th>
                <th colspan="2" style="text-align: center;">Wtorek</th>
                <th colspan="2" style="text-align: center;">Środa</th>
                <th colspan="2" style="text-align: center;">Czwartek</th>
                <th colspan="2" style="text-align: center;">Piątek</th>
            </tr>
            <tr style="text-align: center;">
                <th></th>
                <th style="text-align: center;">Mięsne</th>
                <th style="text-align: center;">Wega</th>
                <th style="text-align: center;">Mięsne</th>
                <th style="text-align: center;">Wega</th>
                <th style="text-align: center;">Pizza</th>
                <th style="text-align: center;">Wega</th>
                <th style="text-align: center;">Mięsne</th>
                <th style="text-align: center;">Wega</th>
                <th style="text-align: center;">Mięsne</th>
                <th style="text-align: center;">Wega</th>
            </tr>
            <?php
            $total = [];

            foreach ($classesOrders as $key => $class) { ?>

            <tr style='text-align: center;'>
                <td>
                    <a href='<?= route('class::orders', ['id' => $key]) ?>'>
                        <?= $class['class']->getName() ?>
                    </a>
                </td>

                <?php

                if (empty($class['amounts'])): ?>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>

                <?php endif;

                $lastday = 1;
                foreach ($class["amounts"] as $day => $amounts) {
                    $weekday = date("N", strtotime($day));

                    if (isset($total[$day])) {
                        $total[$day]['MEAT'] += ($amounts['MEAT'] + $amounts['PIZZA']);
                        $total[$day]['VEGE'] += $amounts['VEGE'];
                    } else {
                        $total[$day] = ["MEAT" => ($amounts['MEAT'] + $amounts['PIZZA']), "VEGE" => $amounts['VEGE']];
                    }


                    // Dodaje wyzerowane pola na inne dni niż poniedziałek
                    if (date("N", strtotime($day)) >= $lastday + 1) {
                        $n = $weekday - $lastday;

                        for ($i = 0; $i < $n; $i++) { ?>
                            <td>0</td>
                            <td>0</td>
                            <?php $lastday++;
                        }
                    }

                    $lastday++;

                    // Mięsny i pizza sumują się jako mięsne, aby zaoszczędzić miejsce
                    $amount_meat = ((isset($amounts['MEAT']) ? $amounts['MEAT'] : 0) + (isset($amounts['PIZZA']) ? $amounts['PIZZA'] : 0));
                    $amount_vege = (isset($amounts['VEGE']) ? $amounts['VEGE'] : 0);

                    if ($amounts['PIZZA'] > 0) {
                        $pizza_details = '
                        <div class="btn-group">
                          <button type="button" class="btn btn-default dropdown-toggle pizza-details-sum" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ' . $amount_meat . ' <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">';

                        foreach ($class['pizza'] as $pizza) {
                            $pizza_details .= '<li><a href="#">' . $pizza['amount'] . 'x - ' . $pizza['ingredients'] . '</a></li>';
                        }

                        $pizza_details .= '</ul>
                      </div>';
                    }

                    $meatColor = ($weekday == $amounts['PIZZA'] > 0 ? getTypeColor("PIZZA") : getTypeColor("MEAT"));
                    $meatAmount = ($amounts['PIZZA'] > 0 ? $pizza_details : $amount_meat);
                    ?>

                    <td style="background-color: <?= $meatColor ?>;">
                        <?= $meatAmount ?>
                    </td>
                    <td style="background-color: <?= getTypeColor("VEGE") ?>;">
                        <?= $amount_vege ?>
                    </td>

                    <?php
                }

                // Dodaje wyzerowane pola jeżeli nie zakończyło się w piątek
                $n = 5 - $lastday;

                if ($lastday > 1 && $n >= 0) {
                    for ($i = 0; $i <= $n; $i++): ?>
                        <td>0</td>
                        <td>0</td>
                    <?php endfor;
                }

                echo '</tr>';
                }

                ksort($total);

                ?>
            <tr style='text-align: center; background-color: #ccc;'>
                <td>Łącznie</td> <?php
                reset($total);
                $first_day = date("N", strtotime(key($total)));

                $lastday = 1;
                foreach ($total as $key => $amounts) {
                    $n = date("N", strtotime($key));

                    $diff = $n - $lastday - 1;

                    if ($lastday == 1 && $n == 2 && $first_day != 1): ?>
                        <td>0</td>
                        <td>0</td>
                    <?php endif;

                    if ($diff > 0) {
                        for ($i = 0; $i <= $diff; $i++): ?>
                            <td>0</td>
                            <td>0</td>
                        <?php endfor;
                    }

                    ?>
                    <td><?= $amounts['MEAT'] ?></td>
                    <td><?= $amounts['VEGE'] ?></td>

                    <?php

                    $lastday = $n;
                }

                $n = 5 - $lastday;

                if ($n >= 0) {
                    if ($n == 4): ?>
                        <td>0</td>
                        <td>0</td>
                    <?php endif;
                    for ($i = 0; $i < $n; $i++): ?>
                        <td>0</td>
                        <td>0</td>
                    <?php endfor;
                }

                ?>
        </table>
        <a href="<?= route('classes::orders::export', ['from' => $from]) ?>">Pobierz raport</a><br><a
                href="<?= route('classes::orders::export::pizza', ['from' => $from]) ?>">Pobierz raport (tylko
            pizza)</a>
    </div>