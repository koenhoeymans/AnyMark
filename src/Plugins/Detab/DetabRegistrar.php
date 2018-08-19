<?php

namespace AnyMark\Plugins\Detab;

use AnyMark\Api\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class DetabRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher): void
    {
        $detab = new Detab();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($detab) {
                $event->setText($detab->detab($event->getText()));
            }
        );
    }
}
