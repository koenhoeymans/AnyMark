<?php

namespace AnyMark\Plugins\EmptyLineFixer;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class EmptyLineFixerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $fixer = new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($fixer) {
                $event->setText($fixer->fix($event->getText()));
            }
        );
    }
}
