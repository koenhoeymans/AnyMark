<?php

namespace AnyMark\Plugins\Detab;

class Detab
{
    public function detab(string $text) : string
    {
        # adapted from PHP Markdown
        return preg_replace_callback(
            "/^.*?(?<space_before>[ ]?)\t.*$/m",
            function ($matches) {
                $line = $matches[0];
                $blocks = explode("\t", $line);
                $line = $blocks[0];
                unset($blocks[0]);
                foreach ($blocks as $block) {
                    if ($matches['space_before'] === ' ') {
                        $amount = 4;
                    } else {
                        // @todo set tab amount of spaces option
                        $amount = 4 - mb_strlen($line, 'UTF-8') % 4;
                    }
                    $line .= str_repeat(" ", $amount).$block;
                }

                return $line;
            },
            $text
        );
    }
}
