<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EscapeRestorer;

use AnyMark\PublicApi\AfterParsingEvent;
use AnyMark\PublicApi\PatternMatch;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

/**
 * @package AnyMark
 */
class EscapeRestorerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher)
    {
        $restorer = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($restorer) {
                $restorer->restoreTree($event->getTree());
            }
        );
        $eventDispatcher->registerForEvent(
            'AnyMark\\PublicApi\\PatternMatch',
            function (PatternMatch $match) use ($restorer) {
                $restorer->handlePatternMatch($match);
            }
        );
    }
}
