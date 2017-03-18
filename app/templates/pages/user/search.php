<?php
$this->layout('base/main', ['title' => 'Wyszukiwanie użytkowników']);

// Maybe i should implement Models caching..
$classesCache = [];
?>

<?php if (isset($results)): ?>

    <div class="panel panel-default">
        <div class="panel-heading">Wyniki wyszukiwania</div>
        <div class="panel-body table-responsive">
            <table class="table">
                <tr>
                    <th>Login</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Klasa</th>
                    <th>Uprawnienia</th>
                    <th>Stan konta</th>
                </tr>
                <?php

                foreach ($results as $found):
                    if (array_key_exists($found->classId, $classesCache)){
                        $class = $classesCache[$found->classId];
                    }else{
                        // Ja tu sobie cache robię, a właściwie to co tu robi logika
                        $class = Models::find(new SchoolClass(), $found->classId);
                        $classesCache[$found->classId] = $class;
                    }

                    ?>

                    <tr>
                        <td>
                            <a href='<?= route('profile', ['id' => $found->id]) ?>'>
                                <?= $found->login ?>
                            </a>
                        </td>
                        <td><?= $found->firstname ?></td>
                        <td><?= $found->secondname ?></td>
                        <td><?= ($class !== false ? $class->getName() : '') ?></td>
                        <td><?= getRoleName($found->role) ?></td>
                        <td><?= $found->balance ?> zł</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">Wyszukiwanie użytkownika</div>
    <div class="panel-body">
        <form method="POST" action="#" id="editform" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Login</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputLogin" name="inputLogin" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Imię</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputFirstname" name="inputFirstname"
                           autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Nazwisko</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputSecondname" name="inputSecondname"
                           autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Klasa</label>
                <div class="col-sm-10">
                    <select name="classesList" id="classesList" class="form-control">
                        <option value="">-</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class->id ?>"><?= $class->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Uprawnienia</label>
                <div class="col-sm-10">
                    <select id="rolesList" name="rolesList" class="form-control">
                        <option></option>
                        <?php
                        foreach (Roles::getConstants() as $key => $value): ?>
                            <option value="<?= $value ?>">
                                <?= getRoleName($value) ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="inputBalance">Stan konta</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputBalance" name="inputBalance" placeholder="Hajsy"
                           autocomplete="off">
                </div>
            </div>
            <?= csrfField() ?>
            <button class="btn btn-default" style="float: right;" name="search" type="submit">Szukaj użytkownika
            </button>
        </form>
    </div>
</div>
</div>