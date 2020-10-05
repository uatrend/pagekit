<?php

$image = $params->get('logo');
$attrs_link = [];
$attrs_image = [];

// Logo Text
$title = $params->get('title');

// Link
$attrs_link['href'] = $view->url()->get();
$attrs_link['class'][] = isset($class) ? $class : '';
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

    // Inverse
    $image_inverse = $params->get('logo_contrast');
    if ($image_inverse) {

        $attrs_image['class'][] = 'uk-logo-inverse';

        if (isImage($image_inverse) == 'svg') {
            $width = ''; $height = '';
            $logo .= image($image_inverse, array_merge($attrs_image, ['width' => $width, 'height' => $height]));
        } else {
            $logo .= image($image_inverse, $attrs_image);
        }

    }
}
?>

<a<?= attrs($attrs_link) ?>>
    <?= $logo ?>
</a>
