<?php


namespace AllCoinCore\Lambda;


use AllCoinCore\Database\DynamoDb\Exception\ItemReadException;
use AllCoinCore\Process\Order\OrderReportProcess;

class OrderReportLambda implements LambdaInterface
{
    public function __construct(
        private OrderReportProcess $orderReportProcess
    )
    {
    }

    /**
     * @param array $event
     * @throws ItemReadException
     */
    public function __invoke(array $event): void
    {
        $this->orderReportProcess->handle();
    }

}
