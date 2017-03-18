<?php
$this->layout('base/main', ['title' => 'Ustawienia']);
$csrfField = csrfField();
?>

<?php $this->push('scripts') ?>
<script src="/js/pages/settings.js"></script>
<?php $this->end() ?>

<div class="order-day">
    <div class="caption-full">
        <form method="POST" action="#">
            <?= $csrfField ?>
            <div class="form-group">
                <label>Adres email</label>
                <div class="well well-sm" style="margin-bottom: 5px;">Dodanie emaila do konta pozwoli ci na szybkie
                    odzyskanie dostępu do konta gdy zapomnisz hasło
                </div>
                <div class="input-group">
                    <input type="email" class="form-control" id="newemail" name="newemail"
                           value="<?= $profile['user']->email ?>" placeholder="Email">
                    <span class="input-group-btn">
                        <button type="submit" name="changeemail" class="btn btn-primary">Zapisz email</button>
                      </span>
                </div>
            </div>
        </form>

        <div class="form-group">
            <label>Dostęp do konta</label>
            <div class="well well-sm" style="margin-bottom: 5px;">Poniżej wyświetlone są wszystkie urządzenia i usługi
                (np. Facebook), które mają dostęp do twojego konta. <br>Czyli: Opcja zapamiętaj mnie, Połączenie z
                Facebookiem
            </div>
            <form id="removeTokenForm" action="#" method="POST">
                <ul class="list-group">

                    <?= $csrfField ?>
                    <input id="removeToken" name="removeToken" type="hidden"/>
                    <input id="tokenId" name="tokenId" type="hidden" value=""/>

                    <?php
                    $canFBConnect = true;
                    $authTokens = Tokens::getUserTokens($profile['user']);

                    foreach ($authTokens as $device) {
                        $date = date("d-m-Y H:i:s", strtotime($device->last));
                        if ($device->type == "FACEBOOK") {
                            $canFBConnect = false;
                        } ?>

                        <li class="list-group-item">
                            <span value="<?= $device->id ?>" class="badge removeToken"
                                  style="background-color: #d9534f; cursor: pointer;">X</span>
                            <span class="badge" title="<?= $device->useragent ?>"><?= $device->devicename ?></span><span
                                    class="badge hide-mobile">Ostatnio: <?= $date ?></span>
                            <?= $device->devicename ?>
                        </li>

                        <?php
                    }

                    if (empty($authTokens)): ?>
                        Żadna przeglądarka ani aplikacja nie ma dostępu do twojego konta
                    <?php endif; ?>

                </ul>
            </form>
        </div>

        <div class="form-group">
            <label>Facebook</label><br>
            <div class="well">
                <?php
                $userNode = (isset($profile['facebook']) ? $profile['facebook'] : null);

                if (isset($userNode) && $userNode->getName() != null): ?>
                    Zalogowano jako
                <?php else: ?>
                    Nie jesteś zalogowany przez Facebooka

                    <?php if ($canFBConnect): ?>
                        <br>Aplikacja uzyskuje tylko podstawowe informacje o twoim koncie, takie jak: imię, nazwisko, email
                        <br>i <strong>NIE ma</strong> możliwości pisania postów czy czytania wiadomości<br>
                        <br><a href="/login/facebook/connect" class="btn btn-default">Połącz</a>
                    <?php else: ?>
                        <br>Twoje konto już jest powiązane. Możesz zalogować się przez Facebooka
                    <?php endif; ?>
                <?php endif; ?>

                <?= (isset($userNode) ? $userNode->getName() : "") ?>
            </div>
        </div>

        <div class="form-group">
            <label>Inne</label><br>
            <a href="<?= route('user::settings::password::change') ?>">Zmień hasło</a>
        </div>
    </div>
</div>