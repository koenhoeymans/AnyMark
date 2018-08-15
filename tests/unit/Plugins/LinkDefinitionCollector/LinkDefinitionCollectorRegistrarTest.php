<?php

namespace AnyMark\Plugins\LinkDefinitionCollector;

class LinkDefinitionCollectorRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersForBeforeParsingEvent()
    {
        $eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with(
                'AnyMark\\PublicApi\\BeforeParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollectorRegistrar(
            new \AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollector()
        );
        $registrar->registerHandlers($eventDispatcher);
    }
}
