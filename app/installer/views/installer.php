<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <link href="app/system/modules/theme/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="app/system/modules/theme/apple_touch_icon.png" rel="apple-touch-icon-precomposed">
        <?php $view->style('installer-css', 'app/installer/assets/css/installer.css') ?>
        <?php $view->script('installer', 'app/installer/app/bundle/installer.js', ['vue', 'uikit']) ?>
        <?= $view->render('head') ?>
    </head>
    <body>

        <div id="installer" class="tm-background uk-height-viewport uk-flex uk-flex-center uk-flex-middle" v-cloak>
            <div class="tm-container">
                <div :is="step" :vm.sync="$root"></div>
            </div>
        </div>

    </body>
</html>
