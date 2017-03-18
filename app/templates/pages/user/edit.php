<?php
$this->layout('base/main', ['title' => 'Edytor użytkowników']);
$csrf = csrfField();
?>

<?php $this->push('scripts') ?>
<script>
    var classesList = document.getElementById('classesList');
    classesList.value = "<?= $user->classId ?>";

    var rolesList = document.getElementById('rolesList');
    rolesList.value = "<?= $user->role ?>";
</script>
<?php $this->end() ?>

<div class="order-day">
    <div class="panel panel-default">
        <div class="panel-heading">Edycja użytkownika</div>
        <div class="panel-body">
            <form method="POST" action="#" id="editform" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Login</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputLogin" name="inputLogin"
                               value="<?= $user->login; ?>" autocomplete="off">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Hasło</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword"
                               placeholder="****" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputEmail" name="inputEmail"
                               value="<?= $user->email; ?>" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Imię</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputFirstname" name="inputFirstname"
                               value="<?= $user->firstname ?>" autocomplete="off">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nazwisko</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputSecondname" name="inputSecondname"
                               value="<?= $user->secondname; ?>" autocomplete="off">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Klasa</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <select name="classesList" id="classesList" class="form-control">
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class->id ?>"><?= $class->getName() ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Uprawnienia</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <select id="rolesList" name="rolesList" class="form-control">
                            <?php
                            foreach (Roles::getConstants() as $key => $value): ?>
                                <option value="<?= $value ?>">
                                    <?= getRoleName($value) ?>
                                </option>
                            <?php endforeach;
                            ?>
                        </select>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputBalance">Stan konta</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputBalance" name="inputBalance"
                               placeholder="Hajsy" value="<?= ($user->balance == null ? 0 : $user->balance); ?>"
                               autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputIcon">Ikonka</label>
                    <div class="col-sm-10 form-group has-feedback">
                        <input type="text" class="form-control" id="inputIcon" name="inputIcon"
                               placeholder="heart" value="<?= ($user->icon == null ? '' : $user->icon); ?>"
                               autocomplete="off">
                    </div>
                </div>
                <?= $csrf; ?>
                <?php
                if (Pages::getCurrentId() === 'profile::add'): ?>
                    <button class="btn btn-primary" style="float: right;" name="add" type="submit">Dodaj użytkownika
                    </button>
                <?php else: ?>
                    <button class="btn btn-default" style="float: right;" name="update" type="submit"
                            value="<?= $user->id; ?>">
                        Aktualizuj użytkownika
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>