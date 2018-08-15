<?php

namespace AnyMark;

class Tidy extends \PHPUnit\Framework\TestCase
{
    private $comparify;

    public function setup()
    {
        $this->comparify = new \Comparify\Comparify();
    }

    public function tidy($html)
    {
        return $this->comparify->transform($html);
    }
}
