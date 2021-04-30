<?php


namespace AllCoinCore\Builder;


use AllCoinCore\Model\Asset;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\AssetPairPrice;
use AllCoinCore\Model\EventOrder;
use AllCoinCore\Service\DateTimeService;

class EventOrderBuilder
{
    public function __construct(
        private DateTimeService $dateTimeService
    )
    {
    }

    public function build(
        string $eventName,
        Asset $asset,
        AssetPair $assetPair,
        AssetPairPrice $assetPairPrice,
    ): EventOrder
    {
        return new EventOrder(
            $eventName,
            $asset,
            $assetPair,
            $assetPairPrice,
            $this->dateTimeService->now(),
            $assetPairPrice->getBidPrice()
        );
    }
}
