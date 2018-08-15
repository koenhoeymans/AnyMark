<?php

namespace AnyMark\Util;

interface InternalUrlBuilder
{
    /**
     * Creates the url pointing to a resource. The resource must be specified
     * as a relative path.
     */
    public function urlTo(string $resource) : string;
}
