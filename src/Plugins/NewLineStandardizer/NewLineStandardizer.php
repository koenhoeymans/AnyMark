<?php

namespace AnyMark\Plugins\NewLineStandardizer;

class NewLineStandardizer
{
    public function replace(string $text) : string
    {
        return preg_replace("#\r\n?#", "\n", $text);
    }
}
