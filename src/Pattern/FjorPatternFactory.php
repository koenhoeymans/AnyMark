<?php

namespace AnyMark\Pattern;

use Fjor\Api\ObjectGraphConstructor;
use AnyMark\Api\Pattern;

class FjorPatternFactory implements PatternFactory
{
    private $fjor;

    public function __construct(ObjectGraphConstructor $fjor)
    {
        $this->fjor = $fjor;
    }

    public function create($patternClass): Pattern
    {
        return $this->fjor->get($patternClass);
    }
}
