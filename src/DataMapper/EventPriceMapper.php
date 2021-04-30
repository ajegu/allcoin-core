<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Model\EventInterface;
use AllCoinCore\Model\EventPrice;

class EventPriceMapper extends AbstractDataMapper implements EventMapperInterface
{
    public function mapJsonToEvent(string $data): EventPrice|EventInterface
    {
        return $this->serializerService->deserializeToEvent($data, EventPrice::class);
    }
}
