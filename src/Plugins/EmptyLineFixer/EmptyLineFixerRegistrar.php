<?php

namespace AnyMark\Plugins\EmptyLineFixer;

use AnyMark\Api\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class EmptyLineFixerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $fixer = new EmptyLineFixer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($fixer) {
                $event->setText($fixer->fix($event->getText()));
            }
        );
    }
}
