<?php


namespace AllCoinCore\Notification\Handler;

use JetBrains\PhpStorm\Pure;

class OrderAnalyzerNotificationHandler extends AbstractNotificationHandler
{
    #[Pure] public function __construct(
        private string $topicArn,
        private SnsHandler $snsHandler
    )
    {
        parent::__construct(
            $this->topicArn,
            $this->snsHandler
        );
    }
}
