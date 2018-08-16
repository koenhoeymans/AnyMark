<?php

namespace AnyMark\Pattern\Patterns;

class LinkDefinition
{
    private $name;

    private $url;

    private $title;

    public function __construct($name, $url, $title = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->title = $title;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }
}
