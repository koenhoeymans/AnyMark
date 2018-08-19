<?php

namespace AnyMark\Api;

/**
 * This event is thrown before parsing starts.
 */
interface BeforeParsingEvent
{
    /**
     * Get the text as it is before before being parsed.
     */
    public function getText(): string;

    /**
     * Set the text that will be parsed.
     */
    public function setText(string $text): void;
}
