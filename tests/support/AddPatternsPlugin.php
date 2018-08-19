<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Api\PatternConfigLoaded as PatternConfigLoadedApi;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;
use AnyMark\Events\PatternConfigLoaded;
use AnyMark\Events\SetPatternConfig;

/**
 * @package
 */
class AddPatternsPlugin implements Plugin
{
    public function registerHandlers(EventDispatcher $eventDispatcher)
    {
        $configFile = __DIR__ . DIRECTORY_SEPARATOR . 'CustomPatterns.php';
        $eventDispatcher->registerForEvent(
            'AnyMark\\Events\\PatternConfigFile',
            function (SetPatternConfig $event) use ($configFile) {
                $event->setPatternConfigFile($configFile);
            }
        );
        $eventDispatcher->registerForEvent(
            'AnyMark\\Events\\PatternConfigLoaded',
            function (PatternConfigLoaded $event) {
                $this->addPatterns($event);
            }
        );
    }

    public function addPatterns(PatternConfigLoadedApi $patternConfig)
    {
        $patternConfig->setImplementation('emphasis', 'AnyMark\\Pattern\\Patterns\\Emphasis');
        $patternConfig->setImplementation('foo', 'AnyMark\\Patterns\\FooChange');
        $patternConfig->add('emphasis')->toParent('root')->first();
        $patternConfig->add('foo')->toParent('emphasis')->first();
    }
}
