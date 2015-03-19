<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use Epa\Api\Event;
use AnyMark\Pattern\FileArrayPatternConfig;
use AnyMark\PublicApi\SetPatternConfigFileEvent;

/**
 * @package AnyMark
 */
class PatternConfigFile implements Event, SetPatternConfigFileEvent
{
    private $patternConfig;

    public function __construct(FileArrayPatternConfig $patternConfig)
    {
        $this->patternConfig = $patternConfig;
    }

    public function setPatternConfigFile($file)
    {
        $this->patternConfig->fillFrom($file);
    }
}
