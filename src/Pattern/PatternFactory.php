<?php

namespace AnyMark\Pattern;

use \AnyMark\Api\Pattern;

interface PatternFactory
{
    public function create(string $patternClass): Pattern;
}
