<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\NewLineStandardizer;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

/**
 * @package AnyMark
 */
class NewLineStandardizerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher)
    {
        $standardizer = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\BeforeParsingEvent',
            function (BeforeParsingEvent $event) use ($standardizer) {
                $event->setText($standardizer->replace($event->getText()));
            }
        );
    }
}
