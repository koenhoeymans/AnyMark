<?php

namespace AnyMark\Pattern;

interface PatternFactory
{
    public function create(string $patternClass) : \AnyMark\Api\Pattern;
}
