<?php
$user = $profile['user'];
$this->layout('base/main', ['title' => 'Profil użytkownika '.$user->getFullName()]);
?>

<?php $this->push('scripts') ?>
<script src="/js/pages/settings.js"></script>
<?php $this->end() ?>

<div class="panel panel-default">
    <div class="panel-heading">Profil użytkownika: <?= $user->getFullName() ?></div>
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item"><b>Login:</b> <?= $user->login; ?></li>
            <li class="list-group-item"><b>Imię i nazwisko:</b> <?= $user->getFullName() ?></li>
            <li class="list-group-item">
                <b>Klasa:</b> <?= (($class = $user->getClass()) !== false ? $class->getName() : '') ?></li>
            <li class="list-group-item"><b>Rola:</b> <?= getRoleName($user->role); ?></li>
            <li class="list-group-item"><b>Stan konta:</b> <?= $user->balance; ?> zł</li>
            <li class="list-group-item"><b>E-mail:</b> <?= $user->email; ?></li>
            <li class="list-group-item"><b>Ostatnio zalogowany: </b> <?= $user->lastlogin; ?></li>
            <?php if (!is_null($user->icon)): ?>
                <li class="list-group-item"><b>Ikonka: </b> <i class="fa fa-<?= $user->icon; ?>"
                                                               title="<?= $user->icon; ?>"></i></li>
            <?php endif; ?>
        </ul>

        <form method="POST" action="#" class="form-inline">
            <div class="btn-group" role="group">

                <a class="btn btn-default" href="<?= route('user::manage::messenger', ['id' => $user->id]) ?>">Wyślij
                    wiadomość</a>
                <a class="btn btn-default" href="<?= route('profile::edit', ['id' => $user->id]) ?>">Edytuj
                    użytkownika</a>

                <button name="loginas" type="submit" class="btn btn-default">Zaloguj jako</button>

                <a class="btn btn-default" href="<?= route('user::generatepasswordchange', ['id' => $user->id]) ?>">Wygeneruj
                    link zmiany hasła</a>

            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Autoryzacja</div>
    <div class="panel-body">
        <form id="removeTokenForm" action="#" method="POST">
            <ul class="list-group">
                <?= csrfField(); ?>
                <input id="removeToken" name="removeToken" type="hidden"/>
                <input id="tokenId" name="tokenId" type="hidden" value=""/>
                <?php

                foreach ($profile['tokens'] as $device) {
                    $date = date("d-m-Y H:i:s", strtotime($device->last));
                    if ($device->type == "FACEBOOK") {
                        $canFBConnect = false;
                    }
                    ?>

                    <li class="list-group-item">
                        <span value="<?= $device->id ?>" class="badge removeToken"
                              style="background-color: #d9534f; cursor: pointer;">X</span>
                        <span class="badge" title="<?= $device->useragent ?>">
                        <?= ($device->type == TokenType::PasswordReminder ? $device->authKey : $device->devicename) ?>
                    </span>
                        <span class="badge hide-mobile">
                        Ostatnio: <?= $date ?>
                    </span>
                        <?= $device->devicename ?>
                    </li>

                    <?php
                }

                if (empty($profile['tokens'])) {
                    echo 'Żadna przeglądarka ani aplikacja nie ma dostępu do tego konta';
                }

                ?>
            </ul>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Historia zamówień</div>
    <div class="panel-body">

        <form method="POST" action="#" class="form-inline">

            Od: <input type="text" class="form-control datepicker" name="from" value="<?= $from ?>">
            Do: <input type="text" class="form-control datepicker" name="to" value="<?= $to ?>">
            <input class="btn btn-primary" type="submit" value="Wyświetl">
        </form>
        <br>

        <table class="table table-hover">
            <tr>
                <th>Data</th>
                <th>Danie</th>
            </tr>
            <?php

            $lastday = date(0);

            foreach ($orders as $row) {
                if ($row['date'] == null) {
                    continue;
                }

                if ($row['type'] == "PIZZA" && isset($row['pizza'])): ?>
                    <tr>
                        <td><?= insertIf($row['date'], $row['date'] != $lastday) ?></td>
                        <td><span class="label"
                                  style="background-color: <?= getTypeColor($row['type']) ?>;"><?= getTypeName($row['type']) ?></span> <?= $row['description'] ?>
                            (<?= $row['ingredients'] ?>)
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?= insertIf($row['date'], $row['date'] != $lastday) ?></td>
                        <td><span class="label"
                                  style="background-color: <?= getTypeColor($row['type']) ?>;"><?= getTypeName($row['type']) ?></span> <?= $row['description'] ?>
                        </td>
                    </tr>
                <?php endif;

                $lastday = $row['date'];
            }
            ?>

        </table>
    </div>
</div>