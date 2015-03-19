<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

/**
 * @package AnyMark
 */
class FjorPatternFactory implements PatternFactory
{
    private $fjor;

    public function __construct(\Fjor\Api\ObjectGraphConstructor $fjor)
    {
        $this->fjor = $fjor;
    }

    public function create($patternClass)
    {
        return $this->fjor->get($patternClass);
    }
}
