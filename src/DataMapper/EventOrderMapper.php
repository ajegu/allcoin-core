<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Model\EventInterface;
use AllCoinCore\Model\EventOrder;

class EventOrderMapper extends AbstractDataMapper implements EventMapperInterface
{
    public function mapJsonToEvent(string $data): EventOrder|EventInterface
    {
        return $this->serializerService->deserializeToEvent($data, EventOrder::class);
    }
}
