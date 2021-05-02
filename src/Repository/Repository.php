<?php


namespace AllCoinCore\Repository;


use AllCoinCore\Database\DynamoDb\ItemManagerInterface;
use AllCoinCore\Helper\SerializerHelper;

abstract class Repository
{
    public function __construct(
        protected ItemManagerInterface $itemManager,
        protected SerializerHelper $serializer,
    )
    {
    }
}
