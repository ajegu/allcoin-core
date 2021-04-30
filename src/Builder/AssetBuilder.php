<?php


namespace AllCoinCore\Builder;


use AllCoinCore\Model\Asset;
use AllCoinCore\Service\DateTimeService;
use AllCoinCore\Service\UuidService;

class AssetBuilder
{
    public function __construct(
        private UuidService $uuidService,
        private DateTimeService $dateTimeService
    )
    {
    }

    public function build(string $name): Asset
    {
        return new Asset(
            id: $this->uuidService->generateUuid(),
            name: $name,
            createdAt: $this->dateTimeService->now()
        );
    }
}
