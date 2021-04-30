<?php


namespace Test\Builder;


use AllCoinCore\Builder\EventOrderBuilder;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\AssetPairPrice;
use AllCoinCore\Service\DateTimeService;
use DateTime;
use Test\TestCase;

class EventOrderBuilderTest extends TestCase
{
    private EventOrderBuilder $eventOrderBuilder;

    private DateTimeService $dateTimeService;

    public function setUp(): void
    {
        $this->dateTimeService = $this->createMock(DateTimeService::class);

        $this->eventOrderBuilder = new EventOrderBuilder(
            $this->dateTimeService
        );
    }

    public function testBuildShouldBeOK(): void
    {
        $eventName = 'foo';
        $asset = $this->createMock(Asset::class);
        $assetPair = $this->createMock(AssetPair::class);
        $assetPairPrice = $this->createMock(AssetPairPrice::class);
        $price = 1.2;
        $assetPairPrice->expects($this->once())->method('getBidPrice')->willReturn($price);

        $date = new DateTime();
        $this->dateTimeService->expects($this->once())
            ->method('now')
            ->willReturn($date);

        $event = $this->eventOrderBuilder->build(
            $eventName,
            $asset,
            $assetPair,
            $assetPairPrice
        );

        $this->assertEquals($eventName, $event->getName());
        $this->assertEquals($asset, $event->getAsset());
        $this->assertEquals($assetPair, $event->getAssetPair());
        $this->assertEquals($assetPairPrice, $event->getAssetPairPrice());
        $this->assertEquals($date, $event->getDate());
        $this->assertEquals($price, $event->getPrice());
    }
}
