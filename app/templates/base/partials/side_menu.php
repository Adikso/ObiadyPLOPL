<div class="list-group">

    <?php
    $hideOnMobileBySize = Menu::toHideOnMobile();
    $isMobile = Device::isMobile();

    if (!$isMobile): ?>
        <a class="list-group-item decorated-list-item hide-mobile"></a>
    <?php endif;

    foreach (Menu::getMenu() as $element => $value):
        $id = substr($value, 1);

        $classname = '';

        if (in_array($element, $hideOnMobileBySize) && $isMobile) {
            continue;
        }

        if (!is_null(Pages::getCurrent()) && $value === Pages::getCurrent()->getRoute()->getPath()) {
            $classname = "active";
        }

        if (in_array($element, $hideOnMobileBySize)) {
            $classname .= ' hide-mobile';
        }

        ?>

        <a href="<?= $value ?>" class="list-group-item <?= $classname ?>"><?= $element ?></a>

    <?php endforeach; ?>

</div>