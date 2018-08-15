<?php

namespace AnyMark\Api;

interface Parser extends \Epa\Api\Observable
{
    /**
     * Parse text and return an `ElementTree` with the different detected
     * components.
     */
    public function parse($text) : \ElementTree\ElementTree;
}
