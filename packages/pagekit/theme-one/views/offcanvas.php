<?php

$logo = $params->get('logo_offcanvas');

$attrs_offcanvas = [
    'id' => 'offcanvas',
    'class' => 'uk-offcanvas uk-offcanvas-overlay',
    'uk-offcanvas' => json_encode(array_filter([
        'mode' => $params->get('navbar.offcanvas.mode'),
        'flip' => $params->get('navbar.offcanvas.flip'),
        'overlay' => $params->get('navbar.offcanvas.overlay')
    ]))
];

$image = $this->escape($params->get('logo_offcanvas'));
$title = $params->get('title');

// Link
$attrs_link['href'] = $view->url()->get();
$attrs_link['class'][] = 'uk-logo';

// Image
if ($image) {

    $attrs_image['class'][] = isset($img) ? $img : '';
    $attrs_image['alt'] = $title;

    $ext = isImage($image);

    if ($ext == 'gif') {
        $attrs_image['uk-gif'] = true;
    }

    if ($ext == 'svg') {
        $attrs_image['class'][] = 'uk-preserve';
        $attrs_image['uk-svg'] = true;
        $width = $height= '';
        $logo = image($image, array_merge($attrs_image, ['width' => $width, 'height' => $height]));
    } else {
        $logo = image($config['image'], $attrs_image);
    }
}

?>

<div<?= attrs($attrs_offcanvas) ?>>
    <div class="uk-offcanvas-bar">

        <?php if ($image) : ?>
        <div class="uk-panel uk-margin-bottom">
            <a<?= attrs($attrs_link) ?>><?= $logo ?></a>
        </div>
        <?php endif ?>

        <?php if ($view->menu()->exists('offcanvas')) : ?>
            <?= $view->menu('offcanvas', 'menu-navbar.php', ['class' => 'uk-nav uk-nav-primary']) ?>
        <?php endif ?>

        <?php if ($view->position()->exists('offcanvas')) : ?>
            <?= $view->position('offcanvas', 'position-panel.php') ?>
        <?php endif ?>

    </div>
</div>
