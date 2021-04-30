<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Database\DynamoDb\ItemManagerInterface;
use AllCoinCore\Model\AssetPairPrice;
use AllCoinCore\Model\ClassMappingEnum;
use AllCoinCore\Service\DateTimeService;
use AllCoinCore\Service\SerializerService;
use DateTime;
use JetBrains\PhpStorm\Pure;

class AssetPairPriceRepository extends AbstractRepository implements AssetPairPriceRepositoryInterface
{
    #[Pure] public function __construct(
        protected ItemManagerInterface $itemManager,
        protected SerializerService $serializerService,
        private DateTimeService $dateTimeService
    )
    {
        parent::__construct($itemManager, $serializerService);
    }

    /**
     * @param AssetPairPrice $assetPairPrice
     * @throws ItemSaveException
     */
    public function save(AssetPairPrice $assetPairPrice): void
    {
        if ($assetPairPrice->getAssetPair() === null) {
            throw new ItemSaveException('You must defined the asset pair!');
        }

        $data = $this->serializerService->normalizeModel($assetPairPrice);
        unset($data['assetPair']);

        $this->itemManager->save(
            $data,
            ClassMappingEnum::CLASS_MAPPING[AssetPairPrice::class] . '_' . $assetPairPrice->getAssetPair()->getId(),
            $this->dateTimeService->now()->getTimestamp()
        );
    }

    /**
     * @param string $assetPairId
     * @param DateTime $start
     * @param DateTime $end
     * @return AssetPairPrice[]
     * @throws ItemReadException
     */
    public function findAllByDateRange(string $assetPairId, DateTime $start, DateTime $end): array
    {
        $items = $this->itemManager->fetchAllBetween(
            partitionKey: ClassMappingEnum::CLASS_MAPPING[AssetPairPrice::class] . '_' . $assetPairId,
            start: (string)$start->getTimestamp(),
            end: (string)$end->getTimestamp(),
        );

        return array_map(function (array $item) {
            return $this->serializerService->deserializeToModel($item, AssetPairPrice::class);
        }, $items);
    }

}
