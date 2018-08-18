<?php

namespace AnyMark\Plugins\EscapeRestorer;

use AnyMark\Api\AfterParsingEvent;
use AnyMark\Api\PatternMatch;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

class EscapeRestorerRegistrar implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher) : void
    {
        $restorer = new EscapeRestorer();
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\AfterParsingEvent',
            function (AfterParsingEvent $event) use ($restorer) {
                $restorer->restoreTree($event->getTree());
            }
        );
        $eventDispatcher->registerForEvent(
            'AnyMark\\Api\\PatternMatch',
            function (PatternMatch $match) use ($restorer) {
                $restorer->handlePatternMatch($match);
            }
        );
    }
}
