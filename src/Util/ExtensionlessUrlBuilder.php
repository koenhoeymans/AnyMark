<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

/**
 * @package AnyMark
 */
class ExtensionlessUrlBuilder implements InternalUrlBuilder
{
    /**
     * @see AnyMark\Util.InternalUrlBuilder::createLink()
     */
    public function urlTo($resource)
    {
        return $resource;
    }
}
