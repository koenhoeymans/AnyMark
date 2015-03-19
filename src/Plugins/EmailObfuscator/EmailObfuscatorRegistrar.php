<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EmailObfuscator;

use AnyMark\PublicApi\AfterParsingEvent;
use Epa\Api\Plugin;
use Epa\Api\EventDispatcher;

/**
 * @package AnyMark
 */
class EmailObfuscatorRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher)
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
