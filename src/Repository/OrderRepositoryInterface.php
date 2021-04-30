<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Model\Order;

interface OrderRepositoryInterface
{
    /**
     * @param Order $order
     * @param string $assetPairId
     * @throws ItemSaveException
     */
    public function save(Order $order, string $assetPairId): void;

    /**
     * @return array<Order[]>
     * @throws ItemReadException
     */
    public function findAllGroupByAssetPairId(): array;
}
