<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Events\SetPatternConfig;
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
            'AnyMark\\Events\\SetPatternConfig',
            function (SetPatternConfig $event) use ($configFile) {
                $event->setPatternConfigFile($configFile);
            }
        );
    }
}
