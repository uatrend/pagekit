<?php

use Pagekit\Application as App;
use Symfony\Component\Translation\Formatter\IntlFormatter;

if (!function_exists('__')) {

    /**
     * Translates the given message, alias for method trans()
     */
    function __($id, array $parameters = [], $domain = 'messages', $locale = null) {
        return App::translator()->trans($id, $parameters, $domain, $locale);
    }
}

if (!function_exists('_c')) {

    /**
     * The transChoice() method is deprecated since Symfony 4.2, use the trans() one instead with a "%%count%%" parameter.
     * Trying replace '%value%'' to '%count%' in source string and parameters property.
     * TODO - remove _.c() from php and transChoice() from vue.
     */
    function _c($id, $number, array $parameters = [], $domain = null, $locale = null) {
        
        $id = preg_replace('/(%)(.*?)(%)/', '%count%', $id);

        $params = [];
        foreach ($parameters as $key => $value) {
            $params[preg_replace('/(%)(.*?)(%)/', '%count%', $key)] = $value;
        }

        return App::translator()->trans($id, $params, $domain, $locale);
    }
}

if (!function_exists('_i')) {

    /**
     * Translate Messages using the ICU MessageFormat:
     * https://symfony.com/doc/current/translation/message_format.html#using-the-icu-message-format
     * TODO - add _.i() to php and transICU() to vue.
     */
    function _i($id, array $parameters = [], $domain = null, $locale = null) {

        if (null === $domain) {
            $domain = 'messages';
        }

        $catalogue = App::translator()->getCatalogue($locale);
        $locale = $catalogue->getLocale();
        while (!$catalogue->defines($id, $domain)) {
            if ($cat = $catalogue->getFallbackCatalogue()) {
                $catalogue = $cat;
                $locale = $catalogue->getLocale();
            } else {
                break;
            }
        }

        return (new IntlFormatter())->formatIntl($catalogue->get($id, $domain), $locale, $parameters);
    }
}
