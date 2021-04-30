<?php


namespace Test\Builder;


use AllCoinCore\Builder\EventPriceBuilder;
use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\AssetPairPrice;
use DateTime;
use Test\TestCase;

class EventPriceBuilderTest extends TestCase
{
    private EventPriceBuilder $eventPriceBuilder;

    public function setUp(): void
    {
        $this->eventPriceBuilder = new EventPriceBuilder();
    }

    public function testBuildShouldBeOK(): void
    {
        $name = 'foo';
        $asset = $this->createMock(Asset::class);
        $assetPair = $this->createMock(AssetPair::class);
        $assetPairPrice = $this->createMock(AssetPairPrice::class);
        $askPrice = 1.2;
        $assetPairPrice->expects($this->once())->method('getAskPrice')->willReturn($askPrice);
        $dateTime = $this->createMock(DateTime::class);
        $percent = '5';

        $event = $this->eventPriceBuilder->build(
            $name,
            $asset,
            $assetPair,
            $assetPairPrice,
            $dateTime,
            $percent,
        );

        $this->assertEquals($name, $event->getName());
        $this->assertEquals($asset, $event->getAsset());
        $this->assertEquals($assetPair, $event->getAssetPair());
        $this->assertEquals($askPrice, $event->getPrice());
        $this->assertEquals($dateTime, $event->getDate());
        $this->assertEquals($percent, $event->getPercent());
    }
}
