<?php


namespace Test\Notification\Handler;


use AllCoinCore\Exception\NotificationHandlerException;
use AllCoinCore\Model\EventInterface;
use AllCoinCore\Notification\Handler\OrderAnalyzerNotificationHandler;
use AllCoinCore\Notification\Handler\SnsHandler;
use Test\TestCase;

class OrderAnalyzerNotificationHandlerTest extends TestCase
{
    private OrderAnalyzerNotificationHandler $orderAnalyzerNotificationHandler;

    private string $topicArn;
    private SnsHandler $snsHandler;

    public function setUp(): void
    {
        $this->topicArn = 'foo';
        $this->snsHandler = $this->createMock(SnsHandler::class);

        $this->orderAnalyzerNotificationHandler = new OrderAnalyzerNotificationHandler(
            $this->topicArn,
            $this->snsHandler
        );
    }

    /**
     * @throws NotificationHandlerException
     */
    public function testDispatchShouldBeOK(): void
    {
        $event = $this->createMock(EventInterface::class);

        $this->snsHandler->expects($this->once())
            ->method('publish')
            ->with($event, $this->topicArn);

        $this->orderAnalyzerNotificationHandler->dispatch($event);
    }
}
