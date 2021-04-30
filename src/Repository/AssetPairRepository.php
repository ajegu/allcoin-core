<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Database\DynamoDb\ItemManager;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\ClassMappingEnum;
use AllCoinCore\Model\ModelInterface;

class AssetPairRepository extends AbstractRepository implements AssetPairRepositoryInterface
{
    /**
     * @param AssetPair $assetPair
     * @param string $assetId
     * @throws ItemSaveException
     */
    public function save(AssetPair $assetPair, string $assetId): void
    {
        $data = $this->serializerService->normalizeModel($assetPair);
        unset($data['asset']);
        $data[ItemManager::LSI_1] = $assetId;

        $this->itemManager->save(
            $data,
            ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
            $assetPair->getId()
        );
    }

    /**
     * @param string $assetPairId
     * @return AssetPair|ModelInterface
     * @throws ItemReadException
     */
    public function findOneById(string $assetPairId): AssetPair|ModelInterface
    {
        $item = $this->itemManager->fetchOne(
            ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
            $assetPairId
        );

        return $this->serializerService->deserializeToModel(
            $item,
            AssetPair::class
        );
    }

    /**
     * @param string $assetId
     * @return AssetPair[]
     * @throws ItemReadException
     */
    public function findAllByAssetId(string $assetId): array
    {
        $items = $this->itemManager->fetchAllOnLSI(
            partitionKey: ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
            lsiKeyName: ItemManager::LSI_1,
            lsiKey: $assetId,
        );

        return array_map(function ($item) {
            return $this->serializerService->deserializeToModel(
                $item,
                AssetPair::class
            );
        }, $items);
    }

    /**
     * @param string $assetPairId
     * @throws ItemDeleteException
     */
    public function delete(string $assetPairId): void
    {
        $this->itemManager->delete(
            partitionKey: ClassMappingEnum::CLASS_MAPPING[AssetPair::class],
            sortKey: $assetPairId
        );
    }

    /**
     * @return AssetPair[]
     * @throws ItemReadException
     */
    public function findAll(): array
    {
        $items = $this->itemManager->fetchAll(
            ClassMappingEnum::CLASS_MAPPING[AssetPair::class]
        );

        return array_map(function (array $item) {
            return $this->serializerService->deserializeToModel($item, AssetPair::class);
        }, $items);
    }
}
