<?php


namespace Test\Lambda;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Process\Binance\BinancePriceAnalyzerProcess;
use AllCoinCore\Lambda\BinancePriceAnalyzerLambda;
use Test\TestCase;

class BinancePriceAnalyzerLambdaTest extends TestCase
{
    private BinancePriceAnalyzerLambda $priceAnalyzerLambda;

    private BinancePriceAnalyzerProcess $assetPairPriceAnalyzerProcess;

    public function setUp(): void
    {
        $this->assetPairPriceAnalyzerProcess = $this->createMock(BinancePriceAnalyzerProcess::class);

        $this->priceAnalyzerLambda = new BinancePriceAnalyzerLambda(
            $this->assetPairPriceAnalyzerProcess
        );
    }

    /**
     * @throws ItemReadException
     * @throws NotificationHandlerException
     */
    public function testInvokeShouldBeOK(): void
    {
        $this->assetPairPriceAnalyzerProcess->expects($this->once())
            ->method('handle');

        $this->priceAnalyzerLambda->__invoke([]);
    }
}
