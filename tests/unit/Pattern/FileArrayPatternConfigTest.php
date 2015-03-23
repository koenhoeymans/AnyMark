<?php

namespace AnyMark\Pattern;

class FileArrayPatternConfigTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $file = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'SimpleConfig.php';
        $this->config = new \AnyMark\Pattern\FileArrayPatternConfig();
        $this->config->fillFrom($file);
    }

    /**
     * @test
     */
    public function returnsSpecifiedImplementationAsClassNameFromConfig()
    {
        $this->assertEquals(
            '\\AnyMark\\Pattern\\Patterns\\Emphasis',
            $this->config->getSpecifiedImplementation('emphasis')
        );
    }

    /**
     * @test
     */
    public function returnsNullWhenNoImplementationSpecified()
    {
        $this->assertEquals(
            null,
            $this->config->getSpecifiedImplementation('_emp_asis_')
        );
    }

    /**
     * @test
     */
    public function returnsAlias()
    {
        $this->assertEquals(
            array('strong', 'emphasis'),
            $this->config->getAliased('foo')
        );
    }

    /**
     * @test
     */
    public function returnsEmptyListIfNoSubnames()
    {
        $this->assertEquals(array(), $this->config->getSubnames('_emph_asis_'));
    }

    /**
     * @test
     */
    public function returnsListWithSubnames()
    {
        $this->assertEquals(array('strong'), $this->config->getSubnames('emphasis'));
    }

    /**
     * @test
     */
    public function returnsSpecifiedObjectImplementationIfAddedByApi()
    {
        $pattern = new \AnyMark\DummyPattern();

        $this->config->setImplementation('pattern', $pattern);

        $this->assertEquals(
            $pattern,
            $this->config->getSpecifiedImplementation('pattern')
        );
    }

    /**
     * @test
     */
    public function returnsSpecifiedClassImplementationIfAddedByApi()
    {
        $this->config->setImplementation('pattern', 'class');

        $this->assertEquals(
            'class',
            $this->config->getSpecifiedImplementation('pattern')
        );
    }

    /**
     * @test
     */
    public function canAddPatternNameToAlias()
    {
        $this->config->add('mock')->toAlias('foo')->last();

        $this->assertEquals(
            array('strong', 'emphasis', 'mock'),
            $this->config->getAliased('foo')
        );
    }

    /**
     * @test
     */
    public function canAddPatternNameToAliasAfterOtherPattern()
    {
        $this->config->add('mock')->toAlias('foo')->after('strong');

        $this->assertEquals(
            array('strong', 'mock', 'emphasis'),
            $this->config->getAliased('foo')
        );
    }

    /**
     * @test
     */
    public function canAddPatternNameToAliasAfterBeforePattern()
    {
        $this->config->add('mock')->toAlias('foo')->before('emphasis');

        $this->assertEquals(
            array('strong', 'mock', 'emphasis'),
            $this->config->getAliased('foo')
        );
    }

    /**
     * @test
     */
    public function canAddPatternToAliasThatDoesNotYetExist()
    {
        $this->config->add('mock')->toAlias('bar')->last();

        $this->assertEquals(array('mock'), $this->config->getAliased('bar'));
    }

    /**
     * @test
     */
    public function canAddPatternAsLastSubpattern()
    {
        $this->config->add('mock')->toParent('root')->last();

        $names = $this->config->getSubnames('root');
        $this->assertEquals('mock', end($names));
    }

    /**
     * @test
     */
    public function canAddPatternAsFirstSubpattern()
    {
        $this->config->add('mock')->toParent('root')->first();

        $names = $this->config->getSubnames('root');
        $this->assertEquals('mock', array_shift($names));
    }

    /**
     * @test
     */
    public function patternCanBeAddedAfterOtherPattern()
    {
        $this->config->add('mock')->toParent('root')->after('emphasis');

        $this->assertEquals(
            array('emphasis', 'mock', 'foo'),
            $this->config->getSubnames('root')
        );
    }

    /**
     * @test
     */
    public function patternCanBeAddedBeforeOtherPattern()
    {
        $this->config->add('mock')->toParent('root')->before('foo');

        $this->assertEquals(
            array('emphasis', 'mock', 'foo'),
            $this->config->getSubnames('root')
        );
    }
}
