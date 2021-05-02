<?php


namespace AllCoinCore\Lambda\Handler;


use AllCoinCore\Exception\LambdaInvokeException;
use AllCoinCore\Lambda\Event\LambdaAssetListEvent;
use AllCoinCore\Lambda\LambdaEnum;
use AllCoinCore\Model\Asset;

class LambdaAssetHandler extends LambdaHandler
{
    /**
     * @return Asset[]
     * @throws LambdaInvokeException
     */
    public function invokeAssetList(): array
    {
        $event = new LambdaAssetListEvent();
        $data = $this->lambdaAdapter->invoke($event, LambdaEnum::ASSET_LIST);

        return array_map(function(array $payload) {
            return $this->serializerHelper->deserialize($payload, Asset::class);
        }, $data);
    }
}
