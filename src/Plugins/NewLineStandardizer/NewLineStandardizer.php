<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\NewLineStandardizer;

/**
 * @package AnyMark
 */
class NewLineStandardizer
{
    public function replace($text)
    {
        return preg_replace("#\r\n?#", "\n", $text);
    }
}
