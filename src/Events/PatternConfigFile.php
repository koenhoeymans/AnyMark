<?php

namespace AnyMark\Events;

use Epa\Api\Event;
use AnyMark\Pattern\FileArrayPatternConfig;
use AnyMark\PublicApi\SetPatternConfigFileEvent;

class PatternConfigFile implements Event, SetPatternConfigFileEvent
{
    private $patternConfig;

    public function __construct(FileArrayPatternConfig $patternConfig)
    {
        $this->patternConfig = $patternConfig;
    }

    public function setPatternConfigFile(string $file) : void
    {
        $this->patternConfig->fillFrom($file);
    }
}
