<?php


namespace AllCoinCore\Lambda;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Process\Binance\BinancePriceAnalyzerProcess;

class BinancePriceAnalyzerLambda implements LambdaInterface
{
    public function __construct(
        private BinancePriceAnalyzerProcess $assetPairPriceAnalyzerProcess
    )
    {
    }

    /**
     * @param array $event
     * @throws ItemReadException
     * @throws NotificationHandlerException
     */
    public function __invoke(array $event): void
    {
        $this->assetPairPriceAnalyzerProcess->handle();
    }
}
