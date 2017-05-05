<?php
$this->layout('base/main', ['title' => 'Klasa ' . $class->getName()]);
?>

<div class="panel panel-default">
    <div class="panel-heading">Statystyki klasy <?= $class->getName() ?></div>
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item"><b>Rocznik:</b> <?= $class->year ?></li>
            <li class="list-group-item"><b>Złożonych zamówień:</b> <?= $ordersAmount ?></li>
            <li class="list-group-item"><b>Wydane pieniądze:</b> <?= ($ordersAmount * Payments::getCost()) ?> zł
            </li>
        </ul>
    </div>
</div>

<div class="order-day table-responsive">
    <table class="table">
        <th>id</th>
        <th>Login</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Rola</th>
        <th>Stan konta</th>
        <th>Opcja</th>
        <?php

        foreach ($students as $student): ?>
        <tr>
            <td><?= $student->id ?></td>
            <td><a href="<?= route('profile', ['id' => $student->id]) ?>"><?= $student->login ?></a></td>
            <td><?= $student->firstname ?></td>
            <td><?= $student->secondname ?></td>
            <td><?= getRoleName($student->role) ?></td>
            <td><?= $student->balance ?> zł</td>
            <?php

            if (Users::getAccessLevel(user()) <= 0) { ?>

                <td>
                    <div class="btn-group">
                        <a href="<?= route('profile::edit', ['id' => $student->id]) ?>" class="btn btn-default user-manage-option">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        </a>
                        <a href="<?= route('user::manage::messenger', ['id' => $student->id]) ?>" class="btn btn-default user-manage-option">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                        </a>
                    </div>
                </td>
                <?php
            } else { ?>

                <td>
                    <div class="btn-group">
                        <a href="<?= route('user::manage::messenger', ['id' => $student->id]) ?>" class="btn btn-default user-manage-option">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                        </a>
                    </div>
                </td>

                <?php
            }

            echo '</tr>';
            endforeach;

            ?>
    </table>
</div>