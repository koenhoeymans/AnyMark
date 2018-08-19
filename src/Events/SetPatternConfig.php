<?php

namespace AnyMark\Events;

use Epa\Api\Event;
use AnyMark\Pattern\FileArrayPatternConfig;
use AnyMark\Api\SetPatternConfig as SetPatternConfigApi;

class SetPatternConfig implements Event, SetPatternConfigApi
{
    private $patternConfig;

    public function __construct(FileArrayPatternConfig $patternConfig)
    {
        $this->patternConfig = $patternConfig;
    }

    public function setPatternConfigFile(string $file): void
    {
        $this->patternConfig->fillFrom($file);
    }
}
