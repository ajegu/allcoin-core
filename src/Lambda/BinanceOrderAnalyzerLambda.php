<?php


namespace AllCoinCore\Lambda;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Process\Binance\BinanceOrderAnalyzerProcess;

class BinanceOrderAnalyzerLambda implements LambdaInterface
{
    public function __construct(
        private BinanceOrderAnalyzerProcess $binanceOrderAnalyzerProcess
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
        $this->binanceOrderAnalyzerProcess->handle();
    }

}
