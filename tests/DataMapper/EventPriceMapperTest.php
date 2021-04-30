<?php


namespace Test\DataMapper;


use AllCoinCore\DataMapper\EventPriceMapper;
use AllCoinCore\Model\EventPrice;
use AllCoinCore\Service\SerializerService;
use Test\TestCase;

class EventPriceMapperTest extends TestCase
{
    private EventPriceMapper $eventPriceMapper;

    private SerializerService $serializerService;

    public function setUp(): void
    {
        $this->serializerService = $this->createMock(SerializerService::class);

        $this->eventPriceMapper = new EventPriceMapper(
            $this->serializerService
        );
    }

    public function testMapJsonToEventShouldBeOK(): void
    {
        $data = 'foo';

        $this->serializerService->expects($this->once())
            ->method('deserializeToEvent')
            ->with($data, EventPrice::class)
            ->willReturn($this->createMock(EventPrice::class));

        $this->eventPriceMapper->mapJsonToEvent($data);
    }
}
