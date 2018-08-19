<?php

namespace AnyMark\Plugins\NewLineStandardizer;

use AnyMark\Api\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class NewLineStandardizerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher): void
    {
        $standardizer = new NewLineStandardizer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($standardizer) {
                $event->setText($standardizer->replace($event->getText()));
            }
        );
    }
}
