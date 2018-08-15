<?php

namespace AnyMark\Pattern;

use AnyMark\PublicApi\EditPatternConfigurationEvent;
use AnyMark\PublicApi\ToAliasOrParent;
use AnyMark\PublicApi\Where;

class FileArrayPatternConfig implements PatternConfig, EditPatternConfigurationEvent, ToAliasOrParent, Where
{
    private $config = array();

    private $dsl = array(
        'patternName' => null,
        'type' => null, // alias|tree
        'parent' => null,
    );

    public function fillFrom($file) : void
    {
        $this->config = require $file;
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getSpecifiedImplementation()
     */
    public function getSpecifiedImplementation($name) : ?string
    {
        return isset($this->config['implementations'][$name])
            ? $this->config['implementations'][$name]
            : null;
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getAliased()
     */
    public function getAliased($alias) : array
    {
        return isset($this->config['alias'][$alias])
            ? $this->config['alias'][$alias]
            : array();
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getSubnames()
     */
    public function getSubnames($name) : array
    {
        return isset($this->config['tree'][$name])
            ? $this->config['tree'][$name]
            : array();
    }

    /**
     * @see \AnyMark\PublicApi\EditPatternConfigurationEvent::setImplementation()
     */
    public function setImplementation($name, $implementation) : void
    {
        if (is_object($implementation)) {
            $this->config['implementations'][$name] = $implementation;
        } elseif (is_string($implementation)) {
            $this->config['implementations'][$name] = $implementation;
        }
    }

    /**
     * @see \AnyMark\PublicApi\EditPatternConfigurationEvent::add()
     */
    public function add($name) : FileArrayPatternConfig
    {
        $this->dsl['patternName'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\PublicApi\ToAliasOrParent::toAlias()
     */
    public function toAlias($name) : FileArrayPatternConfig
    {
        $this->dsl['type'] = 'alias';
        $this->dsl['parent'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\PublicApi\ToAliasOrParent::toParent()
     */
    public function toParent($name) : FileArrayPatternConfig
    {
        $this->dsl['type'] = 'tree';
        $this->dsl['parent'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\PublicApi\Where::last()
     */
    public function last() : void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $this->config[$this->dsl['type']][$parentName][] = $name;
    }

    /**
     * @see \AnyMark\PublicApi\Where::first()
     */
    public function first() : void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        if (isset($this->config[$this->dsl['type']][$parentName])) {
            array_unshift($this->config[$this->dsl['type']][$parentName], $name);
        } else {
            $this->config[$this->dsl['type']][$parentName][] = $name;
        }
    }

    /**
     * @see \AnyMark\PublicApi\Where::after()
     */
    public function after($patternName) : void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $subpatterns = $this->config[$this->dsl['type']][$parentName];
        $position = array_search($patternName, $subpatterns);
        array_splice($subpatterns, $position+1, 0, $name);
        $this->config[$this->dsl['type']][$parentName] = $subpatterns;
    }

    /**
     * @see \AnyMark\PublicApi\Where::before()
     */
    public function before($patternName) : void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $subpatterns = $this->config[$this->dsl['type']][$parentName];
        $position = array_search($patternName, $subpatterns);
        array_splice($subpatterns, $position, 0, $name);
        $this->config[$this->dsl['type']][$parentName] = $subpatterns;
    }
}
