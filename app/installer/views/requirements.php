<!DOCTYPE html>
<html>
    <head>
        <title>Pagekit Installer Errors</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow">
        <link href="app/system/modules/theme/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="app/system/modules/theme/apple_touch_icon.png" rel="apple-touch-icon-precomposed">
        <link href="app/installer/assets/css/installer.css" rel="stylesheet">
        <script src="app/assets/uikit/dist/js/uikit.min.js" type="text/javascript"></script>
    </head>
    <body>

        <div class="uk-height-viewport uk-flex uk-flex-center uk-flex-middle">
            <div class="tm-container uk-text-center">

                <img class="uk-margin-medium-bottom" src="app/system/assets/images/pagekit-logo-large-black.svg" alt="Pagekit">

                <div class="uk-panel">
                    <h1>System Requirements</h1>
                    <p>Please fix the following <span class="uk-text-primary">#<?php echo count($failed); ?></span> issues to proceed.</p>
                    <ul class="uk-list uk-text-small">
                        <?php foreach ($failed as $req) : ?>
                        <li>
                            <div class="uk-grid-small" uk-grid>
                                <div class="tm-label-changelog"><span class="uk-label uk-label-danger uk-width-expand">Error</span> </div>
                                <div class="uk-width-expand uk-text-left"><?php echo $req->getTestMessage() ?></div>
                            </div>
                        </li>
                        <li>
                            <div class="uk-grid-small" uk-grid>
                                <div class="tm-label-changelog"><span class="uk-label uk-width-expand">Fix</span></div>
                                <div class="uk-width-expand uk-text-left"><?php echo $req->getHelpHtml() ?></div>
                            </div>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>

            </div>
        </div>

    </body>
</html>