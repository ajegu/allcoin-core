<?php


namespace Test\Helper;


use AllCoinCore\Exception\DateTimeHelperException;
use AllCoinCore\Helper\DateTimeHelper;
use DateTime;
use Psr\Log\LoggerInterface;
use Test\TestCase;

class DateTimeHelperTest extends TestCase
{
    private DateTimeHelper $dateTimeService;

    public function setUp(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $this->dateTimeService = new DateTimeHelper(
            $logger
        );
    }

    public function testNowShouldBeOK(): void
    {
        $this->assertNotEmpty(
            $this->dateTimeService->now()
        );
    }

    public function testSubWithErrorShouldThrowException(): void
    {
        $dateTime = new DateTime();
        $duration = 'foo';

        $this->expectException(DateTimeHelperException::class);

        $this->dateTimeService->sub($dateTime, $duration);
    }

    public function testSubShouldBeOK(): void
    {
        $dateTime = new DateTime();
        $duration = 'P1D';

        $dateTimeSub = $this->dateTimeService->sub($dateTime, $duration);

        $this->assertLessThan(
            $dateTime->getTimestamp(),
            $dateTimeSub->getTimestamp()
        );
    }
}
