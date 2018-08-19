<?php

namespace AnyMark\Pattern;

use AnyMark\Api\PatternConfigLoaded;
use AnyMark\Api\ToAliasOrParent;
use AnyMark\Api\Where;

class FileArrayPatternConfig implements PatternConfig, PatternConfigLoaded, ToAliasOrParent, Where
{
    private $config = array();

    private $dsl = array(
        'patternName' => null,
        'type' => null, // alias|tree
        'parent' => null,
    );

    public function fillFrom($file): void
    {
        $this->config = require $file;
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getSpecifiedImplementation()
     */
    public function getSpecifiedImplementation($name)
    {
        return isset($this->config['implementations'][$name])
            ? $this->config['implementations'][$name]
            : null;
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getAliased()
     */
    public function getAliased($alias): array
    {
        return isset($this->config['alias'][$alias])
            ? $this->config['alias'][$alias]
            : array();
    }

    /**
     * @see \AnyMark\Pattern\PatternConfig::getSubnames()
     */
    public function getSubnames($name): array
    {
        return isset($this->config['tree'][$name])
            ? $this->config['tree'][$name]
            : array();
    }

    /**
     * @see \AnyMark\Api\PatternConfigLoaded::setImplementation()
     */
    public function setImplementation($name, $implementation): void
    {
        if (is_object($implementation)) {
            $this->config['implementations'][$name] = $implementation;
        } elseif (is_string($implementation)) {
            $this->config['implementations'][$name] = $implementation;
        }
    }

    /**
     * @see \AnyMark\Api\PatternConfigLoaded::add()
     */
    public function add(string $name): ToAliasOrParent
    {
        $this->dsl['patternName'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\Api\ToAliasOrParent::toAlias()
     */
    public function toAlias(string $name): Where
    {
        $this->dsl['type'] = 'alias';
        $this->dsl['parent'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\Api\ToAliasOrParent::toParent()
     */
    public function toParent($name): Where
    {
        $this->dsl['type'] = 'tree';
        $this->dsl['parent'] = $name;

        return $this;
    }

    /**
     * @see \AnyMark\Api\Where::last()
     */
    public function last(): void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $this->config[$this->dsl['type']][$parentName][] = $name;
    }

    /**
     * @see \AnyMark\Api\Where::first()
     */
    public function first(): void
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
     * @see \AnyMark\Api\Where::after()
     */
    public function after($patternName): void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $subpatterns = $this->config[$this->dsl['type']][$parentName];
        $position = array_search($patternName, $subpatterns);
        array_splice($subpatterns, $position + 1, 0, $name);
        $this->config[$this->dsl['type']][$parentName] = $subpatterns;
    }

    /**
     * @see \AnyMark\Api\Where::before()
     */
    public function before($patternName): void
    {
        $name = $this->dsl['patternName'];
        $parentName = $this->dsl['parent'];
        $subpatterns = $this->config[$this->dsl['type']][$parentName];
        $position = array_search($patternName, $subpatterns);
        array_splice($subpatterns, $position, 0, $name);
        $this->config[$this->dsl['type']][$parentName] = $subpatterns;
    }
}
