<?php


namespace AllCoinCore\DataMapper;


use AllCoinCore\Model\EventInterface;

interface EventMapperInterface
{
    /**
     * @param string $data
     * @return EventInterface
     */
    public function mapJsonToEvent(string $data): EventInterface;
}
