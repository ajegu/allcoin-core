<?php


namespace AllCoinCore\Lambda\Event;


use DateTime;

class LambdaPriceSearchEvent implements LambdaEvent
{
    private string $pair;
    private DateTime $startAt;
    private DateTime $endAt;

    /**
     * @return string
     */
    public function getPair(): string
    {
        return $this->pair;
    }

    /**
     * @param string $pair
     */
    public function setPair(string $pair): void
    {
        $this->pair = $pair;
    }

    /**
     * @return DateTime
     */
    public function getStartAt(): DateTime
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     */
    public function setStartAt(DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return DateTime
     */
    public function getEndAt(): DateTime
    {
        return $this->endAt;
    }

    /**
     * @param DateTime $endAt
     */
    public function setEndAt(DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }


}
