<?php


namespace AllCoinCore\Lambda\Handler;


use AllCoinCore\Exception\LambdaInvokeException;
use AllCoinCore\Lambda\Event\LambdaPriceSearchEvent;
use AllCoinCore\Lambda\LambdaEnum;
use AllCoinCore\Model\Price;

class LambdaPriceHandler extends LambdaHandler
{
    /**
     * @param LambdaPriceSearchEvent $event
     * @return array
     * @throws LambdaInvokeException
     */
    public function invokePriceSearch(LambdaPriceSearchEvent $event): array
    {
        $data = $this->lambdaAdapter->invoke($event, LambdaEnum::PRICE_SEARCH);

        return array_map(function(array $payload) {
            return $this->serializerHelper->deserialize($payload, Price::class);
        }, $data);
    }
}
