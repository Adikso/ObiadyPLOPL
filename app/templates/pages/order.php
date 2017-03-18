<?php
$this->layout('base/main', ['title' => 'Zamów']);

$rule = Rules::getRule('orders');
?>

<form action="#" method="POST">
    <?= csrfField() ?>

    <?php foreach ($menu as $date => $dishes):
        $dtime = new DateTime($date);
        $dayname = getTranslatedDayName($dtime->format("l"));
        $firstDish = $dishes[0];

        $canOrder = Rules::canOrder($date);
        $isAvailable = $firstDish['status'] !== 'LOCKED' || $firstDish['status'] === 'UNLOCKED';
        ?>

        <div class="order-day" id="<?= $date ?>">
            <div class="caption-full">
                <h4>
                    <a href="#<?= $date ?>">
                        <?= $dayname ?>
                    </a> <?= date("d.m", strtotime($date)) ?>


                    <?php if ($canOrder && $isAvailable && !is_null($rule)):
                        $closeDay = Rules::findCloseDay(date('l', strtotime($date)), $rule);

                        if ($closeDay !== null):
                            $closeTime = $rule[$closeDay]['time']; ?>

                            <span class="text-muted text-right"
                                  style="float: right; vertical-align:middle; font-size: small; line-height: 19px">
                                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                    <?= getTranslatedDayName($closeDay) ?> <?= date('h:i', strtotime($closeTime)) ?>
                            </span>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if ($firstDish['status'] === 'UNLOCKED' && !$canOrder): ?>
                        <span class="text-muted text-right"
                              style="float: right; vertical-align:middle; font-size: small; line-height: 19px">
                                    <i class="fa fa-unlock" aria-hidden="true"></i> Odblokowane
                            </span>
                    <?php endif; ?>

                    <?php if ($firstDish['status'] === 'LOCKED' && $canOrder): ?>
                        <span class="text-muted text-right"
                              style="float: right; vertical-align:middle; font-size: small; line-height: 19px">
                                    <i class="fa fa-lock" aria-hidden="true"></i> Zablokowane
                            </span>
                    <?php endif; ?>

                </h4>

                <?php foreach ($dishes as $dish):
                    $bg_color = ($dish['userId'] != null ? "style='background-color: #E8E8E8 ;'" : "");
                    $type_color = getTypeColor($dish['type']);
                    $type_name = getTypeName($dish['type']);
                    $description = (isset($dish['pizza']) ? $dish['description'] . sprintf(' (%s)', $dish['ingredients']) : $dish['description']);
                    $selected_badge = ($dish['userId'] != null ? "<span class='label label-default' style='float: right;'>wybrane</span>" : "");

                    ?>

                    <span class="option" <?= $bg_color ?>>
                    <span class="label" style="background-color: <?= $type_color ?>;"><?= $type_name ?></span>
                        <?= $description ?> <?= $selected_badge ?>
                </span>

                <?php endforeach; ?>

                <?php
                $printButtons = true;
                $ordered = find($dishes, "userId", user()->id);
                ?>

                <?php if ((!$isAvailable || !$canOrder) && $firstDish['status'] !== 'UNLOCKED'): ?>
                    <button type="submit" disabled="disabled" class="btn btn-default width-full">
                        Zamowienia zablokowane
                    </button>

                    <?php $printButtons = false; ?>

                <?php elseif ($ordered): ?>
                    <button type="submit" name="cancel" value="<?= $date ?>" style="margin-top: 3px;"
                            class="btn btn-default width-full">
                        Już zamówione. Naciśnij aby anulować.
                    </button>

                    <?php $printButtons = false; ?>

                <?php endif; ?>




                <?php if (find($dishes, "type", "PIZZA") && !$ordered && $canOrder && $isAvailable):
                    $all_ingredients = config('orders.pizza.ingredients');
                    sort($all_ingredients);
                    ?>

                    <?php if (array_key_exists($date, $pizzas)):
                    foreach ($pizzas[$date] as $pizza): ?>

                        <span class="option">
                    <input type="radio" name="pizza" value="<?= $pizza['id'] ?>"> <?= $pizza['ingredients'] ?>
                            z <?= $pizza['firstname'] . ' ' . $pizza['secondname'] ?>
                </span>

                    <?php endforeach; endif; ?>

                    <div class="row">

                        <?php for ($i = 1; $i <= config('orders.pizza.ingredients_amount'); $i++): ?>
                            <div class="col-xs-6">
                                <select name="dishes[<?= $dish['date'] ?>][ingredients][]"
                                        class="form-control ingredient-selector">
                                    <option value="">Brak</option>

                                    <?php foreach ($all_ingredients as $ing): ?>

                                        <option><?= $ing ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endfor; ?>

                    </div>
                    <div class="alert alert-warning warning-pizza" role="alert">Pamiętaj zaznaczyć, że chcesz zamówić
                        pizzę.
                    </div>
                <?php endif; ?>





                <?php if ($printButtons): ?>

                    <div class="btn-group select-buttons-group" data-toggle="buttons" style="margin-top: 10px;">
                        <label class="btn btn-primary active select-buttons" style="border-bottom: solid 4px #000;">
                            <input type="radio" id="none" autocomplete="off" checked> Brak
                        </label>

                        <?php foreach ($dishes as $dish): ?>
                            <label class="btn btn-primary select-buttons"
                                   style="border-bottom: solid 4px <?= getTypeColor($dish['type']) ?>">
                                <input type="radio" name="dishes[<?= $dish['date'] ?>][id]" value="<?= $dish['id'] ?>"
                                       autocomplete="off"> <?= getTypeName($dish['type']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>




                <?php if (find($dishes, "type", "PIZZA") &&
                    !(find($dishes, "status", "LOCKED")
                        || $ordered
                        || find($dishes, "status", "UNLOCKED"))
                    && $canOrder && $isAvailable): ?>

                    <a style="float: right;" id="resetselection">[Reset wyboru]</a>
                <?php endif; ?>

            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($menu)): ?>
    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 30px;" name="order">
        Zamów
    </button>
    <?php endif; ?>
</form>