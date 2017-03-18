<?php
$this->layout('base/main', ['title' => 'Zamówienia klasy']);
?>

<?php $this->push('scripts') ?>
<script src="/js/vendor/jquery.bootstrap-touchspin.min.js"></script>
<script src="/js/pages/money.js"></script>
<?php $this->end() ?>

<?php $this->push('styles') ?>
<style src="/css/vendor/jquery.bootstrap-touchspin.css"></style>
<?php $this->end() ?>

<form method="POST" action="">
    <?= csrfField() ?>
    <div class="table-responsive">
        <table class="table">

            <tr>
                <th>No.</th>
                <th style="white-space: nowrap;">Imię i nazwisko</th>
                <th style="white-space: nowrap;">Do zapłaty</th>
                <th>Wpłata</th>
                <th>Stan konta</th>
            </tr>

            <?php

            if (isset($students)):

                foreach ($students as $key => $student):
                    $topay = abs((int)($student->balance / Payments::getCost()));

                    $money = 0;
                    if ($student->balance < 0) {
                        $money = abs($student->balance);
                    } ?>

                    <tr class="<?= insertIf(AlertType::Danger, $student->balance < 0) ?>"><td><?= ($key + 1) ?></td><td style="white-space: nowrap;"><?= $student->getFullName() ?></td><td><?= ($student->balance < 0 ? abs($student->balance) : "0") ?> zł (<?= ($student->balance < 0 ? $topay : "0") ?>)</td><td>
                        <div class="row">
                        <div class="col-lg-4">
                        <input id="<?= $student->id ?>" type="text" placeholder="Ile" class="touchspin-control">
                        </div>

                        <div class="col-lg-4"><input type="text" id="cost#<?= $student->id ?>" name="cost#<?= $student->id ?>" class="form-control" style="width: 100px;" placeholder="Kwota"></div><div class="col-lg-3"><button class="btn btn-default naCzysto" target="<?= $student->id ?>" amount="<?= $money ?>" type="button">Na czysto</button></div>
                        </td><td><?= $student->balance ?> zł</td></tr>

                <?php endforeach;

            else: ?>
                <td>Nie udało się wyświetlić listy klasy</td>
            <?php endif; ?>

        </table>
    </div>
    <input class="btn btn-default form-control" type="submit" name="save" value="Zatwierdź wpłaty">
</form>