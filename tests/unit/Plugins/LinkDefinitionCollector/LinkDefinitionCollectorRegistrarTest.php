<?php

namespace AnyMark\Plugins\LinkDefinitionCollector;

class LinkDefinitionCollectorRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersForBeforeParsingEvent()
    {
        $eventDispatcher = $this->createMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with(
                'AnyMark\\Api\\BeforeParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollectorRegistrar(
            new \AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollector()
        );
        $registrar->registerHandlers($eventDispatcher);
    }
}
