<?php
$this->layout('base/main', ['title' => 'Zmiana hasła']);
?>

<div class="order-day" style="text-align: center;">
    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-key" aria-hidden="true"></i> Zmiana hasła</div>
        <div class="panel-body">
            <form action="" method="POST">
                <?= csrfField() ?>
                <input name="old" type="password" class="form-control" placeholder="Stare hasło">
                <input name="new" type="password" class="form-control" placeholder="Nowe hasło">
                <input name="new_confirm" type="password" class="form-control" placeholder="Potwierdź nowe hasło">

                <button type="submit" class="btn btn-primary button-login" name="change" value="true">Zmień hasło</button>
            </form>
        </div>
    </div>
</div>