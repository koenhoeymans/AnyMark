<?php

namespace AnyMark\Api;

use ElementTree\ElementTree;

/**
 * This event is thrown after parsing is done.
 */
interface AfterParsingEvent
{
    /**
     * The parsing result is an `\ElementTree\ElementTree`. You can
     * manipulate the tree. More information about `ElementTree` is
     * available: [ElementTree](https://github.com/koenhoeymans/ElementTree).
     */
    public function getTree() : ElementTree;
}
