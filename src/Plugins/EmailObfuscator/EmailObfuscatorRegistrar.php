<?php

namespace AnyMark\Plugins\EmailObfuscator;

use AnyMark\Api\AfterParsingEvent;
use Epa\Api\Plugin;
use Epa\Api\EventDispatcher;

class EmailObfuscatorRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $obfuscator = new EmailObfuscator();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($obfuscator) {
                $obfuscator->handleTree($event->getTree());
            }
        );
    }
}
