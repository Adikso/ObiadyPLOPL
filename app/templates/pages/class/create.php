<?php
$this->layout('base/main', ['title' => 'Tworzenie klasy']);
?>

<?php $this->push('scripts') ?>
<script src="/js/pages/create.js"></script>
<?php $this->end() ?>

<div class="order-day">

    <?php if (!$created): ?>

        <div class="panel panel-default">
            <div class="panel-heading">Tworzenie klasy</div>
            <div class="panel-body">
                <form method="POST" action="#" id="editform" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Rok</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputYear" name="inputYear"
                                   value="<?= date('Y') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Klasa</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputClass" name="inputClass" placeholder="A"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail" name="inputEmail"
                                   autocomplete="off">
                            <small class="text-muted">Poproszenie o adres email jest zalecane</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Poziom</label>
                        <div class="col-sm-10">
                            <select id="rolesList" name="inputOwner" class="form-control">
                                <option value="">Nieokreślony</option>
                                <?php foreach ($possible_owners as $owner): ?>
                                    <option value="<?= $owner ?>"><?= ucfirst(strtolower($owner)) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Klasa będzie widoczna tylko dla osób zarządzających danym
                                poziomem
                            </small>
                        </div>

                    </div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Numer</th>
                            <th>Login</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Rola</th>
                        </tr>
                        </thead>
                        <?php for ($i = 0; $i < 26; $i++): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><input type="text" id="inputLogin<?= $i ?>" user-id="<?= $i ?>" class="form-control"
                                           name="inputLogin[val][]"></td>
                                <td><input type="text" id="inputFirstname" user-id="<?= $i ?>" class="form-control"
                                           name="inputFirstname[val][]"></td>
                                <td><input type="text" id="inputSecondname" user-id="<?= $i ?>" class="form-control"
                                           name="inputSecondname[val][]"></td>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <select id="inputRole<?= $i ?>" name="inputRole[val][]"
                                                    class="form-control">
                                                <option value="USER">Użytkownik</option>
                                                <option value="CLASS">Skarbnik</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php endfor; ?>

                    </table>

                    <button type="submit" name="create" class="btn btn-primary form-control">Stwórz</button>
                </form>
            </div>
        </div>

    <?php else: ?>

        <table class="table">
            <thead>
            <tr>
                <th>Login</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Kod</th>
            </tr>
            </thead>
            <?php
            foreach ($users as $token => $user): ?>
                <tr>
                    <td><?= $this->e($user->login) ?></td>
                    <td><?= $this->e($user->firstname) ?></td>
                    <td><?= $this->e($user->secondname) ?></td>
                    <td><?= $token ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
        <button id="print-button" class="form-control">Drukuj</button>

    <?php endif; ?>

</div>