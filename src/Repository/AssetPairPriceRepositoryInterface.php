<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Model\AssetPairPrice;
use DateTime;

interface AssetPairPriceRepositoryInterface
{
    /**
     * @param AssetPairPrice $assetPairPrice
     * @throws ItemSaveException
     */
    public function save(AssetPairPrice $assetPairPrice): void;

    /**
     * @param string $assetPairId
     * @param DateTime $start
     * @param DateTime $end
     * @return AssetPairPrice[]
     * @throws ItemReadException
     */
    public function findAllByDateRange(string $assetPairId, DateTime $start, DateTime $end): array;
}
