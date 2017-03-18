<ul class="nav navbar-nav navbar-right navbar-collapse collapse">
    <?php

    $header = Menu::getHeader();
    $isSimpleMode = !(Users::getAccessLevel(user()) < Users::asAccessLevel(Roles::User) || !Device::isMobile());

    foreach (Roles::getConstants() as $role):

        if (!array_key_exists($role, $header)) {
            continue;
        }

        $menu_level = $header[$role];
        $menu_access_level = array_search($role, Users::getRoles());

        if (Users::getAccessLevel(user()) > $menu_access_level) {
            continue;
        }

        ?>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <?= $menu_level['_name'] ?>

                <?php if (!$isSimpleMode): ?>
                    <span class="caret"></span>
                <?php endif; ?>
            </a>

            <?php if (!$isSimpleMode): ?>
            <ul class="dropdown-menu" role="menu">
            <?php endif;

                foreach ($menu_level as $element => $value):

                    if ($element[0] == '_') {
                        continue;
                    }

                    if (is_array($value)): ?>
                        <li>
                            <a href="<?= $value['url'] ?>" class="<?= (isset($value['if']) ? $value['if'] : "") ?>">
                                <span class="glyphicon glyphicon-<?= $value['icon'] ?>"></span>
                                <?= $element ?>
                            </a>
                        </li>
                    <?php elseif ($value == '_divider'): ?>
                        <li class="divider"></li>
                        <li class="dropdown-header"><?= $element ?></li>
                    <?php else: ?>
                        <li><a href="<?= $value ?>"><?= $element ?></a></li>
                    <?php endif ?>

                <?php endforeach; ?>
            <?php if (!$isSimpleMode): ?>
            </ul>
            <?php endif; ?>
        </li>
        <?php
    endforeach;
    ?>
</ul>
