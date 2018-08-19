<?php

namespace AnyMark\Pattern;

class PatternList implements PatternTree
{
    private $patternFactory;

    private $config;

    private $configCopy;

    private $tree;

    private $implementations = array();

    private $hasBeenAddedFromConfig = array();

    public function __construct(PatternConfig $config, PatternFactory $factory)
    {
        $this->config = $config;
        $this->patternFactory = $factory;
    }

    /**
     * @see \AnyMark\Pattern\PatternTree::getSubpatterns()
     */
    public function getSubpatterns(Pattern $parentPattern = null): array
    {
        $this->updateFromConfig();

        if (!$parentPattern) {
            return $this->tree['root'];
        }

        $patternId = $this->getName($parentPattern);

        return (isset($this->tree[$patternId]))
            ? $this->tree[$patternId]
            : array();
    }

    private function updateFromConfig(): void
    {
        if ($this->config != $this->configCopy) {
            $this->update();
            $this->configCopy = clone $this->config;
        }
    }

    private function update(): void
    {
        $this->tree = array();
        $this->hasBeenAddedFromConfig = array();
        $this->addPatterns($this->config->getSubnames('root'));
    }

    private function addPatterns(array $patternNames, $parentName = null)
    {
        foreach ($patternNames as $patternName) {
            $this->addPattern($patternName, $parentName);
        }
    }

    // $parentName is dealiased
    private function addPattern($patternName, $parentName = null)
    {
        if (in_array(array($patternName, $parentName), $this->hasBeenAddedFromConfig)) {
            return;
        }
        $this->hasBeenAddedFromConfig[] = array($patternName, $parentName);

        # Loop twice because we want to keep the order of the pattern config
        # so we add the (dealiased) subpatterns first, then check the subpatterns
        # and their subsubpatterns to prevent that they would add something
        # before the list is ended.
        $dealiasedPatternNames = $this->getDealiasedNames($patternName);
        foreach ($dealiasedPatternNames as $dealiasedPatternName) {
            $this->addDealiasedPatternName($dealiasedPatternName, $parentName);
        }
        foreach ($dealiasedPatternNames as $dealiasedPatternName) {
            $this->addPatterns(
                $this->config->getSubnames($dealiasedPatternName),
                $dealiasedPatternName
            );
            $this->addPatterns(
                $this->config->getSubnames($patternName),
                $dealiasedPatternName
            );
        }
    }

    private function addDealiasedPatternName($patternName, $parentName = null)
    {
        $parentId = $parentName ?: 'root';
        $this->tree[$parentId][] = $this->getPattern($patternName);
    }

    private function isAlias($name)
    {
        return $this->config->getAliased($name) !== array();
    }

    private function getDealiasedNames($alias)
    {
        if (!$this->isAlias($alias)) {
            return array($alias);
        }

        $aliasedNames = $this->config->getAliased($alias);
        $dealiasedNames = array();
        # alias can contain other alias
        foreach ($aliasedNames as $aliasedName) {
            if ($this->isAlias($aliasedName)) {
                $dealiasedNames = array_merge(
                    $dealiasedNames,
                    $this->getDealiasedNames($aliasedName)
                );
            } else {
                $dealiasedNames[] = $aliasedName;
            }
        }

        return array_unique($dealiasedNames);
    }

    private function getPattern($name)
    {
        if (isset($this->implementations[$name])) {
            return $this->implementations[$name];
        }

        # implementation defined
        if ($impl = $this->config->getSpecifiedImplementation($name)) {
            if (!is_object($impl)) {
                $impl = $this->patternFactory->create($impl);
            }
        } else { # or name is implementation
            $impl = $this->patternFactory->create($name);
        }

        $this->implementations[$name] = $impl;

        return $impl;
    }

    private function getName(Pattern $pattern)
    {
        $class = get_class($pattern);
        foreach ($this->implementations as $name => $implementation) {
            if ($pattern == $implementation) {
                return $name;
            }
        }
    }
}
