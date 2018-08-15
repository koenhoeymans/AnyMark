<?php

namespace AnyMark\Plugins\EmailObfuscator;

use ElementTree\Element;
use ElementTree\ElementTree;

class EmailObfuscator
{
    public function handleTree(ElementTree $tree) : void
    {
        $query = $tree->createQuery($tree);
        $elements = $query->find($query->allElements());

        foreach ($elements as $element) {
            $this->obfuscateEmail($element);
        }
    }

    private function obfuscateEmail(Element $element)
    {
        if ($element->getName() !== 'a') {
            return;
        }
        $mailto = $element->getAttributeValue('href');

        if (empty($mailto) || substr($mailto, 0, 7) !== 'mailto:') {
            return;
        }

        $mailto = implode('', $this->encode(substr($mailto, 7)));
        $element->setAttribute('href', $mailto);

        $child = $element->getChildren()[0];
        $anchor = implode('', array_slice($this->encode($child->getValue()), 7));
        $child->setValue($anchor);
    }

    private function encode($addr)
    {
        // based on/copied from PHPMarkdowns Implementation
        $addr = 'mailto:'.$addr;
        $chars = preg_split('/(?<!^)(?!$)/', $addr);
        $seed = (int) abs(crc32($addr) / strlen($addr)); # Deterministic seed.

        foreach ($chars as $key => $char) {
            $ord = ord($char);
            # Ignore non-ascii chars.
            if ($ord < 128) {
                $r = ($seed * (1 + $key)) % 100; # Pseudo-random function.
                # roughly 10% raw, 45% hex, 45% dec
                # '@' *must* be encoded. I insist.
                if ($r > 90 && $char != '@') {
                    /* do nothing */;
                } elseif ($r < 45) {
                    $chars[$key] = '&#x'.dechex($ord).';';
                } else {
                    $chars[$key] = '&#'.$ord.';';
                }
            }
        }

        return $chars;
    }
}
