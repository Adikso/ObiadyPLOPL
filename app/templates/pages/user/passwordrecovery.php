<?php
$this->layout('base/main', ['title' => 'Odzyskiwanie hasła']);
?>

<?php if ($nextStep): ?>
    <div class="order-day" style="text-align: center;">
        <div class="panel panel-default">
            <div class="panel-heading">Zmiana hasła</div>
            <div class="panel-body">
                <form action="<?= route('user::password::recovery::key', ['key' => $key]) ?>" method="POST">
                    <?= csrfField() ?>

                    <input name="password" type="password" class="form-control" placeholder="Nowe hasło"><br>
                    <button type="submit" class="btn btn-primary button-login" name="change" value="true">Zmień hasło
                    </button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="order-day" style="text-align: center;">
        <div class="panel panel-default">
            <div class="panel-heading">Przypomnienie hasła</div>
            <div class="panel-body">
                <form action="" method="POST">
                    <?= csrfField() ?>

                    <input name="username" type="text" class="form-control"
                           placeholder="Nazwa użytkownika lub email"><br>
                    <button type="submit" class="btn btn-primary button-login" name="recover" value="true">Wyślij
                        przypomnienie
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>