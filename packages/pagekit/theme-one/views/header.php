<?php

$layout = $params->get('header.layout');
$fullwidth = $params->get('header.fullwidth');
$logo_padding_remove = $fullwidth ? $params->get('header.logo_padding_remove') : false;
$logo_center = $params->get('header.logo_center');
$logo = $this->escape($params->get('logo'));
$class = ['tm-header'];
$attrs = ['uk-header' => true];
$attrs_sticky = [];

$container = ['class' => ['uk-navbar-container']];
$navbar = $params->get('navbar');

// Dropdown options
$attrs_navbar = [
    'class' => 'uk-navbar',
    'uk-navbar' => json_encode(array_filter([
        'align' => $params->get('navbar.dropbar_align'),
        'boundary' => '!.uk-navbar-container',
        'boundary-align' => $params->get('navbar.dropdown_boundary'),
        'dropbar' => $params->get('navbar.dropbar') ? true : null,
        'dropbar-anchor' => $params->get('navbar.dropbar') ? '!.uk-navbar-container' : null,
        'dropbar-mode' => $params->get('navbar.dropbar')
    ]))
];

// Sticky
if ($sticky = $params->get('navbar.sticky')) {
    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'media' => '@m',
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);
}

?>

<div<?= attrs(['class' => $class], $attrs) ?>>

    <?php if ($sticky) : ?>
    <div<?= attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= attrs($container) ?>>

            <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?><?= $logo && $logo_padding_remove ? ' uk-padding-remove-left' : '' ?>">
                <nav<?= attrs($attrs_navbar) ?>>

                    <?php if ($logo || $layout == 'horizontal-left' && $view->menu()->exists('main') || $view->position()->exists('navbar')) : ?>
                    <div class="uk-navbar-left">

                        <?= $logo ? $view->render('header-logo.php', ['class' => 'uk-navbar-item']) : '' ?>

                        <?php if ($layout == 'horizontal-left') : ?>
                            <?= $view->menu('main', 'menu-navbar.php', ['class' => 'uk-navbar-nav uk-visible@m']) ?>
                            <?= $view->position('navbar', 'position-blank.php') ?>
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if ($layout == 'horizontal-center' && $view->menu()->exists('main') || $view->position()->exists('navbar')) : ?>
                    <div class="uk-navbar-center">
                        <?= $view->menu('main', 'menu-navbar.php', ['class' => 'uk-navbar-nav uk-visible@m']) ?>
                        <?= $view->position('navbar', 'position-blank.php') ?>
                    </div>
                    <?php endif ?>

                    <?php if ($layout == 'horizontal-right' || $view->position()->exists('header') || $view->position()->exists('navbar') || $view->menu()->exists('main')) : ?>
                    <div class="uk-navbar-right">

                        <?php if ($layout == 'horizontal-right' && $view->menu()->exists('main') || $view->position()->exists('navbar')) : ?>
                            <?= $view->menu('main', 'menu-navbar.php', ['class' => 'uk-navbar-nav uk-visible@m']) ?>
                            <?= $view->position('navbar', 'position-blank.php') ?>
                        <?php endif ?>

                        <?php if ($view->position()->exists('header')) : ?>
                        <div class="uk-navbar-item">
                            <?= $view->position('header', 'position-blank.php') ?>
                        </div>
                        <?php endif ?>

                        <?php if ($view->position()->exists('offcanvas') || $view->menu()->exists('offcanvas')) : ?>
                            <a class="uk-navbar-toggle uk-hidden@m" href="#offcanvas" uk-navbar-toggle-icon uk-toggle></a>
                        <?php endif ?>              

                    </div>
                    <?php endif ?>

                </nav>
            </div>

        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

</div>
