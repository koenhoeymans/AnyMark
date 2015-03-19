<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * This event is thrown after parsing is done. The eventname is `AfterParsingEvent`.
 *
 * @package AnyMark
 * @eventname AfterParsingEvent
 */
interface AfterParsingEvent
{
    /**
     * The parsing result is an `\ElementTree\ElementTree`. You can
     * manipulate the tree. More information about `ElementTree` is
     * available: [ElementTree](https://github.com/koenhoeymans/ElementTree).
     *
     * @return \ElementTree\ElementTree;
     */
    public function getTree();
}
