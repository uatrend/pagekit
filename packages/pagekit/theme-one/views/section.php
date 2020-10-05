<?php

    $id = "tm-{$name}";
    $class = [];
    $class[] = "tm-{$name}";
    $attrs_container = [];
    $attrs_viewport_height = [];
    $attrs_image = [];
    $attrs_section = [];

    $options = $options ?: $params->get('position.defaults');

    if (!isset($options['header_transparent'])) {
        $options['header_transparent'] = false;
    }

    if (!isset($options['header_preserve_color'])) {
        $options['header_preserve_color'] = false;
    }

    if (!isset($options['header_transparent_noplaceholder'])) {
        $options['header_transparent_noplaceholder'] = false;
    }

    $attrs = [
        'tm-header-transparent' => $options['header_transparent'] ? $options['header_preserve_color'] ? 'dark' : 'light' : false,
        // 'tm-header-transparent-placeholder' => $options['header_transparent'] && !$options['header_transparent_noplaceholder']
    ];

    $placeholder = $options['header_transparent'] && !$options['header_transparent_noplaceholder'] ?: false;

    // Section
    $class[] = "{$options['style']}";
    $class[] = (isset($options['overlap']) && $options['overlap']) ? 'uk-section-overlap' : '';
    $attrs_section['class'][] = 'uk-section';

    // Image
    if (isset($options['image']) && $options['image']) {
        $attrs_image = bgImage($options['image'], $options);
    }


    // Text color
    if ($options['style'] == 'uk-section-primary' || $options['style'] == 'uk-section-secondary') {
        if (!isset($options['preserve_color'])) $options['preserve_color'] = false;
        $class[] = $options['preserve_color'] ? 'uk-preserve-color' : '';
    }


    // Padding
    if (isset($options['size'])) {
        $attrs_section['class'][] = "{$options['size']}";
    }

    if (isset($options['size']) && ($options['size'] != 'uk-padding-remove-vertical')) {
        if (isset($options['padding_remove_top']) && $options['padding_remove_top']) {
            $attrs_section['class'][] = 'uk-padding-remove-top';
        }
        if (isset($options['padding_remove_bottom']) && $options['padding_remove_bottom']) {
            $attrs_section['class'][] = 'uk-padding-remove-bottom';
        }
    }

    // Height Viewport
    if (isset($options['height']) && $options['height']) {

        if ($options['height'] == 'expand') {
            $attrs_section['uk-height-viewport'] = 'expand: true';
        } else {

            // Vertical alignment
            if (isset($options['vertical_align']) && $options['vertical_align']) {

                $attrs_section['class'][] = "uk-flex uk-flex-{$options['vertical_align']}";
                $attrs_viewport_height['class'][] = 'uk-width-1-1';

            }

            $viewport_options = ['offset-top: true'];
            switch ($options['height']) {
                case 'percent':
                    $viewport_options[] = 'offset-bottom: 20';
                    break;
                case 'section':
                    $viewport_options[] = $options['image'] ? 'offset-bottom: ! +' : 'offset-bottom: true';
                    break;
            }

            $attrs_section['uk-height-viewport'] = implode(';', array_filter($viewport_options));

        }

    }

    // Container and width
    if (!isset($options['width'])) {
        $options['width'] = '';
    }
    if (!$options['width']) {
        $attrs_container['class'][] = 'uk-container';
    } else {
        $attrs_container['class'][] = "uk-container uk-container-{$options['width']}";
    }

?>

<div<?= attrs(compact('id', 'class'), $attrs, !$attrs_image ? $attrs_section : []) ?>>

    <?php if ($attrs_image) : ?>
    <div<?= attrs($attrs_image, $attrs_section) ?>>
    <?php endif ?>

            <?php if ($attrs_viewport_height) : ?>
            <div<?= attrs($attrs_viewport_height) ?>>
            <?php endif ?>

                <?php if ($attrs_container) : ?>
                <div<?= attrs($attrs_container) ?>>
                <?php endif ?>

                    <?php if ($placeholder) : ?>
                    <div class="tm-header-placeholder uk-margin-remove-adjacent"></div>
                    <?php endif ?>

                    <div class="uk-grid-match" uk-grid>

                        <?php if ($name !== 'main') : ?>
                            <?= $view->position($name, 'position-grid.php') ?>
                        <?php else : ?>
                            <main class="<?= $view->position()->exists('sidebar') ? 'uk-width-3-4@m' : 'uk-width-1-1' ?>">
                                <?= $view->render('content') ?>
                            </main>

                            <?php if ($view->position()->exists('sidebar')) : ?>
                            <aside class="uk-width-1-4@m <?= $params['sidebar_first'] ? 'uk-flex-first@m' : '' ?>">
                                <?= $view->position('sidebar', 'position-panel.php') ?>
                            </aside>
                            <?php endif ?>
                        <?php endif ?>

                    </div>

                <?php if ($attrs_container) : ?>
                </div>
                <?php endif ?>

            <?php if ($attrs_viewport_height) : ?>
            </div>
            <?php endif ?>

    <?php if ($attrs_image) : ?>
    </div>
    <?php endif ?>

</div>
