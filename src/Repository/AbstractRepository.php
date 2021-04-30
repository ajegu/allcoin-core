<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\ItemManagerInterface;
use AllCoinCore\Service\SerializerService;

abstract class AbstractRepository
{
    public function __construct(
        protected ItemManagerInterface $itemManager,
        protected SerializerService $serializerService
    )
    {
    }
}
