<?php

namespace AnyMark\Pattern;

class FjorPatternFactory implements PatternFactory
{
    private $fjor;

    public function __construct(\Fjor\Api\ObjectGraphConstructor $fjor)
    {
        $this->fjor = $fjor;
    }

    public function create($patternClass) : \AnyMark\Api\Pattern
    {
        return $this->fjor->get($patternClass);
    }
}
