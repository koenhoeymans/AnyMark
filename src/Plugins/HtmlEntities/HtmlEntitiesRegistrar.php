<?php

namespace AnyMark\Plugins\HtmlEntities;

use Epa\Api\Plugin;
use Epa\Api\EventDispatcher;
use AnyMark\Api\AfterParsingEvent;

class HtmlEntitiesRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $htmlEntities = new HtmlEntities();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($htmlEntities) {
                $htmlEntities->handleTree($event->getTree());
            }
        );
    }
}
