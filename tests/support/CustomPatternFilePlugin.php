<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Events\PatternConfigFile;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

/**
 * @package AnyMark
 */
class CustomPatternFilePlugin implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher)
    {
        $configFile = __DIR__ . DIRECTORY_SEPARATOR . 'CustomPatterns.php';
        $eventDispatcher->registerForEvent(
            'AnyMark\\Events\\PatternConfigFile',
            function (PatternConfigFile $event) use ($configFile) {
                $event->setPatternConfigFile($configFile);
            }
        );
    }
}
