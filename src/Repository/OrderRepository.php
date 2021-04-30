<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Database\DynamoDb\Exception\ItemSaveException;
use AllCoinCore\Database\DynamoDb\ItemManager;
use AllCoinCore\Model\ClassMappingEnum;
use AllCoinCore\Model\Order;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    /**
     * @param Order $order
     * @param string $assetPairId
     * @throws ItemSaveException
     */
    public function save(Order $order, string $assetPairId): void
    {
        $data = $this->serializerService->normalizeModel($order);

        $data[ItemManager::LSI_1] = $assetPairId;
        $data[ItemManager::LSI_2] = $order->getVersion();
        $data[ItemManager::LSI_4] = $order->getCreatedAt()->getTimestamp();

        $this->itemManager->save(
            data: $data,
            partitionKey: ClassMappingEnum::CLASS_MAPPING[Order::class],
            sortKey: $order->getId()
        );
    }

    /**
     * @return array<Order[]>
     * @throws ItemReadException
     */
    public function findAllGroupByAssetPairId(): array
    {
        $items = $this->itemManager->fetchAll(
            ClassMappingEnum::CLASS_MAPPING[Order::class]
        );

        $orders = [];
        foreach ($items as $item) {
            $order = $this->serializerService->deserializeToModel($item, Order::class);
            $orders[$item[ItemManager::LSI_1]][] = $order;
        }

        return $orders;
    }
}
