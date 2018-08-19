<?php

namespace AnyMark\Util;

class ExtensionlessUrlBuilder implements InternalUrlBuilder
{
    /**
     * @see AnyMark\Util.InternalUrlBuilder::createLink()
     */
    public function urlTo(string $resource): string
    {
        return $resource;
    }
}
