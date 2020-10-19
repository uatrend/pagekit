<?php

use Pagekit\Application as App;

function isHTML(string $string) : bool
{
    preg_match("/<\/?\w+((\s+\w+(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/", $string, $matches);

    return (bool) count($matches);
}

function getHTML(string $content) : string
{
    return isHTML($content) ? $content : '<p>'.$content.'</p>';
}

function attrs(array $attrs) : string
{
    $output = [];

    if (count($args = func_get_args()) > 1) {
        $attrs = call_user_func_array('array_merge_recursive', $args);
    }

    foreach ($attrs as $key => $value) {

        if (is_array($value)) {
            $value = implode(' ', array_filter($value));
        }

        if (empty($value) && !is_numeric($value)) {
            continue;
        }

        if (is_numeric($key)) {
           $output[] = $value;
        } elseif ($value === true) {
           $output[] = $key;
        } elseif ($value !== '') {
           $output[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false));
        }
    }

    return (bool) count($output) ? ' '.implode(' ', $output) : '';
}

function bgImage ($url, $options) : array
{

    $attrs = [];
    $attrs['data-src'][] = $options['image'];
    $attrs['uk-img'] = true;

    $attrs['class'][] = 'uk-background-norepeat';
    $attrs['class'][] = "uk-background-cover";

    if (isset($options['image_position']) && $options['image_position']) {
        $attrs['class'][] = "uk-background-{$options['image_position']}";
    }

    if (!isset($options['effect'])) {
        $options['effect'] = '';
    }

    switch ($options['effect']) {
        case '':
            break;
        case 'fixed':
            $attrs['class'][] = 'uk-background-fixed';
            break;
        case 'parallax':
            $parallax_options = [];
            $parallax_options[] = "bgy: -200";
            $parallax_options[] = "media: @s";
            $attrs['uk-parallax'] = implode(';', array_filter($parallax_options));
            break;
    }

    return $attrs;

}

function image($url, array $attrs = []): string
{
    $path = App::view()->url($url);

    if (empty($attrs['alt'])) {
        $attrs['alt'] = true;
    }

    $attributes = attrs(['src' => $path], $attrs);
    return "<img".$attributes.">";
}

function isImage($link): bool
{
    return $link && preg_match('#\.(gif|png|jpe?g|svg)$#', $link, $matches) ? $matches[1] : false;
}
