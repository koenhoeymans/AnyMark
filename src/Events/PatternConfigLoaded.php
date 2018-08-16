<?php

namespace AnyMark\Events;

use AnyMark\Pattern\FileArrayPatternConfig;
use Epa\Api\Event;
use AnyMark\Api\EditPatternConfigurationEvent;
use AnyMark\Api\ToAliasOrParent;

class PatternConfigLoaded implements Event, EditPatternConfigurationEvent
{
    private $patternConfig;

    public function __construct(FileArrayPatternConfig $patternConfig)
    {
        $this->patternConfig = $patternConfig;
    }

    public function getPatternConfig() : FileArrayPatternConfig
    {
        return $this->patternConfig;
    }

    /**
     * @see \AnyMark\Api\EditPatternConfigurationEvent::setImplementation()
     */
    public function setImplementation(string $name, $implementation) : void
    {
        $this->patternConfig->setImplementation($name, $implementation);
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::add()
     */
    public function add(string $name, $implementation = null) : ToAliasOrParent
    {
        return $this->patternConfig->add($name, $implementation);
    }
}
