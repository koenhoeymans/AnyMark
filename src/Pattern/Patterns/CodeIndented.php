<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

class CodeIndented extends Code
{
    public function getRegex(): string
    {
        return
            '@
			(?<=^|\n\n|(?<newline>^\n))
			(?<code>
			(\t|[ ]{4}).*
			(\n+(\t|[ ]{4}).*)*
			)
			(?=\n\n|\n$|$)
			@x';
    }

    public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        if ($parentPattern && $match['newline'] === "\n") {
            if ($parentPattern instanceof \AnyMark\Pattern\Patterns\ManualHtml) {
                return false;
            }
        }

        $code = preg_replace("#(\n|^)(\t|[ ]{4})#", "\${1}", $match['code']);

        return $this->createCodeReplacement($code . "\n", true, $parent);
    }
}
