<?php

namespace AnyMark\Api;

use ElementTree\ElementTree;
use Epa\Api\Observable;

interface Parser extends Observable
{
    /**
     * Parse text and return an `ElementTree` with the different detected
     * components.
     */
    public function parse(string $text) : ElementTree;
}
