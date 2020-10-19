<?php

namespace Pagekit\Intl\Controller;

use Pagekit\Application as App;

class IntlApiController
{
    /**
     * @Route("/{locales}", requirements={"locale"="[a-zA-Z0-9_-]+"}, defaults={"_maintenance" = true}, methods="GET")
     * @Request({"locale"})
     */
    public function localesAction($locale = null): array
    {
        $intl = App::module('system/intl');
        
        return ['locales' => $intl->getAvailableLanguages($locale)];
    }
}
