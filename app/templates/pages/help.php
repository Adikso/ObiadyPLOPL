<?php
$this->layout('base/main', ['title' => 'Kontakt']);
?>

<div class="order-day">
    <ul class="list-group">
        <li class="list-group-item"><strong>Email:</strong> <?= config('mail.username') ?></li>
        <li class="list-group-item"></li>
        <li class="list-group-item"><strong>Pierwsza pomoc:</strong></li>
        <li class="list-group-item"><a href="<?= route('user::password::recovery') ?>">Przypomnij hasło</a></li>
        <li class="list-group-item"><b>Loginy budowane są w następujący sposób:</b></li>
        <li class="list-group-item">u[ROCZNIK][KLASA][3 litery imienia][3literynazwiska].<br> <b>u14ajankow</b> dla Jana
            Kowalskiego z 3A liceum.<br> <b>u16agandnow</b> dla Andrzeja Nowaka z 1AG gimnazjum
        </li>
        <li class="list-group-item"></li>
        <li class="list-group-item"><strong>Ekipa</strong></li>
        <li class="list-group-item"><a>Adam Zambrzycki</a>
            (Administrator i twórca systemu)
        </li>
        <li class="list-group-item"><a>Justyna Rudnicka</a> ("Główny
            Zarządca obiadowy")
        </li>
    </ul>
</div>
<div class="order-day">
    <ul class="list-group">
        <li class="list-group-item"><strong>Wersja systemu:</strong> <?= $last_version_details ?></li>
        <li class="list-group-item"><strong>Autorzy:</strong> Adam Zambrzycki</li>

        <li class="list-group-item"><a href="<?= route('systemhistory') ?>">Historia systemu obiadowego</a> </li>
    </ul>
</div>
