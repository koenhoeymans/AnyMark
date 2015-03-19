<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use AnyMark\Pattern\FileArrayPatternConfig;
use Epa\Api\Event;
use AnyMark\PublicApi\EditPatternConfigurationEvent;

/**
 * @package AnyMark
 */
class PatternConfigLoaded implements Event, EditPatternConfigurationEvent
{
    private $patternConfig;

    public function __construct(FileArrayPatternConfig $patternConfig)
    {
        $this->patternConfig = $patternConfig;
    }

    public function getPatternConfig()
    {
        return $this->patternConfig;
    }

    /**
     * @see \AnyMark\PublicApi\EditPatternConfigurationEvent::setImplementation()
     */
    public function setImplementation($name, $implementation)
    {
        $this->patternConfig->setImplementation($name, $implementation);
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::add()
     */
    public function add($name, $implementation = null)
    {
        return $this->patternConfig->add($name, $implementation);
    }
}
