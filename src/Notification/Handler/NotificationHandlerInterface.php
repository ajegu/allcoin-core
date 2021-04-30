<?php


namespace AllCoinCore\Notification\Handler;


use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Model\EventInterface;

interface NotificationHandlerInterface
{
    /**
     * @param EventInterface $event
     * @throws NotificationHandlerException
     */
    public function dispatch(EventInterface $event): void;
}
