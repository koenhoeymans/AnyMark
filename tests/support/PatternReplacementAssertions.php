<?php

namespace AnyMark;

abstract class PatternReplacementAssertions extends \PHPUnit\Framework\TestCase
{
    protected $tree;

    abstract protected function getPattern();

    public function elementTree()
    {
        if (!$this->tree) {
            $this->tree = new \ElementTree\ElementTree();
        }

        return $this->tree;
    }

    public function applyPattern($text)
    {
        preg_match($this->getPattern()->getRegex(), $text, $match);
        if (empty($match)) {
            return;
        }
        $result = $this->getPattern()->handleMatch(
            $match,
            $this->elementTree()->createElement('foo')
        );

        return $result;
    }
}
