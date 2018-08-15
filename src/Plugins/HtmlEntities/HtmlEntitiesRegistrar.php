<?php

namespace AnyMark\Plugins\HtmlEntities;

use Epa\Api\Plugin;
use Epa\Api\EventDispatcher;
use AnyMark\PublicApi\AfterParsingEvent;

class HtmlEntitiesRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $htmlEntities = new \AnyMark\Plugins\HtmlEntities\HtmlEntities();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($htmlEntities) {
                $htmlEntities->handleTree($event->getTree());
            }
        );
    }
}
