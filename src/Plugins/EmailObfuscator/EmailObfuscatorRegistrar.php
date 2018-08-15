<?php

namespace AnyMark\Plugins\EmailObfuscator;

use AnyMark\PublicApi\AfterParsingEvent;
use Epa\Api\Plugin;
use Epa\Api\EventDispatcher;

class EmailObfuscatorRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $obfuscator = new \AnyMark\Plugins\EmailObfuscator\EmailObfuscator();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($obfuscator) {
                $obfuscator->handleTree($event->getTree());
            }
        );
    }
}
