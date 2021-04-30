<?php


namespace Test\Lambda;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Process\Binance\BinanceOrderAnalyzerProcess;
use AllCoinCore\Lambda\BinanceOrderAnalyzerLambda;
use Test\TestCase;

class BinanceOrderAnalyzerLambdaTest extends TestCase
{
    private BinanceOrderAnalyzerLambda $binanceOrderAnalyzerLambda;

    private BinanceOrderAnalyzerProcess $binanceOrderAnalyzerProcess;

    public function setUp(): void
    {
        $this->binanceOrderAnalyzerProcess = $this->createMock(BinanceOrderAnalyzerProcess::class);

        $this->binanceOrderAnalyzerLambda = new BinanceOrderAnalyzerLambda(
            $this->binanceOrderAnalyzerProcess
        );
    }

    /**
     * @throws ItemReadException
     * @throws NotificationHandlerException
     */
    public function testInvokeShouldBeOK(): void
    {
        $this->binanceOrderAnalyzerProcess->expects($this->once())
            ->method('handle');

        $this->binanceOrderAnalyzerLambda->__invoke([]);
    }
}
