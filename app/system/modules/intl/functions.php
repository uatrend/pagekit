<?php

use Pagekit\Application as App;

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
     * Deprecated transChoice(). Since Symfony 4.2
     * Translates the given choice message by choosing a translation according to a number, alias for method transChoice()
     */
    function _c($id, $number, array $parameters = [], $domain = null, $locale = null) {
        
        // return App::translator()->transChoice($id, $number, $parameters, $domain, $locale);

        $params = [];
        foreach ($parameters as $key => $value) {
            $params[preg_replace('/(%)(.*?)(%)/', '%count%', $key)] = $value;
        }

        return App::translator()->trans($id, $params, $domain, $locale);
    }
}
