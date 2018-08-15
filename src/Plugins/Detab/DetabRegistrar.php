<?php

namespace AnyMark\Plugins\Detab;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class DetabRegistrar implements Plugin
{
    public function registerHandlers(\Epa\Api\EventDispatcher $eventDispatcher) : void
    {
        $detab = new \AnyMark\Plugins\Detab\Detab();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($detab) {
                $event->setText($detab->detab($event->getText()));
            }
        );
    }
}
