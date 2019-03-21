<!DOCTYPE html>
<html class="<?= $params['html_class'] ?>" lang="<?= $intl->getLocaleTag() ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $view->render('head') ?>
        <?php $view->style('theme', 'theme:css/theme.css') ?>
        <?php $view->script('theme', 'theme:js/theme.js', ['uikit', 'uikit-icons']) ?>
    </head>
    <body>

        <?php if ($params['logo'] || $view->menu()->exists('main') || $view->position()->exists('navbar')) : ?>
        <div class="tm-header" uk-header>
        <div class="<?= $params['classes.navbar'] ?>" <?= $params['classes.sticky'] ?>>
            <div class="uk-navbar-container">
                <div class="uk-container">
                    <nav uk-navbar>

                        <div class="uk-navbar-left">
                            <a class="uk-navbar-item uk-logo" href="<?= $view->url()->get() ?>">
                                <?php if ($params['logo']) : ?>
                                    <img src="<?= $this->escape($params['logo']) ?>" alt="">
                                    <img class="uk-logo-inverse" src="<?= ($params['logo_contrast']) ? $this->escape($params['logo_contrast']) : $this->escape($params['logo']) ?>" alt="">
                                <?php else : ?>
                                    <?= $params['title'] ?>
                                <?php endif ?>
                            </a>
                        </div>

                        <div class="uk-navbar-right">
                            <?php if ($view->menu()->exists('main') || $view->position()->exists('navbar')) : ?>
                                <?= $view->menu('main', 'menu-navbar.php') ?>
                                <?= $view->position('navbar', 'position-blank.php') ?>
                            <?php endif ?>

                            <?php if ($view->position()->exists('offcanvas') || $view->menu()->exists('offcanvas')) : ?>
                            <div class="uk-navbar-flip uk-visible-small">
                                <a href="#offcanvas" class="uk-navbar-toggle" data-uk-offcanvas></a>
                            </div>
                            <?php endif ?>
                        </div>

                    </nav>
                </div>
            </div>
        </div>
        </div>
        <?php endif ?>

        <?php if ($view->position()->exists('hero')) : ?>
        <div id="tm-hero" <?= $params['classes.header_transparent'] ?> class="tm-hero uk-section uk-section-large uk-background-cover uk-flex uk-flex-middle <?= $params['classes.hero'] ?>"  <?= $params['classes.viewport'] ?> <?= $params['hero_image'] ? "style=\"background-image: url('{$view->url($params['hero_image'])}');\"" : '' ?> <?= $params['classes.parallax'] ?>>
            <div class="uk-container">

                <section class="uk-grid-match" uk-grid>
                    <?= $view->position('hero', 'position-grid.php') ?>
                </section>

            </div>
        </div>
        <?php endif; ?>

        <?php if ($view->position()->exists('top')) : ?>
        <div id="tm-top" class="tm-top uk-section <?= $params['top_style'] ?>">
            <div class="uk-container">

                <section class="uk-grid-match" uk-grid>
                    <?= $view->position('top', 'position-grid.php') ?>
                </section>

            </div>
        </div>
        <?php endif; ?>

        <div id="tm-main" class="tm-main uk-section <?= $params['main_style'] ?>">
            <div class="uk-container">

                <section class="uk-grid-match" uk-grid>

                    <main class="<?= $view->position()->exists('sidebar') ? 'uk-width-medium-3-4' : 'uk-width-1-1' ?>">
                        <?= $view->render('content') ?>
                    </main>

                    <?php if ($view->position()->exists('sidebar')) : ?>
                    <aside class="uk-width-1-4@m <?= $params['sidebar_first'] ? 'uk-flex-first@m' : '' ?>">
                        <?= $view->position('sidebar', 'position-panel.php') ?>
                    </aside>
                    <?php endif ?>

                </div>

            </div>
        </div>

        <?php if ($view->position()->exists('bottom')) : ?>
        <div id="tm-bottom" class="tm-bottom uk-section <?= $params['bottom_style'] ?>">
            <div class="uk-container">

                <section class="uk-grid-match" uk-grid>
                    <?= $view->position('bottom', 'position-grid.php') ?>
                </section>

            </div>
        </div>
        <?php endif; ?>

        <?php if ($view->position()->exists('footer')) : ?>
        <div id="tm-footer" class="tm-footer uk-section uk-section-secondary">
            <div class="uk-container">

                <section class="uk-grid-match" uk-grid>
                    <?= $view->position('footer', 'position-grid.php') ?>
                </section>

            </div>
        </div>
        <?php endif; ?>

        <?php if ($view->position()->exists('offcanvas') || $view->menu()->exists('offcanvas')) : ?>
        <div id="offcanvas" class="uk-offcanvas">
            <div class="uk-offcanvas-bar uk-offcanvas-bar-flip">

                <?php if ($params['logo_offcanvas']) : ?>
                <div class="uk-panel uk-text-center">
                    <a href="<?= $view->url()->get() ?>">
                        <img src="<?= $this->escape($params['logo_offcanvas']) ?>" alt="">
                    </a>
                </div>
                <?php endif ?>

                <?php if ($view->menu()->exists('offcanvas')) : ?>
                    <?= $view->menu('offcanvas', ['class' => 'uk-nav-offcanvas']) ?>
                <?php endif ?>

                <?php if ($view->position()->exists('offcanvas')) : ?>
                    <?= $view->position('offcanvas', 'position-panel.php') ?>
                <?php endif ?>

            </div>
        </div>
        <?php endif ?>

        <?= $view->render('footer') ?>

    </body>
</html>
