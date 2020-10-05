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

        <?php if ($view->position()->exists('navbar') || $view->menu()->exists('main') || $params->get('logo')) : ?>
            <?= $view->render('header.php'); ?>
        <?php endif ?>


        <?php foreach($params->get('position.customizable') as $name) : ?>
            <?php if ($view->position()->exists($name) || ($name === 'main' && !$params['content_hide'])) : ?>
                <?= $view->position($name, 'section.php', ['name' => $name, 'options' => $params->get('positions.'.$name)]) ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($view->position()->exists('footer')) : ?>
        <div id="tm-footer" class="tm-footer uk-section uk-section-secondary uk-section-large">
            <div class="uk-container">

                <div class="uk-grid-match" uk-grid>
                    <?= $view->position('footer', 'position-grid.php') ?>
                </div>

            </div>
        </div>
        <?php endif; ?>

        <?php if ($view->position()->exists('offcanvas') || $view->menu()->exists('offcanvas')) : ?>
            <?= $view->render('offcanvas.php') ?>
        <?php endif ?>

        <?= $view->render('footer') ?>

    </body>
</html>
