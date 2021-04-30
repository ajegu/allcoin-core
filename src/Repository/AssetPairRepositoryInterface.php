<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemDeleteException;
use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Model\AssetPair;
use AllCoinCore\Model\ModelInterface;

interface AssetPairRepositoryInterface
{
    /**
     * @param AssetPair $assetPair
     * @param string $assetId
     * @throws ItemSaveException
     */
    public function save(AssetPair $assetPair, string $assetId): void;

    /**
     * @param string $assetPairId
     * @return AssetPair|ModelInterface
     * @throws ItemReadException
     */
    public function findOneById(string $assetPairId): AssetPair|ModelInterface;

    /**
     * @param string $assetId
     * @return AssetPair[]
     * @throws ItemReadException
     */
    public function findAllByAssetId(string $assetId): array;

    /**
     * @param string $assetPairId
     * @throws ItemDeleteException
     */
    public function delete(string $assetPairId): void;

    /**
     * @return AssetPair[]
     * @throws ItemReadException
     */
    public function findAll(): array;

}
